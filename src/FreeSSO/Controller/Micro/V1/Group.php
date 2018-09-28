<?php
/**
 * Classe de controle de la partie groupes
 *
 * @author jeromeklam
 * @package SSO\Controller
 */
namespace FreeSSO\Controller\Micro\V1;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Classe controlleur SSO
 * @author jeromeklam
 */
class Group extends \FreeFW\Controller\Base
{

    /**
     * Retourne tous les groupes
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function getAll($request)
    {
        return $this->createCustomResponse(200, [], \FreeSSO\Model\Group::find());
    }

    /**
     * Retourne le groupe suivant son id
     *
     * @param ServerRequestInterface $request
     * @param number                 $id
     *
     * @return ResponseInterface
     */
    public function getById($request, $id)
    {
        return $this->createCustomResponse(200, [], \FreeSSO\Model\Group::getById($id, null));
    }

    /**
     * Retourne le groupe suivant une clef
     *
     * @param ServerRequestInterface $request
     * @param string                 $key
     *
     * @return ResponseInterface
     */
    public function getByKey($request, $key)
    {
        return $this->createCustomResponse(
            200,
            [],
            \FreeSSO\Model\Group::getFirst(
                array('grp_key' => $key),
                array(),
                array(),
                null
            )
        );
    }

    /**
     * Retourne le groupe suivant une clef de vérifications
     *
     * @param ServerRequestInterface $request
     * @param string                 $key
     *
     * @return ResponseInterface
     */
    public function getByVerifKey($request, $key)
    {
        return $this->createCustomResponse(
            200,
            [],
            \FreeSSO\Model\Group::getFirst(
                array('grp_verif_key' => $key),
                array(),
                array(),
                null
            )
        );
    }

    /**
     * Ajout d'un nouveau groupe
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function addOne($request)
    {
        $group = null;
        if ($request->hasAttribute('grp_verif_key')) {
            $key   = $request->getAttribute('grp_verif_key');
            $group = \FreeSSO\Model\Group::getFirst(array('grp_verif_key' => $key));
            if ($group instanceof \FreeSSO\Model\Group) {
                // @todo : Je dois vérifier les IPs, si ok, c'est celui-là...
                $clientIp = $request->getAddr();
            } else {
                $group = new \FreeSSO\Model\Group();
                $group
                    ->setGrpActive(true)
                    ->setGrpVerifKey($key)
                    ->setGrpName($key)
                    ->addGrpIp($request->getClientIp())
                ;
                if (!$group->create()) {
                    $errors = new \FreeFW\Http\Errors();
                    $errors->addError(0, sprintf('Error creating group for key %s !', $key));
                    return $this->createCustomResponse(
                        500,
                        [],
                        $errors,
                        sprintf('Error creating group for key %s !', $key)
                    );
                }
            }
        } else {
            $errors = new \FreeFW\Http\Errors();
            $errors->addError(0, sprintf('Param %s missing', 'grp_verif_key'));
            return $this->createCustomResponse(
                400,
                [],
                $errors,
                sprintf('Param %s missing', 'grp_verif_key')
            );
            $response->addFormattedError(
                \FreeSSO\ErrorCodes::ERROR_PARAM_MISSING,
                'grp_verif_key',
                sprintf('Param %s missing', 'grp_verif_key')
            );
        }
        return $this->createCustomResponse(200, [], $group);
    }

    /**
     * Suppression d'un groupe
     *
     * @param ServerRequestInterface $request
     * @param integer                $id
     *
     * @return ResponseInterface
     */
    public function delOne($request, $id)
    {
        $group = \FreeSSO\Model\Group::getById($id);
        if ($group !== false) {
            // @todo : contrôles
            if (!$group->remove()) {
                return $this->createCustomResponse(500, [], null, sprintf('Error deleting %s group !', $id));
            }
        } else {
            return $this->createCustomResponse(400, [], null, sprintf('Group with %s id not found !', $id));
        }
        return $this->createCustomResponse(200);
    }

    /**
     * Retourne les utilisateurs d'un groupe
     *
     * @param ServerRequestInterface $request
     * @param string                 $grpVerifKey
     *
     * @return ResponseInterface
     */
    public function getUsersByGroupVerifKey($request, $grpVerifKey)
    {
        $group = \FreeSSO\Model\Group::getFirst(
            array(
                'grp_verif_key' => $grpVerifKey
            )
        );
        if ($group !== false) {
            $list = \FreeSSO\Model\GroupUser::find(
                array(
                    'grp_id' => $group->getGrpId()
                )
            );
            $result = new \FreeFW\Model\ResultSet();
            foreach ($list as $idx => $oneGroupUser) {
                $user     = \FreeSSO\Model\User::getById($oneGroupUser->getUserId());
                $result[] = $user;
            }
            return $this->createCustomResponse(200, [], $result);
        }
        return $this->createCustomResponse(200);
    }

