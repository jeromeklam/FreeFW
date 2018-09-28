<?php
namespace FreeFW\Model;

/**
 *
 * @author jeromeklam
 *
 */
class ApiParams
{

    /**
     *
     */
    use \FreeFW\Behaviour\DI;

    /**
     * Filtres
     * @var string
     */
    protected $filters = [];

    /**
     * Depuis
     * @var number
     */
    protected $from = 0;

    /**
     * Longueur
     * @var number
     */
    protected $len = 0;

    /**
     * Page
     * @var number
     */
    protected $page = 0;

    /**
     * Identifiant de la requête
     * @var string
     */
    protected $query_id = false;

    /**
     * Modèles à inclure
     * @var array
     */
    protected $included = array();

    /**
     * Champs à inclure
     * @var array
     */
    protected $fields = array();

    /**
     * Tri
     * @var array
     */
    protected $sort = array();

    /**
     * Partie data
     * @var mixed
     */
    protected $data = false;

    /**
     * Erreurs
     * @var mixed
     */
    protected $errors = false;

    /**
     * Schémas
     * @var string
     */
    protected $schemas = false;

    /**
     * Query mode
     * @var string
     */
    protected $mode = 'EQUAL';

    /**
     * Query AndOr
     * @var string
     */
    protected $andor = 'AND';

    /**
     * Affectation du from 'depuis'
     *
     * @param number $p_from
     *
     * @return \FreeFW\Model\ApiParams
     */
    public function setFrom($p_from)
    {
        $this->from = intval($p_from);
        return $this;
    }

    /**
     * Retourne la partie from 'depuis'
     *
     * @param boolean $p_startAt0
     *
     * @return number
     */
    public function getFrom($p_startAt0 = false)
    {
        if ($p_startAt0) {
            return $this->from - 1;
        }
        return $this->from;
    }

    /**
     * Affectation de len 'longueur'
     *
     * @param number $p_len
     *
     * @return \FreeFW\Model\ApiParams
     */
    public function setLen($p_len)
    {
        $this->len = intval($p_len);
        return $this;
    }

    /**
     * Retourne la partie len 'longueur'
     *
     * @return number
     */
    public function getLen()
    {
        return $this->len;
    }

    /**
     * Affectation du mode
     *
     * @param string $p_mode
     *
     * @return \FreeFW\Model\ApiParams
     */
    public function setMode ($p_mode)
    {
        if (strtoupper($p_mode) == 'LIKE') {
            $this->mode = 'LIKE';
        } else {
            $this->mode = 'EQUAL';
        }
        return $this;
    }

    /**
     * Récupération du mode
     *
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Affectation And Or
     *
     * @param string $p_andor
     *
     * @return \FreeFW\Model\ApiParams
     */
    public function setAndor($p_andor)
    {
        if (strtoupper($p_andor) == 'OR') {
            $this->andor = 'OR';
        } else {
            $this->andor = 'AND';
        }
        return $this;
    }

    /**
     * Récupération And Or
     *
     * @return string
     */
    public function getAndor()
    {
        return $this->andor;
    }

    /**
     * Affectation de la partie 'page'
     *
     * @param number $p_page
     * @param number $p_pageLen
     *
     * @return \FreeFW\Model\ApiParams
     */
    public function setPage($p_page, $p_pageLen = 25)
    {
        $this->page = intval($p_page);
        $this->from = ($this->page - 1) * $p_pageLen;
        $this->len  = $p_pageLen;
        return $this;
    }

    /**
     * Retourne la page
     *
     * @return number
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Affectation de l'identifiant de requête
     *
     * @param string $p_query_id
     *
     * @return \FreeFW\Model\ApiParams
     */
    public function setQueryId($p_query_id)
    {
        $this->query_id = $p_query_id;
        return $this;
    }

    /**
     * Retourne l'identifiant de requête
     *
     * @return string
     */
    public function getQueryId()
    {
        return $this->query_id;
    }

    /**
     * Retourne le filtre
     *
     * @return string
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Filtre existe ?
     *
     * @param string $p_name
     *
     * @return boolean
     */
    public function hasFilter($p_name)
    {
        if (array_key_exists($p_name, $this->filters)) {
            return true;
        }
        return false;
    }

    /**
     * Retourne un filtre
     *
     * @param string  $p_name
     * @param boolean $p_default
     *
     * @return boolean
     */
    public function getFilter($p_name, $p_default = false)
    {
        if (array_key_exists($p_name, $this->filters)) {
            return $this->filters[$p_name];
        }
        return $p_default;
    }

    /**
     * Affectation des filtres
     *
     * @param array $p_filters
     *
     * @return \FreeFW\Model\ApiParams
     */
    public function setFilters($p_filters)
    {
        $this->filters = $p_filters;
        return $this;
    }

    /**
     * Affectation des inclusions
     *
     * @param array $p_included
     *
     * @return \FreeFW\Model\ApiParams
     */
    public function setIncluded($p_included)
    {
        if (is_array($p_included)) {
            $this->included = $p_included;
        } else {
            $this->included = explode(',', str_replace(' ', '', $p_included));
        }
        return $this;
    }

    /**
     * Retourne le inclusions
     *
     * @return array
     */
    public function getIncluded()
    {
        return $this->included;
    }

    /**
     * Retourne la partie inclusions en chaine séparée par ,
     *
     * @return string
     */
    public function getInclude()
    {
        return implode(',', $this->included);
    }

