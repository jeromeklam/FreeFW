<?php
namespace FreeFW\Model\Base;

/**
 * Link
 *
 * @author jeromeklam
 */
abstract class Link extends \FreeFW\Model\StorageModel\Link
{

    /**
     * link_id
     * @var int
     */
    protected $link_id = null;

    /**
     * file_id
     * @var int
     */
    protected $file_id = null;

    /**
     * user_id
     * @var int
     */
    protected $user_id = null;

    /**
     * link_ts
     * @var mixed
     */
    protected $link_ts = null;

    /**
     * link_dead_ts
     * @var mixed
     */
    protected $link_dead_ts = null;

    /**
     * link_token
     * @var string
     */
    protected $link_token = null;

    /**
     * link_name
     * @var string
     */
    protected $link_name = null;

    /**
     * Set link_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Link
     */
    public function setLinkId($p_value)
    {
        $this->link_id = $p_value;
        return $this;
    }

    /**
     * Get link_id
     *
     * @return int
     */
    public function getLinkId()
    {
        return $this->link_id;
    }

    /**
     * Set file_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Link
     */
    public function setFileId($p_value)
    {
        $this->file_id = $p_value;
        return $this;
    }

    /**
     * Get file_id
     *
     * @return int
     */
    public function getFileId()
    {
        return $this->file_id;
    }

    /**
     * Set user_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Link
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
     * Set link_ts
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Link
     */
    public function setLinkTs($p_value)
    {
        $this->link_ts = $p_value;
        return $this;
    }

    /**
     * Get link_ts
     *
     * @return mixed
     */
    public function getLinkTs()
    {
        return $this->link_ts;
    }

    /**
     * Set link_dead_ts
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Link
     */
    public function setLinkDeadTs($p_value)
    {
        $this->link_dead_ts = $p_value;
        return $this;
    }

    /**
     * Get link_dead_ts
     *
     * @return mixed
     */
    public function getLinkDeadTs()
    {
        return $this->link_dead_ts;
    }

    /**
     * Set link_token
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Link
     */
    public function setLinkToken($p_value)
    {
        $this->link_token = $p_value;
        return $this;
    }

    /**
     * Get link_token
     *
     * @return string
     */
    public function getLinkToken()
    {
        return $this->link_token;
    }

    /**
     * Set link_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Link
     */
    public function setLinkName($p_value)
    {
        $this->link_name = $p_value;
        return $this;
    }

    /**
     * Get link_name
     *
     * @return string
     */
    public function getLinkName()
    {
        return $this->link_name;
    }
}
