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
    public function getJsonApiStandardObject($p_model, $p_cls)
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
        $cls = rtrim(ltrim($p_model->getMdNs(), '\\'), '\\') . '\Model\\' . $p_model->getMdClass();
        $cls = str_replace('\\', '::', $cls);
        /**
         * @var \FreeFW\Core\Model $obj
         */
        $obj = \FreeFW\DI\DI::get($cls);
        if ($obj) {
            if ($p_asJsonApi) {
                // Main object + attributes
                $doc->addComponentsSchema($obj->getApiType(), $this->getJsonApiStandardObject($obj, $cls));
                // Attributes
                $doc->addComponentsSchema($obj->getApiType() . '_Attributes', $this->modelToOpenApiV3($obj, $p_asJsonApi));
            } else {
                $doc->addComponentsSchema($obj->getApiType(), $this->modelToOpenApiV3($obj, $p_asJsonApi));
            }
            /**
             * @var \FreeFW\Http\Router $router
             */
            $router  = \FreeFW\DI\DI::getShared('router');
            $collect = $router->getRoutes();
            /**
             * @var \FreeFW\Router\Route $route
             */
            foreach ($collect->getRoutes() as $route) {
                if ($route->getDefaultModel() == $cls) {
                    $path = \FreeFW\DI\DI::get('\FreeFW\OpenApi\V3\Path');
                    $url  = $route->getUrl();
                    $url  = preg_replace_callback("/\/(:\w+)/", array(&$this, 'substituteFilter'), $url);
                    $oper = \FreeFW\DI\DI::get('\FreeFW\OpenApi\V3\Operation');
                    $oper->setDescription($route->getComment());
                    $oper->setOperationId($route->getId());
                    foreach ($route->getParameters() as $name => $properties) {
                        $param = \FreeFW\DI\DI::get('\FreeFW\OpenApi\V3\Parameter');
                        $param->setIn($properties[\FreeFW\Router\Route::ROUTE_PARAMETER_ORIGIN]);
                        $param->setDescription($properties[\FreeFW\Router\Route::ROUTE_PARAMETER_COMMENT]);
                        if (array_key_exists(\FreeFW\Router\Route::ROUTE_PARAMETER_REQUIRED, $properties)) {
                            if ($properties[\FreeFW\Router\Route::ROUTE_PARAMETER_REQUIRED]) {
                                $param->setRequired(true);
                            }
                        }
                        if (array_key_exists(\FreeFW\Router\Route::ROUTE_PARAMETER_TYPE, $properties)) {
                            $schema = \FreeFW\DI\DI::get('\FreeFW\OpenApi\V3\Schema');
                            switch ($properties[\FreeFW\Router\Route::ROUTE_PARAMETER_TYPE]) {
                                case FFCST::TYPE_BIGINT:
                                    $schema->setFormat(\FreeFW\OpenApi\V3\Schema::FORMAT_INT64);
                                    break;
                                case FFCST::TYPE_INTEGER:
                                    $schema->setFormat(\FreeFW\OpenApi\V3\Schema::FORMAT_INT32);
                                    break;
                                case FFCST::TYPE_DECIMAL:
                                    $schema->setFormat(\FreeFW\OpenApi\V3\Schema::FORMAT_FLOAT);
                                    break;
                                case FFCST::TYPE_DATE:
                                    $schema->setFormat(\FreeFW\OpenApi\V3\Schema::FORMAT_DATETIME);
                                    break;
                                case FFCST::TYPE_DATETIME:
                                case FFCST::TYPE_DATETIMETZ:
                                    $schema->setFormat(\FreeFW\OpenApi\V3\Schema::FORMAT_DATETIME);
                                    break;
                                case FFCST::TYPE_BOOLEAN:
                                    $schema->setFormat(\FreeFW\OpenApi\V3\Schema::FORMAT_BOOLEAN);
                                    break;
                                case FFCST::TYPE_BLOB:
                                    $schema->setFormat(\FreeFW\OpenApi\V3\Schema::FORMAT_BINARY);
                                    break;
                                default:
                                    $schema->setFormat(\FreeFW\OpenApi\V3\Schema::FORMAT_STRING);
                                    break;
                            }
                            $param->setSchema($schema);
                        }
                        $oper->addParameter($name, $param);
                    }
                    $doc->addPathsPathOrOperation($url, $path, $route->getMethod(), $oper);
                }
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
        // Base path
        $addp = rtrim(str_replace('\\', '/', $ns), '/');
        $path = rtrim($p_model->getMdPath(), '/');
        if (!is_dir($path . '/' . $addp)) {
            \FreeFW\Tools\Dir::mkpath($path . '/' . $addp);
            if (!is_dir($path . '/' . $addp)) {
                $p_model->addError(
                    \FreeFW\Core\Error::TYPE_PRECONDITION,
                    sprintf('Model::generate, %s is not a directory !', $path . '/' . $addp)
                );
            }
        }
        // Model path
        $modelPath = $path . '/' . $addp . '/Model';
        if (!is_dir($modelPath)) {
            \FreeFW\Tools\Dir::mkpath($modelPath);
            if (!is_dir($modelPath)) {
                $p_model->addError(
                    \FreeFW\Core\Error::TYPE_PRECONDITION,
                    sprintf('Model::generate, %s is not a directory !', $modelPath)
                );
            }
        }
        if (!is_dir($modelPath . '/Base')) {
            \FreeFW\Tools\Dir::mkpath($modelPath . '/Base');
            if (!is_dir($modelPath . '/Base')) {
                $p_model->addError(
                    \FreeFW\Core\Error::TYPE_PRECONDITION,
                    sprintf('Model::generate, %s is not a directory !', $modelPath . '/Base')
                );
            }
        }
        if (!is_dir($modelPath . '/StorageModel')) {
            \FreeFW\Tools\Dir::mkpath($modelPath . '/StorageModel');
            if (!is_dir($modelPath . '/StorageModel')) {
                $p_model->addError(
                    \FreeFW\Core\Error::TYPE_PRECONDITION,
                    sprintf('Model::generate, %s is not a directory !', $modelPath . '/StorageModel')
                );
            }
        }
        // Controller path
        $ctrlPath = $path . '/' . $addp . '/Controller';
        if (!is_dir($ctrlPath)) {
            \FreeFW\Tools\Dir::mkpath($ctrlPath);
            if (!is_dir($ctrlPath)) {
                $p_model->addError(
                    \FreeFW\Core\Error::TYPE_PRECONDITION,
                    sprintf('Model::generate, %s is not a directory !', $ctrlPath)
                );
            }
        }
        // Routes path
        $routePath = $path . '/' . $addp . '/resource/routes/restful';
        if (!is_dir($routePath)) {
            \FreeFW\Tools\Dir::mkpath($routePath);
            if (!is_dir($routePath)) {
                $p_model->addError(
                    \FreeFW\Core\Error::TYPE_PRECONDITION,
                    sprintf('Model::generate, %s is not a directory !', $routePath)
                );
            }
        }
        //
        if ($p_model->hasErrors()) {
            return false;
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
        $filename = $modelPath . '/StorageModel/' . $p_model->getMdClass() . '.php';
        if (!is_file($filename)) {
            // @todo : read existing to update
            $this->createStorageModelClass($p_model, $filename);
        }
        $filename = $modelPath . '/Base/' . $p_model->getMdClass() . '.php';
        @unlink($filename);
        if (!is_file($filename)) {
            $this->createBaseModelClass($p_model, $filename);
        }
        $filename = $modelPath . '/' . $p_model->getMdClass() . '.php';
        if (!is_file($filename)) {
            $this->createModelClass($p_model, $filename);
        }
        $filename = $ctrlPath . '/' . $p_model->getMdClass() . '.php';
        if (!is_file($filename)) {
            $this->createControllerClass($p_model, $filename);
        }
        $filename = $routePath . '/' . \FreeFW\Tools\PBXString::fromCamelCase($p_model->getMdClass()) . '.php';
        //@unlink($filename);
        if (!is_file($filename)) {
            // @todo : read existing to update
            $this->createRoutes($p_model, $filename);
        }
        return true;
    }

    /*
     * Create routes for model class
     *
     * @param \FreeFW\Model\Model $p_model
     * @param string $p_filename
     *
     * @return boolean
     */
    protected function createRoutes(\FreeFW\Model\Model &$p_model, string $p_filename)
    {
        $nsSnake = \FreeFW\Tools\PBXString::fromCamelCase($p_model->getMdNs());
        $clSnake = \FreeFW\Tools\PBXString::fromCamelCase($p_model->getMdClass());
        $modelCl = $p_model->getMdNs() . '::Model::' . $p_model->getMdClass();
        $modelCt = $p_model->getMdNs() . '::Controller::' . $p_model->getMdClass();
        $collPth = trim($p_model->getMdCollPath(), '/');
        $collect = rtrim($p_model->getMdNs() . '/' . \FreeFW\Tools\PBXString::toCamelCase($collPth, true), '/') . '/' . $p_model->getMdClass();
        $scope   = '';
        if (is_array($p_model->getMdScope())) {
            foreach ($p_model->getMdScope() as $oneScope) {
                if (trim($oneScope) != '') {
                    if ($scope == '') {
                        $scope = '\'' . trim($oneScope) . '\'';
                    } else {
                        $scope = $scope . ',' . '\'' . trim($oneScope) . '\'';
                    }
                }
            }
        }
        $lines   = [];
        $lines[] = '<?php';
        $lines[] = 'use \FreeFW\Constants as FFCST;';
        $lines[] = 'use \FreeFW\Router\Route as FFCSTRT;';
        $lines[] = '';
        $lines[] = '/**';
        $lines[] = ' * Routes for ' . $p_model->getMdClass();
        $lines[] = ' *';
        $lines[] = ' * @author jeromeklam';
        $lines[] = ' */';
        $lines[] = '$routes_' . $clSnake . ' = [';
        $lines[] = '    \'' . $nsSnake . '.' . $clSnake . '.autocomplete\' => [';
        $lines[] = '        FFCSTRT::ROUTE_COLLECTION => \'' . $collect . '\',';
        $lines[] = '        FFCSTRT::ROUTE_COMMENT    => \'Autocomplete.\',';
        $lines[] = '        FFCSTRT::ROUTE_METHOD     => FFCSTRT::METHOD_GET,';
        $lines[] = '        FFCSTRT::ROUTE_MODEL      => \'' . $modelCl . '\',';
        $lines[] = '        FFCSTRT::ROUTE_URL        => \'/' . $p_model->getMdVers() . '/' . $collPth . '/' . $clSnake . '/autocomplete/:search\',';
        $lines[] = '        FFCSTRT::ROUTE_CONTROLLER => \'' . $modelCt . '\',';
        $lines[] = '        FFCSTRT::ROUTE_FUNCTION   => \'autocomplete\',';
        $lines[] = '        FFCSTRT::ROUTE_AUTH       => FFCSTRT::AUTH_IN,';
        $lines[] = '        FFCSTRT::ROUTE_MIDDLEWARE => [],';
        $lines[] = '        FFCSTRT::ROUTE_INCLUDE    => [],';
        $lines[] = '        FFCSTRT::ROUTE_SCOPE      => [' . $scope . '],';
        $lines[] = '        FFCSTRT::ROUTE_PARAMETERS => [';
        $lines[] = '            \'search\' => [';
        $lines[] = '                FFCSTRT::ROUTE_PARAMETER_ORIGIN   => FFCSTRT::ROUTE_PARAMETER_ORIGIN_PATH,';
        $lines[] = '                FFCSTRT::ROUTE_PARAMETER_TYPE     => FFCST::TYPE_STRING,';
        $lines[] = '                FFCSTRT::ROUTE_PARAMETER_REQUIRED => true,';
        $lines[] = '                FFCSTRT::ROUTE_PARAMETER_COMMENT  => \'Chaine de recherche\'';
        $lines[] = '            ],';
        $lines[] = '        ],';
        $lines[] = '        FFCSTRT::ROUTE_RESULTS    => [';
        $lines[] = '            \'200\' => [';
        $lines[] = '                FFCSTRT::ROUTE_RESULTS_TYPE    => FFCSTRT::RESULT_LIST,';
        $lines[] = '                FFCSTRT::ROUTE_RESULTS_MODEL   => \'' . $modelCl . '\',';
        $lines[] = '                FFCSTRT::ROUTE_RESULTS_COMMENT => \'Réponse ok\',';
        $lines[] = '            ],';
        $lines[] = '        ]';
        $lines[] = '    ],';
        $lines[] = '    \'' . $nsSnake . '.' . $clSnake . '.getall\' => [';
        $lines[] = '        FFCSTRT::ROUTE_COLLECTION => \'' . $collect . '\',';
        $lines[] = '        FFCSTRT::ROUTE_COMMENT    => \'Retourne une liste filtrée, triée et paginée.\',';
        $lines[] = '        FFCSTRT::ROUTE_METHOD     => FFCSTRT::METHOD_GET,';
        $lines[] = '        FFCSTRT::ROUTE_MODEL      => \'' . $modelCl . '\',';
        $lines[] = '        FFCSTRT::ROUTE_URL        => \'/' . $p_model->getMdVers() . '/' . $collPth . '/' . $clSnake . '\',';
        $lines[] = '        FFCSTRT::ROUTE_CONTROLLER => \'' . $modelCt . '\',';
        $lines[] = '        FFCSTRT::ROUTE_FUNCTION   => \'getAll\',';
        $lines[] = '        FFCSTRT::ROUTE_AUTH       => FFCSTRT::AUTH_IN,';
        $lines[] = '        FFCSTRT::ROUTE_MIDDLEWARE => [],';
        $lines[] = '        FFCSTRT::ROUTE_INCLUDE    => [],';
        $lines[] = '        FFCSTRT::ROUTE_SCOPE      => [' . $scope . '],';
        $lines[] = '        FFCSTRT::ROUTE_RESULTS    => [';
        $lines[] = '            \'200\' => [';
        $lines[] = '                FFCSTRT::ROUTE_RESULTS_TYPE    => FFCSTRT::RESULT_LIST,';
        $lines[] = '                FFCSTRT::ROUTE_RESULTS_MODEL   => \'' . $modelCl . '\',';
        $lines[] = '                FFCSTRT::ROUTE_RESULTS_COMMENT => \'Réponse ok\',';
        $lines[] = '            ],';
        $lines[] = '        ]';
        $lines[] = '    ],';
        $lines[] = '    \'' . $nsSnake . '.' . $clSnake . '.getone\' => [';
        $lines[] = '        FFCSTRT::ROUTE_COLLECTION => \'' . $collect . '\',';
        $lines[] = '        FFCSTRT::ROUTE_COMMENT    => \'Retourne un objet selon son identifiant\',';
        $lines[] = '        FFCSTRT::ROUTE_METHOD     => FFCSTRT::METHOD_GET,';
        $lines[] = '        FFCSTRT::ROUTE_MODEL      => \'' . $modelCl . '\',';
        $lines[] = '        FFCSTRT::ROUTE_URL        => \'/' . $p_model->getMdVers() . '/' . $collPth . '/' . $clSnake . '/:' . $p_model->getPrimaryFieldName() . '\',';
        $lines[] = '        FFCSTRT::ROUTE_CONTROLLER => \'' . $modelCt . '\',';
        $lines[] = '        FFCSTRT::ROUTE_FUNCTION   => \'getOne\',';
        $lines[] = '        FFCSTRT::ROUTE_AUTH       => FFCSTRT::AUTH_IN,';
        $lines[] = '        FFCSTRT::ROUTE_MIDDLEWARE => [],';
        $lines[] = '        FFCSTRT::ROUTE_INCLUDE    => [],';
        $lines[] = '        FFCSTRT::ROUTE_SCOPE      => [' . $scope . '],';
        $lines[] = '        FFCSTRT::ROUTE_PARAMETERS => [';
        $lines[] = '            \'' . $p_model->getPrimaryFieldName() . '\' => [';
        $lines[] = '                FFCSTRT::ROUTE_PARAMETER_ORIGIN   => FFCSTRT::ROUTE_PARAMETER_ORIGIN_PATH,';
        $lines[] = '                FFCSTRT::ROUTE_PARAMETER_TYPE     => FFCST::TYPE_BIGINT,';
        $lines[] = '                FFCSTRT::ROUTE_PARAMETER_REQUIRED => true,';
        $lines[] = '                FFCSTRT::ROUTE_PARAMETER_COMMENT  => \'Identifiant de l\\\'objet\'';
        $lines[] = '            ],';
        $lines[] = '        ],';
        $lines[] = '        FFCSTRT::ROUTE_RESULTS    => [';
        $lines[] = '            \'200\' => [';
        $lines[] = '                FFCSTRT::ROUTE_RESULTS_TYPE    => FFCSTRT::RESULT_OBJECT,';
        $lines[] = '                FFCSTRT::ROUTE_RESULTS_MODEL   => \'' . $modelCl . '\',';
        $lines[] = '                FFCSTRT::ROUTE_RESULTS_COMMENT => \'Réponse ok\',';
        $lines[] = '            ],';
        $lines[] = '        ]';
        $lines[] = '    ],';
        $lines[] = '    \'' . $nsSnake . '.' . $clSnake . '.createone\' => [';
        $lines[] = '        FFCSTRT::ROUTE_COLLECTION => \'' . $collect . '\',';
        $lines[] = '        FFCSTRT::ROUTE_COMMENT    => \'Créé un objet\',';
        $lines[] = '        FFCSTRT::ROUTE_METHOD     => FFCSTRT::METHOD_POST,';
        $lines[] = '        FFCSTRT::ROUTE_MODEL      => \'' . $modelCl . '\',';
        $lines[] = '        FFCSTRT::ROUTE_URL        => \'/' . $p_model->getMdVers() . '/' . $collPth . '/' . $clSnake . '\',';
        $lines[] = '        FFCSTRT::ROUTE_CONTROLLER => \'' . $modelCt . '\',';
        $lines[] = '        FFCSTRT::ROUTE_FUNCTION   => \'createOne\',';
        $lines[] = '        FFCSTRT::ROUTE_AUTH       => FFCSTRT::AUTH_IN,';
        $lines[] = '        FFCSTRT::ROUTE_MIDDLEWARE => [],';
        $lines[] = '        FFCSTRT::ROUTE_INCLUDE    => [],';
        $lines[] = '        FFCSTRT::ROUTE_SCOPE      => [' . $scope . '],';
        $lines[] = '        FFCSTRT::ROUTE_RESULTS    => [';
        $lines[] = '            \'201\' => [';
        $lines[] = '                FFCSTRT::ROUTE_RESULTS_TYPE    => FFCSTRT::RESULT_OBJECT,';
        $lines[] = '                FFCSTRT::ROUTE_RESULTS_MODEL   => \'' . $modelCl . '\',';
        $lines[] = '                FFCSTRT::ROUTE_RESULTS_COMMENT => \'Objet créé\',';
        $lines[] = '            ],';
        $lines[] = '        ]';
        $lines[] = '    ],';
        $lines[] = '    \'' . $nsSnake . '.' . $clSnake . '.updateone\' => [';
        $lines[] = '        FFCSTRT::ROUTE_COLLECTION => \'' . $collect . '\',';
        $lines[] = '        FFCSTRT::ROUTE_COMMENT    => \'Modifie un objet\',';
        $lines[] = '        FFCSTRT::ROUTE_METHOD     => FFCSTRT::METHOD_PUT,';
        $lines[] = '        FFCSTRT::ROUTE_MODEL      => \'' . $modelCl . '\',';
        $lines[] = '        FFCSTRT::ROUTE_URL        => \'/' . $p_model->getMdVers() . '/' . $collPth . '/' . $clSnake . '/:' . $p_model->getPrimaryFieldName() . '\',';
        $lines[] = '        FFCSTRT::ROUTE_CONTROLLER => \'' . $modelCt . '\',';
        $lines[] = '        FFCSTRT::ROUTE_FUNCTION   => \'updateOne\',';
        $lines[] = '        FFCSTRT::ROUTE_AUTH       => FFCSTRT::AUTH_IN,';
        $lines[] = '        FFCSTRT::ROUTE_MIDDLEWARE => [],';
        $lines[] = '        FFCSTRT::ROUTE_INCLUDE    => [],';
        $lines[] = '        FFCSTRT::ROUTE_SCOPE      => [' . $scope . '],';
        $lines[] = '        FFCSTRT::ROUTE_PARAMETERS => [';
        $lines[] = '            \'' . $p_model->getPrimaryFieldName() . '\' => [';
        $lines[] = '                FFCSTRT::ROUTE_PARAMETER_ORIGIN   => FFCSTRT::ROUTE_PARAMETER_ORIGIN_PATH,';
        $lines[] = '                FFCSTRT::ROUTE_PARAMETER_TYPE     => FFCST::TYPE_BIGINT,';
        $lines[] = '                FFCSTRT::ROUTE_PARAMETER_REQUIRED => true,';
        $lines[] = '                FFCSTRT::ROUTE_PARAMETER_COMMENT  => \'Identifiant de l\\\'objet\'';
        $lines[] = '            ],';
        $lines[] = '        ],';
        $lines[] = '        FFCSTRT::ROUTE_RESULTS    => [';
        $lines[] = '            \'200\' => [';
        $lines[] = '                FFCSTRT::ROUTE_RESULTS_TYPE    => FFCSTRT::RESULT_OBJECT,';
        $lines[] = '                FFCSTRT::ROUTE_RESULTS_MODEL   => \'' . $modelCl . '\',';
        $lines[] = '                FFCSTRT::ROUTE_RESULTS_COMMENT => \'Objet modifié\',';
        $lines[] = '            ],';
        $lines[] = '        ]';
        $lines[] = '    ],';
        $lines[] = '    \'' . $nsSnake . '.' . $clSnake . '.removeone\' => [';
        $lines[] = '        FFCSTRT::ROUTE_COLLECTION => \'' . $collect . '\',';
        $lines[] = '        FFCSTRT::ROUTE_COMMENT    => \'Supprime un objet\',';
        $lines[] = '        FFCSTRT::ROUTE_METHOD     => FFCSTRT::METHOD_DELETE,';
        $lines[] = '        FFCSTRT::ROUTE_MODEL      => \'' . $modelCl . '\',';
        $lines[] = '        FFCSTRT::ROUTE_URL        => \'/' . $p_model->getMdVers() . '/' . $collPth . '/' . $clSnake . '/:' . $p_model->getPrimaryFieldName() . '\',';
        $lines[] = '        FFCSTRT::ROUTE_CONTROLLER => \'' . $modelCt . '\',';
        $lines[] = '        FFCSTRT::ROUTE_FUNCTION   => \'removeOne\',';
        $lines[] = '        FFCSTRT::ROUTE_AUTH       => FFCSTRT::AUTH_IN,';
        $lines[] = '        FFCSTRT::ROUTE_MIDDLEWARE => [],';
        $lines[] = '        FFCSTRT::ROUTE_INCLUDE    => [],';
        $lines[] = '        FFCSTRT::ROUTE_SCOPE      => [' . $scope . '],';
        $lines[] = '        FFCSTRT::ROUTE_PARAMETERS => [';
        $lines[] = '            \'' . $p_model->getPrimaryFieldName() . '\' => [';
        $lines[] = '                FFCSTRT::ROUTE_PARAMETER_ORIGIN   => FFCSTRT::ROUTE_PARAMETER_ORIGIN_PATH,';
        $lines[] = '                FFCSTRT::ROUTE_PARAMETER_TYPE     => FFCST::TYPE_BIGINT,';
        $lines[] = '                FFCSTRT::ROUTE_PARAMETER_REQUIRED => true,';
        $lines[] = '                FFCSTRT::ROUTE_PARAMETER_COMMENT  => \'Identifiant de l\\\'objet\'';
        $lines[] = '            ],';
        $lines[] = '        ],';
        $lines[] = '        FFCSTRT::ROUTE_RESULTS    => [';
        $lines[] = '            \'204\' => [';
        $lines[] = '                FFCSTRT::ROUTE_RESULTS_COMMENT => \'Objet supprimé\',';
        $lines[] = '            ]';
        $lines[] = '        ]';
        $lines[] = '    ],';
        $lines[] = '];';
        $lines[] = '';
        file_put_contents($p_filename, implode(PHP_EOL, $lines));
        return true;
    }

   /*
    * Create Controller model class
    *
    * @param \FreeFW\Model\Model $p_model
    * @param string $p_filename
    *
    * @return boolean
    */
    protected function createControllerClass(\FreeFW\Model\Model &$p_model, string $p_filename)
    {
        $addNS   = '';
        $lines   = [];
        $lines[] = '<?php';
        $lines[] = 'namespace ' . $p_model->getMdNs() . '\Controller' . $addNS . ';';
        $lines[] = '';
        $lines[] = '/**';
        $lines[] = ' * Controller ' . $p_model->getMdClass();
        $lines[] = ' *';
        $lines[] = ' * @author jeromeklam';
        $lines[] = ' */';
        $lines[] = 'class ' . $p_model->getMdClass() . ' extends \FreeFW\Core\ApiController';
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
    protected function createModelClass(\FreeFW\Model\Model &$p_model, string $p_filename)
    {
        $addNS   = '';
        $lines   = [];
        $lines[] = '<?php';
        $lines[] = 'namespace ' . $p_model->getMdNs() . '\Model' . $addNS . ';';
        $lines[] = '';
        $lines[] = 'use \FreeFW\Constants as FFCST;';
        $lines[] = '';
        $lines[] = '/**';
        $lines[] = ' * Model ' . $p_model->getMdClass();
        $lines[] = ' *';
        $lines[] = ' * @author jeromeklam';
        $lines[] = ' */';
        $lines[] = 'class ' . $p_model->getMdClass() . ' extends \\' .
            $p_model->getMdNs() . '\Model' . $addNS . '\Base\\' . $p_model->getMdClass();
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
        $addNS   = '';
        $lines   = [];
        $lines[] = '<?php';
        $lines[] = 'namespace ' . $p_model->getMdNs() . '\Model' . $addNS . '\StorageModel;';
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
        $lines[] = '    /**';
        $lines[] = '     * Field properties as static arrays';
        $lines[] = '     * @var array';
        $lines[] = '     */';
        // fields
        $fields = $p_model->getMdFields();
        $nbre   = count($fields);
        for ($i=0; $i<$nbre; $i++) {
            /**
             * @var \FreeFW\Model\Field $oneField
             */
            $oneField = $fields[$i];
            $lines[] = '    protected static $PRP_' . strtoupper($oneField->getFldName()) . ' = [';
            $lines[] = '        FFCST::PROPERTY_PRIVATE => \'' . $oneField->getFldName() . '\',';
            $lines[] = '        FFCST::PROPERTY_TYPE    => FFCST::' . $oneField->getFldTypeForClass() . ',';
            $lines[] = '        FFCST::PROPERTY_OPTIONS => [' . $oneField->getFldOptionsForClass() . '],';
            $lines[] = '        FFCST::PROPERTY_COMMENT => \'\',';
            switch ($oneField->getFldType()) {
                case FFCST::TYPE_INTEGER:
                case FFCST::TYPE_BIGINT:
                    $lines[] = '        FFCST::PROPERTY_SAMPLE  => 123,';
                    break;
                case FFCST::TYPE_BOOLEAN:
                    $lines[] = '        FFCST::PROPERTY_SAMPLE  => true,';
                    break;
                case FFCST::TYPE_STRING:
                    $lines[] = '        FFCST::PROPERTY_MAX     => ' . $oneField->getFldLength() . ',';
                default:
                    $lines[] = '        FFCST::PROPERTY_SAMPLE  => \'\',';
                    break;
            }
            if ($oneField->isForeignkey()) {
                $lines[] = '        FFCST::PROPERTY_FK      => [\'' . $oneField->getFldName() . '\' => ';
                $lines[] = '            [';
                $lines[] = '                FFCST::FOREIGN_MODEL => \'NS::Model::ModelName\',';
                $lines[] = '                FFCST::FOREIGN_FIELD => \'' . $oneField->getFldName() . '\',';
                $lines[] = '                FFCST::FOREIGN_TYPE  => \FreeFW\Model\Query::JOIN_LEFT,';
                $lines[] = '            ]';
                $lines[] = '        ],';
            }
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
        $lines[] = '';
        $lines[] = '    /**';
        $lines[] = '     * Get object short description';
        $lines[] = '     *';
        $lines[] = '     * @return string';
        $lines[] = '     */';
        $lines[] = '    public static function getSourceComments()';
        $lines[] = '    {';
        $lines[] = '        return \'\';';
        $lines[] = '    }';
        $lines[] = '';
        $lines[] = '    /**';
        $lines[] = '     * Get autocomplete field';
        $lines[] = '     *';
        $lines[] = '     * @return string';
        $lines[] = '     */';
        $lines[] = '    public static function getAutocompleteField()';
        $lines[] = '    {';
        $lines[] = '        return \'\';';
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
        $addNS   = '';
        $lines   = [];
        $lines[] = '<?php';
        $lines[] = 'namespace ' . $p_model->getMdNs() . '\Model' . $addNS . '\Base;';
        $lines[] = '';
        $lines[] = '/**';
        $lines[] = ' * ' . $p_model->getMdClass();
        $lines[] = ' *';
        $lines[] = ' * @author jeromeklam';
        $lines[] = ' */';
        $lines[] = 'abstract class ' . $p_model->getMdClass() . ' extends \\' .
            $p_model->getMdNs() . '\Model' . $addNS . '\StorageModel\\' . $p_model->getMdClass();
        $lines[] = '{';
        /**
         * @var \FreeFW\Model\Field $oneField
         */
        foreach ($p_model->getMdFields() as $oneField) {
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
        foreach ($p_model->getMdFields() as $oneField) {
            $camel   = \FreeFW\Tools\PBXString::toCamelCase($oneField->getFldName(), true);
            $lines[] = '';
            $lines[] = '    /**';
            $lines[] = '     * Set ' . $oneField->getFldName();
            $lines[] = '     *';
            $lines[] = '     * @param ' . $oneField->getFldTypeForPhp() . ' $p_value';
            $lines[] = '     *';
            $lines[] = '     * @return \\' . $p_model->getMdNs() . '\Model' . $addNS . '\\' . $p_model->getMdClass();
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

    /**
     * Filters for regexp
     *
     * @return string
     */
    private function substituteFilter($matches)
    {
        return '/{' . ltrim($matches[1], ':') . '}';
    }

    /**
     * Filters for regexp
     *
     * @return string
     */
    private function removeFilter($matches)
    {
        return '';
    }
}
