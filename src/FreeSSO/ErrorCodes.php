<?php
/**
 * Constantes des erreurs
 *
 * @author jeromeklam
 * @package SSO\Errors
 */
namespace FreeSSO;

/**
 * Constantes des erreurs
 * @author jeromeklam
 */
class ErrorCodes
{

    const ERROR_GENERAL                 = 10300000;
    const ERROR_OPTIONS_BROKER_REQUIRED = 10300001;
    const ERROR_PASSWORD_WRONG          = 10300002;
    const ERROR_USER_DEACTIVATED        = 10300003;
    const ERROR_LOGIN_NOTFOUND          = 10300004;
    const ERROR_PASSWORD_DIFFERENT      = 10300005;
    const ERROR_LOGIN_EXISTS            = 10300006;
    const ERROR_JOBQUEUE_NOT_AVAILABLE  = 10300007;
    const ERROR_LOGIN_EMPTY             = 10300008;
    const ERROR_PASSWORD_EMPTY          = 10300009;
    const ERROR_PARAM_MISSING           = 10300010;
    const ERROR_CREATION                = 10300011;
    const ERROR_NOT_FOUND               = 10300012;
    const ERROR_EXISTS                  = 10300013;
    const ERROR_MODIFICATION            = 10300014;
    const ERROR_TOKEN_EMPTY             = 10300015;
    const ERROR_TOKEN_NOT_FOUND         = 10300016;
    const ERROR_USER_NOTACTIVATED       = 10300017;
}
