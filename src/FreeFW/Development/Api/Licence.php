<?php
namespace FreeFW\Development\Api;

/**
 *
 * @author jerome.klam
 *
 */
class Licence
{

    /**
     * Type de licence
     * @var string
     */
    protected $type = null;

    /**
     * Url de la licence
     * @var string
     */
    protected $url = null;

    /**
     * Retourne une licence en fonction d'une configuration
     *
     * @param array $p_config
     *
     * @return \FreeFW\Development\Api\Licence
     */
    public static function getFromConfig($p_config)
    {
        $licence = new static();
        if (is_array($p_config)) {
            if (array_key_exists('type', $p_config)) {
                $licence->setType($p_config['type']);
            }
            if (array_key_exists('url', $p_config)) {
                $licence->setUrl($p_config['url']);
            }
        }
        return $licence;
    }

    /**
     * Affectation du type de licence
     *
     * @param string $p_type
     *
     * @return \FreeFW\Development\Api\Licence
     */
    public function setType($p_type)
    {
        $this->type = $p_type;
        return $this;
    }

    /**
     * Récupération du type de licence
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Affectation de l'url
     *
     * @param string $p_url
     *
     * @return \FreeFW\Development\Api\Licence
     */
    public function setUrl($p_url)
    {
        $this->url = $p_url;
        return $this;
    }

    /**
     * Récupération de l'url
     *
     * @return string
     */
    public function geturl()
    {
        return $this->url;
    }

    /**
     * Retourne la licence au format swagger
     *
     * @param number $p_version
     *
     * @return \stdClass
     */
    public function getForSwagger($p_version = 3)
    {
        $licence       = new \stdClass();
        $licence->name = $this->getType();
        $licence->url  = $this->geturl();
        return $licence;
    }
}
