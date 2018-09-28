<?php
/**
 * Utilitaires pour les images
 *
 * @author jeromeklam
 * @package Image
 * @category Tools
 */
namespace FreeFW\Tools;

/**
 * Utilitaires pour les images
 * @author jeromeklam
 */
class Image
{

    /**
     * Génération d'une image carrée
     *
     * @var string $p_destFile
     * @var number $p_size
     * @var string $p_background
     *
     * @return boolean
     */
    public static function createSquare($p_destFile, $p_size, $p_background = null)
    {
        try {
            $im = ImageCreate($p_size, $p_size);
            $cf = ImageColorAllocate($im, 255, 255, 255);
            
            return @imagejpeg($im, $p_destFile, 90);
        } catch (\Exception $ex) {
            // ...
        }
        
        return false;
    }
}
