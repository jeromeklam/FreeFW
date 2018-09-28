<?php
/**
 * Templater Twig
 *
 * @author jeromeklam
 * @package Templater
 * @category Twig
 */
namespace FreeFW\Templater;

/**
 * Templater Twig
 * @author jeromeklam
 */
class Twig extends \FreeFW\Templater\AbstractTemplater implements \FreeFW\Interfaces\Templater
{

    /**
     * Comportement
     */
    use \FreeFW\Behaviour\LoggerAwareTrait;

    /**
     *
     * @var unknown $instance
     */
    protected static $instance = null;

    /**
     * Set application
     *
     * @var unknown
     */
    protected $application = null;

    /**
     * loader
     *
     * @var unknown
     */
    protected $loader = null;

    /**
     * twig
     *
     * @var unknown
     */
    protected $twig = null;

    /**
     * Constructeur
     */
    protected function __construct($p_config = array())
    {
        $this->config = $p_config;
    }

    /**
     * Retourne une instance du templater
     *
     * @return \FreeFW\Templater\Twig
     */
    public static function getInstance($p_config = array())
    {
        if (self::$instance === null) {
            self::$instance = new self($p_config);
        }
        return self::$instance;
    }

    /**
     * Affectation de l'application
     *
     * @param unknown $p_app
     *
     * @return \FreeFW\Templater\Twig
     */
    public function setApplication($p_app)
    {
        $this->application = $p_app;
        return $this;
    }

    /**
     * Enregistrement des functions
     *
     * @return \FreeFW\Templater\Twig
     */
    protected function registerFunctions()
    {
        $app  = $this->application;
        $lang = $app->getLang();
        //
        $function = new \Twig_SimpleFunction(
            'render',
            function ($p_controller, $p_params = array()) use ($app) {
                try {
                    return $app->renderController($p_controller, $p_params);
                } catch (\Exception $ex) {
                    return $ex->getMessage();
                }
            },
            array('is_safe' => array('html'))
        );
        $this->twig->addFunction($function);
        //
        $function2 = new \Twig_SimpleFunction(
            '_',
            function ($p_value, $p_params = array()) use ($app) {
                try {
                    $trad = $app->getTranslator()->get($p_value);
                    $trad = \FreeFW\Tools\PBXString::parse($trad, $p_params);
                    return $trad;
                } catch (\Exception $ex) {
                    return $ex->getMessage();
                }
            },
            array('is_safe' => array('html'))
        );
        $this->twig->addFunction($function2);
        //
        $function3 = new \Twig_SimpleFunction(
            'route',
            function ($p_name, $p_params = array(), $p_withExtraParams = true) use ($app) {
                try {
                    return $app->getRoute($p_name, $p_params, $p_withExtraParams);
                } catch (\Exception $ex) {
                    return $ex->getMessage();
                }
            },
            array('is_safe' => array('html'))
        );
        $this->twig->addFunction($function3);
        //
        $function4 = new \Twig_SimpleFunction(
            'routeExtraParams',
            function ($p_name, $p_params = array()) use ($app) {
                try {
                    return $app->getRouteExtraParams($p_name, $p_params);
                } catch (\Exception $ex) {
                    return $ex->getMessage();
                }
            },
            array('is_safe' => array('html'))
        );
        $this->twig->addFunction($function4);
        //
        $function5 = new \Twig_SimpleFunction(
            'currentRoute',
            function ($p_params = array()) use ($app) {
                try {
                    $router = $app::getDIShared('router');
                    $route  = $router->getCurrentRoute();
                    if ($route != false) {
                        return $route->getName();
                    } else {
                        return "";
                    }
                } catch (\Exception $ex) {
                    return '';
                }
            },
            array('is_safe' => array('html'))
        );
        $this->twig->addFunction($function5);
        //
        $function6 = new \Twig_SimpleFunction(
            'isConnected',
            function ($p_params = array()) use ($app) {
                try {
                    $ssoServer = $app::getDIShared('sso');
                    $user      = $ssoServer->getUser();
                    if ($user !== false) {
                        return true;
                    }
                } catch (\Exception $ex) {
                    return false;
                }
                return false;
            }
        );
        $this->twig->addFunction($function6);
        //
        $function7 = new \Twig_SimpleFunction(
            'aceSkin',
            function () {
                if (array_key_exists('ace_skin', $_COOKIE)) {
                    $skin = $_COOKIE['ace_skin'];
                } else {
                    $skin = 'no-skin';
                }
                return $skin;
            },
            array('is_safe' => array('html'))
        );
        $this->twig->addFunction($function7);
        //
        $function8 = new \Twig_SimpleFunction(
            'is_file',
            function ($p_str = '') {
                if ($p_str != '') {
                    return is_file($p_str);
                }
                return false;
            },
            array('is_safe' => array('html'))
        );
        $this->twig->addFunction($function8);
        //
        return $this;
    }

