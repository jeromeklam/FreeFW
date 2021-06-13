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
            //
            $this->words['FEATURE_UPPER']      = strtoupper(\FreeFW\Tools\PBXString::fromCamelCase($mdName));
            $this->words['FEATURE_LOWER']      = strtolower(\FreeFW\Tools\PBXString::fromCamelCase($mdName));
            $this->words['FEATURE_MODEL']      = $mdNs . '_' . $mdName;
            $this->words['FEATURE_CAMEL']      = $mdName;
            $this->words['FEATURE_COLLECTION'] = $this->model->getMdCollPath();
            $this->words['FEATURE_SERVICE']    = \FreeFW\Tools\PBXString::fromCamelCase($this->model->getMdClass());
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
            /**
             * Je vais modifier pour chaque fichier du dossier tpl les variables
             * Seuls certains fichiers ont besoin de traitements spéciaux.
             */
            $directory = dirname(__FILE__) . '/tpl1';
            $files     = \FreeFW\Tools\Dir::recursiveDirectoryIterator($directory);
            $this->renderFiles($files, $this->path . '/' . $this->words['FEATURE_LOWER']);
        } else {
            throw new \Exception(sprintf('Model not found %s !', $this->model));
        }
        return true;
    }

    /**
     *
     * @param array $p_files
     * @param string $p_directory
     */
    protected function renderFiles($p_files, $p_directory)
    {
        foreach ($p_files as $name => $content) {
            if (is_array($content)) {
                $this->renderFiles($content, rtrim($p_directory, '/') . '/' . $name);
            } else {
                $data = file_get_contents($content->getPath() . '/' . $content->getFilename());
                $data = \FreeFW\Tools\PBXString::parse($data, $this->words);
                \FreeFW\Tools\Dir::mkpath($p_directory);
                file_put_contents($p_directory . '/' . $content->getFilename(), $data);
            }
        }
    }
}