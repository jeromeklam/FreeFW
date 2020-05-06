<?php
namespace FreeFW\Service;

use \FreeFW\Constants as FFCST;

/**
 * Model
 *
 * @author jeromeklam
 */
class Model extends \FreeFW\Core\Service
{

    /**
     * To OpenApi V3
     *
     * @param \FreeFW\Core\Model $p_model
     * @param boolean            $p_asJsonApi
     *
     * @return \FreeFW\OpenApi\V3\Schema
     */
    public function modelToOpenApiV3(\FreeFW\Core\Model $p_model, $p_asJsonApi = true)
    {
        /**
         * @var \FreeFW\OpenApi\V3\Schema $schema
         */
        $schema = \FreeFW\DI\DI::get('\FreeFW\OpenApi\V3\Schema');
        $schema->setType(\FreeFW\OpenApi\V3\Schema::TYPE_OBJECT);
        foreach ($p_model->getProperties() as $propName => $propValue) {
            /**
             * @var \FreeFW\OpenApi\V3\Schema $property
             */
            $property = \FreeFW\DI\DI::get('\FreeFW\OpenApi\V3\Schema');
            $inlinePr = $propValue[FFCST::PROPERTY_OPTIONS];
            if ($p_asJsonApi) {
                if (in_array(FFCST::OPTION_PK, $inlinePr)) {
                    // Primary keys aren't an attribute
                    continue;
                }
                if (in_array(FFCST::OPTION_FK, $inlinePr)) {
                    // Foreign keys are Object, not properties
                    continue;
                }
                if (in_array(FFCST::OPTION_JSONIGNORE, $inlinePr)) {
                    // Information non retournée...
                    continue;
                }
            }
            if (in_array(FFCST::OPTION_BROKER, $inlinePr)) {
                // Broker not in result
                continue;
            }
            switch ($propValue[FFCST::PROPERTY_TYPE]) {
                case FFCST::TYPE_BIGINT:
                    $property->setFormat(\FreeFW\OpenApi\V3\Schema::FORMAT_INT64);
                    break;
                case FFCST::TYPE_INTEGER:
                    $property->setFormat(\FreeFW\OpenApi\V3\Schema::FORMAT_INT32);
                    break;
                case FFCST::TYPE_DECIMAL:
                    $property->setFormat(\FreeFW\OpenApi\V3\Schema::FORMAT_FLOAT);
                    break;
                case FFCST::TYPE_DATE:
                    $property->setFormat(\FreeFW\OpenApi\V3\Schema::FORMAT_DATETIME);
                    break;
                case FFCST::TYPE_DATETIME:
                case FFCST::TYPE_DATETIMETZ:
                    $property->setFormat(\FreeFW\OpenApi\V3\Schema::FORMAT_DATETIME);
                    break;
                case FFCST::TYPE_BOOLEAN:
                    $property->setFormat(\FreeFW\OpenApi\V3\Schema::FORMAT_BOOLEAN);
                    break;
                case FFCST::TYPE_BLOB:
                    $property->setFormat(\FreeFW\OpenApi\V3\Schema::FORMAT_BINARY);
                    break;
                default:
                    $property->setFormat(\FreeFW\OpenApi\V3\Schema::FORMAT_STRING);
                    break;
            }
            if (!in_array(FFCST::OPTION_REQUIRED, $inlinePr)) {
                if ($propValue[FFCST::PROPERTY_TYPE] != FFCST::TYPE_BOOLEAN) {
                    $property->setNullable(true);
                }
            }
            if (array_key_exists(FFCST::PROPERTY_COMMENT, $propValue)) {
                $property->setDescription($propValue[FFCST::PROPERTY_COMMENT]);
            }
            if (array_key_exists(FFCST::PROPERTY_DEFAULT, $propValue)) {
                $property->setDefault($propValue[FFCST::PROPERTY_DEFAULT]);
            } else {
                if ($propValue[FFCST::PROPERTY_TYPE] == FFCST::TYPE_BOOLEAN) {
                    $property->setDefault(false);
                }
            }
            if (array_key_exists(FFCST::PROPERTY_MIN, $propValue)) {
                $property->setMinLength($propValue[FFCST::PROPERTY_MIN]);
            }
            if (array_key_exists(FFCST::PROPERTY_MAX, $propValue)) {
                $property->setMaxLength($propValue[FFCST::PROPERTY_MAX]);
            }
            if (array_key_exists(FFCST::PROPERTY_SAMPLE, $propValue)) {
                $property->setExample($propValue[FFCST::PROPERTY_SAMPLE]);
            }
            if (array_key_exists(FFCST::PROPERTY_ENUM, $propValue)) {
                $property->setEnum($propValue[FFCST::PROPERTY_ENUM]);
            }
            $schema->addProperty($propName, $property);
        }
        return $schema;
    }

