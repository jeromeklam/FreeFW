<?php
namespace FreeFW\Model;

/**
 * Gestion du résultat d'une requête
 *
 * @author jeromeklam
 * @package SQL\Result
 */
class ResultSet implements \ArrayAccess, \Countable, \Iterator, \JsonSerializable
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
    protected $my_count = 0;

    /**
     * Total count
     *
     * @var number
     */
    protected $total_count = 0;

    /**
     * Erreur
     *
     * @var boolean
     */
    protected $error = false;

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
        $this->my_count = count($this->var);
    }

    /**
     * Récupération du total général
     *
     * @return number
     */
    public function getTotalCount()
    {
        return $this->total_count;
    }

    /**
     * On affecte le total général
     *
     * @param number $p_count
     *
     * @return \FreeFW\Model\ResultSet
     */
    public function setTotalCount($p_count)
    {
        $this->total_count = $p_count;

        return $this;
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
        return $this->my_count;
    }

    /**
     * @see \Iterable
     */
    public function add($value)
    {
        $this->var[]    = $value;
        $this->my_count = count($this->var);

        return $this;
    }

    /**
     * @see \ArrayAccess
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->var[] = $value;
        } else {
            $this->var[$offset] = $value;
        }
        $this->my_count = count($this->var);
    }

    /**
     * @see \ArrayAccess
     */
    public function offsetExists($offset)
    {
        return isset($this->var[$offset]);
    }

    /**
     * @see \ArrayAccess
     */
    public function offsetUnset($offset)
    {
        unset($this->var[$offset]);
        $this->my_count = count($this->var);
    }

    /**
     * @see \ArrayAccess
     */
    public function offsetGet($offset)
    {
        return isset($this->var[$offset]) ? $this->var[$offset] : null;
    }

    /**
     * Vrai si le contenu est vide
     *
     * @return boolean
     */
    public function isEmpty()
    {
        if ($this->my_count <= 0) {
            return true;
        }

        return false;
    }

    /**
     * Passage en erreur
     *
     * @return \ArrayAccess
     */
    public function setError()
    {
        $this->error = true;

        return $this;
    }

    /**
     * Récupération de l'erreur
     *
     * @return boolean
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Retourne un tableau
     *
     * @return array
     */
    public function __toArray()
    {
        $result = array();
        $idx = 0;
        foreach ($this->var as $idx => $line) {
            $result[] = $line->__toFields();
        }
        return $result;
    }

    /**
     * Appel en récursif sur le contenu
     *
     * @param string $p_version
     *
     * @return array
     */
    public function __toJsonApi($p_version)
    {
        $result = array();
        foreach ($this->var as $idx => $line) {
            if (is_object($line) && method_exists($line, '__toJsonApi')) {
                $result[] = $line->__toJsonApi($p_version);
            } else {
                throw new \Exception('Impossible de convertir le résultat en json api, contenu incorrect !');
            }
        }
        return $result;
    }

    /**
     * Sérialise l'object
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->__toArray();
    }

    /**
     * Retourne les données
     *
     * @return array
     */
    public function getDatas()
    {
        return $this->var;
    }
}
