<?php
/**
 * Gestion des entrées de type paramètre
 *
 * @author jeromeklam
 * @package Console\Input
 */
namespace FreeFW\Console\Input;

/**
 * Classe des paramètres d'entrée de la console
 * @author klam
 */
abstract class AbstractInput
{

    /**
     * Lecture d'un paramètre
     *
     * @param string $p_name
     * @param mixed  $p_default
     *
     * @return mixed
     */
    abstract public function read($p_name, $p_default);
}
