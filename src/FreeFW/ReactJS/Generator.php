<?php
namespace FreeFW\ReactJs;

class Generator
{

    /**
     * String
     * @var \FreeFW\Model\Model
     */
    protected $model = null;

    /**
     * Path
     * @var string
     */
    protected $path = null;

    /**
     * Nom de la fonctionnalité
     * @var string
     */
    protected $feature_name = null;

    /**
     * Words to replace
     * @var array
     */
    protected $words = [];

    /**
     * QuickSerach fields
     * @var array
     */
    protected $quickSearch = [];

    /**
     * DefaultSort fields
     * @var array
     */
    protected $defaultSort = [];

    /**
     * Constructor
     *
     * @param \FreeFW\Model\Model $p_model
     */
    public function __construct(\FreeFW\Model\Model $p_model)
    {
        $this->model = $p_model;
        $this->path  = $p_model->getMdPath();
    }

    /**
     * Generate full feature from model
     *
     * @return boolean
     */
    public function generateFeature()
    {
        $class = $this->model->getMdNs() . '::Model::' . $this->model->getMdClass();
        $model = \FreeFW\DI\DI::get($class);
        if ($model instanceof \FreeFW\Core\Model) {
            /**
             * Je commence par définir les principaux champs de remplacement
             */
            $mdNs    = $this->model->getMdNs();
            $mdName  = $this->model->getMdClass();
            $this->words['FEATURE_UPPER'] = strtoupper(\FreeFW\Tools\PBXString::fromCamelCase($mdName));
            $this->words['FEATURE_LOWER'] = strtolower(\FreeFW\Tools\PBXString::fromCamelCase($mdName));
            $this->words['FEATURE_MODEL'] = $mdNs . '_' . $mdName;
            $this->words['FEATURE_CAMEL'] = $mdNs . '_' . $mdName;
            $this->words['FEATURE_COLLECTION'] = $mdName;
            $this->words['FEATURE_SERVICE'] = \FreeFW\Tools\PBXString::fromCamelCase($this->model->getMdClass());
            /**
             * Il me faut aussi certains champs
             * Le tri par défaut
             */
            $this->defaultSort = [];
            if (method_exists($model, 'getAutocompleteField')) {
                $this->defaultSort = $model->getAutocompleteField();
                if (!is_array($this->defaultSort)) {
                    $this->defaultSort = explode(',', $this->defaultSort);
                }
            }
            /**
             * Les champs de recherche
             */
            $this->quickSearch = $this->defaultSort;


            var_dump($this->words, $this->defaultSort, $this->quickSearch);die;
        } else {
            throw new \Exception(sprintf('Model not found %s !', $this->model));
        }
        return true;
    }
}