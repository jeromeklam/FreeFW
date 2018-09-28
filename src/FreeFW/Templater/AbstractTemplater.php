<?php
namespace FreeFW\Templater;

abstract class AbstractTemplater
{

    /**
     * Namespaces
     *
     * @var array
     */
    protected $namespaces = array();

    /**
     * Enregistrement d'un namespace
     *
     * @param string  $p_name
     * @param string  $p_dir;
     * @param boolean $p_failOnError
     *
     * @return \FreeFW\Templater\Twig
     */
    public function registerNamespace($p_name, $p_dir, $p_failOnError = false)
    {
        if (is_dir($p_dir) || $p_failOnError === true) {
            $this->namespaces[$p_name] = $p_dir;
        }
        return $this;
    }
}
