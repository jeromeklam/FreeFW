<?php
namespace FreeFW\Interfaces;

/**
 *
 * @author jeromeklam
 */
interface Email
{

    /**
     * Types d'email
     *
     * @var string
     */
    const TYPE_TEXT = 'TEXT';
    const TYPE_HTML = 'HTML';
    const TYPE_BOTH = 'BOTH';

    /**
     * Retourne le type d'email
     *
     * @return string
     */
    public function getMailType();

    /**
     * Retourne l'object de l'email
     *
     * @return string
     */
    public function getMailSubject();

    /**
     * Récupération du corps html
     *
     * @return string
     */
    public function getMailBodyHtml();

    /**
     * Retourne le contenu texte
     *
     * @return string
     */
    public function getMailBodyText();

    /**
     * Affectation de l'expéditeur
     *
     * @param string $p_fromEmail
     * @param string $p_fromName
     *
     * @return string
     */
    public function setMailFrom($p_fromEmail, $p_fromName = null);

    /**
     * Retourne l'email de l'expéditeur
     *
     * @return string
     */
    public function getMailFromEmail();

    /**
     * Retourne le nom de l'expéditeur
     *
     * @return string
     */
    public function getMailFromName();

    /**
     * Retourne les distinataires sous forme de tableau
     *
     * @return array
     */
    public function getMailToAsArray();

    /**
     * Retourne les cc sous forme de tableau
     *
     * @return array
     */
    public function getMailCcAsArray();

    /**
     * Retourne les bcc sous forme de tableau
     *
     * @return array
     */
    public function getMailBccAsArray();

    /**
     * Retourne les pièces joints sous forme de tableau
     *
     * @return array
     */
    public function getMailAttachmentsAsArray();
}
