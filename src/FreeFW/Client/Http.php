<?php
/**
 * Client Http avec curl
 *
 * @author jeromeklam
 * @package Http
 */
namespace FreeFW\Client;

/**
 * Classe d'interface Http (on utilise curl)
 * @author jeromeklam
 */
class Http
{

    /**
     * Comportements
     */
    use \FreeFW\Behaviour\LoggerAwareTrait;
    use \FreeFW\Behaviour\DI;

    /**
     * url
     * @var unknown
     */
    protected $url = null;

    /**
     * Params
     * @var mixed
     */
    protected $params = null;

    /**
     * Headers
     * @var array
     */
    protected $headers = [];

    /**
     * Affectation de l'url
     *
     * @param string $p_url
     *
     * @return \FreeFW\Client\Http
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
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Affectation des paramètres
     *
     * @param mixed $p_params
     *
     * @return \FreeFW\Client\Http
     */
    public function setParams($p_params)
    {
        $this->params = $p_params;
        return $this;
    }

    /**
     * Retourne les paramètres
     *
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Ajout d'une en-tête
     *
     * @param string $p_name
     * @param string $p_value
     *
     * @return \FreeFW\Client\Http
     */
    public function addHeader($p_name, $p_value)
    {
        $this->headers[$p_name] = $p_value;
        return $this;
    }

    /**
     * Get content
     *
     * @return mixed
     */
    public function getContent()
    {
        $content = false;
        $url     = $this->getUrl();
        $params  = $this->getParams();
        $ch      = curl_init($url);
        if (count($this->headers) > 0) {
            $headers = [];
            foreach ($this->headers as $key=>$value) {
                $headers[] = $key. ': ' . $value;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        } else {
            curl_setopt($ch, CURLOPT_HEADER, 0);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        if (is_array($params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        $curl_response = curl_exec($ch);
        //finish off the session
        if ($curl_response !== false) {
            $content = $curl_response;
        } else {
            trigger_error(curl_error($ch));
        }
        curl_close($ch);
        return $content;
    }
}
