<?php
namespace FreeFW\Model\StorageModel;

use \FreeFW\Constants as FFCST;

/**
 * Edition
 *
 * @author jeromeklam
 */
abstract class Edition extends \FreeFW\Core\StorageModel
{

    /**
     * Field properties as static arrays
     * @var array
     */
    protected static $PRP_EDI_ID = [
        FFCST::PROPERTY_PRIVATE => 'edi_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_PK],
        FFCST::PROPERTY_COMMENT => 'Identifiant de l\'édition',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_BRK_ID = [
        FFCST::PROPERTY_PRIVATE => 'brk_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_BROKER],
        FFCST::PROPERTY_COMMENT => 'Restriction broker',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_GRP_ID = [
        FFCST::PROPERTY_PRIVATE => 'grp_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_FK],
        FFCST::PROPERTY_COMMENT => 'Le groupe',
        FFCST::PROPERTY_SAMPLE  => 123,
        FFCST::PROPERTY_FK      => ['group' =>
            [
                FFCST::FOREIGN_MODEL => 'FreeFW::Model::Group',
                FFCST::FOREIGN_FIELD => 'grp_id',
                FFCST::FOREIGN_TYPE  => \FreeFW\Model\Query::JOIN_LEFT,
            ]
        ],
    ];
    protected static $PRP_LANG_ID = [
        FFCST::PROPERTY_PRIVATE => 'lang_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_FK],
        FFCST::PROPERTY_COMMENT => 'La langue',
        FFCST::PROPERTY_SAMPLE  => 123,
        FFCST::PROPERTY_FK      => ['lang' =>
            [
                FFCST::FOREIGN_MODEL => 'FreeFW::Model::Lang',
                FFCST::FOREIGN_FIELD => 'lang_id',
                FFCST::FOREIGN_TYPE  => \FreeFW\Model\Query::JOIN_LEFT,
            ]
        ],
    ];
    protected static $PRP_EDI_OBJECT_NAME = [
        FFCST::PROPERTY_PRIVATE => 'edi_object_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_COMMENT => 'Nom de l\'objet',
        FFCST::PROPERTY_MAX     => 32,
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_EDI_OBJECT_ID = [
        FFCST::PROPERTY_PRIVATE => 'edi_object_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Identifiant de l\'objet',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_EDI_TS = [
        FFCST::PROPERTY_PRIVATE => 'edi_ts',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_COMMENT => 'Date de dernière mise à jour',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_EDI_NAME = [
        FFCST::PROPERTY_PRIVATE => 'edi_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_COMMENT => 'Nom de l\'édition',
        FFCST::PROPERTY_MAX     => 255,
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_EDI_DESC = [
        FFCST::PROPERTY_PRIVATE => 'edi_desc',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BLOB,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Description de l\'édition',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_EDI_DATA = [
        FFCST::PROPERTY_PRIVATE => 'edi_data',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BLOB,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_JSONIGNORE],
        FFCST::PROPERTY_COMMENT => 'Le contenu de l\'édition',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_EDI_TYPE = [
        FFCST::PROPERTY_PRIVATE => 'edi_type',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_SELECT,
        FFCST::PROPERTY_ENUM    => ['IMPRESS','CALC','WRITER','HTML'],
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_DEFAULT => 'HTML',
        FFCST::PROPERTY_COMMENT => 'Le type de contenu de l\'édition',
        FFCST::PROPERTY_MAX     => 7,
        FFCST::PROPERTY_SAMPLE  => 'HTML',
    ];
    protected static $PRP_EDI_MODE = [
        FFCST::PROPERTY_PRIVATE => 'edi_mode',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_SELECT,
        FFCST::PROPERTY_ENUM    => ['RENEW','OTHER','KEEP'],
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_DEFAULT => 'RENEW',
        FFCST::PROPERTY_COMMENT => 'Le mode d\'édition',
        FFCST::PROPERTY_MAX     => 5,
        FFCST::PROPERTY_SAMPLE  => 'KEEP',
    ];
    protected static $PRP_EDI_DURATION = [
        FFCST::PROPERTY_PRIVATE => 'edi_duration',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_INTEGER,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_COMMENT => 'La durée de conservation en jours, 0 pour illimité',
        FFCST::PROPERTY_DEFAULT => 0,
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
            'edi_id'          => self::$PRP_EDI_ID,
            'brk_id'          => self::$PRP_BRK_ID,
            'grp_id'          => self::$PRP_GRP_ID,
            'lang_id'         => self::$PRP_LANG_ID,
            'edi_object_name' => self::$PRP_EDI_OBJECT_NAME,
            'edi_object_id'   => self::$PRP_EDI_OBJECT_ID,
            'edi_ts'          => self::$PRP_EDI_TS,
            'edi_name'        => self::$PRP_EDI_NAME,
            'edi_desc'        => self::$PRP_EDI_DESC,
            'edi_data'        => self::$PRP_EDI_DATA,
            'edi_type'        => self::$PRP_EDI_TYPE,
            'edi_mode'        => self::$PRP_EDI_MODE,
            'edi_duration'    => self::$PRP_EDI_DURATION
        ];
    }

    /**
     * Set object source
     *
     * @return string
     */
    public static function getSource()
    {
        return 'sys_edition';
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
