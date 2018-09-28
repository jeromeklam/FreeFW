<?php
/**
 * ... ...
 * @author jeromeklam
 */
namespace FreeSSO\Model\Raw;

/**
 * Classe : Broker
 * @author : jerome.klam
 */
class Broker extends \FreeFW\Model\AbstractPDOStorage
{

    /**
     * brk_id
     * @var Bigint
     */
    protected $brk_id = null;
    const DESC_BRK_ID = array(
        'name'   => 'brk_id',
        'column' => 'brk_id',
        'field'  => 'brk_id',
        'type'   => \FreeFW\Constants::TYPE_BIGINT,
        'camel'  => 'BrkId',
        'snake'  => 'brk_id',
        'getter' => 'getBrkId',
        'setter' => 'setBrkId',
        'search' => false,
        'key'    => true,
        'uniq'   => true
    );
    /**
     * dom_id
     * @var number
     */
    protected $dom_id = null;
    const DESC_DOM_ID = array(
        'name'     => 'dom_id',
        'column'   => 'dom_id',
        'field'    => 'dom_id',
        'type'     => \FreeFW\Constants::TYPE_RESULTSET,
        'list'     => 'FreeFW.Sso::Broker.allDomains',
        'camel'    => 'DomId',
        'snake'    => 'dom_id',
        'getter'   => 'getDomId',
        'setter'   => 'setDomId',
        'required' => true,
        'search'   => false
    );
    /**
     * brk_key
     * @var String
     */
    protected $brk_key = null;
    const DESC_BRK_KEY = array(
        'name'     => 'brk_key',
        'column'   => 'brk_key',
        'field'    => 'brk_key',
        'type'     => \FreeFW\Constants::TYPE_STRING,
        'camel'    => 'BrkKey',
        'snake'    => 'brk_key',
        'getter'   => 'getBrkKey',
        'setter'   => 'setBrkKey',
        'required' => true,
        'search'   => true
    );
    /**
     * brk_name
     * @var String
     */
    protected $brk_name = null;
    const DESC_BRK_NAME = array(
        'name'     => 'brk_name',
        'column'   => 'brk_name',
        'field'    => 'brk_name',
        'type'     => \FreeFW\Constants::TYPE_STRING,
        'camel'    => 'BrkName',
        'snake'    => 'brk_name',
        'getter'   => 'getBrkName',
        'setter'   => 'setBrkName',
        'display'  => true,
        'required' => true,
        'search'   => true
    );
    /**
     * brk_certificate
     * @var String
     */
    protected $brk_certificate = null;
    const DESC_BRK_CERTIFICATE = array(
        'name'     => 'brk_certificate',
        'column'   => 'brk_certificate',
        'field'    => 'brk_certificate',
        'type'     => \FreeFW\Constants::TYPE_MD5,
        'camel'    => 'BrkCertificate',
        'snake'    => 'brk_certificate',
        'getter'   => 'getBrkCertificate',
        'setter'   => 'setBrkCertificate',
        'required' => false,
        'search'   => true
    );
    /**
     * brk_active
     * @var String
     */
    protected $brk_active = null;
    const DESC_BRK_ACTIVE = array(
        'name'   => 'brk_active',
        'column' => 'brk_active',
        'field'  => 'brk_active',
        'type'   => \FreeFW\Constants::TYPE_BOOLEAN,
        'camel'  => 'BrkActive',
        'snake'  => 'brk_active',
        'getter' => 'getBrkActive',
        'setter' => 'setBrkActive',
        'search' => false
    );
    /**
     * brk_ts
     * @var Timestamp
     */
    protected $brk_ts = null;
    const DESC_BRK_TS = array(
        'name'   => 'brk_ts',
        'column' => 'brk_ts',
        'field'  => 'brk_ts',
        'type'   => \FreeFW\Constants::TYPE_DATETIME,
        'camel'  => 'BrkTs',
        'snake'  => 'brk_ts',
        'getter' => 'getBrkTs',
        'setter' => 'setBrkTs',
        'search' => false
    );
    /**
     * brk_api_scope
     * @var String
     */
    protected $brk_api_scope = null;
    const DESC_BRK_API_SCOPE = array(
        'name'   => 'brk_api_scope',
        'column' => 'brk_api_scope',
        'field'  => 'brk_api_scope',
        'type'   => \FreeFW\Constants::TYPE_TEXT,
        'camel'  => 'BrkApiScope',
        'snake'  => 'brk_api_scope',
        'getter' => 'getBrkApiScope',
        'setter' => 'setBrkApiScope',
        'search' => false
    );
    /**
     * brk_users_scope
     * @var String
     */
    protected $brk_users_scope = null;
    const DESC_BRK_USERS_SCOPE = array(
        'name'   => 'brk_users_scope',
        'column' => 'brk_users_scope',
        'field'  => 'brk_users_scope',
        'type'   => \FreeFW\Constants::TYPE_TEXT,
        'camel'  => 'BrkUsersScope',
        'snake'  => 'brk_users_scope',
        'getter' => 'getBrkUsersScope',
        'setter' => 'setBrkUsersScope',
        'search' => false
    );
    /**
     * brk_ips
     * @var String
     */
    protected $brk_ips = null;
    const DESC_BRK_IPS = array(
        'name'   => 'brk_ips',
        'column' => 'brk_ips',
        'field'  => 'brk_ips',
        'type'   => \FreeFW\Constants::TYPE_TEXT,
        'camel'  => 'BrkIps',
        'snake'  => 'brk_ips',
        'getter' => 'getBrkIps',
        'setter' => 'setBrkIps',
        'search' => false
    );
    /**
     * brk_config
     * @var String
     */
    protected $brk_config = null;
    const DESC_BRK_CONFIG = array(
        'name'   => 'brk_config',
        'column' => 'brk_config',
        'field'  => 'brk_config',
        'type'   => \FreeFW\Constants::TYPE_JSON,
        'camel'  => 'BrkConfig',
        'snake'  => 'brk_config',
        'json'   => array(
            'title'      => 'Configuration',
            'type'       => 'object',
            'properties' => array(
                'db1.dsn'         => array('type' => 'string'),
                'db1.username'    => array('type' => 'string'),
                'db1.password'    => array('type' => 'string'),
                'db1.dbname'      => array('type' => 'string'),
                'db2.dsn'         => array('type' => 'string'),
                'db2.username'    => array('type' => 'string'),
                'db2.password'    => array('type' => 'string'),
                'db2.dbname'      => array('type' => 'string'),
                'email.fromemail' => array('type' => 'string'),
                'email.fromname'  => array('type' => 'string')
            )
        ),
        'getter' => 'getBrkConfig',
        'setter' => 'setBrkConfig',
        'search' => false
    );
    /**
     * Source
     * @var String
     */
    protected static $source = 'FreeFW_brokers';

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
            'brk_id'          => self::DESC_BRK_ID,
            'dom_id'          => self::DESC_DOM_ID,
            'brk_key'         => self::DESC_BRK_KEY,
            'brk_name'        => self::DESC_BRK_NAME,
            'brk_certificate' => self::DESC_BRK_CERTIFICATE,
            'brk_active'      => self::DESC_BRK_ACTIVE,
            'brk_ts'          => self::DESC_BRK_TS,
            'brk_api_scope'   => self::DESC_BRK_API_SCOPE,
            'brk_users_scope' => self::DESC_BRK_USERS_SCOPE,
            'brk_ips'         => self::DESC_BRK_IPS,
            'brk_config'      => self::DESC_BRK_CONFIG
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
            'brk_id'          => self::DESC_BRK_ID,
            'dom_id'          => self::DESC_DOM_ID,
            'brk_key'         => self::DESC_BRK_KEY,
            'brk_name'        => self::DESC_BRK_NAME,
            'brk_certificate' => self::DESC_BRK_CERTIFICATE,
            'brk_active'      => self::DESC_BRK_ACTIVE,
            'brk_ts'          => self::DESC_BRK_TS,
            'brk_api_scope'   => self::DESC_BRK_API_SCOPE,
            'brk_users_scope' => self::DESC_BRK_USERS_SCOPE,
            'brk_ips'         => self::DESC_BRK_IPS,
            'brk_config'      => self::DESC_BRK_CONFIG
        );
    }

    /**
     * Setter brk_id
     *
     * @param bigint $p_value
     *
     * @return \FreeSSO\Model\Broker
     */
    public function setBrkId($p_value)
    {
        $this->brk_id = $p_value;
        return $this;
    }

    /**
     * Getter brk_id
     *
     * @return bigint
     */
    public function getBrkId()
    {
        return $this->brk_id;
    }

    /**
     * Setter dom_id
     *
     * @param number $p_value
     *
     * @return \FreeSSO\Model\Broker
     */
    public function setDomId($p_value)
    {
        $this->dom_id = $p_value;
        return $this;
    }

    /**
     * Getter dom_id
     *
     * @return number
     */
    public function getDomId()
    {
        return $this->dom_id;
    }

    /**
     * Setter brk_key
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Broker
     */
    public function setBrkKey($p_value)
    {
        $this->brk_key = $p_value;
        return $this;
    }

    /**
     * Getter brk_key
     *
     * @return string
     */
    public function getBrkKey()
    {
        return $this->brk_key;
    }

    /**
     * Setter brk_name
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Broker
     */
    public function setBrkName($p_value)
    {
        $this->brk_name = $p_value;
        return $this;
    }

    /**
     * Getter brk_name
     *
     * @return string
     */
    public function getBrkName()
    {
        return $this->brk_name;
    }

    /**
     * Setter brk_certificate
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Broker
     */
    public function setBrkCertificate($p_value)
    {
        $this->brk_certificate = $p_value;
        return $this;
    }

    /**
     * Getter brk_certificate
     *
     * @return string
     */
    public function getBrkCertificate()
    {
        return $this->brk_certificate;
    }

    /**
     * Setter brk_active
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Broker
     */
    public function setBrkActive($p_value)
    {
        $this->brk_active = $p_value;
        return $this;
    }

    /**
     * Getter brk_active
     *
     * @return string
     */
    public function getBrkActive()
    {
        return $this->brk_active;
    }

    /**
     * Setter brk_ts
     *
     * @param timestamp $p_ts
     *
     * @return \FreeSSO\Model\Broker
     */
    public function setBrkTs($p_ts)
    {
        if ($p_ts !== null && $p_ts != '' && strpos($p_ts, '/') !== false) {
            $this->brk_ts= \FreeFW\Tools\Date::ddmmyyyyToMysql($p_ts);
        } else {
            $this->brk_ts= $p_ts;
        }
        return $this;
    }

    /**
     * Getter brk_ts
     *
     * @return timestamp
     */
    public function getBrkTs()
    {
        return $this->brk_ts;
    }

    /**
     * Setter brk_api_scope
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Broker
     */
    public function setBrkApiScope($p_value)
    {
        $this->brk_api_scope = $p_value;
        return $this;
    }

    /**
     * Getter brk_api_scope
     *
     * @return string
     */
    public function getBrkApiScope()
    {
        return $this->brk_api_scope;
    }

    /**
     * Setter brk_users_scope
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Broker
     */
    public function setBrkUsersScope($p_value)
    {
        $this->brk_users_scope = $p_value;
        return $this;
    }

    /**
     * Getter brk_users_scope
     *
     * @return string
     */
    public function getBrkUsersScope()
    {
        return $this->brk_users_scope;
    }

    /**
     * Setter brk_ips
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Broker
     */
    public function setBrkIps($p_value)
    {
        $this->brk_ips = $p_value;
        return $this;
    }

    /**
     * Getter brk_ips
     *
     * @return string
     */
    public function getBrkIps()
    {
        return $this->brk_ips;
    }

    /**
     * Set config
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Raw\Broker
     */
    public function setBrkConfig($p_value)
    {
        $this->brk_config = $p_value;
        return $this;
    }

    /**
     * Get config
     *
     * @return string
     */
    public function getBrkConfig(){
        $arr = json_decode($this->brk_config, true);
        if (!is_array($arr)) {
            $arr            = array(
                'db1.dsn'         => '',
                'db1.username'    => '',
                'db1.password'    => '',
                'db1.dbname'      => '',
                'db2.dsn'         => '',
                'db2.username'    => '',
                'db2.password'    => '',
                'db2.dbname'      => '',
                'email.fromemail' => '',
                'email.fromname'  => ''
            );
            $this->brk_config = json_encode($arr);
        }
        return $this->brk_config;
    }

    /**
     * Retourne la liste des colonnes
     *
     * @return array
     */
    public static function columnMap()
    {
        return array(
            'brk_id'          => 'brk_id',
            'dom_id'          => 'dom_id',
            'brk_key'         => 'brk_key',
            'brk_name'        => 'brk_name',
            'brk_certificate' => 'brk_certificate',
            'brk_active'      => 'brk_active',
            'brk_ts'          => 'brk_ts',
            'brk_api_scope'   => 'brk_api_scope',
            'brk_users_scope' => 'brk_users_scope',
            'brk_ips'         => 'brk_ips',
            'brk_config'      => 'brk_config'
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
            'brk_id'
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
            'brk_key', 'brk_name', 'brk_certificate', 'brk_config'
        );
    }
}
