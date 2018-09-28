<?php
namespace FreeSSO\Schema\V1;

class Broker extends \Neomerx\JsonApi\Schema\SchemaProvider
{

    /**
     * @see \Neomerx\JsonApi\Schema\SchemaProvider
     */
    protected $resourceType = 'FreeFW_Sso_Broker';

    /**
     * @see \Neomerx\JsonApi\Schema\SchemaProvider
     */
    public function getId($obj)
    {
        return $obj->getBrkId();
    }

    /**
     * @see \Neomerx\JsonApi\Schema\SchemaProvider
     */
    public function getAttributes($obj)
    {
        $arr = [
            'brk_id' => $obj->getBrkId(),
            'dom_id' => $obj->getDomId(),
            'brk_key' => $obj->getBrkKey(),
            'brk_name' => $obj->getBrkName(),
            'brk_certificate' => $obj->getBrkCertificate(),
            'brk_active' => $obj->getBrkActive(),
            'brk_ts' => $obj->getBrkTs(),
            'brk_api_scope' => $obj->getBrkApiScope(),
            'brk_users_scope' => $obj->getBrkUsersScope(),
            'brk_ips' => $obj->getBrkIps(),
        ];
        return $arr;
    }

    /**
     * @see \Neomerx\JsonApi\Schema\SchemaProvider
     */
    public function getResourceLinks($resource)
    {
        $links = [];
        return $links;
    }

}
