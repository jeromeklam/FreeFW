<?php
namespace FreeFW\Development;

/**
 *
 * @author jerome.klam
 *
 */
class ApiBlueprint extends \FreeFW\Development\Api\Api
{

    /**
     * New stream
     * @var \FreeFW\Stream\BufferStream
     */
    protected $stream = null;

    /**
     * Ecriture
     *
     * @param string  $p_str
     * @param boolean $p_addnewLine
     *
     * @return \FreeFW\Development\ApiBlueprint
     */
    protected function write($p_str = '', $p_addnewLine = true)
    {
        if ($this->stream === null) {
            $this->stream = new \FreeFW\Stream\BufferStream();
        }
        if ($p_addnewLine) {
            $p_str = $p_str . PHP_EOL;
        }
        $this->stream->write($p_str);
        return $this;
    }

    /**
     * Conversion en type swagger
     *
     * @param string $p_type
     *
     * @return string
     */
    protected static function modelTypeToApiBlueprintType($p_type)
    {
        switch ($p_type) {
            case \FreeFW\Constants::TYPE_STRING:
            case \FreeFW\Constants::TYPE_TEXT:
                return 'string';
            case \FreeFW\Constants::TYPE_BOOLEAN:
                return 'boolean';
            case \FreeFW\Constants::TYPE_BIGINT:
            case \FreeFW\Constants::TYPE_INTEGER:
                return 'number';
            case \FreeFW\Constants::TYPE_SELECT:
                return 'string';
            default:
                return 'string';
        }
    }

    /**
     *
     * @param unknown $p_object
     * @param unknown $p_before
     */
    protected function addObjectProperties($p_object, $p_before)
    {
        $props = $p_object->getColumnDescByField();
        foreach ($props as $idx => $oneField) {
            $this->write($p_before . '    "' . $oneField['name'] . '" : {');
            $this->write($p_before . '        "type" : "' .
                         $this->modelTypeToApiBlueprintType($oneField['type']) . '"');
            $this->write($p_before . '    },');
        }
    }

    /**
     * Ajout d'un schéma simple
     */
    protected function addMJsonSchema ($p_model, $p_before = '')
    {
        $parts = explode('_', $p_model);
        $model = array_pop($parts);
        $class = '\\' . implode('\\', $parts) . '\Model\\' . $model;
        if (class_exists($class)) {
            $object = new $class();
            $this->write('+ type (string)');
            $this->write('+ id (string)');
            $this->write('+ attributes (object)');
            if ($object) {
                $props = $object->getColumnDescByField();
                foreach ($props as $idx => $oneField) {
                    $abType = $this->modelTypeToApiBlueprintType($oneField['type']);
                    $key    = strtolower($model . '.' . $oneField['name']);
                    $doc    = $this->getDoc($key);
                    if ($doc) {
                        $doc = ' - ' .  $doc;
                    } else {
                        $doc = '';
                    }
                    $this->write($p_before . '    + ' . $oneField['name'] . ' (' . $abType . ')' . $doc);
                }
            }
        }
    }

    /**
     * Ajout d'un schéma simple
     */
    protected function addSimpleSchema($p_model, $p_before = '    ')
    {
        $parts = explode('_', $p_model);
        $model = array_pop($parts);
        $class = '\\' . implode('\\', $parts) . '\Model\\' . $model;
        if (class_exists($class)) {
            $object = new $class();
        }
        $this->write($p_before . '+ Attributes');
        $this->write($p_before . '    + data (' . $p_model . ')');
        $this->write('');
    }

    /**
     * Ajout d'un schéma simple
     */
    protected function addMultipleSchema($p_model, $p_before = '    ')
    {
        $parts = explode('_', $p_model);
        $model = array_pop($parts);
        $class = '\\' . implode('\\', $parts) . '\Model\\' . $model;
        if (class_exists($class)) {
            $object = new $class();
        }
        $this->write($p_before . '+ Attributes');
        $this->write($p_before . '    + data (array[' . $p_model . '])');
        $this->write('');
    }

