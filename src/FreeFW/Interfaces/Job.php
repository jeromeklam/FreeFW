<?php
namespace FreeFW\Interfaces;

/**
 *
 * @author klam
 */
interface Job
{

    /**
     * Status
     *
     * @var string
     */
    const STATUS_PREPROCESS = 'PREPROCESS';
    const STATUS_INPROGRESS = 'IN_PROGRESS';
    const STATUS_ERROR      = 'ERROR';
    const STATUS_WAITING    = 'WAITING';
    const STATUS_RETRY      = 'RETRY';
    const STATUS_FINISH     = 'FINISH';

    /**
     * Types de job
     *
     * @var string
     */
    const TYPE_MANUAL = 'MANUAL';
    const TYPE_ONCE   = 'ONCE';
    const TYPE_LOOP   = 'LOOP';

    /**
     * Get params as array
     *
     * @return array
     */
    public function getJobParamsAsArray();

    /**
     * Ajout d'un message
     *
     * @param mixed $p_message
     *
     * @return \FreeFW\Interfaces\Job
     */
    public function addJobMessage($p_message);

    /**
     * Passage en erreur
     *
     * @param string $p_message
     *
     * @return \FreeFW\Interfaces\Job
     */
    public function setJobError($p_message = 'general error');

    /**
     * Job fini
     *
     * @return \FreeFW\Interface\Job
     */
    public function setJobFinished();

    /**
     * Job en cours
     *
     * @param boolean $p_save
     *
     * @return \FreeFW\Interfaces\Job
     */
    public function setJobInProgress($p_save = false);
}
