<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Model
 *
 * @author jeromeklam
 */
class Model extends \FreeFW\Core\Model
{

    /**
     * Model classname
     * @var string
     */
    protected $md_class = null;

    /**
     * Source
     * @var string
     */
    protected $md_source = null;

    /**
     * Namespace
     * @var string
     */
    protected $md_ns = null;

    /**
     * Path
     * @var string
     */
    protected $md_path =  null;

    /**
     * File
     * @var string
     */
    protected $md_file = null;

    /**
     * Default version
     * @var string
     */
    protected $md_vers = 'v1';

    /**
     * Fields
     * @var [\FreeFW\Model\Field]
     */
    protected $md_fields = [];

    /**
     * Collection path
     * @var string
     */
    protected $md_coll_path = 'prj_mgnt';

    /**
     * Scope
     * @var string
     */
    protected $md_scope = null;

    /**
     * Html rendering ?
     * @var bool
     */
    protected $html_rendering = null;

    /**
     * Main col
     * @var string
     */
    protected $md_main_col = null;

    /**
     * Sort
     * @var array
     */
    protected $md_sort = [];

    /**
     * Set classname
     *
     * @param string $p_class
     *
     * @return \FreeFW\Model\Model
     */
    public function setMdClass(string $p_class)
    {
        $this->md_class = $p_class;
        return $this;
    }

    /**
     * Get classname
     *
     * @return string
     */
    public function getMdClass()
    {
        return $this->md_class;
    }

