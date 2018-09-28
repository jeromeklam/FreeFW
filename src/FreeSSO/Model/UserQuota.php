<?php
namespace FreeSSO\Model;

/**
 *
 * @author jerome.klam
 *
 */
class UserQuota extends \FreeFW\Model\AbstractNoStorage
{

    /**
     * Types de SMS
     * @var string
     */
    const TYPE_SMS = 'SMS';

    /**
     * User id
     * @var string
     */
    protected $user_id = null;

    /**
     * Type de quota
     * @var string
     */
    protected $type = self::TYPE_SMS;

    /**
     * Type
     * @var number
     */
    protected $quota = 0;

    /**
     * Retourne le userid
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Affectation du iserId
     *
     * @param string $p_userId
     *
     * @return \FreeSSO\Model\UserAccount
     */
    public function setUserId($p_userId)
    {
        $this->user_id = $p_userId;
        return $this;
    }

    /**
     * Retourne le type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Affectation du type
     *
     * @param string $type
     *
     * @return UserAccount
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Retourne le quota
     *
     * @return string
     */
    public function getQuota()
    {
        return $this->quota;
    }

    /**
     * Affectation du quota
     *
     * @param number $p_quota
     *
     * @return UserQuota
     */
    public function setQuota($p_quota)
    {
        $this->quota = $p_quota;
        return $this;
    }

    /**
     * Retourne la liste des colonnes
     *
     * @return array
     */
    public static function columnMap()
    {
        return array(
            'user_id' => 'user_id',
            'type'    => 'type',
            'quota'   => 'quota'
        );
    }

    /**
     * Retourne la liste des colonnes identifiantes
     *
     * @return array      // Tableau de propertyName
     */
    public static function columnId()
    {
        return array(
            'user_id', 'type'
        );
    }
}