    /**
     *
     * @param \FreeFW\Development\Api\Version $p_version
     *
     * @param array $p_package
     */
    protected function iteratepackageType($p_version, $p_packageName, $p_package)
    {
        $urls = [];
        foreach ($p_version->getAll() as $idx => $oneRoute) {
            $packName = $oneRoute->getPackage();
            if ($packName == $p_packageName) {
                if (!in_array($oneRoute->geturl(), $urls)) {
                    $urls[] = $oneRoute->geturl();
                }
            }
        }
        if (count($urls)  > 0) {
            $this
                ->write('# Group ' . $p_packageName)
                ->write()
            ;
        }
        foreach ($urls as $idO => $url) {
            $path = $this->getStandardUrl($url);
            $this
                ->write('## ' . $path)
                ->write()
            ;
            $first = true;
            foreach ($p_version->getAll() as $idx => $oneRoute) {
                $uriParams = [];
                $queParams = [];
                if ($oneRoute->getUrl() == $url) {
                    $params = $oneRoute->getParameters();
                    if (count($params) > 0) {
                        foreach ($params as $idxP => $oneParam) {
                            if ($oneParam->getFrom() == \FreeFW\Router\Param::FROM_URI) {
                                $uriParams[] = $oneParam;
                            }
                            if ($oneParam->getFrom() == \FreeFW\Router\Param::FROM_QUERY) {
                                $queParams[] = $oneParam;
                            }
                        }
                    }
                    if (count($params) > 0 && $first && count($uriParams) > 0) {
                        $this->write('+ Parameters');
                        foreach ($uriParams as $idxP => $oneParam) {
                            $this->write('    + ' . $oneParam->getName() . ' ' .
                                '(' . $oneParam->getType() . ')' .
                                ' - ' . $oneParam->getDescription());
                        }
                        $this->write('');
                    }
                    $first = false;
                    foreach ($oneRoute->getResults() as $idxR => $oneResult) {
                        $code = intval($oneResult->getHttp());
                        if ($code >= 200 && $code <= 299) {
                            $this
                                ->write('### ' . $oneRoute->getTitle() .
                                        ' [' . $oneRoute->getFirstMethod() . ']')
                                ->write()
                            ;
                            if (count($queParams) > 0) {
                                foreach ($queParams as $idxP => $oneParam) {
                                    $this->write('+ ' . $oneParam->getName() . ' ' .
                                        '(' . $oneParam->getType() . ')' .
                                        ' - ' . $oneParam->getDescription());
                                }
                                $this->write('');
                            }
                            $type = ' (application/json)';
                            if ($this->isJsonApi()) {
                                $type = ' (application/vnd.api+json)';
                            }
                            $this
                                ->write('+ Response ' . $code .$type)
                                ->write()
                            ;
/*                            if ($this->isJsonApi()) {
                                $this
                                    ->write('    + Headers')
                                    ->write('        Content-Type : application/vnd.api+json')
                                    ->write()
                                ;
                            }*/
                            if ($oneResult->getType() == \FreeFW\Router\Result::TYPE_OBJECT) {
                                $model = $oneResult->getObject();
                                $this->addSimpleSchema($model);
                            } else {
                                if ($oneResult->getType() == \FreeFW\Router\Result::TYPE_ARRAY) {
                                    $model = $oneResult->getObject();
                                    $this->addMultipleSchema($model);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Retourne le contenu au format blueprint
     *
     * @return string
     */
    protected function get()
    {
        $version = $this->getLastVersion();
        $this
            ->write('FORMAT: 1A')
            ->write()
            ->write('# ' . $this->getName())
            ->write()
            ->write($this->getDescription() . ' - version ' . $this->getLastVersionName())
            ->write()
        ;
        $this
            ->write('# Data Structures')
            ->write('')
        ;
        $types = $this->getDistinctTypes($this->getLastVersionName());
        if (count($types) > 0) {
            foreach ($types as $type => $props) {
                $this
                    ->write('## `' . $type . '` (object)')
                    ->write('')
                ;
                $this->addMJsonSchema($type);
                $this->write('');
            }
        }
        foreach ($this->getPackages() as $name => $package) {
            $this->iteratepackageType($version, $name, $package);
        }
        return $this->stream->getContents();
    }

    /**
     * Génération complète
     *
     */
    public function generateAll()
    {
        $this->load();
        echo $this->get();
    }
}
