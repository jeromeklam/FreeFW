<?php
namespace FreeFW\ReactJS;

use \FreeFW\Constants as FFCST;

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
     * Default search
     * @var array
     */
    protected $defaultSearch = [];

    /**
     * Translations
     * @var array
     */
    protected $translations = [];

    /**
     * Constructor
     *
     * @param \FreeFW\Model\Model $p_model
     */
    public function __construct(\FreeFW\Model\Model $p_model)
    {
        $this->model = $p_model;
        $this->path  = trim($p_model->getMdPath());
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
             * Initialisation
             */
            $this->translations = [];
            /**
             * Je commence par définir les principaux champs de remplacement
             */
            $mdNs    = $this->model->getMdNs();
            $mdName  = $this->model->getMdClass();
            //
            $this->lower = trim(str_replace('_', '-', strtolower(\FreeFW\Tools\PBXString::fromCamelCase($mdName))));
            $this->words['FEATURE_UPPER']      = strtoupper(\FreeFW\Tools\PBXString::fromCamelCase($mdName));
            $this->words['FEATURE_LOWER']      = $this->lower;
            $this->words['FEATURE_MODEL']      = $mdNs . '_' . $mdName;
            $this->words['FEATURE_SNAKE']      = strtolower(\FreeFW\Tools\PBXString::fromCamelCase($mdName));
            $this->words['FEATURE_CAMEL']      = \FreeFW\Tools\PBXString::toCamelCase(\FreeFW\Tools\PBXString::fromCamelCase($mdName), false);
            $this->words['FEATURE_CAMEL_FULL'] = \FreeFW\Tools\PBXString::toCamelCase(\FreeFW\Tools\PBXString::fromCamelCase($mdName), true);
            $this->words['FEATURE_MAINCOL']    = $this->model->getMdMainCol();
            $this->words['FEATURE_COLLECTION'] = $this->model->getMdCollPath();
            $this->words['FEATURE_SERVICE']    = \FreeFW\Tools\PBXString::fromCamelCase($this->model->getMdClass());
            $this->words['FEATURE_INCLUDE']    = $this->getDefaultInclude($model);
            $this->words['FEATURE_ID_FIELD']   = $model->getFieldNameByOption(FFCST::OPTION_PK);
            /**
             *
             * @var Ambiguous $colsR
             */
            $colsR = $this->renderCols($model, $this->model->getMdMainCol());
            $this->words['FEATURE_COLS']       = $colsR['cols'];
            $this->words['FEATURE_COLS_IMP1']  = $colsR['import1'];
            $this->words['FEATURE_FIELDS']     = $this->renderFields($model, $this->model->getMdMainCol());
            /**
             * Il me faut aussi certains champs
             * Le tri par défaut
             */
            $this->defaultSort = [];
            if (method_exists($model, 'getSort')) {
                $this->defaultSort = $model->getSort();
            } else {
                if (method_exists($model, 'getAutocompleteField')) {
                    $this->defaultSort = $model->getAutocompleteField();
                }
            }
            if (!is_array($this->defaultSort)) {
                $this->defaultSort = explode(',', $this->defaultSort);
            }
            $sort = [];
            foreach ($this->defaultSort as $key => $value) {
                if (is_numeric($key)) {
                    $col = $value;
                    $way = 'up';
                    if (strpos($col, '-') === 0) {
                        $col = substr($col, 1);
                        $way = 'down';
                    }
                } else {
                    $col = $key;
                    $way = 'up';
                    if ($value == '-') {
                        $way = 'down';
                    }
                }
                if (trim($col) != '') {
                    $sortC = new \stdClass();
                    $sortC->col = $col;
                    $sortC->way = $way;
                    $sort[] = $sortC;
                }
            }
            $this->words['FEATURE_SORT'] = json_encode($sort);
            /**
             * Autocomplete
             */
            $this->defaultSearch = [];
            if (method_exists($model, 'getAutocompleteField')) {
                $this->defaultSearch = $model->getAutocompleteField();
            }
            if (!is_array($this->defaultSearch)) {
                $this->defaultSearch = explode(',', $this->defaultSearch);
            }
            $search = '';
            foreach ($this->defaultSearch as $value) {
                $search .= '        filters.addFilter(\'' . $value . '\', action.value);' . PHP_EOL;
            }
            $this->words['FEATURE_SEARCH'] = $search;
            /**
             * Traductions génériques
             */
            if (method_exists($model, 'getSourceTitle')) {
                $this->translations['app.features.' . $this->lower . '.list.title'] = $model::getSourceTitle();
            } else {
                $this->translations['app.features.' . $this->lower . '.list.title'] = $mdName;
            }
            $this->translations['app.features.' . $this->lower . '.list.search'] = "Recherche";
            $this->translations['app.features.' . $this->lower . '.form.tabs.ident'] = 'Identification';
            $this->translations['app.features.' . $this->lower . '.form.title'] = $model::getSourceTitle();
            /**
             * Je vais modifier pour chaque fichier du dossier tpl les variables
             * Seuls certains fichiers ont besoin de traitements spéciaux.
             */
            $directory = dirname(__FILE__) . '/tpl1';
            \FreeFW\Tools\Dir::remove($this->path . '/' . $this->lower);
            $files     = \FreeFW\Tools\Dir::recursiveDirectoryIterator($directory);
            $this->renderFiles($files, $this->path . '/' . $this->lower);
            file_put_contents($this->path . '/' . $this->lower . '/translations.json', json_encode($this->translations, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES));
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

    /**
     * Get deault include
     *
     * @param \FreeFW\Core\Model $p_model
     *
     * @return string
     */
    protected function getDefaultInclude(\FreeFW\Core\Model $p_model)
    {
        $includes   = [];
        $properties = $p_model::getProperties();
        foreach ($properties as $name => $oneProperty) {
            if (in_array(FFCST::OPTION_FK, $oneProperty[FFCST::PROPERTY_OPTIONS])) {
                foreach ($oneProperty[FFCST::PROPERTY_FK] as $name => $desc) {
                    $includes[] = $name;
                }
            }
        }
        return implode(',', $includes);
    }

    /**
     * Render cols
     *
     * @param \FreeFW\Core\Model $p_model
     * @param string           $p_main_col
     *
     * @return string
     */
    protected function renderCols(\FreeFW\Core\Model $p_model, $p_main_col = '')
    {
        $impEnum = '';
        $cols = '';
        $first = true;
        $properties = $p_model::getProperties();
        foreach ($properties as $name => $oneProperty) {
            if (in_array(FFCST::OPTION_JSONIGNORE, $oneProperty[FFCST::PROPERTY_OPTIONS])) {
                continue;
            }
            if (isset($oneProperty[FFCST::PROPERTY_DEPRECATED])) {
                continue;
            }
            $title   = isset($oneProperty[FFCST::PROPERTY_TITLE]) ? $oneProperty[FFCST::PROPERTY_TITLE] : $name;
            $comment = isset($oneProperty[FFCST::PROPERTY_COMMENT]) ? $oneProperty[FFCST::PROPERTY_COMMENT] : $name;
            // Go
            $cols .= '    {' . PHP_EOL;
            $cols .= '      name: \'' . $name . '\',' . PHP_EOL;
            $cols .= '      label: props.intl.formatMessage({' . PHP_EOL;
            $cols .= '        id: \'app.features.' . $this->lower . '.field.' . $name . '.label\',' . PHP_EOL;
            $cols .= '        defaultMessage: \'' . str_replace("'", "\\'", $title) . '\'' . PHP_EOL;
            $cols .= '      }),' . PHP_EOL;
            $cols .= '      shortLabel: props.intl.formatMessage({' . PHP_EOL;
            $cols .= '        id: \'app.features.' . $this->lower . '.field.' . $name . '.short\',' . PHP_EOL;
            $cols .= '        defaultMessage: \'' . str_replace("'", "\\'", $title) . '\'' . PHP_EOL;
            $cols .= '      }),' . PHP_EOL;
            $cols .= '      comment: props.intl.formatMessage({' . PHP_EOL;
            $cols .= '        id: \'app.features.' . $this->lower . '.field.' . $name . '.comment\',' . PHP_EOL;
            $cols .= '        defaultMessage: \'' . str_replace("'", "\\'", $comment) . '\'' . PHP_EOL;
            $cols .= '      }),' . PHP_EOL;
            $cols .= '      col: \'' . $name . '\',' . PHP_EOL;
            $cols .= '      size: {xxs: 36, sm: 12, md: 4 },' . PHP_EOL;
            $cols .= '      title: true,' . PHP_EOL;
            $cols .= '      sortable: true,' . PHP_EOL;
            if (in_array(FFCST::OPTION_PK, $oneProperty[FFCST::PROPERTY_OPTIONS])) {
                $cols .= '      hidden: true,' . PHP_EOL;
                $cols .= '      card: { role: \'ID\' },' . PHP_EOL;
            } else {
                if (in_array($name, ['agentcre','datecre','agentmod','datemod'])) {
                    $cols .= '      hidden: true,' . PHP_EOL;
                }
                if (strtolower($p_main_col) == strtolower($name)) {
                    $cols .= '      card: { role: \'TITLE\' },' . PHP_EOL;
                } else {
                    $cols .= '      card: { role: \'FIELD\' },' . PHP_EOL;
                }
                if ($first) {
                    $cols .= '      first: true,' . PHP_EOL;
                    $first = false;
                }
            }
            switch ($oneProperty[FFCST::PROPERTY_TYPE]) {
                case FFCST::TYPE_BOOLEAN:
                    $cols .= '      filterable:  { type: \'bool\'},' . PHP_EOL;
                    $cols .= '      type: \'bool\',' . PHP_EOL;
                    $cols .= '      values: displayColBool,' . PHP_EOL;
                    break;
                case FFCST::TYPE_DATETIMETZ:
                    $cols .= '      filterable:  { type: \'datetime\'},' . PHP_EOL;
                    $cols .= '      type: \'datetime\',' . PHP_EOL;
                    break;
                default:
                    $cols .= '      filterable: true,' . PHP_EOL;
                    break;
            }
            $cols .= '    },' . PHP_EOL;
            $title   = isset($oneProperty[FFCST::PROPERTY_TITLE]) ? $oneProperty[FFCST::PROPERTY_TITLE] : $name;
            $comment = isset($oneProperty[FFCST::PROPERTY_COMMENT]) ? $oneProperty[FFCST::PROPERTY_COMMENT] : $name;
            if ($title == '') {
                $title = $name;
            }
            if ($comment == '') {
                $comment = $name;
            }
            $this->translations['app.features.' . $this->lower . '.field.' . $name . '.label'] = $title;
            $this->translations['app.features.' . $this->lower . '.field.' . $name . '.short'] = $title;
            $this->translations['app.features.' . $this->lower . '.field.' . $name . '.comment'] = $comment;
        }
        return ['cols' => $cols, 'import1' => $impEnum];
    }

    /**
     * Render form fields
     *
     * @param \FreeFW\Core\Model $p_model
     * @param string           $p_main_col
     *
     * @return string
     */
    protected function renderFields(\FreeFW\Core\Model $p_model, $p_main_col = '')
    {
        $fields = '      <Row>' . PHP_EOL;
        $properties = $p_model::getProperties();
        foreach ($properties as $name => $oneProperty) {
            if (in_array($name, ['agentcre','datecre','agentmod','datemod'])) {
                continue;
            }
            if (in_array(FFCST::OPTION_PK, $oneProperty[FFCST::PROPERTY_OPTIONS])) {
                continue;
            }
            if (in_array(FFCST::OPTION_JSONIGNORE, $oneProperty[FFCST::PROPERTY_OPTIONS])) {
                continue;
            }
            if (isset($oneProperty[FFCST::PROPERTY_DEPRECATED])) {
                continue;
            }
            $fields .= '      <Col size={{xxs: 36, sm: 12}}>' . PHP_EOL;
            switch ($oneProperty[FFCST::PROPERTY_TYPE]) {
                case FFCST::TYPE_DATETIMETZ:
                    $fields .= '        <InputDatetime' . PHP_EOL;
                    $fields .= '          label={props.intl.formatMessage({' . PHP_EOL;
                    $fields .= '            id: \'app.features.' . $this->lower . '.field.' . $name . '.label\',' . PHP_EOL;
                    $fields .= '            defaultMessage: \'' . $name . '\',' . PHP_EOL;
                    $fields .= '          })}' . PHP_EOL;
                    $fields .= '          id="' . $name . '"' . PHP_EOL;
                    $fields .= '          name="' . $name . '"' . PHP_EOL;
                    $fields .= '          value={values.' . $name . '}' . PHP_EOL;
                    if (in_array(FFCST::OPTION_REQUIRED, $oneProperty[FFCST::PROPERTY_OPTIONS])) {
                        $fields .= '          required={true}' . PHP_EOL;
                    }
                    $fields .= '          labelTop={true}' . PHP_EOL;
                    $fields .= '          onChange={handleChange}' . PHP_EOL;
                    $fields .= '          error={getErrorMessage(\'' . $name . '\')}' . PHP_EOL;
                    $fields .= '        />' . PHP_EOL;
                    break;
                case FFCST::TYPE_BOOLEAN:
                    $fields .= '        <InputCheckbox' . PHP_EOL;
                    $fields .= '          label={props.intl.formatMessage({' . PHP_EOL;
                    $fields .= '            id: \'app.features.' . $this->lower . '.field.' . $name . '.label\',' . PHP_EOL;
                    $fields .= '            defaultMessage: \'' . $name . '\',' . PHP_EOL;
                    $fields .= '          })}' . PHP_EOL;
                    $fields .= '          id="' . $name . '"' . PHP_EOL;
                    $fields .= '          name="' . $name . '"' . PHP_EOL;
                    $fields .= '          checked={values.' . $name . ' === true}' . PHP_EOL;
                    if (in_array(FFCST::OPTION_REQUIRED, $oneProperty[FFCST::PROPERTY_OPTIONS])) {
                        $fields .= '          required={true}' . PHP_EOL;
                    }
                    $fields .= '          labelTop={true}' . PHP_EOL;
                    $fields .= '          onChange={handleChange}' . PHP_EOL;
                    $fields .= '          error={getErrorMessage(\'' . $name . '\')}' . PHP_EOL;
                    $fields .= '        />' . PHP_EOL;
                    break;
                default:
                    $fields .= '        <InputText' . PHP_EOL;
                    $fields .= '          label={props.intl.formatMessage({' . PHP_EOL;
                    $fields .= '            id: \'app.features.' . $this->lower . '.field.' . $name . '.label\',' . PHP_EOL;
                    $fields .= '            defaultMessage: \'' . $name . '\',' . PHP_EOL;
                    $fields .= '          })}' . PHP_EOL;
                    $fields .= '          id="' . $name . '"' . PHP_EOL;
                    $fields .= '          name="' . $name . '"' . PHP_EOL;
                    $fields .= '          value={values.' . $name . '}' . PHP_EOL;
                    if (in_array(FFCST::OPTION_REQUIRED, $oneProperty[FFCST::PROPERTY_OPTIONS])) {
                        $fields .= '          required={true}' . PHP_EOL;
                    }
                    $fields .= '          labelTop={true}' . PHP_EOL;
                    $fields .= '          onChange={handleChange}' . PHP_EOL;
                    $fields .= '          error={getErrorMessage(\'' . $name . '\')}' . PHP_EOL;
                    $fields .= '        />' . PHP_EOL;
                    break;
            }
            $fields .= '      </Col>' . PHP_EOL;
        }
        $fields .= '      </Row>' . PHP_EOL;
        return $fields;
    }
}