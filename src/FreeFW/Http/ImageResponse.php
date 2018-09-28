<?php
namespace FreeFW\Http;

/**
 * Response au format image
 */
class ImageResponse extends \FreeFW\Http\MimeResponse
{

    /**
     * Génération de la réponse
     *
     * @return void;
     */
    public function render()
    {
        if (is_file($this->getContent())) {
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            $type = $this->getMimeType();
            if ($type !== false) {
                header('Content-type: ' . $type);
            } else {
                header('Content-type: image/jpeg');
            }
            readfile($this->getContent());
        } else {
            // @todo
        }
    }
}
