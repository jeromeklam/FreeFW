<?php
namespace FreeFW\Console\Output;

/**
 *
 * @author klam
 */
abstract class AbstractOutput
{

    /**
     *
     * @var unknown
     */
    const VERBOSITY_QUIET = 16;
    const VERBOSITY_NORMAL = 32;
    const VERBOSITY_VERBOSE = 64;
    const VERBOSITY_VERY_VERBOSE = 128;
    const VERBOSITY_DEBUG = 256;

    /**
     *
     * @var unknown
     */
    const OUTPUT_NORMAL = 1;
    const OUTPUT_RAW = 2;
    const OUTPUT_PLAIN = 4;

    /**
     * Stream
     *
     * @var mixed
     */
    protected $stream = null;

    /**
     * Mode d'affichage
     *
     * @var number
     */
    protected $verbosity = self::VERBOSITY_NORMAL;

    /**
     * Constructor.
     *
     * @param resource $p_stream
     * @param number   $p_verbosity
     *
     * @throws \InvalidArgumentException When first argument is not a real stream
     */
    public function __construct($p_stream, $p_verbosity = self::VERBOSITY_NORMAL)
    {
        if (!is_resource($p_stream) || 'stream' !== get_resource_type($p_stream)) {
            throw new \InvalidArgumentException('The StreamOutput class needs a stream as its first argument.');
        }
        $this->verbosity = $p_verbosity;
        $this->stream    = $p_stream;
    }

    /**
     * Retourne le mode verbose
     *
     * @return number
     */
    public function getVerbosity()
    {
        return $this->verbosity;
    }

    /**
     * Ecriture
     *
     * @param mixed   $p_messages
     * @param boolean $p_newline
     * @param number  $p_length
     * @param number  $p_options
     */
    public function write($p_messages, $p_newline = false, $p_length = 0, $p_options = self::OUTPUT_NORMAL)
    {
        $messages    = (array) $p_messages;
        $types       = self::OUTPUT_NORMAL | self::OUTPUT_RAW | self::OUTPUT_PLAIN;
        $type        = $types & $p_options ?: self::OUTPUT_NORMAL;
        $verbosities = self::VERBOSITY_QUIET | self::VERBOSITY_NORMAL | self::VERBOSITY_VERBOSE |
                       self::VERBOSITY_VERY_VERBOSE | self::VERBOSITY_DEBUG;
        $verbosity   = $verbosities & $p_options ?: self::VERBOSITY_NORMAL;
        if ($verbosity > $this->getVerbosity()) {
            return;
        }
        foreach ($messages as $message) {
            switch ($type) {
                case self::OUTPUT_NORMAL:
                case self::OUTPUT_RAW:
                    break;
                case self::OUTPUT_PLAIN:
                    $message = strip_tags($message);
                    break;
            }
            if ($p_length > 0) {
                if (strlen($message) > $p_length) {
                    $message = substr($message, 0, $p_length);
                } else {
                    $message = str_pad($message, $p_length, ' ');
                }
            }
            $this->doWrite($message, $p_newline);
        }
        
        return $this;
    }

    /**
     * Ajout d'un séparateur
     *
     * @return void
     */
    public function addSeparator()
    {
        $this->write('######################################################################', true);
        
        return $this;
    }

    /**
     * Exécution de l'écriture
     *
     * @param mixed   $p_message
     * @param boolean $p_newline
     *
     * @return void
     */
    protected function doWrite($p_message, $p_newline)
    {
        if (false === @fwrite($this->stream, $p_message)
            || ($p_newline && (false === @fwrite($this->stream, PHP_EOL)))
        ) {
            throw new \RuntimeException('Unable to write output.');
        }
        fflush($this->stream);
    }
}
