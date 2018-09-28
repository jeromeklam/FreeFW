<?php
namespace FreeSSO\Controller;

use \FreeSSO\Model\User as UserModel;
use \FreeSSO\Server as SsoServer;

/**
 *
 * @author jeromeklam
 *
 */
class Db extends \FreeFW\Controller\Base
{

    /**
     * Retourne tous les utilisateurs
     *
     * @return \FreeFW\Interfaces\Response
     */
    public function get()
    {
        $response = new \FreeFW\Http\JsonResponse();
        //
        $ssoServer = SsoServer::getInstance();
        try {
            $user = $ssoServer->getUser();
            if ($user === false) {
                $response->withStatus(401);
            } else {
                $response->setContent($user);
            }
        } catch (\Exception $ex) {
            $response->withStatus(401);
        }
        return $response;
    }
}
