<?php

/**
 * Classe de gestion des shells
 *
 * @author jeromeklam
 * @package Command
 */

namespace FreeFW\Tools;

/**
 * Class Shell
 * @author jeromeklam
 */
class Shell
{

    /**
     * Exécute une commande en tâche de fond
     *
     * @param string $cmd
     *
     * @return void
     */
    public static function execInBackground($cmd)
    {
        if (substr(php_uname(), 0, 7) == "Windows") {
            pclose(popen("start /B " . $cmd, "r"));
        } else {
            exec($cmd . " > /dev/null &");
        }
    }

    /**
     * Execute a command and return it's output. Either wait until the command exits or the timeout has expired.
     *
     * @param string $cmd     Command to execute.
     * @param int    $timeout Timeout in seconds.
     * 
     * @return string Output of the command.
     * @throws \Exception
     */
    public static function exec_timeout(string $cmd, int $timeout)
    {
        // File descriptors passed to the process.
        $descriptors = array(
            0 => array('pipe', 'r'),  // stdin
            1 => array('pipe', 'w'),  // stdout
            2 => array('pipe', 'w')   // stderr
        );
        // Start the process.
        $process = proc_open('exec ' . $cmd, $descriptors, $pipes);
        if (!is_resource($process)) {
            throw new \Exception('Could not execute process');
        }
        // Set the stdout stream to none-blocking.
        stream_set_blocking($pipes[1], 0);
        // Turn the timeout into microseconds.
        $timeout = $timeout * 1000000;
        // Output buffer.
        $buffer   = '';
        $exitcode = 255;
        // While we have time to wait.
        while ($timeout > 0) {
            $start = microtime(true);
            // Wait until we have output or the timer expired.
            $read  = array($pipes[1]);
            $other = array();
            stream_select($read, $other, $other, 0, $timeout);
            // Get the status of the process.
            // Do this before we read from the stream,
            // this way we can't lose the last bit of output if the process dies between these     functions.
            $status = proc_get_status($process);
            // Read the contents from the buffer.
            // This function will always return immediately as the stream is none-blocking.
            $buffer .= stream_get_contents($pipes[1]);
            if (!$status['running']) {
                // Break from this loop if the process exited before the timeout.
                $exitcode = $status['exitcode'];
                break;
            }
            // Subtract the number of microseconds that we waited.
            $timeout -= (microtime(true) - $start) * 1000000;
        }
        // Check if there were any errors.
        $errors = stream_get_contents($pipes[2]);
        if (!empty($errors)) {
            throw new \Exception($errors);
        }
        // Kill the process in case the timeout expired and it's still running.
        // If the process already exited this won't do anything.
        proc_terminate($process, 9);
        // Close all streams.
        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);

        return ['output' => $buffer, 'exitcode' => $exitcode];
    }
}
