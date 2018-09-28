<?php
/**
 * ... ...
 * @author jeromeklam
 */
namespace FreeSSO\Model\Raw;

/**
 * Classe : Group
 * @author : jeromeklam
 */
class Group extends \FreeFW\Model\AbstractPDOStorage
{

    /**
     * grp_id
     * @var number
     */
    protected $grp_id = null;
    const DESC_GRP_ID = array(
        'name'   => 'grp_id',
        'column' => 'grp_id',
        'field'  => 'grp_id',
        'type'   => \FreeFW\Constants::TYPE_BIGINT,
        'camel'  => 'GrpId',
        'snake'  => 'grp_id',
        'getter' => 'getGrpId',
        'setter' => 'setGrpId',
        'search' => false,
        'key'    => true,
        'uniq'   => true
    );
    /**
     * grp_name
     * @var String
     */
    protected $grp_name = null;
    const DESC_GRP_NAME = array(
        'name'     => 'grp_name',
        'column'   => 'grp_name',
        'field'    => 'grp_name',
        'type'     => \FreeFW\Constants::TYPE_STRING,
        'camel'    => 'GrpName',
        'snake'    => 'grp_name',
        'getter'   => 'getGrpName',
        'setter'   => 'setGrpName',
        'search'   => true,
        'required' => true
    );
    /**
     * grp_active
     * @var String
     */
    protected $grp_active = true;
    const DESC_GRP_ACTIVE = array(
        'name'   => 'grp_active',
        'column' => 'grp_active',
        'field'  => 'grp_active',
        'type'   => \FreeFW\Constants::TYPE_BOOLEAN,
        'camel'  => 'GrpActive',
        'snake'  => 'grp_active',
        'getter' => 'getGrpActive',
        'setter' => 'setGrpActive',
        'search' => false
    );
    /**
     * grp_ips
     * @var String
     */
    protected $grp_ips = null;
    const DESC_GRP_IPS = array(
        'name'   => 'grp_ips',
        'column' => 'grp_ips',
        'field'  => 'grp_ips',
        'type'   => \FreeFW\Constants::TYPE_JSON,
        'camel'  => 'GrpIps',
        'snake'  => 'grp_ips',
        'getter' => 'getGrpIps',
        'setter' => 'setGrpIps',
        'search' => false
    );
    /**
     * grp_key
     * @var String
     */
    protected $grp_key = null;
    const DESC_GRP_KEY = array(
        'name'     => 'grp_key',
        'column'   => 'grp_key',
        'field'    => 'grp_key',
        'type'     => \FreeFW\Constants::TYPE_MD5,
        'camel'    => 'GrpKey',
        'snake'    => 'grp_key',
        'getter'   => 'getGrpKey',
        'setter'   => 'setGrpKey',
        'search'   => true,
        'required' => true,
        'uniq'     => true
    );
    /**
     * grp_verif_key
     * @var String
     */
    protected $grp_verif_key = null;
    const DESC_GRP_VERIF_KEY = array(
        'name'     => 'grp_verif_key',
        'column'   => 'grp_verif_key',
        'field'    => 'grp_verif_key',
        'type'     => \FreeFW\Constants::TYPE_STRING,
        'camel'    => 'GrpVerifKey',
        'snake'    => 'grp_verif_key',
        'getter'   => 'getGrpVerifKey',
        'setter'   => 'setGrpVerifKey',
        'search'   => true,
        'required' => true
    );
    /**
     * grp_cnx
     * @var String
     */
    protected $grp_cnx = null;
    const DESC_GRP_CNX = array(
        'name'   => 'grp_cnx',
        'column' => 'grp_cnx',
        'field'  => 'grp_cnx',
        'type'   => \FreeFW\Constants::TYPE_JSON,
        'camel'  => 'GrpCnx',
        'snake'  => 'grp_cnx',
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
                'webservice.password' => array('type' => 'string'),
                'eclient.servername'  => array('type' => 'string'),
                'eclient.database'    => array('type' => 'string'),
                'eclient.user'        => array('type' => 'string'),
                'eclient.password'    => array('type' => 'string'),
                'eclient.url'         => array('type' => 'string'),
                'customer'            => array('type' => 'string'),
                'sms_provider'        => array('type' => 'string'),
                'sms_component'       => array('type' => 'string'),
                'sms_sender'          => array('type' => 'string'),
                'sms_origin'          => array('type' => 'string')
            )
        ),
        'getter' => 'getGrpCnx',
        'setter' => 'setGrpCnx',
        'search' => false
    );
    /**
     * grp_verif_prefix
     * @var String
     */
    protected $grp_verif_prefix = null;
    const DESC_GRP_VERIF_PREFIX = array(
        'name'     => 'grp_verif_prefix',
        'column'   => 'grp_verif_prefix',
        'field'    => 'grp_verif_prefix',
        'type'     => \FreeFW\Constants::TYPE_MD5,
        'camel'    => 'GrpVerifPrefix',
        'snake'    => 'grp_verif_prefix',
        'getter'   => 'getGrpVerifPrefix',
        'setter'   => 'setGrpVerifPrefix',
        'search'   => true,
        'required' => true
    );
    /**
     * grp_extern_code
     * @var String
     */
    protected $grp_extern_code = null;
    const DESC_GRP_EXTERN_CODE = array(
        'name'     => 'grp_extern_code',
        'column'   => 'grp_extern_code',
        'field'    => 'grp_extern_code',
        'type'     => \FreeFW\Constants::TYPE_STRING,
        'camel'    => 'GrpExternCode',
        'snake'    => 'grp_extern_code',
        'getter'   => 'getGrpExternCode',
        'setter'   => 'setGrpExternCode',
        'search'   => true
    );
    /**
     * Source
     * @var String
     */
    protected static $source = 'FreeFW_groups';

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
            'grp_id'           => self::DESC_GRP_ID,
            'grp_name'         => self::DESC_GRP_NAME,
            'grp_active'       => self::DESC_GRP_ACTIVE,
            'grp_ips'          => self::DESC_GRP_IPS,
            'grp_key'          => self::DESC_GRP_KEY,
            'grp_verif_key'    => self::DESC_GRP_VERIF_KEY,
            'grp_cnx'          => self::DESC_GRP_CNX,
            'grp_verif_prefix' => self::DESC_GRP_VERIF_PREFIX,
            'grp_extern_code'  => self::DESC_GRP_EXTERN_CODE
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
            'grp_id'           => self::DESC_GRP_ID,
            'grp_name'         => self::DESC_GRP_NAME,
            'grp_active'       => self::DESC_GRP_ACTIVE,
            'grp_ips'          => self::DESC_GRP_IPS,
            'grp_key'          => self::DESC_GRP_KEY,
            'grp_verif_key'    => self::DESC_GRP_VERIF_KEY,
            'grp_cnx'          => self::DESC_GRP_CNX,
            'grp_verif_prefix' => self::DESC_GRP_VERIF_PREFIX,
            'grp_extern_code'  => self::DESC_GRP_EXTERN_CODE
        );
    }

    /**
     * Setter grp_id
     *
     * @param number $p_value
     *
     * @return \FreeSSO\Model\Group
     */
    public function setGrpId($p_value)
    {
        $this->grp_id = $p_value;
        return $this;
    }

    /**
     * Getter grp_id
     *
     * @return number
     */
    public function getGrpId()
    {
        return $this->grp_id;
    }

    /**
     * Setter grp_name
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Group
     */
    public function setGrpName($p_value)
    {
        $this->grp_name = $p_value;
        return $this;
    }

    /**
     * Getter grp_name
     *
     * @return string
     */
    public function getGrpName()
    {
        return $this->grp_name;
    }

    /**
     * Setter grp_active
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Group
     */
    public function setGrpActive($p_value)
    {
        $this->grp_active = $p_value;
        return $this;
    }

    /**
     * Getter grp_active
     *
     * @return string
     */
    public function getGrpActive()
    {
        return $this->grp_active;
    }

    /**
     * Setter grp_ips
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Group
     */
    public function setGrpIps($p_value)
    {
        $this->grp_ips = $p_value;
        return $this;
    }

    /**
     * Getter grp_ips
     *
     * @return string
     */
    public function getGrpIps()
    {
        return $this->grp_ips;
    }

    /**
     * Setter grp_key
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Group
     */
    public function setGrpKey($p_value)
    {
        $this->grp_key = $p_value;
        return $this;
    }

    /**
     * Getter grp_key
     *
     * @return string
     */
    public function getGrpKey()
    {
        return $this->grp_key;
    }

    /**
     * Setter grp_verif_key
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Group
     */
    public function setGrpVerifKey($p_value)
    {
        $this->grp_verif_key = $p_value;
        return $this;
    }

    /**
     * Getter grp_verif_key
     *
     * @return string
     */
    public function getGrpVerifKey()
    {
        return $this->grp_verif_key;
    }

    /**
     * Setter grp_cnx
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Group
     */
    public function setGrpCnx($p_value)
    {
        $this->grp_cnx = $p_value;
        return $this;
    }

    /**
     * Setter grp_verif_prefix
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Group
     */
    public function setGrpVerifPrefix($p_value)
    {
        $this->grp_verif_prefix = $p_value;
        return $this;
    }

    /**
     * Getter grp_verif_prefix
     *
     * @return string
     */
    public function getGrpVerifPrefix()
    {
        return $this->grp_verif_prefix;
    }

    /**
     * Setter grp_extern_code
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Raw\Group
     */
    public function setGrpExternCode($p_value)
    {
        $this->grp_extern_code = $p_value;
        return $this;
    }

    /**
     * Getter grp_extern_code
     *
     * @return string
     */
    public function getGrpExternCode()
    {
        return $this->grp_extern_code;
    }

    /**
     * Getter grp_cnx
     *
     * @return string
     */
    public function getGrpCnx()
    {
        $arr = json_decode($this->grp_cnx, true);
        if (!is_array($arr)) {
            $arr = [];
            foreach (self::DESC_GRP_CNX['json']['properties'] as $idx => $type) {
                $arr[$idx] = '';
            }
            $this->grp_cnx = json_encode($arr);
        } else {
            $new = [];
            foreach (self::DESC_GRP_CNX['json']['properties'] as $idx => $type) {
                $new[$idx] = '';
                if (array_key_exists($idx, $arr)) {
                    $new[$idx] = $arr[$idx];
                }
            }
            $this->grp_cnx = json_encode($new);
        }
        return $this->grp_cnx;
    }

    /**
     * Retourne la liste des colonnes
     *
     * @return array
     */
    public static function columnMap()
    {
        return array(
            'grp_id'           => 'grp_id',
            'grp_name'         => 'grp_name',
            'grp_active'       => 'grp_active',
            'grp_ips'          => 'grp_ips',
            'grp_key'          => 'grp_key',
            'grp_verif_key'    => 'grp_verif_key',
            'grp_cnx'          => 'grp_cnx',
            'grp_verif_prefix' => 'grp_verif_prefix',
            'grp_extern_code'  => 'grp_extern_code'
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
            'grp_id'
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
            'grp_name', 'grp_key', 'grp_verif_key', 'grp_extern_code'
        );
    }
}
