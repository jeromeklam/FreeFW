<?php
namespace FreeFW\Development\Tests;

/**
 *
 * @author jerome.klam
 *
 */
class ApiTestBase extends \PHPUnit\Framework\TestCase {

    /**
     * Config
     * @var \FreeFW\Application\Config
     */
    protected static $config = null;

    /**
     * Server Config
     * @var array
     */
    protected static $server = null;

    /**
     * Recording ??
     * @var string
     */
    protected static $recording = false;

    /*
     * Auth token
     * @var string
     */
    protected static $token = null;

    /**
     * Recording or tests ?
     *
     * @return boolean
     */
    public function isRecording()
    {
        return self::$recording;
    }

    /**
     * Before all
     */
    public static function setUpBeforeClass()
    {
        $app = false;
        try {
            self::$config = \FreeFW\Application\Config::getInstance();
            $app = self::$config->get('app', false);
        } catch (\Exception $ex) {
            $app = false;
        }
        if ($app === false) {
            throw new \InvalidArgumentException('Configuration missing !');
        }
        $record = getenv('TEST_RECORDING');
        if ($record == 'ON') {
            self::$recording = true;
        }
        $server = getenv('TEST_SERVER');
        if ($server === false) {
            $server = 'docker';
        }
        self::$server = self::$config->get('app:servers:' . $server, false);
        if (self::$server === false) {
            throw new \InvalidArgumentException(sprintf('Configuration missing for %s server !', $server));
        }
    }

    /**
     * Get Auth token
     *
     * @todo check config, ....
     * @param string $p_authUser
     *
     * @return string
     */
    protected static function getAuthToken($p_authUser)
    {
        if (self::$token === null) {
            $headers = self::getHeaders(false);
            $params  = [
                'base_uri' => self::$server['url']
            ];
            if (count($headers) > 0) {
                $params['headers'] = $headers;
            }
            $client   = new \GuzzleHttp\Client($params);
            $data     = self::$server['auth']['users'][$p_authUser];
            $response = $client->request(
                'POST',
                '/api/v1/sso/signin',
                ['form_params' => $data]
            );
            if ($response->getStatusCode() != 200 && $response->getStatusCode() != 201) {
                throw new \Exception('Cannot login..., check config !');
            }
            $retHeaders = $response->getHeaders();
            if (!array_key_exists('authorization', $retHeaders)) {
                throw new \Exception('No auth header..., check config !');
            }
            self::$token = $retHeaders['authorization'][0];
        }
        return self::$token;
    }

    /**
     * Return all headers
     *
     * @param boolean $p_withAuth
     *
     * @return array
     */
    protected static function getHeaders()
    {
        $headers = [];
        if (array_key_exists('headers', self::$server)) {
            if (array_key_exists('in', self::$server['headers'])) {
                foreach (self::$server['headers']['in'] as $name => $content) {
                    $headers[$name] = $content['value'];
                }
            }
        }
        return $headers;
    }

    /**
     * Retourne un client
     *
     * @param mixed $p_authUser
     *
     * @return
     */
    public function getClient($p_authUser = false)
    {
        $headers = self::getHeaders();
        if ($p_authUser !== false) {
            if (!array_key_exists('auth', self::$server)) {
                throw new \InvalidArgumentException('Auth config missing !');
            }
            $name           = self::$server['auth']['name'];
            $token          = self::getAuthToken($p_authUser);
            $headers[$name] = sprintf(self::$server['auth']['value'], $token);
        }
        $params  = [
            'base_uri' => self::$server['url']
        ];
        if (count($headers) > 0) {
            $params['headers'] = $headers;
        }
        $client  = new \GuzzleHttp\Client($params);
        return $client;
    }

    /**
     * Before each test
     */
    protected function setUp()
    {
    }

    /**
     * After each test
     */
    protected function tearDown()
    {
    }

    /**
     * After all
     */
    public static function tearDownAfterClass()
    {
    }
}
