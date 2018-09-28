<?php
namespace FreeSSO;

use \FreeFW\Tools\Date;
use \FreeSSO\Model\Broker;
use \FreeSSO\Model\BrokerSession;
use \FreeSSO\Model\Domain;
use \FreeSSO\Model\User as User;
use \FreeSSO\Model\PasswordToken;
use \FreeSSO\Model\Session as SSOSession;
use \FreeSSO\Http\Remote;
use \FreeSSO\SsoException;
use \FreeFW\Client\Http;

/**
 * Serveur de gestion des accès SSO
 *
 * @author jeromeklam
 * @package SSO\Server
 */
class Server implements \FreeFW\Interfaces\SSO
{

    /**
     * Comportements
     */
    use \FreeFW\Behaviour\DI;
    use \FreeFW\Behaviour\EventManager;
    use \FreeFW\Behaviour\Translation;

    /**
     * Instance of server
     * @var Server
     */
    protected static $instance = null;

    /**
     * Domain instance
     * @var Domain
     */
    protected static $domain = null;

    /**
     * Broker instance
     * @var Broker
     */
    protected static $broker = null;

    /**
     * Session
     * @var Session
     */
    protected static $session = null;

    /**
     * User
     * @var User
     */
    protected static $user = null;

    /**
     * Constructor
     *
     * @param array   $p_options
     */
    protected function __construct(array $p_options = array())
    {
        // Peut être un appel rest, ... en-tête API_ID ??
        $request = self::getDIRequest();
        if ($request->hasHeader('ApiId')) {
            $key = $request->getHeader('ApiId');
            if (is_array($key)) {
                $key = $key[0];
            }
            $this->setBroker($key);
        } else {
            if ($request->hasHeader('API_ID')) {
                $key = $request->getHeader('API_ID');
                if (is_array($key)) {
                    $key = $key[0];
                }
                $this->setBroker($key);
            } else {
                if (!array_key_exists(\FreeSSO\Constants::BROKER_KEY, $p_options)) {
                    throw new \InvalidArgumentException(
                        'Erreur de configuration : La clef "client" est obligatoire !',
                        \FreeSSO\ErrorCodes::ERROR_OPTIONS_BROKER_REQUIRED
                    );
                } else {
                    $this->setBroker($p_options[\FreeSSO\Constants::BROKER_KEY]);
                }
            }
        }
        // Oauth2...
        if (array_key_exists(\FreeSSO\Constants::OAUTH_KEY, $p_options)) {
            $oauth2 = $p_options[\FreeSSO\Constants::OAUTH_KEY];
            if (array_key_exists('activated', $oauth2) && $oauth2['activated'] === true) {
                $pdo = $this->getDIConnexion();
                $oauth2_storage = new \OAuth2\Storage\Pdo($pdo);
            }
        }
    }

    /**
     * Retourne les valeurs des cookies
     *
     * @return string[]
     */
    public function getCookies()
    {
        return array(
            'ssoId' => \FreeSSO\Http\Remote::getSSOCookie(),
            'appId' => \FreeSSO\Http\Remote::getApplicationCookie()
        );
    }

    /**
     * Get SSOServer instance
     *
     * @param array $p_options
     *
     * @return \FreeSSO\Server
     */
    public static function getInstance(array $p_options = array())
    {
        if (self::$instance === null) {
            if (is_array($p_options) && count($p_options) > 0) {
                self::$instance = new self($p_options);
            } else {
                throw new \InvalidArgumentException('Erreur de configuration : aucune option !');
            }
        }
        return self::$instance;
    }

