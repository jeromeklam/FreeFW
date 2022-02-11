<?php
namespace FreeFW\Model\Behaviour;

/**
 * template
 *
 * @author jeromeklam
 *
 */
trait Template
{

   /**
     * Id
     * @var number
     */
    protected $tpl_id = null;

    /**
     * Template
     * @var \FreeFW\Model\Template
     */
    protected $template = null;

    /**
     * Set id : template
     *
     * @param number $p_id
     *
     * @return \FreeFW\Model\Behaviour\Template
     */
    public function setTplId($p_id)
    {
        $this->tpl_id = $p_id;
        if ($this->template) {
            if ($this->template->getTplId() != $this->tpl_id) {
                $this->template = null;
            }
        }
        return $this;
    }

    /**
     * Get id : template
     *
     * @return number
     */
    public function getTplId()
    {
        return $this->tpl_id;
    }

    /**
     * Set template
     *
     * @param \FreeFW\Model\Template $p_model
     *
     * @return \FreeFW\Core\Model
     */
    public function setTemplate($p_model)
    {
        $this->template = $p_model;
        if ($p_model) {
            $this->tpl_id = $p_model->getTplId();
        }
        return $this;
   }

   /**
     * Get template
     *
     * @param boolean $p_force
     *
     * @return \FreeFW\Model\Template
     */
    public function getTemplate($p_force = false)
    {
        if ($this->template === null || $p_force) {
            if ($this->tpl_id > 0) {
                $this->template = \FreeFW\Model\Template::findFirst(
                    [
                        'tpl_id' => $this->tpl_id
                    ]
                );
            } else {
                $this->template = null;
            }
        }
        return $this->template;
    }
}