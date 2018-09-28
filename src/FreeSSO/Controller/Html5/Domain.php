<?php
/**
 * Classe de controle des domaines
 *
 * @author jeromeklam
 * @package Broker
 */
namespace FreeSSO\Controller\Html5;

/**
 * Gestion des Domaines
 * @author jeromeklam
 */
class Domain extends \FreeFW\Controller\Base
{

    /**
     * Retourne tous les domaines
     *
     * @return \FreeFW\Interfaces\Response
     */
    public function getAll($p_param = array('sort'=>'dom_name'))
    {
        $grid = new \FreeSSO\Html\Domain\Grid($p_param);
        return $this->getStandardHtml5Grid($grid);
    }


    /**
     * Formulaire d'ajout
     *
     * @return \FreeFW\Interfaces\Response
     */
    public function addOne()
    {
        return $this->getStandardHtml5AddForm(
            '\\FreeFW\\Sso\\Model\\Domain',
            '\\FreeFW\\Sso\\Html\\Domain\\AddForm'
        );
    }

    /**
     * Formulaire de modification
     *
     * @return \FreeFW\Interfaces\Response
     */
    public function editOne($p_id)
    {
        return $this->getStandardHtml5EditForm(
            '\\FreeFW\\Sso\\Model\\Domain',
            '\\FreeFW\\Sso\\Html\\Domain\\EditForm',
            $p_id
        );
    }

    /**
     * Sauvegarde
     *
     * @param number $p_id
     *
     * @return \FreeFW\Interfaces\Response
     */
    public function saveOne($p_id)
    {
        return $this->saveStandardHtml5Model(
            '\\FreeFW\\Sso\\Model\\Domain',
            '\\FreeFW\\Sso\\Html\\Domain\\AddForm',
            '\\FreeFW\\Sso\\Html\\Domain\\EditForm',
            $p_id
        );
    }

    /**
     * Suppression
     *
     * @param number $p_id
     *
     * @return \FreeFW\Interfaces\Response
     */
    public function deleteOne($p_id)
    {
        return $this->deleteStandardHtml5Model(
            '\\FreeFW\\Sso\\Model\\Domain',
            $p_id
        );
    }
}
