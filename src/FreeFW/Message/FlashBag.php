<?php
/**
 * Classe de gestion d'un container de messages
 *
 * @author jeromeklam
 * @package Message
 */
namespace FreeFW\Message;

/**
 * Container de messages flash
 * @author jeromeklam
 */
class FlashBag extends \FreeFW\Session\Storage implements \Countable, \Iterator, \JsonSerializable
{

    /**
     * Liste des messages
     */
    protected $var = array();

    /**
     * Nombre
     *
     * @var number
     */
    protected $myCount = 0;

    /**
     * Constructeur
     *
     * @param array $array
     */
    public function __construct($array = array())
    {
        if (is_array($array)) {
            $this->var = $array;
        }
        $this->myCount = count($this->var);
    }

    /**
     * @see \Iterable
     */
    public function rewind()
    {
        reset($this->var);
    }

    /**
     * @see \Iterable
     */
    public function current()
    {
        $var = current($this->var);

        return $var;
    }

    /**
     * @see \Iterable
     */
    public function key()
    {
        $var = key($this->var);

        return $var;
    }

    /**
     * @see \Iterable
     */
    public function next()
    {
        $var = next($this->var);

        return $var;
    }

    /**
     * @see \Iterable
     */
    public function valid()
    {
        $key = key($this->var);
        $var = ($key !== null && $key !== false);

        return $var;
    }

    /**
     * @see \Countable
     */
    public function count()
    {
        return $this->myCount;
    }

    /**
     * @see \Iterable
     */
    public function add($value)
    {
        $this->var[]    = $value;
        $this->myCount = count($this->var);

        return $this;
    }

    /**
     * @see \JsonSerializable
     */
    public function jsonSerialize()
    {
        $result = array();
        foreach ($this->var as $idx => $message) {
            $result[] = $message->__toArray();
        }

        return $result;
    }

    /**
     * Sauvegarde en session
     *
     * @param \FreeFW\Interfaces\Session $p_session
     */
    protected function storeToSession(\FreeFW\Interfaces\Session $p_session)
    {
        $result = array();
        foreach ($this->var as $idx => $message) {
            if ($message->isvalid()) {
                $result[] = $message->__toArray();
            }
        }
        if (count($result) > 0) {
            $ses = json_encode($result);
            $p_session->set('flashbag', $ses);
        } else {
            $p_session->remove('flashbag');
        }
    }

    /**
     * Récupération de la session
     *
     * @param \FreeFW\Interfaces\Session $p_session
     */
    protected function restoreFromSession(\FreeFW\Interfaces\Session $p_session)
    {
        $ses = $p_session->get('flashbag');
        if ($ses !== null && $ses !== false && $ses != '') {
            $arr = json_decode($ses, true);
            if (is_array($arr)) {
                $this->var = [];
                foreach ($arr as $idx => $arrM) {
                    $this->var[] = \FreeFW\Message\Flash::getInstance($arrM);
                }
                $this->myCount = count($this->var);
            }
        }
    }
}
