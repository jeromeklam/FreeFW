<?php
/**
 * Utilitaires de traitement des images
 *
 * @author jeromeklam
 * @package Image
 * @category Tools
 */
namespace FreeFW\Tools;

use \FreeFW\Constants as FFCST;

/**
 * Utilitaires de traitement des images
 * @author jeromeklam
 */
class ImageResizer
{
    const CROPTOP = 1;
    const CROPCENTRE = 2;
    const CROPCENTER = 2;
    const CROPBOTTOM = 3;
    const CROPLEFT = 4;
    const CROPRIGHT = 5;
    public $quality_jpg = 75;
    public $quality_png = 0;
    public $interlace = 0;
    public $source_type;
    protected $source_image;
    protected $original_w;
    protected $original_h;
    protected $dest_x = 0;
    protected $dest_y = 0;
    protected $source_x;
    protected $source_y;
    protected $dest_w;
    protected $dest_h;
    protected $source_w;
    protected $source_h;
    protected $ask_w = 0;
    protected $ask_h = 0;
    protected $centered = false;

    /**
     * Create instance from a strng
     *
     * @param  string $image_data
     * @throws \exception
     *
     * @return string
     */
    public static function createFromString($image_data)
    {
        $resize = new self('data://application/octet-stream;base64,' . base64_encode($image_data));
        return $resize;
    }

    /**
     * Loads image source and its properties to the instanciated object
     *
     * @param  string $filename
     * @throws \Exception
     *
     * @return string
     */
    public function __construct($filename)
    {
        $image_info = @getimagesizefromstring($filename);
        if (!$image_info) {
            $image_info = @getimagesize($filename);
            if (!$image_info) {
                throw new \Exception('Could not read file', FFCST::ERROR_FILE_NOT_FOUND);
            }
            $fromString = false;
        } else {
            $fromString = true;
        }
        $this->source_filename = $filename;
        list (
            $this->original_w,
            $this->original_h,
            $this->source_type
        ) = $image_info;
        if ($fromString) {
            switch ($this->source_type) {
                case IMAGETYPE_GIF:
                    $this->source_image = imagecreatefromstring($filename);
                    break;
                case IMAGETYPE_JPEG:
                    $this->source_image = imagecreatefromstring($filename);
                    $this->imageCreateJpegfromExif($this->source_image);
                    // set new width and height for image, maybe it has changed
                    $this->original_w = ImageSX($this->source_image);
                    $this->original_h = ImageSY($this->source_image);
                    break;
                case IMAGETYPE_PNG:
                    $this->filters = PNG_NO_FILTER;
                    $this->source_image = imagecreatefromstring($filename);
                    break;
                default:
                    throw new \Exception('Unsupported image type', FFCST::ERROR_IMAGETYPE_NOT_SUPPORTED);
                    break;
            }
        } else {
            switch ($this->source_type) {
                case IMAGETYPE_GIF:
                    $this->source_image = imagecreatefromgif($filename);
                    break;
                case IMAGETYPE_JPEG:
                    $this->source_image = $this->imageCreateJpegfromExif($filename);
                    // set new width and height for image, maybe it has changed
                    $this->original_w = ImageSX($this->source_image);
                    $this->original_h = ImageSY($this->source_image);
                    break;
                case IMAGETYPE_PNG:
                    $this->filters = PNG_NO_FILTER;
                    $this->source_image = imagecreatefrompng($filename);
                    break;
                default:
                    throw new \Exception('Unsupported image type', FFCST::ERROR_IMAGETYPE_NOT_SUPPORTED);
                    break;
            }
        }
        return $this->resize($this->getSourceWidth(), $this->getSourceHeight());
    }

    /**
     * Création à partir d'un exif
     *
     * @param string $filename
     *
     * @return resource
     */
    public function imageCreateJpegfromExif($filename)
    {
        $img  = imagecreatefromjpeg($filename);
        try {
            $exif = @exif_read_data($filename);
        } catch (\Exception $ex) {
            $exif = false;
        }
        if (!$exif || !isset($exif['Orientation'])) {
            return $img;
        }
        $orientation = $exif['Orientation'];
        if ($orientation === 6 || $orientation === 5) {
            $img = imagerotate($img, 270, null);
        } elseif ($orientation === 3 || $orientation === 4) {
            $img = imagerotate($img, 180, null);
        } elseif ($orientation === 8 || $orientation === 7) {
            $img = imagerotate($img, 90, null);
        }
        if ($orientation === 5 || $orientation === 4 || $orientation === 7) {
            imageflip($img, IMG_FLIP_HORIZONTAL);
        }

        return $img;
    }

