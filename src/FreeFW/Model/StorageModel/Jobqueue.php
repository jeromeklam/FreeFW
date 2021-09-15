<?php
namespace FreeFW\Model\StorageModel;

use \FreeFW\Constants as FFCST;

/**
 * Jobqueue
 *
 * @author jeromeklam
 */
abstract class Jobqueue extends \FreeFW\Core\StorageModel
{

    /**
     * Field properties as static arrays
     * @var array
     */
    protected static $PRP_JOBQ_ID = [
        FFCST::PROPERTY_PRIVATE => 'jobq_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_PK],
        FFCST::PROPERTY_TITLE   => 'Id.',
        FFCST::PROPERTY_COMMENT => 'Identifiant de la tâche',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_BRK_ID = [
        FFCST::PROPERTY_PRIVATE => 'brk_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_BROKER],
        FFCST::PROPERTY_TITLE   => 'Broker',
        FFCST::PROPERTY_COMMENT => 'Identifiant du broker, pour restriction',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_JOBQ_NAME = [
        FFCST::PROPERTY_PRIVATE => 'jobq_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_TITLE   => 'Nom',
        FFCST::PROPERTY_COMMENT => 'Le nom de la tâche',
        FFCST::PROPERTY_SAMPLE  => 'Mon job 1',
        FFCST::PROPERTY_MAX     => 255,
    ];
    protected static $PRP_JOBQ_DESC = [
        FFCST::PROPERTY_PRIVATE => 'jobq_desc',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Description',
        FFCST::PROPERTY_COMMENT => 'Description de la tâche',
        FFCST::PROPERTY_SAMPLE  => 'Tâche pour test',
    ];
    protected static $PRP_GRP_ID = [
        FFCST::PROPERTY_PRIVATE => 'grp_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_FK],
        FFCST::PROPERTY_DEFAULT => FFCST::DEFAULT_CURRENT_GROUP,
        FFCST::PROPERTY_TITLE   => 'Groupe',
        FFCST::PROPERTY_COMMENT => 'Identifiant du groupe déclencheur',
        FFCST::PROPERTY_SAMPLE  => 123,
        FFCST::PROPERTY_FK      => ['group' =>
            [
                FFCST::FOREIGN_MODEL => 'FreeSSO::Model::Group',
                FFCST::FOREIGN_FIELD => 'grp_id',
                FFCST::FOREIGN_TYPE  => \FreeFW\Model\Query::JOIN_LEFT
            ]
        ]
    ];
    protected static $PRP_USER_ID = [
        FFCST::PROPERTY_PRIVATE => 'user_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_FK],
        FFCST::PROPERTY_DEFAULT => FFCST::DEFAULT_CURRENT_USER,
        FFCST::PROPERTY_TITLE   => 'User',
        FFCST::PROPERTY_COMMENT => 'Identifiant de l\'utilisateur déclencheur',
        FFCST::PROPERTY_SAMPLE  => 123,
        FFCST::PROPERTY_FK      => ['user' =>
            [
                FFCST::FOREIGN_MODEL => 'FreeSSO::Model::User',
                FFCST::FOREIGN_FIELD => 'user_id',
                FFCST::FOREIGN_TYPE  => \FreeFW\Model\Query::JOIN_LEFT
            ]
        ]
    ];
    protected static $PRP_JOBQ_TYPE = [
        FFCST::PROPERTY_PRIVATE => 'jobq_type',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_SELECT,
        FFCST::PROPERTY_ENUM    => ['LOOP','ONCE'],
        FFCST::PROPERTY_DEFAULT => 'ONCE',
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_TITLE   => 'Type',
        FFCST::PROPERTY_COMMENT => 'Type de tâche',
        FFCST::PROPERTY_SAMPLE  => 'ONCE',
    ];
    protected static $PRP_JOBQ_STATUS = [
        FFCST::PROPERTY_PRIVATE => 'jobq_status',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_SELECT,
        FFCST::PROPERTY_ENUM    => ['WAITING','FINISHED','ERROR','PENDING','RETRY'],
        FFCST::PROPERTY_DEFAULT => 'WAITING',
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_TITLE   => 'Status',
        FFCST::PROPERTY_COMMENT => 'Statut de tâche',
        FFCST::PROPERTY_SAMPLE  => 'ERROR',
    ];
    protected static $PRP_JOBQ_TS = [
        FFCST::PROPERTY_PRIVATE => 'jobq_ts',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_DEFAULT => FFCST::DEFAULT_NOW,
        FFCST::PROPERTY_TITLE   => 'Créé le',
        FFCST::PROPERTY_COMMENT => 'Date heure de création',
        FFCST::PROPERTY_SAMPLE  => '2020-01-01 15:00:12',
    ];
    protected static $PRP_JOBQ_LAST_REPORT = [
        FFCST::PROPERTY_PRIVATE => 'jobq_last_report',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Rapport',
        FFCST::PROPERTY_COMMENT => 'Rapport du dernier lancement',
        FFCST::PROPERTY_SAMPLE  => 'Tout est ok',
    ];
    protected static $PRP_JOBQ_LAST_TS = [
        FFCST::PROPERTY_PRIVATE => 'jobq_last_ts',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_DEFAULT => FFCST::DEFAULT_NOW,
        FFCST::PROPERTY_TITLE   => 'Date rapport',
        FFCST::PROPERTY_COMMENT => 'Date heure de dernière mise à jour',
        FFCST::PROPERTY_SAMPLE  => '2020-04-01 15:00:12',
    ];
    protected static $PRP_JOBQ_SERVICE = [
        FFCST::PROPERTY_PRIVATE => 'jobq_service',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Service',
        FFCST::PROPERTY_COMMENT => 'Service à exécuter',
        FFCST::PROPERTY_SAMPLE  => 'FreeFW::Service::Message',
        FFCST::PROPERTY_MAX     => 80,
    ];
    protected static $PRP_JOBQ_METHOD = [
        FFCST::PROPERTY_PRIVATE => 'jobq_method',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Méthode',
        FFCST::PROPERTY_COMMENT => 'Méthode du service à exécuter',
        FFCST::PROPERTY_SAMPLE  => 'sendEmails',
        FFCST::PROPERTY_MAX     => 80,
    ];
    protected static $PRP_JOBQ_PARAMS = [
        FFCST::PROPERTY_PRIVATE => 'jobq_params',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_JSON,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Paramètres',
        FFCST::PROPERTY_COMMENT => 'Paramètres de la méthode à exécuter',
        FFCST::PROPERTY_SAMPLE  => '{}',
    ];
    protected static $PRP_JOBQ_MAX_RETRY = [
        FFCST::PROPERTY_PRIVATE => 'jobq_max_retry',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_INTEGER,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_DEFAULT => 1,
        FFCST::PROPERTY_TITLE   => 'Nb essais',
        FFCST::PROPERTY_COMMENT => 'Nombre maximum d\'essais',
        FFCST::PROPERTY_SAMPLE  => 3,
    ];
    protected static $PRP_JOBQ_NB_RETRY = [
        FFCST::PROPERTY_PRIVATE => 'jobq_nb_retry',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_INTEGER,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_DEFAULT => 0,
        FFCST::PROPERTY_TITLE   => 'Nb tentatives',
        FFCST::PROPERTY_COMMENT => 'Nombre d\'essais',
        FFCST::PROPERTY_SAMPLE  => 0,
    ];
    protected static $PRP_JOBQ_NEXT_MINUTES = [
        FFCST::PROPERTY_PRIVATE => 'jobq_next_minutes',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_INTEGER,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_DEFAULT => 0,
        FFCST::PROPERTY_TITLE   => 'Minutes',
        FFCST::PROPERTY_COMMENT => 'Nombre de minutes entre chaque appel',
        FFCST::PROPERTY_SAMPLE  => 3600,
    ];
    protected static $PRP_JOBQ_NEXT_RETRY = [
        FFCST::PROPERTY_PRIVATE => 'jobq_next_retry',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_DEFAULT => FFCST::DEFAULT_NOW,
        FFCST::PROPERTY_TITLE   => 'Prochain',
        FFCST::PROPERTY_COMMENT => 'Date heure du prochain lancement',
        FFCST::PROPERTY_SAMPLE  => '2020-05-01 23:00:00',
    ];

    /**
     * get properties
     *
     * @return array[]
     */
    public static function getProperties()
    {
        return [
            'jobq_id'           => self::$PRP_JOBQ_ID,
            'brk_id'            => self::$PRP_BRK_ID,
            'jobq_name'         => self::$PRP_JOBQ_NAME,
            'jobq_desc'         => self::$PRP_JOBQ_DESC,
            'grp_id'            => self::$PRP_GRP_ID,
            'user_id'           => self::$PRP_USER_ID,
            'jobq_type'         => self::$PRP_JOBQ_TYPE,
            'jobq_status'       => self::$PRP_JOBQ_STATUS,
            'jobq_ts'           => self::$PRP_JOBQ_TS,
            'jobq_last_report'  => self::$PRP_JOBQ_LAST_REPORT,
            'jobq_last_ts'      => self::$PRP_JOBQ_LAST_TS,
            'jobq_service'      => self::$PRP_JOBQ_SERVICE,
            'jobq_method'       => self::$PRP_JOBQ_METHOD,
            'jobq_params'       => self::$PRP_JOBQ_PARAMS,
            'jobq_max_retry'    => self::$PRP_JOBQ_MAX_RETRY,
            'jobq_nb_retry'     => self::$PRP_JOBQ_NB_RETRY,
            'jobq_next_minutes' => self::$PRP_JOBQ_NEXT_MINUTES,
            'jobq_next_retry'   => self::$PRP_JOBQ_NEXT_RETRY
        ];
    }

    /**
     * Set object source
     *
     * @return string
     */
    public static function getSource()
    {
        return 'sys_jobqueue';
    }

    /**
     * Retourne une explication de la table
     */
    public static function getSourceTitle()
    {
        return 'Tâches planifiées';
    }

    /**
     * Retourne une explication de la table
     */
    public static function getSourceComments()
    {
        return 'Gestion des tâches planifiées, ponctuelles, différées';
    }

    /**
     * Get autocomplete field
     *
     * @return string
     */
    public static function getAutocompleteField()
    {
        return 'jobq_name';
    }
}
