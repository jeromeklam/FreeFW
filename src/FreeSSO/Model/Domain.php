<?php
/**
 * ... ...
 * @author jeromeklam
 */
namespace FreeSSO\Model;

/**
 * Classe : Domains
 * @author : jeromeklam
 */
class Domain extends \FreeFW\Model\AbstractPDOStorage
{

    /**
     * dom_id
     * @var Bigint
     */
    protected $dom_id = null;
    const DESC_DOM_ID = array(
        'name'   => 'dom_id',
        'column' => 'dom_id',
        'field'  => 'dom_id',
        'type'   => \FreeFW\Constants::TYPE_BIGINT,
        'camel'  => 'DomId',
        'snake'  => 'dom_id',
        'getter' => 'getDomId',
        'setter' => 'setDomId',
        'search' => false,
        'key'    => true,
        'uniq'   => true
    );
    /**
     * dom_key
     * @var String
     */
    protected $dom_key = null;
    const DESC_DOM_KEY = array(
        'name'     => 'dom_key',
        'column'   => 'dom_key',
        'field'    => 'dom_key',
        'type'     => \FreeFW\Constants::TYPE_MD5,
        'camel'    => 'DomKey',
        'snake'    => 'dom_key',
        'getter'   => 'getDomKey',
        'setter'   => 'setDomKey',
        'required' => true,
        'uniq'     => true,
        'search'   => true
    );
    /**
     * dom_name
     * @var String
     */
    protected $dom_name = null;
    const DESC_DOM_NAME = array(
        'name'     => 'dom_name',
        'column'   => 'dom_name',
        'field'    => 'dom_name',
        'type'     => \FreeFW\Constants::TYPE_STRING,
        'camel'    => 'DomName',
        'snake'    => 'dom_name',
        'getter'   => 'getDomName',
        'setter'   => 'setDomName',
        'required' => true,
        'uniq'     => true,
        'search'   => true
    );
    /**
     * dom_concurrent_user
     * @var String
     */
    protected $dom_concurrent_user = null;
    const DESC_DOM_CONCURRENT_USER = array(
        'name'   => 'dom_concurrent_user',
        'column' => 'dom_concurrent_user',
        'field'  => 'dom_concurrent_user',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'DomConcurrentUser',
        'snake'  => 'dom_concurrent_user',
        'getter' => 'getDomConcurrentUser',
        'setter' => 'setDomConcurrentUser',
        'search' => false
    );
    /**
     * dom_maintain_seconds
     * @var String
     */
    protected $dom_maintain_seconds = null;
    const DESC_DOM_MAINTAIN_SECONDS = array(
        'name'   => 'dom_maintain_seconds',
        'column' => 'dom_maintain_seconds',
        'field'  => 'dom_maintain_seconds',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'DomMaintainSeconds',
        'snake'  => 'dom_maintain_seconds',
        'getter' => 'getDomMaintainSeconds',
        'setter' => 'setDomMaintainSeconds',
        'search' => false
    );
    /**
     * dom_session_minutes
     * @var String
     */
    protected $dom_session_minutes = null;
    const DESC_DOM_SESSION_MINUTES = array(
        'name'   => 'dom_session_minutes',
        'column' => 'dom_session_minutes',
        'field'  => 'dom_session_minutes',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'DomSessionMinutes',
        'snake'  => 'dom_session_minutes',
        'getter' => 'getDomSessionMinutes',
        'setter' => 'setDomSessionMinutes',
        'search' => false
    );
    /**
     * dom_sites
     * @var String
     */
    protected $dom_sites = null;
    const DESC_DOM_SITES = array(
        'name'   => 'dom_sites',
        'column' => 'dom_sites',
        'field'  => 'dom_sites',
        'type'   => \FreeFW\Constants::TYPE_TEXT,
        'camel'  => 'DomSites',
        'snake'  => 'dom_sites',
        'getter' => 'getDomSites',
        'setter' => 'setDomSites',
        'search' => false
    );
    /**
     * Source
     * @var String
     */
    protected static $source = 'FreeFW_domains';

    /**
     * Retourne la source
     *
     * @return string
     */
    public static function getSource()
    {
        return self::$source;
    }

