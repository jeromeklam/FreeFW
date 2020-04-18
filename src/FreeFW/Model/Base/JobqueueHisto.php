<?php
namespace FreeFW\Model\Base;

/**
 * JobqueueHisto
 *
 * @author jeromeklam
 */
abstract class JobqueueHisto extends \FreeFW\Model\StorageModel\JobqueueHisto
{

    /**
     * jobqh_id
     * @var int
     */
    protected $jobqh_id = null;

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
     * jobqh_ts
     * @var string
     */
    protected $jobqh_ts = null;

    /**
     * jobqh_msg
     * @var mixed
     */
    protected $jobqh_msg = null;

    /**
     * jobqh_status
     * @var string
     */
    protected $jobqh_status = null;

    /**
     * Set jobqh_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\JobqueueHisto
     */
    public function setJobqhId($p_value)
    {
        $this->jobqh_id = $p_value;
        return $this;
    }

    /**
     * Get jobqh_id
     *
     * @return int
     */
    public function getJobqhId()
    {
        return $this->jobqh_id;
    }

    /**
     * Set jobq_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\JobqueueHisto
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
     * @return \FreeFW\Model\JobqueueHisto
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
     * Set jobqh_ts
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\JobqueueHisto
     */
    public function setJobqhTs($p_value)
    {
        $this->jobqh_ts = $p_value;
        return $this;
    }

    /**
     * Get jobqh_ts
     *
     * @return string
     */
    public function getJobqhTs()
    {
        return $this->jobqh_ts;
    }

    /**
     * Set jobqh_msg
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\JobqueueHisto
     */
    public function setJobqhMsg($p_value)
    {
        $this->jobqh_msg = $p_value;
        return $this;
    }

    /**
     * Get jobqh_msg
     *
     * @return mixed
     */
    public function getJobqhMsg()
    {
        return $this->jobqh_msg;
    }

    /**
     * Set jobqh_status
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\JobqueueHisto
     */
    public function setJobqhStatus($p_value)
    {
        $this->jobqh_status = $p_value;
        return $this;
    }

    /**
     * Get jobqh_status
     *
     * @return string
     */
    public function getJobqhStatus()
    {
        return $this->jobqh_status;
    }
}