    /**
     *
     * @param \FreeFW\Model\Model $p_model
     *
     * @return \FreeFW\OpenApi\V3\Schema
     */
    protected function getJsonApiStandardObject($p_model)
    {
        $type = $p_model->getApiType();
        /**
         * @var \FreeFW\OpenApi\V3\Schema $schema
         */
        $schema = \FreeFW\DI\DI::get('\FreeFW\OpenApi\V3\Schema');
        $schema->setType(\FreeFW\OpenApi\V3\Schema::TYPE_OBJECT);
        /**
         * @var \FreeFW\OpenApi\V3\Schema $schema
         */
        $prpType = \FreeFW\DI\DI::get('\FreeFW\OpenApi\V3\Schema');
        $prpType->setFormat(\FreeFW\OpenApi\V3\Schema::FORMAT_STRING);
        $prpType->setDefault($type);
        $prpType->setReadOnly(true);
        $schema->addProperty('type', $prpType);
        /**
         * @var \FreeFW\OpenApi\V3\Schema $schema
         */
        $prpId = \FreeFW\DI\DI::get('\FreeFW\OpenApi\V3\Schema');
        $prpId->setFormat(\FreeFW\OpenApi\V3\Schema::FORMAT_INT64);
        $schema->addProperty('id', $prpId);
        /**
         * @var \FreeFW\OpenApi\V3\Schema $schema
         */
        $prpAttr = \FreeFW\DI\DI::get('\FreeFW\OpenApi\V3\Schema');
        $prpAttr->setRef('#/components/Schemas/' . $type . '_Attributes');
        $schema->addProperty('attributes', $prpAttr);
        //
        if (method_exists($p_model, 'getSourceComments')) {
            $schema->setDescription($p_model->getSourceComments());
        }
        return $schema;
    }

    /**
     *
     * @param \FreeFW\Model\Model $p_model
     * @param boolean             $p_asJsonApi
     *
     * @return \FreeFW\OpenApi\V3\OpenApi
     */
    public function generateDocumentation(\FreeFW\Model\Model &$p_model, $p_asJsonApi = true)
    {
        $doc = \FreeFW\DI\DI::get('\FreeFW\OpenApi\V3\OpenApi');
        $cls = rtrim(ltrim($p_model->getMdNs(), '\\'), '\\') . '\\' . $p_model->getMdClass();
        $cls = str_replace('\\', '::', $cls);
        /**
         * @var \FreeFW\Core\Model $obj
         */
        $obj = \FreeFW\DI\DI::get($cls);
        if ($obj) {
            $add = '';
            if ($p_asJsonApi) {
                // Main object + attributes
                $doc->addComponentsSchema($obj->getApiType(), $this->getJsonApiStandardObject($obj));
                // Attributes
                $doc->addComponentsSchema($obj->getApiType() . '_Attributes', $this->modelToOpenApiV3($obj, $p_asJsonApi));
            } else {
                $doc->addComponentsSchema($obj->getApiType(), $this->modelToOpenApiV3($obj, $p_asJsonApi));
            }
        }
        echo json_encode($doc);die;
        return $doc;
    }

