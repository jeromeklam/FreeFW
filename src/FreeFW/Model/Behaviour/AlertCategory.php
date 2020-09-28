<?php
namespace FreeFW\Model\Behaviour;

/**
 *
 * @author jeromeklam
 *
 */
trait AlertCategory
{

    /**
     * AlertCategory
     * @var \FreeFW\Model\AlertCategory
     */
    protected $category = null;

    /**
     * AlertCategoryId
     * @var number
     */
    protected $alerc_id = null;

    /**
     * Set category
     *
     * @param \FreeFW\Model\AlertCategory $p_category
     *
     * @return \FreeFW\Core\Model
     */
    public function setAlertCategory($p_category)
    {
        $this->category = $p_category;
        if ($this->category instanceof \FreeFW\Model\AlertCategory) {
            $this->setAlercId($this->category->getAlercId());
        } else {
            $this->setAlercId(null);
        }
        return $this;
    }

    /**
     * Get category
     *
     * @return \FreeFW\Model\AlertCategory
     */
    public function getAlertCategory()
    {
        if ($this->category === null) {
            if ($this->alerc_id > 0) {
                $this->category = \FreeFW\Model\AlertCategory::findFirst(['alerc_id' => $this->alerc_id]);
            }
        }
        return $this->category;
    }

    /**
     * Set category id
     *
     * @param number $p_id
     *
     * @return \FreeFW\Model\Behaviour\AlertCategory
     */
    public function setAlercId($p_id)
    {
        $this->alerc_id = $p_id;
        if ($this->category !== null) {
            if ($this->alerc_id != $this->category->getAlercId()) {
                $this->category = null;
            }
        }
        return $this;
    }

    /**
     * Get category id
     *
     * @return number
     */
    public function getAlercId()
    {
        return $this->alerc_id;
    }
}
