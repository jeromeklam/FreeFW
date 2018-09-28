<?php
namespace FreeFW\Development\Api;

/**
 *
 * @author jerome.klam
 *
 */
class Contact
{

    /**
     * Types de contact
     * @var string
     */
    const TYPE_MAIN  = 'main';
    const TYPE_OTHER = 'other';

    /**
     * Type de contact
     * @var string
     */
    protected $type = self::TYPE_MAIN;

    /**
     * Nom de la personne
     * @var string
     */
    protected $name = null;

    /**
     * Email
     * @var string
     */
    protected $email = null;

    /**
     * Url
     * @var string
     */
    protected $url = null;

    /**
     * Numéro de téléphone
     * @var string
     */
    protected $phone = null;

    /**
     * Retourne un nouveau contact à partir d'une config
     *
     * @param array $p_config
     *
     * @return \FreeFW\Development\Api\Contact
     */
    public static function getFromConfig($p_config)
    {
        $contact = new static();
        if (is_array($p_config)) {
            if (array_key_exists('name', $p_config)) {
                $contact->setName($p_config['name']);
            }
            if (array_key_exists('email', $p_config)) {
                $contact->setEmail($p_config['email']);
            }
            if (array_key_exists('url', $p_config)) {
                $contact->setUrl($p_config['url']);
            }
            if (array_key_exists('phone', $p_config)) {
                $contact->setPhone($p_config['phone']);
            }
        }
        return $contact;
    }

    /**
     * Affectation du type de contact
     *
     * @param string $p_type
     *
     * @return \FreeFW\Development\Api\Contact
     */
    public function setType($p_type)
    {
        $this->type = $p_type;
        return $this;
    }

    /**
     * Retourne le type de contact
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Contact principal ?
     *
     * @return boolean
     */
    public function isMain()
    {
        if ($this->getType() == self::TYPE_MAIN) {
            return true;
        }
        return false;
    }

    /**
     * Affectation du nom
     *
     * @param string $p_name
     *
     * @return \FreeFW\Development\Api\Contact
     */
    public function setName($p_name)
    {
        $this->name = $p_name;
        return $this;
    }

    /**
     * Récupération du nom
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Affectation de l'email
     *
     * @param string $p_email
     *
     * @return \FreeFW\Development\Api\Contact
     */
    public function setEmail($p_email)
    {
        $this->email = $p_email;
        return $this;
    }

    /**
     * Retourne l'email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Affectation de l'url
     *
     * @param string $p_url
     *
     * @return \FreeFW\Development\Api\Contact
     */
    public function setUrl($p_url)
    {
        $this->url = $p_url;
        return $this;
    }

    /**
     * Retourne l'url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Affectation du numéro de téléphone
     *
     * @param string $p_phone
     *
     * @return \FreeFW\Development\Api\Contact
     */
    public function setPhone($p_phone)
    {
        $this->phone = $p_phone;
        return $this;
    }

    /**
     * Récupération du numéro de téléphone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Retourne le contact au format Swagger
     *
     * @param number $p_version
     *
     * @return \stdClass
     */
    public function getForSwagger($p_version = 3)
    {
        $contact        = new \stdClass();
        $contact->name  = $this->getName();
        $contact->url   = $this->getUrl();
        $contact->email = $this->getEmail();
        return $contact;
    }
}
