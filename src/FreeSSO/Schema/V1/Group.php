<?php
namespace FreeSSO\Schema\V1;

class Group extends \Neomerx\JsonApi\Schema\SchemaProvider
{

    /**
     * @see \Neomerx\JsonApi\Schema\SchemaProvider
     */
    protected $resourceType = 'FreeFW_Sso_Group';

    /**
     * @see \Neomerx\JsonApi\Schema\SchemaProvider
     */
    public function getId($obj)
    {
        return $obj->getGrpId();
    }

    /**
     * @see \Neomerx\JsonApi\Schema\SchemaProvider
     */
    public function getAttributes($obj)
    {
        $arr = [
            'grp_id' => $obj->getGrpId(),
            'grp_name' => $obj->getGrpName(),
            'grp_active' => $obj->getGrpActive(),
            'grp_ips' => $obj->getGrpIps(),
            'grp_key' => $obj->getGrpKey(),
            'grp_verif_key' => $obj->getGrpVerifKey(),
            'grp_verif_prefix' => $obj->getGrpVerifPrefix(),
        ];
        return $arr;
    }

    /**
     * @see \Neomerx\JsonApi\Schema\SchemaProvider
     */
    public function getResourceLinks($resource)
    {
        $links = [];
        $url = \FreeFW\Router\Route::renderHrefForObj('/v1/sso/group/:grp_id', $resource);
        $links['self']  = new \Neomerx\JsonApi\Document\Link($url, null, true);
        return $links;
    }

}
