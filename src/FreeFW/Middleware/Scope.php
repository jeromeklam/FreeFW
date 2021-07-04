<?php
namespace FreeFW\Middleware;

use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Message\ResponseFactoryInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;

/**
 * Ignore methods
 *
 * @author jerome.klam
 */
class Scope implements
    MiddlewareInterface,
    \Psr\Log\LoggerAwareInterface,
    \FreeFW\Interfaces\ConfigAwareTraitInterface
{

    /**
     * Behaviour
     */
    use \Psr\Log\LoggerAwareTrait;
    use \FreeFW\Behaviour\EventManagerAwareTrait;
    use \FreeFW\Behaviour\ConfigAwareTrait;
    use \FreeFW\Behaviour\HttpFactoryTrait;

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface  $p_request
     * @param RequestHandlerInterface $p_handler
     *
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $p_request,
        RequestHandlerInterface $p_handler
    ): ResponseInterface {
        $authorized = true;
        /**
         * First, get route collection
         * Il fauyt avoir accès à la collection si on demande une authentification entrante
         * On verra plus tard pour rendre une collection libre
         * @var string $collection
         */
        $collection = 'FreeFW\Core\Collection';
        try {
            $route = $p_request->getAttribute('route');
            if ($route instanceof \FreeFW\Router\Route) {
                $auth = $route->getAuth();
                if ($auth == \FreeFW\Router\Route::AUTH_IN || $auth == \FreeFW\Router\Route::AUTH_BOTH) {
                    $collection = $route->getCollection();
                } else {
                    // On vide la collection pour laisser passer.
                    $collection = '';
                }
            }
        } catch (\Exception $ex) {
            //
        }
        if ($collection != '') {
            $authorized = false;
            /**
             * Second, get scope
             * @var \FreeSSO\Server $sso
             */
            $sso   = \FreeFW\DI\DI::getShared('sso');
            $scope = '';
            try {
                $user = $sso->getUser();
                if ($user instanceof \FreeSSO\Model\Base\User) {
                    $scope = $user->getUserScope();
                }
            } catch (\Exception $ex) {
                //
            }
            // Not case sensitive...
            $collection = strtoupper($collection);
            /**
             * Il faut vérifier que le user à accès à la collection
             * @var array $parts
             */
            $parts = explode(',', $scope);
            foreach ($parts as $oneScope) {
                if (trim($oneScope) != '') {
                    $testScope = strtoupper($oneScope);
                    $split     = explode('.', $testScope);
                    $resp      = 'CRUD';
                    if ($split[0] == 'ZEUS') {
                        $authorized = $resp;
                        break;
                    }
                    if (strpos($collection, $split[0]) === 0) {
                        $authorized = $resp;
                        break;
                    }
                }
            }
            /**
             * En fonction du role ou de la srucharge via scope, il faut traiter le CRUD
             */
        }
        if ($authorized === false) {
            $this->logger->info(sprintf('FreeFW.Middleware.Scope.%s', $collection));
            return $this->createResponse(401, "Not authorized !");
        }
        return $p_handler->handle($p_request);
    }
}
