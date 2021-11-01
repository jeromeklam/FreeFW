<?php
namespace FreeFW\Model\StorageModel;

use \FreeFW\Constants as FFCST;

/**
 * Help
 *
 * @author jeromeklam
 */
abstract class Help extends \FreeFW\Core\StorageModel
{

    /**
     * Field properties as static arrays
     * @var array
     */
    protected static $PRP_HELP_ID = [
        FFCST::PROPERTY_PRIVATE => 'help_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_PK],
        FFCST::PROPERTY_TITLE   => 'Id.',
        FFCST::PROPERTY_COMMENT => 'Identifiant',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_HELP_NAME = [
        FFCST::PROPERTY_PRIVATE => 'help_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_TITLE   => 'Nom',
        FFCST::PROPERTY_COMMENT => 'Nom de l\'aide',
        FFCST::PROPERTY_MAX     => 255,
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_HELP_DESC = [
        FFCST::PROPERTY_PRIVATE => 'help_desc',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BLOB,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Description',
        FFCST::PROPERTY_COMMENT => 'Description de l\'aide',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_HELP_POSITION = [
        FFCST::PROPERTY_PRIVATE => 'help_position',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_INTEGER,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_TITLE   => 'Position',
        FFCST::PROPERTY_COMMENT => 'Position',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_HELP_TYPE = [
        FFCST::PROPERTY_PRIVATE => 'help_type',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_SELECT,
        FFCST::PROPERTY_ENUM    => ['HTML','PDF','MP4','URL'],
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_TITLE   => 'Type',
        FFCST::PROPERTY_COMMENT => 'Type',
        FFCST::PROPERTY_MAX     => 4,
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_HELP_CONTENT = [
        FFCST::PROPERTY_PRIVATE => 'help_content',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_HTML,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Contenu',
        FFCST::PROPERTY_COMMENT => 'Contenu',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_HELP_SCOPE = [
        FFCST::PROPERTY_PRIVATE => 'help_scope',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Scope',
        FFCST::PROPERTY_COMMENT => 'Scope',
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
            'help_id'       => self::$PRP_HELP_ID,
            'help_name'     => self::$PRP_HELP_NAME,
            'help_desc'     => self::$PRP_HELP_DESC,
            'help_position' => self::$PRP_HELP_POSITION,
            'help_type'     => self::$PRP_HELP_TYPE,
            'help_content'  => self::$PRP_HELP_CONTENT,
            'help_scope'    => self::$PRP_HELP_SCOPE
        ];
    }

    /**
     * Set object source
     *
     * @return string
     */
    public static function getSource()
    {
        return 'sys_help';
    }

    /**
     * Get object short description
     *
     * @return string
     */
    public static function getSourceTitle()
    {
        return 'Aide';
    }

    /**
     * Get object description
     *
     * @return string
     */
    public static function getSourceComments()
    {
        return 'Gestion de l\'aide';
    }

    /**
     * Get autocomplete field
     *
     * @return string
     */
    public static function getAutocompleteField()
    {
        return 'help_name';
    }
}
