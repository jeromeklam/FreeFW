<?php
/**
 * Interface Templater
 *
 * @author jeromeklam
 * @package Templater
 * @category Interface
 */
namespace FreeFW\Interfaces;

/**
 * Interface Templater
 * @author jeromeklam
 */
interface Templater
{
    /**
     * Génération
     *
     * @param string $p_templateFileName
     * @param array  $p_params
     *
     * @return string
     */
    public function render($p_templateFileName, $p_params);
}
