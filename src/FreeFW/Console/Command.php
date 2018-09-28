<?php
/**
 * Classe de base des commandes
 *
 * @autgor jeromeklam
 * @package Command
 */
namespace FreeFW\Console;

/**
 * Classe de base d'une commande
 * @author jeromeklam
 */
class Command
{

    /**
     * Comportements
     */
    use \FreeFW\Behaviour\DI;
    use \FreeFW\Behaviour\LoggerAwareTrait;
}