    /**
     * Set broker
     *
     * @param string $p_brokerKey
     *
     * @throws \Exception
     */
    public function setBroker($p_brokerKey)
    {
        if (substr_count($p_brokerKey, '@') == 1) {
            $parts = explode('@', $p_brokerKey);
            $myBroker = Broker::getFirst(array(
                "brk_key"         => $parts[1],
                "brk_certificate" => $parts[0]
            ));
        } else {
            $myBroker = Broker::getFirst(array(
                "brk_key"         => $p_brokerKey,
                "brk_certificate" => ''
            ));
        }
        if ($myBroker instanceof Broker) {
            self::$broker = $myBroker;
            self::$domain = Domain::getById(array(
                'dom_id' => self::$broker->getDomId()
            ));
            if (!self::$domain instanceof Domain) {
                throw new \Exception(sprintf('Le domaine n\'a pas été trouvé !'));
            }
            if (!$myBroker->isActive()) {
                throw new \Exception(sprintf('Le "client" n\'est plus actif !'));
            }
        } else {
            throw new \Exception(sprintf('le "client" %s n\'a pas été trouvé !', $p_brokerKey));
        }
        /**
         * Init the cookies, ....
         */
        $ssoCk = Remote::getSSOCookie(self::$domain->getDomName());
        $appCk = Remote::getApplicationCookie();
        $cliIp = \FreeFW\Http\ServerRequest::getClientIp();
        /**
         * Try to get a broker session....
         */
        $this->verifyBrokerSession(self::$broker->getBrkKey(), $ssoCk, $appCk, $cliIp);
    }

    /**
     * Signin
     *
     * @param string  $p_login
     * @param string  $p_password
     * @param boolean $p_remember
     *
     * @throws \FreeSSO\SsoException
     *
     * @return boolean
     */
    public function signinByIdAndLogin($p_id, $p_login)
    {
        try {
            $this->fireEvent('sso:beforeSigninByIdAndLogin');
        } catch (\Exception $ex) {
        }
        self::$user = false;
        if ($p_login === null || $p_login == '') {
            throw new SsoException('Le login est obligatoire !', ErrorCodes::ERROR_LOGIN_EMPTY);
        }
        $user = User::getFirst(array(
            'user_id'    => $p_id,
            'user_login' => $p_login
        ));
        if ($user instanceof User) {
            if (!$user->isActive()) {
                throw new SsoException(sprintf('L\'utilisateur n\'est plus actif !'), ErrorCodes::ERROR_USER_DEACTIVATED);
            }
            // Ok, save to session...
            if (self::$session instanceof SSOSession) {
                self::$session->setUserId($user->getUserId());
                self::$session->setSessContent($user->serialize());
                self::$session->save();
                self::$user = $user;
            } else {
                throw new SsoException('Erreur : impossible de trouver la session !', ErrorCodes::ERROR_GENERAL);
            }
            if (self::$user->getUserLastUpdate() === null) {
                try {
                    $this->fireEvent('sso:lastUserUpdateEmpty', $this, self::$user);
                } catch (\Exception $ex) {
                    self::$user = false;
                }
            }
        } else {
            throw new SsoException(sprintf('Le login %s n\'existe pas !', $p_login), ErrorCodes::ERROR_LOGIN_NOTFOUND);
        }
        try {
            $this->fireEvent('sso:afterSigninByIdAndLogin', $user);
        } catch (\Exception $ex) {
        }
        return true;
    }

