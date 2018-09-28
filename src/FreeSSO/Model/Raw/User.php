<?php
/**
 * ... ...
 * @author jeromeklam
 */
namespace FreeSSO\Model\Raw;

/**
 * Classe : User
 * @author : jeromeklam
 */
class User extends \FreeFW\Model\AbstractPDOStorage
{

    /**
     * User types
     * @var string
     */
    const TYPE_USER      = 'USER';
    const TYPE_IP        = 'IP';
    const TYPE_ANONYMOUS = 'ANONYMOUS';
    const TYPE_UUID      = 'UUID';
    const TYPE_REST      = 'REST';

    /**
     * User titles
     * @var string
     */
    const TITLE_MISTER = 'MISTER';
    const TITLE_MADAM  = 'MADAM';
    const TITLE_MISS   = 'MISS';
    const TITLE_OTHER  = 'OTHER';

    /**
     * user_id
     * @var number
     */
    protected $user_id = null;
    const DESC_USER_ID = array(
        'name'   => 'user_id',
        'column' => 'user_id',
        'field'  => 'user_id',
        'type'   => \FreeFW\Constants::TYPE_BIGINT,
        'camel'  => 'UserId',
        'snake'  => 'user_id',
        'getter' => 'getUserId',
        'setter' => 'setUserId',
        'search' => false,
        'key'    => true,
        'uniq'   => true
    );
    /**
     * user_login
     * @var String
     */
    protected $user_login = null;
    const DESC_USER_LOGIN = array(
        'name'     => 'user_login',
        'column'   => 'user_login',
        'field'    => 'user_login',
        'type'     => \FreeFW\Constants::TYPE_STRING,
        'camel'    => 'UserLogin',
        'snake'    => 'user_login',
        'getter'   => 'getUserLogin',
        'setter'   => 'setUserLogin',
        'required' => true,
        'uniq'     => true,
        'search'   => true
    );
    /**
     * user_password
     * @var String
     */
    protected $user_password = null;
    const DESC_USER_PASSWORD = array(
        'name'     => 'user_password',
        'column'   => 'user_password',
        'field'    => 'user_password',
        'type'     => \FreeFW\Constants::TYPE_PASSWORD,
        'camel'    => 'UserPassword',
        'snake'    => 'user_password',
        'getter'   => 'getUserPassword',
        'setter'   => 'setUserPassword',
        'json'     => false,
        'required' => true,
        'search'   => true
    );
    /**
     * user_active
     * @var String
     */
    protected $user_active = false;
    const DESC_USER_ACTIVE = array(
        'name'   => 'user_active',
        'column' => 'user_active',
        'field'  => 'user_active',
        'type'   => \FreeFW\Constants::TYPE_BOOLEAN,
        'camel'  => 'UserActive',
        'snake'  => 'user_active',
        'getter' => 'getUserActive',
        'setter' => 'setUserActive',
        'search' => false
    );
    /**
     * user_salt
     * @var String
     */
    protected $user_salt = null;
    const DESC_USER_SALT = array(
        'name'   => 'user_salt',
        'column' => 'user_salt',
        'field'  => 'user_salt',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'UserSalt',
        'snake'  => 'user_salt',
        'getter' => 'getUserSalt',
        'setter' => 'setUserSalt',
        'json'   => false,
        'search' => true
    );
    /**
     * user_email
     * @var String
     */
    protected $user_email = null;
    const DESC_USER_EMAIL = array(
        'name'     => 'user_email',
        'column'   => 'user_email',
        'field'    => 'user_email',
        'type'     => \FreeFW\Constants::TYPE_STRING,
        'camel'    => 'UserEmail',
        'snake'    => 'user_email',
        'getter'   => 'getUserEmail',
        'setter'   => 'setUserEmail',
        'required' => true,
        'uniq'     => true,
        'search'   => true
    );
    /**
     * user_first_name
     * @var String
     */
    protected $user_first_name = null;
    const DESC_USER_FIRST_NAME = array(
        'name'   => 'user_first_name',
        'column' => 'user_first_name',
        'field'  => 'user_first_name',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'UserFirstName',
        'snake'  => 'user_first_name',
        'getter' => 'getUserFirstName',
        'setter' => 'setUserFirstName',
        'search' => true
    );
    /**
     * user_last_name
     * @var String
     */
    protected $user_last_name = null;
    const DESC_USER_LAST_NAME = array(
        'name'   => 'user_last_name',
        'column' => 'user_last_name',
        'field'  => 'user_last_name',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'UserLastName',
        'snake'  => 'user_last_name',
        'getter' => 'getUserLastName',
        'setter' => 'setUserLastName',
        'search' => true
    );
    /**
     * user_title
     * @var String
     */
    protected $user_title = self::TITLE_OTHER;
    const DESC_USER_TITLE = array(
        'name'   => 'user_title',
        'column' => 'user_title',
        'field'  => 'user_title',
        'type'   => \FreeFW\Constants::TYPE_SELECT,
        'list'   => array(self::TITLE_OTHER, self::TITLE_MISTER, self::TITLE_MISS, self::TITLE_MADAM),
        'camel'  => 'UserTitle',
        'snake'  => 'user_title',
        'getter' => 'getUserTitle',
        'setter' => 'setUserTitle',
        'search' => true
    );
    /**
     * user_roles
     * @var String
     */
    protected $user_roles = null;
    const DESC_USER_ROLES = array(
        'name'   => 'user_roles',
        'column' => 'user_roles',
        'field'  => 'user_roles',
        'type'   => \FreeFW\Constants::TYPE_TEXT,
        'camel'  => 'UserRoles',
        'snake'  => 'user_roles',
        'getter' => 'getUserRoles',
        'setter' => 'setUserRoles',
        'search' => false
    );
    /**
     * user_type
     * @var String
     */
    protected $user_type = self::TYPE_USER;
    const DESC_USER_TYPE = array(
        'name'   => 'user_type',
        'column' => 'user_type',
        'field'  => 'user_type',
        'type'   => \FreeFW\Constants::TYPE_SELECT,
        'list'   => array(
            self::TYPE_USER,
            self::TYPE_ANONYMOUS,
            self::TYPE_IP,
            self::TYPE_REST,
            self::TYPE_UUID
        ),
        'camel'  => 'UserType',
        'snake'  => 'user_type',
        'getter' => 'getUserType',
        'setter' => 'setUserType',
        'search' => true
    );
    /**
     * user_ips
     * @var String
     */
    protected $user_ips = null;
    const DESC_USER_IPS = array(
        'name'   => 'user_ips',
        'column' => 'user_ips',
        'field'  => 'user_ips',
        'type'   => \FreeFW\Constants::TYPE_TEXT,
        'camel'  => 'UserIps',
        'snake'  => 'user_ips',
        'getter' => 'getUserIps',
        'setter' => 'setUserIps',
        'search' => false
    );
    /**
     * user_last_update
     * @var string
     */
    protected $user_last_update = null;
    const DESC_USER_LAST_UPDATE = array(
        'name'   => 'user_last_update',
        'column' => 'user_last_update',
        'field'  => 'user_last_update',
        'type'   => \FreeFW\Constants::TYPE_DATETIME,
        'camel'  => 'UserLastUpdate',
        'snake'  => 'user_last_update',
        'getter' => 'getUserLastUpdate',
        'setter' => 'setUserLastUpdate',
        'search' => false
    );
    /**
     * user_preferred_language
     * @var String
     */
    protected $user_preferred_language = \FreeFW\Constants::LANG_FR;
    const DESC_USER_PREFERRED_LANGUAGE = array(
        'name'   => 'user_preferred_language',
        'column' => 'user_preferred_language',
        'field'  => 'user_preferred_language',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'UserPreferredLanguage',
        'snake'  => 'user_preferred_language',
        'getter' => 'getUserPreferredLanguage',
        'setter' => 'setUserPreferredLanguage',
        'search' => true
    );
    /**
     * user_avatar
     * @var String
     */
    protected $user_avatar = null;
    const DESC_USER_AVATAR = array(
        'name'   => 'user_avatar',
        'column' => 'user_avatar',
        'field'  => 'user_avatar',
        'type'   => \FreeFW\Constants::TYPE_TEXT,
        'camel'  => 'UserAvatar',
        'snake'  => 'user_avatar',
        'getter' => 'getUserAvatar',
        'setter' => 'setUserAvatar',
        'search' => false
    );
    /**
     * user_cache
     * @var String
     */
    protected $user_cache = null;
    const DESC_USER_CACHE = array(
        'name'   => 'user_cache',
        'column' => 'user_cache',
        'field'  => 'user_cache',
        'type'   => \FreeFW\Constants::TYPE_JSON,
        'camel'  => 'UserCache',
        'snake'  => 'user_cache',
        'getter' => 'getUserCache',
        'setter' => 'setUserCache',
        'search' => false
    );
    /**
     * user_val_string
     * @var String
     */
    protected $user_val_string = null;
    const DESC_USER_VAL_STRING = array(
        'name'   => 'user_val_string',
        'column' => 'user_val_string',
        'field'  => 'user_val_string',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'UserValString',
        'snake'  => 'user_val_string',
        'getter' => 'getUserValString',
        'setter' => 'setUserValString',
        'json'   => false,
        'search' => true
    );
    /**
     * user_val_end
     * @var string
     */
    protected $user_val_end = null;
    const DESC_USER_VAL_END = array(
        'name'   => 'user_val_end',
        'column' => 'user_val_end',
        'field'  => 'user_val_end',
        'type'   => \FreeFW\Constants::TYPE_DATETIME,
        'camel'  => 'UserValEnd',
        'snake'  => 'user_val_end',
        'getter' => 'getUserValEnd',
        'setter' => 'setUserValEnd',
        'json'   => false,
        'search' => false
    );
    /**
     * user_val_login
     * @var String
     */
    protected $user_val_login = null;
    const DESC_USER_VAL_LOGIN = array(
        'name'   => 'user_val_login',
        'column' => 'user_val_login',
        'field'  => 'user_val_login',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'UserValLogin',
        'snake'  => 'user_val_login',
        'getter' => 'getUserValLogin',
        'setter' => 'setUserValLogin',
        'json'   => false,
        'search' => true
    );
    /**
     * user_cnx
     * @var String
     */
    protected $user_cnx = null;
    const DESC_USER_CNX = array(
        'name'   => 'user_cnx',
        'column' => 'user_cnx',
        'field'  => 'user_cnx',
        'type'   => \FreeFW\Constants::TYPE_JSON,
        'camel'  => 'UserCnx',
        'snake'  => 'user_cnx',
        'json'   => array(
            'title'      => 'Connexion',
            'type'       => 'object',
            'properties' => array(
                'database.host'       => array('type' => 'string'),
                'database.user'       => array('type' => 'string'),
                'database.password'   => array('type' => 'string'),
                'database.name'       => array('type' => 'string'),
                'webservice.url'      => array('type' => 'string'),
                'webservice.user'     => array('type' => 'string'),
                'webservice.password' => array('type' => 'string')
            )
        ),
        'getter' => 'getUserCnx',
        'setter' => 'setUserCnx',
        'search' => false
    );
    /**
     * confirm
     * @var String
     */
    protected $confirm = null;
    const DESC_VAL_CONFIRM = array(
        'name'     => 'confirm',
        'field'    => 'confirm',
        'column'   => false,
        'type'     => \FreeFW\Constants::TYPE_PASSWORD,
        'camel'    => 'confirm',
        'snake'    => 'confirm',
        'getter'   => 'getConfirm',
        'setter'   => 'setConfirm',
        'search'   => true
    );
    /**
     * user_extern_code
     * @var String
     */
    protected $user_extern_code = null;
    const DESC_USER_EXTERN_CODE = array(
        'name'   => 'user_extern_code',
        'column' => 'user_extern_code',
        'field'  => 'user_extern_code',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'UserExternCode',
        'snake'  => 'user_extern_code',
        'getter' => 'getUserExternCode',
        'setter' => 'setUserExternCode',
        'json'   => false,
        'search' => true
    );
    /**
     * Source
     * @var String
     */
    protected static $source = 'FreeFW_users';

