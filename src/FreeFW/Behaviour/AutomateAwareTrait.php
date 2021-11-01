<?php
namespace FreeFW\Behaviour;

/**
 * AutomateAware
 */
trait AutomateAwareTrait
{

    /**
     * run ?
     * @var boolean
     */
    protected $run_automate = true;

    /**
     * Off
     *
     * @return void
     */
    public function turnAutomateOff()
    {
        $this->run_automate = false;
    }

    /**
     * On
     *
     * @return void
     */
    public function turnAutomateOn()
    {
        $this->run_automate = true;
    }

    /**
     * Run automate ?
     *
     * @return boolean
     */
    public function mustRunAutomate()
    {
        return $this->run_automate;
    }
}