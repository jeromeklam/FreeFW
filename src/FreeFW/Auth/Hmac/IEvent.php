<?php
namespace FreeFW\Auth\Hmac;

/**
 *
 * @author jeromejklam
 */
interface IEvent
{

    /**
     * Gestion de l'événement
     */
    public function handleEvent();
}
