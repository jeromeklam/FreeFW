<?php
/**
 * ... ...
 * @author jeromeklam
 */
namespace FreeSSO\Model;

/**
 * Classe : User
 * @author : jeromeklam
 */
class User extends \FreeSSO\Model\Raw\User implements \FreeFW\Interfaces\User
{

    /**
     * Quota SMS pour un groupe !!
     * @var number
     */
    protected $sms_user = false;

    /**
     * User accounts
     * @var array
     */
    protected $user_accounts = [];

    /**
     * Les liens
     * @var array
     */
    protected $links = null;

    /**
     * User key : extern id
     * @var string
     */
    protected $user_key = null;

    /**
     * Retourne le quota SMS
     *
     * @return \FreeFW\Sms\Model\User
     */
    public function getSmsUser()
    {
        return $this->sms_user;
    }

    /**
     * Affectation du quota SMS
     *
     * @param \FreeFW\Sms\Model\User $sms_user
     *
     * @return User
     */
    public function setSmsUser($sms_user)
    {
        $this->sms_user = $sms_user;
        return $this;
    }

    /**
     * Vérifie l'accès à un role
     *
     * @param string $p_role
     *
     * @return boolean
     */
    public function hasRole($p_role)
    {
        if (strpos($this->getRoles(), $p_role) !== false) {
            return true;
        }
        return false;
    }

    /**
     * Is active ?
     *
     * @return boolean
     */
    public function isActive()
    {
        if (isset($this->user_active) && ($this->user_active == 1 ||
            in_array(strtoupper($this->user_active), array('O', 'Y', '1')))) {
            return true;
        }
        return false;
    }

    /**
     * Titles
     *
     * @return array
     */
    public static function getTitles()
    {
        return array(self::TITLE_MISTER, self::TITLE_MADAM, self::TITLE_MISS, self::TITLE_OTHER);
    }

    /**
     * Types
     *
     * @return array
     */
    public static function getTypes()
    {
        return array(self::TYPE_USER, self::TYPE_IP, self::TYPE_ANONYMOUS, self::TYPE_UUID);
    }

    /**
     * Add key to cache
     *
     * @param string $p_key
     * @param mixed  $p_value
     *
     * @return User
     */
    public function putInCache($p_key, $p_value)
    {
        if ($this->cache === null) {
            $cache = array();
        } else {
            $cache = json_decode($this->cache, true);
        }
        $cache[$p_key] = $p_value;
        $this->cache   = json_encode($cache);
        return $this;
    }

    /**
     * Get value from cache
     *
     * @param string $p_key
     *
     * @return false | mixed
     */
    public function getFromCache($p_key)
    {
        if ($this->cache !== null) {
            $cache = json_decode($this->cache, true);
            if (array_key_exists($p_key, $cache)) {
                return $cache[$p_key];
            }
        }
        return false;
    }

    /**
     * Get cache as array
     *
     * @return array
     */
    public function getCacheAsArray()
    {
        if ($this->user_cache !== null) {
            $cache = json_decode($this->user_cache, true);
            return $cache;
        }
        return array();
    }

    /**
     * return user as array
     *
     * @param array $p_titles
     *
     * @return array
     */
    public function asArray($p_titles = array())
    {
        $arr = array(
            'id'        => $this->getUserId(),
            'title'     => $this->getUserTitle($p_titles),
            'firstname' => $this->getUserFirstName(),
            'lastname'  => $this->getUserLastName(),
            'email'     => $this->getUserEmail(),
            'login'     => $this->getUserLogin(),
            'active'    => $this->isActive(),
            'actToken'  => $this->getUserValString()
        );
        if ($this->getSmsUser() !== false) {
            $arr['smsUser'] = $this->getSmsUser();
        }
        return array_merge($arr, $this->getCacheAsArray());
    }

