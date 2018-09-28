<?php
namespace FreeSSO\Model;

/**
 *
 * @author jerome.klam
 *
 */
class UserAccount extends \FreeFW\Model\AbstractNoStorage
{

    /**
     * Account types : TYPE_*
     * @var string
     */
    const TYPE_SMS = 'sms';

    /**
     * Providers
     * @var string
     */
    const PROVIDER_OVH = 'ovh';

    /**
     * Identifiant de l'utilisateur
     * @var string
     */
    protected $user_id = null;

    /**
     * Type
     * @var string
     */
    protected $type = null;

    /**
     * Provider : OVH, ...
     * @var string
     */
    protected $provider = null;

    /**
     * Clef 1
     * @var String
     */
    protected $key1 = null;

    /**
     * Clef 2
     * @var string
     */
    protected $key2 = null;

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
     * Retourne le provider
     *
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Affectation du provider
     *
     * @param string $provider
     *
     * @return UserAccount
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
        return $this;
    }

    /**
     * Retourne la clef 1
     *
     * @return string
     */
    public function getKey1()
    {
        return $this->key1;
    }

    /**
     * Affectation de la clef 1
     *
     * @param string $key1
     *
     * @return UserAccount
     */
    public function setKey1($key1)
    {
        $this->key1 = $key1;
        return $this;
    }

    /**
     * Retourne la clef 2
     *
     * @return string
     */
    public function getKey2()
    {
        return $this->key2;
    }

    /**
     * Affectation de la clef 2
     *
     * @param string $key2
     *
     * @return UserAccount
     */
    public function setKey2($key2)
    {
        $this->key2 = $key2;
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
            'user_id'  => 'user_id',
            'type'     => 'type',
            'provider' => 'provider',
            'key1'     => 'key1',
            'key2'     => 'key2'
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
