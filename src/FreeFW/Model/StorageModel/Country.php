<?php
namespace FreeFW\Model\StorageModel;

use \FreeFW\Constants as FFCST;

/**
 * Country
 *
 * @author jeromeklam
 */
abstract class Country extends \FreeFW\Core\StorageModel
{

    /**
     * Field properties as static arrays
     * @var array
     */
    protected static $PRP_CNTY_ID = [
        FFCST::PROPERTY_PRIVATE => 'cnty_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_PK],
        FFCST::PROPERTY_COMMENT => 'Identifiant du pays',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_CNTY_NAME = [
        FFCST::PROPERTY_PRIVATE => 'cnty_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_COMMENT => 'Nom du pays',
        FFCST::PROPERTY_SAMPLE  => 'France',
        FFCST::PROPERTY_MAX     => 80,
    ];
    protected static $PRP_CNTY_CODE = [
        FFCST::PROPERTY_PRIVATE => 'cnty_code',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Code ISO 3166 du pays',
        FFCST::PROPERTY_SAMPLE  => 'FR',
        FFCST::PROPERTY_MAX     => 3,
    ];

    /**
     * get properties
     *
     * @return array[]
     */
    public static function getProperties()
    {
        return [
            'cnty_id'   => self::$PRP_CNTY_ID,
            'cnty_name' => self::$PRP_CNTY_NAME,
            'cnty_code' => self::$PRP_CNTY_CODE
        ];
    }

    /**
     * Set object source
     *
     * @return string
     */
    public static function getSource()
    {
        return 'sys_country';
    }

    /**
     * Retourne une explication de la table
     */
    public static function getSourceComments()
    {
        return 'Gestion des pays';
    }

    /**
     * Get autocomplete field
     *
     * @return string
     */
    public static function getAutocompleteField()
    {
        return 'cnty_name';
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
                FFCST::INDEX_FIELDS => 'cnty_name',
                FFCST::INDEX_EXISTS => FFCST::ERROR_COUNTRY_NAME_EXISTS,
            ],
            'code' => [
                FFCST::INDEX_FIELDS => 'cnty_code',
                FFCST::INDEX_EXISTS => FFCST::ERROR_COUNTRY_CODE_EXISTS,
            ]
        ];
    }
}
