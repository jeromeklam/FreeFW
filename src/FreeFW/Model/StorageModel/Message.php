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
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_PK],
        FFCST::PROPERTY_TITLE   => 'Id.',
        FFCST::PROPERTY_COMMENT => 'Identifiant du message',
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
    protected static $PRP_MSG_OBJECT_NAME = [
        FFCST::PROPERTY_PRIVATE => 'msg_object_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Objet',
        FFCST::PROPERTY_COMMENT => 'Nom de l\'objet émétteur',
        FFCST::PROPERTY_SAMPLE  => 'FreeSSO_User',
        FFCST::PROPERTY_MAX     => 80,
    ];
    protected static $PRP_MSG_OBJECT_ID = [
        FFCST::PROPERTY_PRIVATE => 'msg_object_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Id objet',
        FFCST::PROPERTY_COMMENT => 'Identifiant de l\'objet émétteur',
        FFCST::PROPERTY_SAMPLE  => '123',
        FFCST::PROPERTY_MAX     => 20,
    ];
    protected static $PRP_LANG_ID = [
        FFCST::PROPERTY_PRIVATE => 'lang_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_FK],
        FFCST::PROPERTY_TITLE   => 'Langue',
        FFCST::PROPERTY_COMMENT => 'Identifiant de la langue du message',
        FFCST::PROPERTY_SAMPLE  => 123,
        FFCST::PROPERTY_FK      => ['lang' =>
            [
                FFCST::FOREIGN_MODEL => 'FreeFW::Model::Lang',
                FFCST::FOREIGN_FIELD => 'lang_id',
                FFCST::FOREIGN_TYPE  => \FreeFW\Model\Query::JOIN_LEFT
            ]
        ]
    ];
    protected static $PRP_MSG_TS = [
        FFCST::PROPERTY_PRIVATE => 'msg_ts',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_DEFAULT => FFCST::DEFAULT_NOW,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Créé le',
        FFCST::PROPERTY_COMMENT => 'Date heure d\'expédition du message',
        FFCST::PROPERTY_SAMPLE  => '2020-04-01 18:34:00',
    ];
    protected static $PRP_MSG_STATUS = [
        FFCST::PROPERTY_PRIVATE => 'msg_status',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_SELECT,
        FFCST::PROPERTY_ENUM    => ['WAITING','PENDING','OK','ERROR'],
        FFCST::PROPERTY_DEFAULT => ['WAITING'],
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_TITLE   => 'Status',
        FFCST::PROPERTY_COMMENT => 'Status du message',
        FFCST::PROPERTY_SAMPLE  => 'PENDING',
    ];
    protected static $PRP_MSG_TYPE = [
        FFCST::PROPERTY_PRIVATE => 'msg_type',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_SELECT,
        FFCST::PROPERTY_ENUM    => ['EMAIL','SMS'],
        FFCST::PROPERTY_DEFAULT => ['EMAIL'],
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_TITLE   => 'Type',
        FFCST::PROPERTY_COMMENT => 'Type du message',
        FFCST::PROPERTY_SAMPLE  => 'EMAIL',
    ];
    protected static $PRP_MSG_DEST = [
        FFCST::PROPERTY_PRIVATE => 'msg_dest',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_JSON,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Destinataire(s)',
        FFCST::PROPERTY_COMMENT => 'Destinataires du message au format json',
        FFCST::PROPERTY_SAMPLE  => '[{"address":"test@free.fr"}]',
    ];
    protected static $PRP_MSG_CC = [
        FFCST::PROPERTY_PRIVATE => 'msg_cc',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_JSON,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'CC(s)',
        FFCST::PROPERTY_COMMENT => 'CC du message au format json',
        FFCST::PROPERTY_SAMPLE  => '[{"address":"test@free.fr"}]',
    ];
    protected static $PRP_MSG_BCC = [
        FFCST::PROPERTY_PRIVATE => 'msg_bcc',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_JSON,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'BCC(s)',
        FFCST::PROPERTY_COMMENT => 'CC cachés du message au format json',
        FFCST::PROPERTY_SAMPLE  => '[{"address":"test@free.fr"}]',
    ];
    protected static $PRP_MSG_SUBJECT = [
        FFCST::PROPERTY_PRIVATE => 'msg_subject',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Sujet',
        FFCST::PROPERTY_COMMENT => 'Sujet du message, sans html',
        FFCST::PROPERTY_SAMPLE  => 'Mon sujet',
    ];
    protected static $PRP_MSG_BODY = [
        FFCST::PROPERTY_PRIVATE => 'msg_body',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_HTML,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Contenu',
        FFCST::PROPERTY_COMMENT => 'Le corps du message, html recommandé',
        FFCST::PROPERTY_SAMPLE  => '<p>Mon message</p>',
    ];
    protected static $PRP_MSG_PJ1 = [
        FFCST::PROPERTY_PRIVATE => 'msg_pj1',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'PJ 1',
        FFCST::PROPERTY_COMMENT => 'Fichier en pj 1',
        FFCST::PROPERTY_SAMPLE  => '/tmp/fic001.png',
        FFCST::PROPERTY_MAX     => 255,
    ];
    protected static $PRP_MSG_PJ2 = [
        FFCST::PROPERTY_PRIVATE => 'msg_pj2',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'PJ 2',
        FFCST::PROPERTY_COMMENT => 'Fichier en pj 2',
        FFCST::PROPERTY_SAMPLE  => '/tmp/fic001.png',
        FFCST::PROPERTY_MAX     => 255,
    ];
    protected static $PRP_MSG_PJ3 = [
        FFCST::PROPERTY_PRIVATE => 'msg_pj3',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'PJ 3',
        FFCST::PROPERTY_COMMENT => 'Fichier en pj 3',
        FFCST::PROPERTY_SAMPLE  => '/tmp/fic001.png',
        FFCST::PROPERTY_MAX     => 255,
    ];
    protected static $PRP_MSG_PJ4 = [
        FFCST::PROPERTY_PRIVATE => 'msg_pj4',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'PJ 4',
        FFCST::PROPERTY_COMMENT => 'Fichier en pj 4',
        FFCST::PROPERTY_SAMPLE  => '/tmp/fic001.png',
        FFCST::PROPERTY_MAX     => 255,
    ];
    protected static $PRP_MSG_SEND_TS = [
        FFCST::PROPERTY_PRIVATE => 'msg_send_ts',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Expédié le',
        FFCST::PROPERTY_COMMENT => 'Date heure d\'expédition',
        FFCST::PROPERTY_SAMPLE  => '2020:04:01 13:00:00',
    ];
    protected static $PRP_MSG_SEND_ERROR = [
        FFCST::PROPERTY_PRIVATE => 'msg_send_error',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Erreur',
        FFCST::PROPERTY_COMMENT => 'Message d\'erreur retourné par le serveur',
        FFCST::PROPERTY_SAMPLE  => 'Error ...',
    ];
    protected static $PRP_MSG_FROM = [
        FFCST::PROPERTY_PRIVATE => 'msg_from',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_JSON,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'From',
        FFCST::PROPERTY_COMMENT => 'From du message',
        FFCST::PROPERTY_SAMPLE  => '{"address":"support@test.fr","name":"Support"}',
    ];
    protected static $PRP_MSG_REPLY_TO = [
        FFCST::PROPERTY_PRIVATE => 'msg_reply_to',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_TEXT,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'From name',
        FFCST::PROPERTY_COMMENT => 'Informations de retour',
        FFCST::PROPERTY_SAMPLE  => '{"address":"support@test.fr","name":"Support"}',
    ];
    protected static $PRP_MSG_PJ1_NAME = [
        FFCST::PROPERTY_PRIVATE => 'msg_pj1_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Nom PJ 1',
        FFCST::PROPERTY_COMMENT => 'Nom de la pj 1',
        FFCST::PROPERTY_SAMPLE  => 'exemple.png',
        FFCST::PROPERTY_MAX     => 80,
    ];
    protected static $PRP_MSG_PJ2_NAME = [
        FFCST::PROPERTY_PRIVATE => 'msg_pj2_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Nom PJ 2',
        FFCST::PROPERTY_COMMENT => 'Nom de la pj 2',
        FFCST::PROPERTY_SAMPLE  => 'exemple.png',
        FFCST::PROPERTY_MAX     => 80,
    ];
    protected static $PRP_MSG_PJ3_NAME = [
        FFCST::PROPERTY_PRIVATE => 'msg_pj3_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Nom PJ 3',
        FFCST::PROPERTY_COMMENT => 'Nom de la pj 3',
        FFCST::PROPERTY_SAMPLE  => 'exemple.png',
        FFCST::PROPERTY_MAX     => 80,
    ];
    protected static $PRP_MSG_PJ4_NAME = [
        FFCST::PROPERTY_PRIVATE => 'msg_pj4_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_TITLE   => 'Nom PJ 4',
        FFCST::PROPERTY_COMMENT => 'Nom de la pj 4',
        FFCST::PROPERTY_SAMPLE  => 'exemple.png',
        FFCST::PROPERTY_MAX     => 80,
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
            'msg_from'        => self::$PRP_MSG_FROM,
            'msg_reply_to'    => self::$PRP_MSG_REPLY_TO,
            'msg_pj1_name'    => self::$PRP_MSG_PJ1_NAME,
            'msg_pj2_name'    => self::$PRP_MSG_PJ2_NAME,
            'msg_pj3_name'    => self::$PRP_MSG_PJ3_NAME,
            'msg_pj4_name'    => self::$PRP_MSG_PJ4_NAME
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

    /**
     * Retourne une explication de la table
     */
    public static function getSourceTitle()
    {
        return 'Messages';
    }

    /**
     * Retourne une explication de la table
     */
    public static function getSourceComments()
    {
        return 'Gestion des messages expédiés';
    }

    /**
     * Get autocomplete field
     *
     * @return string
     */
    public static function getAutocompleteField()
    {
        return 'msg_subject';
    }
}
