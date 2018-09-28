<?php
/**
 * Classe de gestion des shells
 *
 * @author jeromeklam
 * @package Command
 */
namespace FreeFW\Tools;

/**
 * Class Shell
 * @author jeromeklam
 */
class Shell
{

    /**
     * Exécute une commande en tâche de fond
     *
     * @param string $cmd
     *
     * @return void
     */
    public static function execInBackground($cmd)
    {
        if (substr(php_uname(), 0, 7) == "Windows") {
            pclose(popen("start /B ". $cmd, "r"));
        } else {
            exec($cmd . " > /dev/null &");
        }
    }
}