    /**
     * Generate model
     *
     * @param \FreeFW\Model\Model $p_model
     *
     * @throws \FreeFW\Core\FreeFWException
     *
     * @return boolean
     */
    public function generateModel(\FreeFW\Model\Model &$p_model)
    {
        if (!is_dir($p_model->getMdPath())) {
            $p_model->addError(
                \FreeFW\Core\Error::TYPE_PRECONDITION,
                sprintf('Model::generate, %s is not a directory !', $p_model->getMdPath())
            );
        }
        $ns = rtrim(ltrim($p_model->getMdNs(), '\\'), '\\');
        $p_model->setMdNs($ns);
        $addp = str_replace('\\', '/', $ns);
        $path = rtrim($p_model->getMdPath(), '/');
        if (!is_dir($path . '/' . $addp)) {
            $p_model->addError(
                \FreeFW\Core\Error::TYPE_PRECONDITION,
                sprintf('Model::generate, %s is not a directory !', $path . '/' . $addp)
            );
        }
        if ($p_model->hasErrors()) {
            return false;
        }
        $addBase = false;
        if (is_dir($path . '/' . $addp . '/Base')) {
            $addBase = true;
        }
        $addStorage = false;
        if (is_dir($path . '/' . $addp . '/StorageModel')) {
            $addStorage = true;
        }
        // Check fields if empty.
        if ($p_model->getMdSource() != '') {
            $parts  = explode('::', $p_model->getMdSource());
            $stName = 'default';
            $source = $p_model->getMdSource();
            if (count($parts) > 1) {
                $source = $parts[1];
                $stName = $parts[0];
            }
            /**
             * Storage
             * @var \FreeFW\Interfaces\StorageInterface $storage
             */
            $storage = \FreeFW\DI\DI::getShared('Storage::' . $stName);
            $p_model->setMdFields($storage->getFields($source));
        }
        if ($addStorage) {
            $filename = $path . '/' . $addp . '/StorageModel/' . $p_model->getMdClass() . '.php';
            if (!is_file($filename)) {
                $this->createStorageModelClass($p_model, $filename);
            }
        }
        if ($addBase) {
            $filename = $path . '/' . $addp . '/Base/' . $p_model->getMdClass() . '.php';
            if (!is_file($filename)) {
                $this->createBaseModelClass($p_model, $filename);
            }
        }
        $filename = $path . '/' . $addp . '/' . $p_model->getMdClass() . '.php';
        if (!is_file($filename)) {
            $this->createModelClass($p_model, $filename);
        }
        return true;
    }

    /**
     * Create Base model class
     *
     * @param \FreeFW\Model\Model $p_model
     * @param string $p_filename
     *
     * @return boolean
     */
    protected function createModelClass(\FreeFW\Model\Model &$p_model, string $p_filename)
    {
        $lines   = [];
        $lines[] = '<?php';
        $lines[] = 'namespace ' . $p_model->getMdNs() . ';';
        $lines[] = '';
        $lines[] = 'use \FreeFW\Constants as FFCST;';
        $lines[] = '';
        $lines[] = '/**';
        $lines[] = ' * ' . $p_model->getMdClass();
        $lines[] = ' *';
        $lines[] = ' * @author jeromeklam';
        $lines[] = ' */';
        $lines[] = 'class ' . $p_model->getMdClass() . ' extends \\' .
            $p_model->getMdNs() . '\\Base\\' . $p_model->getMdClass();
        $lines[] = '{';
        $lines[] = '}';
        $lines[] = '';
        file_put_contents($p_filename, implode(PHP_EOL, $lines));
        return true;
    }

    /**
     * Create Base model class
     *
     * @param \FreeFW\Model\Model $p_model
     * @param string $p_filename
     *
     * @return boolean
     */
    protected function createStorageModelClass(\FreeFW\Model\Model &$p_model, string $p_filename)
    {
        $lines   = [];
        $lines[] = '<?php';
        $lines[] = 'namespace ' . $p_model->getMdNs() . '\StorageModel;';
        $lines[] = '';
        $lines[] = 'use \FreeFW\Constants as FFCST;';
        $lines[] = '';
        $lines[] = '/**';
        $lines[] = ' * ' . $p_model->getMdClass();
        $lines[] = ' *';
        $lines[] = ' * @author jeromeklam';
        $lines[] = ' */';
        $lines[] = 'abstract class ' . $p_model->getMdClass() . ' extends \FreeFW\Core\StorageModel';
        $lines[] = '{';
        $lines[] = '';
        $lines[] = '/**';
        $lines[] = ' * Field properties as static arrays';
        $lines[] = ' * @var array';
        $lines[] = ' */';
        // fields
        $fields = $p_model->getMdFields();
        $nbre   = count($fields);
        for ($i=0; $i<$nbre; $i++) {
            $oneField = $fields[$i];
            $lines[] = '    protected static $PRP_' . strtoupper($oneField->getFldName()) . ' = [';
            $lines[] = '        FFCST::PROPERTY_PRIVATE => \'' . $oneField->getFldName() . '\',';
            $lines[] = '        FFCST::PROPERTY_TYPE    => FFCST::' . $oneField->getFldTypeForClass() . ',';
            $lines[] = '        FFCST::PROPERTY_OPTIONS => [' . $oneField->getFldOptionsForClass() . ']';
            $lines[] = '    ];';
        }
        $lines[] = '';
        $lines[] = '    /**';
        $lines[] = '     * get properties';
        $lines[] = '     *';
        $lines[] = '     * @return array[]';
        $lines[] = '     */';
        $lines[] = '    public static function getProperties()';
        $lines[] = '    {';
        $lines[] = '        return [';
        //
        $max = 0;
        for ($i=0; $i<$nbre; $i++) {
            $oneField = $fields[$i];
            if ($max < strlen($oneField->getFldName())) {
                $max = strlen($oneField->getFldName());
            }
        }
        for ($i=0; $i<$nbre; $i++) {
            $oneField = $fields[$i];
            $add      = ',';
            if ($i+1 == $nbre) {
                $add = '';
            }
            $lines[]  = '            \'' . $oneField->getFldName() .
                '\'' . str_repeat(' ', ($max-strlen($oneField->getFldName()))) . ' => self::$PRP_' .
                strtoupper($oneField->getFldName()) . $add;
        }
        $lines[] = '        ];';
        $lines[] = '    }';
        $lines[] = '';
        $lines[] = '    /**';
        $lines[] = '     * Set object source';
        $lines[] = '     *';
        $lines[] = '     * @return string';
        $lines[] = '     */';
        $lines[] = '    public static function getSource()';
        $lines[] = '    {';
        $lines[] = '        return \'' . $p_model->getMdSource() . '\';';
        $lines[] = '    }';
        $lines[] = '}';
        $lines[] = '';
        file_put_contents($p_filename, implode(PHP_EOL, $lines));
        return true;
    }

