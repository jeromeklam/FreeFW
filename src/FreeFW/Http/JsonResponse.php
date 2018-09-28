<?php
namespace FreeFW\Http;

/**
 * Response au format json
 */
class JsonResponse extends \FreeFW\Http\Response
{

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
     * Génération de la réponse
     *
     * @return void;
     */
    public function render()
    {
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        header('HTTP/1.0 ' . $this->getStatusCode() . ' ' . $this->getMessage());
        $content = $this->getContent();
        if ($content !== null) {
            $json = json_encode($content);
            if ($json === false) {
                throw new \Exception('Impossible de convertir le résultat en json !');
            } else {
                echo $json;
            }
        } else {
            echo json_encode(array($this->getStatusCode() => $this->getMessage()));
        }
    }
}
