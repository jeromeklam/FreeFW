<?php
namespace FreeFW\Console\Output;

/**
 *
 * @author klam
 */
class ConsoleOutput extends \FreeFW\Console\Output\AbstractOutput
{

    /**
     * Constructeur
     *
     * @param string $p_verbosity
     */
    public function __construct($p_verbosity = self::VERBOSITY_NORMAL)
    {
        parent::__construct($this->openOutputStream(), $p_verbosity);
    }

    /**
     * Support stdout ?
     *
     * @return boolean
     */
    private function hasStdoutSupport()
    {
        return true;
    }

    /**
     * @return resource
     */
    private function openOutputStream()
    {
        if (!$this->hasStdoutSupport()) {
            return fopen('php://output', 'w');
        }
        return @fopen('php://stdout', 'w') ?: fopen('php://output', 'w');
    }
}
