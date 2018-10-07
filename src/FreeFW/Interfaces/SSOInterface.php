<?php
namespace FreeFW\Interfaces;

/**
 * Interface SSO
 *
 * @author jeromeklam
 */
interface SSOInterface
{

    /**
     * Get current loggedin user
     * Events :
     *     *sso:lastUserUpdateEmpty
     *
     * @return \FreeFW\Interfaces\UserInterface | false
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
     * @return \FreeFW\Interfaces\UserInterface | false
     */
    public function registerNewUser(
        $p_login,
        $p_email,
        $p_password,
        array $p_datas = [],
        $p_withValidation = false
    );

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
     * @return \FreeFW\Interfaces\UserInterface | false
     */
    public function getUserById($p_id);

    /**
     * Retourne un utilisateur selon son login
     * Events :
     *     *sso:lastUserUpdateEmpty
     *
     * @param string $p_login
     *
     * @return \FreeFW\Interfaces\UserInterface | false
     */
    public function getUserByLogin($p_login);

    /**
     * Déconnecte l'utilisateur courant
     * Events:
     *     *sso:afterUserLogout
     */
    public function logout();
}
