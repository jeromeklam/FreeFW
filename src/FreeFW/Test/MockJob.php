<?php
/**
 * Classe mock d'un Jobqueue
 *
 * @author jeromeklam
 * @package Mock
 * @category Mock
 */
namespace FreeFW\Test;

/**
 * Classe mock d'un Jobqueue
 * @author jeromeklam
 */
class MockJob extends \FreeFW\Model\AbstractNoStorage implements \FreeFW\Interfaces\Job
{

    /**
     *
     * @var unknown $params
     */
    protected $params = array();

    /**
     * Status
     *
     * @var string
     */
    protected $status = self::STATUS_WAITING;

    /**
     * Messages
     *
     * @var string
     */
    protected $text = null;

    /**
     * Messages
     *
     * @var mixed
     */
    protected $message = null;

    /**
     * Affectation des paramètres
     *
     * @param array $p_params
     *
     * @return \FreeFW\Model\MockJob
     */
    public function setParams($p_params)
    {
        $this->params = $p_params;
        
        return $this;
    }

    /**
     * Get params as array
     *
     * @return array
     */
    public function getParamsAsArray()
    {
        return $this->params;
    }

    /**
     * Ajout d'un message
     *
     * @param mixed $p_message
     *
     * @return \FreeFW\Interfaces\Job
     */
    public function addMessage($p_message)
    {
        if ($this->message === null || $this->message == '') {
            $this->message = $p_message;
        } else {
            $this->message .= PHP_EOL . $p_message;
        }
        
        return $this;
    }

    /**
     * Passage en erreur
     *
     * @param string $p_message
     *
     * @return \FreeFW\Interface\Job
     */
    public function setError($p_message = 'general error')
    {
        $this->status = self::STATUS_ERROR;
        
        return $this;
    }

    /**
     * Job fini
     *
     * &return \FreeFW\Interface\Job
     */
    public function setFinished()
    {
        $this->status = self::STATUS_FINISH;
        
        return $this;
    }
}
