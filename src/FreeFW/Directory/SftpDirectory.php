<?php
namespace FreeFW\Directory;

class SftpDirectory implements \FreeFW\Interfaces\Directory
{

    /**
     * Connexion
     * @var resource
     */
    protected $connexion = null;

    /**
     * Les instances
     * @var array
     */
    protected static $instances = array();

    /**
     * Constructeur
     *
     * @param array $p_params
     */
    protected function __construct($p_params = array())
    {
        $sftp = new \phpseclib\Net\SFTP($p_params['server']);
        if (!$sftp->login($p_params['username'], $p_params['password'])) {
            throw new \Exception(
                sprintf(
                    'Login Failed %s:%s@%s',
                    $p_params['server'],
                    $p_params['username'],
                    $p_params['password']
                )
            );
        }
        if (array_key_exists('root', $p_params)) {
            $sftp->chdir($p_params['root']);
        }
        $pwd = $sftp->pwd();
        if (!strtoupper(rtrim($pwd, '/')) == strtoupper(rtrim($p_params['root'], '/'))) {
            throw new \Exception(sprintf('Can\'t chdir to %s !', $p_params['root']));
        }
        $this->connexion = $sftp;
    }

    /**
     * Retourne un nouvel élément
     *
     * @param string $p_name
     * @param array  $p_params
     *
     * @return \static
     */
    public static function getFactory($p_name, $p_params = array())
    {
        if (!array_key_exists($p_name, self::$instances)) {
            self::$instances[$p_name] = new self($p_params);
        }

        return self::$instances[$p_name];
    }

    /**
     * Retourne le contenu
     *
     * @return array
     */
    public function getContent()
    {
        return $this->connexion->rawlist();
    }

    /**
     * Récupère le fichier en local
     *
     * @param unknown $p_file
     * @param unknown $pdestFile
     *
     * @return boolean
     */
    public function getFile($p_file, $pdestFile)
    {
        $content = $this->connexion->get($p_file['filename']);
        file_put_contents($pdestFile, $content);

        return true;
    }

    /**
     * Supprime le fichier distant
     *
     * @param unknown $p_file
     *
     * @return boolean
     */
    public function delFile($p_file)
    {
        return delete($p_file);
    }

    /**
     * Déplace le fichier en local
     *
     * @param unknown $p_file
     * @param unknown $pdestFile
     *
     * @return boolean
     */
    public function putFile($p_file, $pdestFile)
    {
        $fileName = basename($p_file);
        return copy($p_file, $pdestFile."/".$fileName);
    }
}
