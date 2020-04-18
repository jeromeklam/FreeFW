<?php
namespace FreeFW\Model\Base;

/**
 * Jobqueue
 *
 * @author jeromeklam
 */
abstract class Jobqueue extends \FreeFW\Model\StorageModel\Jobqueue
{

    /**
     * jobq_id
     * @var int
     */
    protected $jobq_id = null;

    /**
     * brk_id
     * @var int
     */
    protected $brk_id = null;

    /**
     * jobq_name
     * @var string
     */
    protected $jobq_name = null;

    /**
     * jobq_desc
     * @var mixed
     */
    protected $jobq_desc = null;

    /**
     * grp_id
     * @var int
     */
    protected $grp_id = null;

    /**
     * user_id
     * @var int
     */
    protected $user_id = null;

    /**
     * jobq_type
     * @var string
     */
    protected $jobq_type = null;

    /**
     * jobq_status
     * @var string
     */
    protected $jobq_status = null;

    /**
     * jobq_ts
     * @var string
     */
    protected $jobq_ts = null;

    /**
     * jobq_last_report
     * @var mixed
     */
    protected $jobq_last_report = null;

    /**
     * jobq_last_ts
     * @var string
     */
    protected $jobq_last_ts = null;

    /**
     * jobq_service
     * @var string
     */
    protected $jobq_service = null;

    /**
     * jobq_method
     * @var string
     */
    protected $jobq_method = null;

    /**
     * jobq_params
     * @var mixed
     */
    protected $jobq_params = null;

    /**
     * jobq_max_retry
     * @var string
     */
    protected $jobq_max_retry = null;

    /**
     * jobq_nb_retry
     * @var string
     */
    protected $jobq_nb_retry = null;

    /**
     * jobq_next_minutes
     * @var int
     */
    protected $jobq_next_minutes = null;

    /**
     * jobq_next_retry
     * @var string
     */
    protected $jobq_next_retry = null;

    /**
     * Set jobq_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function setJobqId($p_value)
    {
        $this->jobq_id = $p_value;
        return $this;
    }

    /**
     * Get jobq_id
     *
     * @return int
     */
    public function getJobqId()
    {
        return $this->jobq_id;
    }

    /**
     * Set brk_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function setBrkId($p_value)
    {
        $this->brk_id = $p_value;
        return $this;
    }

    /**
     * Get brk_id
     *
     * @return int
     */
    public function getBrkId()
    {
        return $this->brk_id;
    }

    /**
     * Set jobq_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function setJobqName($p_value)
    {
        $this->jobq_name = $p_value;
        return $this;
    }

    /**
     * Get jobq_name
     *
     * @return string
     */
    public function getJobqName()
    {
        return $this->jobq_name;
    }

    /**
     * Set jobq_desc
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function setJobqDesc($p_value)
    {
        $this->jobq_desc = $p_value;
        return $this;
    }

    /**
     * Get jobq_desc
     *
     * @return mixed
     */
    public function getJobqDesc()
    {
        return $this->jobq_desc;
    }

    /**
     * Set grp_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function setGrpId($p_value)
    {
        $this->grp_id = $p_value;
        return $this;
    }

    /**
     * Get grp_id
     *
     * @return int
     */
    public function getGrpId()
    {
        return $this->grp_id;
    }

    /**
     * Set user_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function setUserId($p_value)
    {
        $this->user_id = $p_value;
        return $this;
    }

    /**
     * Get user_id
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set jobq_type
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function setJobqType($p_value)
    {
        $this->jobq_type = $p_value;
        return $this;
    }

    /**
     * Get jobq_type
     *
     * @return string
     */
    public function getJobqType()
    {
        return $this->jobq_type;
    }

    /**
     * Set jobq_status
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function setJobqStatus($p_value)
    {
        $this->jobq_status = $p_value;
        return $this;
    }

    /**
     * Get jobq_status
     *
     * @return string
     */
    public function getJobqStatus()
    {
        return $this->jobq_status;
    }

    /**
     * Set jobq_ts
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function setJobqTs($p_value)
    {
        $this->jobq_ts = $p_value;
        return $this;
    }

    /**
     * Get jobq_ts
     *
     * @return string
     */
    public function getJobqTs()
    {
        return $this->jobq_ts;
    }

    /**
     * Set jobq_last_report
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function setJobqLastReport($p_value)
    {
        $this->jobq_last_report = $p_value;
        return $this;
    }

    /**
     * Get jobq_last_report
     *
     * @return mixed
     */
    public function getJobqLastReport()
    {
        return $this->jobq_last_report;
    }

    /**
     * Set jobq_last_ts
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function setJobqLastTs($p_value)
    {
        $this->jobq_last_ts = $p_value;
        return $this;
    }

    /**
     * Get jobq_last_ts
     *
     * @return string
     */
    public function getJobqLastTs()
    {
        return $this->jobq_last_ts;
    }

    /**
     * Set jobq_service
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function setJobqService($p_value)
    {
        $this->jobq_service = $p_value;
        return $this;
    }

    /**
     * Get jobq_service
     *
     * @return string
     */
    public function getJobqService()
    {
        return $this->jobq_service;
    }

    /**
     * Set jobq_method
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function setJobqMethod($p_value)
    {
        $this->jobq_method = $p_value;
        return $this;
    }

    /**
     * Get jobq_method
     *
     * @return string
     */
    public function getJobqMethod()
    {
        return $this->jobq_method;
    }

    /**
     * Set jobq_params
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function setJobqParams($p_value)
    {
        $this->jobq_params = $p_value;
        return $this;
    }

    /**
     * Get jobq_params
     *
     * @return mixed
     */
    public function getJobqParams()
    {
        return $this->jobq_params;
    }

    /**
     * Set jobq_max_retry
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function setJobqMaxRetry($p_value)
    {
        $this->jobq_max_retry = $p_value;
        return $this;
    }

    /**
     * Get jobq_max_retry
     *
     * @return string
     */
    public function getJobqMaxRetry()
    {
        return $this->jobq_max_retry;
    }

    /**
     * Set jobq_nb_retry
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function setJobqNbRetry($p_value)
    {
        $this->jobq_nb_retry = $p_value;
        return $this;
    }

    /**
     * Get jobq_nb_retry
     *
     * @return string
     */
    public function getJobqNbRetry()
    {
        return $this->jobq_nb_retry;
    }

    /**
     * Set jobq_next_minutes
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function setJobqNextMinutes($p_value)
    {
        $this->jobq_next_minutes = $p_value;
        return $this;
    }

    /**
     * Get jobq_next_minutes
     *
     * @return int
     */
    public function getJobqNextMinutes()
    {
        return $this->jobq_next_minutes;
    }

    /**
     * Set jobq_next_retry
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Jobqueue
     */
    public function setJobqNextRetry($p_value)
    {
        $this->jobq_next_retry = $p_value;
        return $this;
    }

    /**
     * Get jobq_next_retry
     *
     * @return string
     */
    public function getJobqNextRetry()
    {
        return $this->jobq_next_retry;
    }
}
