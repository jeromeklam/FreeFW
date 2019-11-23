<?php
namespace FreeFW\Model;

/**
 * Error
 *
 * @author : jeromeklam
 */
class Error extends \FreeFW\Core\Model
{
    
    /**
     * Get new error from exception
     *
     * @param integer $p_status
     *
     * @param \Exception $p_ex
     *
     * @return \FreeFW\Model\Error
     */
    public static function getFromException($p_status, \Exception $p_ex)
    {
        $me = new \FreeFW\Model\Error();
        $me->addError($p_ex->getCode(), $p_ex->getMessage(), $p_status);
        return $me;
    }
}