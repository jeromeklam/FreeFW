<?php
namespace FreeFW\Model;

use \FreeFW\Interfaces\StorageModel as ModelInterface;

/**
 * Classe de base de gestion avec un SGBD
 *
 * @author jeromeklam
 * @package Storage
 * @package Abstract
 */
abstract class AbstractNestedTreeStorage extends \FreeFW\Model\AbstractPDOStorage implements
    ModelInterface,
    \JsonSerializable
{

    /**
     * Id
     * @var string
     */
    protected $nested_id = false;

    /**
     * Left
     * @var string
     */
    protected $nested_left = false;

    /**
     * Right
     * @var string
     */
    protected $nested_right = false;

    /**
     * Level
     * @var string
     */
    protected $nested_level = false;

    /**
     * Parent, arbre standard
     * @var string
     */
    protected $nested_parent = false;

    /**
     * Position, arbre standard
     * @var string
     */
    protected $nested_position = false;

    /**
     * Affectation des champs utilisés
     *
     * @param string $p_id
     * @param string $p_left
     * @param string $p_right
     * @param string $p_level
     * @param string $p_parent
     * @param string $p_position
     *
     * @return \FreeFW\Model\AbstractNestedTreeStorage
     */
    public function setNestedTree($p_id, $p_left, $p_right, $p_level, $p_parent = false, $p_position = false)
    {
        $this->nested_id       = $p_id;
        $this->nested_left     = $p_left;
        $this->nested_right    = $p_right;
        $this->nested_level    = $p_level;
        $this->nested_parent   = $p_parent;
        $this->nested_position = $p_position;
        return $this;
    }

    /**
     * A des enfants ?
     *
     * @return boolean
     */
    public function hasChild()
    {
        $get1 = 'get' . \FreeFW\Tools\PBXString::toCamelCase($this->nested_left, true);
        $get2 = 'get' . \FreeFW\Tools\PBXString::toCamelCase($this->nested_right, true);
        $val1 = intval($this->$get1());
        $val2 = intval($this->$get1());
        if (($val2 - $val1)>1) {
            return true;
        }
        return false;
    }

    /**
     * Retourne le niveau
     *
     * @return number
     */
    public function getLevel()
    {
        $get1 = 'get' . \FreeFW\Tools\PBXString::toCamelCase($this->nested_level, true);
        return intval($this->$get1());
    }

    /**
     * Retourne l'identifiant
     *
     * @return number
     */
    public function getNodeId()
    {
        $get1 = 'get' . \FreeFW\Tools\PBXString::toCamelCase($this->nested_id, true);
        return $this->$get1();
    }

    /**
     * @see AbstractPDOStorage
     */
    public function beforeSave()
    {
        $id = $this->getNodeId();
        if ($id === null || $id == 0) {
            $fields = $this->getColumnDescByField();
            if ($this->nested_parent !== false && array_key_exists($this->nested_parent, $fields)) {
                $getPa  = $fields[$this->nested_parent]['getter'];
                $getPo  = $fields[$this->nested_position]['getter'];
                $setPo  = $fields[$this->nested_position]['setter'];
                $setLe  = $fields[$this->nested_left]['setter'];
                $setRi  = $fields[$this->nested_right]['setter'];
                $setLv  = $fields[$this->nested_level]['setter'];
                $getLe  = $fields[$this->nested_left]['getter'];
                $getRi  = $fields[$this->nested_right]['getter'];
                $getLv  = $fields[$this->nested_level]['getter'];
                $result = $this->getFirst(
                    array(
                        $this->nested_parent => $this->$getPa()
                    ),
                    array(
                        $this->nested_position => self::SORT_DESC
                    )
                );
                if ($result) {
                    $position = $result->$getPo();
                    $position = $position + 1;
                    $this->$setPo($position);
                    // Dernier "frère"
                    $lastRi = $result->$getRi();
                    $lastLv = $result->$getLv();
                    $this
                        ->$setLe($lastRi + 1)
                        ->$setRi($lastRi + 2)
                        ->$setLv($lastLv)
                    ;
                    // Il faut tout décaller....
                    // Right + 2 where right > lastRi
                    // Left  + 2 where left  > lastRi
                    $this->update(
                        array($this->nested_right => array(self::UPDATE_ADD => 2)),
                        array($this->nested_right => array(self::FIND_GREATER => $lastRi))
                    );
                    $this->update(
                        array($this->nested_left => array(self::UPDATE_ADD => 2)),
                        array($this->nested_left => array(self::FIND_GREATER => $lastRi))
                    );
                } else {
                    $position = 1;
                    $this->$setPo($position);
                    $parent = $this->getById($this->$getPa());
                    if ($parent) {
                        // Nouveau "fils"
                        $lastRi = $result->$getLe();
                        $lastLv = $result->$getLv() + 1;
                        $this
                            ->$setLe($lastRi + 1)
                            ->$setRi($lastRi + 2)
                            ->$setLv($lastLv)
                        ;
                        // Il faut tout décaller....
                        // Right + 2 where right > lastRi
                        // Left  + 2 where left  > lastRi
                        $this->update(
                            array($this->nested_right => array(self::UPDATE_ADD => 2)),
                            array($this->nested_right => array(self::FIND_GREATER => $lastRi))
                        );
                        $this->update(
                            array($this->nested_left => array(self::UPDATE_ADD => 2)),
                            array($this->nested_left => array(self::FIND_GREATER => $lastRi))
                        );
                    } else {
                        $this
                            ->$setLe(1)
                            ->$setRi(2)
                            ->$setLv(1)
                        ;
                    }
                }
            }
        }
        return true;
    }

    /**
     * Après la suppression
     *
     * @return boolean
     */
    public function afterDelete()
    {
        $fields = $this->getColumnDescByField();
        if ($this->nested_parent !== false && array_key_exists($this->nested_parent, $fields)) {
            $getPa  = $fields[$this->nested_parent]['getter'];
            $getPo  = $fields[$this->nested_position]['getter'];
            $setPo  = $fields[$this->nested_position]['setter'];
            $setLe  = $fields[$this->nested_left]['setter'];
            $setRi  = $fields[$this->nested_right]['setter'];
            $setLv  = $fields[$this->nested_level]['setter'];
            $getLe  = $fields[$this->nested_left]['getter'];
            $getRi  = $fields[$this->nested_right]['getter'];
            $getLv  = $fields[$this->nested_level]['getter'];
            //
            $width  = $this->$getRi() - $this->$getLe() + 1;
            $lastLe = $this->$getLe();
            $lastRi = $this->$getRi();
            // Suppression des noeux left entre left et right
            // Update right - width where right > lastRi
            // Update leftt - width where left > lastRi
            $this->delete(
                array($this->nested_left => array(self::FIND_BETWEEN => array($lastLe, $lastRi)))
            );
            $this->update(
                array($this->nested_right => array(self::UPDATE_REMOVE => $width)),
                array($this->nested_right => array(self::FIND_GREATER => $lastRi))
            );
            $this->update(
                array($this->nested_left => array(self::UPDATE_REMOVE => $width)),
                array($this->nested_left => array(self::FIND_GREATER => $lastRi))
            );
        }
        return true;
    }
    /**
     * Retourne le nom du noeud
     *
     * @return string
     */
    abstract public function getNodeName();

    /**
     * Retourne le code du noeud
     *
     * @return string
     */
    abstract public function getNodeCode();
}