    /**
     * Affectation des champs à retourner
     *
     * @param array $p_fields
     *
     * @return \FreeFW\Model\ApiParams
     */
    public function setFields($p_fields)
    {
        if (is_array($p_fields)) {
            foreach($p_fields as $idxF => $valD) {
                if (!array_key_exists($idxF, $this->fields)) {
                    $this->fields[$idxF] = array();
                }
                $this->fields[$idxF] = array_merge($this->fields[$idxF], explode(',', $valD));
            }
        }
        return $this;
    }

    /**
     * Récupération des champs à retourner
     *
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Affectation du tri
     *
     * @param array|string $p_sort
     *
     * @return \FreeFW\Model\ApiParams
     */
    public function setSort($p_sort)
    {
        if (is_array($p_sort)) {
            $this->sort = $p_sort;
        } else {
            $this->sort = explode(',', str_replace(' ', '', $p_sort));
        }
        return $this;
    }

    /**
     * retourne le tri
     *
     * @return array
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Ajout d'un filtre
     *
     * @param string $p_field
     * @param mixed  $p_value
     *
     * @return \FreeFW\Model\ApiParams
     */
    public function addFilter($p_field, $p_value)
    {
        $this->filters[$p_field] = $p_value;
        return $this;
    }

    /**
     * Affectation de la partie data
     *
     * @param mixed $p_data
     *
     * @return \FreeFW\Model\ApiParams
     */
    public function setData($p_data)
    {
        $this->data = $p_data;
        return $this;
    }

    /**
     * Affectation des chémas
     *
     * @param mixed $p_schemas
     *
     * @return \FreeFW\Model\ApiParams
     */
    public function setSchemas($p_schemas)
    {
        $this->schemas = $p_schemas;
        return $this;
    }

    /**
     * Convert datas to Model
     *
     * @param object $schema
     * @param array  $datas
     *
     * @return object
     */
    protected function dataToModel($schema, $datas, $p_orig = null)
    {
        $model = $schema->getModel($datas, $p_orig);
        if ($model && array_key_exists('relationships', $datas)) {
            foreach ($datas['relationships'] as $relName => $relation) {
                if (array_key_exists('data', $relation)) {
                    if (array_key_exists('type', $relation['data'])) {
                        $sch = $this->getResourceSchemaFromType($relation['data']['type']);
                        if ($sch) {
                            $relModel = $this->dataToModel($sch, $relation['data']);
                            if ($relModel) {
                                $schema->setRelationShips($model, $relName, $relModel);
                            }
                        }
                    }
                }
            }
        }
        return $model;
    }

    /**
     * Retourne un modèle
     *
     * @param string $p_name
     * @param string $p_id
     *
     * @return object
     */
    public function buildModel($p_class, $p_id = '', $p_orig = null)
    {
        $obj = false;
        $sch = $this->getResourceSchemaFromClass($p_class);
        if ($sch) {
            if (is_array($this->data)) {
                $type = $sch->getResourceType();
                if (array_key_exists('type', $this->data)) {
                    if ($this->data['type'] == $type) {
                        if ($this->data['id'] == $p_id || $p_id == '') {
                            // found one...
                            $obj = $this->dataToModel($sch, $this->data, $p_orig);
                        }
                    }
                } else {
                    foreach ($this->data as $idx => $dat) {
                        if (array_key_exists('type', $dat)) {
                            if ($dat['type'] == $type) {
                                if ($dat['id'] == $p_id || $p_id == '') {
                                    // found one...
                                    if (!$obj) {
                                        $obj = [];
                                    }
                                    $obj[] = $this->dataToModel($sch, $dat);
                                }
                            }
                        }
                    }
                }
            } else {
                // @todo no data attribute ??
            }
        } else {
            // @todo  no schema for class ??
        }
        return $obj;
    }

    /**
     * Return md5
     *
     * @return string
     */
    public function getLocalMd5()
    {
        $obj           = new \stdClass();
        $obj->from     = $this->from;
        $obj->len      = $this->len;
        $obj->filters  = $this->filters;
        $obj->sort     = $this->sort;
        $obj->included = $this->included;
        $md5           = md5(json_encode($obj));
        return $md5;
    }

    /**
     * Get Key from params
     *
     * @param string  $p_prefix
     * @param boolean $p_withUser
     *
     * @return string
     */
    public function getMd5Key($p_prefix, $p_withUser = true)
    {
        $prefix = $p_prefix;
        if ($p_withUser) {
            $sso    = self::getDIShared('sso');
            $user   = $sso->getUser();
            $prefix = $prefix . '.' . $user->getUserId();
        }
        return $prefix . '.' . $this->getLocalMd5();
    }

    /**
     * Get resource schémas
     *
     * @param string $p_name
     *
     * @return object
     */
    protected function getResourceSchemaFromClass($p_name)
    {
        $provider = new \Neomerx\JsonApi\Factories\Factory();
        if (is_array($this->schemas) && array_key_exists($p_name, $this->schemas)) {
            $cls = $this->schemas[$p_name]['to'];
            if (class_exists($cls)) {
                $instance = new $cls($provider);
                return $instance;
            }
        }
        return false;
    }

    /**
     * Get resource schémas
     *
     * @param string $p_name
     *
     * @return object
     */
    protected function getResourceSchemaFromType($p_name)
    {
        $provider = new \Neomerx\JsonApi\Factories\Factory();
        foreach ($this->schemas as $cls => $data) {
            if ($data['name'] == $p_name) {
                $cls = $data['to'];
                if (class_exists($cls)) {
                    $instance = new $cls($provider);
                    return $instance;
                }
            }
        }
        return false;
    }
}