    /**
     * Retourne la source
     *
     * @return string
     */
    public static function getSource()
    {
        return self::$source;
    }

    /**
     * Retourne le descriptif des colonnes par nom en db
     *
     * @return array
     */
    public static function getColumnDescByName()
    {
        return array(
            'user_id'                 => self::DESC_USER_ID,
            'user_login'              => self::DESC_USER_LOGIN,
            'user_password'           => self::DESC_USER_PASSWORD,
            'user_active'             => self::DESC_USER_ACTIVE,
            'user_salt'               => self::DESC_USER_SALT,
            'user_email'              => self::DESC_USER_EMAIL,
            'user_first_name'         => self::DESC_USER_FIRST_NAME,
            'user_last_name'          => self::DESC_USER_LAST_NAME,
            'user_title'              => self::DESC_USER_TITLE,
            'user_roles'              => self::DESC_USER_ROLES,
            'user_type'               => self::DESC_USER_TYPE,
            'user_ips'                => self::DESC_USER_IPS,
            'user_last_update'        => self::DESC_USER_LAST_UPDATE,
            'user_preferred_language' => self::DESC_USER_PREFERRED_LANGUAGE,
            'user_avatar'             => self::DESC_USER_AVATAR,
            'user_cache'              => self::DESC_USER_CACHE,
            'user_val_string'         => self::DESC_USER_VAL_STRING,
            'user_val_end'            => self::DESC_USER_VAL_END,
            'user_val_login'          => self::DESC_USER_VAL_LOGIN,
            'user_cnx'                => self::DESC_USER_CNX,
            'user_extern_code'        => self::DESC_USER_EXTERN_CODE,
        );
    }