    /**
     * Enregistrement des filtres
     *
     * @return \FreeFW\Templater\Twig
     */
    protected function registerFilters()
    {
        if (array_key_exists('relative_path', $this->config)) {
            $host = '';
            if (array_key_exists('HTTP_X_FORWARDED_HOST', $_SERVER) && $_SERVER['HTTP_X_FORWARDED_HOST'] != '') {
                $host = 'http://' . $_SERVER['HTTP_X_FORWARDED_HOST'];
                if (array_key_exists('HTTP_X_FORWARDED_PORT', $_SERVER) && $_SERVER['HTTP_X_FORWARDED_PORT'] != '') {
                    $host .= ':' . $_SERVER['HTTP_X_FORWARDED_PORT'];
                }
            } else {
                $host = 'http://' . $_SERVER["HTTP_HOST"];
            }
            // Filtre des images
            $relative = $this->config['relative_path'];
            $filter   = new \Twig_SimpleFilter(
                'yzyImage',
                function ($string) use ($relative, $host) {
                    if (strpos($string, 'http') !== false) {
                        return '<img src="' . $string . '" />';
                    }
                    return '<img src="' . $host . $relative . 'api/produit/image/80x80/' . $string . '" />';
                },
                array('is_safe' => array('html'))
            );
            $this->twig->addFilter($filter);
        }
        // Filtre des dayes
        $filter2  = new \Twig_SimpleFilter(
            'localeDatetime',
            function ($string) {
                return \FreeFW\Tools\Date::mysqlToddmmyyyy($string);
            },
            array('is_safe' => array('html'))
        );
        $this->twig->addFilter($filter2);
        //
        $filter3  = new \Twig_SimpleFilter(
            'bootstrapCols',
            function ($p_sizes) {
                if (is_array($p_sizes)) {
                    $sizes = '';
                    foreach ($p_sizes as $siz => $col) {
                        if ($col == '-' || strtoupper($col) == 'N'|| strtoupper($col) == 'HIDDEN') {
                            $sizes .= ' hidden-' . strtolower($siz);
                        } else {
                            $sizes .= ' col-' . intval($col);
                        }
                    }
                    return $sizes;
                } else {
                    return 'col-' . intval($p_sizes);
                }
            },
            array('is_safe' => array('html'))
        );
        $this->twig->addFilter($filter3);
        //
        $filterZ = new \Twig_SimpleFilter(
            'not_zero',
            function ($string, $euro = true) {
                $ret    = '-';
                $string = str_replace(',', '.', $string);
                if (is_numeric($string)) {
                    $var = floatval($string);
                    if ($var > 0) {
                        $ret = number_format($var, 2, ',', ' ');
                        if ($euro === true) {
                            $ret .= '&nbsp;&euro;';
                        }
                    }
                }
                return trim($ret);
            },
            array('is_safe' => array('html'))
        );
        $this->twig->addFilter($filterZ);
        return $this;
    }

    /**
     * Retourne le templater Twig
     *
     * @return \Twig_Environment
     */
    protected function getTwig()
    {
        $app  = $this->application;
        $lang = $app->getLang();
        if ($this->twig === null) {
            $this->loader = new \Twig_Loader_Filesystem($this->config['template_dir']);
            foreach ($this->namespaces as $name => $dir) {
                $path = rtrim($dir, '/') . '/Template/' . strtoupper($lang);
                if (!is_dir($path)) {
                    $path = rtrim($dir, '/') . '/Template';
                }
                if (is_dir($path)) {
                    $this->loader->addPath($path, $name);
                }
            }
            $this->twig = new \Twig_Environment(
                $this->loader,
                array(
                    'cache' => false
                )
            );
            $this
                ->registerFunctions()
                ->registerFilters();
        }
        return $this->twig;
    }

    /**
     * Génération
     *
     * @param string $p_templateFileName
     * @param array  $p_params
     *
     * @return string
     */
    public function render($p_templateFileName, $p_params)
    {
        if (!is_array($p_params)) {
            $p_params = array();
        }
        if (array_key_exists('development', $this->config)) {
            $p_params['development'] = $this->config['development'];
        } else {
            $p_params['development'] = false;
        }
        if (array_key_exists('app', $this->config)) {
            $app = $this->config['app'];
            foreach ($app as $key => $value) {
                $p_params['app' . \FreeFW\Tools\PBXString::toCamelCase($key, true)] = $value;
            }
        }
        try {
            return $this->getTwig()->render($p_templateFileName, $p_params);
        } catch (\Exception $ex) {
            self::error($ex->getMessage());
            throw new \Exception($ex->getMessage());
        }
    }
}
