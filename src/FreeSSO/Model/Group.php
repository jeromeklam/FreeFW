<?php
/**
 * ... ...
 * @author jeromeklam
 */
namespace FreeSSO\Model;

/**
 * Classe : Group
 * @author : jeromeklam
 */
class Group extends \FreeSSO\Model\Raw\Group
{

    /**
     * Ajoute une IP
     *
     * @param unknown $p_ip
     *
     * @return \\FreeSSO\Model\Group;
     */
    public function addGrpIp($p_ip)
    {
        $arr = json_decode($this->grp_ips, true);
        if (!is_array($arr)) {
            $arr = array();
        }
        $arr[] = $p_ip;
        $this->grp_ips = json_encode($arr);
        return $this;
    }

    /**
     * Avant la création
     *
     * @return boolean
     */
    public function beforeSave()
    {
        if ($this->grp_key === null || trim($this->grp_key) == '') {
            $this->grp_key = md5(uniqid(true) . uniqid(true));
        }
        if ($this->grp_verif_prefix === null || trim($this->grp_verif_prefix) == '') {
            $this->grp_verif_prefix = md5(uniqid(true) . uniqid(true));
            $this->grp_verif_key    = $this->grp_verif_prefix . '@' . $this->grp_verif_key;
        }
        return true;
    }

    /**
     * Conversion en réponse Json API
     *
     * @param unknown $p_version
     *
     * @return array
     */
    public function __toJsonApi($p_version)
    {
        return array(
            'type' => 'group',
            'id'   => $this->getGrpId(),
            'attributes' => array(
                'grp_id'           => $this->getGrpId(),
                'grp_key'          => $this->getGrpKey(),
                'grp_verif_key'    => $this->getGrpVerifKey(),
                'grp_name'         => $this->getGrpName(),
                'grp_verif_prefix' => $this->getGrpVerifPrefix()
            )
        );
    }

    /**
     * Retourne l'identifiant
     *
     * @return number
     */
    public function getId()
    {
        return $this->getGrpId();
    }

    /**
     * Libellé pour l'affichage
     *
     * @return string
     */
    public function getDisplay()
    {
        return $this->getGrpName();
    }
}
