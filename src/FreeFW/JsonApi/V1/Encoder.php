<?php
namespace FreeFW\JsonApi\V1;

/**
 * JsonApi encoder
 *
 * @author jeromeklam
 */
class Encoder implements \Psr\Log\LoggerAwareInterface
{

    /**
     * comportements
     */
    use \Psr\Log\LoggerAwareTrait;

    /**
     * Cached data...
     */
    protected static $_cached = [];

    /**
     * Encode single resource
     *
     * @param \FreeFW\Interfaces\ApiResponseInterface $p_api_response
     * @param \FreeFW\JsonApi\V1\Model\IncludedObject $p_included
     * @param \FreeFW\Http\ApiParams                  $p_api_params
     * @param string                                  $p_prefix
     *
     * @return \FreeFW\JsonApi\V1\Model\ResourceObject
     */
    protected function encodeSingleResource(
        \FreeFW\Interfaces\ApiResponseInterface $p_api_response,
        \FreeFW\JsonApi\V1\Model\IncludedObject $p_included,
        \FreeFW\Http\ApiParams $p_api_params,
        $p_prefix = ''
    ) : \FreeFW\JsonApi\V1\Model\ResourceObject {
        $this->logger->debug('FreeFW.JsonApi.Encoder.start.' . $p_api_response->getApiType() . '.' . $p_api_response->getApiId());
        if ($p_prefix != '') {
            $p_prefix = $p_prefix . '.';
        }
        $incTab   = $p_api_params->getInclude();
        $includes = '@@' . implode('@@', $incTab) . '@@';
        $cacheKey = $p_api_response->getApiType() . '.' . $p_api_response->getApiId();
        if (isset(self::$_cached[$cacheKey])) {
            $this->logger->debug('FreeFW.JsonApi.Encoder.cached');
            return self::$_cached[$cacheKey];
        }
        $resource = new \FreeFW\JsonApi\V1\Model\ResourceObject(
            $p_api_response->getApiType(),
            $p_api_response->getApiId(),
            $p_api_response->isSingleElement()
        );
        $fieldsForeignkey = [];
        $relations        = $p_api_response->getApiRelationShips();
        $fields           = $p_api_response->getApiAttributes();
        $this->logger->debug('FreeFW.JsonApi.Encoder.fields');
        if ($fields) {
            $fldTab = $p_api_params->getFieldsFor(rtrim($p_prefix, '.'));
            foreach ($fldTab as $oneFldTab) {
                $jsonIgnore = false;
                $allFields  = false;
                switch ($oneFldTab[0]) {
                    case '-' :
                        $jsonIgnore = true;
                        $fldTab = substr($oneFldTab, 1);
                        $allFields = (empty($fldTab));
                        break;
                    case '+' :
                        $jsonIgnore = false;
                        $fldTab = substr($oneFldTab, 1);
                        if (empty($fldTab)) {
                            continue 2;
                        }
                        break;
                    default:
                        $jsonIgnore = false;
                        $fldTab = $oneFldTab;
                        break;
                }
                $found = false;
                foreach ($fields as $key => $field) {
                    if ($allFields) {
                        $found = true;
                        if (self::isForeignkey($field->getName())) {
                            $fieldsForeignkey[$field->getName()] = $jsonIgnore;
                        } else {
                            $field->setJsonIgnore($jsonIgnore);
                        }
                    } else {
                        if ($p_prefix . $field->getName() === $p_prefix . $fldTab) {
                            $found = true;
                            if (self::isForeignkey($field->getName())) {
                                $fieldsForeignkey[$field->getName()] = $jsonIgnore;
                            } else {
                                $field->setJsonIgnore($jsonIgnore);
                            }
                            break;
                        }
                    }
                }
                if (!$found) { // peut être qu'il s'agit du nom d'une relation !
                    foreach ($relations as $relation) {
                        if ($relation->getName() === $fldTab) {
                            $fieldsForeignkey[$relation->getPropertyName()] = $jsonIgnore;
                        }
                    }
                }
            }
            $attributes = new \FreeFW\JsonApi\V1\Model\AttributesObject($fields);
            if (method_exists($p_api_response, 'getTs')) {
                $tsAttribute = new \FreeFW\JsonApi\V1\Model\AttributeObject('__ts', $p_api_response->getTs());
                $attributes->addAttribute($tsAttribute);
            }
            $resource->setAttributes($attributes);
        }
        $this->logger->debug('FreeFW.JsonApi.Encoder.relations');
        $relationShips = new \FreeFW\JsonApi\V1\Model\RelationshipsObject();
        if ($relations) {
            foreach ($relations as $relation) {
                if (strpos($includes, '@@' . $p_prefix . $relation->getName() . '@@') !== false) {
                    $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($relation->getName(), true);
                    if (method_exists($p_api_response, $getter)) {
                        $model  = $p_api_response->$getter();
                        if ($model && $model instanceof \FreeFW\Interfaces\ApiResponseInterface) {
                            if ($model->isSingleElement()) {
                                foreach (array_keys($incTab, $relation->getName(), true) as $key) {
                                    unset($incTab[$key]);
                                }
                                $resourceRel = new \FreeFW\JsonApi\V1\Model\ResourceObject(
                                    $model->getApiType(),
                                    $model->getApiId(),
                                    $model->isSingleElement()
                                );
                                $relationShips->addRelation($relation->getName(), $resourceRel);
                                $included = $this->encodeSingleResource(
                                    $model,
                                    $p_included,
                                    $p_api_params,
                                    $p_prefix . $relation->getName()
                                );
                                $p_included->addIncluded($included);
                            } else {
                                foreach (array_keys($incTab, $relation->getName(), true) as $key) {
                                    unset($incTab[$key]);
                                }
                                foreach ($model as $oneModel) {
                                    $resourceRel = new \FreeFW\JsonApi\V1\Model\ResourceObject(
                                        $oneModel->getApiType(),
                                        $oneModel->getApiId(),
                                        $oneModel->isSingleElement()
                                    );
                                    $relationShips->addRelation($relation->getName(), $resourceRel, true);
                                    $included = $this->encodeSingleResource(
                                        $oneModel,
                                        $p_included,
                                        $p_api_params,
                                        $p_prefix . $relation->getName()
                                    );
                                    $p_included->addIncluded($included);
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
                    }
                } else {
                    if ($relation->getType() == \FreeFW\JsonApi\V1\Model\RelationshipObject::ONE_TO_ONE) {
                        $getter      = 'get' . \FreeFW\Tools\PBXString::toCamelCase($relation->getPropertyName(), true);
                        $resourceRel = new \FreeFW\JsonApi\V1\Model\ResourceObject(
                            str_replace('::Model::', '_', $relation->getModel()),
                            $p_api_response->$getter(),
                            true
                        );
                        $relationShips->addRelation($relation->getName(), $resourceRel);
                    }
                }
            }
        }
        $this->logger->debug('FreeFW.JsonApi.Encoder.extra');
        // Extra included here...
        foreach ($incTab as $include) {
            $parts = explode('.', $include);
            $posi  = explode('.', $p_prefix);
            if (count($parts) == count($posi)) {
                $elem   = $parts[count($posi)-1];
                if ($elem && $elem != '') {
                    $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($elem, true);
                    if (method_exists($p_api_response, $getter)) {
                        $result = $p_api_response->$getter();
                        if ($result instanceof \FreeFW\Core\Model) {
                            $resourceRel = new \FreeFW\JsonApi\V1\Model\ResourceObject(
                                $result->getApiType(),
                                $result->getApiId(),
                                $result->isSingleElement()
                            );
                            $relationShips->addRelation($elem, $resourceRel);
                            $included = $this->encodeSingleResource(
                                $result,
                                $p_included,
                                $p_api_params,
                                $p_prefix . $elem
                            );
                            $p_included->addIncluded($included);
                        } else {
                            if ($result !== null) {
                                $specAttribute = new \FreeFW\JsonApi\V1\Model\AttributeObject($elem, $result);
                                $attributes->addAttribute($specAttribute);
                                //throw new \Exception ($elem . ' not a instanceof Model');
                            }
                        }
                    }
                }
            }
        }
        $this->logger->debug('FreeFW.JsonApi.Encoder.set');
        $resource->setRelationShips($relationShips);
        self::$_cached[$cacheKey] = $resource;
        $this->logger->debug('FreeFW.JsonApi.Encoder.end');
        // Done
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
        $resource = $this->encodeSingleResource($p_api_response, $included, $p_api_params);
        $document
            ->setData($resource)
            ->setIncluded($included)
        ;
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
        self::$_cached = [];
        $count = null;
        if (method_exists($p_api_response, 'getTotalCount')) {    // @todo : use interface instead
            $count = $p_api_response->getTotalCount();
        }
        $document = new \FreeFW\JsonApi\V1\Model\Document(null, ['count' => $count]);
        $included = new \FreeFW\JsonApi\V1\Model\IncludedObject();
        foreach ($p_api_response as $idx => $oneElement) {
            $resource = $this->encodeSingleResource($oneElement, $included, $p_api_params);
            $document
                ->addData($resource)
                ->setIncluded($included)
            ;
        }
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
        }
        return $document;
    }

    /**
     * Foreign key ??
     * @param string $p
     *
     * @return boolean
     */
    protected static function isForeignkey($p)
    {
        return (bool)(substr($p, -3) == '_id');
    }
}