    /**
     * Saves new image
     *
     * @param  string  $filename
     * @param  string  $image_type
     * @param  integer $quality
     * @param  integer $permissions
     *
     * @return static
     */
    public function save($filename, $image_type = null, $quality = null, $permissions = null)
    {
        $image_type = $image_type ?: $this->source_type;
        if ($this->centered) {
            $dest_image = imagecreatetruecolor($this->ask_w, $this->ask_h);
        } else {
            $dest_image = imagecreatetruecolor($this->getDestWidth(), $this->getDestHeight());
        }
        imageinterlace($dest_image, $this->interlace);
        switch ($image_type) {
            case IMAGETYPE_GIF:
                $background = imagecolorallocatealpha($dest_image, 255, 255, 255, 1);
                imagecolortransparent($dest_image, $background);
                imagefill($dest_image, 0, 0, $background);
                imagesavealpha($dest_image, true);
                break;
            case IMAGETYPE_JPEG:
                $background = imagecolorallocate($dest_image, 255, 255, 255);
                if ($this->centered) {
                    imagefilledrectangle($dest_image, 0, 0, $this->ask_w, $this->ask_h, $background);
                } else {
                    imagefilledrectangle($dest_image, 0, 0, $this->getDestWidth(), $this->getDestHeight(), $background);
                }
                break;
            case IMAGETYPE_PNG:
                imagealphablending($dest_image, false);
                $background = imagecolorallocate($dest_image, 255, 255, 255);
                if ($this->centered) {
                    imagefilledrectangle($dest_image, 0, 0, $this->ask_w, $this->ask_h, $background);
                } else {
                    imagefilledrectangle($dest_image, 0, 0, $this->getDestWidth(), $this->getDestHeight(), $background);
                }
                    imagesavealpha($dest_image, true);
                break;
        }
        imagecopyresampled(
            $dest_image,
            $this->source_image,
            $this->dest_x,
            $this->dest_y,
            $this->source_x,
            $this->source_y,
            $this->getDestWidth(),
            $this->getDestHeight(),
            $this->source_w,
            $this->source_h
        );
        switch ($image_type) {
            case IMAGETYPE_GIF:
                imagegif($dest_image, $filename);
                break;
            case IMAGETYPE_JPEG:
                if ($quality === null) {
                    $quality = $this->quality_jpg;
                }
                imagejpeg($dest_image, $filename, $quality);
                break;
            case IMAGETYPE_PNG:
                if ($quality === null) {
                    $quality = $this->quality_png;
                }
                imagepng($dest_image, $filename, $quality);
                break;
        }
        if ($permissions) {
            chmod($filename, $permissions);
        }
        return $this;
    }

    /**
     * Conversion en chaine
     *
     * @param  int $image_type
     * @param  int $quality
     *
     * @return string
     */
    public function getImageAsString($image_type = null, $quality = null)
    {
        $string_temp = tempnam('', '');
        $this->save($string_temp, $image_type, $quality);
        $string = file_get_contents($string_temp);
        unlink($string_temp);

        return $string;
    }

    /**
     * Magic toString
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getImageAsString();
    }

    /**
     * Output
     *
     * @param string  $image_type
     * @param integer $quality
     *
     * @return void
     */
    public function output($image_type = null, $quality = null)
    {
        $image_type = $image_type ?: $this->source_type;
        header('Content-Type: ' . image_type_to_mime_type($image_type));
        $this->save(null, $image_type, $quality);
    }

    /**
     * Redimension suivant la hauteur, largeur proportionnelle
     *
     * @param integer $height
     * @param boolean $allow_enlarge
     *
     * @return static
     */
    public function resizeToHeight($height, $allow_enlarge = false)
    {
        $ratio = $height / $this->getSourceHeight();
        $width = $this->getSourceWidth() * $ratio;
        $this->resize($width, $height, $allow_enlarge);

        return $this;
    }

    /**
     * Redimension sur la largeur, hauteur proportionnelle
     *
     * @param integer $width
     * @param boolean $allow_enlarge
     *
     * @return static
     */
    public function resizeToWidth($width, $allow_enlarge = false)
    {
        $ratio  = $width / $this->getSourceWidth();
        $height = $this->getSourceHeight() * $ratio;
        $this->resize($width, $height, $allow_enlarge);

        return $this;
    }

    /**
     * Redimension au miieux
     *
     * @param integer $max_width
     * @param integer $max_height
     * @param boolean $allow_enlarge
     * @param boolean $allow_centered
     *
     * @return static
     */
    public function resizeToBestFit($max_width, $max_height, $allow_enlarge = false, $allow_centered = false)
    {
        if ($this->getSourceWidth() <= $max_width && $this->getSourceHeight() <= $max_height &&
            $allow_enlarge === false) {
            return $this;
        }
        $ratio  = $this->getSourceHeight() / $this->getSourceWidth();
        $width  = $max_width;
        $height = $width * $ratio;
        if ($height > $max_height) {
            $height = $max_height;
            $width  = $height / $ratio;
        }

        if ($allow_centered) {
            return $this->resizeCentered($max_width, $max_height, $width, $height, $allow_enlarge);
        }

        return $this->resize($width, $height, $allow_enlarge);
    }

