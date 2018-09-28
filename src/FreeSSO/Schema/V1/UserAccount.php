<?php
namespace FreeSSO\Schema\V1;

class UserAccount extends \Neomerx\JsonApi\Schema\SchemaProvider
{

    /**
     * @see \Neomerx\JsonApi\Schema\SchemaProvider
     */
    protected $resourceType = 'FreeFW_Sso_UserAccount';

    /**
     * @see \Neomerx\JsonApi\Schema\SchemaProvider
     */
    public function getId($obj)
    {
        return $obj->getUserId() . '@' . $obj->getType();
    }

    /**
     * @see \Neomerx\JsonApi\Schema\SchemaProvider
     */
    public function getAttributes($obj)
    {
        $arr = [
            'uac_type'     => $obj->getType(),
            'uac_provider' => $obj->getProvider(),
            'uac_key1'     => $obj->getKey1(),
            'uac_key2'     => $obj->getKey2()
        ];
        return $arr;
    }
}