    /**
     * Retourne le descriptif des colonnes par nom de propriété
     *
     * @return array
     */
    public static function getColumnDescByField()
    {
        return array(
            'user_id'                 => self::DESC_USER_ID,
            'user_login'              => self::DESC_USER_LOGIN,
            'user_password'           => self::DESC_USER_PASSWORD,
            'user_active'             => self::DESC_USER_ACTIVE,
            'user_salt'               => self::DESC_USER_SALT,
            'user_email'              => self::DESC_USER_EMAIL,
            'user_first_name'         => self::DESC_USER_FIRST_NAME,
            'user_last_name'          => self::DESC_USER_LAST_NAME,
            'user_title'              => self::DESC_USER_TITLE,
            'user_roles'              => self::DESC_USER_ROLES,
            'user_type'               => self::DESC_USER_TYPE,
            'user_ips'                => self::DESC_USER_IPS,
            'user_last_update'        => self::DESC_USER_LAST_UPDATE,
            'user_preferred_language' => self::DESC_USER_PREFERRED_LANGUAGE,
            'user_avatar'             => self::DESC_USER_AVATAR,
            'user_cache'              => self::DESC_USER_CACHE,
            'user_val_string'         => self::DESC_USER_VAL_STRING,
            'user_val_end'            => self::DESC_USER_VAL_END,
            'user_val_login'          => self::DESC_USER_VAL_LOGIN,
            'user_cnx'                => self::DESC_USER_CNX,
            'user_extern_code'        => self::DESC_USER_EXTERN_CODE,
            'confirm'                 => self::DESC_VAL_CONFIRM
        );
    }

