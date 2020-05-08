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
     * Default version
     * @var string
     */
    protected $md_vers = null;

    /**
     * Fields
     * @var [\FreeFW\Model\Field]
     */
    protected $md_fields = [];

    /**
     * Collection path
     * @var string
     */
    protected $md_coll_path = null;

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
     * Set namespace
     *
     * @param string $p_namespace
     *
     * @return \FreeFW\Model\Model
     */
    public function setMdNs(string $p_namespace)
    {
        $this->md_ns = $p_namespace;
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
        $this->md_path = $p_path;
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
        $this->md_coll_path = $p_path;
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
                FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED]
            ],
            'md_source' => [
                FFCST::PROPERTY_PRIVATE => 'md_source',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
                FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED]
            ],
            'md_ns' => [
                FFCST::PROPERTY_PRIVATE => 'md_ns',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
                FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED]
            ],
            'md_vers' => [
                FFCST::PROPERTY_PRIVATE => 'md_vers',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
                FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED]
            ],
            'md_coll_path' => [
                FFCST::PROPERTY_PRIVATE => 'md_coll_path',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
                FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED]
            ],
            'md_path' => [
                FFCST::PROPERTY_PRIVATE => 'md_path',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
                FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED]
            ]
        ];
    }
}
