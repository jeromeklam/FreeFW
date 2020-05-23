<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Jobqueue
 *
 * @author jeromeklam
 */
class Jobqueue extends \FreeFW\Model\Base\Jobqueue implements \Psr\Log\LoggerInterface
{

    /**
     * Types
     * @var string
     */
    const TYPE_ONCE = 'ONCE';
    const TYPE_LOOP = 'LOOP';

    /**
     * Status
     * @var string
     */
    const STATUS_WAITING  = 'WAITING';
    const STATUS_PENDING  = 'PENDING';
    const STATUS_FINISHED = 'FINISHED';
    const STATUS_ERROR    = 'ERROR';
    const STATUS_RETRY    = 'RETRY';

    /**
     * Prevent from saving history
     * @var bool
     */
    protected $no_history = true;

    /**
     * User
     * @var \FreeSSO\Model\User
     */
    protected $user = null;

    /**
     * Group
     * @var \FreeSSO\Model\Group
     */
    protected $group = null;

    /**
     * Report in cache...
     * @var boolean
     */
    protected $cache = true;

    /**
     * logs
     * @var array
     */
    protected $logs = [];

    /**
     * Par défaut tout
     * @var mixed
     */
    protected $level = \Psr\Log\LogLevel::DEBUG;

    /**
     * Enregistrement si cache actif
     */
    public function __destruct()
    {
        if ($this->cache) {
            $this->saveLogs();
        }
    }

    /**
     * Set user
     *
     * @param \FreeSSO\Model\User $p_user
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function setUser($p_user)
    {
        $this->user = $p_user;
        return $this;
    }

    /**
     * Get user
     *
     * @return \FreeSSO\Model\User
     */
    public function getuser()
    {
        return $this->user;
    }

    /**
     * Set group
     *
     * @param \FreeSSO\Model\Group $p_group
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function setGroup($p_group)
    {
        $this->group = $p_group;
        return $this;
    }

    /**
     * Get group
     *
     * @return \FreeSSO\Model\Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Save logs
     */
    public function saveLogs()
    {
        if (is_array($this->logs) && count($this->logs) > 0) {
            $text = '';
            foreach ($this->logs as $oneLog) {
                $text .= $oneLog['message'] . PHP_EOL;
            }
            $this
                ->setJobqLastReport($text)
                ->save()
            ;
        }
        $this->logs = [];
    }

    /**
     * Increment nb try
     *
     * @param number $p_nb
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function incrementTry($p_nb = 1)
    {
        $this->jobq_nb_retry   = $this->jobq_nb_retry + $p_nb;
        $this->jobq_next_retry = \FreeFW\Tools\Date::getCurrentTimestamp(30);
        return $this;
    }

    /**
     * Can continue ?
     *
     * @return boolean
     */
    public function canContinue()
    {
        return ($this->jobq_nb_retry < $this->jobq_max_retry);
    }

