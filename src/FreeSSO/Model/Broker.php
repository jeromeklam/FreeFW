<?php
/**
 * ... ...
 * @author jeromeklam
 */
namespace FreeSSO\Model;

/**
 * Classe : Broker
 * @author : jeromeklam
 */
class Broker extends \FreeSSO\Model\Raw\Broker
{

    /**
     * Is active ?
     *
     * @return boolean
     */
    public function isActive()
    {
        if (isset($this->brk_active) && ($this->brk_active== 1 ||
            in_array(strtoupper($this->brk_active), array('O', 'Y', '1')))) {
            return true;
        }
        return false;
    }

    /**
     * Verify that the required scope is present in the broker's config
     *
     * @param string $p_scope
     *
     * @return boolean
     */
    public function verifyApiScope($p_scope)
    {
        $scopes = explode(';', $this->getBrkApiScope());
        if (in_array($p_scope, $scopes) || array_key_exists($p_scope, $scopes)) {
            return true;
        }
        return false;
    }

    /**
     * Retourne tous les domaines
     *
     * @return array
     */
    public static function getAllDomains()
    {
        $arr = \FreeSSO\Model\Domain::find();
        return $arr;
    }
}
