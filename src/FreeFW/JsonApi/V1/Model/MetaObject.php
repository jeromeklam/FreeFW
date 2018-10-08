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
     * Convert to array
     *
     * @return array
     */
    public function __toArray()
    {
        return $this->metas;
    }
}
