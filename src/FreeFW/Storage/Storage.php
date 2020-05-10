<?php
namespace FreeFW\Storage;

/**
 *
 * @author jeromeklam
 *
 */
abstract class Storage implements
    \FreeFW\Interfaces\StorageInterface,
    \Psr\Log\LoggerAwareInterface,
    \FreeFW\Interfaces\ConfigAwareTraitInterface
{

    /**
     * Modes de recherche
     *
     * @var string
     */
    const COND_EQUAL                 = 'eq';
    const COND_EQUAL_OR_NULL         = 'eqn';
    const COND_NOT_EQUAL             = 'neq';
    const COND_NOT_EQUAL_OR_NULL     = 'neqn';
    const COND_GREATER               = 'gt';
    const COND_GREATER_OR_NULL       = 'gtn';
    const COND_GREATER_EQUAL         = 'gte';
    const COND_GREATER_EQUAL_OR_NULL = 'gten';
    const COND_LOWER                 = 'lt';
    const COND_LOWER_OR_NULL         = 'ltn';
    const COND_LOWER_EQUAL           = 'lte';
    const COND_LOWER_EQUAL_OR_NULL   = 'lten';
    const COND_LIKE                  = 'contains';
    const COND_IN                    = 'in';
    const COND_NOT_IN                = 'nin';
    const COND_EMPTY                 = 'empty';
    const COND_NOT_EMPTY             = 'nempty';
    const COND_BETWEEN               = 'between';
    const COND_BEGIN_WITH            = '%*';
    const COND_END_WITH              = '*>';
    const COND_AND                   = 'and';
    const COND_OR                    = 'or';

    /**
     * Tri
     *
     * @var string
     */
    const SORT_ASC  = 'ASC';
    const SORT_DESC = 'DESC';

    /**
     * comportements
     */
    use \Psr\Log\LoggerAwareTrait;
    use \FreeFW\Behaviour\EventManagerAwareTrait;
    use \FreeFW\Behaviour\ConfigAwareTrait;

    public static function getAllOperators()
    {
        return [
            self::COND_BEGIN_WITH,
            self::COND_BETWEEN,
            self::COND_EMPTY,
            self::COND_END_WITH,
            self::COND_EQUAL,
            self::COND_EQUAL_OR_NULL,
            self::COND_GREATER,
            self::COND_GREATER_EQUAL,
            self::COND_GREATER_EQUAL_OR_NULL,
            self::COND_GREATER_OR_NULL,
            self::COND_IN,
            self::COND_LIKE,
            self::COND_LOWER,
            self::COND_LOWER_EQUAL,
            self::COND_LOWER_EQUAL_OR_NULL,
            self::COND_LOWER_OR_NULL,
            self::COND_NOT_EMPTY,
            self::COND_NOT_EQUAL,
            self::COND_NOT_EQUAL_OR_NULL
        ];
    }
}