    /**
     * Signin
     *
     * @param string  $p_login
     * @param string  $p_password
     * @param boolean $p_remember
     *
     * @throws \FreeSSO\SsoException
     *
     * @return boolean
     */
    public function signinByLoginAndPassword($p_login, $p_password, $p_remember = false)
    {
        try {
            $this->fireEvent('sso:beforeSigninByLoginAndPassword');
        } catch (\Exception $ex) {
        }
        self::$user = false;
        if ($p_login === null || $p_login == '') {
            throw new SsoException('Le login est obligatoire !', ErrorCodes::ERROR_LOGIN_EMPTY);
        }
        $user = User::getFirst(array(
            'user_login' => $p_login
        ));
        if ($user instanceof User) {
            if (!$user->verifyPassword($p_password)) {
                throw new SsoException(sprintf('Le mot de passe est incorrect !'), ErrorCodes::ERROR_PASSWORD_WRONG);
            }
            if (!$user->isActive()) {
                if ($user->getUserValLogin() != '') {
                    throw new SsoException(sprintf('Le compte n\'a pas été activé !'), ErrorCodes::ERROR_USER_NOTACTIVATED);
                } else {
                    throw new SsoException(sprintf('Le compte n\'est plus actif !'), ErrorCodes::ERROR_USER_DEACTIVATED);
                }
            }
            // Ok, save to session...
            if (self::$session instanceof SSOSession) {
                self::$session->setUserId($user->getUserId());
                self::$session->setSessContent($user->serialize());
                self::$session->save();
                if ($p_remember) {
                    // @todo : set autologin cookie
                }
                self::$user = $user;
            } else {
                throw new SsoException('Erreur : impossible de trouver la session !', ErrorCodes::ERROR_GENERAL);
            }
            if (self::$user->getUserLastUpdate() === null) {
                try {
                    $this->fireEvent('sso:lastUserUpdateEmpty', $this, self::$user);
                } catch (\Exception $ex) {
                    self::$user = false;
                }
            }
        } else {
            throw new SsoException(sprintf('Le login %s n\'existe pas !', $p_login), ErrorCodes::ERROR_LOGIN_NOTFOUND);
        }
        try {
            $this->fireEvent('sso:afterSigninByLoginAndPassword', $user);
        } catch (\Exception $ex) {
        }
        return true;
    }

    /**
     * Logout current user
     *
     * @throws \FreeSSO\SsoException
     */
    public function logout()
    {
        $user = self::$user;
        if (self::$session instanceof SSOSession) {
            self::$session
                ->setUserId(null)
                ->setSessContent(null)
            ;
            self::$session->save();
        } else {
            throw new SsoException('Erreur : impossible de trouver la session !', ErrorCodes::ERROR_GENERAL);
        }
        $this->fireEvent('sso:afterUserLogout', $user);
    }

    /**
     * Get current loggedin user
     *
     * @throws SsoException
     *
     * @param boolean $p_light
     *
     * @return \FreeSSO\Model\User
     */
    public function getUser($p_light = false)
    {
        if (self::$user === null) {
            self::$user = false;
            if (self::$session instanceof SSOSession) {
                if (self::$session->getUserId() !== null) {
                    self::$user = User::getFirst(array(
                        'user_id' => self::$session->getUserId()
                    ));
                    if (self::$user instanceof User) {
                        if (self::$user->getUserLastUpdate() === null) {
                            try {
                                $this->fireEvent('sso:lastUserUpdateEmpty', self::$user);
                            } catch (\Exception $ex) {
                                self::$user = false;
                            }
                        }
                    } else {
                        self::$user = false;
                    }
                }
            } else {
                throw new SsoException('Erreur : impossible de trouver la session !', ErrorCodes::ERROR_GENERAL);
            }
        }
        return self::$user;
    }

    /**
     * Get Broker
     *
     * @return \FreeSSO\Model\Broker
     */
    protected function getBroker()
    {
        return self::$broker;
    }

    /**
     * Update session
     *
     * @param string $p_sess_id
     */
    protected function touchSession($p_sess_id)
    {
        $mySession = SSOSession::getFirst(array('sess_id' => $p_sess_id));
        if ($mySession instanceof SSOSession) {
            $mySession
                ->setSessTouch(\FreeFW\Tools\Date::getServerDatetime())
                ->setSessEnd(\FreeFW\Tools\Date::getServerDatetime(60*24))
            ;
            $mySession->save();
        }
        SSOSession::delete(
            array(
                'sess_end' => array(
                    \FreeFW\Model\AbstractStorage::FIND_LOWER => \FreeFW\Tools\Date::getCurrentTimestamp()
                )
            )
        );
    }

