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

    /**
     *
     * @param object $p_object
     * @param object $p_queue
     * @param object $p_queueCfg
     * @param string $p_event_name
     */
    public function listen($p_object, $p_queue, $p_queueCfg, $p_event_name = null) {
        if ($p_event_name == '') {
            $p_event_name = 'unknown';
        }
        switch ($p_event_name) {
            case \FreeFW\Constants::EVENT_STORAGE_BEGIN:
                $this->in_transaction += 1;
                break;
            case \FreeFW\Constants::EVENT_STORAGE_ROLLBACK:
                $this->updates = [];
            case \FreeFW\Constants::EVENT_STORAGE_COMMIT:
                $this->in_transaction -= 1;
                break;
            case \FreeFW\Constants::EVENT_STORAGE_DELETE:
            case \FreeFW\Constants::EVENT_STORAGE_UPDATE:
            case \FreeFW\Constants::EVENT_STORAGE_CREATE:
                if ($p_object instanceof \FreeFW\Core\Model && $p_object->forwardStorageEvent()) {
                    $this->updates[] = [
                        'event' => $p_event_name,
                        'type'  => $p_object->getApiType(),
                        'id'    => $p_object->getApiId()
                    ];
                }
                break;
        }
        // Only Core Models
        if ($this->in_transaction <= 0) {
            $this->in_transaction = 0;
            // Only if requested
            try {
                foreach ($this->updates as $oneUpdate) {
                    // First to RabbitMQ
                    $properties = [
                        'content_type' => 'application/json',
                        'delivery_mode' => \PhpAmqpLib\Message\AMQPMessage::DELIVERY_MODE_PERSISTENT
                    ];
                    $channel = $p_queue->channel();
                    // Exchange as fanout, only to connected consumers
                    $channel->exchange_declare($p_queueCfg['name'], 'fanout', false, false, false);
                    $msg = new \PhpAmqpLib\Message\AMQPMessage(
                        serialize($oneUpdate),
                        $properties
                    );
                    $channel->basic_publish($msg, $p_queueCfg['name']);
                    $channel->close();
                    // And then send Event to webSocket...
                    $context = new \ZMQContext();
                    $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my event');
                    $socket->connect("tcp://localhost:5555");
                    $socket->send(serialize($oneUpdate));
                }
            } catch (\Exception $ex) {
                // @todo...
            }
        }
    }
}
