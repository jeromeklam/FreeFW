<?php
/**
 * Interface de description des données de base d'un contact
 * @author jeromeklam
 */
namespace FreeFW\Interfaces;

/**
 * Interface Contact
 * @author jeromeklam
 */
interface Contact
{

    /**
     * Retourne le nom complet
     *
     * @return string
     */
    public function getFullname();

    /**
     * Retourne le prénom
     *
     * @return string
     */
    public function getFirstname();

    /**
     * Retourne le nom
     *
     * @return string
     */
    public function getLastname();

    /**
     * Retourne l'email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Retourne le numéro de mobile
     *
     * @return string
     */
    public function getMobile();

    /**
     * Retourne un tableau avec les valeurs des champs
     *
     * @return array
     */
    public function __toArray();

    /**
     * Retourne la langue préférée
     *
     * @return \FreeFW\Constants\LANG_*
     */
    public function getPreferredLanguage();
}
