<?php
namespace FreeFW\Model\StorageModel;

use \FreeFW\Constants as FFCST;

/**
 * JobqueueHisto
 *
 * @author jeromeklam
 */
abstract class JobqueueHisto extends \FreeFW\Core\StorageModel
{

/**
 * Field properties as static arrays
 * @var array
 */
    protected static $PRP_JOBQH_ID = [
        FFCST::PROPERTY_PRIVATE => 'jobqh_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_PK]
    ];
    protected static $PRP_JOBQ_ID = [
        FFCST::PROPERTY_PRIVATE => 'jobq_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_FK],
        FFCST::PROPERTY_FK      => ['jobqueue' =>
            [
                'model' => 'FreeFW::Model::Jobqueue',
                'field' => 'jobq_id',
                'type'  => \FreeFW\Model\Query::JOIN_LEFT
            ]
        ]
    ];
    protected static $PRP_BRK_ID = [
        FFCST::PROPERTY_PRIVATE => 'brk_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_BROKER]
    ];
    protected static $PRP_JOBQH_TS = [
        FFCST::PROPERTY_PRIVATE => 'jobqh_ts',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_JOBQH_MSG = [
        FFCST::PROPERTY_PRIVATE => 'jobqh_msg',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_JOBQH_STATUS = [
        FFCST::PROPERTY_PRIVATE => 'jobqh_status',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
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
            'jobqh_id'     => self::$PRP_JOBQH_ID,
            'jobq_id'      => self::$PRP_JOBQ_ID,
            'brk_id'       => self::$PRP_BRK_ID,
            'jobqh_ts'     => self::$PRP_JOBQH_TS,
            'jobqh_msg'    => self::$PRP_JOBQH_MSG,
            'jobqh_status' => self::$PRP_JOBQH_STATUS
        ];
    }

    /**
     * Set object source
     *
     * @return string
     */
    public static function getSource()
    {
        return 'sys_jobqueue_histo';
    }
}
