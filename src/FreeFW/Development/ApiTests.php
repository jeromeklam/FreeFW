<?php
namespace FreeFW\Development;

use FreeFW\Tools\PBXString;

/**
 *
 * @author jerome.klam
 *
 */
class ApiTests extends \FreeFW\Development\Api\Api
{

    /**
     * Répertoire de base
     * @var string
     */
    protected $base_dir = null;

    /**
     * Préfixe du namespace
     * @var string
     */
    protected $ns = null;

    /**
     * Constructeur
     *
     * @param string $p_filename
     */
    public function __construct()
    {
    }

    /**
     * Génération des tests
     *
     * @param \FreeFW\Development\Api\Server  $p_server
     * @param \FreeFW\Development\Api\Version $p_version
     * @param array                               $p_tests
     * @param string                              $p_packageName
     * @param \FreeFW\Stream\BufferStream     $p_stream
     * @param string                              $p_destPath
     */
    protected function generateTests($p_server, $p_version, $p_tests, $p_packageName, $p_stream, $p_mockPath, $p_destPath)
    {
        foreach ($p_version->getAll() as $idxR => $oneRoute) {
            if ($oneRoute->getPackage() == $p_packageName) {
                if (array_key_exists($oneRoute->getName(), $p_tests)) {
                    foreach ($p_tests[$oneRoute->getName()] as $idxT => $oneTest) {
                        $fctName = \FreeFW\Tools\PBXString::toCamelCase(
                            str_replace([':', '-'], '_', $oneRoute->getName()),
                            true
                            ) . '_' .
                            \FreeFW\Tools\PBXString::toCamelCase(
                                str_replace([':', '-'], '', $idxT),
                                true
                            )
                        ;
                        $copy = rtrim($p_mockPath, '/') . '/results/' . $oneRoute->getVersion();
                        $oneTest = array_merge(
                            [
                                'auth' => false
                            ],
                            $oneTest
                        );
                        $p_stream->writeLn('');
                        $p_stream->writeLn('    /**');
                        $p_stream->writeLn('     * Titre du service web : ' .
                            $oneRoute->getTitle());
                        $p_stream->writeLn('     */');
                        $p_stream->writeLn('    public function test_' . $fctName . '()');
                        $p_stream->writeLn('    {');
                        if ($oneTest['auth']) {
                            $p_stream->writeLn('        $client   = $this->getClient(\'' .
                                $oneTest['auth'] . '\');');
                        } else {
                            $p_stream->writeLn('        $client   = $this->getClient(false);');
                        }
                        $p_stream->writeLn('        $response = $client->request(');
                        $p_stream->writeLn('            \'' . $oneRoute->getFirstMethod() .
                            '\',');
                        $uri = [];
                        if (array_key_exists('uri', $oneTest)) {
                            $uri = $oneTest['uri'];
                        }
                        $query = '';
                        if (array_key_exists('query', $oneTest)) {
                            $queries = $oneTest['query'];
                            $query   = '?' . http_build_query($queries);
                        }
                        $p_stream->writeLn('            \'' . rtrim($p_server->getUrl(), '/') .
                            '/' . ltrim($oneRoute->renderHref($uri), '/') . $query . '\','
                        );
                        $p_stream->writeLn('            [');
                        $p_stream->writeLn('                \'http_errors\' => false');
                        if (array_key_exists('params', $oneTest)) {
                            $p_stream->writeLn('                ,\'form_params\' => ');
                            $p_stream->writeLn(var_export($oneTest['params'], true));
                        }
                        $p_stream->writeLn('            ]');
                        $p_stream->writeLn('        );');
                        $p_stream->writeLn('        $this->assertEquals(' .
                            $oneTest['status'] . ', $response->getStatusCode()' .
                            ');'
                        );
                        if (array_key_exists('body', $oneTest)) {
                            $dir1  = str_replace(APP_ROOT, '', $copy);
                            $dir2  = str_replace(APP_ROOT, '', $p_destPath);
                            $fname = str_replace(':', '_', $oneTest['body']) . '.json';
                            $p_stream->writeLn('        $file = APP_ROOT . \'' . rtrim($dir2, '/') . '/' . $fname . '\';');
                            $p_stream->writeLn('        $body = $response->getBody();');
                            $p_stream->writeLn('        if ($this->isRecording()) {');
                            $p_stream->writeLn('            file_put_contents($file, $body->getContents());');
                            $p_stream->writeLn('            copy($file, APP_ROOT . \'' . $dir1 . '/' . $fname . '\');');
                            $p_stream->writeLn('        } else {');
                            $p_stream->writeLn('            $this->assertFileExists($file);');
                            $p_stream->writeLn('            $cmp = file_get_contents($file);');
                            $p_stream->writeLn('            $this->assertJsonStringEqualsJsonString(');
                            $p_stream->writeLn('                $body->getContents(),');
                            $p_stream->writeLn('                $cmp');
                            $p_stream->writeLn('            );');
                            $p_stream->writeLn('        }');
                        }
                        $p_stream->writeLn('    }');
                    }
                }
            }
        }
    }

