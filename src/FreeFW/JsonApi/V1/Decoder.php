<?php
namespace FreeFW\JsonApi\V1;

/**
 * JsonApi decoder
 *
 * @author jeromeklam
 */
class Decoder
{

    /**
     * Decode a ApiResponseInterface
     *
     * @param \FreeFW\JsonApi\V1\Model\Document $p_document
     *
     * @return \FreeFW\Core\Model
     */
    public function decode(\FreeFW\JsonApi\V1\Model\Document $p_document
    ) : \FreeFW\Core\Model {
        if ($p_document->isSimpleResource()) {
            $resource = $p_document->getData();
            $cls      = $resource->getType();
            $class    = str_replace('_', '::Model::', $cls);
            /**
             * @var \FreeFW\Core\Model $obj
             */
            $obj  = \FreeFW\DI\DI::get($class);
            $attr = $resource->getAttributes();
            $obj->initWithJson($attr->__toArray());
            return $obj;
        }
        die('decoder');
        return null;
    }
}
