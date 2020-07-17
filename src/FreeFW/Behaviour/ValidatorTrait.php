<?php
namespace FreeFW\Behaviour;

/**
 * Validator
 *
 * @author jeromeklam
 */
trait ValidatorTrait
{

    use \FreeFW\Behaviour\ErrorTrait;

    /**
     * Check if valid
     *
     * @return boolean
     */
    public function isValid() : bool
    {
        $this->validate();
        return empty($this->errors);
    }

    /**
     * Check if model in creation
     *
     * @return boolean
     * @desc true si la Pk est à zéro. Rappel, la Pk est obligatoire et son type est un entier
     */
    public function isCreation()
    {
        $getter = $this->getPkGetter();
        $value = $this->$getter(); // si le get ou la PK n'existent pas, erreur 500.
        return ($value==null || $value===(int)0); // true si null ou 0
    }

    /**
     * Check if model in modification
     *
     * @return boolean
     * @desc true si la Pk n'est pas à zéro. Rappel, la Pk est obligatoire et son type est un entier
     */
    public function isModification()
    {
        return !$this->isCreation();
    }

    /**
     * Validate model
     *
     * @return void
     */
    abstract protected function validate();
}
