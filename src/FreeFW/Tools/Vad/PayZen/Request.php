<?php
namespace FreeFW\Tools\Vad\PayZen;

/**
 *
 * @author jeromeklam
 *
 */
class Request extends \FreeFW\Validators\AbstractModelValidator implements \FreeFW\Tools\Vad\VadInterface
{

    /**
     * Constantes
     * @var string
     */
    const ENV_TEST       = 'TEST';
    const ENV_PRODUCTION = 'PRODUCTION';

    /**
     * Algos
     * @var string
     */
    const ALGO_SHA1   = 'SHA-1';
    const ALGO_SHA256 = 'SHA-256';

    /**
     * Instance
     * @var \FreeFW\Tools\Vad\PayBox\Request
     */
    protected static $instance = null;

    /**
     * Paramètres fixés pour caque requête
     * @var array
     */
    private $fixed_parameters = [];

    /**
     * Paramètres propres à chaque appel
     * @var array
     */
    private $request_parameters = [];

    /**
     * Environnement
     * @var string
     */
    private $env = self::ENV_TEST;

    /**
     * Hash algorythm
     * @var string
     */
    private $hash_algo = self::ALGO_SHA1;

    /**
     * Hash key
     * @var string
     */
    private $hash_key = '';

    /**
     * Urls
     * @var array
     */
    private $urls = [];

    /**
     * Constructeur
     *
     * @param array $p_config
     */
    protected function __construct($p_config = array())
    {
        if (array_key_exists('env', $p_config)) {
            if (strtoupper($p_config['env']) == 'PRODUCTION') {
                $this->env                               = self::ENV_PRODUCTION;
                $this->fixed_parameters['vads_ctx_mode'] = 'PRODUCTION';
            } else {
                $this->env                               = self::ENV_TEST;
                $this->fixed_parameters['vads_ctx_mode'] = 'TEST';
            }
        } else {
            throw new \InvalidArgumentException('PayZen env parameter missing !');
        }
        if (array_key_exists('currency', $p_config)) {
            $code = \FreeFW\Tools\ISO4217::getAsNumeric($p_config['currency']);
            if ($code === false) {
                throw new \InvalidArgumentException('PayZen currency parameter ISO4217 value not found !');
            }
            $this->fixed_parameters['vads_currency'] = $code;
        } else {
            throw new \InvalidArgumentException('PayBox currency parameter missing !');
        }
        if (array_key_exists('site', $p_config)) {
            $this->fixed_parameters['vads_site_id'] = $p_config['site'];
        } else {
            throw new \InvalidArgumentException('PayBox site parameter missing !');
        }
        if (array_key_exists('key', $p_config)) {
            $this->hash_key = $p_config['key'];
        } else {
            throw new \InvalidArgumentException('PayZen hmac parameter missing !');
        }
        if (array_key_exists('urls', $p_config)) {
            $this->urls = $p_config['urls'];
            foreach ($this->urls as $key => $value) {
                switch (strtoupper($key)) {
                    case 'REPONDRE_A':
                        $this->fixed_parameters['vads_url_check'] = $value;
                        break;
                    case 'EFFECTUE':
                        $this->fixed_parameters['vads_url_success'] = $value;
                        break;
                    case 'REFUSE':
                        $this->fixed_parameters['vads_url_error'] = $value;
                        break;
                    case 'ANNULE':
                        $this->fixed_parameters['vads_url_cancel'] = $value;
                        break;
                }
            }
        }
        $this->fixed_parameters['vads_page_action']    = 'PAYMENT';
        $this->fixed_parameters['vads_action_mode']    = 'INTERACTIVE';
        $this->fixed_parameters['vads_payment_config'] = 'SINGLE';
        $this->fixed_parameters['vads_version']        = 'V2';
    }

    /**
     * Retourne une instance de classe
     *
     * @param array $p_config
     *
     * @return \FreeFW\Tools\Vad\PayBox\Request
     */
    public static function getInstance($p_config = null)
    {
        if (self::$instance === null) {
            self::$instance = new static($p_config);
        }
        return self::$instance;
    }
    /**
     * Compute a PayZen signature. Parameters must be in UTF-8.
     *
     * @param array[string][string] $parameters payment platform request/response parameters
     * @param string $key shop certificate
     * @param string $algo signature algorithm
     * @param boolean $hashed set to false to get the unhashed signature
     * @return string
     */
    protected function sign()
    {
        $sign       = '';
        $parameters = array_merge($this->fixed_parameters, $this->request_parameters);
        ksort($parameters);
        foreach ($parameters as $name => $value) {
            if (strpos($name, 'vads_') == 0) {
                $sign .= $value . '+';
            }
        }
        $sign .= $this->hash_key;
        switch ($this->hash_algo) {
            case self::ALGO_SHA1:
                $this->request_parameters['signature'] = sha1($sign);
                break;
            case self::ALGO_SHA256:
                $this->request_parameters['signature'] = base64_encode(hash_hmac('sha256', $sign, $this->hash_key, true));
                break;
            default:
                throw new \InvalidArgumentException("Unsupported algorithm passed : {$algo}.");
        }
    }

