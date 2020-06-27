<?php
namespace FreeFW\Model\StorageModel;

use \FreeFW\Constants as FFCST;

/**
 * Alert
 *
 * @author jeromeklam
 */
abstract class Alert extends \FreeFW\Core\StorageModel
{

    /**
     * Field properties as static arrays
     * @var array
     */
    protected static $PRP_ALERT_ID = [
        FFCST::PROPERTY_PRIVATE => 'alert_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_PK],
        FFCST::PROPERTY_COMMENT => 'Identifiant de l\'alerte',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_BRK_ID = [
        FFCST::PROPERTY_PRIVATE => 'brk_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_BROKER],
        FFCST::PROPERTY_COMMENT => 'Identifiant du broker, pour restriction',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_USER_ID = [
        FFCST::PROPERTY_PRIVATE => 'user_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_USER],
        FFCST::PROPERTY_COMMENT => 'L\'utilisateur à l\'origine de l\'alerte',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_ALERT_OBJECT_NAME = [
        FFCST::PROPERTY_PRIVATE => 'alert_object_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_COMMENT => 'Le nom de l\'objet ou table',
        FFCST::PROPERTY_MAX     => 32,
        FFCST::PROPERTY_SAMPLE  => 'sys_lang',
    ];
    protected static $PRP_ALERT_OBJECT_ID = [
        FFCST::PROPERTY_PRIVATE => 'alert_object_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_COMMENT => 'L\'identifiant fe l\'objet ou table',
        FFCST::PROPERTY_SAMPLE  => 34,
    ];
    protected static $PRP_ALERT_FROM = [
        FFCST::PROPERTY_PRIVATE => 'alert_from',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Début de validité de l\'alerte',
        FFCST::PROPERTY_SAMPLE  => '2020-06-03',
    ];
    protected static $PRP_ALERT_TO = [
        FFCST::PROPERTY_PRIVATE => 'alert_to',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Fin de validité de l\'alerte',
        FFCST::PROPERTY_SAMPLE  => '2020-07-03',
    ];
    protected static $PRP_ALERT_TS = [
        FFCST::PROPERTY_PRIVATE => 'alert_ts',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_DEFAULT => FFCST::DEFAULT_NOW,
        FFCST::PROPERTY_COMMENT => 'Date de l\'alerte',
        FFCST::PROPERTY_SAMPLE  => '2020-06-01',
    ];
    protected static $PRP_ALERT_DONE_TS = [
        FFCST::PROPERTY_PRIVATE => 'alert_done_ts',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Début de clôture de l\'alerte',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_ALERT_DONE_ACTION = [
        FFCST::PROPERTY_PRIVATE => 'alert_done_action',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Code de l\'action qui peut clôturer cette alerte',
        FFCST::PROPERTY_MAX     => 80,
        FFCST::PROPERTY_SAMPLE  => 'PRINTED',
    ];
    protected static $PRP_ALERT_DONE_USER_ID = [
        FFCST::PROPERTY_PRIVATE => 'alert_done_user_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_FK],
        FFCST::PROPERTY_COMMENT => 'Utilisateur de clôture',
        FFCST::PROPERTY_SAMPLE  => 123,
        FFCST::PROPERTY_FK      => ['user' =>
            [
                FFCST::FOREIGN_MODEL => 'FreeSSO::Model::User',
                FFCST::FOREIGN_FIELD => 'alert_done_user_id',
                FFCST::FOREIGN_TYPE  => \FreeFW\Model\Query::JOIN_LEFT,
            ]
        ],
    ];
    protected static $PRP_ALERT_CODE = [
        FFCST::PROPERTY_PRIVATE => 'alert_code',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Code de l\'action, pour l\'intl',
        FFCST::PROPERTY_MAX     => 80,
        FFCST::PROPERTY_SAMPLE  => 'MUST_PRINT',
    ];
    protected static $PRP_ALERT_TEXT = [
        FFCST::PROPERTY_PRIVATE => 'alert_text',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BLOB,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Texte',
        FFCST::PROPERTY_SAMPLE  => 'Merci d\'imprimer...',
    ];
    protected static $PRP_ALERT_ACTIV = [
        FFCST::PROPERTY_PRIVATE => 'alert_activ',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BOOLEAN,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_DEFAULT => true,
        FFCST::PROPERTY_COMMENT => 'Alerte active ?',
        FFCST::PROPERTY_SAMPLE  => true,
    ];
    protected static $PRP_ALERT_PRIORITY = [
        FFCST::PROPERTY_PRIVATE => 'alert_priority',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_SELECT,
        FFCST::PROPERTY_ENUM    => ['IMPORTANT','CRITICAL','INFORMATION','TODO','NONE'],
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_DEFAULT => 'NONE',
        FFCST::PROPERTY_COMMENT => 'Priorité pour information',
        FFCST::PROPERTY_MAX     => 11,
        FFCST::PROPERTY_SAMPLE  => 'TODO',
    ];

    /**
     * get properties
     *
     * @return array[]
     */
    public static function getProperties()
    {
        return [
            'alert_id'           => self::$PRP_ALERT_ID,
            'brk_id'             => self::$PRP_BRK_ID,
            'user_id'            => self::$PRP_USER_ID,
            'alert_object_name'  => self::$PRP_ALERT_OBJECT_NAME,
            'alert_object_id'    => self::$PRP_ALERT_OBJECT_ID,
            'alert_from'         => self::$PRP_ALERT_FROM,
            'alert_to'           => self::$PRP_ALERT_TO,
            'alert_ts'           => self::$PRP_ALERT_TS,
            'alert_done_ts'      => self::$PRP_ALERT_DONE_TS,
            'alert_done_action'  => self::$PRP_ALERT_DONE_ACTION,
            'alert_done_user_id' => self::$PRP_ALERT_DONE_USER_ID,
            'alert_code'         => self::$PRP_ALERT_CODE,
            'alert_text'         => self::$PRP_ALERT_TEXT,
            'alert_activ'        => self::$PRP_ALERT_ACTIV,
            'alert_priority'     => self::$PRP_ALERT_PRIORITY
        ];
    }

    /**
     * Set object source
     *
     * @return string
     */
    public static function getSource()
    {
        return 'sys_alert';
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