    /**
     *
     * @param string $p_key
     * @param string $p_ssoId
     * @param string $p_appId
     * @param string $p_ip
     */
    protected function verifyBrokerSession($p_key, $p_ssoId, $p_appId, $p_ip)
    {
        $addNewBrokerSession = true;
        // Read if application session exists
        $myBrokerSession = BrokerSession::getFirst(array(
            'brs_token' => $p_appId
        ));
        if ($myBrokerSession instanceof BrokerSession) {
            $addNewBrokerSession = false;
            // Must be for the same IP
            if ($myBrokerSession->getBrsClientAddress() != $p_ip) {
                $myBrokerSession->remove();
                $addNewBrokerSession = true;
            } else {
                // Must be the same SSO id
                if ($myBrokerSession->getBrsSessionId() != $p_ssoId) {
                    $myBrokerSession->remove();
                    $addNewBrokerSession = true;
                } else {
                    // Expired, delete ?
                    if (strtotime($myBrokerSession->getBrsEnd()) < time()) {
                        $myBrokerSession->remove();
                        $addNewBrokerSession = true;
                    } else {
                        $myBrokerSession->setBrsEnd(
                            \FreeFW\Tools\Date::getCurrentTimestamp(\FreeSSO\Constants::BROKER_SESSION_LIFETIME)
                        );
                        $myBrokerSession->save();
                        // Need to touch the session too...
                        $this->touchSession($myBrokerSession->getSessId());
                        self::$session = SSOSession::getFirst(array(
                            'sess_id' => $myBrokerSession->getSessId()
                        ));
                    }
                }
            }
        }
        if ($addNewBrokerSession) {
            // First, is there a session for the same SSO id ?
            $myBrokerSession = BrokerSession::getFirst(array(
                'brs_id' => $p_ssoId
            ));
            self::$session = null;
            if ($myBrokerSession instanceof BrokerSession) {
                if ($myBrokerSession->getBrsClientAddress() == $p_ip) {
                    // We share the same session
                    self::$session = SSOSession::getFirst(array('sess_id'=>$myBrokerSession->getSessId()));
                }
            }
            if (!self::$session instanceof SSOSession) {
                self::$session = new SSOSession();
                self::$session
                    ->setSessStart(\FreeFW\Tools\Date::getCurrentTimestamp())
                    ->setSessClientAddr($p_ip)
                ;
                if (self::$session->create() === false) {
                    // @TODO
                }
            }
            $myBrokerSession = new BrokerSession();
            $myBrokerSession
                ->setBrkKey($p_key)
                ->setBrsToken($p_appId)
                ->setBrsSessionId($p_ssoId)
                ->setBrsClientAddress($p_ip)
                ->setSessId(self::$session->getSessId())
                ->setBrsEnd(\FreeFW\Tools\Date::getCurrentTimestamp(\FreeSSO\Constants::BROKER_SESSION_LIFETIME))
            ;
            if ($myBrokerSession->create() === false) {
                // @TODO
            }
        }
        BrokerSession::delete(
            array(
                'brs_end' => array(
                    \FreeFW\Model\AbstractStorage::FIND_LOWER => \FreeFW\Tools\Date::getServerDatetime()
                )
            )
        );
    }

    /**
     * Return identifier
     *
     * @return string | boolean
     */
    public function getIdentifier()
    {
        if (self::$broker instanceof \FreeSSO\Model\Broker) {
            return self::$broker->getBrkKey();
        }
        return false;
    }

    /**
     * Return broker id
     *
     * @return number
     */
    public function getBrokerId()
    {
        if (self::$broker instanceof \FreeSSO\Model\Broker) {
            return self::$broker->getBrkId();
        }
        return false;
    }

    /**
     * Return configuration
     *
     * @return array | boolean
     */
    public function getConfiguration()
    {
        if (self::$broker instanceof \FreeSSO\Model\Broker) {
            $cfg = self::$broker->getBrkConfig();
            return json_decode($cfg, true);
        }
        return false;
    }

