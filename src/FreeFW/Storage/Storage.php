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
    const COND_EQUAL                 = '=';
    const COND_EQUAL_OR_NULL         = '==NULL';
    const COND_NOT_EQUAL             = '!=';
    const COND_NOT_EQUAL_OR_NULL     = '!=NULL';
    const COND_GREATER               = '>';
    const COND_GREATER_OR_NULL       = '>NULL';
    const COND_GREATER_EQUAL         = '>=';
    const COND_GREATER_EQUAL_OR_NULL = '>=NULL';
    const COND_LOWER                 = '<';
    const COND_LOWER_OR_NULL         = '<NULL';
    const COND_LOWER_EQUAL           = '<=';
    const COND_LOWER_EQUAL_OR_NULL   = '<=NULL';
    const COND_LIKE                  = '%*%';
    const COND_IN                    = 'IN';
    const COND_NOT_IN                = 'NIN';
    const COND_EMPTY                 = 'EMPTY';
    const COND_NOT_EMPTY             = 'NEMPTY';
    const COND_BETWEEN               = 'BETWEEN';
    const COND_BEGIN_WITH            = '%*';
    const COND_END_WITH              = '*>';

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
}
