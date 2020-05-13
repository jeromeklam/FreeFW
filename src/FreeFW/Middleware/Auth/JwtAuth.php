<?php
namespace FreeFW\Middleware\Auth;

use \Psr\Http\Message\ServerRequestInterface;
use \Firebase\JWT\JWT as FireJWT;
use \FreeFW\Middleware\Auth\AuthorizationHeader;

/**
 * JWT Auth
 *
 * @author jeromeklam
 */
class JwtAuth implements
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
        FireJWT::$leeway = 20;
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
        try {
            FireJWT::$leeway = 20;
            $decoded = FireJWT::decode($token, $this->getConfigValue('publicKey'), array('RS256'));
        } catch (\Exception $ex) {
            return null;
        }
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
        $config = $this->getAppConfig()->get('jwt');
        if (is_array($config) && array_key_exists($p_key, $config)) {
            return $config[$p_key];
        }
        return false;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\AuthAdapterInterface::getAuthorizationHeader()
     */
    public function getAuthorizationHeader(ServerRequestInterface $p_request, AuthorizationHeader $p_header)
    {
        $header = '';
        $sso    = \FreeFW\DI\Di::getShared('sso');
        if ($sso) {
            $user = $sso->getUser();
            if ($user) {
                $header = $this->generateJwtToken($p_request, $user);
            }
        }
        $authHeader = new \FreeFW\Middleware\Auth\AuthorizationHeader();
        $authHeader
            ->setType('JWT')
            ->addParameter('id', $header)
        ;
        return $authHeader->__toString();
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\AuthAdapterInterface::verifyAuthorizationHeader()
     */
    public function verifyAuthorizationHeader(ServerRequestInterface $p_request, AuthorizationHeader $p_header)
    {
        $user   = false;
        $token  = $p_header->getParameter('id');
        $jUser  = $this->decodeJwtToken($token);
        if ($jUser !== null && $jUser !== false) {
            $sso  = \FreeFW\DI\DI::getShared('sso');
            $user = $sso->signinByIdAndLogin($jUser['aud']['id'], $jUser['aud']['login']);
        }
        return $user;
    }
}
