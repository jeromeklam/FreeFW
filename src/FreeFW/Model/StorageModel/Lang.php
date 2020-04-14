<?php
namespace FreeFW\Model\StorageModel;

use \FreeFW\Constants as FFCST;

/**
 * Lang
 *
 * @author jeromeklam
 */
abstract class Lang extends \FreeFW\Core\StorageModel
{

/**
 * Field properties as static arrays
 * @var array
 */
    protected static $PRP_LANG_ID = [
        FFCST::PROPERTY_PRIVATE => 'lang_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_PK]
    ];
    protected static $PRP_LANG_NAME = [
        FFCST::PROPERTY_PRIVATE => 'lang_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED]
    ];
    protected static $PRP_LANG_CODE = [
        FFCST::PROPERTY_PRIVATE => 'lang_code',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_LANG_ISO = [
        FFCST::PROPERTY_PRIVATE => 'lang_iso',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_LANG_FLAG = [
        FFCST::PROPERTY_PRIVATE => 'lang_flag',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => []
    ];

    /**
     * get properties
     *
     * @return array[]
     */
    public static function getProperties()
    {
        return [
            'lang_id'   => self::$PRP_LANG_ID,
            'lang_name' => self::$PRP_LANG_NAME,
            'lang_code' => self::$PRP_LANG_CODE,
            'lang_iso'  => self::$PRP_LANG_ISO,
            'lang_flag' => self::$PRP_LANG_FLAG
        ];
    }

    /**
     * Set object source
     *
     * @return string
     */
    public static function getSource()
    {
        return 'core_lang';
    }
}
