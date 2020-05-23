<?php
namespace FreeFW\Model;

/**
 * JobqueueHisto
 *
 * @author jeromeklam
 */
class JobqueueHisto extends \FreeFW\Model\Base\JobqueueHisto
{

    /**
     * Prevent from saving history
     * @var bool
     */
    protected $no_history = true;

    /**
     * Jobqueue
     * @var \FreeFW\Model\Jobqueue
     */
    protected $jobqueue = null;

    /**
     * Set jobqueue
     *
     * @param \FreeFW\Model\Jobqueue $p_jobqueue
     *
     * @return \FreeFW\Model\JobqueueHisto
     */
    public function setJobqueue($p_jobqueue)
    {
        $this->jobqueue = $p_jobqueue;
        return $this;
    }

    /**
     * Get jobqueue
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function getJobqueue()
    {
        return $this->jobqueue;
    }
}
