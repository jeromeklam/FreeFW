<?php
/**
 * Classe de gestion des événements
 *
 * @author jeromeklam
 * @package Event
 * @category Core
 */
namespace FreeFW\Listener;

/**
 * Gestion d'événements
 * @author jeromeklam
 */
class EventManager
{

    /**
     * Singleton
     */
    public static $instance = null;

    /**
     * Ecouteurs
     *
     * @var array
     */
    private $listeners = array();

    /**
     * Constructeur privé, <=> songleton
     */
    private function __construct()
    {
    }

    /**
     * Retourne le singleton
     *
     * @return \FreeFW\Listener\EventManager
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Notification d'événement
     *
     * @param string $event_name
     * @param mixed  $data
     *
     * @return \FreeFW\Listener\EventManager
     */
    public function notify($event_name, $data = null)
    {
        $listener = $this->getListener($event_name);
        if (!$listener) {
            return $this;
        }
        foreach ($listener as $event) {
            call_user_func($event['callback'], $data, $event_name);
        }
        return $this;
    }

    /**
     * Association d'un événement
     *
     * @param mixed $event (string/array)
     * @param mixed $callback
     * @param int   $priority
     *
     * @return \FreeFW\Listener\EventManager
     */
    public function bind($event, $callback, $priority = 1)
    {
        if (is_array($event)) {
            foreach ($event as $oneEvent) {
                $this->registerEvent($oneEvent, $callback, $priority);
            }
        } else {
            $this->registerEvent($event, $callback, $priority);
        }
        return $this;
    }

    /**
     * Supression d'un événement
     *
     * @param  string $event_name
     * @return EventManager
     */
    public function unbind($event_name)
    {
        return $this->deRegisterEvent($event_name);
    }

    /**
     * Fonction finale d'enregistrement d'un événement
     *
     * @param string $event_name
     * @param mixed  $callback
     * @param int    $priority
     *
     * @return \FreeFW\Listener\EventManager
     */
    final public function registerEvent($event_name, $callback, $priority)
    {
        $event_name = trim($event_name);
        if (!isset($this->listeners[$event_name])) {
            $this->listeners[$event_name] = array();
        }
        $event = array(
            'event_name' => $event_name,
            'callback' => $callback,
            'priority' => (int)$priority
        );
        array_push($this->listeners[$event_name], $event);
        if (count($this->listeners[$event_name]) > 1) {
            usort($this->listeners[$event_name], array($this, 'sortListenerByPriority'));
        }
        return $this;
    }

    /**
     * Fonction finale pour désactiver un événement
     *
     * @param string $event_name
     *
     * @return \FreeFW\Listener\EventManager
     */
    final public function deRegisterEvent($event_name)
    {
        if (isset($this->listeners[$event_name])) {
            unset($this->listeners[$event_name]);
        }
        return $this;
    }

    /**
     * Retourne la liste des écouteurs
     *
     * @return array
     */
    public function getListeners()
    {
        return $this->listeners;
    }

    /**
     * Retourne un écouteur spécifique
     *
     * @param $listener
     *
     * @return bool | array
     */
    public function getListener($listener)
    {
        if (isset($this->listeners[$listener])) {
            return $this->listeners[$listener];
        }
        return false;
    }

    /**
     *
     */
    private function sortListenerByPriority($a, $b)
    {
        if ($a['priority'] == $b['priority']) {
            return 0;
        }
        return ($a['priority'] < $b['priority']) ? -1 : 1;
    }
}
