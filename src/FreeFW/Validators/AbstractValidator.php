<?php
namespace FreeFW\Validators;

/**
 *
 * @author jeromeklam
 *
 */
abstract class AbstractValidator
{

    /**
     * Validation du numéro
     *
     * @param mixed $p_object
     *
     * @return boolean
     */
    abstract public static function validate($p_object);

    /**
     * Valide ?
     * @return boolean
     */
    public function isValid()
    {
        return static::validate($this->value);
    }
}
