<?php
/**
 * Classe de controle des brokers
 *
 * @author jeromeklam
 * @package Broker
 */
namespace FreeSSO\Controller\Html5;

/**
 * Gestion des Brokers
 * @author jeromeklam
 */
class Broker extends \FreeFW\Controller\Base
{

    /**
     * Retourne tous les brokers
     *
     * @return \FreeFW\Interfaces\Response
     */
    public function getAll($p_param = array('sort'=>'brk_name'))
    {
        $grid = new \FreeSSO\Html\Broker\Grid($p_param);
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
            '\\FreeFW\\Sso\\Model\\Broker',
            '\\FreeFW\\Sso\\Html\\Broker\\AddForm'
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
            '\\FreeFW\\Sso\\Model\\Broker',
            '\\FreeFW\\Sso\\Html\\Broker\\EditForm',
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
            '\\FreeFW\\Sso\\Model\\Broker',
            '\\FreeFW\\Sso\\Html\\Broker\\AddForm',
            '\\FreeFW\\Sso\\Html\\Broker\\EditForm',
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
            '\\FreeFW\\Sso\\Model\\Broker',
            $p_id
        );
    }
}
