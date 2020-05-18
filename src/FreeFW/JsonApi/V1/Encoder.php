<?php
namespace FreeFW\JsonApi\V1;

/**
 * JsonApi encoder
 *
 * @author jeromeklam
 */
class Encoder
{

    /**
     * Encode single resource
     *
     * @param \FreeFW\Interfaces\ApiResponseInterface $p_api_response
     * @param \FreeFW\JsonApi\V1\Model\IncludedObject $p_included
     * @param \FreeFW\Http\ApiParams                  $p_api_params
     *
     * @return \FreeFW\JsonApi\V1\Model\ResourceObject
     */
    protected function encodeSingleResource(
        \FreeFW\Interfaces\ApiResponseInterface $p_api_response,
        \FreeFW\JsonApi\V1\Model\IncludedObject $p_included,
        \FreeFW\Http\ApiParams $p_api_params
    ) : \FreeFW\JsonApi\V1\Model\ResourceObject {
        $incTab   = $p_api_params->getInclude();
        $includes = '@@' . implode('@@', $incTab) . '@@';
        $includes = str_replace('.', '@@', $includes);
        $resource = new \FreeFW\JsonApi\V1\Model\ResourceObject(
            $p_api_response->getApiType(),
            $p_api_response->getApiId(),
            $p_api_response->isSingleElement()
        );
        $fields = $p_api_response->getApiAttributes();
        if ($fields) {
            $attributes = new \FreeFW\JsonApi\V1\Model\AttributesObject($fields);
            $resource->setAttributes($attributes);
        }
        $relations = $p_api_response->getApiRelationShips();
        $continue  = !$p_included->incomingExists($resource);
        if ($relations && $continue) {
            $p_included->addIncoming($resource);
            $relationShips = new \FreeFW\JsonApi\V1\Model\RelationshipsObject();
            foreach ($relations as $relation) {
                $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($relation->getName(), true);
                if (method_exists($p_api_response, $getter)) {
                    $model  = $p_api_response->$getter();
                    if ($model && $model instanceof \FreeFW\Interfaces\ApiResponseInterface) {
                        if ($model->isSingleElement()) {
                            if (strpos($includes, '@@' . $relation->getName() . '@@') !== false) {
                                unset($incTab[$relation->getName()]);
                                $resourceRel = new \FreeFW\JsonApi\V1\Model\ResourceObject(
                                    $model->getApiType(),
                                    $model->getApiId(),
                                    $model->isSingleElement()
                                );
                                $relationShips->addRelation($relation->getName(), $resourceRel);
                                $included = $this->encodeSingleResource($model, $p_included, $p_api_params);
                                $p_included->addIncluded($included);
                            }
                        } else {
                            if (strpos($includes, '@@' . $relation->getName() . '@@') !== false) {
                                unset($incTab[$relation->getName()]);
                                foreach ($model as $oneModel) {
                                    $resourceRel = new \FreeFW\JsonApi\V1\Model\ResourceObject(
                                        $oneModel->getApiType(),
                                        $oneModel->getApiId(),
                                        $oneModel->isSingleElement()
                                    );
                                    $relationShips->addRelation($relation->getName(), $resourceRel, true);
                                    $included = $this->encodeSingleResource($oneModel, $p_included, $p_api_params);
                                    $p_included->addIncluded($included);
                                }
                            }
                        }
                    } else {
                        if ($relation->getPropertyName() != '' && $relation->getModel() != '') {
                            $relModel = \FreeFW\DI\DI::get($relation->getModel());
                            $getter   = 'get' . \FreeFW\Tools\PBXString::toCamelCase($relation->getPropertyName(), true);
                            if (method_exists($p_api_response, $getter)) {
                                $resourceRel = new \FreeFW\JsonApi\V1\Model\ResourceObject(
                                    $relModel->getApiType(),
                                    $p_api_response->$getter(),
                                    $relModel->isSingleElement()
                                );
                                $relationShips->addRelation($relation->getName(), $resourceRel);
                            }
                        }
                    }
                } else {
                    // Auto getters....
                }
            }
            $resource->setRelationShips($relationShips);
        }
        /*
        foreach ($incTab as $include) {
            $parts = explode('.', $include);
            while (count($parts)>0) {
                $elem   = array_shift($parts);
                $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($elem, true);
                if (method_exists($p_api_response, $getter)) {
                    $result = $p_api_response->$getter();
                }
            }
        }*/
        return $resource;
    }

    /**
     * Encode a ApiResponseInterface
     *
     * @param \FreeFW\Interfaces\ApiResponseInterface $p_api_response
     * @param \FreeFW\Http\ApiParams                  $p_api_params
     *
     * @return \FreeFW\JsonApi\V1\Model\Document
     */
    public function encode(
        \FreeFW\Interfaces\ApiResponseInterface $p_api_response,
        \FreeFW\Http\ApiParams $p_api_params
    ) : \FreeFW\JsonApi\V1\Model\Document {
        $document = new \FreeFW\JsonApi\V1\Model\Document();
        $included = new \FreeFW\JsonApi\V1\Model\IncludedObject();
        if ($p_api_response->hasErrors()) {
            /**
             * @var \FreeFW\Core\Error $oneError
             */
            foreach ($p_api_response->getErrors() as $idx => $oneError) {
                $newError = new \FreeFW\JsonApi\V1\Model\ErrorObject(
                    $oneError->getType(),
                    $oneError->getMessage(),
                    $oneError->getCode(),
                    $oneError->getField()
                );
                $document->addError($newError);
            }
        } else {
            $resource = $this->encodeSingleResource($p_api_response, $included, $p_api_params);
            $document
                ->setData($resource)
                ->setIncluded($included)
            ;
        }
        return $document;
    }

    /**
     * Encode multiple objects
     *
     * @param \Iterator              $p_api_response
     * @param \FreeFW\Http\ApiParams $p_api_params
     *
     * @return \FreeFW\JsonApi\V1\Model\Document
     */
    public function encodeList(\Iterator $p_api_response, \FreeFW\Http\ApiParams $p_api_params)
    {
        $document = new \FreeFW\JsonApi\V1\Model\Document();
        $included = new \FreeFW\JsonApi\V1\Model\IncludedObject();
        foreach ($p_api_response as $idx => $oneElement) {
            $resource = $this->encodeSingleResource($oneElement, $included, $p_api_params);
            $document
                ->addData($resource)
                ->setIncluded($included)
            ;
        }
        return $document;
    }
}
