<?php
/**
 * Classe core de gestion d'une application
 *
 * @author jeromeklam
 * @package Application
 */
namespace FreeFW\Application;

/**
 * Classe de base des applications
 * @author jeromeklam
 */
class Base extends \FreeFW\Listener\Observable
{

    /**
     * Comportements
     */
    use \FreeFW\Behaviour\DI;
    use \FreeFW\Behaviour\LoggerAwareTrait;
    use \FreeFW\Behaviour\EventManager;
    use \FreeFW\Behaviour\Translation;

    /**
     * Type de templater
     *
     * @var string
     */
    protected $templaterType = \FreeFW\Router\Route::TYPE_HTML;

    /**
     * Templater
     *
     * @var \FreeFW\Interfaces\Templater
     */
    protected $templater = null;

    /**
     * Destructeur
     */
    public function __destruct()
    {
    }

    /**
     *
     * @param unknown $p_controller
     * @param array   $p_params
     * @return string
     */
    public function renderController($p_controller, $p_params = array())
    {
        $action = explode('::', $p_controller);
        if (count($action)<=1) {
            throw new \Exception(sprintf('Call %s is not well configured, :: missing !', $p_controller));
        }
        if (!class_exists($action[0])) {
            throw new \Exception(sprintf('Class %s doesn\'t exists !', $action[0]));
        }
        $instance = new $action[0];
        // @todo : vérifier le nombre de paramètres...
        if (method_exists($instance, 'isDI')) {
            $response = $instance->{$action[1]}();
            return $response->render();
        }
        return $p_controller;
    }

    /**
     * Affectation du templater
     *
     * @param \FreeFW\Interfaces\Templater $p_templater
     *
     * @return \FreeFW\Application\Html5
     */
    public function setTemplater($p_templater)
    {
        $this->templater     = $p_templater;
        $this->templaterType = \FreeFW\Router\Route::TYPE_TWIG;
        $this->templater->setApplication($this);
        foreach (self::getDIModules() as $name => $module) {
            $ns   = str_replace('.', '/', $module['ns']);
            $path = rtrim($module['path'], '/') . '/' . rtrim($module['name'], '/') . '/src/' . $ns;
            $name = $module['layout'];
            $this->templater->registerNamespace($name, $path);
        }
        self::setDIShared('templater', $this->templater);
        return $this;
    }

    /**
     * Get templater
     *
     * @return \FreeFW\Interfaces\Templater
     */
    public function getTemplater()
    {
        return $this->templater;
    }

    /**
     * Event de fin
     *
     * @return void
     */
    protected function beforeFinish()
    {
        $manager = $this->getEventManager();
        $manager->notify(\FreeFW\Constants::EVENT_BEFORE_FINISH);
        return $this;
    }

    /**
     * Event de fin
     *
     * @return void
     */
    protected function afterRender()
    {
        $manager = $this->getEventManager();
        $manager->notify(\FreeFW\Constants::EVENT_AFTER_RENDER);
        return $this;
    }

    /**
     * Checks système
     *
     * @return static
     */
    protected function checkSystem()
    {
        $errorLog = ini_get('error_log');
        if (!is_file($errorLog)) {
            $h = fopen($errorLog, 'a+');
            if ($h) {
                fclose($h);
            }
        }
        //chmod($errorLog, 0666);
        return $this;
    }
}
