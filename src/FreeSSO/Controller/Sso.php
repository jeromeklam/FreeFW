<?php
/**
 * Classe de controle de la partie SSO
 *
 * @author jeromeklam
 * @package SSO\Controller
 */
namespace FreeSSO\Controller;

use \FreeSSO\Server as SsoServer;
use \FreeSSO\ErrorCodes as SsoErrors;

/**
 * Classe controlleur SSO
 * @author jeromeklam
 */
class Sso extends \FreeFW\Controller\Base
{

    /**
     * Retourne tous les utilisateurs
     *
     * @return \FreeFW\Interfaces\Response
     */
    public function login()
    {
        $response = $this->getResponse();
        //
        $request = self::getDIRequest();
        $response
            ->setTemplate('@FreeFW-sso/Auth/login.html')
        ;
        return $response;
    }

    /**
     * Redirige vers la page au forulaire d'inscription
     *
     * @return \FreeFW\Interfaces\Response
     */
    public function register()
    {
        $response = $this->getResponse();
        $response->setTemplate('@FreeFW-sso/Auth/register.html');
        return $response;
    }

    /**
     * Vérification de l'authentification
     *
     * @return unknown
     */
    public function auth()
    {
        $response = new \FreeFW\Http\FreeFWResponse();
        //
        $ssoServer = SsoServer::getInstance();
        try {
            $user = $ssoServer->getUser();
            if ($user === false) {
                $response->withStatus(401);
                $response->setContent($ssoServer->getCookies());
            } else {
                $response->setContent(array_merge($user->__toFields(), $ssoServer->getCookies()));
            }
        } catch (\Exception $ex) {
            $response->withStatus(401);
            $response->setContent($ssoServer->getCookies());
        }
        return $response;
    }

    /**
     * Login
     *
     *
     */
    public function signin()
    {
        $response = $this->getResponse();
        //
        $ssoServer = SsoServer::getInstance();
        $request   = self::getDIRequest();
        try {
            $error = false;
            $login = '';
            if (!$request->hasAttribute('login') || $request->getAttribute('login') == '') {
                $error = true;
                $response->addError(array('field' => 'login', 'code' => SsoErrors::ERROR_LOGIN_EMPTY));
            } else {
                $login = $request->getAttribute('login');
            }
            if (!$request->hasAttribute('password') || $request->getAttribute('password') == '') {
                $error = true;
                $response->addError(array('field' => 'password', 'code' => SsoErrors::ERROR_PASSWORD_EMPTY));
            }
            if ($error == false) {
                self::debug($request->getAttribute('login'));
                self::debug($request->getAttribute('password'));
                $result = $ssoServer->signinByLoginAndPassword(
                    $request->getAttribute('login'),
                    $request->getAttribute('password'),
                    false
                );
                if ($response->isJson()) {
                    $user = $ssoServer->getUser();
                    $response->setContent(array_merge($user->__toFields(), $ssoServer->getCookies()));
                } else {
                    $response->redirectHome();
                }
            } else {
                if (!$response->isJson()) {
                    $response
                        ->setTemplate('@FreeFW-sso/Auth/login.html')
                        ->setContent(array('login' => $login))
                        ->redirectReferer()
                    ;
                }
                $response
                    ->withStatus(412)
                    ->setMessage('Precondition failed !')
                ;
            }
        } catch (\Exception $ex) {
            if (!$response->isJson()) {
                $bag = self::getDIShared('flashbag');
                if ($bag instanceof \FreeFW\Message\FlashBag) {
                    $bag->add(\FreeFW\Message\Flash::getWarningMessage($ex->getMessage()));
                }
                $response
                    ->redirectReferer()
                    ->forwardParams(array('error_code'=>$ex->getCode()))
                ;
            } else {
                $response
                    ->withStatus(401)
                    ->setMessage($ex->getMessage())
                ;
            }
        }
        return $response;
    }

    /**
     * Ajoute un utilisateur a la BDD
     *
     */
    public function registerUser()
    {
        $response = $this->getResponse();
        //
        $ssoServer = SsoServer::getInstance();
        $request   = self::getDIRequest();

        try {
            $result = $ssoServer->registerNewUser(
                $request->getAttribute('login'),
                $request->getAttribute('email'),
                $request->getAttribute('password'),
                $request->getAttribute('password2'),
                $request->getAttribute('firstname'),
                $request->getAttribute('lastname'),
                $request->getAttribute('title')
            );
            if ($response->isJson()) {
                $user = $ssoServer->getUser();
                if ($result != false) {
                    $ssoServer->signinByLoginAndPassword(
                        $request->getAttribute('login'),
                        $request->getAttribute('password')
                    );
                    $user = $ssoServer->getUser();
                }
                $response->setContent($user);
            } else {
                $response->redirectHome();
            }
        } catch (\Exception $ex) {
            if (!$response->isJson()) {
                $bag = self::getDIShared('flashbag');
                if ($bag instanceof \FreeFW\Message\FlashBag) {
                    $bag->add(\FreeFW\Message\Flash::getWarningMessage($ex->getMessage()));
                }
                $response
                    ->redirectReferer()
                    ->forwardParams(array('error_code'=>$ex->getCode()))
                ;
            } else {
                $response
                    ->withStatus(401)
                ;
            }
        }
        return $response;
    }

    /**
     * Logout
     *
     *
     */
    public function logout()
    {
        $response = $this->getResponse();
        //
        $ssoServer = SsoServer::getInstance();
        try {
            $ssoServer->logout();
            if (!$response->isJson()) {
                $response->redirectHome();
            } else {
                $response->setContent(array());
            }
        } catch (\Exception $ex) {
            //var_dump($ex);
            //die;
        }
        return $response;
    }

    /**
     * Redirection sur la page d'edition
     *
     * des informations de l'utilisateur
     *
     */
    public function editUser()
    {
        $response = $this->getResponse();
        $vars     = array('appname' => $this->_('application.name'));
        //
        $ssoServer = self::getDIShared('sso');
        $user      = $ssoServer->getUser();
        if ($user !== false) {
            $response->setTemplate('@FreeFW-sso/Auth/editUser.html');
            $vars = array_merge($vars, $user->jsonSerialize());
        }
        $response->setContent($vars);

        return $response;
    }

    /**
     * Edition des information de l'utilisateur
     *
     *
     */
    public function editUserBase()
    {
        $response = $this->getResponse();
        //
        $ssoServer = SsoServer::getInstance();
        $request   = self::getDIRequest();
        try {
            $user = $ssoServer->getUser();
            $data = array(
                'firstname' => $request->getAttribute('firstname'),
                'lastname' => $request->getAttribute('lastname'),
                'email' => $request->getAttribute('email'),
                'title' => $request->getAttribute('title'),
                'oldPWD' => $request->getAttribute('oldpassword'),
                'PWD' => $request->getAttribute('password'),
                'PWD2' => $request->getAttribute('password2')
            );
            $result = $ssoServer->updateUser($user, $data);
            if ($response->isJson()) {
                $user = $ssoServer->getUser();
                $response->setContent($user);
            } else {
                $response->redirectHome();
            }
        } catch (\Exception $ex) {
            if (!$response->isJson()) {
                $bag = self::getDIShared('flashbag');
                if ($bag instanceof \FreeFW\Message\FlashBag) {
                    $bag->add(\FreeFW\Message\Flash::getWarningMessage($ex->getMessage()));
                }
                $response
                    ->redirectReferer()
                    ->forwardParams(array('error_code'=>$ex->getCode()))
                ;
            } else {
                $response
                    ->withStatus(401)
                ;
            }
        }
        return $response;
    }
}
