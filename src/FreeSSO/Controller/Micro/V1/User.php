<?php
/**
 * Classe de controle de la partie utilisateurs
 *
 * @author jerome.klam
 * @package SSO\Controller
 */
namespace FreeSSO\Controller\Micro\V1;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Classe controlleur SSO
 * @author jerome.klam
 */
class User extends \FreeFW\Controller\Base
{

    /**
     * Retourne tous les utilisateurs d'un groupe
     *
     * @param ServerRequestInterface $request
     * @param number                 $grpId
     *
     * @return ResponseInterface
     */
    public function getAllByGroup($request, $grpId)
    {
        $list     = \FreeSSO\Model\GroupUser::find(
            array(
                'grp_id' => $grpId
            )
        );
        $result = new \FreeFW\Model\ResultSet();
        foreach ($list as $idx => $oneGroupUser) {
            $user     = \FreeSSO\Model\User::getById($oneGroupUser->getUserId());
            $result[] = $user;
        }
        return $this->createCustomResponse(200, [], $result);
    }

    /**
     * Retourne un utilisateur selon on identifiant
     *
     * @param ServerRequestInterface $request
     * @param number                 $id
     *
     * @return ResponseInterface
     */
    public function getById($request, $id)
    {
        return $this->createCustomResponse(200, [], \FreeSSO\Model\User::getById($id, null));
    }

    /**
     * Retourne un utilisateur selon son login
     *
     * @param ServerRequestInterface $request
     * @param string                 $login
     *
     * @return ResponseInterface
     */
    public function getByLogin($request, $login)
    {
        return $this->createCustomResponse(
            200,
            [],
            \FreeSSO\Model\User::getFirst(
                array('user_login' => $login),
                array(),
                array(),
                null
            )
        );
    }

    /**
     * Ajout d'un utilisateur
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function addOneForGroup($request, $grpId)
    {
        $errors = new \FreeFW\Http\Errors();
        // Vérification des paramètres obligatoires
        if ($grpId === null || $grpId == '' || $grpId == '0') {
            $errors->addError(
                \FreeSSO\ErrorCodes::ERROR_PARAM_MISSING,
                sprintf('Param %s missing', 'grp_id'),
                null,
                'grp_id',
                \FreeFW\Http\Error::TYPE_REQUIRED
            );
        }
        if (!$request->hasAttribute('user_key')) {
            $errors->addError(
                \FreeSSO\ErrorCodes::ERROR_PARAM_MISSING,
                sprintf('Param %s missing', 'user_key'),
                null,
                'user_key',
                \FreeFW\Http\Error::TYPE_REQUIRED
            );
        }
        // Je continue que si je n'ai pas d'erreurs
        if (!$errors->hasErrors()) {
            // On recherche si le groupe existe
            $login  = $request->getAttribute('user_login');
            $key    = $request->getAttribute('user_key');
            $group  = \FreeSSO\Model\Group::getById($grpId, false);
            if ($group) {
                if (trim($login) == '') {
                    $login = $group->getGrpVerifKey() . '@' . $key;
                }
                // Je vérifie que l'utilisateur n'existe pas déjà
                $user = \FreeSSO\Model\User::getFirst(
                    array(
                        'user_login' => $login
                    )
                );
                if ($user !== false) {
                    $errors->addError(
                        \FreeSSO\ErrorCodes::ERROR_EXISTS,
                        sprintf('User with login %s allready exists', $login),
                        null,
                        'user_login',
                        \FreeFW\Http\Error::TYPE_UNIQUE
                    );
                } else {
                    // C'est bon, je créé...
                    $user = new \FreeSSO\Model\User();
                    $user
                        ->setUserActive(0)
                        ->setUserLogin($login)
                        ->setUserType(\FreeSSO\Model\User::TYPE_REST)
                        ->createNewPassword(uniqid(microtime()))
                    ;
                    // Je traite les paramètres optionnels
                    if ($request->hasAttribute('user_email')) {
                        $user->setUserEmail($request->getAttribute('user_email'));
                    }
                    if ($request->hasAttribute('user_first_name')) {
                        $user->setUserFirstName($request->getAttribute('user_first_name'));
                    }
                    if ($request->hasAttribute('user_last_name')) {
                        $user->setUserLastName($request->getAttribute('user_last_name'));
                    }
                    // @var \PDO
                    $pdo = self::getDIConnexion();
                    $pdo->startTransaction();
                    if (!$user->create(false)) {
                        $errors->addCritical(
                            \FreeSSO\ErrorCodes::ERROR_CREATION,
                            sprintf('Error creating %s user', $login),
                            null
                        );
                    } else {
                        $userGroup = new \FreeSSO\Model\GroupUser();
                        $userGroup
                            ->setGrpId($group->getGrpId())
                            ->setUserId($user->getUserId())
                            ->setGruKey($key)
                            ->setGruDefault(1)
                        ;
                        if ($request->hasAttribute('gru_infos')) {
                            $userGroup->setGruInfos(json_encode($request->getAttribute('gru_infos')));
                        }
                        if (!$userGroup->create(false)) {
                            $errors->addCritical(
                                \FreeSSO\ErrorCodes::ERROR_CREATION,
                                sprintf('Error creating link between %s user and %s group', $login, $grpId),
                                null
                            );
                        }
                    }
                    if ($errors->hasErrors()) {
                        $pdo->rollbackTransaction();
                    } else {
                        $pdo->commitTransaction();
                    }
                }
            } else {
                $errors->addError(
                    \FreeSSO\ErrorCodes::ERROR_NOT_FOUND,
                    sprintf('Group %s not found !', $grpKey),
                    null,
                    'grp_key'
                );
            }
        }
        if ($errors->hasErrors()) {
            return $this->createCustomResponse(400, [], $errors);
        }
        return $this->createCustomResponse(200, [], $user);
    }

    /**
     * Mise à jour de l'utilisateur connecté
     *
     * @param ServerRequestInterface $request
     *
     * @return \FreeFW\Behaviour\ResponseInterface
     */
    public function updateCurrent($request)
    {
        $errors = new \FreeFW\Http\Errors();
        $sso    = self::getDIShared('sso');
        $user   = $sso->getUser();
        if ($user !== false) {
            $updateUser = false;
            if ($request->hasAttribute('dashboards')) {
                $dashboard = $request->getAttribute('dashboards');
                $user->updateCacheKey('dashboards', $dashboard);
                $updateUser = true;
            }
            if ($request->hasAttribute('panels')) {
                $panels = $request->getAttribute('panels');
                $user->updateCacheKey('panels', $panels);
                $updateUser = true;
            }
            if ($updateUser) {
                $user->save();
            }
            // @todo : check with jwt user...
        } else {
            return $this->createCustomResponse(400, [], null, sprintf('User with %s id not found !', $id));
        }
        return $this->createCustomResponse(200, [], $user);
    }