    /**
     * @see \FreeFW\Validators\AbstractModelValidator
     */
    protected function validate()
    {
        return true;
    }

    /**
     * Set request parameters
     *
     * @param array $p_params
     *
     * @return static
     */
    public function setParameters(array $p_params = array())
    {
        $this->request_parameters = [];
        $nbEcheance               = 1;
        foreach ($p_params as $key => $value) {
            switch (strtoupper($key)) {
                case self::CST_PARAM_AMOUNT:
                    if (is_numeric($value)) {
                        $mttc = round($value * 100, 0);
                        $this->request_parameters['vads_amount'] = number_format($mttc, 0, '.', '');
                    } else {
                        throw new \InvalidArgumentException('PayZen total amount is incorrect !');
                    }
                    break;
                case self::CST_PARAM_EMAIL:
                    if (\FreeFW\Tools\Email::verifyFormatting($value)) {
                        $this->request_parameters['vads_cust_email'] = $value;
                    } else {
                        throw new \InvalidArgumentException('PayZen email is incorrect !');
                    }
                    break;
                case self::CST_PARAM_CMD:
                    if (trim($value) != '') {
                        $this->request_parameters['vads_order_id'] = trim($value);
                    } else {
                        throw new \InvalidArgumentException('PayZen cmd is incorrect !');
                    }
                    break;
                case self::CST_PARAM_MODE:
                    if (trim($value) != '' && intval($value) > 0) {
                        $nbEcheance = intval($value);
                    } else {
                        throw new \InvalidArgumentException('PayZen cmd is incorrect !');
                    }
                    break;
                case self::CST_CUST_ID:
                    if (trim($value) != '') {
                        $this->request_parameters['vads_cust_id'] = trim($value);
                    }
                    break;
            }
        }
        if ($nbEcheance > 1) {
            // Je dois compléter le champ avec certaines infos...
            $nbDays = 31;
            if (array_key_exists(self::CST_PARAM_DAYS, $p_params)) {
                $nbDays = intval($p_params[self::CST_PARAM_DAYS]);
            }
            $add   = ':first=';
            $first = floor($this->request_parameters['vads_amount'] / $nbEcheance);
            $add  .= $first . ';';
            $add  .= ':count=' . $nbEcheance . ';';
            $add  .= ':period=' . $nbDays . ';';
            $this->request_parameters['vads_payment_config'] = 'MULTI' . $add;
        } else {
            $this->request_parameters['vads_payment_config'] = 'SINGLE';
        }
        $this->request_parameters['vads_trans_id']   = gmdate('His');
        $this->request_parameters['vads_trans_date'] = gmdate('YmdHis');
        return $this;
    }

    /**
     * Return form
     *
     * @param string $p_html_model
     *
     * @return string
     */
    public function getForm($p_html_model)
    {
        $form = '';
        if ($this->isValid()) {
            $action = 'https://secure.payzen.eu/vads-payment/';
            $method = 'POST';
            if ($this->env == self::ENV_PRODUCTION) {
                $action = 'https://secure.payzen.eu/vads-payment/';
            }
            $this->sign();
            $fields = array_merge($this->fixed_parameters, $this->request_parameters);
            ksort($fields);
            $hidden = "";
            foreach ($fields as $name => $value) {
                if ($name != 'signature') {
                    $hidden .= '<input type="hidden" name="' . $name . '" value="' . $value . '" />' . PHP_EOL;
                }
            }
            $hidden .= '<input type="hidden" name="signature" value="' . $fields['signature']. '" />' . PHP_EOL;
            $form = \FreeFW\Tools\PBXString::parse(
                $p_html_model,
                [
                    'action'  => $action,
                    'method'  => $method,
                    'content' => $hidden
                ]
            );
        }
        return $form;
    }
}
