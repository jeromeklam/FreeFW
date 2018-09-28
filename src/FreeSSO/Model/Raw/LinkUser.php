<?php
/**
 * ... ...
 * @author jerome.klam
 */
namespace FreeSSO\Model\Raw;

/**
 * Classe : LinkUser
 * @author : jerome.klam
 */
class LinkUser extends \FreeFW\Model\AbstractPDOStorage
{

    /**
     * Auth methods
     * @var string
     */
    const AUTH_METHOD_AUTO = 'AUTO';

    /**
     * lku_id
     * @var number
     */
    protected $lku_id = null;
    const DESC_LKU_ID = array(
        'name'   => 'lku_id',
        'column' => 'lku_id',
        'field'  => 'lku_id',
        'type'   => \FreeFW\Constants::TYPE_BIGINT,
        'camel'  => 'LkuId',
        'snake'  => 'lku_id',
        'getter' => 'getLkuId',
        'setter' => 'setLkuId',
        'search' => false,
        'key'    => true,
        'uniq'   => true
    );
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
        'search' => false
    );
    /**
     * lku_partner_type
     * @var string
     */
    protected $lku_partner_type = null;
    const DESC_LKU_PARTNER_TYPE = array(
        'name'   => 'lku_partner_type',
        'column' => 'lku_partner_type',
        'field'  => 'lku_partner_type',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'LkuPartnerType',
        'snake'  => 'lku_partner_type',
        'getter' => 'getLkuPartnerType',
        'setter' => 'setLkuPartnerType',
        'search' => true
    );
    /**
     * lku_partner_id
     * @var number
     */
    protected $lku_partner_id = null;
    const DESC_LKU_PARTNER_ID = array(
        'name'   => 'lku_partner_id',
        'column' => 'lku_partner_id',
        'field'  => 'lku_partner_id',
        'type'   => \FreeFW\Constants::TYPE_BIGINT,
        'camel'  => 'LkuPartnerId',
        'snake'  => 'lku_partner_id',
        'getter' => 'getLkuPartnerId',
        'setter' => 'setLkuPartnerId',
        'search' => false
    );
    /**
     * lku_auth_method
     * @var string
     */
    protected $lku_auth_method = null;
    const DESC_LKU_AUTH_METHOD = array(
        'name'   => 'lku_auth_method',
        'column' => 'lku_auth_method',
        'field'  => 'lku_auth_method',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'LkuAuthMethod',
        'snake'  => 'lku_auth_method',
        'getter' => 'getLkuAuthMethod',
        'setter' => 'setLkuAuthMethod',
        'search' => true
    );
    /**
     * lku_auth_datas
     * @var string
     */
    protected $lku_auth_datas = null;
    const DESC_LKU_AUTH_DATAS = array(
        'name'   => 'lku_auth_datas',
        'column' => 'lku_auth_datas',
        'field'  => 'lku_auth_datas',
        'type'   => \FreeFW\Constants::TYPE_TEXT,
        'camel'  => 'LkuAuthDatas',
        'snake'  => 'lku_auth_datas',
        'getter' => 'getLkuAuthDatas',
        'setter' => 'setLkuAuthDatas',
        'search' => false
    );
    /**
     * lku_end
     * @var string
     */
    protected $lku_end = null;
    const DESC_LKU_END = array(
        'name'   => 'lku_end',
        'column' => 'lku_end',
        'field'  => 'lku_end',
        'type'   => \FreeFW\Constants::TYPE_DATETIME,
        'camel'  => 'LkuEnd',
        'snake'  => 'lku_end',
        'getter' => 'getLkuEnd',
        'setter' => 'setLkuEnd',
        'search' => false
    );
    /**
     * brk_id
     * @var number
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
        'search' => false
    );
    /**
     * lku_ts
     * @var string
     */
    protected $lku_ts = null;
    const DESC_LKU_TS = array(
        'name'   => 'lku_ts',
        'column' => 'lku_ts',
        'field'  => 'lku_ts',
        'type'   => \FreeFW\Constants::TYPE_DATETIME,
        'camel'  => 'LkuTs',
        'snake'  => 'lku_ts',
        'getter' => 'getLkuTs',
        'setter' => 'setLkuTs',
        'search' => false
    );
    /**
     * Source
     * @var String
     */
    protected static $source = 'FreeFW_links_users';

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
            'lku_id'           => self::DESC_LKU_ID,
            'user_id'          => self::DESC_USER_ID,
            'lku_partner_type' => self::DESC_LKU_PARTNER_TYPE,
            'lku_partner_id'   => self::DESC_LKU_PARTNER_ID,
            'lku_auth_method'  => self::DESC_LKU_AUTH_METHOD,
            'lku_auth_datas'   => self::DESC_LKU_AUTH_DATAS,
            'lku_end'          => self::DESC_LKU_END,
            'brk_id'           => self::DESC_BRK_ID,
            'lku_ts'           => self::DESC_LKU_TS
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
            'lku_id'           => self::DESC_LKU_ID,
            'user_id'          => self::DESC_USER_ID,
            'lku_partner_type' => self::DESC_LKU_PARTNER_TYPE,
            'lku_partner_id'   => self::DESC_LKU_PARTNER_ID,
            'lku_auth_method'  => self::DESC_LKU_AUTH_METHOD,
            'lku_auth_datas'   => self::DESC_LKU_AUTH_DATAS,
            'lku_end'          => self::DESC_LKU_END,
            'brk_id'           => self::DESC_BRK_ID,
            'lku_ts'           => self::DESC_LKU_TS
        );
    }

    /**
     * Setter lku_id
     *
     * @param number $p_value
     *
     * @return \FreeSSO\Model\Raw\LinkUser
     */
    public function setLkuId($p_value)
    {
        $this->lku_id = $p_value;
        return $this;
    }

    /**
     * Getter lku_id
     *
     * @return number
     */
    public function getLkuId()
    {
        return $this->lku_id;
    }

    /**
     * Setter user_id
     *
     * @param number $p_value
     *
     * @return \FreeSSO\Model\Raw\LinkUser
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
     * Setter lku_partner_type
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Raw\LinkUser
     */
    public function setLkuPartnerType($p_value)
    {
        $this->lku_partner_type = $p_value;
        return $this;
    }

    /**
     * Getter lku_partner_type
     *
     * @return string
     */
    public function getLkuPartnerType()
    {
        return $this->lku_partner_type;
    }

    /**
     * Setter lku_partner_id
     *
     * @param number $p_value
     *
     * @return \FreeSSO\Model\Raw\LinkUser
     */
    public function setLkuPartnerId($p_value)
    {
        $this->lku_partner_id = $p_value;
        return $this;
    }

    /**
     * Getter lku_partner_id
     *
     * @return number
     */
    public function getLkuPartnerId()
    {
        return $this->lku_partner_id;
    }

    /**
     * Setter lku_auth_method
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Raw\LinkUser
     */
    public function setLkuAuthMethod($p_value)
    {
        $this->lku_auth_method = $p_value;
        return $this;
    }

    /**
     * Getter lku_auth_method
     *
     * @return string
     */
    public function getLkuAuthMethod()
    {
        return $this->lku_auth_method;
    }

    /**
     * Setter lku_auth_datas
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Raw\LinkUser
     */
    public function setLkuAuthDatas($p_value)
    {
        $this->lku_auth_datas = $p_value;
        return $this;
    }

    /**
     * Getter lku_auth_datas
     *
     * @return string
     */
    public function getLkuAuthDatas()
    {
        return $this->lku_auth_datas;
    }

    /**
     * Setter lku_end
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Raw\LinkUser
     */
    public function setLkuEnd($p_value)
    {
        $this->lku_end = $p_value;
        return $this;
    }

    /**
     * Getter lku_end
     *
     * @return string
     */
    public function getLkuEnd()
    {
        return $this->lku_end;
    }

    /**
     * Setter brk_id
     *
     * @param number $p_value
     *
     * @return \FreeSSO\Model\Raw\LinkUser
     */
    public function setBrkId($p_value)
    {
        $this->brk_id = $p_value;
        return $this;
    }

    /**
     * Getter brk_id
     *
     * @return number
     */
    public function getBrkId()
    {
        return $this->brk_id;
    }

    /**
     * Setter lku_ts
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Raw\LinkUser
     */
    public function setLkuTs($p_value)
    {
        $this->lku_ts = $p_value;
        return $this;
    }

    /**
     * Getter lku_ts
     *
     * @return string
     */
    public function getLkuTs()
    {
        return $this->lku_ts;
    }

    /**
     * Retourne la liste des colonnes
     *
     * @return array
     */
    public static function columnMap()
    {
        return array(
            'lku_id'           => 'lku_id',
            'user_id'          => 'user_id',
            'lku_partner_type' => 'lku_partner_type',
            'lku_partner_id'   => 'lku_partner_id',
            'lku_auth_method'  => 'lku_auth_method',
            'lku_auth_datas'   => 'lku_auth_datas',
            'lku_end'          => 'lku_end',
            'brk_id'           => 'brk_id',
            'lku_ts'           => 'lku_ts'
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
            'lku_id'
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
            'lku_partner_type', 'lku_auth_method'
        );
    }
}
