<?php
/**
 * Classe de gestion json-api
 *
 * @author jeromeklam
 * @package Event
 * @category Core
 */
namespace FreeFW\JsonApi;

/**
 * Hook pour les tableaux...
 *
 * @author jeromeklam
 *
 */
abstract class SchemaProvider extends \Neomerx\JsonApi\Schema\SchemaProvider
{

    /**
     * Appel du schéma pour les éléments du tableau
     *
     * @param array $arr
     */
    protected function getArrayAttributes($arr)
    {
        $newArr = [];
        $o = $this->createRelationshipObject($this, 'factures', [self::DATA => $arr]);
        return $newArr;
    }
}
