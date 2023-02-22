<?php
namespace FreeFW\Log;

/**
 * File logger
 */
class FileLogger extends \Psr\Log\AbstractLogger implements \Serializable
{

    /**
     * Taille maxi des lmogs en octets
     * @var number
     */
    const MAXLOGSIZE = 10000000;

    /**
     * local pid
     * @var string
     */
    protected static $pid = null;

    /**
     * Par défaut juste les erreurs
     * @var mixed
     */
    protected $level = \Psr\Log\LogLevel::ERROR;

    /**
     * Nom du fichier
     * @var string
     */
    protected $file = null;

    /**
     * Enregistrement cache
     * @var boolean
     */
    protected $cache = true;

    /**
     * Tableau des logs
     * @var array
     */
    protected $tabCache = array();

    /**
     * Tableau des logs filebeat
     * @var array
     */
    protected $tabBeat = array();

    /**
     * Constructeur
     *
     * @param string  $p_file
     * @param mixed   $p_level
     * @param boolean $p_cache
     */
    public function __construct($p_file, $p_level = \Psr\Log\LogLevel::ERROR, $p_cache = true)
    {
        $this->level = $p_level;
        $this->file  = $p_file;
        if (self::$pid === null) {
            self::$pid = getmypid() . '.' . md5(uniqid());
        }
        $this->cache = $p_cache;
    }

    /**
     * Enregistrement si cache actif
     */
    public function __destruct()
    {
        if ($this->cache) {
            $this->commit();
        }
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        // On ne logge qu'en fonction du niveau souhaité
        if (self::getLevelAsInt($level) <= self::getLevelAsInt($this->level)) {
            $myMsg = \FreeFW\Tools\PBXString::parse($message, $context);
            $this->write($myMsg, $level);
        }
        if ($level >= \Psr\Log\LogLevel::ERROR) {
            // send alert to monitoring...
            // APP_NAME, server name, broker...
        }
    }

    /**
     * ^Get client IP
     *
     * @return string
     */
    public static function getClientIp()
    {
        //Just get the headers if we can or else use the SERVER global
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
        } else {
            $headers = $_SERVER;
        }
        //Get the forwarded IP if it exists
        if (isset($headers['X-Forwarded-For']) &&
            filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $the_ip = $headers['X-Forwarded-For'];
        } else {
            if (isset($headers['HTTP_X_FORWARDED_FOR']) &&
                filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
            } else {
                if (isset($headers['X-ClientSide'])) {
                    $parts  = explode(':', $headers['X-ClientSide']);
                    $the_ip = $parts[0];
                } else {
                    $the_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
                }
            }
        }
        return $the_ip;
    }

    /**
     * Ecriture du message
     *
     * @var string $message
     *
     * @return \FreeFW\Log\FileLogger
     */
    protected function write($message, $level = false)
    {
        $now  = \DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
        $now->setTimeZone(new \DateTimeZone('Europe/Paris'));
        $line = array(
            'pid'  => self::$pid,
            'time' => $now->format("d/m/Y H:i:s.u")
        );
        if ($level !== false) {
            $line['$level'] = $level;
        } else {
            $line['$level'] = '........';
        }
        $line['mesg'] = $message;
        if (!$this->cache) {
            $this->flush();
        }
        $this->tabCache[] = $line;
        try {
            $params = [
                'appCode'    => APP_NAME,
                'appClient'  => '',
                'appSession' => session_id(),
                'appTs'      => microtime(),
                'appDate'    => date('c'),
                'appType'    => $level,
                'appErrNum'  => 0,
                'appErrMsg'  => (string)$message,
                'appErrFile' => '',
                'appErrLine' => 0,
                'appData'    => []
            ];
            if (isset($_SERVER) && isset($_SERVER['REQUEST_URI'])) {
                $params['requestUri'] = $_SERVER['REQUEST_URI'];
                $params['requestIp']  = self::getClientIp();
                if (isset($_SERVER['HTTP_USER_AGENT'])) {
                    $params['requestUA']  = $_SERVER['HTTP_USER_AGENT'];
                }
                if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                    $params['requestLg'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
                }
            }
            $this->tabBeat[] = $params;
        } catch (\Exception $ex) {
            // @nothing
        }
        if (!$this->cache) {
            $this->commit();
        }
        return $this;
    }

    /**
     * Purge du cache
     *
     * @return \FreeFW\Log\FileLogger
     */
    protected function flush()
    {
        $this->tabCache = array();
        $this->tabBeat  = array();
        return $this;
    }

    /**
     * Retourne le niveau de log
     *
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Enregistrement du cache
     *
     * @return \FreeFW\Log\FileLogger
     */
    protected function commit()
    {
        $rr = false;
        $fg = 'a+';
        if (is_file($this->file)) {
            $size = filesize($this->file);
            if ($size > self::MAXLOGSIZE) {
                if (is_file($this->file . '.1')) {
                    unlink($this->file . '.1');
                }
                rename($this->file, $this->file . '.1');
                $rr = true;
            }
        } else {
            $rr = true;
        }
        if (is_file(APP_LOG . '/' . APP_NAME . '.log')) {
            $size = filesize(APP_LOG . '/' . APP_NAME . '.log');
            if ($size > self::MAXLOGSIZE) {
                if (is_file(APP_LOG . '/' . APP_NAME . '.log.1')) {
                    unlink(APP_LOG . '/' . APP_NAME . '.log.1');
                }
                rename(APP_LOG . '/' . APP_NAME . '.log', APP_LOG . '/' . APP_NAME . '.log.1');
            }
        }
        if ($rr) {
            touch($this->file);
            chmod($this->file, 0666);
        }
        $content = '';
        while (null !== ($line = array_shift($this->tabCache))) {
            $content .= implode('  ---  ', $line) . PHP_EOL;
        }
        file_put_contents($this->file, $content, FILE_APPEND);
        return $this;
    }

    /**
     * Return logLevel as int
     *
     * @param string $p_level
     *
     * @return number
     */
    protected static function getLevelAsInt($p_level)
    {
        switch ($p_level) {
            case \Psr\Log\LogLevel::DEBUG:
                return 9;
            case \Psr\Log\LogLevel::INFO:
                return 8;
            case \Psr\Log\LogLevel::ALERT:
                return 7;
            case \Psr\Log\LogLevel::NOTICE:
                return 6;
            case \Psr\Log\LogLevel::WARNING:
                return 5;
            case \Psr\Log\LogLevel::ERROR:
                return 3;
            case \Psr\Log\LogLevel::CRITICAL:
                return 2;
            case \Psr\Log\LogLevel::EMERGENCY:
                return 1;
        }
        return 0;
    }

    /**
     * @see \Serializable 
     */
    public function serialize() {
        $this->commit();
        $datas = [
            'file' => $this->file,
            'cache' => $this->cache,
            'tab' => $this->tabCache,
        ];
        return serialize($datas);
    }

    /**
     * @see \Serializable 
     */
    public function unserialize($data) {
        $datas = unserialize($data);
        $this->tabCache = $datas['tab'];
        $this->file = $datas['file'];
        $this->cache = $datas['cache'];
    }
}
