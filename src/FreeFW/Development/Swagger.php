<?php
namespace FreeFW\Development;

use FreeFW\Tools\PBXString;

/**
 *
 * @author jerome.klam
 *
 */
class Swagger extends \FreeFW\Development\Api\Api
{

    /**
     * Comportements
     */
    use \FreeFW\Behaviour\Translation;

    /**
     * Swagger content as arrays keys
     * @var array
     */
    protected $swagger = null;

    /**
     * Conversion en type swagger
     *
     * @param string $p_type
     *
     * @return string
     */
    protected static function modelTypeToSwaggerType($p_type)
    {
        switch ($p_type) {
            case \FreeFW\Constants::TYPE_STRING:
            case \FreeFW\Constants::TYPE_TEXT:
                return 'string';
            case \FreeFW\Constants::TYPE_BOOLEAN:
                return 'boolean';
            case \FreeFW\Constants::TYPE_BIGINT:
            case \FreeFW\Constants::TYPE_INTEGER:
                return 'integer';
            case \FreeFW\Constants::TYPE_SELECT:
                return 'string';
            default:
                return 'string';
        }
    }

    /**
     * Génération du schéma swagger pour un modèle
     *
     * @param string $p_model
     *
     * @return \StdClass
     */
    public function generateJsonApiSchema($p_model, $p_type)
    {
        $ref                                         = '$ref';
        $schema                                      = new \StdClass();
        $schema->type                                = 'object';
        $schema->properties                          = new \StdClass();
        $schema->properties->type                    = new \stdClass();
        $schema->properties->type->type              = 'string';
        $schema->properties->type->enum              = $p_type;
        $schema->properties->type->title             = 'Le type d\'object';
        $schema->properties->id                      = new \stdClass();
        $schema->properties->id->type                = 'string';
        $schema->properties->id->tile                = 'L\'identifiant de l\'objet';
        $schema->properties->attributes              = new \stdClass();
        $schema->properties->attributes->type        = 'object';
        $schema->properties->attributes->title       = 'La liste des attributs';
        $schema->properties->attributes->{$ref}      = "#/components/schemas/" . $p_type;
        return $schema;
    }

    /**
     * Génération du schéma swagger pour un modèle
     *
     * @param string $p_model
     *
     * @return \StdClass
     */
    public function generateOneJsonApiSchema($p_model, $p_type)
    {
        $data = new \StdClass();
        $ref                            = '$ref';
        $data->type                     = 'object';
        $data->properties               = new \stdClass();
        $data->properties->data         = new \stdClass();
        $data->properties->data->{$ref} = '#/components/schemas/' . $p_type . '_JSONAPI';
        return $data;
    }

    /**
     * Génération du schéma swagger pour un modèle
     *
     * @param string $p_model
     *
     * @return \StdClass
     */
    public function generateMultipleJsonApiSchema($p_model, $p_type)
    {
        $data = new \StdClass();
        $ref                            = '$ref';
        $data->type                     = 'object';
        $data->properties               = new \stdClass();
        $data->properties->data         = new \stdClass();
        $data->properties->data->type   = 'array';
        $data->properties->data->items  = new \StdClass();
        $data->properties->data->items->{$ref} = '#/components/schemas/' . $p_type . '_JSONAPI';
        $data->properties->data->uniqueItems   = true;
        return $data;
    }

    /**
     * Génération du schéma swagger pour un modèle
     *
     * @param string $p_model
     *
     * @return \StdClass
     */
    public function generateSchema($p_model)
    {
        $schema             = new \StdClass();
        $model              = new $p_model();
        $schema->type       = 'object';
        $schema->properties = new \StdClass();
        $fields             = $model->getColumnDescByField();
        $classname          = strtolower(get_class($model));
        $parts              = explode('\\', $classname);
        $classname          = array_pop($parts);
        foreach ($fields as $idxF => $field) {
            if (!array_key_exists('json', $field) || $field['json'] === true) {
                $fldName                              = $field['name'];
                $schema->properties->{$fldName}       = new \stdClass();
                $schema->properties->{$fldName}->type = self::modelTypeToSwaggerType($field['type']);
                if ($field['type'] == \FreeFW\Constants::TYPE_SELECT) {
                    $schema->properties->{$fldName}->enum = $field['list'];
                }
                if (array_key_exists('desc', $field)) {
                    $schema->properties->{$fldName}->title = $field['desc'];
                } else {
                    $tr = $this->_($classname . '.' . $field['name'] . '.jsonapi', false);
                    if ($tr !== false && $tr !== '') {
                        $schema->properties->{$fldName}->title = $tr;
                    } else {
                        $tr = $this->_($classname . '.' . $field['name'] . '.title', false);
                        if ($tr !== false && $tr !== '') {
                            $schema->properties->{$fldName}->title = $tr;
                        }
                    }
                }
            }
        }
        return $schema;
    }

