<?php
namespace FreeFW\Http;

/**
 * Response au format excel
 */
class PdfResponse extends \FreeFW\Http\MimeResponse
{

    /**
     * Génération de la réponse
     *
     * @return void;
     */
    public function render()
    {
        $mode = 'inline';
        if ($this->getMode() == self::MODE_DOWNLOAD) {
            $mode = 'attachment';
        }
        $name = $this->getName();
        if (is_file($this->getContent())) {
            $path_parts = pathinfo($this->getContent());
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Content-type: application/pdf');
            if ($name === null) {
                $name = $path_parts['basename'];
            }
            header('Content-Disposition: ' . $mode . '; filename="' . $name . '";');
            header('Pragma: no-cache');
            header('Expires: 0');
            readfile($this->getContent());
        } else {
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Content-type: application/pdf');
            if ($name === null) {
                $name = uniqid() . '.pdf';
            }
            header('Content-Disposition: ' . $mode . '; filename="' . $name . '";');
            header('Pragma: no-cache');
            header('Expires: 0');
            die(base64_decode($this->getContent()));
        }
    }
}