    /**
     * Setter user_id
     *
     * @param number $p_value
     *
     * @return \FreeSSO\Model\User
     */
    public function setUserId($p_value)
    {
        $this->user_id = $p_value;
        return $this;
    }

    /**
     * Getter user_id
     *
     * @return number
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Setter user_login
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\User
     */
    public function setUserLogin($p_value)
    {
        $this->user_login = $p_value;
        return $this;
    }

    /**
     * Getter user_login
     *
     * @return string
     */
    public function getUserLogin()
    {
        return $this->user_login;
    }

    /**
     * Setter user_password
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\User
     */
    public function setUserPassword($p_value)
    {
        $this->user_password = $p_value;
        return $this;
    }

    /**
     * Getter user_password
     *
     * @return string
     */
    public function getUserPassword()
    {
        return $this->user_password;
    }

    /**
     * Setter user_active
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\User
     */
    public function setUserActive($p_value)
    {
        $this->user_active = $p_value;
        return $this;
    }

    /**
     * Getter user_active
     *
     * @return string
     */
    public function getUserActive()
    {
        return $this->user_active;
    }

    /**
     * Setter user_salt
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\User
     */
    public function setUserSalt($p_value)
    {
        $this->user_salt = $p_value;
        return $this;
    }

    /**
     * Getter user_salt
     *
     * @return string
     */
    public function getUserSalt()
    {
        return $this->user_salt;
    }

