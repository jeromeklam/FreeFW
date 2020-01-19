<?php
namespace FreeFW\Core;

/**
 * Base controller
 *
 * @author jeromeklam
 */
class ApiMediaController extends \FreeFW\Core\ApiController
{

    /**
     * Get base64 src format
     *
     * @param mixed $p_data
     *
     * @return boolean|string
     */
    protected function decode_chunk($p_data)
    {
        $data = explode(';base64,', $p_data);
        if (!is_array($data) || !isset($data[1])) {
            return false;
        }
        $data = base64_decode($data[1]);
        if (!$data) {
            return false;
        }
        return $data;
    }

    /**
     * Fet file type
     * 
     * @param string $p_filename
     * 
     * @return string
     */
    protected function getMediaType($p_filename)
    {
        $path_parts = pathinfo($p_filename);
        $extension  = trim(strtolower($path_parts['extension']));
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'tiff':
            case 'raw':
            case 'bmp':
                return 'PHOTO';
        }
        return 'OTHER';
    }
}
