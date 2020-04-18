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
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_PK]
    ];
    protected static $PRP_BRK_ID = [
        FFCST::PROPERTY_PRIVATE => 'brk_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_BROKER]
    ];
    protected static $PRP_JOBQ_NAME = [
        FFCST::PROPERTY_PRIVATE => 'jobq_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED]
    ];
    protected static $PRP_JOBQ_DESC = [
        FFCST::PROPERTY_PRIVATE => 'jobq_desc',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_GRP_ID = [
        FFCST::PROPERTY_PRIVATE => 'grp_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_FK],
        FFCST::PROPERTY_FK      => ['group' =>
            [
                'model' => 'FreeSSO::Model::Group',
                'field' => 'grp_id',
                'type'  => \FreeFW\Model\Query::JOIN_LEFT
            ]
        ]
    ];
    protected static $PRP_USER_ID = [
        FFCST::PROPERTY_PRIVATE => 'user_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_FK],
        FFCST::PROPERTY_FK      => ['user' =>
            [
                'model' => 'FreeSSO::Model::User',
                'field' => 'user_id',
                'type'  => \FreeFW\Model\Query::JOIN_LEFT
            ]
        ]
    ];
    protected static $PRP_JOBQ_TYPE = [
        FFCST::PROPERTY_PRIVATE => 'jobq_type',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED]
    ];
    protected static $PRP_JOBQ_STATUS = [
        FFCST::PROPERTY_PRIVATE => 'jobq_status',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED]
    ];
    protected static $PRP_JOBQ_TS = [
        FFCST::PROPERTY_PRIVATE => 'jobq_ts',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED]
    ];
    protected static $PRP_JOBQ_LAST_REPORT = [
        FFCST::PROPERTY_PRIVATE => 'jobq_last_report',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_JOBQ_LAST_TS = [
        FFCST::PROPERTY_PRIVATE => 'jobq_last_ts',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_JOBQ_SERVICE = [
        FFCST::PROPERTY_PRIVATE => 'jobq_service',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_JOBQ_METHOD = [
        FFCST::PROPERTY_PRIVATE => 'jobq_method',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_JOBQ_PARAMS = [
        FFCST::PROPERTY_PRIVATE => 'jobq_params',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_JOBQ_MAX_RETRY = [
        FFCST::PROPERTY_PRIVATE => 'jobq_max_retry',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_INTEGER,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED]
    ];
    protected static $PRP_JOBQ_NB_RETRY = [
        FFCST::PROPERTY_PRIVATE => 'jobq_nb_retry',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_INTEGER,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_JOBQ_NEXT_MINUTES = [
        FFCST::PROPERTY_PRIVATE => 'jobq_next_minutes',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_INTEGER,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_JOBQ_NEXT_RETRY = [
        FFCST::PROPERTY_PRIVATE => 'jobq_next_retry',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => []
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
}
