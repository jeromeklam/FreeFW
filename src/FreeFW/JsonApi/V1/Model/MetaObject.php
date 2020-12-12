<?php
namespace FreeFW\JsonApi\V1\Model;

/**
 * Meta object
 *
 * @author jeromeklam
 */
class MetaObject
{

    /**
     * Meta
     * @var array
     */
    protected $metas = [
        'copyright' => 'Copyright jeromeklam 2018',
        'authors' => [
            'Jérôme KLAM <jeromeklam@free.fr>'
        ]
    ];

    /**
     * Add ùeta
     *
     * @param string $p_name
     * @param string $p_value
     *
     * @return \FreeFW\JsonApi\V1\Model\MetaObject
     */
    public function addMeta($p_name, $p_value)
    {
        $this->metas[$p_name] = $p_value;
        return $this;
    }

    /**
     * Convert to array
     *
     * @return array
     */
    public function __toArray()
    {
        return $this->metas;
    }
}
