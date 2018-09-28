<?php
/**
 * Classe de controle des utilisateurs par groupe
 *
 * @author jeromeklam
 * @package Broker
 */
namespace FreeSSO\Controller\Html5;

/**
 * Gestion des Groupes
 * @author jeromeklam
 */
class GroupUser extends \FreeFW\Controller\Base
{

    /**
     * Retourne tous les Groupes
     *
     * @return \FreeFW\Interfaces\Response
     */
    public function getAllByGroup($p_grpId, $p_param = [])
    {
        $grpu = \FreeSSO\Model\Group::getById($p_grpId);
        if ($grpu !== false) {
            $grid = new \FreeSSO\Html\GroupUser\Grid($p_param);
            $grid->setFieldsForTrads($grpu);
            return $this->getStandardHtml5Grid($grid);
        } else {
            return $this->response;
        }
    }


    /**
     * Formulaire d'ajout
     *
     * @return \FreeFW\Interfaces\Response
     */
    public function addOne()
    {
        return $this->getStandardHtml5AddForm(
            '\\FreeFW\\Sso\\Model\\GroupUser',
            '\\FreeFW\\Sso\\Html\\GroupUser\\AddForm'
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
            '\\FreeFW\\Sso\\Model\\GroupUser',
            '\\FreeFW\\Sso\\Html\\GroupUser\\EditForm',
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
            '\\FreeFW\\Sso\\Model\\GroupUser',
            '\\FreeFW\\Sso\\Html\\GroupUser\\AddForm',
            '\\FreeFW\\Sso\\Html\\GroupUser\\EditForm',
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
            '\\FreeFW\\Sso\\Model\\GroupUser',
            $p_id
        );
    }
}
