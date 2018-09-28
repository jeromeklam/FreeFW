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
class BrokerSession extends \FreeFW\Controller\Base
{

    /**
     * Retourne tous les brokers
     *
     * @return \FreeFW\Interfaces\Response
     */
    public function getAll($p_param = array('sort'=>'-brs_end'))
    {
        $grid = new \FreeSSO\Html\BrokerSession\Grid($p_param);
        return $this->getStandardHtml5Grid($grid);
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
            '\\FreeFW\\Sso\\Model\\BrokerSession',
            $p_id
        );
    }
}