    /**
     * Setter user_email
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\User
     */
    public function setUserEmail($p_value)
    {
        $this->user_email = $p_value;
        return $this;
    }

    /**
     * Getter user_email
     *
     * @return string
     */
    public function getUserEmail()
    {
        return $this->user_email;
    }

    /**
     * Setter user_first_name
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\User
     */
    public function setUserFirstName($p_value)
    {
        $this->user_first_name = $p_value;
        return $this;
    }

    /**
     * Getter user_first_name
     *
     * @return string
     */
    public function getUserFirstName()
    {
        return $this->user_first_name;
    }

    /**
     * Setter user_last_name
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\User
     */
    public function setUserLastName($p_value)
    {
        $this->user_last_name = $p_value;
        return $this;
    }

    /**
     * Getter user_last_name
     *
     * @return string
     */
    public function getUserLastName()
    {
        return $this->user_last_name;
    }

    /**
     * Setter user_title
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\User
     */
    public function setUserTitle($p_value)
    {
        $this->user_title = $p_value;
        return $this;
    }

    /**
     * Getter user_title
     *
     * @return string
     */
    public function getUserTitle()
    {
        if ($this->user_title === null) {
            $this->user_title = self::TITLE_OTHER;
        }
        return $this->user_title;
    }

    /**
     * Setter user_roles
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\User
     */
    public function setUserRoles($p_value)
    {
        $this->user_roles = $p_value;
        return $this;
    }

    /**
     * Getter user_roles
     *
     * @return string
     */
    public function getUserRoles()
    {
        return $this->user_roles;
    }

    /**
     * Setter user_type
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\User
     */
    public function setUserType($p_value)
    {
        $this->user_type = $p_value;
        return $this;
    }

    /**
     * Getter user_type
     *
     * @return string
     */
    public function getUserType()
    {
        if ($this->user_type === null) {
            $this->user_type = self::TYPE_USER;
        }
        return $this->user_type;
    }

    /**
     * Setter user_ips
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\User
     */
    public function setUserIps($p_value)
    {
        $this->user_ips = $p_value;
        return $this;
    }

    /**
     * Getter user_ips
     *
     * @return string
     */
    public function getUserIps()
    {
        return $this->user_ips;
    }

    /**
     * Setter user_last_update
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\User
     */
    public function setUserLastUpdate($p_value)
    {
        $this->user_last_update = $p_value;
        return $this;
    }

    /**
     * Getter user_last_update
     *
     * @return string
     */
    public function getUserLastUpdate()
    {
        return $this->user_last_update;
    }

    /**
     * Setter user_preferred_language
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\User
     */
    public function setUserPreferredLanguage($p_value)
    {
        $this->user_preferred_language = $p_value;
        return $this;
    }

    /**
     * Getter user_preferred_language
     *
     * @return string
     */
    public function getUserPreferredLanguage()
    {
        return $this->user_preferred_language;
    }

    /**
     * Setter user_avatar
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\User
     */
    public function setUserAvatar($p_value)
    {
        $this->user_avatar = $p_value;
        return $this;
    }

    /**
     * Getter user_avatar
     *
     * @return string
     */
    public function getUserAvatar()
    {
        return $this->user_avatar;
    }

