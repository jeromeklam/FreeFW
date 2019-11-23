<?php
namespace FreeFW\Behaviour;

/**
 * Validator
 *
 * @author jeromeklam
 */
trait ValidatorTrait
{

    /**
     * Errors
     * @var array[\FreeFW\Core\Error]
     */
    protected $errors = [];

    /**
     * Get errors
     *
     * @return array[\FreeFW\Core\Error]
     */
    public function getErrors() : array
    {
        return $this->errors;
    }

    /**
     * Clear errors
     *
     * @return static
     */
    public function flushErrors()
    {
        $this->errors = [];
        return $this;
    }

    /**
     * Add an error
     *
     * @param int    $p_code
     * @param string $p_message
     * @param int    $p_type
     * @param mixed  $p_field
     *
     * @return static
     */
    public function addError(
        int $p_code,
        $p_message = null,
        $p_type = \FreeFW\Core\Error::TYPE_ERROR,
        $p_field = null
    ) {
        $this->errors[] = new \FreeFW\Core\Error($p_code, $p_message, $p_type, $p_field);
        return $this;
    }

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
     * Errors
     *
     * @return bool
     */
    public function hasErrors() : bool
    {
        return (count($this->errors) > 0);
    }

    /**
     * Validate model
     *
     * @return void
     */
    abstract protected function validate();
}
