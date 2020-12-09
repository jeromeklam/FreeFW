<?php
namespace FreeFW\Model\Base;

/**
 * File
 *
 * @author jeromeklam
 */
abstract class File extends \FreeFW\Model\StorageModel\File
{

    /**
     * file_id
     * @var int
     */
    protected $file_id = null;

    /**
     * brk_id
     * @var int
     */
    protected $brk_id = null;

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
     * lang_id
     * @var int
     */
    protected $lang_id = null;

    /**
     * file_ts
     * @var mixed
     */
    protected $file_ts = null;

    /**
     * parent_file_id
     * @var int
     */
    protected $parent_file_id = null;

    /**
     * file_name
     * @var string
     */
    protected $file_name = null;

    /**
     * file_blob
     * @var mixed
     */
    protected $file_blob = null;

    /**
     * file_type
     * @var string
     */
    protected $file_type = null;

    /**
     * edi_id
     * @var int
     */
    protected $edi_id = null;

    /**
     * file_object_name
     * @var string
     */
    protected $file_object_name = null;

    /**
     * file_object_id
     * @var int
     */
    protected $file_object_id = null;

    /**
     * Set file_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\File
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
     * Set brk_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\File
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
     * Set grp_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\File
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
     * @return \FreeFW\Model\File
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
     * Set lang_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\File
     */
    public function setLangId($p_value)
    {
        $this->lang_id = $p_value;
        return $this;
    }

    /**
     * Get lang_id
     *
     * @return int
     */
    public function getLangId()
    {
        return $this->lang_id;
    }

    /**
     * Set file_ts
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\File
     */
    public function setFileTs($p_value)
    {
        $this->file_ts = $p_value;
        return $this;
    }

    /**
     * Get file_ts
     *
     * @return mixed
     */
    public function getFileTs()
    {
        return $this->file_ts;
    }

    /**
     * Set parent_file_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\File
     */
    public function setParentFileId($p_value)
    {
        $this->parent_file_id = $p_value;
        return $this;
    }

    /**
     * Get parent_file_id
     *
     * @return int
     */
    public function getParentFileId()
    {
        return $this->parent_file_id;
    }

    /**
     * Set file_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\File
     */
    public function setFileName($p_value)
    {
        $this->file_name = $p_value;
        return $this;
    }

    /**
     * Get file_name
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * Set file_blob
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\File
     */
    public function setFileBlob($p_value)
    {
        $this->file_blob = $p_value;
        return $this;
    }

    /**
     * Get file_blob
     *
     * @return mixed
     */
    public function getFileBlob()
    {
        return $this->file_blob;
    }

    /**
     * Set file_type
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\File
     */
    public function setFileType($p_value)
    {
        $this->file_type = $p_value;
        return $this;
    }

    /**
     * Get file_type
     *
     * @return string
     */
    public function getFileType()
    {
        return $this->file_type;
    }

    /**
     * Set edi_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\File
     */
    public function setEdiId($p_value)
    {
        $this->edi_id = $p_value;
        return $this;
    }

    /**
     * Get edi_id
     *
     * @return int
     */
    public function getEdiId()
    {
        return $this->edi_id;
    }

    /**
     * Set file_object_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\File
     */
    public function setFileObjectName($p_value)
    {
        $this->file_object_name = $p_value;
        return $this;
    }

    /**
     * Get file_object_name
     *
     * @return string
     */
    public function getFileObjectName()
    {
        return $this->file_object_name;
    }

    /**
     * Set file_object_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\File
     */
    public function setFileObjectId($p_value)
    {
        $this->file_object_id = $p_value;
        return $this;
    }

    /**
     * Get file_object_id
     *
     * @return int
     */
    public function getFileObjectId()
    {
        return $this->file_object_id;
    }
}
