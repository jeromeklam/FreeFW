<?php
namespace FreeFW\Model\StorageModel;

use \FreeFW\Constants as FFCST;

/**
 * Email
 *
 * @author jeromeklam
 */
abstract class Email extends \FreeFW\Core\StorageModel
{

    /**
     * Field properties as static arrays
     * @var array
     */
    protected static $PRP_EMAIL_ID = [
        FFCST::PROPERTY_PRIVATE => 'email_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_PK],
        FFCST::PROPERTY_COMMENT => 'Identifiant de l\'email',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_BRK_ID = [
        FFCST::PROPERTY_PRIVATE => 'brk_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_BROKER],
        FFCST::PROPERTY_COMMENT => 'Identifiant du broker, pour restriction',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_LANG_ID = [
        FFCST::PROPERTY_PRIVATE => 'lang_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_FK],
        FFCST::PROPERTY_COMMENT => 'Identifiant de la langue',
        FFCST::PROPERTY_SAMPLE  => 123,
        FFCST::PROPERTY_DEFAULT => FFCST::DEFAULT_LANG,
        FFCST::PROPERTY_FK      => ['lang' =>
            [
                FFCST::FOREIGN_MODEL => 'FreeFW::Model::Lang',
                FFCST::FOREIGN_FIELD => 'lang_id',
                FFCST::FOREIGN_TYPE  => \FreeFW\Model\Query::JOIN_LEFT
            ]
        ]
    ];
    protected static $PRP_EMAIL_CODE = [
        FFCST::PROPERTY_PRIVATE => 'email_code',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_COMMENT => 'Code interne de l\'email',
        FFCST::PROPERTY_SAMPLE  => 'PASSWORD',
        FFCST::PROPERTY_MAX     => 80,
    ];
    protected static $PRP_EMAIL_SUBJECT = [
        FFCST::PROPERTY_PRIVATE => 'email_subject',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
        FFCST::PROPERTY_COMMENT => 'Le sujet de l\'email, sans html',
        FFCST::PROPERTY_SAMPLE  => 'Expédition email',
        FFCST::PROPERTY_MAX     => 255,
    ];
    protected static $PRP_EMAIL_BODY = [
        FFCST::PROPERTY_PRIVATE => 'email_body',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_HTML,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Le contenu de l\'email, de préférence en html',
        FFCST::PROPERTY_SAMPLE  => '<p>Corps du mail</p>',
    ];
    protected static $PRP_EMAIL_FROM = [
        FFCST::PROPERTY_PRIVATE => 'email_from',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Email de l\'expéditeur',
        FFCST::PROPERTY_SAMPLE  => 'support@test.fr',
        FFCST::PROPERTY_MAX     => 255,
    ];
    protected static $PRP_EMAIL_FROM_NAME = [
        FFCST::PROPERTY_PRIVATE => 'email_from_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Nom de l\'expéditeur',
        FFCST::PROPERTY_SAMPLE  => 'Support',
        FFCST::PROPERTY_MAX     => 255,
    ];
    protected static $PRP_EMAIL_REPLY_TO = [
        FFCST::PROPERTY_PRIVATE => 'email_reply_to',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Email de réponse',
        FFCST::PROPERTY_SAMPLE  => 'support@test.fr',
        FFCST::PROPERTY_MAX     => 255,
    ];

    /**
     * get properties
     *
     * @return array[]
     */
    public static function getProperties()
    {
        return [
            'email_id'        => self::$PRP_EMAIL_ID,
            'brk_id'          => self::$PRP_BRK_ID,
            'lang_id'         => self::$PRP_LANG_ID,
            'email_code'      => self::$PRP_EMAIL_CODE,
            'email_subject'   => self::$PRP_EMAIL_SUBJECT,
            'email_body'      => self::$PRP_EMAIL_BODY,
            'email_from'      => self::$PRP_EMAIL_FROM,
            'email_from_name' => self::$PRP_EMAIL_FROM_NAME,
            'email_reply_to'  => self::$PRP_EMAIL_REPLY_TO
        ];
    }

    /**
     * Set object source
     *
     * @return string
     */
    public static function getSource()
    {
        return 'sys_email';
    }

    /**
     * Retourne une explication de la table
     */
    public static function getSourceComments()
    {
        return 'Gestion des modèles de mail';
    }

    /**
     * Get autocomplete field
     *
     * @return string
     */
    public static function getAutocompleteField()
    {
        return 'email_subject';
    }

    /**
     * Composed index
     *
     * @return string[][]|number[][]
     */
    public static function getUniqIndexes()
    {
        return [
            'code' => [
                FFCST::INDEX_FIELDS => ['brk_id', 'lang_id', 'email_code'],
                FFCST::INDEX_EXISTS => \FreeFW\Constants::ERROR_EMAIL_CODE_EXISTS
            ],
        ];
    }

    /**
     * Get One To many relationShips
     *
     * @return array
     */
    public function getRelationships()
    {
        return [
            'versions' => [
                FFCST::REL_MODEL   => 'FreeFW::Model::EmailLang',
                FFCST::REL_FIELD   => 'email_id',
                FFCST::REL_TYPE    => \FreeFW\Model\Query::JOIN_LEFT,
                FFCST::REL_COMMENT => 'Les versions de l\'email',
                FFCST::REL_REMOVE  => FFCST::REL_REMOVE_CASCADE
            ]
        ];
    }
}
