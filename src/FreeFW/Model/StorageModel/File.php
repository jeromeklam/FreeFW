<?php
namespace FreeFW\Model\StorageModel;

use \FreeFW\Constants as FFCST;

/**
 * File
 *
 * @author jeromeklam
 */
abstract class File extends \FreeFW\Core\StorageModel
{

    /**
     * Field properties as static arrays
     * @var array
     */
    protected static $PRP_FILE_ID = [
        FFCST::PROPERTY_PRIVATE => 'file_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_PK],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_BRK_ID = [
        FFCST::PROPERTY_PRIVATE => 'brk_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_BROKER],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_GRP_ID = [
        FFCST::PROPERTY_PRIVATE => 'grp_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_FK],
        FFCST::PROPERTY_DEFAULT => FFCST::DEFAULT_CURRENT_GROUP,
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
    protected static $PRP_USER_ID = [
        FFCST::PROPERTY_PRIVATE => 'user_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_FK],
        FFCST::PROPERTY_DEFAULT => FFCST::DEFAULT_CURRENT_USER,
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => 123,
        FFCST::PROPERTY_FK      => ['user' =>
            [
                FFCST::FOREIGN_MODEL => 'FreeFW::Model::User',
                FFCST::FOREIGN_FIELD => 'user_id',
                FFCST::FOREIGN_TYPE  => \FreeFW\Model\Query::JOIN_LEFT,
            ]
        ],
    ];
    protected static $PRP_LANG_ID = [
        FFCST::PROPERTY_PRIVATE => 'lang_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_FK],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => 123,
        FFCST::PROPERTY_FK      => ['lang_id' =>
            [
                FFCST::FOREIGN_MODEL => 'FreeFW::Model::Lang',
                FFCST::FOREIGN_FIELD => 'lang_id',
                FFCST::FOREIGN_TYPE  => \FreeFW\Model\Query::JOIN_LEFT,
            ]
        ],
    ];
    protected static $PRP_FILE_TS = [
        FFCST::PROPERTY_PRIVATE => 'file_ts',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_DEFAULT => FFCST::DEFAULT_NOW,
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_PARENT_FILE_ID = [
        FFCST::PROPERTY_PRIVATE => 'parent_file_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_FK],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => 123,
        FFCST::PROPERTY_FK      => ['parent_file' =>
            [
                FFCST::FOREIGN_MODEL => 'FreeFW::Model::File',
                FFCST::FOREIGN_FIELD => 'parent_file_id',
                FFCST::FOREIGN_TYPE  => \FreeFW\Model\Query::JOIN_LEFT,
            ]
        ],
    ];
    protected static $PRP_FILE_NAME = [
        FFCST::PROPERTY_PRIVATE => 'file_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_MAX     => 80,
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_FILE_BLOB = [
        FFCST::PROPERTY_PRIVATE => 'file_blob',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BLOB,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_FILE_TYPE = [
        FFCST::PROPERTY_PRIVATE => 'file_type',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_MAX     => 32,
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_EDI_ID = [
        FFCST::PROPERTY_PRIVATE => 'edi_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_FK],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => 123,
        FFCST::PROPERTY_FK      => ['edition' =>
            [
                FFCST::FOREIGN_MODEL => 'FreeFW::Model::Edition',
                FFCST::FOREIGN_FIELD => 'edi_id',
                FFCST::FOREIGN_TYPE  => \FreeFW\Model\Query::JOIN_LEFT,
            ]
        ],
    ];
    protected static $PRP_FILE_OBJECT_NAME = [
        FFCST::PROPERTY_PRIVATE => 'file_object_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_MAX     => 80,
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_FILE_OBJECT_ID = [
        FFCST::PROPERTY_PRIVATE => 'file_object_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];

    /**
     * get properties
     *
     * @return array[]
     */
    public static function getProperties()
    {
        return [
            'file_id'          => self::$PRP_FILE_ID,
            'brk_id'           => self::$PRP_BRK_ID,
            'grp_id'           => self::$PRP_GRP_ID,
            'user_id'          => self::$PRP_USER_ID,
            'lang_id'          => self::$PRP_LANG_ID,
            'file_ts'          => self::$PRP_FILE_TS,
            'parent_file_id'   => self::$PRP_PARENT_FILE_ID,
            'file_name'        => self::$PRP_FILE_NAME,
            'file_blob'        => self::$PRP_FILE_BLOB,
            'file_type'        => self::$PRP_FILE_TYPE,
            'edi_id'           => self::$PRP_EDI_ID,
            'file_object_name' => self::$PRP_FILE_OBJECT_NAME,
            'file_object_id'   => self::$PRP_FILE_OBJECT_ID
        ];
    }

    /**
     * Set object source
     *
     * @return string
     */
    public static function getSource()
    {
        return 'sys_file';
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
