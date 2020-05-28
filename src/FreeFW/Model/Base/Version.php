<?php
namespace FreeFW\Model\Base;

/**
 * Version
 *
 * @author jeromeklam
 */
abstract class Version extends \FreeFW\Model\StorageModel\Version
{

    /**
     * vers_id
     * @var int
     */
    protected $vers_id = null;

    /**
     * vers_version
     * @var string
     */
    protected $vers_version = null;

    /**
     * vers_install_date
     * @var mixed
     */
    protected $vers_install_date = null;

    /**
     * vers_install_text
     * @var mixed
     */
    protected $vers_install_text = null;

    /**
     * vers_install_status
     * @var string
     */
    protected $vers_install_status = null;

    /**
     * vers_install_content
     * @var mixed
     */
    protected $vers_install_content = null;

    /**
     * vers_install_file
     * @var string
     */
    protected $vers_install_file = null;

    /**
     * Set vers_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Version
     */
    public function setVersId($p_value)
    {
        $this->vers_id = $p_value;
        return $this;
    }

    /**
     * Get vers_id
     *
     * @return int
     */
    public function getVersId()
    {
        return $this->vers_id;
    }

    /**
     * Set vers_version
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Version
     */
    public function setVersVersion($p_value)
    {
        $this->vers_version = $p_value;
        return $this;
    }

    /**
     * Get vers_version
     *
     * @return string
     */
    public function getVersVersion()
    {
        return $this->vers_version;
    }

    /**
     * Set vers_install_date
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Version
     */
    public function setVersInstallDate($p_value)
    {
        $this->vers_install_date = $p_value;
        return $this;
    }

    /**
     * Get vers_install_date
     *
     * @return mixed
     */
    public function getVersInstallDate()
    {
        return $this->vers_install_date;
    }

    /**
     * Set vers_install_text
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Version
     */
    public function setVersInstallText($p_value)
    {
        $this->vers_install_text = $p_value;
        return $this;
    }

    /**
     * Get vers_install_text
     *
     * @return mixed
     */
    public function getVersInstallText()
    {
        return $this->vers_install_text;
    }

    /**
     * Set vers_install_status
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Version
     */
    public function setVersInstallStatus($p_value)
    {
        $this->vers_install_status = $p_value;
        return $this;
    }

    /**
     * Get vers_install_status
     *
     * @return string
     */
    public function getVersInstallStatus()
    {
        return $this->vers_install_status;
    }

    /**
     * Set vers_install_content
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Version
     */
    public function setVersInstallContent($p_value)
    {
        $this->vers_install_content = $p_value;
        return $this;
    }

    /**
     * Get vers_install_content
     *
     * @return mixed
     */
    public function getVersInstallContent()
    {
        return $this->vers_install_content;
    }

    /**
     * Set vers_install_file
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Version
     */
    public function setVersInstallFile($p_value)
    {
        $this->vers_install_file = $p_value;
        return $this;
    }

    /**
     * Get vers_install_file
     *
     * @return string
     */
    public function getVersInstallFile()
    {
        return $this->vers_install_file;
    }
}
