<?php
namespace FreeFW;

/**
 * Constantes générales
 */
class Constants
{

    /**
     * Langues
     * @var string
     */
    const LANG_FR      = 'FR';
    const LANG_EN      = 'EN';
    const LANG_DE      = 'DE';
    const LANG_ES      = 'ES';
    const LANG_ID      = 'ID';
    const LANG_DEFAULT = 'FR';

    /**
     * Locales
     * @var string
     */
    const LOCALE_FR = 'FR_FR';
    const LOCALE_US = 'EN_US';

    /**
     * Monnaies
     * @var string
     */
    const CURRENCY_EURO   = 'EUR';
    const CURRENCY_DOLLAR = 'USD';

    /**
     * Routes events
     *
     * @var string
     */
    const EVENT_ROUTE_NOT_FOUND    = 'not-found';
    const EVENT_COMMAND_NOT_FOUND  = 'not-found';
    const EVENT_BEFORE_FINISH      = 'app-before-finish';
    const EVENT_AFTER_RENDER       = 'app-after-render';
    const EVENT_INCOMPLETE_REQUEST = 'app-inc-request';
    const EVENT_STORAGE_CREATE     = 'storage_create';
    const EVENT_STORAGE_UPDATE     = 'storage_update';
    const EVENT_STORAGE_DELETE     = 'storage_delete';

    /**
     * Types d'objets
     * @var string
     */
    const TYPE_STRING             = 'STRING';
    const TYPE_MD5                = 'MD5';
    const TYPE_PASSWORD           = 'PASSWORD';
    const TYPE_TEXT               = 'TEXT';
    const TYPE_TEXT_HTML          = 'TEXT_HTML';
    const TYPE_BLOB               = 'BLOB';
    const TYPE_JSON               = 'JSON';
    const TYPE_DATE               = 'DATE';
    const TYPE_DATETIME           = 'DATETIME';
    const TYPE_DATETIMETZ         = 'DATETIMETZ';
    const TYPE_BIGINT             = 'BIGINT';
    const TYPE_BOOLEAN            = 'BOOLEAN';
    const TYPE_INTEGER            = 'INTEGER';
    const TYPE_DECIMAL            = 'DECIMAL';
    const TYPE_MONETARY           = 'MONETARY';
    const TYPE_TABLE              = 'TABLE';
    const TYPE_SELECT             = 'SELECT';
    const TYPE_LIST               = 'SELECT2';
    const TYPE_RESULTSET          = 'RESULTSET';
    const TYPE_FILE               = 'FILE';
    const TYPE_HTML               = 'HTML';

    /**
     * Properties
     * @var unknown
     */
    const PROPERTY_NAME    = 'name';
    const PROPERTY_PRIVATE = 'private';
    const PROPERTY_TYPE    = 'type';
    const PROPERTY_OPTIONS = 'options';
    const PROPERTY_PUBLIC  = 'public';
    const PROPERTY_DEFAULT = 'default';
    const PROPERTY_FK      = 'fk';

    /**
     * Options
     * @var string
     */
    const OPTION_REQUIRED         = 'REQUIRED';
    const OPTION_PK               = 'PK';
    const OPTION_FK               = 'FK';
    const OPTION_JSONIGNORE       = 'NOJSON';
    const OPTION_LOCAL            = 'LOCAL';
    const OPTION_UNIQ             = 'UNIQ';
    const OPTION_BROKER           = 'BROKER';

    /**
     * Default constants
     * @var string
     */
    const DEFAULT_NOW   = 'NOW';
    const DEFAULT_TRUE  = 1;
    const DEFAULT_FALSE = 0;

    /**
     * Regex
     * @var string
     */
    const PARAM_REGEX = '[0-9a-z_\-\.\@\%]*';

    /**
     * Errors types
     * @var string
     */
    const ERROR_REQUIRED     = 666001;
}
