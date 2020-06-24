<?php
namespace FreeFW\Application;

use GuzzleHttp;

/**
 * Application application
 *
 * @author jeromeklam
 */
class Application extends \FreeFW\Core\Application
{

    /**
     * Behaviour
     */
    use \FreeFW\Behaviour\HttpFactoryTrait;

    /**
     * Application instance
     * @var \FreeFW\Application\Application
     */
    protected static $instance = null;

    /**
     * Constructor
     *
     * @param \FreeFW\Application\Config $p_config
     */
    protected function __construct(
        \FreeFW\Application\Config $p_config,
        \Psr\Log\LoggerInterface $p_logger
    ) {
        parent::__construct($p_config, $p_logger);
        \FreeFW\DI\DI::setShared('config', $p_config);
        \FreeFW\DI\DI::setShared('logger', $p_logger);
    }

    /**
     * Array of modified objects
     * @var array
     */
    protected $updates = [];

    /**
     * We are in transaction
     * @var string
     */
    protected $in_transaction = 0;

    /**
     * Get Application instance
     *
     * @param \FreeFW\Application\Config $p_config
     *
     * @return \FreeFW\Application\Application
     */
    public static function getInstance(
        \FreeFW\Application\Config $p_config,
        \Psr\Log\LoggerInterface $p_logger
    ) {
        if (self::$instance === null) {
            self::$instance = new static($p_config, $p_logger);
        }
        return self::$instance;
    }

    /**
     * Send an HTTP response
     *
     * @return void
     */
    protected function send(\Psr\Http\Message\ResponseInterface $p_response)
    {
        $http_line = sprintf(
            'HTTP/%s %s %s',
            $p_response->getProtocolVersion(),
            $p_response->getStatusCode(),
            $p_response->getReasonPhrase()
        );
        header($http_line, true, $p_response->getStatusCode());
        foreach ($p_response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header("$name: $value", false);
            }
        }
        $stream = $p_response->getBody();
        if ($stream->isSeekable()) {
            $stream->rewind();
        }
        while (!$stream->eof()) {
            echo $stream->read(1024 * 8);
        }
    }

    /**
     * Send route for http code
     *
     * @param mixed $p_http_code
     */
    public function sendHttpCode($p_http_code)
    {
        $this->logger->debug('Application.sendHttpCode.start');
        try {
            $request     = \FreeFW\Http\ApiServerRequest::fromGlobals();
            $this->route = $this->router->findSpecificRoute($p_http_code);
            if ($this->route) {
                $this->route->setLogger($this->logger);
                $this->route->setAppConfig($this->getAppConfig());
                $this->send($this->route->render($request));
            } else {
                // @todo
                $response = $this->createResponse(404, 'Not found');
                $this->send($response);
            }
            $this->afterRender();
        } catch (\Exception $ex) {
            // @todo : handle 500 response
            $response = $this->createResponse(500, $ex->getMessage());
            $this->send($response);
        }
        $this->logger->debug('Application.sendHttpCode.end');
    }

    /**
     * Handle request
     */
    public function handle()
    {
        $this->logger->debug('Application.handle.start');
        try {
            $request     = \FreeFW\Http\ApiServerRequest::fromGlobals();
            $this->route = $this->router->findRoute($request);
            if ($this->route) {
                $this->route->setLogger($this->logger);
                $this->route->setAppConfig($this->getAppConfig());
                $this->send($this->route->render($request));
            } else {
                $this->fireEvent(\FreeFW\Constants::EVENT_ROUTE_NOT_FOUND);
            }
            $this->afterRender();
        } catch (\Exception $ex) {
            // @todo : handle 500 response
            $response = $this->createResponse(500, $ex->getMessage());
            $this->send($response);
        }
        $this->logger->debug('Application.handle.end');
    }
}