    /**
     * Update broker configuration
     *
     * @param array $p_config
     *
     * @return mixed|boolean
     */
    public function updateConfiguration($p_config)
    {
        if (self::$broker instanceof \FreeSSO\Model\Broker) {
            $cfg = json_encode($p_config);
            self::$broker->setBrkConfig($cfg);
            return self::$broker->save();
        }
        return false;
    }

    /**
     * Return private key
     *
     * @return string | boolean
     */
    public function getPrivateKey()
    {
        if (self::$broker instanceof \FreeSSO\Model\Broker) {
            return self::$broker->getBrkCertificate();
        }
        return false;
    }

    /**
     * Get password token and email
     *
     * @param string $p_login
     *
     * @throws SsoException
     */
    public function getUserPasswordToken($p_login)
    {
        $user = User::getFirst(
            array(
                'user_login' => $p_login
            )
        );
        if ($user instanceof User) {
            // First delete olders
            $olders = PasswordToken::find(
                array(
                    'user_id'   =>  $user->getUserId(),
                    'ptok_used' => 0
                )
            );
            foreach ($olders as $oneToken) {
                if (!$oneToken->remove()) {
                    return false;
                }
            }
            // New one
            $data          = array();
            $token         = md5(uniqid(microtime(true)));
            $data['email'] = $user->getUserEmail();
            $data['token'] = $token;
            $pToken        = new PasswordToken();
            $pToken
                ->setPtokToken($token)
                ->setPtokEmail($user->getUserEmail())
                ->setUserId($user->getUserId())
                ->setPtokRequestIp('')
                ->setPtokUsed(0)
                ->setPtokEnd(\FreeFW\Tools\Date::getServerDatetime(60))
            ;
            if ($pToken->create()) {
                return $data;
            }
        } else {
            throw new SsoException(sprintf('Le login %s n\'existe pas !', $p_login), ErrorCodes::ERROR_LOGIN_NOTFOUND);
        }
        return false;
    }

    /**
     * Verify password token and return user
     *
     * @param string $p_token
     *
     * @return false | User
     */
    public function getUserFromPasswordToken($p_token)
    {
        $token = PasswordToken::getFirst(
            array(
                'ptok_token' => [\FreeFW\Model\AbstractStorage::FIND_EQUAL => $p_token],
                'ptok_used'  => [\FreeFW\Model\AbstractStorage::FIND_EQUAL => 0],
                'ptok_end'   => [\FreeFW\Model\AbstractStorage::FIND_GREATER => \FreeFW\Tools\Date::getServerDatetime()]
            )
        );
        if ($token instanceof PasswordToken) {
            $user = User::getFirst(
                array(
                    'user_id' => $token->getUserId()
                )
            );
            if ($user instanceof User) {
                return $user;
            }
        }
        return false;
    }

    /**
     * Retourne un utilisateur selon son identifiant
     *
     * @param string $p_id
     *
     * @return \FreeSSO\Model\User|false
     */
    public function getUserById($p_id)
    {
        $user = User::getFirst(array(
            'user_id' => $p_id
        ));
        if ($user instanceof User) {
            if ($user->getUserLastUpdate() === null) {
                try {
                    $this->fireEvent('sso:lastUserUpdateEmpty', $this, $user);
                } catch (\Exception $ex) {
                    $user = false;
                }
            }
            return $user;
        }
        return false;
    }

    /**
     * Retourne un utilisateur selon son login
     *
     * @param string $p_login
     *
     * @return \FreeSSO\Model\User|boolean
     */
    public function getUserByLogin($p_login)
    {
        $user = User::getFirst(array(
            'user_login' => $p_login
        ));
        if ($user instanceof User) {
            if ($user->getUserLastUpdate() === null) {
                try {
                    $this->fireEvent('sso:lastUserUpdateEmpty', $this, $user);
                } catch (\Exception $ex) {
                    $user = false;
                }
            }
        }
        return $user;
    }

