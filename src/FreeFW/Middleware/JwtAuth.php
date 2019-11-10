<?php
namespace FreeFW\Middleware;

use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use \Firebase\JWT\JWT as FireJWT;

/**
 * JWT Auth
 *
 * @author jeromeklam
 */
class JwtAuth implements
    MiddlewareInterface,
    \Psr\Log\LoggerAwareInterface,
    \FreeFW\Interfaces\ConfigAwareTraitInterface,
    \FreeFW\Interfaces\AuthAdapterInterface
{

    /**
     * Behaviour
     */
    use \Psr\Log\LoggerAwareTrait;
    use \FreeFW\Behaviour\EventManagerAwareTrait;
    use \FreeFW\Behaviour\ConfigAwareTrait;
    use \FreeFW\Behaviour\HttpFactoryTrait;

    /**
     * Constants
     * @var string
     */
    const JWT_HEADER_NAME = 'authorization';

    /**
     * Secured ?
     * @var bool
     */
    protected $secured = false;

    /**
     * Generate identity
     * @var boolean
     */
    protected $identity = false;

    /**
     * Before
     *
     * @param ServerRequestInterface $p_request
     *
     * @return \FreeFW\Interfaces\UserInterface | false
     */
    protected function beforeProcess($p_request)
    {
        $user = false;
        if ($p_request->hasHeader(self::JWT_HEADER_NAME)) {
            $parts = explode(' ', $p_request->getHeader(self::JWT_HEADER_NAME)[0]);
            if (count($parts) == 2 && $parts[0] == 'Bearer') {
                $token = $parts[1];
                $jUser = $this->decodeJwtToken($token);
                if ($jUser !== null && $jUser !== false) {
                    $sso  = \FreeFW\DI\DI::getShared('sso');
                    $user = $sso->signinByIdAndLogin($jUser['aud']['id'], $jUser['aud']['login']);
                }
            }
        }
        return $user;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface  $p_request
     * @param RequestHandlerInterface $p_handler
     *
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $p_request,
        RequestHandlerInterface $p_handler
    ): ResponseInterface {
        $allowed = true;
        if ($this->secured) {
            $allowed = false;
            /**
             * @var \FreeFW\Interfaces\UserInterface $user
             */
            $user = $this->beforeProcess($p_request);
            if ($user !== false) {
                $allowed = true;
            }
        }
        if (!$allowed) {
            return $this->createResponse(401);
        }
        $response = $p_handler->handle($p_request);
        if ($this->getIndentityGeneration()) {
            /**
             * @var \FreeSSO\Server $sso;
             */
            $sso  = \FreeFW\DI\DI::getShared('sso');
            /**
             * @var \FreeFW\Interfaces\UserInterface $user
             */
            $user = $sso->getUser();
            if ($user) {
                $token    = $this->generateJwtToken($p_request, $user);
                $response = $response->withHeader(self::JWT_HEADER_NAME, $token);
            }
        }
        return $response;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\AuthAdapterInterface::isSecured()
     */
    public function isSecured(): bool
    {
        return $this->secured;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\AuthAdapterInterface::setSecured()
     */
    public function setSecured(bool $p_secured = true)
    {
        $this->secured = $p_secured;
        return $this;
    }

    /**
     * generate token
     *
     * @param ServerRequestInterface           $p_request
     * @param \FreeFW\Interfaces\UserInterface $p_user
     *
     * @return string
     */
    protected function generateJwtToken($p_request, $user)
    {
        $token = array(
            'iss' => $p_request->getUri()->getHost(),
            'sub' => 'api',
            'aud' => [
                'id'    => $user->getUserId(),
                'login' => $user->getUserLogin(),
                'ip'    => \FreeFW\Http\ServerRequest::getClientIp($p_request)
            ],
            'iat' => time(),
            'exp' => time() + intval($this->getConfigValue('duration'))
        );
        $jwt = FireJWT::encode($token, $this->getConfigValue('privateKey'), 'RS256');
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
        $decoded = FireJWT::decode($token, $this->getConfigValue('publicKey'), array('RS256'));
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
    protected function getConfigValue($p_key)
    {
        $config = $this->getConfig()->get('jwt');
        if (is_array($config) && array_key_exists($p_key, $config)) {
            return $config[$p_key];
        }
        return false;
    }

    /**
     * Force identity generation
     *
     * @param bool $p_identity
     */
    public function setIdentityGeneration(bool $p_identity = true)
    {
        $this->identity = $p_identity;
        return $this;
    }

    /**
     * Get identity generation
     *
     * @return bool
     */
    public function getIndentityGeneration() : bool
    {
        return $this->identity;
    }
}