    /**
     * Set finished
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function finished()
    {
        if ($this->getJobqType() === self::TYPE_ONCE) {
            $this->setJobqStatus(self::STATUS_FINISHED);
        } else {
            $this
                ->setJobqStatus(self::STATUS_WAITING)
                ->setJobqNextRetry(\FreeFW\Tools\Date::getCurrentTimestamp($this->getJobqNextMinutes()))
            ;
        }
        return $this;
    }

    /**
     * Execute jobqueue
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function run()
    {
        try {
            $this
                ->setJobqStatus(self::STATUS_PENDING)
                ->setJobqLastReport(null)
                ->save()
            ;
            /**
             *
             * @var \FreeFW\Core\Service $service
             */
            $service = \FreeFW\DI\DI::get($this->getJobqService());
            if ($service) {
                $method = $this->getJobqMethod();
                if (method_exists($service, $method)) {
                    $params = json_decode($this->getJobqParams(), true);
                    if (!is_array($params)) {
                        $params = [];
                    }
                    $service->setLogger($this);
                    $result = call_user_func_array([$service, $method], ['params' => $params]);
                    if ($result === false) {
                        $this
                            ->setJobqStatus(self::STATUS_ERROR)
                            ->setJobqLastReport('Unknown method in service !')
                            ->save()
                        ;
                    } else {
                        if (is_array($result)) {
                            $this->setJobqParams(json_encode($result));
                        }
                        $this
                            ->finished()
                            ->setJobqLastReport($result)
                            ->setJobqNbRetry(0)
                            ->save()
                        ;
                    }
                } else {
                    $this
                        ->setJobqStatus(self::STATUS_ERROR)
                        ->setJobqLastReport('Unknown method in service !')
                        ->save()
                    ;
                }
            } else {
                $this
                    ->setJobqStatus(self::STATUS_ERROR)
                    ->setJobqLastReport('Unknown service !')
                    ->save()
                ;
            }
        } catch (\Exception $ex) {
            if ($this->canContinue()) {
                $this->setJobqStatus(self::STATUS_RETRY);
            } else {
                $this->setJobqStatus(self::STATUS_ERROR);
            }
            $this
                ->setJobqLastReport(print_r($ex, true))
                ->incrementTry()
                ->save()
            ;
        }
        return $this;
    }

    /**
     * Reset jobqueue
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function reset()
    {
        try {
            $this
                ->setJobqStatus(self::STATUS_WAITING)
                ->save()
            ;
        } catch (\Exception $ex) {
            // @TODO
        }
        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function beforeSave()
    {
        $this->setJobqLastTs(\FreeFW\Tools\Date::getCurrentTimestamp());
        return true;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Psr\Log\LoggerInterface::critical()
     */
    public function critical($message, array $context = [])
    {
        $this->log(\Psr\Log\LogLevel::CRITICAL, $message, $context);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Psr\Log\LoggerInterface::log()
     */
    public function log($level, $message, array $context = [])
    {
        $this->logs[] = [
            'level' => $level,
            'message' => print_r($message, true)
        ];
        if (!$this->cache) {
            $this->saveLogs();
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see \Psr\Log\LoggerInterface::emergency()
     */
    public function emergency($message, array $context = [])
    {
        $this->log(\Psr\Log\LogLevel::EMERGENCY, $message, $context);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Psr\Log\LoggerInterface::error()
     */
    public function error($message, array $context = [])
    {
        $this->log(\Psr\Log\LogLevel::ERROR, $message, $context);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Psr\Log\LoggerInterface::alert()
     */
    public function alert($message, array $context = [])
    {
        $this->log(\Psr\Log\LogLevel::ALERT, $message, $context);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Psr\Log\LoggerInterface::warning()
     */
    public function warning($message, array $context = [])
    {
        $this->log(\Psr\Log\LogLevel::WARNING, $message, $context);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Psr\Log\LoggerInterface::notice()
     */
    public function notice($message, array $context = [])
    {
        $this->log(\Psr\Log\LogLevel::NOTICE, $message, $context);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Psr\Log\LoggerInterface::info()
     */
    public function info($message, array $context = [])
    {
        $this->log(\Psr\Log\LogLevel::INFO, $message, $context);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Psr\Log\LoggerInterface::debug()
     */
    public function debug($message, array $context = [])
    {
        $this->log(\Psr\Log\LogLevel::DEBUG, $message, $context);
    }

    /**
     * Add to history
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function addToHistory()
    {
        /**
         *
         * @var \FreeFW\Model\JobqueueHisto $histo
         */
        $histo = \FreeFW\DI\DI::get('FreeFW::Model::JobqueueHisto');
        $histo
            ->setJobqhTs($this->getJobqTs())
            ->setJobqhMsg($this->getJobqLastReport())
            ->setJobqId($this->getJobqId())
            ->setJobqhStatus($this->getJobqStatus())
        ;
        $histo->create();
        return $this;
    }
}
