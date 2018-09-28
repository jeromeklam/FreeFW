<?php
namespace FreeSSO\Model;

/**
 * Requête de retour des contrats
 * @author jeromeklam
 */
class GroupUserGrid extends \FreeFW\Model\AbstractQueryStorage
{

    /**
     * Identifiant user groupe
     * @var number
     */
    protected $gru_id = null;

    /**
     * Identifiant du groupe
     * @var number
     */
    protected $grp_id = null;

    /**
     * Identifiant de l'utilisateur
     * @var number
     */
    protected $user_id = null;

    /**
     * User email
     * @var string
     */
    protected $user_email = null;

    /**
     * User login
     * @var string
     */
    protected $user_login = null;

    /**
     * Identifiant utilisateur pour ce groupe
     * @var string
     */
    protected $gru_key = null;

    /**
     * Source
     * @var String
     */
    protected static $source = 'FreeFW_groups_users';

    /**
     * Retourne la source
     *
     * @return String
     */
    public static function getSource()
    {
        return self::$source;
    }

    /**
     * Requête , vue, ... ?
     *
     * @return boolean
     */
    public static function isQuery()
    {
        return true;
    }

    /**
     * Retourne les colonnes de jointure
     *
     * @return Array
     */
    public static function columnJoin()
    {
        return array(
            array(
                'from' => 'FreeFW.Sso::GroupUser.grp_id',
                'to'   => 'FreeFW.Sso::Group.grp_id'
            ),
            array(
                'from' => 'FreeFW.Sso::GroupUser.user_id',
                'to'   => 'FreeFW.Sso::User.user_id'
            )
        );
    }

    /**
     * Retourne la liste des colonnes
     *
     * @return Array
     */
    public static function columnMap()
    {
        return array(
            'FreeFW.Sso::GroupUser.gru_id'  => 'gru_id',
            'FreeFW.Sso::GroupUser.grp_id'  => 'grp_id',
            'FreeFW.Sso::GroupUser.user_id' => 'user_id',
            'FreeFW.Sso::User.user_login'   => 'user_login',
            'FreeFW.Sso::User.user_email'   => 'user_email',
            'FreeFW.Sso::GroupUser.gru_key' => 'gru_key'
        );
    }

    /**
     * Retourne la liste des colonnes identifiantes
     *
     * @return Array      // Tableau de propertyName
     */
    public static function columnId()
    {
        return array(
            'gru_id'
        );
    }

    /**
     * Retourne les colonnes disponibles en fulltext
     *
     * @return Array
     */
    public static function columnFulltext()
    {
        return array(
            'gru_key'
        );
    }
}
