<?php
namespace FreeFW\Model\Behaviour;

/**
 *
 * @author jeromeklam
 *
 */
trait File
{

    /**
     * File
     * @var \FreeFW\Model\File
     */
    protected $file = null;

    /**
     * FileId
     * @var number
     */
    protected $file_id = null;

    /**
     * Set file
     *
     * @param \FreeFW\Model\File $p_lang
     *
     * @return \FreeFW\Core\Model
     */
    public function setFile($p_lang)
    {
        $this->file = $p_lang;
        if ($this->file instanceof \FreeFW\Model\File) {
            $this->setFileId($this->file->getFileId());
        } else {
            $this->setFileId(null);
        }
        return $this;
    }

    /**
     * Get file
     *
     * @return \FreeFW\Model\File
     */
    public function getFile()
    {
        if ($this->file === null) {
            if ($this->file_id > 0) {
                $this->file = \FreeFW\Model\File::findFirst(['file_id' => $this->file_id]);
            }
        }
        return $this->file;
    }

    /**
     * Set file id
     *
     * @param number $p_id
     *
     * @return \FreeFW\Model\Behaviour\File
     */
    public function setFileId($p_id)
    {
        $this->file_id = $p_id;
        if ($this->file !== null) {
            if ($this->file_id != $this->file->getFileId()) {
                $this->file = null;
            }
        }
        return $this;
    }

    /**
     * Get file id
     *
     * @return number
     */
    public function getFileId()
    {
        return $this->file_id;
    }
}
