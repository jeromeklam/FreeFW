<?php
namespace FreeFW\Development;

use FreeFW\Tools\PBXString;

/**
 *
 * @author jerome.klam
 *
 */
class JsonApi extends \FreeFW\Development\Api\Api
{

    /**
     * Répertoire de base
     * @var string
     */
    protected $base_dir = null;

    /**
     * Préfixe du namespace
     * @var string
     */
    protected $ns = null;

    /**
     * Constructeur
     *
     * @param string $p_filename
     */
    public function __construct()
    {
    }

    /**
     * génération de la classe Schéma
     *
     * @param string $p_ns
     * @param string $p_version
     * @param string $p_model
     * @param string $p_name
     * @param string $p_dir
     * @param string $p_type
     * @param string $p_pack
     */
    protected function generateSchema($p_ns, $p_version, $p_model, $p_name, $p_dir, $p_type, $p_pack)
    {
        $version = \FreeFW\Tools\PBXString::toCamelCase($p_version, true);
        $name    = \FreeFW\Tools\PBXString::toCamelCase($p_name, true);
        $snake   = \FreeFW\Tools\PBXString::fromCamelCase($name);
        $stream  = new \FreeFW\Stream\BufferStream();
        $model   = new $p_model();
        $file    = rtrim($p_dir, '/') . '/Schema/' . $version . '/' . $name . '.php';
        if (!is_file($file)) {
            $stream->writeLn('<?php');
            $stream->writeLn('namespace ' . $p_ns . '\\Schema\\' . $version . ';');
            $stream->writeLn('');
            $stream->writeLn('class ' . $name . ' extends \Neomerx\JsonApi\Schema\SchemaProvider');
            $stream->writeLn('{');
            $stream->writeLn('');
            $stream->writeLn('    /**');
            $stream->writeLn('     * @see \\Neomerx\\JsonApi\\Schema\\SchemaProvider');
            $stream->writeLn('     */');
            $stream->writeLn('    protected $resourceType = \'' . trim($p_type) . '\';');
            $stream->writeLn('');
            $stream->writeLn('    /**');
            $stream->writeLn('     * @see \\Neomerx\\JsonApi\\Schema\\SchemaProvider');
            $stream->writeLn('     */');
            $stream->writeLn('    public function getId($obj)');
            $stream->writeLn('    {');
            $cols = $model::getColumnDescByField();
            $ids  = $model::columnId();
            $add  = '';
            foreach ($ids as $ii => $cc) {
                if ($add == '') {
                    $add = $add . '$obj->' . $cols[$cc]['getter'] . '()';
                } else {
                    $add = $add . ' . \'_\' . $obj->' . $cols[$cc]['getter'] . '()';
                }
            }
            $stream->writeLn('        return ' . $add . ';');
            $stream->writeLn('    }');
            $stream->writeLn('');
            $stream->writeLn('    /**');
            $stream->writeLn('     * @see \\Neomerx\\JsonApi\\Schema\\SchemaProvider');
            $stream->writeLn('     */');
            $stream->writeLn('    public function getAttributes($obj)');
            $stream->writeLn('    {');
            $stream->writeLn('        $arr = [');
            foreach ($cols as $ii => $cc) {
                if (!array_key_exists('json', $cc) || $cc['json'] === true) {
                    $stream->writeLn('            \'' . $ii . '\' => $obj->' . $cc['getter'] . '(),');
                }
            }
            $route = $this->findByRole($p_version, $p_pack, \FreeFW\Router\Route::ROLE_GET);
            $stream->writeLn('        ];');
            if (method_exists($model, 'getJsonComplement')) {
                $stream->writeLn('        $cpl = ' . $p_model . '::getJsonComplement($obj);');
                $stream->writeLn('        $arr = array_merge($arr, $cpl);');
            }
            $stream->writeLn('        return $arr;');
            $stream->writeLn('    }');
            $stream->writeLn('');
            $stream->writeLn('    /**');
            $stream->writeLn('     * @see \\Neomerx\\JsonApi\\Schema\\SchemaProvider');
            $stream->writeLn('     */');
            $stream->writeLn('    public function getResourceLinks($resource)');
            $stream->writeLn('    {');
            $stream->writeLn('        $links = [];');
            if ($route !== false) {
                /*if (method_exists($model, '__toArray')) {
                    $stream->writeLn('        $obj = $resource->__toArray();');
                } else {
                    $stream->writeLn('        $obj = (array)$resource;');
                }*/
                $stream->writeLn('        $url = \\FreeFW\\Core\\Router\\Route::renderHrefForObj(\'' .
                                 $route->getUrl() . '\', $resource);');
                $stream->writeLn('        $links[\'self\']  = new \\Neomerx\\JsonApi\\Document\\Link($url, null, true);');
            }
            $stream->writeLn('        return $links;');
            $stream->writeLn('    }');
            $stream->writeLn('');
            if (method_exists($model, 'relationShips')) {
                $stream->writeLn('    /**');
                $stream->writeLn('     * @see \\Neomerx\\JsonApi\\Schema\\SchemaProvider');
                $stream->writeLn('     */');
                $stream->writeLn('    public function getRelationships($resource, $isPrimary, array $includeList)');
                $stream->writeLn('    {');
                $stream->writeLn('        return [');
                foreach ($model->relationShips() as $field => $props) {
                    $stream->writeLn('            \'' . $props['name'] . '\' => ' .
                                     '[self::DATA => $resource->' .  $props['name']. '],'
                     );
                }
                $stream->writeLn('        ];');
                $stream->writeLn('    }');
                $stream->writeLn('');
                $stream->writeLn('    /**');
                $stream->writeLn('     * @see \\Neomerx\\JsonApi\\Schema\\SchemaProvider');
                $stream->writeLn('     */');
                $stream->writeLn('    public function getIncludePaths()');
                $stream->writeLn('    {');
                $stream->writeLn('        return [');
                $stream->writeLn('            \'' . trim($p_type) . '\',');
                foreach ($model->relationShips() as $field => $props) {
                    $stream->writeLn('            \'' . $props['name'] . '\',');
                }
                $stream->writeLn('        ];');
                $stream->writeLn('    }');
                $stream->writeLn('');
            }
            $stream->writeLn('}');
            file_put_contents($file, $stream->getContents());
        }
    }

    /**
     * Retourne le contenu au format swagger 3
     *
     * @return string
     */
    protected function generate()
    {
        $version = $this->getLastVersion();
        foreach ($version->getModules() as $idxM => $oneModule) {
            $packages  = $oneModule['packages'];
            $modelPath = '\\' . str_replace('.', '\\', $oneModule['ns']) . '\\Model\\';
            foreach ($packages as $idxP => $onePackage) {
                if (array_key_exists('model', $onePackage)) {
                    $modelName = PBXString::toCamelCase($onePackage['model'], true);
                    $model     = $modelPath . $modelName;
                    if (class_exists($model)) {
                        $this->generateSchema(
                            str_replace('.', '\\', $oneModule['ns']),
                            $oneModule['apiVers'],
                            $model,
                            $modelName,
                            $oneModule['basePath'],
                            $idxP,
                            $idxP
                        );
                    } else {
                        throw new \InvalidArgumentException(sprintf('%s class does not exists !', $model));
                    }
                }
            }
        }
    }

    /**
     * Génération complète
     *
     * @return void
     */
    public function generateAll()
    {
        $this->load();
        $this->generate();
    }
}
