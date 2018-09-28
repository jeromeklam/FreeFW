<?php
namespace FreeFW\Http\JsonApi;

/**
 * Response au format Json API v 1.0
 */
class Response extends \FreeFW\Http\Response
{

    /**
     * Specific metas
     * @var array
     */
    protected $metas = array();

    /**
     * Réponse de type JSON
     *
     * @return boolean
     */
    public function isJson()
    {
        return true;
    }

    /**
     * Ajout d'une meta
     *
     * @param string $p_key
     * @param mixed  $p_value
     *
     * @return \FreeFW\Http\JsonApi\FreeFWResponse
     */
    public function addMeta($p_key, $p_value)
    {
        $this->metas[$p_key] = $p_value;
        return $this;
    }

    /**
     * Retourne le tableau des metas
     *
     * @return array
     */
    public function getMetas()
    {
        return $this->metas;
    }

    /**
     * Génération de la réponse
     *
     * @return void;
     */
    public function render()
    {
        $request = self::getDIRequest();
        $content = $this->getContent();
        if ($content === false) {
            $content = null;
        }
        if ($content === null || (is_object($content) && method_exists($content, '__toJsonApi'))) {
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Content-type: application/json');
            header('HTTP/1.0 ' . $this->getStatusCode());
            $data = null;
            if ($content !== null) {
                $data = $content->__toJsonApi($this->getVersion());
            }
            $result  = array(
                'meta'   => $this->getMetas(),
                'errors' => $this->getErrors(),
                'data'   => $data,
                'links'  => array(
                    'self' => (string)$request->getUri()
                ),
                'jsonapi' => array(
                    'version' => $this->getVersion()
                )
            );
            $jsonContent = json_encode($result);
            if ($jsonContent === false) {
                throw new \Exception('Impossible de convertir le résultat en json api !');
            }
        } else {
            throw new \Exception('Impossible de convertir le résultat en json api, contenu incorrect !');
        }
        echo $jsonContent;
    }
}
