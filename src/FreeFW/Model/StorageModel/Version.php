<?php
namespace FreeFW\Model\StorageModel;

use \FreeFW\Constants as FFCST;

/**
 * Version
 *
 * @author jeromeklam
 */
abstract class Version extends \FreeFW\Core\StorageModel
{

    /**
     * Field properties as static arrays
     * @var array
     */
    protected static $PRP_VERS_ID = [
        FFCST::PROPERTY_PRIVATE => 'vers_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_PK],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_VERS_VERSION = [
        FFCST::PROPERTY_PRIVATE => 'vers_version',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_MAX     => 80,
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_VERS_INSTALL_DATE = [
        FFCST::PROPERTY_PRIVATE => 'vers_install_date',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_VERS_INSTALL_TEXT = [
        FFCST::PROPERTY_PRIVATE => 'vers_install_text',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BLOB,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_VERS_INSTALL_STATUS = [
        FFCST::PROPERTY_PRIVATE => 'vers_install_status',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_MAX     => 7,
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_VERS_INSTALL_CONTENT = [
        FFCST::PROPERTY_PRIVATE => 'vers_install_content',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BLOB,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_VERS_INSTALL_FILE = [
        FFCST::PROPERTY_PRIVATE => 'vers_install_file',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_MAX     => 255,
        FFCST::PROPERTY_SAMPLE  => '',
    ];

    /**
     * get properties
     *
     * @return array[]
     */
    public static function getProperties()
    {
        return [
            'vers_id'              => self::$PRP_VERS_ID,
            'vers_version'         => self::$PRP_VERS_VERSION,
            'vers_install_date'    => self::$PRP_VERS_INSTALL_DATE,
            'vers_install_text'    => self::$PRP_VERS_INSTALL_TEXT,
            'vers_install_status'  => self::$PRP_VERS_INSTALL_STATUS,
            'vers_install_content' => self::$PRP_VERS_INSTALL_CONTENT,
            'vers_install_file'    => self::$PRP_VERS_INSTALL_FILE
        ];
    }

    /**
     * Set object source
     *
     * @return string
     */
    public static function getSource()
    {
        return 'sys_version';
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
