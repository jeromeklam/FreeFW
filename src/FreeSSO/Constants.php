<?php
/**
 * Constantes pour le serveur SSO
 *
 * @author jeromeklam
 * @package SSO\Constants
 */
namespace FreeSSO;

/**
 * Constantes pour le serveur SSO
 * @author jeromeklam
 */
class Constants
{

    /**
     * Brokers, ...
     * @var mixed
     */
    const BROKER_KEY              = 'broker-key';
    const BROKER_SECRET           = 'broker-secret';
    const BROKER_SESSION_LIFETIME = 60; // Minutes

    /**
     * oAuth2 stuff
     * @var string
     */
    const OAUTH_KEY              = 'oauth2';

    /**
     * Connexions
     * @var unknown
     */
    const SSO_CNX       = 'sso-cnx';

    /**
     * Cookies default values
     * @var mixed
     */
    const COOKIE_DEFAULT_DOMAIN = 'jvsonline.fr';
    const COOKIE_CDSSO          = 'SSO_ID';
    const COOKIE_APP            = 'APP_ID';
    const HEADER_CDSSO          = 'SsoId';
    const HEADER_APP            = 'AppId';
    const COOKIE_APP_DAYS       = 360; // Days

    /**
     * Other constants
     * @var mixed
     */
    const SESSION_LIFETIME = 50; // Seconds
}
