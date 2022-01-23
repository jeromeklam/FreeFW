<?php

namespace FreeFW\Model\StorageModel;

use \FreeFW\Constants as FFCST;

/**
 * Taxonomy
 *
 * @author jeromeklam
 */
abstract class Taxonomy extends \FreeFW\Core\StorageModel
{

    /**
     * Field properties as static arrays
     * @var array
     */
    protected static $PRP_TX_ID = [
        FFCST::PROPERTY_PRIVATE => 'tx_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_PK],
        FFCST::PROPERTY_TITLE   => 'Id',
        FFCST::PROPERTY_COMMENT => 'Identifiant',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_TX_CODE = [
        FFCST::PROPERTY_PRIVATE => 'tx_code',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_TITLE   => 'Code',
        FFCST::PROPERTY_COMMENT => 'Code',
        FFCST::PROPERTY_MAX     => 80,
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_TX_DESC = [
        FFCST::PROPERTY_PRIVATE => 'tx_desc',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BLOB,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Description',
        FFCST::PROPERTY_COMMENT => 'Description',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_TX_OBJECT_NAME = [
        FFCST::PROPERTY_PRIVATE => 'tx_object_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Objet',
        FFCST::PROPERTY_COMMENT => 'Objet rattaché',
        FFCST::PROPERTY_MAX     => 80,
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_TX_OBJECT_ID = [
        FFCST::PROPERTY_PRIVATE => 'tx_object_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Id. Objet',
        FFCST::PROPERTY_COMMENT => 'Identifiant de l\'objet',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_TX_PARENT_ID = [
        FFCST::PROPERTY_PRIVATE => 'tx_parent_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_FK],
        FFCST::PROPERTY_TITLE   => 'Parent',
        FFCST::PROPERTY_COMMENT => 'Parent',
        FFCST::PROPERTY_SAMPLE  => 123,
        FFCST::PROPERTY_FK      => [
            'tx_parent_id' =>
            [
                FFCST::FOREIGN_MODEL => 'FreeFW::Model::Taxonomy',
                FFCST::FOREIGN_FIELD => 'tx_id',
                FFCST::FOREIGN_TYPE  => \FreeFW\Model\Query::JOIN_LEFT,
            ]
        ],
    ];

    /**
     * get properties
     *
     * @return array[]
     */
    public static function getProperties()
    {
        return [
            'tx_id'          => self::$PRP_TX_ID,
            'tx_code'        => self::$PRP_TX_CODE,
            'tx_desc'        => self::$PRP_TX_DESC,
            'tx_object_name' => self::$PRP_TX_OBJECT_NAME,
            'tx_object_id'   => self::$PRP_TX_OBJECT_ID,
            'tx_parent_id'   => self::$PRP_TX_PARENT_ID
        ];
    }

    /**
     * Set object source
     *
     * @return string
     */
    public static function getSource()
    {
        return 'sys_taxonomy';
    }

    /**
     * Get object short description
     *
     * @return string
     */
    public static function getSourceTitle()
    {
        return 'Taxonomie';
    }

    /**
     * Get object description
     *
     * @return string
     */
    public static function getSourceComments()
    {
        return 'Taxonomie';
    }

    /**
     * Get autocomplete field
     *
     * @return string
     */
    public static function getAutocompleteField()
    {
        return ['tx_code', 'tx_desc'];
    }

    /**
     * Get One To many relationShips
     *
     * @return array
     */
    public function getRelationships()
    {
        return [
            'traductions' => [
                FFCST::REL_MODEL   => 'FreeFW::Model::TaxonomyLang',
                FFCST::REL_FIELD   => 'tx_id',
                FFCST::REL_TYPE    => \FreeFW\Model\Query::JOIN_LEFT,
                FFCST::REL_COMMENT => 'Les traductions',
                FFCST::REL_REMOVE  => FFCST::REL_REMOVE_CASCADE
            ]
        ];
    }
}