    /**
     * Verify user password
     *
     * @param string $p_password
     *
     * @return boolean
     */
    public function verifyPassword($p_password)
    {
        $testPassword = md5($this->getUserSalt() . $p_password);
        if ($testPassword == $this->getUserPassword()) {
            return true;
        }
        return false;
    }

    /**
     * Create new password with new salt
     *
     * @param string $p_password
     *
     * @return \FreeSSO\Model\User
     */
    public function createNewPassword($p_password)
    {
        $this->setUserSalt(md5(uniqid()));
        $this->setUserPassword(md5($this->getUserSalt() . $p_password));
        return $this;
    }

    /**
     * Update one cache key
     *
     * @param string $p_key
     * @param mixed $p_value
     *
     * @return \FreeSSO\Model\User
     */
    public function updateCacheKey($p_key, $p_value)
    {
        $cache         = $this->getCacheAsArray();
        $cache[$p_key] = $p_value;
        $this->setUserCache($cache);
        return $this;
    }

    /**
     * Affichage des infos
     *
     * @return string
     */
    public function displayInfos()
    {
        $infos  = '<ul>';
        $infos .= '<li>Nom : ' . $this->user_last_name . '</li>';
        $infos .= '<li>Prénom : ' . $this->user_first_name . '</li>';
        $infos .= '<li>Email : ' . $this->user_email . '</li>';
        $infos .= '<ul>';
        return $infos;
    }

    /**
     * Confirmation du mot de passe
     *
     * @param string $p_confirm
     *
     * @return \FreeSSO\Model\User
     */
    public function setConfirm($p_confirm)
    {
        $this->confirm = $p_confirm;
        return $this;
    }

    /**
     * Retourne le mot de passe de confirmation
     *
     * @return string
     */
    public function getConfirm()
    {
        return $this->confirm;
    }

    /**
     * @See \FreeFW\Behaviour\Validation
     */
    protected function validate()
    {
        $check = parent::validate();
        if (intval($this->user_id) <= 0) {
            if ($this->user_password != $this->confirm) {
                $this
                    ->addValidationError('user_password', '', 'form.user.error.passworddiffer')
                    ->addValidationError('confirm', '', 'form.user.error.passworddiffer')
                ;
                $check = false;
            }
        }
        return $check;
    }

    /**
     * Sérialise l'object
     *
     * @return array
     */
    public function __toFields()
    {
        $main = parent::__toFields();
        $main = array_merge($this->getCacheAsArray(), $main);
        return $main;
    }

    /**
     * Actif
     *
     * @return string
     */
    public function displayUserActive()
    {
        if ($this->user_active) {
            return 'Oui';
        }
        return 'Non';
    }

    /**
     * Before delete
     *
     * @return boolean
     */
    public function beforeDelete()
    {
        \FreeSSO\Model\LinkUser::delete(
            array(
                'user_id' => $this->getUserId()
            )
        );
        \FreeSSO\Model\GroupUser::delete(
            array(
                'user_id' => $this->getUserId()
            )
        );
        \FreeSSO\Model\Session::update(
            array(
                'user_id' => null
            ),
            array(
                'user_id' => $this->getUserId()
            )
        );
        return true;
    }

    /**
     * Return user accounts
     *
     * @return array
     */
    public function getUserAccounts()
    {
        $this->user_accounts = [];
        $list = \FreeSSO\Model\GroupUser::find(
            array(
                'user_id' => $this->getUserId()
            )
        );
        foreach ($list as $idx => $oneAccount) {
            $this->user_accounts[$oneAccount->getGrpId()] = $oneAccount->getAccounts();
        }
        //
        return $this->user_accounts;
    }

    /**
     * Accounts fro broker
     *
     * @return array
     */
    public function getUserBrokerAccounts()
    {
        $accounts = [];
        $sso      = self::getDIShared('sso');
        if ($sso) {
            $list = \FreeSSO\Model\LinkUser::find(
                array(
                    'user_id' => $this->getUserId(),
                    'brk_id'  => $sso->getBrokerId()
                )
            );
            foreach ($list as $idx => $oneLink) {
                $accounts = array_merge_recursive($accounts, $oneLink->getAccounts());
            }
        }
        return $accounts;
    }

