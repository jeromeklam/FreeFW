<?php
namespace FreeFW\Tools;

/**
 *
 * @author jeromeklam
 *
 */
class Exception
{

    /**
     * Original exception
     * @var \Exception|\Throwable
     */
    private $exception;

    /**
     * String
     * @var string
     */
    private $formattedString;

    /**
     * Constructor
     *
     * @param \Exception|\Throwable $exception
     */
    private function __construct($exception)
    {
        $this->exception = $exception;
        $this->formattedString = $this->formatException();
    }

    /**
     * Format exception
     *
     * @return string
     */
    private function formatException()
    {
        return $this->formatExceptionMessage() . $this->formatExceptionTrace() . $this->getCauseIfApplicable();
    }

    /**
     * Format message
     *
     * @return string
     */
    private function formatExceptionMessage()
    {
        $exceptionClass = get_class($this->exception);
        $exceptionMessage = $this->exception->getMessage();
        $fileAndLine = $this->formatFileAndLine($this->exception->getFile(), $this->exception->getLine());
        if ($exceptionMessage === '') {
            return "${exceptionClass} (${fileAndLine})\n";
        }
        return "${exceptionClass}: ${exceptionMessage} (${fileAndLine})\n";
    }

    /**
     * Where
     *
     * @param string $file
     * @param string $line
     *
     * @return string
     */
    private function formatFileAndLine($file, $line)
    {
        return "${file}:${line}";
    }

    /**
     * Trace
     *
     * @return string
     */
    private function formatExceptionTrace()
    {
        $exceptionTrace = $this->exception->getTrace();
        $formattedTrace = [];
        foreach($exceptionTrace as $trace) {
            $formattedTrace[] = "\tat ".$this->formatTraceElement($trace);
        }
        return implode("\n", $formattedTrace);
    }

    /**
     * Trace element
     *
     * @param array $traceElement
     *
     * @return string
     */
    private function formatTraceElement($traceElement)
    {
        $fileAndLine = $this->formatFileAndLine(
            isset($traceElement['file']) ? $traceElement['file'] : 'unknown',
            isset($traceElement['line']) ? $traceElement['line'] : 'unknown'
        );
        if ($this->isFunctionCall($traceElement)) {
            $functionCall = $this->formatFunctionCall($traceElement);
            $arguments = $this->formatArguments($traceElement);
            return "${functionCall}(${arguments}) (${fileAndLine})";
        }
        return $fileAndLine;
    }

    /**
     * Call
     *
     * @param array $traceElement
     *
     * @return boolean
     */
    private function isFunctionCall($traceElement)
    {
        return array_key_exists('function', $traceElement);
    }

    /**
     * Format call
     *
     * @param array $traceElement
     *
     * @return string
     */
    private function formatFunctionCall($traceElement)
    {
        return (isset($traceElement['class']) ? $traceElement['class'] : '') .
            (isset($traceElement['type']) ? $traceElement['type'] : '') .
            $traceElement['function']
        ;
    }

    /**
     * Format arguments
     *
     * @param array $traceElement
     *
     * @return string
     */
    private function formatArguments($traceElement)
    {
        /** @var string[] $arguments */
        $arguments = $traceElement['args'];
        $formattedArgs = [];
        foreach ($arguments as $arg) {
            $formattedArgs[] = $this->formatArgument($arg);
        }
        return implode(', ', $formattedArgs);
    }

    /**
     * Format one argument
     *
     * @param mixed $arg
     *
     * @return string
     */
    private function formatArgument($arg)
    {
        if (is_string($arg)) {
            return "\"".$arg."\"";
        } else {
            if (is_array($arg)) {
                return 'Array';
            } else {
                if ($arg === null) {
                    return 'null';
                } else {
                    if (is_bool($arg)) {
                        return $arg ? 'true' : 'false';
                    } else {
                        if (is_object($arg)) {
                            return get_class($arg);
                        } else {
                            if (is_resource($arg)) {
                                return get_resource_type($arg);
                            }
                        }
                    }
                }
            }
        }
        return $arg;
    }

    /**
     * Cause
     *
     * @return string
     */
    private function getCauseIfApplicable()
    {
        $previousException = $this->exception->getPrevious();
        if ($previousException !== null) {
            return "\nCaused by: " . self::format($previousException);
        }
        return '';
    }

    /**
     * Converts an Exception to a Java-style stack trace string.
     *
     * @param \Exception|\Throwable The Exception/Throwable to format as a "pretty" string.
     *
     * @return string
     */
    public static function format($exception)
    {
        $formatter = new Exception($exception);
        return $formatter->getFormattedString();
    }

    /**
     * Get as string
     *
     * @return string
     */
    public function getFormattedString()
    {
        return $this->formattedString;
    }
}