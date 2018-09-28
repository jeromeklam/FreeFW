<?php
namespace FreeFW\Tools\Vad\Tipi;

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
    const ENV_ACTIVATION = 'ACTIVATION';
    const ENV_PRODUCTION = 'PRODUCTION';

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
                $this->env                        = self::ENV_PRODUCTION;
                $this->fixed_parameters['saisie'] = 'M';
            } else {
                if (strtoupper($p_config['env']) == 'ACTIVATION') {
                    $this->env                        = self::ENV_ACTIVATION;
                    $this->fixed_parameters['saisie'] = 'X';
                } else {
                    $this->env                        = self::ENV_TEST;
                    $this->fixed_parameters['saisie'] = 'T';
                }
            }
        } else {
            throw new \InvalidArgumentException('Tipi env parameter missing !');
        }
        if (array_key_exists('site', $p_config)) {
            $this->fixed_parameters['numcli'] = $p_config['site'];
        } else {
            throw new \InvalidArgumentException('Tipi site parameter missing !');
        }
        if (array_key_exists('urls', $p_config)) {
            $this->urls = $p_config['urls'];
            foreach ($this->urls as $key => $value) {
                switch (strtoupper($key)) {
                    case 'REPONDRE_A':
                        $this->fixed_parameters['urlcl'] = $value;
                        break;
                }
            }
        }
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
                        $this->request_parameters['montant'] = number_format($mttc, 0, '.', '');
                    } else {
                        throw new \InvalidArgumentException('Tipi montant is incorrect !');
                    }
                    break;
                case self::CST_PARAM_EMAIL:
                    if (\FreeFW\Tools\Email::verifyFormatting($value)) {
                        $this->request_parameters['mel'] = $value;
                    } else {
                        throw new \InvalidArgumentException('Tipi email is incorrect !');
                    }
                    break;
                case self::CST_PARAM_CMD:
                    if (trim($value) != '') {
                        $this->request_parameters['objet'] = trim($value);
                    } else {
                        throw new \InvalidArgumentException('Tipi cmd is incorrect !');
                    }
                    break;
                case self::CST_PARAM_REFDET:
                    if (trim($value) != '' && intval($value) > 0) {
                        if ($this->env != self::ENV_PRODUCTION) {
                            $this->request_parameters['refdet'] = '999999990000000000000';
                        } else {
                            $this->request_parameters['refdet'] = trim($value);
                        }
                    } else {
                        throw new \InvalidArgumentException('Tipi refdet is incorrect !');
                    }
                    break;
                case self::CST_PARAM_EXER:
                    if (trim($value) != '' && intval($value) > 0) {
                        if ($this->env != self::ENV_PRODUCTION) {
                            $this->request_parameters['exer'] = '9999';
                        } else {
                            $this->request_parameters['exer'] = trim($value);
                        }
                    } else {
                        throw new \InvalidArgumentException('Tipi exer is incorrect !');
                    }
                    break;
            }
        }
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
            $action = 'https://www.tipi.budget.gouv.fr/tpa/paiement.web';
            $method = 'GET';
            $fields = array_merge($this->fixed_parameters, $this->request_parameters);
            ksort($fields);
            $hidden = "";
            $query  = http_build_query($fields);
            $form = \FreeFW\Tools\PBXString::parse(
                $p_html_model,
                [
                    'action'  => $action . '?' . $query,
                    'method'  => $method,
                    'content' => $hidden
                ]
            );
        }
        return $form;
    }
}
