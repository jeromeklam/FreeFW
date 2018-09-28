<?php
/**
 * ... ...
 * @author jeromeklam
 */
namespace FreeSSO\Model;

/**
 * Classe : LinkUser
 * @author : jeromeklam
 */
class LinkUser extends \FreeSSO\Model\Raw\LinkUser
{

    /**
     * Datas as accounts
     *
     * @return \FreeSSO\Model\UserAccount[]
     */
    public function getAccounts()
    {
        $list  = [];
        $infos = json_decode($this->lku_auth_datas, true);
        if (is_array($infos) && array_key_exists('accounts', $infos)) {
            $accounts = $infos['accounts'];
            foreach ($accounts as $idx => $oneAccount) {
                $anAccount = new \FreeSSO\Model\UserAccount();
                $anAccount->getFromRecord($oneAccount);
                $anAccount->setUserId($this->getUserId());
                $list[] = $anAccount;
            }
        }
        return $list;
    }
}
