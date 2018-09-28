<?php
namespace FreeSSO\Schema\V1;

class UserQuota extends \Neomerx\JsonApi\Schema\SchemaProvider
{

    /**
     * @see \Neomerx\JsonApi\Schema\SchemaProvider
     */
    protected $resourceType = 'FreeFW_Sso_UserQuota';

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
            'uqu_type'  => $obj->getType(),
            'uqu_quota' => $obj->getQuota()
        ];
        return $arr;
    }
}
