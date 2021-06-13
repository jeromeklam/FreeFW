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
    protected static $PRP_EMAIL_EDI1_ID = [
        FFCST::PROPERTY_PRIVATE => 'email_edi1_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_FK],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => 123,
        FFCST::PROPERTY_FK      => ['edition1' =>
            [
                FFCST::FOREIGN_MODEL => 'FreeFW::Model::Edition',
                FFCST::FOREIGN_FIELD => 'edi_id',
                FFCST::FOREIGN_TYPE  => \FreeFW\Model\Query::JOIN_LEFT,
            ]
        ],
    ];
    protected static $PRP_EMAIL_EDI1_OBJECT = [
        FFCST::PROPERTY_PRIVATE => 'email_edi1_object',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Object de l\'édition',
        FFCST::PROPERTY_SAMPLE  => 'donation',
        FFCST::PROPERTY_MAX     => 80,
    ];
    protected static $PRP_EMAIL_EDI2_ID = [
        FFCST::PROPERTY_PRIVATE => 'email_edi2_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_FK],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => 123,
        FFCST::PROPERTY_FK      => ['edition2' =>
            [
                FFCST::FOREIGN_MODEL => 'FreeFW::Model::Edition',
                FFCST::FOREIGN_FIELD => 'edi_id',
                FFCST::FOREIGN_TYPE  => \FreeFW\Model\Query::JOIN_LEFT,
            ]
        ],
    ];
    protected static $PRP_EMAIL_EDI2_OBJECT = [
        FFCST::PROPERTY_PRIVATE => 'email_edi2_object',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Object de l\'édition',
        FFCST::PROPERTY_SAMPLE  => 'donation',
        FFCST::PROPERTY_MAX     => 80,
    ];
    protected static $PRP_EMAIL_OBJECT_NAME = [
        FFCST::PROPERTY_PRIVATE => 'email_object_name',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Object de l\'email',
        FFCST::PROPERTY_SAMPLE  => 'FreeFW_User',
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
            'email_id'          => self::$PRP_EMAIL_ID,
            'brk_id'            => self::$PRP_BRK_ID,
            'lang_id'           => self::$PRP_LANG_ID,
            'email_code'        => self::$PRP_EMAIL_CODE,
            'email_subject'     => self::$PRP_EMAIL_SUBJECT,
            'email_body'        => self::$PRP_EMAIL_BODY,
            'email_from'        => self::$PRP_EMAIL_FROM,
            'email_from_name'   => self::$PRP_EMAIL_FROM_NAME,
            'email_reply_to'    => self::$PRP_EMAIL_REPLY_TO,
            'email_edi1_id'     => self::$PRP_EMAIL_EDI1_ID,
            'email_edi1_object' => self::$PRP_EMAIL_EDI1_OBJECT,
            'email_edi2_id'     => self::$PRP_EMAIL_EDI2_ID,
            'email_edi2_object' => self::$PRP_EMAIL_EDI2_OBJECT,
            'email_object_name' => self::$PRP_EMAIL_OBJECT_NAME,
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