    /**
     * Retourne un utilisateur selon ses clefs groupe et utilisateur
     *
     * @param ServerRequestInterface $request
     * @param string                 $grpVerifKey
     * @param string                 $usrKey
     *
     * @return ResponseInterface
     */
    public function getUserByKey($request, $grpVerifKey, $usrKey)
    {
        $group = \FreeSSO\Model\Group::getFirst(
            array(
                'grp_verif_key' => $grpVerifKey
            )
        );
        if ($group !== false) {
            $groupUser = \FreeSSO\Model\GroupUser::getFirst(
                array(
                    'grp_id'  => $group->getGrpId(),
                    'gru_key' => $usrKey
                )
            );
            if ($groupUser !== false) {
                $user = \FreeSSO\Model\User::getById($groupUser->getUserId(), null);
                if ($user && $request->hasAttribute('with_quota')) {
                    if (in_array($request->getAttribute('with_quota'), [1, '1', 'O', true])) {
                        $smsUser = null;
                        try {
                            $smsService = $this::getDIService('FreeFW.Sms::Sms');
                            $config     = self::getDIConfig();
                            $grpCfg     = json_decode($group->getGrpCnx(), true);
                            $account    = $groupUser->getAccount(\FreeSSO\Model\UserAccount::TYPE_SMS);
                            if ($account !== false) {
                                if (array_key_exists('sms_provider', $grpCfg) && $grpCfg['sms_provider'] != '') {
                                    $datas             = array_merge($config->get('sms'), $grpCfg);
                                    $datas['provider'] = $grpCfg['sms_provider'];
                                } else {
                                    $datas = array_merge($config->get('sms'), $grpCfg, $account->__toArray());
                                }
                                $smsUser = $smsService->getSmsQuota($datas);
                            }
                        } catch (\Exception $ex) {
                            return $this->createCustomResponse(500, [], null, $ex->getMessage());
                        }
                        $user->setSmsUser($smsUser);
                    }
                }
                return $this->createCustomResponse(
                    200,
                    [],
                    $user
                );
            }
        }
        return $this->createCustomResponse(200);
    }

