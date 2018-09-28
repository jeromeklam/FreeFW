<?php
namespace FreeFW\Client;

/**
 * Classe client Soap
 * @author jerome.klam
 */
class Soap
{

    /**
     * Comportements
     */
    use \FreeFW\Behaviour\LoggerAwareTrait;
    use \FreeFW\Behaviour\DI;

    /**
     * Instance
     * @var unknown
     */
    protected static $instance = null;

    /**
     * Fichier de config
     * @var string
     */
    protected $wsdl = null;

    /**
     * Client SOAP
     * @var SoapClient
     */
    protected $client = null;

    /**
     * Constructeur
     *
     * @param array $p_config
     */
    protected function __construct($p_config = array())
    {
        ini_set('soap.wsdl_cache_enabled', 0);
        //
        $params = array (
            'verifypeer' => false,
            'verifyhost' => false,
            'trace' => 1,
            'exceptions' => 1,
            'connection_timeout' => 180
        );
        $this->wsdl   = $p_config['wsdl'];
        try {
            $this->client = new \SoapClient($this->wsdl, $params);
        } catch (\Exception $ex) {
            //var_dump($ex);die;
            self::error(print_r($ex, true));
            self::error(print_r($p_config, true));
            throw $ex;
        }
    }

    /**
     * Instance du client SOAP
     *
     * @param array $p_config
     *
     * @return \FreeFW\Client\Soap
     */
    public static function getInstance($p_config = array())
    {
        if (self::$instance == null) {
            self::$instance = new static($p_config);
        }
        return self::$instance;
    }

    /**
     * Call ws function
     *
     * @param string $p_function
     * @param array   $p_params
     *
     * @return mixed
     */
    public function call($p_function, $p_params = array())
    {
        $result      = false;
        $max_retries = 5;
        $retry_count = 0;
        while (!$result && $retry_count < $max_retries) {
            try {
                $result = call_user_func_array(array($this->client, $p_function), $p_params);
            } catch (\SoapFault $fault) {
                if ($fault->faultstring != 'Could not connect to host') {
                    throw $fault;
                }
            }
            sleep(1);
            $retry_count++;
        }
        if ($retry_count == $max_retries) {
            throw new \SoapFault('Could not connect to host after 5 attempts');
        }
        return $result;
    }
}
