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
     * @param array $p_included
     */
    public function __construct(array $p_relations = [], $p_included = [])
    {
        $this->relationships = [];
        foreach ($p_relations as $key => $value) {
            if ($value instanceof \FreeFW\JsonApi\V1\Model\RelationshipObject) {
                $this->addRelation($key, $value);
            } else {
                if (is_object($value) && property_exists($value, 'data')) {
                    $data = $value->data;
                    if (is_array($data)) {
                        $relation = new \FreeFW\JsonApi\V1\Model\RelationshipObject($key);
                        $relation->setType(\FreeFW\JsonApi\V1\Model\RelationshipObject::ONE_TO_MANY);
                        foreach ($data as $oneRel) {
                            if (isset($oneRel->type) && isset($oneRel->id)) {
                                $cls   = $oneRel->type;
                                $class = str_replace('_', '::Model::', $cls);
                                $relation->setModel($class);
                                $relation->addValue($oneRel->id);
                            }
                        }
                        $this->addRelation($key, $relation);
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
    public function addRelation($p_name, $p_relation, $p_array = false)
    {
        if ($this->relationships === null) {
            $this->relationships = [];
        }
        if ($p_array) {
            if (!array_key_exists($p_name, $this->relationships)) {
                $this->relationships[$p_name] = [];
            }
            $this->relationships[$p_name][] = $p_relation;
        } else {
            if (!array_key_exists($p_name, $this->relationships)) {
                $this->relationships[$p_name] = $p_relation;
            }
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
                $rels[$name]['data'] = [];
                foreach ($relation as $rel) {
                    $rels[$name]['data'][] = [
                        'id'    => '' . $rel->getId(),
                        'type'  => $rel->getType(),
                    ];
                }
            } else {
                $rels[$name] = [
                    'data' => [
                        'id'   => '' . $relation->getId(),
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
                'model'  => $value->getModel(),
                'values' => $value->getValues()
            ];
        }
        return $arr;
    }
}
