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
        $this->relationships = [];
        foreach ($p_relations as $key => $value) {
            if ($value instanceof \FreeFW\JsonApi\V1\Model\RelationshipObject) {
                $this->addRelation($key, $value);
            } else {
                if (is_object($value) && array_key_exists('data', $value)) {
                    $data = $value->data;
                    if (is_array($data)) {
                        var_export('decoder . @todo');die;
                    } else {
                        $relation = new \FreeFW\JsonApi\V1\Model\RelationshipObject($key);
                        $cls   = $data->type;
                        $class = str_replace('_', '::Model::', $cls);
                        $relation->setModel($class);
                        $relation->addValue($data->id);
                    }
                    $this->addRelation($key, $relation);
                }
            }
        }
        $p_relations;
    }

    /**
     * Add a relation
     * 
     * @param \FreeFW\JsonApi\V1\Model\RelationshipObject $p_relation
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
                        'data' => [
                            'id'   => $rel->getId(),
                            'type' => $rel->getType()
                        ]
                    ];
                }
            } else {
                $rels[$name] = [
                    'data' => [
                        'id'   => $relation->getId(),
                        'type' => $relation->getType()
                    ]
                ];
            }
        }
        return $rels;
    }

    /**
     * Convert to array
     * 
     * @return array
     */
    public function __toArray()
    {
        $arr = [];
        foreach ($this->relationships as $key => $value) {
            $arr[$value->getName()] = [
                'name'   => $value->getName(),
                'type'   => $value->getType(),
                'values' => $value->getValues()
            ];
        }
        return $arr;
    }
}
