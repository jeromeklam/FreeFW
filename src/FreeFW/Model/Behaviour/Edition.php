<?php
namespace FreeFW\Model\Behaviour;

/**
 *
 * @author jeromeklam
 *
 */
trait Edition
{

    /**
     * Edition
     * @var \FreeFW\Model\Edition
     */
    protected $edition = null;

    /**
     * EdiId
     * @var number
     */
    protected $edi_id = null;

    /**
     * Set edition
     *
     * @param \FreeFW\Model\Edition $p_edition
     *
     * @return \FreeFW\Core\Model
     */
    public function setEdition($p_edition)
    {
        $this->edition = $p_edition;
        if ($this->edition instanceof \FreeFW\Model\Edition) {
            $this->setEdiId($this->edition->getEdiId());
        } else {
            $this->setEdiId(null);
        }
        return $this;
    }

    /**
     * Get edition
     *
     * @return \FreeFW\Model\Edition
     */
    public function getEdition()
    {
        if ($this->edition === null) {
            if ($this->edi_id > 0) {
                $this->edition = \FreeFW\Model\Edition::findFirst(['edi_id' => $this->edi_id]);
            }
        }
        return $this->edition;
    }

    /**
     * Set edition id
     *
     * @param number $p_id
     *
     * @return \FreeFW\Model\Behaviour\Edition
     */
    public function setEdiId($p_id)
    {
        $this->edi_id = $p_id;
        if ($this->edition !== null) {
            if ($this->edi_id != $this->edition->getEdiId()) {
                $this->edition = null;
            }
        }
        return $this;
    }

    /**
     * Get edition id
     *
     * @return number
     */
    public function getEdiId()
    {
        return $this->edi_id;
    }
}
