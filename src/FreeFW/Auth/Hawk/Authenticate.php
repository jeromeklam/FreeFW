<?php
namespace FreeFW\Auth\Hawk;

/**
 *
 * @author jeromejklam
 */
class Authenticate extends \FreeFW\Auth\Authenticate
{

    /**
     * Vérification de la requête
     *
     * @param boolean $p_authenticated
     * @param boolean $p_allowed
     *
     * @return boolean
     */
    public function checkRequest($p_authenticated = true, $p_allowed = false)
    {
        $authenticated = $p_authenticated;
        $allowed       = $p_allowed;
        if (!$allowed) {
            $iRequestTime = $this->msg->getTime();
            $msgData      = is_array($this->msg->getData()) ?
                            $this->msg->getDataAsUrl() :
                            $this->msg->getDataForBuild();
            $data         = $iRequestTime . $this->msg->getId() . $msgData;
            self::debug('hmac.data : ' . $data);
            $serverHash   = hash_hmac('sha256', $data, $this->privateKey);
            self::debug('hmac.server : ' . $serverHash);
            self::debug('hmac.client : ' . $this->msg->getHash());
            $clientHash   = $this->msg->getHash();
            if ($authenticated && $clientHash == $serverHash) {
                $t1 = \FreeFW\Tools\Date::mysqlToDatetime($iRequestTime);
                $t2 = new \Datetime();
                $it   = $t1->diff($t2);
                $diff = $it->days*86400 + $it->h*3600 + $it->i*60 + $it->s;
                self::debug('hmac.diff.time : ' . $diff);
                if (abs($diff) <= $this->maxRequestDelay) {
                    $allowed = true;
                }
            }
        }
        return $allowed;
    }
}
