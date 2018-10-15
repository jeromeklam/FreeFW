<?php
namespace FreeFW\Service;

/**
 * Model
 *
 * @author jeromeklam
 */
class Model extends \FreeFW\Core\Service
{

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
            $this->createStorageModelClass($p_model, $filename);
        }
        if ($addBase) {
            $filename = $path . '/' . $addp . '/Base/' . $p_model->getMdClass() . '.php';
            $this->createBaseModelClass($p_model, $filename);
        }
        $filename = $path . '/' . $addp . '/' . $p_model->getMdClass() . '.php';
        $this->createModelClass($p_model, $filename);
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
                '\'' . str_repeat(' ', ($max-strlen($oneField->getFldName()))) . ' => $PRP_' .
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
