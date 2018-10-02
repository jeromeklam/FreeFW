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
     * @var array
     */
    protected $errors = [];

    /**
     * Get errors
     *
     * @return array
     */
    public function getErrors()
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
     *
     * @return static
     */
    public function addError(int $p_code, $p_message = null, $p_type = \FreeFW\Core\Error::TYPE_ERROR)
    {
        $this->errors[] = new \FreeFW\Core\Error($p_code, $p_message, $p_type);
        return $this;
    }

    /**
     * Check if valid
     *
     * @return boolean
     */
    public function isValid()
    {
        $this->validate();
        return empty($this->errors);
    }

    /**
     * Validate model
     *
     * @return void
     */
    abstract protected function validate();
}
