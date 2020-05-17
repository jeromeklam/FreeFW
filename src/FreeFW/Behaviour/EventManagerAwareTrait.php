<?php
/**
 * Helper de gestion des événements
 *
 * @author jeromeklam
 * @package Event
 * @category Manager
 */
namespace FreeFW\Behaviour;

/**
 * Comportement de gestionnaire d'événement
 *
 * @author jeromeklam
 */
trait EventManagerAwareTrait
{

    /**
     * Manager
     * @var \FreeFW\Listener\Eventmanager
     */
    protected $eventManager = null;

    /**
     * Affectation du gestionnaire d'événement
     *
     * @var \FreeFW\Listener\Eventmanager $p_manager
     *
     * @return \FreeFW\Behaviour\EventManagerAwareTrait
     */
    public function setEventManager($p_manager)
    {
        $this->eventManager = $p_manager;
        return $this;
    }

    /**
     * Retourne le gestionnaire d'événement
     *
     * @return \FreeFW\Listener\Eventmanager
     */
    public function getEventManager()
    {
        if ($this->eventManager === null) {
            $this->eventManager = \FreeFW\Listener\EventManager::getInstance();
        }
        return $this->eventManager;
    }

    /**
     * Lance un événement
     *
     * @param string $p_name
     * @param mixed  $p_data
     *
     * @return static
     */
    public function fireEvent($p_name, $p_data = null)
    {
        $evtManager = $this->getEventManager();
        if ($p_data === null) {
            $p_data = array();
        }
        $evtManager->notify($p_name, (array)$p_data);
        return $this;
    }

    /**
     * Lance un événement
     *
     * @param string $p_name
     * @param mixed  $p_data
     *
     * @return static
     */
    public function forwardRawEvent($p_name, $p_data = null)
    {
        $evtManager = $this->getEventManager();
        $evtManager->notify($p_name, $p_data);
        return $this;
    }
}
