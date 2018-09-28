<?php
namespace FreeFW\Middleware;

use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Message\ResponseInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \FreeFW\Tools\PBXString as Str;

/**
 * Pipeline of Middlewares
 * @author jerome.klam
 *
 */
class Pipeline implements RequestHandlerInterface
{

    /**
     * Behaviours
     */
    use \FreeFW\Behaviour\DI;
    use \FreeFW\Behaviour\LoggerAwareTrait;

    /**
     * Middlewares
     * @var array
     */
    protected $middlewares = [];

    /**
     * Current processing middleware
     * @var integer
     */
    protected $current = 0;

    /**
     * Object
     * @var Object
     */
    protected $object = null;

    /**
     * Function
     * @var string
     */
    protected $function = null;

    /**
     * Parameters
     * @var array
     */
    protected $parameters = null;

    /**
     * main version
     * @var string
     */
    protected $version = null;

    /**
     * Constructor
     *
     * @param string $p_object
     * @param string $p_function
     * @param string $p_parameters
     */
    public function __construct($p_object = null, $p_function = null, $p_parameters = null)
    {
        $this->object     = $p_object;
        $this->function   = $p_function;
        $this->parameters = $p_parameters;
    }

    /**
     * Set "end" function
     *
     * @param string $p_object
     * @param string $p_function
     * @param string $p_parameters
     *
     * @return Pipeline
     */
    public function setFunction($p_object = null, $p_function = null, $p_parameters = null)
    {
        $this->object     = $p_object;
        $this->function   = $p_function;
        $this->parameters = $p_parameters;
        return $this;
    }

    /**
     * Affectation de la version
     *
     * @param string $p_version
     *
     * @return \FreeFW\Middleware\Pipeline
     */
    public function setVersion($p_version)
    {
        $this->version = $p_version;
        return $this;
    }

    /**
     * Récupération de la version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Ajoute un middleware
     *
     * @return Pipeline
     */
    protected function addAndVerifyMiddleware(MiddlewareInterface $p_middleware)
    {
        $this->middlewares[] = $p_middleware;
        return $this;
    }

    /**
     * Add new middleware(s)
     *
     * @param mixed $p_middleware
     *
     * @return Pipeline
     */
    public function addMiddleware($p_middleware)
    {
        if (is_array($p_middleware)) {
            foreach ($p_middleware as $idx => $middleware) {
                $this->addAndVerifyMiddleware($middleware);
            }
        } else {
            $this->addAndVerifyMiddleware($p_middleware);
        }
        return $this;
    }

    /**
     * Ajout d'un middleware en fonction de sa clef
     *
     * @param string $p_key
     *
     * @return Pipeline
     */
    public function addMiddlewareByKey($p_key)
    {
        if (!is_array($p_key)) {
            $midCfg = $this->getDIConfig()->get('app:middlewares:micro');
            if (array_key_exists($p_key, $midCfg)) {
                $p_key = $midCfg[$p_key];
            } else {
                $p_key = explode(';', $p_key);
            }
        }
        if (is_array($p_key) && count($p_key)>0) {
            foreach ($p_key as $idx => $oneMid) {
                if ($oneMid != '') {
                    if (strpos($oneMid, '\\') === false) {
                        $class = '\\FreeFW\\Core\\Middleware\\' . Str::toCamelCase($oneMid, true);
                    } else {
                        $class = $oneMid;
                    }
                    if (class_exists($class)) {
                        $this->addAndVerifyMiddleware(new $class());
                    } else {
                        //throw new \Exception(sprintf('Unknown class %s !', $class));
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Dispatch the next available middleware and return the response.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (count($this->middlewares) > $this->current) {
            // Normal, it is a middleware
            $middleware    = $this->middlewares[$this->current];
            $this->current = $this->current + 1;
            self::debug(sprintf('**before middleware %s', get_class($middleware)));
            $response = $middleware->process($request, $this);
            self::debug(sprintf('**after middleware %s', get_class($middleware)));
        } else {
            if ($this->object !== null) {
                // No more middleware to process, call function
                $params = array_merge([$request], $this->parameters);
                self::debug(
                    sprintf(
                        '**before middleware last call %s . %s',
                        get_class($this->object),
                        $this->function
                    )
                );
                try {
                    if (method_exists($this->object, $this->function)) {
                        $response = call_user_func_array(array($this->object, $this->function), $params);
                    } else {
                        $response = new \FreeFW\Http\Response(500, [], 'Route incorrecte !');
                    }
                } catch (\Exception $ex) {
                    $response = new \FreeFW\Http\Response(500, [], $ex->getMessage());
                }
                self::debug(
                    sprintf(
                        '**after middleware last call %s . %s',
                        get_class($this->object),
                        $this->function
                    )
                );
            } else {
                // @todo ??:
                $response = new \FreeFW\Http\Response();
            }
        }
        return $response;
    }
}