    /**
     * Retourne le contenu au format swagger 3
     *
     * @return string
     */
    protected function get()
    {
        $mainType = 'application/json';
        if ($this->isJsonApi()) {
            $mainType = 'application/vnd.api+json';
        }
        $this->swagger          = new \stdClass();
        $this->swagger->openapi = "3.0.0";
        $this->swagger->info    = new \stdClass();
        // Données principales
        $this->swagger->info->title       = $this->getName();
        $this->swagger->info->description = $this->getDescription();
        foreach ($this->getContacts() as $idx => $oneContact) {
            if ($oneContact->isMain()) {
                $this->swagger->info->contact = $oneContact->getForSwagger();
                break;
            }
        }
        $licence = $this->getLicence();
        if ($licence !== null) {
            $this->swagger->info->licence = $licence->getForSwagger();
        }
        $version                      = $this->getLastVersionName();
        $this->swagger->info->version = $version;
        $servers = false;
        foreach ($this->getServers() as $idxS => $oneServer) {
            if ($servers === false) {
                $servers = [];
            }
            $servers[] = $oneServer->getForSwagger();
        }
        if ($servers !== false) {
            $this->swagger->servers = $servers;
        }
        // Composants
        $this->swagger->components = new \stdClass();
        // Schémas
        $types = $this->getDistinctTypes($version);
        if (count($types) > 0) {
            $schemas = new \stdClass();
            foreach ($types as $type => $props) {
                $modelParts = explode('_', $type);
                $modelName  = array_pop($modelParts);
                $model      = implode('\\', $modelParts) . '\\Model\\' . PBXString::toCamelCase($modelName, true);
                if (class_exists($model)) {
                    $schemas->{$type} = $this->generateSchema($model);
                    if ($this->isJsonApi()) {
                        $typeA = $type . '_JSONAPI';
                        $schemas->{$typeA} = $this->generateJsonApiSchema($model, $type);
                        $typeA = $type . '_ONE';
                        $schemas->{$typeA} = $this->generateOneJsonApiSchema($model, $type);
                        $typeA = $type . '_MULTIPLE';
                        $schemas->{$typeA} = $this->generateMultipleJsonApiSchema($model, $type);
                    }
                }
            }
            $this->swagger->components->schemas = $schemas;
        }
        $headers = false;
        if ($this->isJsonApi()) {
            if ($headers === false) {
                $headers = new \stdClass();
            }
            $acceptHeader                   = new \StdClass();
            $acceptHeader->description      = "Accept header";
            $acceptHeader->schema           = new \StdClass();
            $acceptHeader->schema->type     = 'string';
            $acceptHeader->schema->required = true;
            $headers->Accept                = $acceptHeader;
        }
        if ($this->isJsonApi()) {
            if ($headers === false) {
                $headers = new \stdClass();
            }
            $secureHeader                   = new \StdClass();
            $secureHeader->description      = "Application broker name";
            $secureHeader->schema           = new \StdClass();
            $secureHeader->schema->type     = 'string';
            $secureHeader->schema->required = true;
            $headers->API_ID                = $secureHeader;
        }
        if ($headers !== false) {
            $this->swagger->components->headers = $headers;
        }
        // Paths
        $this->swagger->paths = new \stdClass();
        $base = $this->swagger->paths;
        foreach ($this->getAllRoutes($version) as $idxR => $oneRoute) {
            $path = $oneRoute->getUrl($path);
            $path = $this->getStandardUrl($path);
            if (! isset($base->{$path})) {
                $base->{$path} = new \StdClass();
            }
            $package = $this->getPackage($oneRoute->getPackage());
            if ($package === false) {
                $tag = $oneRoute->getPackage();
            } else {
                $tag = $package['description'] . ' [' . $package['name'] . ']';
            }
            //
            $method                                = strtolower($oneRoute->getFirstmethod());
            $base->{$path}->{$method}              = new \StdClass();
            $base->{$path}->{$method}->summary     = $oneRoute->getTitle();
            $base->{$path}->{$method}->operationId = $oneRoute->getName();
            $base->{$path}->{$method}->tags        = [$tag];
            // Je continue avec les paramètres
            $params = $oneRoute->getParameters();
            if (count($params) > 0) {
                $sParams  = false;
                $sRequest = false;
                foreach ($params as $idxP => $oneParam) {
                    if ($oneParam->getFrom() == \FreeFW\Router\Param::FROM_URI ||
                        $oneParam->getFrom() == \FreeFW\Router\Param::FROM_QUERY) {
                        $newParam = new \StdClass();
                        $newParam->name = $oneParam->getName();
                        if ($oneParam->getFrom() == \FreeFW\Router\Param::FROM_URI) {
                            $newParam->in = 'path';
                        } else {
                            $newParam->in = 'query';
                        }
                        $desc = $oneParam->getDescription();
                        if ($desc != '') {
                            $newParam->description = $desc;
                        }
                        if ($sParams === false) {
                            $sParams = [];
                        }
                        $sParams[] = $newParam;
                    } else {
                        if ($sRequest == false) {
                            $bodyCt             = new \StdClass();
                            $bodyCt->type       = 'object';
                            $bodyCt->properties = new \stdClass();
                            $schema             = new \StdClass();
                            $schema->schema     = $bodyCt;
                            $content            = new \StdClass();
                            $type               = 'application/x-www-form-urlencoded';
                            $content->{$type}   = $schema;
                            $body               = new \StdClass();
                            $body->content      = $content;
                            $body->required     = true;
                            $sRequest           = $body;
                        }
                        $name = $oneParam->getName();
                        $bodyCt->properties->{$name} = new \StdClass();
                        $bodyCt->properties->{$name}->type = self::modelTypeToSwaggerType($oneParam->getType());
                        $bodyCt->properties->{$name}->description = $oneParam->getDescription();
                    }
                }
                if ($sParams !== false) {
                    $base->{$path}->{$method}->parameters = $sParams;
                }
                if ($sRequest !== false) {
                    $base->{$path}->{$method}->requestBody = $sRequest;
                }
            }
            $base->{$path}->{$method}->responses = new \StdClass();
            foreach ($oneRoute->getResults() as $idxU => $oneResult) {
                $http = $oneResult->getHttp();
                $base->{$path}->{$method}->responses->{$http} = new \StdClass();
                $base->{$path}->{$method}->responses->{$http}->description = $oneResult->getComments();
                if ($oneResult->getObject() != '') {
                    $base->{$path}->{$method}->responses->{$http}->content = new \StdClass();
                    $rType                  = $mainType;
                    $rSchema                = new \StdClass();
                    $rSchema->schema        = new \StdClass();
                    $rRef                   = '$ref';
                    $typeA                  = $oneResult->getObject();
                    $simpleType             = true;
                    if ($this->isJsonApi()) {
                        if ($oneResult->getType() == \FreeFW\Router\Result::TYPE_ARRAY) {
                            $typeA      = $typeA . '_MULTIPLE';
                            $simpleType = false;
                        } else {
                            if ($oneResult->getType() == \FreeFW\Router\Result::TYPE_OBJECT) {
                                $typeA      = $typeA . '_ONE';
                                $simpleType = false;
                            }
                        }
                    }
                    if (!$simpleType) {
                        $rSchema->schema->$rRef = '#/components/schemas/' . $typeA;
                        $base->{$path}->{$method}->responses->{$http}->content->{$rType} = $rSchema;
                    } else {
                        $base->{$path}->{$method}->responses->{$http}->content->{$rType} = $typeA;
                    }
                }
            }
        }
        // Fini, je retourne l'objet
        return $this->swagger;
    }

    /**
     * Génération complète
     *
     */
    public function generateAll()
    {
        $this->load();
        $content = $this->get();
        echo json_encode($content, JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES);
    }
}
