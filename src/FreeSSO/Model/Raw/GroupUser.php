<?php
/**
 * ... ...
 * @author jeromeklam
 */
namespace FreeSSO\Model\Raw;

/**
 * Classe : GroupUser
 * @author : jeromeklam
 */
class GroupUser extends \FreeFW\Model\AbstractPDOStorage
{

    /**
     * gru_id
     * @var Bigint
     */
    protected $gru_id = null;
    const DESC_GRU_ID = array(
        'name'   => 'gru_id',
        'column' => 'gru_id',
        'field'  => 'gru_id',
        'type'   => \FreeFW\Constants::TYPE_BIGINT,
        'camel'  => 'GruId',
        'snake'  => 'gru_id',
        'getter' => 'getGruId',
        'setter' => 'setGruId',
        'search' => false,
        'key'    => true,
        'uniq'   => true
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
        'type'   => \FreeFW\Constants::TYPE_RESULTSET,
        'list'   => 'FreeFW.Sso::User.find',
        'camel'  => 'UserId',
        'snake'  => 'user_id',
        'getter' => 'getUserId',
        'setter' => 'setUserId',
        'search' => false
    );
    /**
     * grp_id
     * @var Bigint
     */
    protected $grp_id = null;
    const DESC_GRP_ID = array(
        'name'   => 'grp_id',
        'column' => 'grp_id',
        'field'  => 'grp_id',
        'type'   => \FreeFW\Constants::TYPE_RESULTSET,
        'list'   => 'FreeFW.Sso::Group.find',
        'camel'  => 'GrpId',
        'snake'  => 'grp_id',
        'getter' => 'getGrpId',
        'setter' => 'setGrpId',
        'search' => false
    );
    /**
     * gru_key
     * @var String
     */
    protected $gru_key = null;
    const DESC_GRU_KEY = array(
        'name'   => 'gru_key',
        'column' => 'gru_key',
        'field'  => 'gru_key',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'GruKey',
        'snake'  => 'gru_key',
        'getter' => 'getGruKey',
        'setter' => 'setGruKey',
        'search' => true
    );
    /**
     * gru_infos
     * @var String
     */
    protected $gru_infos = null;
    const DESC_GRU_INFOS = array(
        'name'   => 'gru_infos',
        'column' => 'gru_infos',
        'field'  => 'gru_infos',
        'type'   => \FreeFW\Constants::TYPE_JSON,
        'camel'  => 'GruInfos',
        'snake'  => 'gru_infos',
        'getter' => 'getGruInfos',
        'setter' => 'setGruInfos',
        'search' => false
    );
    /**
     * gru_active
     * @var String
     */
    protected $gru_active = null;
    const DESC_GRU_ACTIVE = array(
        'name'   => 'gru_active',
        'column' => 'gru_active',
        'field'  => 'gru_active',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'GruActive',
        'snake'  => 'gru_active',
        'getter' => 'getGruActive',
        'setter' => 'setGruActive',
        'search' => false
    );
    /**
     * gru_default
     * @var String
     */
    protected $gru_default = null;
    const DESC_GRU_DEFAULT = array(
        'name'   => 'gru_default',
        'column' => 'gru_default',
        'field'  => 'gru_default',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'GruDefault',
        'snake'  => 'gru_default',
        'getter' => 'getGruDefault',
        'setter' => 'setGruDefault',
        'search' => false
    );
    /**
     * Source
     * @var String
     */
    protected static $source = 'FreeFW_groups_users';

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
            'gru_id'      => self::DESC_GRU_ID,
            'user_id'     => self::DESC_USER_ID,
            'grp_id'      => self::DESC_GRP_ID,
            'gru_key'     => self::DESC_GRU_KEY,
            'gru_infos'   => self::DESC_GRU_INFOS,
            'gru_active'  => self::DESC_GRU_ACTIVE,
            'gru_default' => self::DESC_GRU_DEFAULT
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
            'gru_id'      => self::DESC_GRU_ID,
            'user_id'     => self::DESC_USER_ID,
            'grp_id'      => self::DESC_GRP_ID,
            'gru_key'     => self::DESC_GRU_KEY,
            'gru_infos'   => self::DESC_GRU_INFOS,
            'gru_active'  => self::DESC_GRU_ACTIVE,
            'gru_default' => self::DESC_GRU_DEFAULT
        );
    }

    /**
     * Setter gru_id
     *
     * @param bigint $p_value
     *
     * @return \FreeSSO\Model\GroupUser
     */
    public function setGruId($p_value)
    {
        $this->gru_id = $p_value;
        return $this;
    }

    /**
     * Getter gru_id
     *
     * @return bigint
     */
    public function getGruId()
    {
        return $this->gru_id;
    }

    /**
     * Setter user_id
     *
     * @param bigint $p_value
     *
     * @return \FreeSSO\Model\GroupUser
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
     * Setter grp_id
     *
     * @param bigint $p_value
     *
     * @return \FreeSSO\Model\GroupUser
     */
    public function setGrpId($p_value)
    {
        $this->grp_id = $p_value;
        return $this;
    }

    /**
     * Getter grp_id
     *
     * @return bigint
     */
    public function getGrpId()
    {
        return $this->grp_id;
    }

    /**
     * Setter gru_key
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\GroupUser
     */
    public function setGruKey($p_value)
    {
        $this->gru_key = $p_value;
        return $this;
    }

    /**
     * Getter gru_key
     *
     * @return string
     */
    public function getGruKey()
    {
        return $this->gru_key;
    }

    /**
     * Setter gru_infos
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\GroupUser
     */
    public function setGruInfos($p_value)
    {
        $this->gru_infos = $p_value;
        return $this;
    }

    /**
     * Getter gru_infos
     *
     * @return string
     */
    public function getGruInfos()
    {
        return $this->gru_infos;
    }

    /**
     * Setter gru_active
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\GroupUser
     */
    public function setGruActive($p_value)
    {
        $this->gru_active = $p_value;
        return $this;
    }

    /**
     * Getter gru_active
     *
     * @return string
     */
    public function getGruActive()
    {
        return $this->gru_active;
    }

    /**
     * Setter gru_default
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\GroupUser
     */
    public function setGruDefault($p_value)
    {
        $this->gru_default = $p_value;
        return $this;
    }

    /**
     * Getter gru_default
     *
     * @return string
     */
    public function getGruDefault()
    {
        return $this->gru_default;
    }

    /**
     * Retourne la liste des colonnes
     *
     * @return array
     */
    public static function columnMap()
    {
        return array(
            'gru_id'      => 'gru_id',
            'user_id'     => 'user_id',
            'grp_id'      => 'grp_id',
            'gru_key'     => 'gru_key',
            'gru_infos'   => 'gru_infos',
            'gru_active'  => 'gru_active',
            'gru_default' => 'gru_default'
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
            'gru_id'
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
            'gru_key'
        );
    }
}
