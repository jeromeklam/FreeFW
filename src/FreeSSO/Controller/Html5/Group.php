<?php
/**
 * Classe de controle des groupes
 *
 * @author jeromeklam
 * @package Broker
 */
namespace FreeSSO\Controller\Html5;

/**
 * Gestion des Groupes
 * @author jeromeklam
 */
class Group extends \FreeFW\Controller\Base
{

    /**
     * Retourne tous les Groupes
     *
     * @return \FreeFW\Interfaces\Response
     */
    public function getAll($p_param = array('sort'=>'grp_name'))
    {
        $grid = new \FreeSSO\Html\Group\Grid($p_param);
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
            '\\FreeFW\\Sso\\Model\\Group',
            '\\FreeFW\\Sso\\Html\\Group\\AddForm'
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
            '\\FreeFW\\Sso\\Model\\Group',
            '\\FreeFW\\Sso\\Html\\Group\\EditForm',
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
            '\\FreeFW\\Sso\\Model\\Group',
            '\\FreeFW\\Sso\\Html\\Group\\AddForm',
            '\\FreeFW\\Sso\\Html\\Group\\EditForm',
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
            '\\FreeFW\\Sso\\Model\\Group',
            $p_id
        );
    }
}
