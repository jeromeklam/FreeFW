<?php
namespace FreeFW\Model\Model;

use \FreeFW\Constants as FFCST;

/**
 * Signin
 *
 * @author jeromeklam
 */
class PrintOptions extends \FreeFW\Core\StorageModel
{

    /**
     * Field properties as static arrays
     * @var array
     */
    protected static $PRT_NAME = [
        FFCST::PROPERTY_PRIVATE => 'prt_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_COMMENT => 'Le nom de l\'édition',
        FFCST::PROPERTY_SAMPLE  => 'impression du modèle',
        FFCST::PROPERTY_MAX     => 255,
    ];
    protected static $PRT_TYPE = [
        FFCST::PROPERTY_PRIVATE => 'prt_type',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_SELECT,
        FFCST::PROPERTY_ENUM    => ['EDITION','OTHER'],
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_COMMENT => 'Le type de l\'édition',
        FFCST::PROPERTY_DEFAULT => 'EDITION',
        FFCST::PROPERTY_SAMPLE  => 'EDITION',
    ];
    protected static $EDI_ID = [
        FFCST::PROPERTY_PRIVATE => 'edi_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Identifiant de l\'édition',
        FFCST::PROPERTY_SAMPLE  => 1,
    ];

    /**
     * get properties
     *
     * @return array[]
     */
    public static function getProperties()
    {
        return [
            'prt_name' => self::$PRT_NAME,
            'prt_type' => self::$PRT_TYPE,
            'edi_id'   => self::$EDI_ID,
        ];
    }

    /**
     * Set object source
     *
     * @return string
     */
    public static function getSource()
    {
        return 'dummy_print_options';
    }

    /**
     * Retourne une explication de la table
     */
    public static function getSourceComments()
    {
        return 'Options pour l\'impression';
    }
}
