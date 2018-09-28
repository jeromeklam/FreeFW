<?php
namespace FreeFW\Model;

use \FreeFW\Tools\PBXString;
use \FreeFW\Interfaces\BaseModel as ModelInterface;

/**
 * Classe de base de gestion en mémoire
 *
 * @author jeromeklam
 * @package Storage
 */
abstract class AbstractSoapStorage extends \FreeFW\Model\AbstractStorage implements
    ModelInterface,
    \JsonSerializable
{

    /**
     * wsdl
     * @var array
     */
    protected static $wsdl = null;

    /**
     * Identifiant WS de l'utilisateur
     * @var string
     */
    protected static $user_key = null;

    /**
     * Constructeur
     *
     * @param $resource
     * @param array    $config
     */
    public function __construct()
    {
    }

    /**
     * Chargement de la configuration
     */
    public static function loadConfig()
    {
        $sso  = self::getDIShared('sso');
        $user = $sso->getUser();
        if ($user !== false) {
            $cnx = json_decode($user->getUserCnx(), true);
            if (is_array($cnx)) {
                if (array_key_exists('webservice.user', $cnx)) {
                    self::$user_key = $cnx['webservice.user'];
                }
                if (array_key_exists('webservice.url', $cnx)) {
                    self::$wsdl = [
                        'wsdl' => $cnx['webservice.url']
                    ];
                }
            }
        }
    }

    /**
     * Retourne le Wsdl
     *
     * @return string
     */
    public static function getWsdlConfig()
    {
        if (self::$wsdl === null) {
            self::loadConfig();
        }
        return self::$wsdl;
    }

    /**
     * Retourne l'authentification
     *
     * @return \stdClass
     */
    public static function getAuth()
    {
        if (self::$user_key === null) {
            self::loadConfig();
        }
        $auth            = new \stdClass();
        $auth->clef_util = self::$user_key;
        return $auth;
    }

    /**
     * Retourne la clef md5 des filtres et données de tri
     *
     * @param array $p_filters
     * @param array $p_sortCols
     *
     * @return string
     */
    public static function getMd5FromParams($p_filters, $p_sortCols, $p_mode = 'EQUAL', $p_andor = 'AND')
    {
        // Je ne prend sue les clefs qui impactent le résultat
        $params = [];
        foreach ($p_filters as $key => $value) {
            if (! in_array($key, array('depuis', 'longueur', 'from', 'len', 'page', 'query_id'))) {
                $params[$key] = $value;
            }
        }
        $params['sort_cols']   = $p_sortCols;
        $params['query_mode']  = $p_mode;
        $params['query_andor'] = $p_andor;
        // Je tri le tableau par clefs
        ksort($params);
        // Encodage json
        $str = json_encode($params);
        // md5
        $md5 = md5($str);
        return $md5;
    }

    /**
     * Retourne les filtres
     *
     * @return \stdClass
     */
    public static function getFilters($p_filters, $p_sortCols, $p_from = 0, $p_len = 0,
        $p_included = '', $p_fields = '', $p_mode = 'EQUAL', $p_andor = 'AND')
    {
        // On va essayer d'attribuer un identifiant de filtre.
        // L'appelant a passé un query_id qui est censé identifier une liste côté FO.
        // Il existe 2 cas : on a déjà été appelé avec cet ID, ou pas.
        // * Si non appelé, c'est simple :
        //     On va enregistrer une ligne dans FreeFW_caches avec un md5 des critères et tris
        // * Si déjà appelé :
        //     Il faut vérifier que l'on a pas changé de critère, tri :
        //     On va engeristré les changements
        $queryId = false;
        if (array_key_exists('query_id', $p_filters)) {
            $queryId = 'query_id.' . $p_filters['query_id'];
        } else {
            $queryId = 'query_id.' . self::$user_key;
        }
        $cache = \FreeFW\Admin\Model\QueryCache::getById($queryId);
        $md5   = self::getMd5FromParams($p_filters, $p_sortCols, $p_mode, $p_andor);
        if ($cache === false) {
            $cache = new \FreeFW\Admin\Model\QueryCache();
            $cache
                ->generateContent($md5)
                ->setCachId($queryId)
                ->setCachRole('find')
            ;
            $cache->create(false, false);
        } else {
            $oldMd5 = $cache->getMd5();
            if ($oldMd5 != $md5) {
                $cache->generateContent($md5);
                $cache->save();
            }
        }
        $filters             = new \stdClass();
        $filters->champs     = json_encode($p_filters);
        $filters->id_filtre  = $cache->getTabKey();
        $filters->depuis     = $p_from;
        $filters->longueur   = $p_len;
        $filters->included   = $p_included;
        $filters->tri        = implode(',', $p_sortCols);
        $filters->util_id    = self::$user_key;
        $filters->rech_mode  = $p_mode;
        $filters->rech_andor = $p_andor;
        return $filters;
    }

    /**
     * Recherche
     *
     * @param array  $p_filters   // Tableau de propertyName => value
     * @param array  $p_sortCols  // Tableau de propertyName => ASC/DESC
     * @param array  $p_groupCols // Tableau de propertyName
     * @param number $p_from      // Indice de départ, commence à 0
     * @param number $p_len       // Longueur de recherche, 0 pour illimité
     * @param string $p_fulltext  // Chaine à rechercher dans les champs type fulltext
     * @param string $p_included  // Liste des éléments à inclure
     * @param string $p_fields    // Pour limiter une liste de champs
     * @param string $p_mode      // Mode de recherche : EQUAL / LIKE
     * @param string $p_andor     // Clauses : AND / OR
     *
     * @return \Iterator
     */
    public static function find(
        $p_filters = array(),
        $p_sortCols = array(),
        $p_groupCols = array(),
        $p_from = 0,
        $p_len = 0,
        $p_fulltext = null,
        $p_included = "",
        $p_fields = "",
        $p_mode = "EQUAL",
        $p_andor = "AND"
    ) {
        $resultSet = new \FreeFW\Model\ResultSet();
        $class     = self::getModelClass();
        if ($class !== false) {
            $data  = $class::getSource();
            $parts = explode('::', $data);
            if (count($parts) > 1) {
                $methodClass = $parts[1];
                $wsdl        = $parts[0];
            } else {
                $methodClass = $parts[0];
                $wsdl        = 'wsdl';
            }
            $cols   = array_flip($class::columnMap());
            $ids    = $class::columnId();
            $method = 'filtrer' . \FreeFW\Tools\PBXString::toCamelCase($methodClass, true);
            $result = null;
            //
            try {
                $soapClient = \FreeFW\Client\Soap::getInstance(self::getWsdlConfig());
                $auth       = self::getAuth();
                $criteres   = self::getFilters($p_filters, $p_sortCols, $p_from, $p_len, $p_included, $p_fields, $p_mode, $p_andor);
                self::debug('SOAP BEFORE CALL ' . $method);
                $result     = $soapClient->call($method, array('auth' => $auth, 'criteres' => $criteres));
                self::debug('SOAP AFTER CALL ' . $method);
                if ($result) {
                    $status = intval($result->me_status);
                    if ($status >= 200 && $status < 400) {
                        $real_result = json_decode($result->ms_response, true);
                        if ($real_result !== false) {
                            foreach ($real_result as $idx => $oneRow) {
                                $obj = $class::getInstance($oneRow);
                                $resultSet->add($obj);
                                // On va parcourir les données annexes...
                            }
                        }
                    }
                } else {
                    // @TODO
                }
                self::debug('SOAP AFTER DATA ' . $method);
            } catch (\Exception $ex) {
                // @todo
                self::error(print_r($ex, true));
                throw new \Exception("SOAP call error : " . $ex->getMessage());
            }
        }
        return $resultSet;
    }

    /**
     * Retourne un enregistrement en fonction de son identifiant(s)
     *
     * @param array $p_values
     *
     * @return object
     */
    public static function findById($p_values = array())
    {
        $resultSet = new \FreeFW\Model\ResultSet();
        $class     = self::getModelClass();
        if ($class !== false) {
            $data  = $class::getSource();
            $parts = explode('::', $data);
            if (count($parts) > 1) {
                $methodClass = $parts[1];
                $wsdl        = $parts[0];
            } else {
                $methodClass = $parts[0];
                $wsdl        = 'wsdl';
            }
            $cols   = array_flip($class::columnMap());
            $ids    = $class::columnId();
            $method = 'lire' . \FreeFW\Tools\PBXString::toCamelCase($methodClass, true) . 'AvecIdentifiant';
            $result = null;
            //
            try {
                $soapClient = \FreeFW\Client\Soap::getInstance(self::getWsdlConfig());
                $value      = null;
                $idCol      = 'id';
                foreach ($p_values as $idx => $val) {
                    $idCol = $idx;
                    $value = $val;
                }
                $auth = self::getAuth();
                $result = $soapClient->call($method, array('auth' => $auth, $idCol => $value));
                if ($result !== false) {
                    $status = intval($result->me_status);
                    if ($status >= 200 && $status < 400) {
                        $real_result = json_decode($result->ms_response, true);
                        if ($real_result !== false) {
                            $obj = $class::getInstance($real_result);
                            $resultSet->add($obj);
                        }
                    }
                } else {

                }
            } catch (\Exception $ex) {
                // @todo
            }
        }
        return $resultSet;
    }

    /**
     * Retourne un enregistrement en fonction de son identifiant(s)
     *
     * @param array $p_values
     *
     * @return object
     */
    public static function getById($p_values = array())
    {
        if (!is_array($p_values)) {
            $ids      = static::columnId();
            $p_values = array($ids[0] => $p_values);
        }
        $list = self::findById($p_values);
        if (count($list) == 1) {
            $list->rewind();
            return $list->current();
        }
        return false;
    }

    /**
     * Enregistrement de la modification des données
     *
     * @return boolean
     */
    public function save($p_raw = false, $p_withTransaction = true)
    {
        $result = false;
        $class  = self::getModelClass();
        if ($class !== false) {
            $data  = $class::getSource();
            $parts = explode('::', $data);
            if (count($parts) > 1) {
                $methodClass = $parts[1];
                $wsdl        = $parts[0];
            } else {
                $methodClass = $parts[0];
                $wsdl        = 'wsdl';
            }
            $next = true;
            if (method_exists($this, 'beforeSave')) {
                $next = $this->beforeSave();
            }
            $ids    = $class::columnId();
            $idCol  = 'id';
            $id     = null;
            foreach ($ids as $idx => $val) {
                $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($val, true);
                $idCol  = $val;
                $id     = $this->$getter();
            }
            if ($next) {
                // Appel du WS
                try {
                    $soapClient     = \FreeFW\Client\Soap::getInstance(self::getWsdlConfig());
                    $auth           = self::getAuth();
                    $method         = 'modifier' . \FreeFW\Tools\PBXString::toCamelCase($methodClass, true);
                    $body           = new \stdClass();
                    $body->util_id  = self::$user_key;
                    $body->content  = json_encode($this);
                    $body->included = '';
                    $wsResult       = $soapClient->call($method, array('auth' => $auth, $idCol => $id, 'body' => $body));
                    $status         = intval($wsResult->me_status);
                    if ($status >= 200 && $status < 400) {
                        $real_result = json_decode($wsResult->ms_response, true);
                        if ($real_result !== false) {
                            $this->getFromRecord($real_result);
                            // Suite
                            if (method_exists($this, 'afterSave')) {
                                $result = $this->afterCreate();
                            } else {
                                $result = true;
                            }
                        }
                    } else {
                        // @TODO : get errors.... from included_errors array(message+champ+code)
                        $this->addValidationError(0, '', 'Erreur');
                    }
                } catch (\Exception $ex) {
                    // @todo
                    self::error(print_r($ex, true));
                    $this->addValidationError($ex->getCode(), '', $ex->getMessage());
                }
            }
        }
        return $result;
    }

    /**
     * Regarde si un enregistrement existe
     * pour le champ $param
     *
     * @$objectID   : id de l'element
     * @$objectName : nom du champ testé
     *
     * @return boolean
     */
    public static function exist($objectName, $objectID)
    {
        $bool1 = false;
        $bool2 = false;
        foreach (self::file as $key => $value) {
            foreach ($value as $id => $val) {
                if ($key == 'lock_object_id' && $val == $objectID) {
                    $bool1 = true;
                }
                if ($key == 'lock_object_name' && $val == $objectName) {
                    $bool2 = true;
                }
            }
            if ($bool1 && $bool2) {
                return true;
            }
        }
        return false;
    }

     /**
     * Supprime un élément en fonction de son id
     *
     * gestion de suppression par id simple
     *
     * @param number $id // id de l'enregistrement à supprimer
     *
     * @return boolean
     */
    public static function deleteById($id)
    {
        //On récupère la classe appelante
        $class = self::getModelClass();
        $class = $class . "_id";
        $file  = self::file;
        foreach (self::file as $key => $value) {
            foreach ($value as $id => $val) {
                if ($key == $class && $val == $id) {
                    unset($file[$key]);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Enregistrement du résultat : insert
     *
     * @param boolean $p_withTransaction
     *
     * @return boolean
     */
    public function create($p_withTransaction = true)
    {
        $result = false;
        $class  = self::getModelClass();
        if ($class !== false) {
            $data  = $class::getSource();
            $parts = explode('::', $data);
            if (count($parts) > 1) {
                $methodClass = $parts[1];
                $wsdl        = $parts[0];
            } else {
                $methodClass = $parts[0];
                $wsdl        = 'wsdl';
            }
            $next = true;
            if (method_exists($this, 'beforeSave')) {
                $next = $this->beforeSave();
            }
            if ($next) {
                // Appel du WS
                try {
                    $soapClient     = \FreeFW\Client\Soap::getInstance(self::getWsdlConfig());
                    $auth           = self::getAuth();
                    $method         = 'creer' . \FreeFW\Tools\PBXString::toCamelCase($methodClass, true);
                    $body           = new \stdClass();
                    $body->util_id  = self::$user_key;
                    $body->content  = json_encode($this->__toArray());
                    $body->included = '';
                    $wsResult       = $soapClient->call($method, array('auth' => $auth, 'body' => $body));
                    var_dump($wsResult);die;
                    $status         = intval($wsResult->me_status);
                    if ($status >= 200 && $status < 400) {
                        $real_result = json_decode($wsResult->ms_response, true);
                        if ($real_result !== false) {
                            $this->getFromRecord($real_result);
                            // Suite
                            if (method_exists($this, 'afterCreate')) {
                                $result = $this->afterSave();
                            } else {
                                $result = true;
                            }
                        }
                    } else {
                        // @TODO : gat errors.... from included_errors array(message+champ+code)
                    }
                } catch (\Exception $ex) {
                    self::error(print_r($ex, true));
                    // @todo
                }
            }
        }
        return $result;
    }
}
