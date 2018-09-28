<?php
namespace FreeFW\Http;

/**
 * Response au format json
 */
class FreeFWResponse extends \FreeFW\Http\Response
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
        header('HTTP/1.0 ' . $this->getStatusCode());
        $content = $this->getContent();
        $result  = array(
            'status'   => $this->getStatusCode(),
            'message'  => $this->getMessage(),
            'redirect' => null,
            'errors'   => $this->getErrors(),
            'data'     => $content
        );
        $jsonContent = json_encode($result);
        if ($jsonContent === false) {
            throw new \Exception('Impossible de convertir le résultat en json !');
        }
        echo $jsonContent;
    }
}
