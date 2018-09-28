<?php
/**
 * Classe de controle de la partie SSO
 *
 * @author jeromeklam
 * @package SSO\Controller
 */
namespace FreeSSO\Controller\Micro\V1;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \FreeSSO\ErrorCodes as SsoErrors;
use FreeSSO\SsoException;

/**
 * Classe controlleur SSO
 * @author jeromeklam
 */
class Sso extends \FreeFW\Controller\Base
{

    /**
     * Register new user
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function signUp($request)
    {
        $errors    = new \FreeFW\Http\Errors();
        $ssoServer = $this->getDIShared('sso');
        $user      = false;
        try {
            if (!$request->hasAttribute('login') || $request->getAttribute('login') == '') {
                $errors->addRequiredField(SsoErrors::ERROR_LOGIN_EMPTY, 'login');
            }
            if (!$request->hasAttribute('password') || $request->getAttribute('password') == '') {
                $errors->addRequiredField(SsoErrors::ERROR_PASSWORD_EMPTY, 'password');
            }
            if (!$errors->hasErrors()) {
                self::debug($request->getAttribute('login'));
                self::debug($request->getAttribute('password'));
                $user = $ssoServer->registerNewUser(
                    $request->getAttribute('login'),
                    $request->getAttribute('login'),
                    $request->getAttribute('password'),
                    [],
                    true
                );
                $mailCode = strtoupper($ssoServer->getIdentifier()) . '-' . 'ACCOUNT-VALIDATION';
                $mailing = \FreeFW\Mailing\Model\Mailing::getFirst(
                    array(
                        'mailg_code' => $mailCode
                    )
                );
                if ($mailing === false) {
                    $mailCode = 'SSO-ACCOUNT-VALIDATION';
                    $mailing = \FreeFW\Mailing\Model\Mailing::getFirst(
                        array(
                            'mailg_code' => $mailCode
                        )
                    );
                    if ($mailing === false) {
                        return $this->createCustomResponse(409, [], 'No mailing *-ACCOUNT-VALIDATION');
                    }
                }
                $token       = ['token' => $user->getUserValString()];
                $datas       = array_merge($user->__toArray(), $token);
                $mailingServ = self::getDIService('FreeFW.Mailing::Mailing');
                $email       = $mailingServ->getAsEmail(
                    $mailing,
                    $request->getAttribute('login'),
                    null,
                    \FreeFW\Constants::LANG_FR,
                    $datas
                );
                $token = $datas['token'];
                $email
                    ->setMailObjectId($user->getUserId())
                    ->setMailObject('ACCOUNT-VALIDATION')
                    ->setMailStatus(\FreeFW\Mailing\Model\Email::STATUS_WAITING)
                ;
                if (!$email->create()) {
                    return $this->createCustomResponse(409, [], 'Can\'t create email');
                }
                // Try to send....
                $mailServ = self::getDIService('FreeFW.Mailing::Email');
                $mailServ->send($email);
            } else {
                return $this->createCustomResponse(400, [], $errors);
            }
        } catch (\FreeSSO\SsoException $ex) {
            if ($ex->getCode() == \FreeSSO\ErrorCodes::ERROR_LOGIN_EXISTS) {
                $errors->addError($ex->getCode(), 'Ce compte existe déjà', 'Ce compte existe déjà', 'login');
            } else {
                $errors->addError($ex->getCode(), 'Erreur lors de l\'inscription', 'Erreur lors de l\'inscription', 'login');
            }
            return $this->createCustomResponse(409, [], $errors);
        } catch (\Exception $ex) {
            $errors->addCritical($ex->getCode(), $ex->getMessage());
            return $this->createCustomResponse(500, [], $errors);
        }
        return $this->createCustomResponse(201, [], $user);
    }

    /**
     * Validate user account
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function validateAccount($p_request)
    {
        $errors    = new \FreeFW\Http\Errors();
        $ssoServer = $this->getDIShared('sso');
        $token     = false;
        $user      = false;
        try {
            if (!$p_request->hasAttribute('token') || $p_request->getAttribute('token') == '') {
                $errors->addRequiredField(SsoErrors::ERROR_TOKEN_EMPTY, 'token');
            }
            $user = $ssoServer->validateAccount($p_request->getAttribute('token'));
            if ($errors->hasErrors()) {
                return $this->createCustomResponse(409, [], $errors);
            }
        } catch (SsoException $ex) {
            $errors->addError($ex->getCode(), $ex->getMessage(), $ex->getMessage(), 'token');
            return $this->createCustomResponse(409, [], $errors);
        } catch (\Exception $ex) {
            return $this->createCustomResponse(500);
        }
        return $this->createCustomResponse(200, [], $user);
    }

    /**
     * Log user in
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function signIn($request)
    {
        $errors    = new \FreeFW\Http\Errors();
        $ssoServer = $this->getDIShared('sso');
        try {
            if (!$request->hasAttribute('login') || $request->getAttribute('login') == '') {
                $errors->addRequiredField(SsoErrors::ERROR_LOGIN_EMPTY, 'login');
            }
            if (!$request->hasAttribute('password') || $request->getAttribute('password') == '') {
                $errors->addRequiredField(SsoErrors::ERROR_PASSWORD_EMPTY, 'password');
            }
            if (!$errors->hasErrors()) {
                self::debug($request->getAttribute('login'));
                self::debug($request->getAttribute('password'));
                $result = $ssoServer->signinByLoginAndPassword(
                    $request->getAttribute('login'),
                    $request->getAttribute('password'),
                    false
                );
                $user = $ssoServer->getUser();
            } else {
                return $this->createCustomResponse(400, [], $errors);
            }
        } catch (\FreeSSO\SsoException $ex) {
            if ($ex->getCode() == \FreeSSO\ErrorCodes::ERROR_LOGIN_NOTFOUND) {
                $errors->addError($ex->getCode(), $ex->getMessage(), $ex->getMessage(), 'login');
            } else {
                if ($ex->getCode() == \FreeSSO\ErrorCodes::ERROR_PASSWORD_WRONG) {
                    $errors->addError($ex->getCode(), $ex->getMessage(), $ex->getMessage(), 'password');
                } else {
                    if ($ex->getCode() == \FreeSSO\ErrorCodes::ERROR_USER_NOTACTIVATED) {
                        $errors->addError($ex->getCode(), $ex->getMessage(), $ex->getMessage(), 'login');
                    } else {
                        $errors->addError($ex->getCode(), $ex->getMessage());
                    }
                }
            }
            return $this->createCustomResponse(409, [], $errors);
        } catch (\Exception $ex) {
            $errors->addCritical($ex->getCode(), $ex->getMessage());
            return $this->createCustomResponse(500, [], $errors);
        }
        return $this->createCustomResponse(201, [], $user);
    }

    /**
     * Logout
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function signOut($request)
    {
        self::debug('logout');
        $ssoServer = self::getDIShared('sso');
        try {
            $ssoServer->logout();
        } catch (\Exception $ex) {
            $errors->addCritical($ex->getCode(), $ex->getMessage());
            return $this->createCustomResponse(500, [], $errors);
        }
        return $this->createCustomResponse(204);
    }

    /**
     * Vérification de l'authentification
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function auth($request)
    {
        $ssoServer = $this->getDIShared('sso');
        $user      = false;
        try {
            $user = $ssoServer->getUser();
            if ($user === false) {
                return $this->createCustomResponse(401);
            }
        } catch (\Exception $ex) {
            return $this->createCustomResponse(401);
        }
        return $this->createCustomResponse(200, [], $user);
    }

    /**
     * Demande de changement de mot de passe
     *
     * @param ServerRequestInterface $p_request
     *
     * @return ResponseInterface
     */
    public function requestNewPasswordToken ($p_request)
    {
        $errors    = new \FreeFW\Http\Errors();
        $ssoServer = $this->getDIShared('sso');
        $token     = false;
        try {
            if (!$p_request->hasAttribute('login') || $p_request->getAttribute('login') == '') {
                $errors->addRequiredField(SsoErrors::ERROR_LOGIN_EMPTY, 'login');
            }
            if (!$errors->hasErrors()) {
                $login = $p_request->getAttribute('login');
                $user  = $ssoServer->getUserByLogin($login);
                if ($user === false) {
                    $errors->addError(SsoErrors::ERROR_LOGIN_NOTFOUND, sprintf('Login %s non trouvé !', $login));
                    return $this->createCustomResponse(409, [], $errors);
                }
                $token = $ssoServer->getUserPasswordToken($login);
                if ($token === false) {
                    $errors->addError(SsoErrors::ERROR_TOKEN_EMPTY, 'Le token est obligatoire !');
                    return $this->createCustomResponse(409, [], $errors);
                }
                $mailCode = strtoupper($ssoServer->getIdentifier()) . '-' . 'REQUEST-PASSWORD';
                $mailing = \FreeFW\Mailing\Model\Mailing::getFirst(
                    array(
                        'mailg_code' => $mailCode
                    )
                );
                if ($mailing === false) {
                    $mailCode = 'SSO-REQUEST-PASSWORD';
                    $mailing = \FreeFW\Mailing\Model\Mailing::getFirst(
                        array(
                            'mailg_code' => $mailCode
                        )
                    );
                    if ($mailing === false) {
                        $errors->addError(SsoErrors::ERROR_GENERAL, 'Pas de modèle de mail !');
                        return $this->createCustomResponse(409, [], $errors);
                    }
                }
                $datas       = array_merge($user->__toArray(), $token);
                $mailingServ = self::getDIService('FreeFW.Mailing::Mailing');
                $email       = $mailingServ->getAsEmail(
                    $mailing,
                    $login,
                    null,
                    \FreeFW\Constants::LANG_FR,
                    $datas
                );
                $token = $datas['token'];
                $email
                    ->setMailObjectId($user->getUserId())
                    ->setMailObject('REQUEST-PASSWORD')
                    ->setMailStatus(\FreeFW\Mailing\Model\Email::STATUS_WAITING)
                ;
                if (!$email->create()) {
                    $errors->addError(SsoErrors::ERROR_GENERAL, 'Impossible de créer l\'email !');
                    return $this->createCustomResponse(409, [], $errors);
                }
                // Try to send....
                $mailServ = self::getDIService('FreeFW.Mailing::Email');
                $mailServ->send($email);
            } else {
                return $this->createCustomResponse(409, [], $errors);
            }
        } catch (\Exception $ex) {
            return $this->createCustomResponse(500);
        }
        return $this->createCustomResponse(200, [], $token);
    }