    /**
     * Create Base model class
     *
     * @param \FreeFW\Model\Model $p_model
     * @param string $p_filename
     *
     * @return boolean
     */
    protected function createBaseModelClass(\FreeFW\Model\Model &$p_model, string $p_filename)
    {
        $lines   = [];
        $lines[] = '<?php';
        $lines[] = 'namespace ' . $p_model->getMdNs() . '\Base;';
        $lines[] = '';
        $lines[] = '/**';
        $lines[] = ' * ' . $p_model->getMdClass();
        $lines[] = ' *';
        $lines[] = ' * @author jeromeklam';
        $lines[] = ' */';
        $lines[] = 'abstract class ' . $p_model->getMdClass() . ' extends \\' .
            $p_model->getMdNs() . '\\StorageModel\\' . $p_model->getMdClass();
        $lines[] = '{';
        /**
         * @var \FreeFW\Model\Field $oneField
         */
        foreach ($p_model->getMdFields() as $idx => $oneField) {
            $lines[] = '';
            $lines[] = '    /**';
            $lines[] = '     * ' . $oneField->getFldName();
            $lines[] = '     * @var ' . $oneField->getFldTypeForPhp();
            $lines[] = '     */';
            $lines[] = '    protected $' . $oneField->getFldName() . ' = null;';
        }
        /**
         * @var \FreeFW\Model\Field $oneField
         */
        foreach ($p_model->getMdFields() as $idx => $oneField) {
            $camel   = \FreeFW\Tools\PBXString::toCamelCase($oneField->getFldName(), true);
            $lines[] = '';
            $lines[] = '    /**';
            $lines[] = '     * Set ' . $oneField->getFldName();
            $lines[] = '     *';
            $lines[] = '     * @param ' . $oneField->getFldTypeForPhp() . ' $p_value';
            $lines[] = '     *';
            $lines[] = '     * @return \\' . $p_model->getMdNs() . '\\' . $p_model->getMdClass();
            $lines[] = '     */';
            $lines[] = '    public function set' . $camel . '($p_value)';
            $lines[] = '    {';
            $lines[] = '        $this->' . $oneField->getFldName() . ' = $p_value;';
            $lines[] = '        return $this;';
            $lines[] = '    }';
            $lines[] = '';
            $lines[] = '    /**';
            $lines[] = '     * Get ' . $oneField->getFldName();
            $lines[] = '     *';
            $lines[] = '     * @return ' . $oneField->getFldTypeForPhp();
            $lines[] = '     */';
            $lines[] = '    public function get' . $camel . '()';
            $lines[] = '    {';
            $lines[] = '        return $this->' . $oneField->getFldName() . ';';
            $lines[] = '    }';
        }
        $lines[] = '}';
        $lines[] = '';
        file_put_contents($p_filename, implode(PHP_EOL, $lines));
        return true;
    }
}
