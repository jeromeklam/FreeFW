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
        FFCST::PROPERTY_COMMENT => 'Identifiant du broker',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_USER_ID = [
        FFCST::PROPERTY_PRIVATE => 'user_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_FK],
        FFCST::PROPERTY_COMMENT => 'Identifiant de l\'utilisateur à l\'origine de l\'alerte',
        FFCST::PROPERTY_SAMPLE  => 123,
        FFCST::PROPERTY_FK      => ['user' =>
            [
                FFCST::FOREIGN_MODEL => 'FreeSSO::Model::User',
                FFCST::FOREIGN_FIELD => 'user_id',
                FFCST::FOREIGN_TYPE  => \FreeFW\Model\Query::JOIN_LEFT,
            ]
        ],
    ];
    protected static $PRP_ALERT_OBJECT_NAME = [
        FFCST::PROPERTY_PRIVATE => 'alert_object_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Nom de l\'objet',
        FFCST::PROPERTY_MAX     => 32,
        FFCST::PROPERTY_SAMPLE  => 'FreeAsso_Cause',
    ];
    protected static $PRP_ALERT_OBJECT_ID = [
        FFCST::PROPERTY_PRIVATE => 'alert_object_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Identifiant dfe l\'objet',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_ALERT_TITLE = [
        FFCST::PROPERTY_PRIVATE => 'alert_title',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_COMMENT => 'Titre de l\'alerte',
        FFCST::PROPERTY_MAX     => 255,
        FFCST::PROPERTY_SAMPLE  => 'John Doe',
    ];
    protected static $PRP_ALERT_FROM = [
        FFCST::PROPERTY_PRIVATE => 'alert_from',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Date de création de l\'alerte',
        FFCST::PROPERTY_SAMPLE  => '2020-08-09 12:11:00',
    ];
    protected static $PRP_ALERT_TO = [
        FFCST::PROPERTY_PRIVATE => 'alert_to',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Date de fin de l\'alerte',
        FFCST::PROPERTY_SAMPLE  => 'null',
    ];
    protected static $PRP_ALERT_TS = [
        FFCST::PROPERTY_PRIVATE => 'alert_ts',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_DEFAULT => FFCST::DEFAULT_NOW,
        FFCST::PROPERTY_COMMENT => 'Date de dernière mise à jour de l\'alerte',
        FFCST::PROPERTY_SAMPLE  => '2020-08-09 12:11:00',
    ];
    protected static $PRP_ALERT_DEADLINE = [
        FFCST::PROPERTY_PRIVATE => 'alert_deadline',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Date d\'échéance de l\'alerte',
        FFCST::PROPERTY_SAMPLE  => '2020-08-10 12:11:00',
    ];
    protected static $PRP_ALERT_DONE_TS = [
        FFCST::PROPERTY_PRIVATE => 'alert_done_ts',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Date de réalasation de l\'alerte',
        FFCST::PROPERTY_SAMPLE  => 'null',
    ];
    protected static $PRP_ALERT_DONE_ACTION = [
        FFCST::PROPERTY_PRIVATE => 'alert_done_action',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Action réalisant l\'alerte automatiquement',
        FFCST::PROPERTY_MAX     => 80,
        FFCST::PROPERTY_SAMPLE  => 'SAVE_TEST',
    ];
    protected static $PRP_ALERT_DONE_USER_ID = [
        FFCST::PROPERTY_PRIVATE => 'alert_done_user_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_FK],
        FFCST::PROPERTY_COMMENT => 'Utilisateur ayant réalisé l\'alerte',
        FFCST::PROPERTY_SAMPLE  => 123,
        FFCST::PROPERTY_FK      => ['done_by' =>
            [
                FFCST::FOREIGN_MODEL => 'FreeSSO::Model::User',
                FFCST::FOREIGN_FIELD => 'user_id',
                FFCST::FOREIGN_TYPE  => \FreeFW\Model\Query::JOIN_LEFT,
            ]
        ],
    ];
    protected static $PRP_ALERT_DONE_TEXT = [
        FFCST::PROPERTY_PRIVATE => 'alert_done_text',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_HTML,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Texte de réalisation',
        FFCST::PROPERTY_SAMPLE  => 'bla bla',
    ];
    protected static $PRP_ALERT_CODE = [
        FFCST::PROPERTY_PRIVATE => 'alert_code',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Variable à choix multiple pour une catégorie par type d\'objet',
        FFCST::PROPERTY_MAX     => 80,
        FFCST::PROPERTY_SAMPLE  => 'WATER',
    ];
    protected static $PRP_ALERT_TEXT = [
        FFCST::PROPERTY_PRIVATE => 'alert_text',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_HTML,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Description de l\'alerte',
        FFCST::PROPERTY_SAMPLE  => 'Besoin de remplir l\'eau',
    ];
    protected static $PRP_ALERT_ACTIV = [
        FFCST::PROPERTY_PRIVATE => 'alert_activ',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BOOLEAN,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_DEFAULT => FFCST::DEFAULT_TRUE,
        FFCST::PROPERTY_COMMENT => 'Alerte active',
        FFCST::PROPERTY_SAMPLE  => true,
    ];
    protected static $PRP_ALERT_PRIORITY = [
        FFCST::PROPERTY_PRIVATE => 'alert_priority',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_SELECT,
        FFCST::PROPERTY_ENUM    => ['IMPORTANT','CRITICAL','INFORMATION','TODO','NONE'],
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_DEFAULT => 'TODO',
        FFCST::PROPERTY_COMMENT => 'Priorité',
        FFCST::PROPERTY_MAX     => 11,
        FFCST::PROPERTY_SAMPLE  => 'TODO',
    ];
    protected static $PRP_ALERT_RECUR_TYPE = [
        FFCST::PROPERTY_PRIVATE => 'alert_recur_type',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_SELECT,
        FFCST::PROPERTY_ENUM    => ['HOUR','MINUTE','DAY','MONTH','YEAR','MANUAL','NONE'],
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_DEFAULT => 'NONE',
        FFCST::PROPERTY_COMMENT => 'Type de récurrence de l\'alerte',
        FFCST::PROPERTY_SAMPLE  => 'MONTH',
    ];
    protected static $PRP_ALERT_RECUR_NUMBER = [
        FFCST::PROPERTY_PRIVATE => 'alert_recur_number',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_INTEGER,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Durée pour la récurrence',
        FFCST::PROPERTY_SAMPLE  => 1,
    ];
    protected static $PRP_ALERT_EMAIL_1 = [
        FFCST::PROPERTY_PRIVATE => 'alert_email_1',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_SELECT,
        FFCST::PROPERTY_ENUM    => ['NONE','15M','30M','1H','2H','1D','2D'],
        FFCST::PROPERTY_DEFAULT => 'NONE',
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Rappel par email 1',
        FFCST::PROPERTY_SAMPLE  => '1H',
    ];
    protected static $PRP_ALERT_EMAIL_2 = [
        FFCST::PROPERTY_PRIVATE => 'alert_email_2',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_SELECT,
        FFCST::PROPERTY_ENUM    => ['NONE','15M','30M','1H','2H','1D','2D'],
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_DEFAULT => 'NONE',
        FFCST::PROPERTY_COMMENT => 'Rappel par email 2',
        FFCST::PROPERTY_SAMPLE  => 'NONE',
    ];
    protected static $PRP_ALERT_STRING_1 = [
        FFCST::PROPERTY_PRIVATE => 'alert_string_1',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Variable de type chaine 1',
        FFCST::PROPERTY_MAX     => 32,
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_ALERT_STRING_2 = [
        FFCST::PROPERTY_PRIVATE => 'alert_string_2',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Variable de type chaine 2',
        FFCST::PROPERTY_MAX     => 32,
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_ALERT_NUMBER_1 = [
        FFCST::PROPERTY_PRIVATE => 'alert_number_1',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_INTEGER,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Variable de type entier 1',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_ALERT_NUMER_2 = [
        FFCST::PROPERTY_PRIVATE => 'alert_numer_2',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_INTEGER,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Variable de type entier 2',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_ALERT_BOOL_1 = [
        FFCST::PROPERTY_PRIVATE => 'alert_bool_1',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BOOLEAN,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Variable de type booléen 1',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_ALERT_BOOL_2 = [
        FFCST::PROPERTY_PRIVATE => 'alert_bool_2',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BOOLEAN,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Variable de type booléen 1',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_ALERT_TEXT_1 = [
        FFCST::PROPERTY_PRIVATE => 'alert_text_1',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_HTML,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Variable de type texte 1',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_ALERT_TEXT_2 = [
        FFCST::PROPERTY_PRIVATE => 'alert_text_2',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_HTML,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Variable de type texte 1',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_ALERT_TASK = [
        FFCST::PROPERTY_PRIVATE => 'alert_task',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BOOLEAN,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_DEFAULT => 1,
        FFCST::PROPERTY_COMMENT => 'Tâche du calendrier ?',
        FFCST::PROPERTY_SAMPLE  => 1,
    ];
    protected static $PRP_ALERT_PARENT_ID = [
        FFCST::PROPERTY_PRIVATE => 'alert_parent_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_FK],
        FFCST::PROPERTY_COMMENT => 'Identifiant de l\'alerte parent',
        FFCST::PROPERTY_SAMPLE  => 123,
        FFCST::PROPERTY_FK      => ['parent' =>
            [
                FFCST::FOREIGN_MODEL => 'FreeFW::Model::Alert',
                FFCST::FOREIGN_FIELD => 'alert_id',
                FFCST::FOREIGN_TYPE  => \FreeFW\Model\Query::JOIN_LEFT,
            ]
        ],
    ];
    protected static $PRP_ALERC_ID = [
        FFCST::PROPERTY_PRIVATE => 'alerc_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_FK],
        FFCST::PROPERTY_COMMENT => 'Identifiant de la catégorie d\'alerte',
        FFCST::PROPERTY_SAMPLE  => 123,
        FFCST::PROPERTY_FK      => ['alert_category' =>
            [
                FFCST::FOREIGN_MODEL => 'FreeFW::Model::AlertCategory',
                FFCST::FOREIGN_FIELD => 'alerc_id',
                FFCST::FOREIGN_TYPE  => \FreeFW\Model\Query::JOIN_LEFT,
            ]
        ],
    ];
    protected static $PRP_ALERT_CHECKLIST = [
        FFCST::PROPERTY_PRIVATE => 'alert_checklist',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Checklists au format json',
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
            'alert_id'           => self::$PRP_ALERT_ID,
            'brk_id'             => self::$PRP_BRK_ID,
            'user_id'            => self::$PRP_USER_ID,
            'alert_object_name'  => self::$PRP_ALERT_OBJECT_NAME,
            'alert_object_id'    => self::$PRP_ALERT_OBJECT_ID,
            'alert_title'        => self::$PRP_ALERT_TITLE,
            'alert_from'         => self::$PRP_ALERT_FROM,
            'alert_to'           => self::$PRP_ALERT_TO,
            'alert_ts'           => self::$PRP_ALERT_TS,
            'alert_deadline'     => self::$PRP_ALERT_DEADLINE,
            'alert_done_ts'      => self::$PRP_ALERT_DONE_TS,
            'alert_done_action'  => self::$PRP_ALERT_DONE_ACTION,
            'alert_done_user_id' => self::$PRP_ALERT_DONE_USER_ID,
            'alert_done_text'    => self::$PRP_ALERT_DONE_TEXT,
            'alert_code'         => self::$PRP_ALERT_CODE,
            'alert_text'         => self::$PRP_ALERT_TEXT,
            'alert_activ'        => self::$PRP_ALERT_ACTIV,
            'alert_priority'     => self::$PRP_ALERT_PRIORITY,
            'alert_recur_type'   => self::$PRP_ALERT_RECUR_TYPE,
            'alert_recur_number' => self::$PRP_ALERT_RECUR_NUMBER,
            'alert_email_1'      => self::$PRP_ALERT_EMAIL_1,
            'alert_email_2'      => self::$PRP_ALERT_EMAIL_2,
            'alert_string_1'     => self::$PRP_ALERT_STRING_1,
            'alert_string_2'     => self::$PRP_ALERT_STRING_2,
            'alert_number_1'     => self::$PRP_ALERT_NUMBER_1,
            'alert_numer_2'      => self::$PRP_ALERT_NUMER_2,
            'alert_bool_1'       => self::$PRP_ALERT_BOOL_1,
            'alert_bool_2'       => self::$PRP_ALERT_BOOL_2,
            'alert_text_1'       => self::$PRP_ALERT_TEXT_1,
            'alert_text_2'       => self::$PRP_ALERT_TEXT_2,
            'alert_task'         => self::$PRP_ALERT_TASK,
            'alert_parent_id'    => self::$PRP_ALERT_PARENT_ID,
            'alerc_id'           => self::$PRP_ALERC_ID,
            'alert_checklist'    => self::$PRP_ALERT_CHECKLIST,
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
        return 'Table système des alertes';
    }

    /**
     * Get autocomplete field
     *
     * @return string
     */
    public static function getAutocompleteField()
    {
        return ['alert_title', 'alert_text', 'alert_checklist'];
    }
}