    /**
     * Changement du mot de passe
     *
     * @param ServerRequestInterface $p_request
     *
     * @return ResponseInterface
     */
    public function changePassword ($p_request)
    {
        $errors    = new \FreeFW\Http\Errors();
        $ssoServer = $this->getDIShared('sso');
        $user      = false;
        try {
            $user = $ssoServer->getUser();
            if ($user === false) {
                return $this->createCustomResponse(401);
            }
            if (!$p_request->hasAttribute('old_password') || $p_request->getAttribute('old_password') == '') {
                $errors->addRequiredField(SsoErrors::ERROR_PASSWORD_EMPTY, 'old_password');
            }
            if (!$p_request->hasAttribute('new_password') || $p_request->getAttribute('new_password') == '') {
                $errors->addRequiredField(SsoErrors::ERROR_PASSWORD_EMPTY, 'new_password');
            }
            if ($errors->hasErrors()) {
                return $this->createCustomResponse(409, [], $errors);
            }
            $ssoServer->changeUserPassword(
                $user,
                $p_request->getAttribute('old_password'),
                $p_request->getAttribute('new_password')
            );
            $mailCode = strtoupper($ssoServer->getIdentifier()) . '-' . 'CHANGED-PASSWORD';
            $mailing = \FreeFW\Mailing\Model\Mailing::getFirst(
                array(
                    'mailg_code' => $mailCode
                )
            );
            if ($mailing === false) {
                $mailCode = 'SSO-CHANGED-PASSWORD';
                $mailing = \FreeFW\Mailing\Model\Mailing::getFirst(
                    array(
                        'mailg_code' => $mailCode
                    )
                );
                if ($mailing === false) {
                    return $this->createCustomResponse(409, [], 'No mailing *-CHANGED-PASSWORD');
                }
            }
            $datas       = array_merge($user->__toArray(), $token);
            $mailingServ = self::getDIService('FreeFW.Mailing::Mailing');
            $email       = $mailingServ->getAsEmail(
                $mailing,
                $user->getUserEmail(),
                null,
                \FreeFW\Constants::LANG_FR,
                $datas
            );
            $email
                ->setMailObjectId($user->getUserId())
                ->setMailObject('CHANGE-PASSWORD')
                ->setMailStatus(\FreeFW\Mailing\Model\Email::STATUS_WAITING)
            ;
            if (!$email->create()) {
                return $this->createCustomResponse(409, [], 'Can\'t create email');
            }
            // Try to send....
            $mailServ = self::getDIService('FreeFW.Mailing::Email');
            $mailServ->send($email);
        } catch (\Exception $ex) {
            $errors->addError(0, $ex->getMessage(), $ex->getMessage(), 'old_password');
            return $this->createCustomResponse(409, [], $errors);
        }
        return $this->createCustomResponse(200, [], $user);
    }

