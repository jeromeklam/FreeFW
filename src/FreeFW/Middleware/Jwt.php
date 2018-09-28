<?php
namespace FreeFW\Middleware;

use \Psr\Http\Message\StreamInterface;
use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Message\ResponseFactoryInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use \FreeFW\Http\Response;
use \Firebase\JWT\JWT as FireJWT;

/**
 *
 * @author jerome.klam
 *
 */
class Jwt extends \FreeFW\Middleware\Base implements MiddlewareInterface
{

    /**
     * Comportements
     */
    use \FreeFW\Behaviour\DI;
    use \FreeFW\Behaviour\LoggerAwareTrait;

    /**
     * Constants
     * @var string
     */
    const JWT_HEADER_NAME = 'authorization';

    /**
     * Local config
     * @var array
     */
    protected $config = null;

    /**
     * Constructeur
     */
    public function __construct()
    {
        $config       = self::getDIConfig();
        $this->config = $config->get('jwt');
    }

    /**
     * Before
     *
     * @param ServerRequestInterface $request
     *
     * @return mixed
     */
    protected function beforeProcess($request)
    {
        $user = null;
        if ($request->hasHeader(self::JWT_HEADER_NAME)) {
            $parts = explode(' ', $request->getHeader(self::JWT_HEADER_NAME)[0]);
            if (count($parts) == 2 && $parts[0] == 'Bearer') {
                $token = $parts[1];
                $user  = $this->decodeJwtToken($token);
                if ($user !== null && $user !== false) {
                    $sso = self::getDIShared('sso');
                    $sso->signinByIdAndLogin($user['aud']['id'], $user['aud']['login']);
                }
            }
        }
        return $user;
    }

    /**
     * After
     *
     * @param ServerRequestInterface     $request
     * @param Response ResponseInterface $response
     *
     * @return ResponseInterface
     */
    protected function afterProcess($request, $response)
    {
        try {
            $body = $response->getBody();
            if (is_object($body)) {
                if ($body instanceof StreamInterface) {
                    $content = $body->getContents();
                    if ($content !== null && $content != '' && $content !== false) {
                        try {
                            $object = @unserialize($content);
                            if ($object !== false) {
                                if ($object instanceof \FreeFW\Interfaces\User) {
                                    $token    = $this->generateJwtToken($request, $object);
                                    $response = $response->withHeader(self::JWT_HEADER_NAME, $token);
                                }
                            }
                        } catch (\Exception $ex) {
                            // @Todo
                        }
                    }
                    $body->rewind();
                }
            }
            // response must be converted to correct type
            return $response;
        } catch (\Exception $ex) {
            return $this->createCustomResponse(500, [], $ex->getMessage());
        }
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user     = $this->beforeProcess($request);
        $request  = $request->withAttribute('user', $user);
        $response = $handler->handle($request);
        return $this->afterProcess($request, $response);
    }

    /**
     * generate token
     *
     * @param ServerRequestInterface      $request
     * @param \FreeFW\Interfaces\User $user
     *
     * @return string
     */
    protected function generateJwtToken($request, $user)
    {
        $token = array(
            'iss' => $request->getUri()->getHost(),
            'sub' => 'api',
            'aud' => [
                'id'    => $user->getUserId(),
                'login' => $user->getUserLogin(),
                'ip'    => $request->getClientIp()
            ],
            'iat' => time(),
            'exp' => time() + intval($this->getConfig('duration'))
        );
        $jwt = FireJWT::encode($token, $this->getConfig('privateKey'), 'RS256');
        return $jwt;
    }

    /**
     * Return array from decoded token
     *
     * @param string $token
     *
     * @return array
     */
    protected function decodeJwtToken($token)
    {
        $decoded = FireJWT::decode($token, $this->getConfig('publicKey'), array('RS256'));
        if ($decoded !== null && is_object($decoded)) {
            return json_decode(json_encode($decoded), true);
        }
        return null;
    }

    /**
     * Return config value
     *
     * @param string $p_key
     *
     * @return mixed|boolean
     */
    protected function getConfig($p_key)
    {
        if (is_array($this->config) && array_key_exists($p_key, $this->config)) {
            return $this->config[$p_key];
        }
        return false;
    }
}
