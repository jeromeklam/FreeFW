<?php
namespace FreeFW\Validators;

/**
 *
 * @author jeromeklam
 *
 */
abstract class AbstractModelValidator
{

    /**
     * Validation du numéro
     *
     * @return boolean
     */
    abstract protected function validate();

    /**
     * Valide ?
     * @return boolean
     */
    public function isValid()
    {
        return $this->validate();
    }
}
