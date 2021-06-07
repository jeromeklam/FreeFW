<?php
namespace FreeFW\Model\StorageModel;

use \FreeFW\Constants as FFCST;

/**
 * Automate
 *
 * @author jeromeklam
 */
abstract class Automate extends \FreeFW\Core\StorageModel
{

    /**
     * Field properties as static arrays
     * @var array
     */
    protected static $PRP_AUTO_ID = [
        FFCST::PROPERTY_PRIVATE => 'auto_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_PK],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_AUTO_NAME = [
        FFCST::PROPERTY_PRIVATE => 'auto_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_MAX     => 255,
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_AUTO_DESC = [
        FFCST::PROPERTY_PRIVATE => 'auto_desc',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_HTML,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_AUTO_OBJECT_NAME = [
        FFCST::PROPERTY_PRIVATE => 'auto_object_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_MAX     => 80,
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_GRP_ID = [
        FFCST::PROPERTY_PRIVATE => 'grp_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_FK],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => 123,
        FFCST::PROPERTY_FK      => ['group' =>
            [
                FFCST::FOREIGN_MODEL => 'FreeFW::Model::Group',
                FFCST::FOREIGN_FIELD => 'grp_id',
                FFCST::FOREIGN_TYPE  => \FreeFW\Model\Query::JOIN_LEFT,
            ]
        ],
    ];
    protected static $PRP_AUTO_SERVICE = [
        FFCST::PROPERTY_PRIVATE => 'auto_service',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_MAX     => 80,
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_AUTO_METHOD = [
        FFCST::PROPERTY_PRIVATE => 'auto_method',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_MAX     => 80,
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_AUTO_PARAMS = [
        FFCST::PROPERTY_PRIVATE => 'auto_params',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_JSON,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_AUTO_EVENTS = [
        FFCST::PROPERTY_PRIVATE => 'auto_events',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => 'storage_update',
        FFCST::PROPERTY_MAX     => 512,
    ];

    /**
     * get properties
     *
     * @return array[]
     */
    public static function getProperties()
    {
        return [
            'auto_id'          => self::$PRP_AUTO_ID,
            'auto_name'        => self::$PRP_AUTO_NAME,
            'auto_desc'        => self::$PRP_AUTO_DESC,
            'auto_object_name' => self::$PRP_AUTO_OBJECT_NAME,
            'grp_id'           => self::$PRP_GRP_ID,
            'auto_service'     => self::$PRP_AUTO_SERVICE,
            'auto_method'      => self::$PRP_AUTO_METHOD,
            'auto_params'      => self::$PRP_AUTO_PARAMS,
            'auto_events'      => self::$PRP_AUTO_EVENTS,
        ];
    }

    /**
     * Set object source
     *
     * @return string
     */
    public static function getSource()
    {
        return 'sys_automate';
    }

    /**
     * Get object short description
     *
     * @return string
     */
    public static function getSourceComments()
    {
        return '';
    }

    /**
     * Get autocomplete field
     *
     * @return string
     */
    public static function getAutocompleteField()
    {
        return '';
    }
}
