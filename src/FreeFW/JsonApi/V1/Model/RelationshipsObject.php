<?php
namespace FreeFW\JsonApi\V1\Model;

/**
 * Relationships object
 *
 * @author jeromeklam
 */
class RelationshipsObject implements \Countable, \JsonSerializable
{

    /**
     * Relationships
     * @var array | null
     */
    protected $relationships = [];
    
    /**
     * Constructor
     *
     * @param array $p_relations
     */
    public function __construct(array $p_relations = [])
    {
        $this->relationships = $p_relations;
    }

    /**
     * Add a relation
     * 
     * @param unknown $p_relation
     * 
     * @return \FreeFW\JsonApi\V1\Model\RelationshipsObject
     */
    public function addRelation($p_name, $p_relation)
    {
        if ($this->relationships === null) {
            $this->relationships = [];
        }
        if (array_key_exists($p_name, $this->relationships)) {
            if (!is_array($this->relationships[$p_name])) {
                $temp = $this->relationships[$p_name];
                $this->relationships[$p_name] = [];
                $this->relationships[$p_name][] = $temp;
            }
            $this->relationships[$p_name][] = $p_relation;
        } else {
            $this->relationships[$p_name] = $p_relation;
        }
        return $this;
    }
    
    public function getRelations()
    {
        return $this->relationships;
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \Countable::count()
     */
    public function count()
    {
        return count($this->relationships);
    }

    /**
     * 
     * {@inheritDoc}
     * @see \JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        $rels = [];
        foreach ($this->relationships as $name => $relation) {
            if (is_array($relation)) {
                $rels[$name] = [];
                foreach ($relation as $rel) {
                    $rels[$name][] = [
                        'id'   => $rel->getId(),
                        'type' => $rel->getType()
                    ];
                }
            } else {
                $rels[$name] = [
                    'id'   => $relation->getId(),
                    'type' => $relation->getType()
                ];
            }
        }
        return $rels;
    }
}