    /**
     * Setter user_cache
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\User
     */
    public function setUserCache($p_value)
    {
        if (is_array($p_value)) {
            $this->user_cache = json_encode($p_value);
        } else {
            $this->user_cache = $p_value;
        }
        return $this;
    }

    /**
     * Getter user_cache
     *
     * @return string
     */
    public function getUserCache()
    {
        return $this->user_cache;
    }

    /**
     * Setter user_val_string
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\User
     */
    public function setUserValString($p_value)
    {
        $this->user_val_string = $p_value;
        return $this;
    }

    /**
     * Getter user_val_string
     *
     * @return string
     */
    public function getUserValString()
    {
        return $this->user_val_string;
    }

    /**
     * Setter user_val_end
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\User
     */
    public function setUserValEnd($p_value)
    {
        $this->user_val_end = $p_value;
        return $this;
    }

    /**
     * Getter user_val_end
     *
     * @return string
     */
    public function getUserValEnd()
    {
        return $this->user_val_end;
    }

    /**
     * Setter user_val_login
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\User
     */
    public function setUserValLogin($p_value)
    {
        $this->user_val_login = $p_value;
        return $this;
    }

    /**
     * Getter user_val_login
     *
     * @return string
     */
    public function getUserValLogin()
    {
        return $this->user_val_login;
    }

    /**
     * Setter user_extern_code
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Raw\User
     */
    public function setUserExternCode($p_value)
    {
        $this->user_extern_code = $p_value;
        return $this;
    }

    /**
     * Getter user_extern_code
     *
     * @return string
     */
    public function getUserExternCode()
    {
        return $this->user_extern_code;
    }

    /**
     * Setter user_cnx
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\User
     */
    public function setUserCnx($p_value)
    {
        $this->user_cnx = $p_value;
        return $this;
    }

    /**
     * Getter user_cnx
     *
     * @return string
     */
    public function getUserCnx()
    {
        $arr = json_decode($this->user_cnx, true);
        if (!is_array($arr)) {
            $arr            = array(
                'database.host'       => '',
                'database.user'       => '',
                'database.password'   => '',
                'database.name'       => '',
                'webservice.url'      => '',
                'webservice.user'     => '',
                'webservice.password' => ''
            );
            $this->user_cnx = json_encode($arr);
        }
        return $this->user_cnx;
    }

    /**
     * Retourne la liste des colonnes
     *
     * @return array
     */
    public static function columnMap()
    {
        return array(
            'user_id'                 => 'user_id',
            'user_login'              => 'user_login',
            'user_password'           => 'user_password',
            'user_active'             => 'user_active',
            'user_salt'               => 'user_salt',
            'user_email'              => 'user_email',
            'user_first_name'         => 'user_first_name',
            'user_last_name'          => 'user_last_name',
            'user_title'              => 'user_title',
            'user_roles'              => 'user_roles',
            'user_type'               => 'user_type',
            'user_ips'                => 'user_ips',
            'user_last_update'        => 'user_last_update',
            'user_preferred_language' => 'user_preferred_language',
            'user_avatar'             => 'user_avatar',
            'user_cache'              => 'user_cache',
            'user_val_string'         => 'user_val_string',
            'user_val_end'            => 'user_val_end',
            'user_val_login'          => 'user_val_login',
            'user_cnx'                => 'user_cnx',
            'user_extern_code'        => 'user_extern_code'
        );
    }

    /**
     * Retourne la liste des colonnes identifiantes
     *
     * @return array      // Tableau de propertyName
     */
    public static function columnId()
    {
        return array(
            'user_id'
        );
    }

    /**
     * Retourne les colonnes disponibles en fulltext
     *
     * @return array
     */
    public static function columnFulltext()
    {
        return array(
            'user_login', 'user_password', 'user_salt', 'user_email', 'user_first_name',
            'user_last_name', 'user_title', 'user_type', 'user_preferred_language', 'user_val_string',
            'user_val_login', 'user_extern_code'
        );
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
     * Update ine cache key
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
}
