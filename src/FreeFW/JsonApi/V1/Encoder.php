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
     *
     * @return \FreeFW\JsonApi\V1\Model\ResourceObject
     */
    protected function encodeSingleResource(
        \FreeFW\Interfaces\ApiResponseInterface $p_api_response
    ) : \FreeFW\JsonApi\V1\Model\ResourceObject
    {
        $resource = new \FreeFW\JsonApi\V1\Model\ResourceObject(
            $p_api_response->getApiType(),
            $p_api_response->getApiId()
        );
        $fields = $p_api_response->getApiAttributes();
        if ($fields) {
            $attributes = new \FreeFW\JsonApi\V1\Model\AttributesObject($fields);
            $resource->setAttributes($attributes);
        }
        return $resource;
    }

    /**
     * Encode a ApiResponseInterface
     *
     * @param \FreeFW\Interfaces\ApiResponseInterface $p_api_response
     *
     * @return \FreeFW\JsonApi\V1\Model\Document
     */
    public function encode(\FreeFW\Interfaces\ApiResponseInterface $p_api_response
    ) : \FreeFW\JsonApi\V1\Model\Document {
        $document = new \FreeFW\JsonApi\V1\Model\Document();
        if ($p_api_response->hasErrors()) {
            /**
             * @var \FreeFW\Core\Error $oneError
             */
            foreach ($p_api_response->getErrors() as $idx => $oneError) {
                $newError = new \FreeFW\JsonApi\V1\Model\ErrorObject(
                    $oneError->getType(),
                    $oneError->getMessage(),
                    $oneError->getCode()
                );
                $document->addError($newError);
            }
        } else {
            if ($p_api_response instanceof \Iterator) {
                // Composed of multiple objetcs
            } else {
                $resource = $this->encodeSingleResource($p_api_response);
                $document->setData($resource);
            }
        }
        return $document;
    }
}
