<?php
namespace FreeFW\Model\Base;

/**
 * Inbox
 *
 * @author jeromeklam
 */
abstract class Inbox extends \FreeFW\Model\StorageModel\Inbox
{

    /**
     * inbox_id
     * @var int
     */
    protected $inbox_id = null;

    /**
     * inbox_filename
     * @var string
     */
    protected $inbox_filename = null;

    /**
     * inbox_ts
     * @var mixed
     */
    protected $inbox_ts = null;

    /**
     * user_id
     * @var int
     */
    protected $user_id = null;

    /**
     * inbox_content
     * @var mixed
     */
    protected $inbox_content = null;

    /**
     * inbox_name
     * @var string
     */
    protected $inbox_name = null;

    /**
     * inbox_desc
     * @var mixed
     */
    protected $inbox_desc = null;

    /**
     * inbox_object_name
     * @var string
     */
    protected $inbox_object_name = null;

    /**
     * inbox_params
     * @var mixed
     */
    protected $inbox_params = null;

    /**
     * inbox_download_ts
     * @var mixed
     */
    protected $inbox_download_ts = null;

    /**
     * Set inbox_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Inbox
     */
    public function setInboxId($p_value)
    {
        $this->inbox_id = $p_value;
        return $this;
    }

    /**
     * Get inbox_id
     *
     * @return int
     */
    public function getInboxId()
    {
        return $this->inbox_id;
    }

    /**
     * Set inbox_filename
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Inbox
     */
    public function setInboxFilename($p_value)
    {
        $this->inbox_filename = $p_value;
        return $this;
    }

    /**
     * Get inbox_filename
     *
     * @return string
     */
    public function getInboxFilename()
    {
        return $this->inbox_filename;
    }

    /**
     * Set inbox_ts
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Inbox
     */
    public function setInboxTs($p_value)
    {
        $this->inbox_ts = $p_value;
        return $this;
    }

    /**
     * Get inbox_ts
     *
     * @return mixed
     */
    public function getInboxTs()
    {
        return $this->inbox_ts;
    }

    /**
     * Set user_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Inbox
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
     * Set inbox_content
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Inbox
     */
    public function setInboxContent($p_value)
    {
        $this->inbox_content = $p_value;
        return $this;
    }

    /**
     * Get inbox_content
     *
     * @return mixed
     */
    public function getInboxContent()
    {
        return $this->inbox_content;
    }

    /**
     * Set inbox_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Inbox
     */
    public function setInboxName($p_value)
    {
        $this->inbox_name = $p_value;
        return $this;
    }

    /**
     * Get inbox_name
     *
     * @return string
     */
    public function getInboxName()
    {
        return $this->inbox_name;
    }

    /**
     * Set inbox_desc
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Inbox
     */
    public function setInboxDesc($p_value)
    {
        $this->inbox_desc = $p_value;
        return $this;
    }

    /**
     * Get inbox_desc
     *
     * @return mixed
     */
    public function getInboxDesc()
    {
        return $this->inbox_desc;
    }

    /**
     * Set inbox_object_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Inbox
     */
    public function setInboxObjectName($p_value)
    {
        $this->inbox_object_name = $p_value;
        return $this;
    }

    /**
     * Get inbox_object_name
     *
     * @return string
     */
    public function getInboxObjectName()
    {
        return $this->inbox_object_name;
    }

    /**
     * Set inbox_params
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Inbox
     */
    public function setInboxParams($p_value)
    {
        $this->inbox_params = $p_value;
        return $this;
    }

    /**
     * Get inbox_params
     *
     * @return mixed
     */
    public function getInboxParams()
    {
        return $this->inbox_params;
    }

    /**
     * Set inbox_download_ts
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Inbox
     */
    public function setDownloadInboxTs($p_value)
    {
        $this->inbox_download_ts = $p_value;
        return $this;
    }

    /**
     * Get inbox_download_ts
     *
     * @return mixed
     */
    public function getInboxDownloadTs()
    {
        return $this->inbox_download_ts;
    }

}
