<?php
namespace FreeFW\Service;

use \FreeFW\Constants as FFCST;

/**
 * Application
 *
 * @author jeromeklam
 */
class Application extends \FreeFW\Core\Service
{

    /**
     * Generate documentation
     *
     * @return \FreeFW\OpenApi\V3\OpenApi
     */
    public function generateDocumentation()
    {
        $schemas = [];
        /**
         * @var \FreeFW\Service\Model $modelService
         */
        $modelService = \FreeFW\DI\DI::get('FreeFW::Service::Model');
        /**
         * @var \FreeFW\OpenApi\V3\OpenApi $doc
         */
        $doc = \FreeFW\DI\DI::get('\FreeFW\OpenApi\V3\OpenApi');
        /**
         * @var \FreeFW\Http\Router $router
         */
        $router  = \FreeFW\DI\DI::getShared('router');
        $collect = $router->getRoutes();
        /**
         * @var \FreeFW\Router\Route $route
         */
        foreach ($collect->getRoutes() as $route) {
            $model = $route->getDefaultModel();
            if ($model != '' && !array_key_exists($model, $schemas)) {
                $obj = \FreeFW\DI\DI::get($model);
                if ($obj) {
                    // Main object + attributes
                    $doc->addComponentsSchema($obj->getApiType(), $modelService->getJsonApiStandardObject($obj, $model));
                    // Attributes
                    $doc->addComponentsSchema($obj->getApiType() . '_Attributes', $modelService->modelToOpenApiV3($obj, true));
                }
                $schemas[$model] = $obj;
            }
            $path = \FreeFW\DI\DI::get('\FreeFW\OpenApi\V3\Path');
            $url  = $route->getUrl();
            $url  = preg_replace_callback("/\/(:\w+)/", array(
                &$this,
                'substituteFilter'
            ), $url);
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
        return $doc;
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