    /**
     * Change user password with its password token
     *
     * @param string $p_token
     * @param string $p_password
     *
     * @throws SsoException
     */
    public function changeUserPasswordFromToken($p_token, $p_password)
    {
        if (false !== ($user = $this->getUserFromPasswordToken($p_token))) {
            $user->setUserPassword(md5($user->getUserSalt() . $p_password));
            $user->save();
            $token = PasswordToken::getFirst(
                array(
                    'ptok_token' => $p_token
                )
            );
            if ($token instanceof PasswordToken) {
                $token->setPtokUsed(1);
                return $token->save();
            }
        }
        throw new SsoException(sprintf('Le token %s n\'existe pas !', $p_token), ErrorCodes::ERROR_TOKEN_NOTFOUND);
    }

    /**
     * Change user password
     *
     * @param User   $p_user
     * @param string $p_old
     * @param string $p_password
     *
     * @throws SsoException
     */
    public function changeUserPassword($p_user, $p_old, $p_password)
    {
        $oldCalc = md5($p_user->getUserSalt() . $p_old);
        if (strcmp($p_user->getUserPassword(), $oldCalc) == 0) {
            $p_user->setUserPassword(md5($p_user->getUserSalt() . $p_password));
            $p_user->save();
            return true;
        } else {
            throw new SsoException('Mot de passe incorrect !', ErrorCodes::ERROR_PASSWORD_WRONG);
        }
        throw new SsoException('Mot de passe incorrect !', ErrorCodes::ERROR_PASSWORD_WRONG);
        return false;
    }

    /**
     * Register new user
     *
     * @param string  $p_login
     * @param string  $p_email
     * @param string  $p_password
     * @param array   $p_datas
     * @param boolean $p_withValidation
     *
     * @throws \FreeSSO\SsoException
     *
     * @return \FreeSSO\Model\User|boolean
     */
    public function registerNewUser($p_login, $p_email, $p_password, $p_datas = array(), $p_withValidation = false)
    {
        try {
            $this->fireEvent('sso:beforeRegisterNewUser');
        } catch (\Exception $ex) {
        }
        //On s'assure que l'utilisateur n'existe pas
        $allUsers = User::find();
        foreach ($allUsers as $item) {
            if ($item->getUserLogin() === $p_login) {
                throw new SsoException(
                    sprintf('L\'utilisateur avec le login %s n\'existe pas !', $p_login),
                    ErrorCodes::ERROR_LOGIN_EXISTS
                );
            }
        }
        $user = new User();
        $user->getFromRecord($p_datas);
        $user
            ->setUserLogin($p_login)
            ->setUserEmail($p_email)
            ->createNewPassword($p_password)
            ->setUserActive(1)
        ;
        if ($p_withValidation) {
            $user
                ->setUserActive(0)
                ->setUserValLogin($p_login)
                ->setUserValString(md5(uniqid($p_login)))
                ->setUserValEnd(\FreeFW\Tools\Date::getServerDatetime(60*24*2))
            ;
        }
        if ($user->create()) {
            try {
                $this->fireEvent('sso:afterRegisterNewUser', $user);
            } catch (\Exception $ex) {
                // @todo
            }
            return $user;
        }
        return false;
    }

    /**
     * Mise à jour d'un utillsateur
     *
     * @param string $p_user
     * @param array  $p_datas
     */
    public function updateUser($p_user, $p_datas = array())
    {
        try {
            //On modifie dabord le password
            //effet de bord, verifie que les mots de passes tapé soit correct
            //sinon exception
            $bool = $this->changeUserPassword($p_user, $p_datas['oldPWD'], $p_datas['PWD']);
            if (strcmp($p_datas['PWD'], $p_datas['PWD2']) !== 0) {
                throw new SsoException(
                    sprintf('Erreur : les mots de passe sont differents !'),
                    ErrorCodes::ERROR_PASSWORD_WRONG
                );
            }
        } catch (\Exception $ex) {
            throw new SsoException(sprintf('Erreur de mot de passes!'), ErrorCodes::ERROR_PASSWORD_WRONG);
        }
        if ($bool) {
            $p_user = $this->updateUserFields($p_user, $p_datas);
            if ($p_user->save()) {
                try {
                    $this->fireEvent('sso:afterUpdateUser', $p_user);
                } catch (\Exception $ex) {
                    //var_dump($ex);
                    //die;
                    // @todo
                }
                return $p_user;
            }
        }
        return false;
    }

