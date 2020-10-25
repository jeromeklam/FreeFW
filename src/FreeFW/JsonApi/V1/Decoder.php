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
    public function decode(
        \FreeFW\JsonApi\V1\Model\Document $p_document
    ) : \FreeFW\Core\Model {
        if ($p_document->isSimpleResource()) {
            // @todo : recursive with no loop !!
            $resource = $p_document->getData();
            $cls      = $resource->getType();
            $class    = str_replace('_', '::Model::', $cls);
            /**
             * @var \FreeFW\Core\Model $obj
             */
            $obj  = \FreeFW\DI\DI::get($class);
            $obj->setModelBehaviour($obj::MODEL_BEHAVIOUR_API);
            $attr = $resource->getAttributes();
            $rels = $resource->getRelationships();
            if ($rels) {
                $included = [];
                foreach ($p_document->getIncluded() as $oneIncluded) {
                    $cls   = $oneIncluded->getType();
                    $class = str_replace('_', '::Model::', $cls);
                    /**
                     * @var \FreeFW\Core\Model $objI
                     */
                    $objI  = \FreeFW\DI\DI::get($class);
                    $attrI = $oneIncluded->getAttributes();
                    $relsI = $oneIncluded->getRelationships();
                    if ($relsI) {
                        $objI->initWithJson($attrI->__toArray(), $relsI->__toArray(), $included);
                    } else {
                        $objI->initWithJson($attrI->__toArray());
                    }
                    $objI->setApiId($oneIncluded->getId());
                    $included[] = $objI;
                }
                $obj->initWithJson($attr->__toArray(), $rels->__toArray(), $included);
            } else {
                $obj->initWithJson($attr->__toArray());
            }
            $obj->setApiId($resource->getId());
            return $obj;
        }
        return null;
    }
}
