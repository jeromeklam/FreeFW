<?php
namespace FreeFW\Model\StorageModel;

use \FreeFW\Constants as FFCST;

/**
 * Notification
 *
 * @author jeromeklam
 */
abstract class Notification extends \FreeFW\Core\StorageModel
{

    /**
     * Field properties as static arrays
     * @var array
     */
    protected static $PRP_NOTIF_ID = [
        FFCST::PROPERTY_PRIVATE => 'notif_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_PK],
        FFCST::PROPERTY_COMMENT => 'Identifiant de la notification',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_BRK_ID = [
        FFCST::PROPERTY_PRIVATE => 'brk_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_BROKER],
        FFCST::PROPERTY_COMMENT => 'Identifiant du broker, pour restriction',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_NOTIF_TEXT = [
        FFCST::PROPERTY_PRIVATE => 'notif_text',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_HTML,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Le texte de la notification, html de préférence',
        FFCST::PROPERTY_SAMPLE  => '<p>Ma notification</p>',
    ];
    protected static $PRP_NOTIF_SUBJECT = [
        FFCST::PROPERTY_PRIVATE => 'notif_subject',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Le sujet de la notification, ne pas mettre d\'html',
        FFCST::PROPERTY_SAMPLE  => 'Mon sujet',
    ];
    protected static $PRP_NOTIF_OBJECT_NAME = [
        FFCST::PROPERTY_PRIVATE => 'notif_object_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Le nom de l\'objet concerné',
        FFCST::PROPERTY_SAMPLE  => 'FreeFW_Notification',
    ];
    protected static $PRP_NOTIF_OBJECT_ID = [
        FFCST::PROPERTY_PRIVATE => 'notif_object_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'L\'indentifiant de l\'objet concerné',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_NOTIF_CODE = [
        FFCST::PROPERTY_PRIVATE => 'notif_code',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Code interne de la notification',
        FFCST::PROPERTY_SAMPLE  => 'RAPPEL',
    ];
    protected static $PRP_NOTIF_TS = [
        FFCST::PROPERTY_PRIVATE => 'notif_ts',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_DEFAULT => FFCST::DEFAULT_NOW,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'La date de création de la notification',
        FFCST::PROPERTY_SAMPLE  => '2020-04-01 14:00:00',
    ];
    protected static $PRP_NOTIF_TYPE = [
        FFCST::PROPERTY_PRIVATE => 'notif_type',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_SELECT,
        FFCST::PROPERTY_ENUM    => ['ERROR','WARNING','INFORMATION','MANUAL','OTHER'],
        FFCST::PROPERTY_DEFAULT => 'OTHER',
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_COMMENT => 'Le type de la notification',
        FFCST::PROPERTY_SAMPLE  => 'OTHER',
    ];
    protected static $PRP_NOTIF_READ = [
        FFCST::PROPERTY_PRIVATE => 'notif_read',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BOOLEAN,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_COMMENT => 'La notification a-t-elle été lue ?',
        FFCST::PROPERTY_SAMPLE  => false,
    ];
    protected static $PRP_NOTIF_READ_TS = [
        FFCST::PROPERTY_PRIVATE => 'notif_read_ts',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'La date de lecture',
        FFCST::PROPERTY_SAMPLE  => '2020-04-01 14:00:00',
    ];
    protected static $PRP_USER_ID = [
        FFCST::PROPERTY_PRIVATE => 'user_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_FK],
        FFCST::PROPERTY_COMMENT => 'L\'utilisateur ayant lu la notification',
        FFCST::PROPERTY_SAMPLE  => 123,
        FFCST::PROPERTY_FK      => ['user' =>
            [
                'model' => 'FreeSSO::Model::User',
                'field' => 'user_id',
                'type'  => \FreeFW\Model\Query::JOIN_LEFT
            ]
        ]
    ];

    /**
     * get properties
     *
     * @return array[]
     */
    public static function getProperties()
    {
        return [
            'notif_id'          => self::$PRP_NOTIF_ID,
            'brk_id'            => self::$PRP_BRK_ID,
            'notif_text'        => self::$PRP_NOTIF_TEXT,
            'notif_subject'     => self::$PRP_NOTIF_SUBJECT,
            'notif_object_name' => self::$PRP_NOTIF_OBJECT_NAME,
            'notif_object_id'   => self::$PRP_NOTIF_OBJECT_ID,
            'notif_code'        => self::$PRP_NOTIF_CODE,
            'notif_ts'          => self::$PRP_NOTIF_TS,
            'notif_type'        => self::$PRP_NOTIF_TYPE,
            'notif_read'        => self::$PRP_NOTIF_READ,
            'notif_read_ts'     => self::$PRP_NOTIF_READ_TS,
            'user_id'           => self::$PRP_USER_ID
        ];
    }

    /**
     * Set object source
     *
     * @return string
     */
    public static function getSource()
    {
        return 'sys_notification';
    }

    /**
     * Retourne une explication de la table
     */
    public static function getSourceComments()
    {
        return 'Gestion des notifications, multi objets.';
    }
}
