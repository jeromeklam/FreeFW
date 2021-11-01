<?php
namespace FreeFW\Model\StorageModel;

use \FreeFW\Constants as FFCST;

/**
 * Translation
 *
 * @author jeromeklam
 */
abstract class Translation extends \FreeFW\Core\StorageModel
{

    /**
     * Field properties as static arrays
     * @var array
     */
    protected static $PRP_TR_ID = [
        FFCST::PROPERTY_PRIVATE => 'tr_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_PK],
        FFCST::PROPERTY_TITLE   => 'Id',
        FFCST::PROPERTY_COMMENT => 'Identifiant',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_TR_KEY = [
        FFCST::PROPERTY_PRIVATE => 'tr_key',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_TITLE   => 'Clef',
        FFCST::PROPERTY_COMMENT => 'Clef de la traduction',
        FFCST::PROPERTY_MAX     => 128,
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_TR_DESC = [
        FFCST::PROPERTY_PRIVATE => 'tr_desc',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BLOB,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Description',
        FFCST::PROPERTY_COMMENT => 'Description',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_TR_HTML = [
        FFCST::PROPERTY_PRIVATE => 'tr_html',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BOOLEAN,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_TITLE   => 'Html',
        FFCST::PROPERTY_COMMENT => 'Html',
        FFCST::PROPERTY_SAMPLE  => true,
    ];
    protected static $PRP_TR_TYPE = [
        FFCST::PROPERTY_PRIVATE => 'tr_type',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_SELECT,
        FFCST::PROPERTY_ENUM    => ['NODE','SHEET'],
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_DEFAULT => 'SHEET',
        FFCST::PROPERTY_TITLE   => 'Type',
        FFCST::PROPERTY_COMMENT => 'Type',
        FFCST::PROPERTY_MAX     => 5,
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_TR_LANG_FR = [
        FFCST::PROPERTY_PRIVATE => 'tr_lang_fr',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'FR',
        FFCST::PROPERTY_COMMENT => 'Français',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_TR_LANG_EN = [
        FFCST::PROPERTY_PRIVATE => 'tr_lang_en',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'EN',
        FFCST::PROPERTY_COMMENT => 'Anglais',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_TR_LANG_CH = [
        FFCST::PROPERTY_PRIVATE => 'tr_lang_ch',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'CH',
        FFCST::PROPERTY_COMMENT => 'Suisse',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_TR_LANG_DE = [
        FFCST::PROPERTY_PRIVATE => 'tr_lang_de',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'DE',
        FFCST::PROPERTY_COMMENT => 'Allemand',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_TR_LANG_ES = [
        FFCST::PROPERTY_PRIVATE => 'tr_lang_es',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'ES',
        FFCST::PROPERTY_COMMENT => 'Espagnol',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_TR_LANG_ID = [
        FFCST::PROPERTY_PRIVATE => 'tr_lang_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'ID',
        FFCST::PROPERTY_COMMENT => 'Indonésien',
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
            'tr_id'      => self::$PRP_TR_ID,
            'tr_key'     => self::$PRP_TR_KEY,
            'tr_desc'    => self::$PRP_TR_DESC,
            'tr_html'    => self::$PRP_TR_HTML,
            'tr_type'    => self::$PRP_TR_TYPE,
            'tr_lang_fr' => self::$PRP_TR_LANG_FR,
            'tr_lang_en' => self::$PRP_TR_LANG_EN,
            'tr_lang_ch' => self::$PRP_TR_LANG_CH,
            'tr_lang_de' => self::$PRP_TR_LANG_DE,
            'tr_lang_es' => self::$PRP_TR_LANG_ES,
            'tr_lang_id' => self::$PRP_TR_LANG_ID
        ];
    }

    /**
     * Set object source
     *
     * @return string
     */
    public static function getSource()
    {
        return 'sys_translation';
    }

    /**
     * Get object short description
     *
     * @return string
     */
    public static function getSourceTitle()
    {
        return 'Traductions';
    }

    /**
     * Get object short description
     *
     * @return string
     */
    public static function getSourceComments()
    {
        return 'Gestion des traductions';
    }

    /**
     * Get autocomplete field
     *
     * @return string
     */
    public static function getAutocompleteField()
    {
        return 'tr_key';
    }
}