    /**
     * Send a validation email
     *
     * @param \FreeSSO\Model\User $p_user
     *
     * @return boolean
     */
    public function sendValidationEmail($p_user)
    {
        $p_user
            ->setUserValString(md5(uniqid('valid')))
            ->setUserValEnd(\FreeFW\Tools\Date::getServerDatetime(60*24*2))
        ;
        if ($p_user->save()) {
            // Send email
            return true;
        }
        return false;
    }

    /**
     * Validate account
     *
     * @param string $p_token
     *
     * @throws SsoException
     *
     * @return \FreeSSO\Model\User|boolean
     */
    public function validateAccount($p_token)
    {
        $user = User::getFirst(
            array(
                'user_val_string' => $p_token
            )
        );
        if ($user) {
            // @todo : $user->getUserValEnd();
            $user
                ->setUserActive(1)
                ->setUserValString(null)
                ->setUserValEnd(null)
            ;
            if (!$user->save()) {
                throw new SsoException('Impossible de valider le compte !', ErrorCodes::ERROR_MODIFICATION);
            }
        } else {
            throw new SsoException(sprintf('Token de validation %s non trouvé !', $p_token), ErrorCodes::ERROR_TOKEN_NOT_FOUND);
        }
        return $user;
    }

    /**
     * Sets users avatar
     *
     * @param string $p_userId
     * @param string $p_avatar
     *
     * @throws SsoException
     *
     * @return \FreeSSO\Model\User|boolean
     */
    public function setUserAvatar($p_userId, $p_avatar)
    {
        $user = User::getFirst(
            array(
                'user_id' => $p_userId
            )
        );
        if (!$user instanceof User) {
            throw new SsoException(
                sprintf('l\'utilisateur avec le mot de passe %s n\'existe pas !', $p_login),
                ErrorCodes::ERROR_LOGIN_EXISTS
            );
        }
        $user->setUserAvatar($p_avatar);
        if ($user->save()) {
            return $user;
        }
        return false;
    }

    /**
     * Vérification de la route
     *
     * @param \FreeFW\Router\Route $p_route
     *
     * @return boolean
     */
    public function verifyAccess(\FreeFW\Router\Route $p_route)
    {
        if ($p_route->getSecure() == \FreeFW\Router\Route::SECURE_PUBLIC) {
            return true;
        } else {
            try {
                $user = $this->getUser();
            } catch (\Exception $ex) {
                $user = false;
            }
            if ($user !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Verify that the required scope is present in the broker's config
     *
     * @param string $p_scope
     *
     * @return boolean
     */
    public function verifyApiScope($p_scope)
    {
        $broker = $this->getBroker();
        if ($broker instanceof Broker) {
            return $broker->verifyApiScope($p_scope);
        }
        return false;
    }

    /**
     * Update user fields
     *
     * @param User  $p_user
     * @param array $p_datas
     *
     * @return User
     */
    protected function updateUserFields($p_user, $p_datas = array())
    {
        foreach ($p_datas as $key => $value) {
            switch (strtolower($key)) {
                case 'title':
                    $p_user->setUserTitle($value);
                    break;
                case 'firstname':
                    $p_user->setUserFirstName($value);
                    break;
                case 'lastname':
                    $p_user->setUserLastName($value);
                    break;
                default:
                    $p_user->putInCache($key, $value);
                    break;
            }
        }
        return $p_user;
    }
}
