<?php
/**
 * ... ...
 * @author jeromeklam
 */
namespace FreeSSO\Model;

/**
 * Classe : GroupUser
 * @author : jeromeklam
 */
class GroupUser extends \FreeSSO\Model\Raw\GroupUser
{

    /**
     * Retourne un compte
     *
     * @param string $p_type
     *
     * @return array|false
     */
    public function getAccount($p_type)
    {
        $infos = json_decode($this->gru_infos, true);
        if (is_array($infos) && array_key_exists('accounts', $infos)) {
            $accounts = $infos['accounts'];
            foreach ($accounts as $idx => $oneAccount) {
                if (array_key_exists('type', $oneAccount) && $oneAccount['type'] == $p_type) {
                    $anAccount = new \FreeSSO\Model\UserAccount();
                    return $anAccount->getFromRecord($oneAccount);
                }
            }
        }
        return false;
    }

    /**
     * Retourne tous les comptes
     *
     * @return array|false
     */
    public function getAccounts()
    {
        $list  = [];
        $infos = json_decode($this->gru_infos, true);
        if (is_array($infos) && array_key_exists('accounts', $infos)) {
            $accounts = $infos['accounts'];
            foreach ($accounts as $idx => $oneAccount) {
                $anAccount = new \FreeSSO\Model\UserAccount();
                $list[] = $anAccount->getFromRecord($oneAccount);
            }
        }
        return $list;
    }

    /**
     * Affectation des infos avec nettoyage
     *
     * @param mixed $p_datas
     *
     * @return \FreeSSO\Model\Raw\GroupUser
     */
    public function setAndCleanGruInfos($p_datas)
    {
        if (!is_array($p_datas)) {
            $p_datas = json_decode($p_datas, true);
        }
        if (is_array($p_datas)) {
            $datas = [];
            if (array_key_exists('accounts', $p_datas)) {
                $list = [];
                foreach ($p_datas['accounts'] as $idx => $oneAccount) {
                    if (!array_key_exists('grp_id', $oneAccount) || $oneAccount['grp_id'] == $this->getGrpId()) {
                        $anAccount = new \FreeSSO\Model\UserAccount();
                        $list[]    = $anAccount->getFromRecord($oneAccount);
                    }
                }
                $datas['accounts'] = $list;
            }
            $this->setGruInfos(json_encode($datas));
        }
        return $this;
    }

    /**
     * Avant l'enregistrement
     *
     * @return boolean
     */
    public function beforeSave()
    {
        if ($this->gru_key === null || trim($this->gru_key) == '') {
            $this->gru_key = md5(uniqid(microtime()));
        }
        return true;
    }
}
