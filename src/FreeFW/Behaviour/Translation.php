<?php
/**
 * Classe de gestion des traductions
 *
 * @author jeromeklam
 * @package I18n
 * @category Trait
 */
namespace FreeFW\Behaviour;

use \FreeFW\ResourceDi as Singleton;

/**
 * Gestion des traductions
 * @author jeromeklam
 */
trait Translation
{

    /****
     *
     * @var string
     */
    protected static $language = 'FR';

    /**
     * Application
     *
     * @var unknown
     */
    protected static $tr_application = null;

    /**
     * Translations
     *
     * @var array
     */
    protected static $translations = array();

    /**
     * Initialisartion
     *
     * @return this
     */
    public function initLang($p_application)
    {
        self::$tr_application = $p_application;
        // Set language to French
        putenv('LC_ALL=fr_FR');
        setlocale(LC_ALL, 'fr_FR');

        return $this;
    }

    /**
     * Affectation de la langue
     *
     * @param string $p_lang
     *
     * @return this
     */
    protected function setLang($p_lang)
    {
        self::$language = $p_lang;

        return $this;
    }

    /**
     * Retourne la langue
     */
    public function getLang()
    {
        return strtoupper(self::$language);
    }

    /**
     * Retourne la liste des traductions
     *
     * @param string  $p_lang
     * @param boolean $p_refresh
     *
     * @return \FreeFW\I18n\Translator
     */
    public function getTranslator($p_lang = null, $p_refresh = false)
    {
        $lang = $this->getLang();
        if ($p_lang !== null) {
            $lang = $p_lang;
        }
        $tr = Singleton::getInstance()->getShared('translator.' . strtolower($lang));
        if ($tr === null || $tr === false || $p_refresh) {
            $tr = \FreeFW\I18n\Translator::getFactory($lang);
            $tr->flush();
            foreach (self::getDIModules() as $idx => $module) {
                $ns     = str_replace('.', '/', $module['ns']);
                $trFile = rtrim($module['path'], '/') . '/' . $module['name'] . '/src/' . $ns .
                          '/Resources/I18n/' . strtolower($lang) . '.php';
                $tr->addFile($trFile);
            }
            Singleton::getInstance()->setShared('translator.' . strtolower($lang), $tr);
        }

        return $tr;
    }

    /**
     * Traduction
     *
     * @param string $p_value
     * @param string $p_default
     * @param array  $p_params
     *
     * @return string
     */
    public function _($p_value, $p_default = '', $p_params = array())
    {
        try {
            $tr = $this->getTranslator()->get($p_value, $p_default, $p_params);
            if ($tr === null || $tr == '') {
                return '';
            }
            if (is_array($tr)) {
                return 'array...' . $p_value;
            }
            return $tr;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}