    /**
     * Redimension suivant le ratio
     *
     * @param integer|float $scale
     *
     * @returnstatic
     */
    public function scale($scale)
    {
        $width  = $this->getSourceWidth() * $scale / 100;
        $height = $this->getSourceHeight() * $scale / 100;
        $this->resize($width, $height, true);
        return $this;
    }

    /**
     * Redimension centrée aux dimensions, au mieux
     *
     * @param integer $width
     * @param integer $height
     * @param boolean $allow_enlarge
     *
     * @return static
     */
    protected function resizeCentered($dest_width, $dest_height, $width, $height, $allow_enlarge = false)
    {
        $this->centered = true;
        $this->ask_w    = $dest_width;
        $this->ask_h    = $dest_height;
        $this->source_x = 0;
        $this->source_y = 0;
        $this->dest_w   = $width;
        $this->dest_h   = $height;
        $this->source_w = $this->getSourceWidth();
        $this->source_h = $this->getSourceHeight();
        if ($this->dest_w < $dest_width) {
            $this->dest_x = ($dest_width-$this->dest_w)/2;
        } else {
            if ($this->dest_h < $dest_height) {
                $this->dest_y = ($dest_height-$this->dest_h)/2;
            }
        }
        return $this;
    }

    /**
     * Redimension aux dimensions
     *
     * @param integer $width
     * @param integer $height
     * @param boolean $allow_enlarge
     *
     * @return static
     */
    public function resize($width, $height, $allow_enlarge = false)
    {
        if (!$allow_enlarge) {
            // if the user hasn't explicitly allowed enlarging,
            // but either of the dimensions are larger then the original,
            // then just use original dimensions - this logic may need rethinking
            if ($width > $this->getSourceWidth() || $height > $this->getSourceHeight()) {
                $width  = $this->getSourceWidth();
                $height = $this->getSourceHeight();
            }
        }
        $this->source_x = 0;
        $this->source_y = 0;
        $this->dest_x   = 0;
        $this->dest_y   = 0;
        $this->dest_w   = $width;
        $this->dest_h   = $height;
        $this->source_w = $this->getSourceWidth();
        $this->source_h = $this->getSourceHeight();

        return $this;
    }

    /**
     * Drop de l'image aux dimensions demandées
     *
     * @param integer $width
     * @param integer $height
     * @param boolean $allow_enlarge
     * @param integer $position
     *
     * @return static
     */
    public function crop($width, $height, $allow_enlarge = false, $position = self::CROPCENTER)
    {
        if (!$allow_enlarge) {
            // this logic is slightly different to resize(),
            // it will only reset dimensions to the original
            // if that particular dimenstion is larger
            if ($width > $this->getSourceWidth()) {
                $width  = $this->getSourceWidth();
            }
            if ($height > $this->getSourceHeight()) {
                $height = $this->getSourceHeight();
            }
        }
        $ratio_source = $this->getSourceWidth() / $this->getSourceHeight();
        $ratio_dest = $width / $height;
        if ($ratio_dest < $ratio_source) {
            $this->resizeToHeight($height, $allow_enlarge);
            $excess_width   = ($this->getDestWidth() - $width) / $this->getDestWidth() * $this->getSourceWidth();
            $this->source_w = $this->getSourceWidth() - $excess_width;
            $this->source_x = $this->getCropPosition($excess_width, $position);
            $this->dest_w   = $width;
        } else {
            $this->resizeToWidth($width, $allow_enlarge);
            $excess_height  = ($this->getDestHeight() - $height) / $this->getDestHeight() * $this->getSourceHeight();
            $this->source_h = $this->getSourceHeight() - $excess_height;
            $this->source_y = $this->getCropPosition($excess_height, $position);
            $this->dest_h   = $height;
        }

        return $this;
    }

    /**
     * Largeur
     *
     * @return integer
     */
    public function getSourceWidth()
    {
        return $this->original_w;
    }

    /**
     * Hauteur
     *
     * @return integer
     */
    public function getSourceHeight()
    {
        return $this->original_h;
    }

    /**
     * Largeur de destination
     *
     * @return integer
     */
    public function getDestWidth()
    {
        return $this->dest_w;
    }

    /**
     * Hauteur de destination
     *
     * @return integer
     */
    public function getDestHeight()
    {
        return $this->dest_h;
    }

    /**
     * Position du crop
     *
     * @param integer $expectedSize
     * @param integer $position
     *
     * @return integer
     */
    protected function getCropPosition($expectedSize, $position = self::CROPCENTER)
    {
        $size = 0;
        switch ($position) {
            case self::CROPBOTTOM:
            case self::CROPRIGHT:
                $size = $expectedSize;
                break;
            case self::CROPCENTER:
            case self::CROPCENTRE:
                $size = $expectedSize / 2;
                break;
        }

        return $size;
    }
}