    /**
     * Retourne le descriptif des colonnes par nom en db
     *
     * @return array
     */
    public static function getColumnDescByName()
    {
        return array(
            'dom_id'               => self::DESC_DOM_ID,
            'dom_key'              => self::DESC_DOM_KEY,
            'dom_name'             => self::DESC_DOM_NAME,
            'dom_concurrent_user'  => self::DESC_DOM_CONCURRENT_USER,
            'dom_maintain_seconds' => self::DESC_DOM_MAINTAIN_SECONDS,
            'dom_session_minutes'  => self::DESC_DOM_SESSION_MINUTES,
            'dom_sites'            => self::DESC_DOM_SITES
        );
    }

    /**
     * Retourne le descriptif des colonnes par nom de propriété
     *
     * @return array
     */
    public static function getColumnDescByField()
    {
        return array(
            'dom_id'               => self::DESC_DOM_ID,
            'dom_key'              => self::DESC_DOM_KEY,
            'dom_name'             => self::DESC_DOM_NAME,
            'dom_concurrent_user'  => self::DESC_DOM_CONCURRENT_USER,
            'dom_maintain_seconds' => self::DESC_DOM_MAINTAIN_SECONDS,
            'dom_session_minutes'  => self::DESC_DOM_SESSION_MINUTES,
            'dom_sites'            => self::DESC_DOM_SITES
        );
    }

    /**
     * Setter dom_id
     *
     * @param bigint $p_value
     *
     * @return \FreeSSO\Model\Domains
     */
    public function setDomId($p_value)
    {
        $this->dom_id = $p_value;
        return $this;
    }

    /**
     * Getter dom_id
     *
     * @return bigint
     */
    public function getDomId()
    {
        return $this->dom_id;
    }

    /**
     * Setter dom_key
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Domains
     */
    public function setDomKey($p_value)
    {
        $this->dom_key = $p_value;
        return $this;
    }

    /**
     * Getter dom_key
     *
     * @return string
     */
    public function getDomKey()
    {
        return $this->dom_key;
    }

    /**
     * Setter dom_name
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Domains
     */
    public function setDomName($p_value)
    {
        $this->dom_name = $p_value;
        return $this;
    }

    /**
     * Getter dom_name
     *
     * @return string
     */
    public function getDomName()
    {
        return $this->dom_name;
    }

    /**
     * Setter dom_concurrent_user
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Domains
     */
    public function setDomConcurrentUser($p_value)
    {
        $this->dom_concurrent_user = $p_value;
        return $this;
    }

    /**
     * Getter dom_concurrent_user
     *
     * @return string
     */
    public function getDomConcurrentUser()
    {
        return $this->dom_concurrent_user;
    }

    /**
     * Setter dom_maintain_seconds
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Domains
     */
    public function setDomMaintainSeconds($p_value)
    {
        $this->dom_maintain_seconds = $p_value;
        return $this;
    }

    /**
     * Getter dom_maintain_seconds
     *
     * @return string
     */
    public function getDomMaintainSeconds()
    {
        return $this->dom_maintain_seconds;
    }

    /**
     * Setter dom_session_minutes
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Domains
     */
    public function setDomSessionMinutes($p_value)
    {
        $this->dom_session_minutes = $p_value;
        return $this;
    }

    /**
     * Getter dom_session_minutes
     *
     * @return string
     */
    public function getDomSessionMinutes()
    {
        return $this->dom_session_minutes;
    }

    /**
     * Setter dom_sites
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Domains
     */
    public function setDomSites($p_value)
    {
        $this->dom_sites = $p_value;
        return $this;
    }

    /**
     * Getter dom_sites
     *
     * @return string
     */
    public function getDomSites()
    {
        return $this->dom_sites;
    }

    /**
     * Retourne l'identifiant
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->dom_id;
    }

    /**
     * Retourne le libellé
     *
     * @return string
     */
    public function getDisplay()
    {
        return $this->dom_name;
    }

    /**
     * Retourne la liste des colonnes
     *
     * @return array
     */
    public static function columnMap()
    {
        return array(
            'dom_id'               => 'dom_id',
            'dom_key'              => 'dom_key',
            'dom_name'             => 'dom_name',
            'dom_concurrent_user'  => 'dom_concurrent_user',
            'dom_maintain_seconds' => 'dom_maintain_seconds',
            'dom_session_minutes'  => 'dom_session_minutes',
            'dom_sites'            => 'dom_sites'
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
            'dom_id'
        );
    }

    /**
     * Retourne les colonnes disponibles en fulltext
     *
     * @return array
     */
    public static function columnFulltext()
    {
        return array(
            'dom_key', 'dom_name'
        );
    }
}