    /**
     * Changement du mot de passe avec token
     *
     * @param ServerRequestInterface $p_request
     *
     * @return ResponseInterface
     */
    public function changePasswordWithToken ($p_request)
    {
        $errors    = new \FreeFW\Http\Errors();
        $ssoServer = $this->getDIShared('sso');
        $token     = false;
        try {
            if (!$p_request->hasAttribute('token') || $p_request->getAttribute('token') == '') {
                $errors->addRequiredField(SsoErrors::ERROR_TOKEN_EMPTY, 'token');
            }
            if (!$p_request->hasAttribute('password') || $p_request->getAttribute('password') == '') {
                $errors->addRequiredField(SsoErrors::ERROR_PASSWORD_EMPTY, 'password');
            }
            if (!$errors->hasErrors()) {
                $token    = $p_request->getAttribute('token');
                $password = $p_request->getAttribute('password');
                $user     = $ssoServer->getUserFromPasswordToken($token);
                if ($user === false) {
                    $errors->addError(SsoErrors::ERROR_TOKEN_NOT_FOUND, sprintf('Le token %s n\'a pas été trouvé !', $token));
                    return $this->createCustomResponse(409, [], $errors);
                }
                if (!$ssoServer->changeUserPasswordFromToken($token, $password)) {
                    $errors->addError(SsoErrors::ERROR_GENERAL, 'Erreurs lors du changement de mot de passe !');
                    return $this->createCustomResponse(409, [], $errors);
                }
                $mailCode = strtoupper($ssoServer->getIdentifier()) . '-' . 'CHANGED-PASSWORD';
                $mailing = \FreeFW\Mailing\Model\Mailing::getFirst(
                    array(
                        'mailg_code' => $mailCode
                    )
                );
                if ($mailing === false) {
                    $mailCode = 'SSO-CHANGED-PASSWORD';
                    $mailing = \FreeFW\Mailing\Model\Mailing::getFirst(
                        array(
                            'mailg_code' => $mailCode
                        )
                    );
                    if ($mailing === false) {
                        $errors->addError(SsoErrors::ERROR_GENERAL, 'Aucun modèle de mail n\'a été défini !');
                        return $this->createCustomResponse(409, [], $errors);
                    }
                }
                $datas       = array_merge($user->__toArray(), $token);
                $mailingServ = self::getDIService('FreeFW.Mailing::Mailing');
                $email       = $mailingServ->getAsEmail(
                    $mailing,
                    $user->getUserEmail(),
                    null,
                    \FreeFW\Constants::LANG_FR,
                    $datas
                );
                $email
                    ->setMailObjectId($user->getUserId())
                    ->setMailObject('CHANGE-PASSWORD')
                    ->setMailStatus(\FreeFW\Mailing\Model\Email::STATUS_WAITING)
                ;
                if (!$email->create()) {
                    $errors->addError(SsoErrors::ERROR_GENERAL, 'Impossible d\'envoyer l\’email !');
                    return $this->createCustomResponse(409, [], $errors);
                }
                // Try to send....
                $mailServ = self::getDIService('FreeFW.Mailing::Email');
                $mailServ->send($email);
            } else {
                return $this->createCustomResponse(409, [], $errors);
            }
            $token = true;
        } catch (\Exception $ex) {
            return $this->createCustomResponse(500);
        }
        return $this->createCustomResponse(200, [], $token);
    }

