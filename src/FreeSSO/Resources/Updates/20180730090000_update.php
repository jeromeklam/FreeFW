<?php

/**
 *
 */
function update20180730090000()
{
    $domain = \FreeSSO\Model\Domain::getFirst(
        [
            'dom_key' => 'jvsonline.fr'
        ]
    );
    if ($domain !== false) {
        $groups = \FreeSSO\Model\Group::find();
        foreach ($groups as $dx => $oneGroup) {
            $config = [];
            $config["db1.dsn"]         = "";
            $config["db1.username"]    = "";
            $config["db1.password"]    = "";
            $config["db1.dbname"]      = "";
            $config["db2.dsn"]         = "";
            $config["db2.username"]    = "";
            $config["db2.password"]    = "";
            $config["db2.dbname"]      = "";
            $config["email.fromemail"] = "";
            $config["email.fromname"]  = "";
            //
            $client = \GicBx\Model\Client::getFirst(
                [
                    'code' => $oneGroup->getGrpName()
                ]
            );
            $clName = $oneGroup->getGrpName();
            if ($client !== false) {
                $clName = '[' . $client->getCode() . '] ' . $client->getNom();
            }
            //
            $grpCfg = json_decode($oneGroup->getGrpCnx(), true);
            if ($grpCfg) {
                $cfg = json_encode(array_merge($config, $grpCfg));
            }
            $broker = new \FreeSSO\Model\Broker();
            $broker
                ->setBrkName($clName)
                ->setBrkKey($oneGroup->getGrpVerifKey())
                ->setBrkActive(1)
                ->setDomId($domain->getDomId())
                ->setBrkConfig($cfg);
            ;
            if (!$broker->create()) {
                return false;
            }
            $grpUsers = \FreeSSO\Model\GroupUser::find(
                [
                    'grp_id' => $oneGroup->getGrpId()
                ]
            );
            foreach ($grpUsers as $idx2 => $oneGroupUser) {
                $user = \FreeSSO\Model\User::getById($oneGroupUser->getUserId());
                if ($user) {
                    $config   = json_decode($user->getUserCnx(), true);
                    $add      = false;
                    $linkUser = \FreeSSO\Model\LinkUser::getFirst(
                        [
                            'brk_id'  => $broker->getBrkId(),
                            'user_id' => $user->getUserId()
                        ]
                    );
                    if ($linkUser === false) {
                        $linkUser = new \FreeSSO\Model\LinkUser();
                        $add      = trua;
                    }
                    $linkUser
                        ->setUserId($user->getUserId())
                        ->setBrkId($broker->getBrkId())
                        ->setLkuPartnerId(intval($oneGroupUser->getGruKey()))
                        ->setLkuPartnerType('omega-util')
                        ->setLkuTs(\FreeFW\Tools\Date::getCurrentTimestamp())
                        ->setLkuAuthMethod(\FreeSSO\Model\LinkUser::AUTH_METHOD_AUTO)
                        ->setLkuAuthDatas($oneGroupUser->getGruInfos())
                    ;
                    if ($add) {
                        if (!$linkUser->create()) {
                            return false;
                        }
                    } else {
                        if (!$linkUser->save()) {
                            return false;
                        }
                    }
                } else {
                    return false;
                }
            }
        }
        return true;
    }
    return false;
}
