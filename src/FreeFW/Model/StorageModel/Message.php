<?php
namespace FreeFW\Model\StorageModel;

use \FreeFW\Constants as FFCST;

/**
 * Message
 *
 * @author jeromeklam
 */
abstract class Message extends \FreeFW\Core\StorageModel
{

/**
 * Field properties as static arrays
 * @var array
 */
    protected static $PRP_MSG_ID = [
        FFCST::PROPERTY_PRIVATE => 'msg_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_PK]
    ];
    protected static $PRP_BRK_ID = [
        FFCST::PROPERTY_PRIVATE => 'brk_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_BROKER]
    ];
    protected static $PRP_MSG_OBJECT_NAME = [
        FFCST::PROPERTY_PRIVATE => 'msg_object_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_MSG_OBJECT_ID = [
        FFCST::PROPERTY_PRIVATE => 'msg_object_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_LANG_ID = [
        FFCST::PROPERTY_PRIVATE => 'lang_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_FK],
        FFCST::PROPERTY_FK      => ['lang' =>
            [
                'model' => 'FreeFW::Model::Lang',
                'field' => 'lang_id',
                'type'  => \FreeFW\Model\Query::JOIN_LEFT
            ]
        ]
    ];
    protected static $PRP_MSG_TS = [
        FFCST::PROPERTY_PRIVATE => 'msg_ts',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_MSG_STATUS = [
        FFCST::PROPERTY_PRIVATE => 'msg_status',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED]
    ];
    protected static $PRP_MSG_TYPE = [
        FFCST::PROPERTY_PRIVATE => 'msg_type',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED]
    ];
    protected static $PRP_MSG_DEST = [
        FFCST::PROPERTY_PRIVATE => 'msg_dest',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_MSG_CC = [
        FFCST::PROPERTY_PRIVATE => 'msg_cc',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_MSG_BCC = [
        FFCST::PROPERTY_PRIVATE => 'msg_bcc',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_MSG_SUBJECT = [
        FFCST::PROPERTY_PRIVATE => 'msg_subject',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_MSG_BODY = [
        FFCST::PROPERTY_PRIVATE => 'msg_body',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_MSG_PJ1 = [
        FFCST::PROPERTY_PRIVATE => 'msg_pj1',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_MSG_PJ2 = [
        FFCST::PROPERTY_PRIVATE => 'msg_pj2',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_MSG_PJ3 = [
        FFCST::PROPERTY_PRIVATE => 'msg_pj3',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_MSG_PJ4 = [
        FFCST::PROPERTY_PRIVATE => 'msg_pj4',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_MSG_SEND_TS = [
        FFCST::PROPERTY_PRIVATE => 'msg_send_ts',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_MSG_SEND_ERROR = [
        FFCST::PROPERTY_PRIVATE => 'msg_send_error',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => []
    ];
    protected static $PRP_MSG_REPLY_TO = [
        FFCST::PROPERTY_PRIVATE => 'msg_reply_to',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
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
            'msg_id'          => self::$PRP_MSG_ID,
            'brk_id'          => self::$PRP_BRK_ID,
            'msg_object_name' => self::$PRP_MSG_OBJECT_NAME,
            'msg_object_id'   => self::$PRP_MSG_OBJECT_ID,
            'lang_id'         => self::$PRP_LANG_ID,
            'msg_ts'          => self::$PRP_MSG_TS,
            'msg_status'      => self::$PRP_MSG_STATUS,
            'msg_type'        => self::$PRP_MSG_TYPE,
            'msg_dest'        => self::$PRP_MSG_DEST,
            'msg_cc'          => self::$PRP_MSG_CC,
            'msg_bcc'         => self::$PRP_MSG_BCC,
            'msg_subject'     => self::$PRP_MSG_SUBJECT,
            'msg_body'        => self::$PRP_MSG_BODY,
            'msg_pj1'         => self::$PRP_MSG_PJ1,
            'msg_pj2'         => self::$PRP_MSG_PJ2,
            'msg_pj3'         => self::$PRP_MSG_PJ3,
            'msg_pj4'         => self::$PRP_MSG_PJ4,
            'msg_send_ts'     => self::$PRP_MSG_SEND_TS,
            'msg_send_error'  => self::$PRP_MSG_SEND_ERROR,
            'msg_reply_to'    => self::$PRP_MSG_REPLY_TO
        ];
    }

    /**
     * Set object source
     *
     * @return string
     */
    public static function getSource()
    {
        return 'sys_message';
    }
}