    /**
     * Demande d'envoi de l'email de confirmation
     *
     * @param ServerRequestInterface $p_request
     *
     * @return ResponseInterface
     */
    public function requestValidationEmail($p_request)
    {
        $errors    = new \FreeFW\Http\Errors();
        $ssoServer = $this->getDIShared('sso');
        try {
            if (!$p_request->hasAttribute('login') || $p_request->getAttribute('login') == '') {
                $errors->addRequiredField(SsoErrors::ERROR_LOGIN_EMPTY, 'login');
                return $this->createCustomResponse(409, [], $errors);
            }
            $login = $p_request->getAttribute('login');
            $user  = $ssoServer->getUserByLogin($login);
            if (!$user) {
                $errors->addRequiredField(SsoErrors::ERROR_LOGIN_NOTFOUND, 'login');
                return $this->createCustomResponse(409, [], $errors);
            }
            if ($user->getUserValLogin() == '' || $user->getUserValString() == '') {
                $errors->addRequiredField(SsoErrors::ERROR_GENERAL, 'login');
                return $this->createCustomResponse(409, [], $errors);
            }
            $mailCode = strtoupper($ssoServer->getIdentifier()) . '-' . 'ACCOUNT-VALIDATION';
            $mailing = \FreeFW\Mailing\Model\Mailing::getFirst(
                array(
                    'mailg_code' => $mailCode
                )
            );
            if ($mailing === false) {
                $mailCode = 'SSO-ACCOUNT-VALIDATION';
                $mailing = \FreeFW\Mailing\Model\Mailing::getFirst(
                    array(
                        'mailg_code' => $mailCode
                    )
                );
                if ($mailing === false) {
                    $errors->addError(SsoErrors::ERROR_GENERAL, 'Aucun modèle de mail n\'a été défini !');
                    return $this->createCustomResponse(409, [], $errors);
                }
            }
            $token       = ['token' => $user->getUserValString()];
            $datas       = array_merge($user->__toArray(), $token);
            $mailingServ = self::getDIService('FreeFW.Mailing::Mailing');
            $email       = $mailingServ->getAsEmail(
                $mailing,
                $login,
                null,
                \FreeFW\Constants::LANG_FR,
                $datas
            );
            $token = $datas['token'];
            $email
                ->setMailObjectId($user->getUserId())
                ->setMailObject('ACCOUNT-VALIDATION')
                ->setMailStatus(\FreeFW\Mailing\Model\Email::STATUS_WAITING)
            ;
            if (!$email->create()) {
                $errors->addError(SsoErrors::ERROR_GENERAL, 'Impossible d\'envoyer l\’email !');
                return $this->createCustomResponse(409, [], $errors);
            }
            // Try to send....
            $mailServ = self::getDIService('FreeFW.Mailing::Email');
            $mailServ->send($email);
        } catch (\Exception $ex) {
            return $this->createCustomResponse(500);
        }
        return $this->createCustomResponse(200, []);
    }

    /**
     * Demande de suppression du compte
     *
     * @param ServerRequestInterface $p_request
     *
     * @return ResponseInterface
     */
    public function remove($p_request)
    {
        $errors    = new \FreeFW\Http\Errors();
        $ssoServer = $this->getDIShared('sso');
        try {
            $user = $ssoServer->getUser();
            if (!$user) {
                $errors->addCritical(SsoErrors::ERROR_LOGIN_NOTFOUND, 'Aucun compte !');
                return $this->createCustomResponse(409, [], $errors);
            }
            $user->remove();
        } catch (\Exception $ex) {
            var_dump($ex);
            return $this->createCustomResponse(500);
        }
        return $this->createCustomResponse(204, []);
    }
}
