<?php
namespace FreeFW\Behaviour;

/**
 * StorageListener
 *
 * @author jeromeklam
 */
trait StorageListenerTrait
{

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
     * Automates
     * @var [\FreeFW\Model\Automate]
     */
    protected static $automates = null;

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
        if (self::$automates === null) {
            self::$automates = \FreeFW\Model\Automate::find();
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
                    // First send Event to webSocket...
                    $context = new \ZMQContext();
                    $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my event');
                    $socket->connect("tcp://127.0.0.1:5555");
                    $socket->send(serialize($oneUpdate));
                    // And then to RabbitMQ
                    if ($p_queue) {
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
                    }
                }
                $this->updates = [];
            } catch (\Exception $ex) {
                // @todo...
            }
        }
        try {
            if ($p_object instanceof \FreeFW\Core\Model) {
                /**
                 * @var \FreeFW\Model\Automate $oneAutomate
                 */
                foreach (self::$automates as $oneAutomate) {
                    if (strtoupper($oneAutomate->getAutoObjectName()) == strtoupper($p_object->getApiType())) {
                        if ($oneAutomate->runForEvent($p_event_name)) {
                            $oneAutomate->run($p_object, $p_event_name);
                        }
                    }
                }
            }
        } catch (\Exception $ex) {
            // @todo
        }
    }
}
