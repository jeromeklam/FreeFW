<?php
/**
 * Classe de gestion des dépendances
 *
 * @author jeromeklam
 * @package DI
 * @category Behaviour
 */
namespace FreeFW\Behaviour;

use FreeFW\Http\Response;
use \FreeFW\ResourceDi as Singleton;

/**
 * Dependency Injector
 * @author jeromeklam
 */
trait DI
{

    /**
     * Réponse
     * @var \FreeFW\Http\Response
     */
    protected $response = null;

    /**
     * Affecte la réponse
     *
     * @var \FreeFW\Http\Response $p_response
     *
     * @return \FreeFW\Controller\Base
     */
    public function setResponse($p_response)
    {
        $this->response = $p_response;

        return $this;
    }

    /**
     * Retourne la réponse
     *
     * @return \FreeFW\Http\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set config
     *
     * @var \FreeFW\Application\Config
     */
    public static function setDIConfig($p_config)
    {
        Singleton::getInstance()->setConfig($p_config);
    }

    /**
     * Get config
     *
     * @return \FreeFW\Application\Config
     */
    public static function getDIConfig()
    {
        return Singleton::getInstance()->getConfig();
    }

    /**
     * Ajout d'un nouveau module
     *
     * @param string $p_path
     * @param string $p_name
     * @param string $p_nameSpace
     * @param string $p_layout
     */
    public static function registerDIModule($p_path, $p_name, $p_nameSpace, $p_layout)
    {
        Singleton::getInstance()->registerModule($p_path, $p_name, $p_nameSpace, $p_layout);
    }

    /**
     * Ajout d'un nouveau module
     *
     * @param array $p_modules
     */
    public static function registerDIModules($p_modules)
    {
        foreach ($p_modules as $ns => $module) {
            $layout = '';
            if (array_key_exists('layout', $module)) {
                $layout = $module['layout'];
            }
            Singleton::getInstance()->registerModule($module['path'], $module['name'], $ns, $layout);
        }
    }

    /**
     * Retourne les modules
     *
     * @return array
     */
    public static function getDIModules()
    {
        return Singleton::getInstance()->getModules();
    }

    /**
     * Retourne un service
     *
     * @param string $p_service
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public static function getDIService($p_service)
    {
        $parts = self::getParts($p_service);
        if (count($parts) == 2) {
            $cls = '\\' . $parts[0] . '\\Service\\' . $parts[1];
            if (class_exists($cls)) {
                $service = new $cls();
                return $service;
            }
            throw new \InvalidArgumentException(
                sprintf('Le service demandé n\'existe pas %s !', $p_service)
            );
        } else {
            throw new \InvalidArgumentException(
                sprintf('Le service demande est mal formaté %s, utiliser NS::Service !', $p_service)
            );
        }
    }

    /**
     * Retourne un client
     *
     * @param string $p_client
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public static function getDIClient($p_client)
    {
        $parts = self::getParts($p_client);
        if (count($parts) == 2) {
            $cls = '\\' . $parts[0] . '\\Client\\' . $parts[1];
            if (class_exists($cls)) {
                $client = new $cls();
                return $client;
            }
            throw new \InvalidArgumentException(sprintf('Le client demandé n\'existe pas %s !', $p_client));
        } else {
            throw new \InvalidArgumentException(sprintf('Le client demandé est mal formaté %s !', $p_client));
        }
    }

    /**
     * Retourne un mailer
     *
     * @return \FreeFW\Mailer\Client
     */
    public static function getDIMailer()
    {
        $mailConfig = self::getDIConfig()->get('mails');
        $mailer     = \FreeFW\Mailer\Client::getInstance($mailConfig);

        return $mailer;
    }

    /**
     * Retourne la requête
     *
     * @return \FreeFW\Http\ServerRequest
     */
    public static function getDIRequest()
    {
        return Singleton::getInstance()->getRequest();
    }

    /**
     * Affecte la requête
     *
     * @param \FreeFW\Http\ServerRequest $p_request
     *
     * @return this
     */
    public static function setDIRequest($p_request)
    {
        Singleton::getInstance()->setRequest($p_request);
    }

    /**
     * Partage de ressource
     *
     * @param unknown $p_name
     * @param unknown $p_value
     */
    public static function setDIShared($p_name, $p_value)
    {
        Singleton::getInstance()->setShared($p_name, $p_value);
    }

    /**
     * Récupération d'une ressource partagée
     *
     * @param string $p_name
     */
    public static function getDIShared($p_name)
    {
        return Singleton::getInstance()->getShared($p_name);
    }

    /**
     * Récupération d'une connexion     * @param string $p_name
     */
    public static function getDIConnexion($p_name = 'default')
    {
        return Singleton::getInstance()->getConnexion($p_name);
    }

    /**
     * Je suis un D.I.
     *
     * @return boolean
     */
    public function isDI()
    {
        return true;
    }

    /**
     * Découpage en parties
     *
     * @param string $p_request
     *
     * @return Array
     */
    protected static function getParts($p_request)
    {
        if (strpos($p_request, '::')) {
            $parts = explode('::', $p_request);
        } else {
            if (strpos($p_request, ':')) {
                $parts = explode(':', $p_request);
            } else {
                $parts = explode('.', $p_request);
            }
        }
        foreach ($parts as $idx => $part) {
            $parts[$idx] = str_replace('.', '\\', $part);
        }
        return $parts;
    }

    /**
     * Return registered shcemas
     *
     * @return array
     */
    public static function getDISchemas()
    {
        return Singleton::getInstance()->getSchemas();
    }

    /**
     * Create a custom response
     *
     * @param number $code
     * @param array  $headers
     * @param mixed  $body
     * @param string $reason
     *
     * @return ResponseInterface
     */
    public function createCustomResponse($code = 200, $headers = array(), $body = null, $reason = null)
    {
        $response = new Response($code, $headers, $body, null, $reason);
        return $response;
    }

    /**
     * Ajout d'un middleware
     *
     * @param string $p_className
     *
     * @return \FreeFW\Behaviour\DI
     */
    public function registerDIMiddleware($p_className)
    {
        if (class_exists($p_className)) {
            Singleton::getInstance()->addMiddleware($p_className);
        } else {
            throw \Exception(sprintf('%s is not a class !', $p_className));
        }
        return $this;
    }

    /**
     * Retourne les middlewares
     *
     * @return array
     */
    public function getDIMiddlewares()
    {
        return Singleton::getInstance()->getMiddlewares();
    }
}
