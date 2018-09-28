<?php
/**
 * ... ...
 * @author jeromeklam
 */
namespace FreeSSO\Model;

/**
 * Classe : AutologinCookie
 * @author : jeromeklam
 */
class AutologinCookie extends \FreeFW\Model\AbstractPDOStorage
{

    /**
     * user_id
     * @var Bigint
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
        'search' => false
    );
    /**
     * auto_cookie
     * @var String
     */
    protected $auto_cookie = null;
    const DESC_AUTO_COOKIE = array(
        'name'   => 'auto_cookie',
        'column' => 'auto_cookie',
        'field'  => 'auto_cookie',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'AutoCookie',
        'snake'  => 'auto_cookie',
        'getter' => 'getAutoCookie',
        'setter' => 'setAutoCookie',
        'search' => true
    );
    /**
     * auto_ip
     * @var String
     */
    protected $auto_ip = null;
    const DESC_AUTO_IP = array(
        'name'   => 'auto_ip',
        'column' => 'auto_ip',
        'field'  => 'auto_ip',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'AutoIp',
        'snake'  => 'auto_ip',
        'getter' => 'getAutoIp',
        'setter' => 'setAutoIp',
        'search' => true
    );
    /**
     * auto_paswd
     * @var String
     */
    protected $auto_paswd = null;
    const DESC_AUTO_PASWD = array(
        'name'   => 'auto_paswd',
        'column' => 'auto_paswd',
        'field'  => 'auto_paswd',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'AutoPaswd',
        'snake'  => 'auto_paswd',
        'getter' => 'getAutoPaswd',
        'setter' => 'setAutoPaswd',
        'search' => true
    );
    /**
     * Source
     * @var String
     */
    protected static $source = 'FreeFW_autologin_cookies';

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
            'user_id'     => self::DESC_USER_ID,
            'auto_cookie' => self::DESC_AUTO_COOKIE,
            'auto_ip'     => self::DESC_AUTO_IP,
            'auto_paswd'  => self::DESC_AUTO_PASWD
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
            'user_id'     => self::DESC_USER_ID,
            'auto_cookie' => self::DESC_AUTO_COOKIE,
            'auto_ip'     => self::DESC_AUTO_IP,
            'auto_paswd'  => self::DESC_AUTO_PASWD
        );
    }

    /**
     * Setter user_id
     *
     * @param bigint $p_value
     *
     * @return \FreeSSO\Model\AutologinCookie
     */
    public function setUserId($p_value)
    {
        $this->user_id = $p_value;
        return $this;
    }

    /**
     * Getter user_id
     *
     * @return bigint
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Setter auto_cookie
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\AutologinCookie
     */
    public function setAutoCookie($p_value)
    {
        $this->auto_cookie = $p_value;
        return $this;
    }

    /**
     * Getter auto_cookie
     *
     * @return string
     */
    public function getAutoCookie()
    {
        return $this->auto_cookie;
    }

    /**
     * Setter auto_ip
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\AutologinCookie
     */
    public function setAutoIp($p_value)
    {
        $this->auto_ip = $p_value;
        return $this;
    }

    /**
     * Getter auto_ip
     *
     * @return string
     */
    public function getAutoIp()
    {
        return $this->auto_ip;
    }

    /**
     * Setter auto_paswd
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\AutologinCookie
     */
    public function setAutoPaswd($p_value)
    {
        $this->auto_paswd = $p_value;
        return $this;
    }

    /**
     * Getter auto_paswd
     *
     * @return string
     */
    public function getAutoPaswd()
    {
        return $this->auto_paswd;
    }

    /**
     * Retourne la liste des colonnes
     *
     * @return array
     */
    public static function columnMap()
    {
        return array(
            'user_id'     => 'user_id',
            'auto_cookie' => 'auto_cookie',
            'auto_ip'     => 'auto_ip',
            'auto_paswd'  => 'auto_paswd'
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
            'auto_cookie', 'auto_ip', 'auto_paswd'
        );
    }
}