    /**
     * Mise à jour selon un identifiant
     *
     * @param ServerRequestInterface $request
     * @param number                 $id
     *
     * @return ResponseInterface
     */
    public function updateById($request, $id)
    {
        $errors = new \FreeFW\Http\Errors();
        $user   = \FreeSSO\Model\User::getById($id);
        if ($user !== false) {

        } else {
            return $this->createCustomResponse(400, [], null, sprintf('User with %s id not found !', $id));
        }
        return $this->createCustomResponse(200, [], $user);
    }

    /**
     * Modification d'un utilisateur
     *
     * @param ServerRequestInterface $request
     * @param number                 $grpId
     * @param number                 $id
     *
     * @return ResponseInterface
     */
    public function updateaOneForGroup($request, $grpId, $id)
    {
        $errors = new \FreeFW\Http\Errors();
        $user   = \FreeSSO\Model\User::getById($id);
        if ($user !== false) {
            // Je traite les paramètres optionnels
            if ($request->hasAttribute('user_active')) {
                $user->setUserActive($request->getAttribute('user_active'));
            }
            if ($request->hasAttribute('user_login')) {
                $user->setUserLogin($request->getAttribute('user_login'));
            }
            if ($request->hasAttribute('user_email')) {
                $user->setUserEmail($request->getAttribute('user_email'));
            }
            if ($request->hasAttribute('user_first_name')) {
                $user->setUserFirstName($request->getAttribute('user_first_name'));
            }
            if ($request->hasAttribute('user_last_name')) {
                $user->setUserLastName($request->getAttribute('user_last_name'));
            }
            // @var \PDO
            $pdo = self::getDIConnexion();
            $pdo->startTransaction();
            if (!$user->save()) {
                $errors->addCritical(
                    \FreeSSO\ErrorCodes::ERROR_MODIFICATION,
                    sprintf('Error creating %s user', $id),
                    null
                );
            } else {
                if ($request->hasAttribute('gru_infos')) {
                    $userGroup = \FreeSSO\Model\GroupUser::getFirst(
                        array(
                            'grp_id'  => $grpId,
                            'user_id' => $id
                        )
                    );
                    if (!$userGroup instanceof \FreeSSO\Model\GroupUser) {
                        $errors->addCritical(
                            \FreeSSO\ErrorCodes::ERROR_MODIFICATION,
                            sprintf('Error retrieving %s user group %s !', $id, $grpId),
                            null
                        );
                    }
                    $userGroup->setGruInfos(json_encode($request->getAttribute('gru_infos')));
                    if (!$userGroup->save()) {
                        $errors->addCritical(
                            \FreeSSO\ErrorCodes::ERROR_MODIFICATION,
                            sprintf('Error modifying %s user', $id),
                            null
                        );
                    }
                }
            }
            if ($errors->hasErrors()) {
                $pdo->rollbackTransaction();
            } else {
                $pdo->commitTransaction();
            }
        } else {
            return $this->createCustomResponse(400, [], null, sprintf('User with %s id not found !', $id));
        }
        return $this->createCustomResponse(200, [], $user);
    }

    /**
     * Suppression d'un utilisateur selon son identifiant
     *
     * @param ServerRequestInterface $request
     * @param number                 $id
     *
     * @return ResponseInterface
     */
    public function delById($request, $id)
    {
        $user = \FreeSSO\Model\User::getById($id);
        if ($user !== false) {
            if (!$user->remove()) {
                return $this->createCustomResponse(500, [], null, sprintf('Error deleting %s user !', $id));
            }
        } else {
            return $this->createCustomResponse(400, [], null, sprintf('User with %s id not found !', $id));
        }
        return $this->createCustomResponse(200);
    }
}
