<?php
namespace FreeFW\Application;

/**
 * Application application
 *
 * @author jeromeklam
 */
class WebSocket extends \FreeFW\Core\Application implements \FreeWS\Wamp2\DataProviderInterface
{

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
     * Handle
     */
    public function handle()
    {
        $cfg     = $this->getAppConfig();
        $ssoBrk  = $cfg->get('sso');
        $input   = \FreeFW\Console\Input\Input::getFromGlobals();
        $brkKey  = $input->getAttribute('broker', $ssoBrk['broker']);
        $force   = $input->getAttribute('force', false);
        try {
            // User first, mandatory
            $userId = $input->getAttribute('user', 1);
            $user   = \FreeSSO\Model\User::findFirst(['user_id' => $userId]);
            // Broker instance
            $broker = \FreeSSO\Model\Broker::findFirst(['brk_key' => $brkKey]);
            if ($broker) {
                $sso    = new \FreeFW\Console\SsoMock($broker->getBrkId());
                $sso
                    ->setUser($user)
                    ->setGroup($broker->getGroup())
                ;
                // Inject in SSO
                \FreeFW\DI\DI::setShared('sso', $sso);
            } else {
                if (!$force) {
                    throw new \Exception('Broker not found !');
                }
            }
        } catch (\Exception $ex) {
            if (!$force) {
                throw $ex;
            }
        }
        /**
         *
         */
        $listener = new \FreeWS\Wamp2\Wamp2StorageListener();
        $listener->setDataProvider($this);
        /**
         * WebSocket
         * @var \React\EventLoop\LoopInterface $loo     */
        $loop    = \React\EventLoop\Factory::create();
        //
        $context = new \React\ZMQ\Context($loop);
        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
        $pull->bind('tcp://127.0.0.1:5555');
        $pull->on('message', array($listener, 'onEvent'));
        $webSock   = new \React\Socket\Server('0.0.0.0:8080', $loop);
        $webServer = new \Ratchet\Server\IoServer(
            new \Ratchet\Http\HttpServer(
                new \Ratchet\WebSocket\WsServer(
                    new \FreeWS\Wamp2\WampServer(
                        $listener,
                        $this->logger
                    )
                )
            ),
            $webSock
        );
        $loop->run();
    }

    /**
     * Get Data
     *
     * @param string $p_event
     * @param string $p_type
     * @param mixed $p_id
     *
     * @return mixed
     */
    public function getDataByEvent($p_event, $p_type, $p_id)
    {
        $datas = null;
        if (in_array($p_event, [\FreeFw\Constants::EVENT_STORAGE_CREATE, \FreeFW\Constants::EVENT_STORAGE_UPDATE])) {
            $role  = \FreeFW\Router\Route::ROLE_GET_ONE;
            $route = $this->router->findRouteByModelAndRole(str_replace('_', '::Model::', $p_type), $role);
            if ($route) {
                // @todo, need user token, ....
                // Quick and dirty solution : send to everybody
                $default = $route->getDefaultModel();
                $model   = \FreeFW\DI\DI::get($default);
                $filters  = new \FreeFW\Model\Conditions();
                $pk_field = $model->getPkField();
                $aField   = new \FreeFW\Model\ConditionMember();
                $aValue   = new \FreeFW\Model\ConditionValue();
                $aValue->setValue($p_id);
                $aField->setValue($pk_field);
                $aCondition = \FreeFW\Model\SimpleCondition::getNew();
                $aCondition->setLeftMember($aField);
                $aCondition->setOperator(\FreeFW\Storage\Storage::COND_EQUAL);
                $aCondition->setRightMember($aValue);
                $filters->add($aCondition);
                /**
                 * @var \FreeFW\Model\Query $query
                 */
                $query = $model->getQuery();
                $query
                    ->addConditions($filters)
                    ->addRelations($route->getInclude())
                    ->setLimit(0, 1)
                ;
                $data = null;
                if ($query->execute()) {
                    $data = $query->getResult();
                }
                $this->logger->debug('FreeFW.ApiController.getOne.end');
                if (count($data) > 0) {
                    $apiParams = new \FreeFW\Http\ApiParams();
                    $encoder   = new \FreeFW\JsonApi\V1\Encoder();
                    $datas     = $encoder->encode($data[0], $apiParams)->__toJson();
                }
            }
        }
        return $datas;
    }
}