    /**
     * Set source
     *
     * @param string $p_source
     *
     * @return \FreeFW\Model\Model
     */
    public function setMdSource(string $p_source)
    {
        $this->md_source = $p_source;
        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getMdSource()
    {
        return $this->md_source;
    }

    /**
     * Set storage
     *
     * @param string $p_storage
     *
     * @return \FreeFW\Model\Model
     */
     public function setMdStorage(string $p_storage)
    {
        $this->md_storage = trim($p_storage);
        return $this;
    }

    /**
     * Get storage
     */
    public function getMdStorage()
    {
        return (empty($this->md_storage) ? 'default' : $this->md_storage);
    }

    /**
     * Set namespace
     *
     * @param string $p_namespace
     *
     * @return \FreeFW\Model\Model
     */
    public function setMdNs(string $p_namespace)
    {
        $this->md_ns = trim($p_namespace);
        return $this;
    }

    /**
     * Get namespace
     *
     * @return string
     */
    public function getMdNs()
    {
        return $this->md_ns;
    }

    /**
     * Set path
     *
     * @param string $p_path
     *
     * @return \FreeFW\Model\Model
     */
    public function setMdPath(string $p_path)
    {
        $this->md_path = trim($p_path);
        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getMdPath()
    {
        return $this->md_path;
    }

    /**
     * Set fields
     *
     * @param array $p_fields
     *
     * @return \FreeFW\Model\Model
     */
    public function setMdFields(array $p_fields)
    {
        $this->md_fields = $p_fields;
        return $this;
    }

    /**
     * Get fields
     *
     * @return array
     */
    public function getMdFields()
    {
        return $this->md_fields;
    }

    /**
     * Set version
     *
     * @param string $p_vers
     */
    public function setMdVers($p_vers)
    {
        $this->md_vers = $p_vers;
        return $this;
    }

    /**
     * Get version
     *
     * @return string
     */
    public function getMdVers()
    {
        return $this->md_vers;
    }

    /**
     * Set collection path
     *
     * @param string $p_path
     *
     * @return \FreeFW\Model\Model
     */
    public function setMdCollPath($p_path)
    {
        $this->md_coll_path = str_replace('\\','/',rtrim(trim($p_path),'\\'));
        return $this;
    }

    /**
     * Get collection path
     *
     * @return string
     */
    public function getMdCollPath()
    {
        return $this->md_coll_path;
    }

    /**
     * Set scope
     *
     * @param mixed $p_scope
     *
     * @return \FreeFW\Model\Model
     */
    public function setMdScope($p_scope)
    {
        $this->md_scope = null;
        if (is_array($p_scope)) {
            $this->md_scope = $p_scope;
        } else {
            if (trim($p_scope) != '') {
                $this->md_scope = explode(',', $p_scope);
            }
        }
        return $this;
    }

    /**
     * get Scope
     *
     * @return array
     */
    public function getMdScope()
    {
        return $this->md_scope;
    }

    /**
     * Set main col
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Model
     */
    public function setMdMainCol($p_value)
    {
        $this->md_main_col = $p_value;
        return $this;
    }

    /**
     * Get main col
     *
     * @return string
     */
    public function getMdMainCol()
    {
        return $this->md_main_col;
    }

    /**
     * Set sort
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Model
     */
    public function setMdSort($p_value)
    {
        $this->md_sort = $p_value;
        if (!is_array($p_value)) {
            $this->md_sort = [];
            $cols = explode(',', $p_value);
            foreach ($cols as $oneCol) {
                $name = trim($oneCol);
                $desc = false;
                if (substr($oneCol, 0, 1) == '-') {
                    $desc = true;
                    $name = trim(substr($oneCol, 1));
                } else {
                    if (substr($oneCol, 0, 1) == '+') {
                        $name = trim(substr($oneCol, 1));
                    }
                }
                if ($desc) {
                    $this->md_sort[$name] = \FreeFW\Storage\Storage::SORT_DESC;
                } else {
                    $this->md_sort[$name] = \FreeFW\Storage\Storage::SORT_ASC;
                }
            }
        }
        return $this;
    }

    /**
     * Get sort
     *
     * @return array|unknown
     */
    public function getMdSort()
    {
        return $this->md_sort;
    }

    /**
     * Set html rendering
     *
     * @param bool $p_html_rendering
     */
    public function setHtmlRendering($p_html_rendering)
    {
        $this->html_rendering = $p_html_rendering;
        return $this;
    }

    /**
     * Get version
     *
     * @return bool
     */
    public function getHtmlRendering()
    {
        return $this->html_rendering;
    }

    /**
     * Set md_file
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Model
     */
    public function setMdFile($p_value)
    {
        $this->md_file = $p_value;
        return $this;
    }

    /**
     * Get md_file
     *
     * @return string
     */
    public function getMdFile()
    {
        return $this->md_file;
    }

    /**
     * Get primary key field name
     *
     * @return string
     */
    public function getPrimaryFieldName()
    {
        /**
         * @var \FreeFW\Model\Field $field
         */
        foreach ($this->md_fields as $field) {
            if ($field->getFldPrimary()) {
                return $field->getFldName();
            }
        }
        return '';
    }

    /**
     * Get primary key field
     *
     * @return \FreeFW\Model\Field|false
     */
    public function getPrimaryField()
    {
        /**
         * @var \FreeFW\Model\Field $field
         */
        foreach ($this->md_fields as $field) {
            if ($field->getFldPrimary()) {
                return $field;
            }
        }
        return false;
    }

    /**
     * Get foreign key fields
     *
     * @return [\FreeFW\Model\Field]|false
     */
    public function getForeignKeyFields()
    {
        /**
         * @var [\FreeFW\Model\Field] $fields
         */
        $fields = null;

        /**
         * @var \FreeFW\Model\Field $field
         */
        foreach ($this->md_fields as $field) {
            if ($field->isForeignkey()) {
                $fields[] = $field;
            }
        }
        return (empty($fields) ? false : $fields);
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Core\Model::getProperties()
     */
    public static function getProperties()
    {
        return [
            'md_class' => [
                FFCST::PROPERTY_PRIVATE => 'md_class',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
                FFCST::PROPERTY_OPTIONS => [],
                FFCST::PROPERTY_COMMENT => 'Le nom de la classe, sans ns, en camelcase',
                FFCST::PROPERTY_SAMPLE  => 'User'
            ],
            'md_source' => [
                FFCST::PROPERTY_PRIVATE => 'md_source',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
                FFCST::PROPERTY_OPTIONS => [],
                FFCST::PROPERTY_COMMENT => 'Nom de la table dont on veut créer le modèle',
                FFCST::PROPERTY_SAMPLE  => 'sso_user'
            ],
            'md_storage' => [
                FFCST::PROPERTY_PRIVATE => 'md_storage',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
                FFCST::PROPERTY_OPTIONS => [],
                FFCST::PROPERTY_COMMENT => 'Nom du storage à utiliser pour accéder à la table',
                FFCST::PROPERTY_SAMPLE  => 'default'
            ],
            'md_ns' => [
                FFCST::PROPERTY_PRIVATE => 'md_ns',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
                FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED],
                FFCST::PROPERTY_COMMENT => 'L\'espace de nom',
                FFCST::PROPERTY_SAMPLE  => 'POSSO'
            ],
            'md_vers' => [
                FFCST::PROPERTY_PRIVATE => 'md_vers',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
                FFCST::PROPERTY_OPTIONS => [],
                FFCST::PROPERTY_COMMENT => 'La version éventuelle',
                FFCST::PROPERTY_SAMPLE  => 'v1'
            ],
            'md_coll_path' => [
                FFCST::PROPERTY_PRIVATE => 'md_coll_path',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
                FFCST::PROPERTY_OPTIONS => [],
                FFCST::PROPERTY_COMMENT => 'Le chemin pour une gestion de collection',
                FFCST::PROPERTY_SAMPLE  => 'sso/broker'
            ],
            'md_path' => [
                FFCST::PROPERTY_PRIVATE => 'md_path',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
                FFCST::PROPERTY_OPTIONS => [],
                FFCST::PROPERTY_COMMENT => 'Le chemin vers le répertoire src',
                FFCST::PROPERTY_SAMPLE  => '/var/www/html/src'
            ],
            'md_file' => [
                FFCST::PROPERTY_PRIVATE => 'md_file',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
                FFCST::PROPERTY_OPTIONS => [],
                FFCST::PROPERTY_COMMENT => 'Le nom du fichier',
                FFCST::PROPERTY_SAMPLE  => 'test.json'
            ],
            'md_scope' => [
                FFCST::PROPERTY_PRIVATE => 'md_scope',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
                FFCST::PROPERTY_OPTIONS => [],
                FFCST::PROPERTY_COMMENT => 'role par défaut pour toutes les routes',
                FFCST::PROPERTY_SAMPLE  => 'ADMIN,USER',
                FFCST::PROPERTY_DEFAULT => null,
            ],
            'md_main_col' => [
                FFCST::PROPERTY_PRIVATE => 'md_main_col',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
                FFCST::PROPERTY_OPTIONS => [],
                FFCST::PROPERTY_COMMENT => 'Colonne principale',
                FFCST::PROPERTY_SAMPLE  => 'FIELD1',
                FFCST::PROPERTY_DEFAULT => null,
            ],
            'md_sort' => [
                FFCST::PROPERTY_PRIVATE => 'md_sort',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
                FFCST::PROPERTY_OPTIONS => [],
                FFCST::PROPERTY_COMMENT => 'Colonnes de tri',
                FFCST::PROPERTY_SAMPLE  => '-FIELD1,FIELD2',
                FFCST::PROPERTY_DEFAULT => null,
            ],
            'html_rendering' => [
                FFCST::PROPERTY_PRIVATE => 'html_rendering',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_BOOLEAN,
                FFCST::PROPERTY_OPTIONS => [],
                FFCST::PROPERTY_COMMENT => 'rendu au format HTML',
                FFCST::PROPERTY_SAMPLE  => 'ADMIN,USER',
                FFCST::PROPERTY_DEFAULT => true,
            ],
        ];
    }
}
