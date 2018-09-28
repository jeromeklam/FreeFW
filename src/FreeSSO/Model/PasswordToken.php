<?php
/**
 * ... ...
 * @author jeromeklam
 */
namespace FreeSSO\Model;

/**
 * Classe : PasswordToken
 * @author : jeromeklam
 */
class PasswordToken extends \FreeFW\Model\AbstractPDOStorage
{

    /**
     * ptok_id
     * @var Bigint
     */
    protected $ptok_id = null;
    const DESC_PTOK_ID = array(
        'name'   => 'ptok_id',
        'column' => 'ptok_id',
        'field'  => 'ptok_id',
        'type'   => \FreeFW\Constants::TYPE_BIGINT,
        'camel'  => 'PtokId',
        'snake'  => 'ptok_id',
        'getter' => 'getPtokId',
        'setter' => 'setPtokId',
        'search' => false,
        'key'    => true,
        'uniq'   => true
    );
    /**
     * ptok_token
     * @var String
     */
    protected $ptok_token = null;
    const DESC_PTOK_TOKEN = array(
        'name'   => 'ptok_token',
        'column' => 'ptok_token',
        'field'  => 'ptok_token',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'PtokToken',
        'snake'  => 'ptok_token',
        'getter' => 'getPtokToken',
        'setter' => 'setPtokToken',
        'search' => true
    );
    /**
     * ptok_used
     * @var String
     */
    protected $ptok_used = null;
    const DESC_PTOK_USED = array(
        'name'   => 'ptok_used',
        'column' => 'ptok_used',
        'field'  => 'ptok_used',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'PtokUsed',
        'snake'  => 'ptok_used',
        'getter' => 'getPtokUsed',
        'setter' => 'setPtokUsed',
        'search' => false
    );
    /**
     * ptok_email
     * @var String
     */
    protected $ptok_email = null;
    const DESC_PTOK_EMAIL = array(
        'name'   => 'ptok_email',
        'column' => 'ptok_email',
        'field'  => 'ptok_email',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'PtokEmail',
        'snake'  => 'ptok_email',
        'getter' => 'getPtokEmail',
        'setter' => 'setPtokEmail',
        'search' => true
    );
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
     * ptok_request_ip
     * @var String
     */
    protected $ptok_request_ip = null;
    const DESC_PTOK_REQUEST_IP = array(
        'name'   => 'ptok_request_ip',
        'column' => 'ptok_request_ip',
        'field'  => 'ptok_request_ip',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'PtokRequestIp',
        'snake'  => 'ptok_request_ip',
        'getter' => 'getPtokRequestIp',
        'setter' => 'setPtokRequestIp',
        'search' => true
    );
    /**
     * ptok_resolve_ip
     * @var String
     */
    protected $ptok_resolve_ip = null;
    const DESC_PTOK_RESOLVE_IP = array(
        'name'   => 'ptok_resolve_ip',
        'column' => 'ptok_resolve_ip',
        'field'  => 'ptok_resolve_ip',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'PtokResolveIp',
        'snake'  => 'ptok_resolve_ip',
        'getter' => 'getPtokResolveIp',
        'setter' => 'setPtokResolveIp',
        'search' => true
    );
    /**
     * ptok_end
     * @var Timestamp
     */
    protected $ptok_end = null;
    const DESC_PTOK_END = array(
        'name'   => 'ptok_end',
        'column' => 'ptok_end',
        'field'  => 'ptok_end',
        'type'   => \FreeFW\Constants::TYPE_DATETIME,
        'camel'  => 'PtokEnd',
        'snake'  => 'ptok_end',
        'getter' => 'getPtokEnd',
        'setter' => 'setPtokEnd',
        'search' => false
    );
    /**
     * Source
     * @var String
     */
    protected static $source = 'FreeFW_passwords_tokens';

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
            'ptok_id'         => self::DESC_PTOK_ID,
            'ptok_token'      => self::DESC_PTOK_TOKEN,
            'ptok_used'       => self::DESC_PTOK_USED,
            'ptok_email'      => self::DESC_PTOK_EMAIL,
            'user_id'         => self::DESC_USER_ID,
            'ptok_request_ip' => self::DESC_PTOK_REQUEST_IP,
            'ptok_resolve_ip' => self::DESC_PTOK_RESOLVE_IP,
            'ptok_end'        => self::DESC_PTOK_END
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
            'ptok_id'         => self::DESC_PTOK_ID,
            'ptok_token'      => self::DESC_PTOK_TOKEN,
            'ptok_used'       => self::DESC_PTOK_USED,
            'ptok_email'      => self::DESC_PTOK_EMAIL,
            'user_id'         => self::DESC_USER_ID,
            'ptok_request_ip' => self::DESC_PTOK_REQUEST_IP,
            'ptok_resolve_ip' => self::DESC_PTOK_RESOLVE_IP,
            'ptok_end'        => self::DESC_PTOK_END
        );
    }

    /**
     * Setter ptok_id
     *
     * @param bigint $p_value
     *
     * @return \FreeSSO\Model\PasswordToken
     */
    public function setPtokId($p_value)
    {
        $this->ptok_id = $p_value;
        return $this;
    }

    /**
     * Getter ptok_id
     *
     * @return bigint
     */
    public function getPtokId()
    {
        return $this->ptok_id;
    }

    /**
     * Setter ptok_token
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\PasswordToken
     */
    public function setPtokToken($p_value)
    {
        $this->ptok_token = $p_value;
        return $this;
    }

    /**
     * Getter ptok_token
     *
     * @return string
     */
    public function getPtokToken()
    {
        return $this->ptok_token;
    }

    /**
     * Setter ptok_used
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\PasswordToken
     */
    public function setPtokUsed($p_value)
    {
        $this->ptok_used = $p_value;
        return $this;
    }

    /**
     * Getter ptok_used
     *
     * @return string
     */
    public function getPtokUsed()
    {
        return $this->ptok_used;
    }

    /**
     * Setter ptok_email
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\PasswordToken
     */
    public function setPtokEmail($p_value)
    {
        $this->ptok_email = $p_value;
        return $this;
    }

    /**
     * Getter ptok_email
     *
     * @return string
     */
    public function getPtokEmail()
    {
        return $this->ptok_email;
    }

    /**
     * Setter user_id
     *
     * @param bigint $p_value
     *
     * @return \FreeSSO\Model\PasswordToken
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
     * Setter ptok_request_ip
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\PasswordToken
     */
    public function setPtokRequestIp($p_value)
    {
        $this->ptok_request_ip = $p_value;
        return $this;
    }

    /**
     * Getter ptok_request_ip
     *
     * @return string
     */
    public function getPtokRequestIp()
    {
        return $this->ptok_request_ip;
    }

    /**
     * Setter ptok_resolve_ip
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\PasswordToken
     */
    public function setPtokResolveIp($p_value)
    {
        $this->ptok_resolve_ip = $p_value;
        return $this;
    }

    /**
     * Getter ptok_resolve_ip
     *
     * @return string
     */
    public function getPtokResolveIp()
    {
        return $this->ptok_resolve_ip;
    }

    /**
     * Setter ptok_end
     *
     * @param timestamp $p_value
     *
     * @return \FreeSSO\Model\PasswordToken
     */
    public function setPtokEnd($p_value)
    {
        $this->ptok_end = $p_value;
        return $this;
    }

    /**
     * Getter ptok_end
     *
     * @return timestamp
     */
    public function getPtokEnd()
    {
        return $this->ptok_end;
    }

    /**
     * Retourne la liste des colonnes
     *
     * @return array
     */
    public static function columnMap()
    {
        return array(
            'ptok_id'         => 'ptok_id',
            'ptok_token'      => 'ptok_token',
            'ptok_used'       => 'ptok_used',
            'ptok_email'      => 'ptok_email',
            'user_id'         => 'user_id',
            'ptok_request_ip' => 'ptok_request_ip',
            'ptok_resolve_ip' => 'ptok_resolve_ip',
            'ptok_end'        => 'ptok_end'
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
            'ptok_id'
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
            'ptok_token', 'ptok_email', 'ptok_request_ip', 'ptok_resolve_ip'
        );
    }
}
