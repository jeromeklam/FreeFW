<?php
/**
 * Classe de gestiton des validateurs
 *
 * @author jeromeklam
 * @package Validator
 * @category Trait
 */
namespace FreeFW\Behaviour;

use \FreeFW\Interfaces\Validation as Valid;

/**
 * Validation
 * @author jeromeklam
 */
trait Validation
{

    /**
     * Liste des erreurs
     * @var array
     */
    protected $validation_errors = array();

    /**
     * Erreurs http
     * @var array
     */
    protected $http_errors = null;

    /**
     * Etat
     * @var string
     */
    protected $validation_state = false;

    /**
     * Validation à partir des infos de la classe
     *
     * @return boolean
     */
    protected function checkValidation()
    {
        $this->validation_state = true;
        $rules = $this->getValidationRules();
        foreach ($rules as $prop => $rule) {
            $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($prop, true);
            $value  = $this->{$getter}();
            if (is_object($value)) {
            } else {
                if (is_array($value)) {
                    if (in_array(Valid::VALIDATOR_NOT_EMPTY, $rule) && count($value) <= 0) {
                        $this->addValidationError($prop, Valid::VALIDATOR_NOT_EMPTY, 'Field cannot be empty');
                    }
                } else {
                    if (in_array(Valid::VALIDATOR_NOT_NULL, $rule) && $value === null) {
                        $this->addValidationError($prop, Valid::VALIDATOR_NOT_NULL, 'Field cannot be null');
                    } else {
                        if (in_array(Valid::VALIDATOR_NOT_EMPTY, $rule) && trim($value) == '') {
                            $this->addValidationError($prop, Valid::VALIDATOR_NOT_EMPTY, 'Field cannot be empty');
                        }
                    }
                }
            }
        }
        return $this->validation_state;
    }

    /**
     * Valide l'object
     *
     * @eturn boolean
     */
    protected function validate()
    {
        if (method_exists($this, 'getValidationRules')) {
            return $this->checkValidation();
        }
        return true;
    }

    /**
     * validation ok ??
     *
     * @return boolean
     */
    public function isValide()
    {
        return $this->validate();
    }

    /**
     * validation ok ??
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->validate();
    }

    /**
     * Force le champ validation
     *
     * @return static
     */
    protected function setValide($p_status = true)
    {
        $this->validation_state = $p_status;
        return $this;
    }

    /**
     * Ajout d'une erreur
     *
     * @param string $p_code
     * @param string $p_message
     *
     * @return this
     */
    public function addValidationError($p_field, $p_code, $p_message)
    {
        $this->validation_state = false;
        $this->validation_errors[] = array(
            'code'    => $p_code,
            'message' => $p_message
        );
        switch ($p_code) {
            case VALID::VALIDATOR_NOT_EMPTY:
                $this->addRequiredFieldError($p_code, $p_field);
                break;
            default:
                if ($this->http_errors === null) {
                    $this->http_errors = new \FreeFW\Http\Errors();
                }
                $this->http_errors->addError($p_code, $p_message);
                break;
        }
        return $this;
    }

    /**
     * Retourne le tableau des erreurs de validation
     *
     * @return array
     */
    public function getValidationErrors()
    {
        return $this->validation_errors;
    }

    /**
     * Ajout d'une erreur
     *
     * @param string $p_code
     * @param string $p_message
     *
     * @return this
     */
    public function addRequiredFieldError($p_code, $p_field)
    {
        if ($this->http_errors === null) {
            $this->http_errors = new \FreeFW\Http\Errors();
        }
        $this->http_errors->addRequiredField($p_code, $p_field);
        return $this;
    }

    /**
     * Ajout d'une erreur
     *
     * @param string $p_code
     * @param string $p_message
     *
     * @return this
     */
    public function addNotUniqueFieldError($p_code, $p_field)
    {
        if ($this->http_errors === null) {
            $this->http_errors = new \FreeFW\Http\Errors();
        }
        $this->http_errors->addNonUniqueField($p_code, $p_field);
        return $this;
    }

    /**
     * Retourne le tableau des erreurs de validation
     *
     * @return array
     */
    public function getHttpErrors()
    {
        if ($this->http_errors === null) {
            $this->http_errors = new \FreeFW\Http\Errors();
        }
        return $this->http_errors;
    }
}
