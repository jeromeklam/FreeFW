<?php
namespace FreeFW\Console;

/**
 * Command collection
 *
 * @author jeromeklam
 */
class CommandCollection
{

    /**
     * Commands
     * @var array
     */
    protected $commands = [];

    /**
     * Flush all commands
     *
     * @return \FreeFW\Console\CommandCollection
     */
    public function flush()
    {
        $this->commands = [];
        return $this;
    }

    /**
     * Get all commands
     *
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * Add one command
     *
     * @param \FreeFW\Console\Command $p_command
     *
     * @return \FreeFW\Console\CommandCollection
     */
    public function addCommand(\FreeFW\Console\Command $p_command)
    {
        $this->commands[] = $p_command;
        return $this;
    }

    /**
     * Add commands
     *
     * @param \FreeFW\Console\CommandCollection $p_commands
     *
     * @return \FreeFW\Console\CommandCollection
     */
    public function addCommands(\FreeFW\Console\CommandCollection $p_commands)
    {
        $this->commands = array_merge($this->commands, $p_commands->getCommands());
        return $this;
    }
}
