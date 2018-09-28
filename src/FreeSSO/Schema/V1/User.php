<?php
namespace FreeSSO\Schema\V1;

class User extends \Neomerx\JsonApi\Schema\SchemaProvider
{

    /**
     * @see \Neomerx\JsonApi\Schema\SchemaProvider
     */
    protected $resourceType = 'FreeFW_Sso_User';

    /**
     * @see \Neomerx\JsonApi\Schema\SchemaProvider
     */
    public function getId($obj)
    {
        return $obj->getUserId();
    }

    /**
     * @see \Neomerx\JsonApi\Schema\SchemaProvider
     */
    public function getAttributes($obj)
    {
        $arr = [
            'user_id' => $obj->getUserId(),
            'user_login' => $obj->getUserLogin(),
            'user_active' => $obj->getUserActive(),
            'user_email' => $obj->getUserEmail(),
            'user_first_name' => $obj->getUserFirstName(),
            'user_last_name' => $obj->getUserLastName(),
            'user_title' => $obj->getUserTitle(),
            'user_roles' => $obj->getUserRoles(),
            'user_type' => $obj->getUserType(),
            'user_ips' => $obj->getUserIps(),
            'user_last_update' => $obj->getUserLastUpdate(),
            'user_preferred_language' => $obj->getUserPreferredLanguage(),
            'user_avatar' => $obj->getUserAvatar(),
            'user_cache' => $obj->getUserCache(),
            'user_key' => $obj->getUserKey(),
            'confirm' => $obj->getConfirm(),
        ];
        $cpl = \FreeSSO\Model\User::getJsonComplement($obj);
        $arr = array_merge($arr, $cpl);
        return $arr;
    }

    /**
     * @see \Neomerx\JsonApi\Schema\SchemaProvider
     */
    public function getResourceLinks($resource)
    {
        $links = [];
        $url = \FreeFW\Router\Route::renderHrefForObj('/v1/sso/user/:user_id', $resource);
        $links['self']  = new \Neomerx\JsonApi\Document\Link($url, null, true);
        return $links;
    }

    /**
     * @see \Neomerx\JsonApi\Schema\SchemaProvider
     */
    public function getRelationships($resource, $isPrimary, array $includeList)
    {
        $rels = [];
        if (array_key_exists('accounts', $includeList)) {
            $rels['accounts'] = [self::DATA => $resource->getUserBrokerAccounts()];
        }
        if (array_key_exists('smsquota', $includeList)) {
            $rels['smsquota'] = [self::DATA => $resource->getSmsQuota()];
        }
        return $rels;
    }

    /**
     * Get model from datas
     *
     * @param array $datas
     *
     * @return \FreeSSO\Model\User
     */
    public function getModel($datas, $p_orig = null)
    {
        $model = false;
        if (array_key_exists('attributes', $datas)) {
            $attributes = $datas['attributes'];
            if ($p_orig === null) {
                $model = \FreeSSO\Model\User::getInstance($attributes);
            } else {
                $model = $p_orig->getFromRecord($attributes);
            }
            if (array_key_exists('user_key', $attributes)) {
                $model->setUserKey($attributes['user_key']);
            }
        }
        return $model;
    }
}
