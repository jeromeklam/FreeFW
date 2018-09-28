<?php
/**
 * Classe de gestion des événements / observeurs
 * Suivre le lien ci-dessous pour la partie Observer
 * @see http://php.net/manual/fr/class.splsubject.php
 *
 * @author jeromeklam
 * @package Event
 * @category Manager
 */
namespace FreeFW\Listener;

/**
 * Observable
 * @author jeromeklam
 */
class Observable implements \SplSubject
{

    /**
     * Liste des observeurs
     *
     * @var array
     */
    private $observers;

    /**
     * Nom
     *
     * @var string
     */
    private $name;

    /**
     * Constructeur
     *
     * @param string $name
     */
    protected function __construct($name)
    {
        $this->observers = new \SplObjectStorage();
        $this->name = $name;
    }

    /**
     * Attachement d'un observeur
     *
     * @param \SplObserver $observer
     */
    public function attach(\SplObserver $observer)
    {
        $this->observers->attach($observer);
    }

    /**
     * Retrait d'un observeur
     *
     * @param \SplObserver $observer
     */
    public function detach(\SplObserver $observer)
    {
        $this->observers->detach($observer);
    }

    /**
     * Notification
     */
    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    /**
     * Retourne le nom
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
