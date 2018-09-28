<?php
namespace FreeFW\Http;

/**
 * Response au format excel
 */
class MimeResponse extends \FreeFW\Http\Response
{

    /**
     * Image mimetype
     */
    protected $mime_type = false;

    /**
     * Retourne le type MIME asocié
     *
     * @return string
     */
    protected function getMimeType()
    {
        if ($this->mime_type === false) {
            try {
                $finfo            = finfo_open(FILEINFO_MIME_TYPE); // Retourne le type mime à l'extension mimetype
                $this->mime_type  = finfo_file($finfo, $this->getContent());
                finfo_close($finfo);
            } catch (\Exception $ex) {
                $this->mime_type = false;
            }
        }
        
        return $this->mime_type;
    }

    /**
     * Affectation du mime type
     *
     * @var string $p_type
     *
     * @return \FreeFW\Http\ImageResponse
     */
    public function setMimeType($p_type)
    {
        $this->mime_type = $p_type;
        
        return $this;
    }

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
            header('Content-type: ' . $this->getMimeType());
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
            header('Content-type: ' . $this->getMimeType());
            if ($name === null) {
                $name = uniqid();
            }
            header('Content-Disposition: ' . $mode . '; filename="' . $name . '";');
            header('Pragma: no-cache');
            header('Expires: 0');
            die($this->getContent());
        }
    }
}