    /**
     * Ajoute un utilisateur selon ses clefs groupe et utilisateur
     *
     * @param ServerRequestInterface $request
     * @param string                 $grpVerifKey
     * @param string                 $usrKey
     *
     * @return ResponseInterface
     */
    public function addUserByKey($request, $grpVerifKey, $usrKey)
    {
        $errors = new \FreeFW\Http\Errors();
        $user   = null;
        $group  = \FreeSSO\Model\Group::getFirst(
            array(
                'grp_verif_key' => $grpVerifKey
            )
        );
        if ($group !== false) {
            $userGroup = \FreeSSO\Model\GroupUser::getFirst(
                array(
                    'grp_id'  => $group->getGrpId(),
                    'gru_key' => $usrKey
                )
            );
            if ($userGroup === false) {
                $user = new \FreeSSO\Model\User();
                $user
                    ->setUserType(\FreeSSO\Model\User::TYPE_REST)
                    ->createNewPassword(uniqid(microtime()))
                    ->setUserActive(false)
                ;
                // Je traite les paramètres optionnels
                if ($request->hasAttribute('user_active')) {
                    $user->setUserActive($request->getAttribute('user_active'));
                }
                if ($request->hasAttribute('user_login')) {
                    // @todo : verify unicity !!
                    $otherUser = \FreeSSO\Model\user::getFirst(
                        array(
                            'user_login' => $request->getAttribute('user_login')
                        )
                    );
                    if ($otherUser !== false) {
                        $errors->addNonUniqueField(
                            \FreeSSO\ErrorCodes::ERROR_MODIFICATION,
                            sprintf('Error updating %s user', $usrKey),
                            null
                        );
                    } else {
                        $user->setUserLogin($request->getAttribute('user_login'));
                    }
                } else {
                    $user->setUserLogin($group->getGrpVerifKey() . '@' . $usrKey);
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
                if ($request->hasAttribute('user_active')) {
                    $user->setUserActive($request->getAttribute('user_active'));
                }
                // @var \PDO
                $pdo = self::getDIConnexion();
                $pdo->startTransaction();
                if (!$user->create(false)) {
                    $errors->addCritical(
                        \FreeSSO\ErrorCodes::ERROR_CREATION,
                        sprintf('Error creating %s user', $usrKey),
                        null
                    );
                } else {
                    $userGroup = new \FreeSSO\Model\GroupUser();
                    $userGroup
                        ->setGruActive(true)
                        ->setGruDefault(true)
                        ->setGrpId($group->getGrpId())
                        ->setUserId($user->getUserId())
                        ->setGruKey($usrKey)
                    ;
                    if ($request->hasAttribute('gru_infos')) {
                        $userGroup->setAndCleanGruInfos($request->getAttribute('gru_infos'));
                    }
                    if (!$userGroup->create(false)) {
                        $errors->addCritical(
                            \FreeSSO\ErrorCodes::ERROR_MODIFICATION,
                            sprintf('Error modifying %s user', $usrKey),
                            null
                        );
                    }
                }
                if ($errors->hasErrors()) {
                    $pdo->rollbackTransaction();
                } else {
                    $pdo->commitTransaction();
                }
            } else {
                self::error(sprintf('User with %s key exists !', $usrKey));
                return $this->createCustomResponse(400, [], null, sprintf('User with %s key exists !', $usrKey));
            }
        } else {
            self::error(sprintf('Group with %s key not found !', $grpVerifKey));
            return $this->createCustomResponse(400, [], null, sprintf('Group with %s key not found !', $grpVerifKey));
        }
        if ($errors->hasErrors()) {
            self::error(print_r($errors, true));
            return $this->createCustomResponse(400, [], $errors);
        }
        return $this->createCustomResponse(200, [], $user);
    }

    /**
     * Retourne un utilisateur selon ses clefs groupe et utilisateur
     *
     * @param ServerRequestInterface $request
     * @param string                 $grpVerifKey
     * @param string                 $usrKey
     *
     * @return ResponseInterface
     */
    public function updateUserByKey($request, $grpVerifKey, $usrKey)
    {
        $errors = new \FreeFW\Http\Errors();
        $user   = null;
        $group  = \FreeSSO\Model\Group::getFirst(
            array(
                'grp_verif_key' => $grpVerifKey
            )
        );
        if ($group !== false) {
            $userGroup = \FreeSSO\Model\GroupUser::getFirst(
                array(
                    'grp_id'  => $group->getGrpId(),
                    'gru_key' => $usrKey
                )
            );
            if ($userGroup !== false) {
                $user = \FreeSSO\Model\User::getById($userGroup->getUserId(), null);
                // Je traite les paramètres optionnels
                if ($request->hasAttribute('user_active')) {
                    $user->setUserActive($request->getAttribute('user_active'));
                }
                if ($request->hasAttribute('user_login')) {
                    // @todo : verify unicity !!
                    $otherUser = \FreeSSO\Model\user::getFirst(
                        array(
                            'user_login' => $request->getAttribute('user_login'),
                            'user_id'    => array(
                                \FreeFW\Model\AbstractStorage::FIND_NOT_EQUAL => $user->getUserid()
                            )
                        )
                    );
                    if ($otherUser !== false) {
                        $errors->addNonUniqueField(
                            \FreeSSO\ErrorCodes::ERROR_MODIFICATION,
                            sprintf('Error updating %s user', $usrKey),
                            null
                        );
                    } else {
                        $user->setUserLogin($request->getAttribute('user_login'));
                    }
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
                if ($request->hasAttribute('user_active')) {
                    $user->setUserActive($request->getAttribute('user_active'));
                }
                // @var \PDO
                $pdo = self::getDIConnexion();
                $pdo->startTransaction();
                if (!$user->save(false, false)) {
                    $errors->addCritical(
                        \FreeSSO\ErrorCodes::ERROR_MODIFICATION,
                        sprintf('Error updating %s user', $usrKey),
                        null
                    );
                } else {
                    if ($request->hasAttribute('gru_infos')) {
                        if (!$userGroup instanceof \FreeSSO\Model\GroupUser) {
                            $errors->addCritical(
                                \FreeSSO\ErrorCodes::ERROR_MODIFICATION,
                                sprintf('Error retrieving %s user group %s !', $usrKey, $grpVerifKey),
                                null
                            );
                        }
                        $userGroup->setAndCleanGruInfos($request->getAttribute('gru_infos'));
                        if (!$userGroup->save(false, false)) {
                            $errors->addCritical(
                                \FreeSSO\ErrorCodes::ERROR_MODIFICATION,
                                sprintf('Error modifying %s user', $usrKey),
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
                return $this->createCustomResponse(400, [], null, sprintf('User with %s key not found !', $usrKey));
            }
        } else {
            return $this->createCustomResponse(400, [], null, sprintf('Group with %s key not found !', $grpVerifKey));
        }
        if ($errors->hasErrors()) {
            return $this->createCustomResponse(400, [], $errors);
        }
        return $this->createCustomResponse(200, [], $user);
    }


    /**
     * Supprime un utilisateur selon ses clefs groupe et utilisateur
     *
     * @param ServerRequestInterface $request
     * @param string                 $grpVerifKey
     * @param string                 $usrKey
     *
     * @return ResponseInterface
     */
    public function deleteUserByKey($request, $grpVerifKey, $usrKey)
    {
        $errors = new \FreeFW\Http\Errors();
        $user   = null;
        $group  = \FreeSSO\Model\Group::getFirst(
            array(
                'grp_verif_key' => $grpVerifKey
            )
        );
        if ($group !== false) {
            $userGroup = \FreeSSO\Model\GroupUser::getFirst(
                array(
                    'grp_id'  => $group->getGrpId(),
                    'gru_key' => $usrKey
                )
            );
            if ($userGroup !== false) {
                $user = \FreeSSO\Model\User::getById($userGroup->getUserId(), null);
                if (!$userGroup->remove()) {
                    $errors->addCritical(
                        \FreeSSO\ErrorCodes::ERROR_REMOVE,
                        sprintf('Error removinf %s user', $usrKey),
                        null
                    );
                }
            } else {
                return $this->createCustomResponse(400, [], null, sprintf('User with %s key not found !', $usrKey));
            }
        } else {
            return $this->createCustomResponse(400, [], null, sprintf('Group with %s key not found !', $grpVerifKey));
        }
        if ($errors->hasErrors()) {
            return $this->createCustomResponse(400, [], $errors);
        }
        return $this->createCustomResponse(200);
    }

    /**
     * Envoi d'un SMS en utilisant le compte de l'utilisateur
     *
     * @param ServerRequestInterface $request
     * @param string                 $grpVerifKey
     * @param string                 $usrKey
     *
     * @return ResponseInterface
     */
    public function sendSmsByUserByKey($request, $grpVerifKey, $usrKey)
    {
        // Avant on vérifie les paramètres obligatoires
        $errors = new \FreeFW\Http\Errors();
        if (!$request->hasAttribute('msg_dest')) {
            $errors->addError(0, sprintf('Field %s missing !', 'msg_dest'), null, 'msg_dest');
        }
        if (!$request->hasAttribute('msg_text')) {
            $errors->addError(0, sprintf('Field %s missing !', 'msg_text'), null, 'msg_text');
        }
        if (!$errors->hasErrors()) {
            $group = \FreeSSO\Model\Group::getFirst(
                array(
                    'grp_verif_key' => $grpVerifKey
                )
            );
            if ($group !== false) {
                $groupUser = \FreeSSO\Model\GroupUser::getFirst(
                    array(
                        'grp_id'  => $group->getGrpId(),
                        'gru_key' => $usrKey
                    )
                );
                if ($groupUser !== false) {
                    $user = \FreeSSO\Model\User::getById($groupUser->getUserId(), null);
                    try {
                        $smsService = $this::getDIService('FreeFW.Sms::Sms');
                        $account    = $groupUser->getAccount(\FreeSSO\Model\UserAccount::TYPE_SMS);
                        if ($account !== false) {
                            $config = self::getDIConfig();
                            $grpCfg = json_decode($group->getGrpCnx(), true);
                            self::debug(print_r($request->getAttribute('msg_dest'), true));
                            self::debug(print_r($request->getAttribute('msg_text'), true));
                            if (array_key_exists('sms_provider', $grpCfg) && $grpCfg['sms_provider'] != '') {
                                $datas             = array_merge($config->get('sms'), $grpCfg);
                                $datas['provider'] = $grpCfg['sms_provider'];
                            } else {
                                $datas = array_merge($config->get('sms'), $grpCfg, $account->__toArray());
                            }
                            $sms = $smsService->sendSms(
                                $datas,
                                $request->getAttribute('msg_dest'),
                                $request->getAttribute('msg_text')
                            );
                            return $this->createCustomResponse(200, [], $sms);
                        } else {
                            $errors->addError(0, sprintf('User %s has no Sms account !', $usrKey));
                        }
                    } catch (\Exception $ex) {
                        return $this->createCustomResponse(
                            500,
                            [],
                            null,
                            $ex->getMessage()
                        );
                    }
                } else {
                    self::debug(sprintf('User %s for group %s not found !', $usrKey, $grpVerifKey));
                    return $this->createCustomResponse(
                        400,
                        [],
                        null,
                        sprintf('User %s for group %s not found !', $usrKey, $grpVerifKey)
                    );
                }
            }
            self::debug(sprintf('Group %s not found !', $grpVerifKey));
            return $this->createCustomResponse(400, [], null, sprintf('Group %s not found !', $grpVerifKey));
        }
        self::debug(print_r($errors, true));
        return $this->createCustomResponse(400, [], $errors);
    }
}
