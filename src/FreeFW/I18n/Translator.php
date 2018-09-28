<?php
namespace FreeFW\I18n;

/**
 * Gestion des traductions
 *
 * @author jeromeklam
 * @package I18n
 */
class Translator
{
    /**
     *
     * @var array
     */
    protected $files = array();

    /**
     * Chargé ??
     *
     * @var boolean
     */
    protected $loaded = false;

    /**
     * Clefs...
     *
     * @var array
     */
    protected $keys = array();

    /**
     * Cache keys
     *
     * @var string
     */
    protected $cache = array();

    /**
     *
     * @var unknown
     */
    protected static $instances = array();

    /**
     * Constructeur
     */
    protected function __construct()
    {
        $cfg       = \FreeFW\ResourceDi::getInstance()->getConfig();
        $this->dev = $cfg->get('development', false);
    }

    /**
     * Retourne une instance
     *
     * @param string $p_lang
     *
     * @return \FreeFW\I18n\Translator
     */
    public static function getFactory($p_lang = null)
    {
        $ret = false;
        if ($p_lang !== null) {
            if (!array_key_exists($p_lang, self::$instances)) {
                self::$instances[$p_lang] = new self();
            }
            $ret = self::$instances[$p_lang];
        } else {
            if (count(self::$instances) > 0) {
                foreach (self::$instances as $idx -> $ret) {
                    break;
                }
            }
        }

        return $ret;
    }

    /**
     * Purge
     *
     * @return \FreeFW\I18n\Translator
     */
    public function flush()
    {
        $this->files  = array();
        $this->loaded = false;

        return $this;
    }

    /**
     * Ajout d'un fichier
     *
     * @param string $p_fileName
     *
     * @return \FreeFW\I18n\Translator
     */
    public function addFile($p_fileName)
    {
        $this->files[] = array(
            'name'   => $p_fileName,
            'loaded' => false
        );

        return $this;
    }

    /**
     * Parse d'une clef
     *
     * @param string $p_key
     * @param array  $p_keys
     *
     * @return mixed
     */
    protected function parse($p_key, $p_keys)
    {
        $ret   = null;
        $parts = explode('.', $p_key);
        if (count($parts) > 0) {
            $elem = $parts[0];
            if (array_key_exists($elem, $p_keys)) {
                if (count($parts) == 1) {
                    return $p_keys[$elem];
                } else {
                    return $this->parse(implode('.', array_slice($parts, 1)), $p_keys[$elem]);
                }
            }
        }
        return $ret;
    }

    /**
     * Retourne une traduction
     *
     * @param string $p_key
     * @param string $p_default
     * @param array  $p_params
     *
     * @return string
     */
    public function get($p_key, $p_default = '', $p_params = array())
    {
        $ret = $p_default;
        if (!$this->loaded) {
            foreach ($this->files as $idx => $file) {
                if (is_file($file['name'])) {
                    $infos = pathinfo($file['name']);
                    switch (strtolower($infos['extension'])) {
                        case 'php':
                            $this->keys = array_replace_recursive(include $file['name'], $this->keys);
                            break;
                    }
                }
            }
            $this->loaded = true;
        }
        if (!array_key_exists($p_key, $this->cache)) {
            $this->cache[$p_key] = $this->parse($p_key, $this->keys);
        }
        $ret = $this->cache[$p_key];
        if ($ret === null) {
            if ($this->dev && $p_default !== false) {
                $ret = $p_key;
            } else {
                $ret = $p_default;
            }
        }
        return $ret;
    }
}