    /**
     * Return user accounts
     *
     * @return array
     */
    public function getUserAccountsAsArray()
    {
        $arr = $this->getUserAccounts();
        $ret = [];
        foreach ($arr as $idx => $accs) {
            $ret[$idx] = [];
            foreach ($accs as $idx2 => $acc) {
                $ret[$idx][] = $acc;
            }
        }
        return $ret;
    }

    /**
     * Retourne l'identifiant
     *
     * @return number
     */
    public function getId()
    {
        return $this->getUserId();
    }

    /**
     * Libellé pour l'affichage
     *
     * @return string
     */
    public function getDisplay()
    {
        return $this->getUserLogin();
    }

    /**
     * Retourne tous les liens
     *
     * @return array
     */
    public function getLinks()
    {
        if ($this->links === null) {
            $this->links = \FreeSSO\Model\LinkUser::find(
                [
                    "user_id" => $this->getUserId()
                ]
            );
        }
        return $this->links;
    }

    /**
     * Retourne les liens par type
     *
     * @param string $p_type
     * @param number $p_brk_id
     *
     * @return array
     */
    public function getLinksByTypeAndBroker($p_type, $p_brk_id)
    {
        $links = [];
        $all   = $this->getLinks();
        foreach ($all as $idx => $link) {
            if (strcasecmp($link->getLkuPartnerType(), $p_type) === 0) {
                if ($link->getBrkId() == $p_brk_id) {
                    $links[] = $link;
                }
            }
        }
        return $links;
    }

    /**
     * Retourne un compte
     *
     * @param string $p_type
     *
     * @return \FreeSSO\Model\UserAccount
     */
    public function  getAccount($p_type)
    {
        $account  = false;
        $accounts = $this->getUserBrokerAccounts();
        foreach ($accounts as $idx => $oneAccount) {
            if ($oneAccount->getType() == $p_type) {
                $account = $oneAccount;
                break;
            }
        }
        return $account;
    }

    /**
     * Retourne le quota SMS
     *
     * @return \FreeSSO\Model\UserQuota
     */
    public function getSmsQuota()
    {
        $oQuota = null;
        try {
            $smsService = $this::getDIService('FreeFW.Sms::Sms');
            $account    = $this->getAccount(\FreeSSO\Model\UserAccount::TYPE_SMS);
            if ($account !== false) {
                $smsUser = $smsService->getSmsQuota(
                    $account->getProvider(),
                    $account->getKey1(),
                    $account->getKey2()
                );
                $oQuota = new \FreeSSO\Model\UserQuota();
                $oQuota
                    ->setUserId($this->getUserId())
                    ->setQuota($smsUser->getUserQuotaLeft())
                ;
            }
        } catch (\Exception $ex) {
            // @todo
        }
        return $oQuota;
    }

    /**
     * Complément Json
     *
     * @param \FreeSSO\Model\User $p_user
     *
     * @return array
     */
    public static function getJsonComplement($p_user)
    {
        $cpl = [
            'user_accounts' => $p_user->getUserAccountsAsArray()
        ];
        if ($p_user->getSmsUser() !== false && $p_user->getSmsUser() !== null) {
            $cpl['user_sms_account'] = $p_user->getSmsUser()->__toArray();
        }
        return $cpl;
    }

    /**
     * Affectation de la clef externe
     *
     * @param string $p_key
     *
     * @return \FreeSSO\Model\User
     */
    public function setUserKey($p_key)
    {
        $this->user_key = $p_key;
        return $this;
    }

    /**
     * Retourne la clef utilisateur
     *
     * @return string
     */
    public function getUserKey()
    {
        return $this->user_key;
    }
}
