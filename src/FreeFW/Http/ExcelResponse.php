<?php
namespace FreeFW\Http;

/**
 * Response au format excel
 */
class ExcelResponse extends \FreeFW\Http\MimeResponse
{

    /**
     * Génération de la réponse
     *
     * @return void;
     */
    public function render()
    {
        if (is_file($this->getContent())) {
            $path_parts = pathinfo($this->getContent());
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            $type = $this->getMimeType();
            if ($type !== false) {
                header('Content-type: ' . $type);
            } else {
                header('Content-type: application/vnd.ms-excel');
            }
            header('Content-type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $path_parts['basename'] . '";');
            header('Pragma: no-cache');
            header('Expires: 0');
            readfile($this->getContent());
        } else {
            // @todo
        }
    }
}
