<?php
/**
 * Interface SSO
 *
 * @author jeromeklam
 * @package SSO
 * @category Interface
 */
namespace FreeFW\Interfaces;

/**
 * Interface SSO
 * @author jeromeklam
 */
interface SSO
{

    /**
     * Get current loggedin user
     * Events :
     *     *sso:lastUserUpdateEmpty
     *
     * @throws \FreeFW\Sso\SsoException
     *
     * @return \FreeFW\Sso\Model\User|false
     */
    public function getUser();

    /**
     * Register new user
     * Events :
     *     *sso:beforeRegisterNewUser
     *     *sso:afterRegisterNewUser
     *
     * @param string  $p_login
     * @param string  $p_email
     * @param string  $p_password
     * @param array   $p_datas
     * @param boolean $p_withValidation
     *
     * @throws \FreeFW\Sso\SsoException
     *
     * @return \FreeFW\Sso\Model\User|false
     */
    public function registerNewUser($p_login, $p_email, $p_password, $p_datas = array(), $p_withValidation = false);

    /**
     * Signin
     * Events :
     *     *sso:beforeSigninByLoginAndPassword
     *     *sso:lastUserUpdateEmpty
     *     *sso:afterSigninByLoginAndPassword
     *
     * @param string  $p_login
     * @param string  $p_password
     * @param boolean $p_remember
     *
     * @throws \FreeFW\Sso\SsoException
     *
     * @return boolean
     */
    public function signinByLoginAndPassword($p_login, $p_password, $p_remember = false);

    /**
     * Retourne un utilisateur selon son identifiant
     * Events :
     *     *sso:lastUserUpdateEmpty
     *
     * @param string $p_id
     *
     * @return \FreeFW\Sso\Model\User|false
     */
    public function getUserById($p_id);

    /**
     * Retourne un utilisateur selon son login
     * Events :
     *     *sso:lastUserUpdateEmpty
     *
     * @param string $p_login
     *
     * @return \FreeFW\Sso\Model\User|false
     */
    public function getUserByLogin($p_login);

    /**
     * Déconnecte l'utilisateur courant
     * Events:
     *     *sso:afterUserLogout
     *
     * @throws \FreeFW\Sso\SsoException
     */
    public function logout();
}
