<?php
/**
 * Templater php standard
 *
 * @author pawrugg
 * @package Templater
 * @category View
 */
namespace FreeFW\Templater;

/**
 * Templater php standard
 * @author pawrugg
 */
class View extends \FreeFW\Templater\AbstractTemplater
{

    /**
     * Comportements
     */
    use \FreeFW\Behaviour\Translation;

    /**
     *
     * @var unknown
     */
    protected $file;

    /**
     *
     * @var unknown
     */
    protected $map;

    /**
     * Lang
     *
     * @var string
     */
    protected $lang = null;

    /**
     * Constructor
     *
     * @param string $p_lang
     */
    public function __construct($p_lang = null)
    {
        $this->lang = $p_lang;
    }

    /**
     * Générarion du nom du fichier en fonction du nom du template
     *
     * @param string $p_template
     *
     * @return \FreeFW\Templater\View
     */
    protected function setFileFromTemplate($p_template)
    {
        $slash = strpos($p_template, '/');
        $ns    = str_replace('@', '', substr($p_template, 0, $slash));
        if (array_key_exists($ns, $this->namespaces)) {
            $dir = rtrim($this->namespaces[$ns], '/') . '/Template/' . strtoupper($this->lang);
            $dir = $dir . substr($p_template, $slash) . '.php';
            //
            $this->file = $dir;
        }
        
        return $this;
    }

    /**
     * Set file name
     *
     * @param string $pfile
     *
     * @return \FreeFW\Templater\View
     */
    public function setFile($pfile)
    {
        $parts = explode('/', $pfile);
        $this->file = $this->dir . '/' . ucFirst($this->lang);
        foreach ($parts as $folder) {
            $this->file .= '/' . ucfirst($folder);
        }
        $this->file .= '.php';
        
        return $this;
    }

    /**
     * Enter description here ...
     * TODO path translation ?
     *
     * @param unknown_type $file
     */
    public function inc($file)
    {
        if (file_exists($file)) {
            include $file;
        }
    }

    /**
     * Catch anything that we havent defined
     *
     * @param mixed $name
     * @param mixed $args
     */
    public function __call($name, $args)
    {
    }

    /**
     * Setter
     *
     * @param string $key
     * @param mixed  $name
     *
     * @return \FreeFW\Templater\View
     */
    public function __set($key, $name)
    {
        if (!is_array($this->map)) {
            $this->map = array();
        }
        $this->map[$key] = $name;
        
        return $this;
    }

    /**
     * Getter
     *
     * @param strng $key
     *
     * @returns mixed
     */
    public function __get($key)
    {
        if (is_array($this->map) && array_key_exists($key, $this->map)) {
            return $this->map[$key];
        }
        
        return null;
    }

    /**
     * Assign = Setter
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return \FreeFW\Templater\View
     */
    public function assign($name, $value = null)
    {
        if (is_string($name)) {
            $this->map[$name] = $value;
        } elseif (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->assign($key, $value);
            }
        }
       
        return $this;
    }

    /**
     * Purge
     *
     * @return \FreeFW\Templater\View
     */
    public function clear()
    {
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            if ('_' !== substr($key, 0, 1)) {
                unset($this->{$key});
            }
        }
        
        return $this;
    }

    /**
     * Génération
     *
     * @param string $p_templateFileName
     * @param array  $p_params
     *
     * @return string
     */
    public function render($p_templateFileName, $p_params = array())
    {
        if (!is_file($p_templateFileName)) {
            $this->setFileFromTemplate($p_templateFileName);
        } else {
            $this->file = $p_templateFileName;
        }
        if (file_exists($this->file)) {
            ob_start();
            include $this->file;
            
            return ob_get_clean();
        } else {
            throw new \Exception("View file does not exist $this->file");
        }
    }
}