    /**
     * Génération des mocks
     *
     * @param \FreeFW\Development\Api\Server  $p_server
     * @param \FreeFW\Development\Api\Version $p_version
     * @param array                               $p_tests
     * @param string                              $p_packageName
     * @param \FreeFW\Stream\BufferStream     $p_stream
     * @param string                              $p_mockPath
     * @param string                              $p_testPath
     */
    protected function generateMocks($p_server, $p_version, $p_tests, $p_packageName, $p_stream, $p_mockPath, $p_destPath)
    {
        foreach ($p_version->getAll() as $idxR => $oneRoute) {
            if ($oneRoute->getPackage() == $p_packageName) {
                if (array_key_exists($oneRoute->getName(), $p_tests)) {
                    foreach ($p_tests[$oneRoute->getName()] as $idxT => $oneTest) {
                        $mockPath = rtrim($p_mockPath, '/') . '/' . ltrim($oneRoute->getUrl(), '/');
                        $mockPath = str_replace(':', '@', $mockPath);
                        \FreeFW\Tools\Dir::mkpath($mockPath);
                        $mockFile = $oneRoute->getFirstMethod();
                        $auth     = false;
                        if (array_key_exists('auth', $oneTest)) {
                            $auth = $oneTest['auth'];
                        }
                        $headers = $p_server->getOutHeaders($auth);
                        if (count($headers) > 0) {
                            foreach ($headers as $keyH => $valH) {
                                $mockFile .= '_' . $keyH . '=' . $valH['value'];
                            }
                        }
                        $mockFile = str_replace(':', '_', $oneRoute->getName() . '_' . $idxT) . '.mock';
                        $mockDesc = str_replace(':', '_', $oneRoute->getName() . '_' . $idxT) . '.json';
                        $message  = \FreeFW\Http\Response::getReasonMessageForCode($oneTest['status']);
                        $stream = new \FreeFW\Stream\BufferStream();
                        $stream->writeLn('HTTP/1.1 ' . $oneTest['status'] . ' ' . $message);
                        $stream->writeLn('Content-Type: "application/vnd.api+json; charset=utf-8;"');
                        if (array_key_exists('body', $oneTest)) {
                            $stream->writeLn('');
                            $fname    = str_replace(':', '_', $oneTest['body']) . '.json';
                            $stream->writeLn("#import '/" . $oneRoute->getVersion() . '/' . $fname . "';");
                        }
                        //var_dump(rtrim($mockPath, '/') . '/' . $mockFile, rtrim($mockPath, '/') . '/' . $mockDesc);
                        file_put_contents(
                            rtrim($mockPath, '/') . '/' . $mockFile,
                            $stream->getContents()
                        );
                        $uri = [];
                        if (array_key_exists('uri', $oneTest)) {
                            $uri = $oneTest['uri'];
                        }
                        $query = [];
                        if (array_key_exists('query', $oneTest)) {
                            $query = $oneTest['query'];
                        }
                        $object = $oneRoute->createMockRoute($uri, $query);
                        file_put_contents(
                            rtrim($mockPath, '/') . '/' . $mockDesc,
                            json_encode($object, JSON_PRETTY_PRINT)
                        );
                    }
                }
            }
        }
    }

    /**
     * Retourne le contenu au format swagger 3
     *
     * @return string
     */
    protected function generate()
    {
        // Generation for each server
        $servers = $this->getServers();
        foreach ($servers as $idxS => $oneServer) {
            $dest = $oneServer->getTests();
            if ($dest !== false) {
                $version = $this->getLastVersion();
                foreach ($version->getModules() as $idxM => $oneModule) {
                    $basePath = $oneModule['basePath'];
                    $versPath = strtoupper($oneModule['apiVers']);
                    $destPath = rtrim($dest['path'], '/') . '/' .
                                $versPath . '/' . str_replace('.', '/', $oneModule['ns']);
                    $testPath = rtrim($dest['tests'], '/') . '/' .
                                $versPath . '/' . str_replace('.', '/', $oneModule['ns']);
                    $mockPath = rtrim($dest['mock'], '/') . '/';
                    // Création d'une classe de test par package
                    $destNs   = rtrim($dest['ns'], '\\') . '\\' . $versPath . '\\' .
                                str_replace('.', '\\', $oneModule['ns']);
                    $packages = $oneModule['packages'];
                    \FreeFW\Tools\Dir::remove($destPath);
                    //\FreeFW\Tools\Dir::remove($mockPath);
                    \FreeFW\Tools\Dir::mkpath($destPath);
                    \FreeFW\Tools\Dir::mkpath($mockPath);
                    foreach ($packages as $idxP => $onePackage) {
                        $className = str_replace('_', '', \FreeFW\Tools\PBXString::toCamelCase($idxP, true));
                        $file      = rtrim($destPath, '/') . '/' . $className . 'Test.php';
                        $ns        = rtrim($destNs, '\\');
                        $tests     = rtrim($testPath, '/') . '/' . $className . '.php';
                        if (is_file($tests)) {
                            $todoTests = include($tests);
                            $stream    = new \FreeFW\Stream\BufferStream();
                            $stream->writeLn('<?php');
                            $stream->writeLn('namespace ' . ltrim($ns, '\\') . ';');
                            $stream->writeLn('');
                            $stream->writeLn('class ' . $className . 'Test' .
                                             ' extends \FreeFW\Development\Tests\ApiTestBase {');
                            if (is_array($todoTests)) {
                                $this->generateTests($oneServer, $version, $todoTests, $idxP, $stream, $mockPath, $destPath);
                                $this->generateMocks($oneServer, $version, $todoTests, $idxP, $stream, $mockPath, $destPath);
                            }
                            $stream->writeLn('}');
                            file_put_contents($file, $stream->getContents());
                        }
                    }
                }
            }
        }
    }

    /**
     * Génération complète
     *
     * @return void
     */
    public function generateAll()
    {
        $this->load();
        $this->generate();
    }
}
