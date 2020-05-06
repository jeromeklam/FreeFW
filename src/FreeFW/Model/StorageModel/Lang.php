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
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_PK],
        FFCST::PROPERTY_COMMENT => 'Identifiant de la langue',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_LANG_NAME = [
        FFCST::PROPERTY_PRIVATE => 'lang_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_COMMENT => 'Le libellé de la langue',
        FFCST::PROPERTY_SAMPLE  => 'Français',
        FFCST::PROPERTY_MAX     => 32,
    ];
    protected static $PRP_LANG_CODE = [
        FFCST::PROPERTY_PRIVATE => 'lang_code',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Le code interne de la langue',
        FFCST::PROPERTY_SAMPLE  => 'fr',
        FFCST::PROPERTY_MAX     => 3,
    ];
    protected static $PRP_LANG_ISO = [
        FFCST::PROPERTY_PRIVATE => 'lang_iso',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Le code iso de la langue',
        FFCST::PROPERTY_SAMPLE  => 'fr_FR',
        FFCST::PROPERTY_MAX     => 10,
    ];
    protected static $PRP_LANG_FLAG = [
        FFCST::PROPERTY_PRIVATE => 'lang_flag',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Le code du drapeau de la langue',
        FFCST::PROPERTY_SAMPLE  => 'fra',
        FFCST::PROPERTY_MAX     => 20,
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
        return 'sys_lang';
    }

    /**
     * Retourne une explication de la table
     */
    public static function getSourceComments()
    {
        return 'Gestion des langues';
    }

    /**
     * Get autocomplete field
     *
     * @return string
     */
    public static function getAutocompleteField()
    {
        return 'lang_name';
    }

    /**
     * Get uniq indexes
     *
     * @return array[]
     */
    public static function getUniqIndexes()
    {
        return [
            'name' => [
                FFCST::INDEX_FIELDS => 'lang_name',
                FFCST::INDEX_EXISTS => FFCST::ERROR_LANG_NAME_EXISTS,
            ],
            'code' => [
                FFCST::INDEX_FIELDS => 'lang_code',
                FFCST::INDEX_EXISTS => FFCST::ERROR_LANG_CODE_EXISTS,
            ],
            'iso' => [
                FFCST::INDEX_FIELDS => 'lang_iso',
                FFCST::INDEX_EXISTS => FFCST::ERROR_LANG_ISO_EXISTS,
            ]
        ];
    }
}
