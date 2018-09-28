<?php
namespace FreeFW\Auth\Hmac;

/**
 *
 * @author jeromejklam
 */
class Message
{

    /**
     * Identifiant du client
     *
     * @var number
     */
    protected $id;

    /**
     * Timestamp
     *
     * @var string
     */
    protected $time;

    /**
     * Données
     *
     * @var string
     */
    protected $data;

    /**
     * Hash
     *
     * @var string
     */
    protected $hash;

    /**
     * Lang
     *
     * @var string
     */
    protected $lang;

    /**
     * Constructeur
     *
     * @param string  $p_id
     * @param integer $p_time
     * @param string  $p_hash
     * @param string  $p_lang
     * @param array   $p_data
     */
    public function __construct($p_id, $p_time, $p_hash, $p_lang, $p_data)
    {
        $this->id   = $p_id;
        $this->hash = $p_hash;
        $this->time = $p_time;
        $this->lang = $p_lang;
        $this->data = $p_data;
        if (is_array($this->data)) {
            foreach (array('_api_vers', '_api_type', 'API_ID', 'API_TIME', 'API_HASH', 'API_LANG', '_') as $key) {
                if (array_key_exists($key, $this->data)) {
                    unset($this->data[$key]);
                }
            }
        }
    }

    /**
     * Retourne le Hash du message
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Retourne l'identifiant du client
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Retourne les données
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Données pour le http_build_url
     *
     * @return array
     */
    public function getDataForBuild()
    {
        if (is_array($this->data)) {
            $ret = array_merge(array(), $this->data);
            foreach ($ret as $key => $value) {
                if ($value === null) {
                    $ret[$key] = '';
                }
            }
            return $ret;
        }
        return $this->data;
    }

    /**
     * Retourne le tout sous forme d'url
     *
     * @param array  $a
     * @param number $b
     * @param number $c
     *
     * @return boolean
     */
    public function getDataAsUrl($a = null, $b = 0, $c = 0)
    {
        return http_build_query($this->data);
    }

    /**
     * Retourne le timestamp
     *
     * @return number
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Retourne la langue
     *
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }
}
