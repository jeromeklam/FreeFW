<?php
namespace FreeFW\Tools\Vad\PayBox;

use FreeFW\Validators\AbstractValidator;

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
     * Hash
     * @var string
     */
    const HASH_512 = 'SHA512';
    const HASH_256 = 'SHA256';

    /**
     * Constantes des langues
     * @var string
     */
    const LANG_FR = 'FRA';

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
    private $hash_algo = self::HASH_512;

    /**
     * HashMac Key
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
                $this->env = self::ENV_PRODUCTION;
            } else {
                $this->env = self::ENV_TEST;
            }
        } else {
            throw new \InvalidArgumentException('PayBox env parameter missing !');
        }
        if (array_key_exists('currency', $p_config)) {
            $code = \FreeFW\Tools\ISO4217::getAsNumeric($p_config['currency']);
            if ($code === false) {
                throw new \InvalidArgumentException('PayBox currency parameter ISO4217 value not found !');
            }
            $this->fixed_parameters['PBX_DEVISE'] = $code;
        } else {
            throw new \InvalidArgumentException('PayBox currency parameter missing !');
        }
        if (array_key_exists('site', $p_config)) {
            $this->fixed_parameters['PBX_SITE'] = $p_config['site'];
        } else {
            throw new \InvalidArgumentException('PayBox site parameter missing !');
        }
        if (array_key_exists('rank', $p_config)) {
            $this->fixed_parameters['PBX_RANG'] = $p_config['rank'];
        } else {
            throw new \InvalidArgumentException('PayBox rank parameter missing !');
        }
        if (array_key_exists('login', $p_config)) {
            $this->fixed_parameters['PBX_IDENTIFIANT'] = $p_config['login'];
        } else {
            throw new \InvalidArgumentException('PayBox login parameter missing !');
        }
        if (array_key_exists('hmac', $p_config)) {
            $this->hash_key = $p_config['hmac'];
        } else {
            throw new \InvalidArgumentException('PayBox hmac parameter missing !');
        }
        if (array_key_exists('urls', $p_config)) {
            $this->urls = $p_config['urls'];
            foreach ($this->urls as $key => $value) {
                switch (strtoupper($key)) {
                    case 'REPONDRE_A':
                        $this->fixed_parameters['PBX_REPONDRE_A'] = urlencode($value);
                        break;
                    case 'EFFECTUE':
                        $this->fixed_parameters['PBX_EFFECTUE'] = urlencode($value);
                        break;
                    case 'REFUSE':
                        $this->fixed_parameters['PBX_REFUSE'] = urlencode($value);
                        break;
                    case 'ANNULE':
                        $this->fixed_parameters['PBX_ANNULE'] = urlencode($value);
                        break;
                }
            }
        }
        $this->fixed_parameters['PBX_HASH']   = $this->hash_algo;
        $this->fixed_parameters['PBX_RETOUR'] = 'montant:M;maref:R;auto:A;paiement:P;carte:C;transaction:S;erreur:E';
        $this->fixed_parameters['PBX_LANGUE'] = self::LANG_FR;
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
     * Computes the hmac hash.
     *
     * @return string
     */
    protected function computeHmac()
    {
        // TIME
        $this->request_parameters['PBX_TIME'] = \FreeFW\Tools\ISO8601::getCurrentDateTime();
        if (isset($this->request_parameters['PBX_HMAC'])) {
            unset($this->request_parameters['PBX_HMAC']);
        }
        // Merge parameters
        $parameters = array_merge($this->fixed_parameters, $this->request_parameters);
        ksort($parameters);
        // Hash Mac
        $binKey = pack('H*', $this->hash_key);
        $hash   = hash_hmac(
            $this->hash_algo,
            \FreeFW\Tools\PBXArray::stringify($parameters),
            $binKey
        );
        $this->request_parameters['PBX_HMAC'] = strtoupper($hash);
        return $this;
    }

    /**
     * @see \FreeFW\Validators\AbstractModelValidator
     */
    protected function validate()
    {
        return true;
    }

    /**
     * @see \FreeFW\Tools\Vad\VadInterface
     */
    public function setParameters(array $p_params = array())
    {
        $this->request_parameters = [];
        foreach ($p_params as $key => $value) {
            switch (strtoupper($key)) {
                case self::CST_PARAM_AMOUNT:
                    if (is_numeric($value)) {
                        $mttc = round($value * 100, 0);
                        $this->request_parameters['PBX_TOTAL'] = number_format($mttc, 0, '.', '');
                    } else {
                        throw new \InvalidArgumentException('PayBox total amount is incorrect !');
                    }
                    break;
                case self::CST_PARAM_EMAIL:
                    if (\FreeFW\Tools\Email::verifyFormatting($value)) {
                        $this->request_parameters['PBX_PORTEUR'] = $value;
                    } else {
                        throw new \InvalidArgumentException('PayBox email is incorrect !');
                    }
                    break;
                case self::CST_PARAM_CMD:
                    if (trim($value) != '') {
                        $this->request_parameters['PBX_CMD'] = trim($value);
                    } else {
                        throw new \InvalidArgumentException('PayBox cmd is incorrect !');
                    }
                    break;
            }
        }
        return $this;
    }

    /**
     * @see \FreeFW\Tools\Vad\VadInterface
     */
    public function getForm($p_html_model)
    {
        $form   = '';
        if ($this->isValid()) {
            $this->computeHmac();
            $action = 'https://preprod-tpeweb.paybox.com/cgi/MYchoix_pagepaiement.cgi';
            $method = 'POST';
            if ($this->env == self::ENV_PRODUCTION) {
                $action = '';
            }
            $fields = array_merge($this->fixed_parameters, $this->request_parameters);
            ksort($fields);
            $hidden = "";
            foreach ($fields as $name => $value) {
                if ($name != 'PBX_HMAC') {
                    $hidden .= '<input type="hidden" name="' . $name . '" value="' . $value . '" />' . PHP_EOL;
                }
            }
            $hidden .= '<input type="hidden" name="PBX_HMAC" value="' . $fields['PBX_HMAC']. '" />' . PHP_EOL;
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
