<?php
/**
 * Classe de controle de la partie broker
 *
 * @author jeromeklam
 * @package SSO\Controller
 */
namespace FreeSSO\Controller\Micro\V1;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use FreeSSO\ErrorCodes;

/**
 * Classe controlleur SSO
 * @author jeromeklam
 */
class Broker extends \FreeFW\Controller\Base
{

    /**
     * Retourne les utilisateurs d'un broker
     *
     * @param ServerRequestInterface $p_request
     * @param string                 $grpVerifKey
     *
     * @return ResponseInterface
     */
    public function getUsers($p_request)
    {
        $sso      = self::getDIShared('sso');
        $brokerId = $sso->getBrokerId();
        if ($brokerId > 0) {
            $query = \FreeFW\Model\IterableQuery::getInstance(
                'FreeFW.Sso::User',
                \FreeFW\Model\IterableQuery::SELECT_DISTINCT,
                ['FreeFW.Sso::User', 'FreeFW.Sso::LinkUser']
            );
            $query
                ->joinModel('FreeFW.Sso::User.user_id', 'FreeFW.Sso::LinkUser.user_id')
                ->fieldEqual('FreeFW.Sso::LinkUser.brk_id', $brokerId)
            ;
            $result = new \FreeFW\Model\ResultSet();
            while (($line = $query->getRow()) !== false) {
                $oneUser     = \FreeSSO\Model\User::getInstance($line);
                $oneLinkUser = \FreeSSO\Model\LinkUser::getInstance($line);
                $oneUser->setUserKey($oneLinkUser->getLkuPartnerId());
                $result[] = $oneUser;
            }
            return $this->createCustomResponse(200, [], $result);
        }
        return $this->createCustomResponse(200);
    }

    /**
     * Vérifie le broker
     *
     * @param ServerRequestInterface $p_request
     *
     * @return ResponseInterface
     */
    public function verify($p_request)
    {
        $sso      = self::getDIShared('sso');
        $brokerId = $sso->getBrokerId();
        if ($brokerId > 0) {
            return $this->createCustomResponse(200, []);
        }
        return $this->createCustomResponse(401, []);
    }

    /**
     * Retourne un utilisateur selon sa clef
     *
     * @param ServerRequestInterface $p_request
     *
     * @return ResponseInterface
     */
    public function getUserByKey($p_request)
    {
        $sso      = self::getDIShared('sso');
        $brokerId = $sso->getBrokerId();
        if ($brokerId > 0) {
            $key   = $p_request->getAttribute('key');
            $query = \FreeFW\Model\IterableQuery::getInstance(
                'FreeFW.Sso::User',
                \FreeFW\Model\IterableQuery::SELECT_DISTINCT,
                ['FreeFW.Sso::User', 'FreeFW.Sso::LinkUser']
            );
            $query
                ->joinModel('FreeFW.Sso::User.user_id', 'FreeFW.Sso::LinkUser.user_id')
                ->fieldEqual('FreeFW.Sso::LinkUser.brk_id', $brokerId)
                ->fieldEqual('FreeFW.Sso::LinkUser.lku_partner_id', $key)
            ;
            $result = false;
            while (($line = $query->getRow()) !== false) {
                $result      = new \FreeFW\Model\ResultSet();
                $oneUser     = \FreeSSO\Model\User::getInstance($line);
                $oneLinkUser = \FreeSSO\Model\LinkUser::getInstance($line);
                $oneUser->setUserKey($oneLinkUser->getLkuPartnerId());
                $result = $oneUser;
                break;
            }
            if ($result) {
                return $this->createCustomResponse(200, [], $result);
            } else {
                return $this->createCustomResponse(404, []);
            }
        }
        return $this->createCustomResponse(401, []);
    }

    /**
     * Retourne un utilisateur selon son identifiant
     *
     * @param ServerRequestInterface $p_request
     *
     * @return ResponseInterface
     */
    public function getUserById($p_request)
    {
        $sso      = self::getDIShared('sso');
        $brokerId = $sso->getBrokerId();
        if ($brokerId > 0) {
            $id    = $p_request->getAttribute('id');
            $query = \FreeFW\Model\IterableQuery::getInstance(
                'FreeFW.Sso::User',
                \FreeFW\Model\IterableQuery::SELECT_DISTINCT,
                ['FreeFW.Sso::User']
            );
            $query
                ->joinModel('FreeFW.Sso::User.user_id', 'FreeFW.Sso::LinkUser.user_id')
                ->fieldEqual('FreeFW.Sso::LinkUser.brk_id', $brokerId)
                ->fieldEqual('FreeFW.Sso::LinkUser.user_id', $id)
            ;
            $result = false;
            while (($line = $query->getRow()) !== false) {
                $result      = new \FreeFW\Model\ResultSet();
                $oneUser     = \FreeSSO\Model\User::getInstance($line);
                $oneLinkUser = \FreeSSO\Model\LinkUser::getInstance($line);
                $oneUser->setUserKey($oneLinkUser->getLkuPartnerId());
                $result = $oneUser;
                break;
            }
            if ($result) {
                return $this->createCustomResponse(200, [], $result);
            } else {
                return $this->createCustomResponse(404, []);
            }
        }
        return $this->createCustomResponse(401, []);
    }

    /**
     * Supprime un utilisateur selon son identifiant
     *
     * @param ServerRequestInterface $p_request
     *
     * @return ResponseInterface
     */
    public function deleteUser($p_request)
    {
        $sso      = self::getDIShared('sso');
        $brokerId = $sso->getBrokerId();
        $params   = new \FreeFW\Model\ApiParams();
        if ($p_request->getAttribute('api_params', false) !== false) {
            $params = $p_request->api_params;
        }
        if ($brokerId > 0) {
            $id    = $p_request->getAttribute('id');
            $query = \FreeFW\Model\IterableQuery::getInstance(
                'FreeFW.Sso::User',
                \FreeFW\Model\IterableQuery::SELECT_DISTINCT,
                ['FreeFW.Sso::User']
            );
            $query
                ->joinModel('FreeFW.Sso::User.user_id', 'FreeFW.Sso::LinkUser.user_id')
                ->fieldEqual('FreeFW.Sso::LinkUser.brk_id', $brokerId)
                ->fieldEqual('FreeFW.Sso::LinkUser.user_id', $id)
            ;
            $result = true;
            while (($line = $query->getRow()) !== false) {
                $oneUser     = \FreeSSO\Model\User::getInstance($line);
                $oneLinkUser = \FreeSSO\Model\LinkUser::getInstance($line);
                if (!$oneLinkUser->remove() || !$oneUser->remove()) {
                    $result = false;
                }
                break;
            }
            if ($result) {
                return $this->createCustomResponse(204, []);
            } else {
                return $this->createCustomResponse(409, []);
            }
        }
        return $this->createCustomResponse(401, []);
    }

    /**
     * Modifie un utilisateur selon son identifiant
     *
     * @param ServerRequestInterface $p_request
     *
     * @return ResponseInterface
     */
    public function updateUser($p_request)
    {
        $sso      = self::getDIShared('sso');
        $brokerId = $sso->getBrokerId();
        $params   = new \FreeFW\Model\ApiParams();
        if ($p_request->getAttribute('api_params', false) !== false) {
            $params = $p_request->api_params;
        }
        if ($brokerId > 0) {
            $id    = $p_request->getAttribute('id');
            $query = \FreeFW\Model\IterableQuery::getInstance(
                'FreeFW.Sso::User',
                \FreeFW\Model\IterableQuery::SELECT_DISTINCT,
                ['FreeFW.Sso::User']
            );
            $query
                ->joinModel('FreeFW.Sso::User.user_id', 'FreeFW.Sso::LinkUser.user_id')
                ->fieldEqual('FreeFW.Sso::LinkUser.brk_id', $brokerId)
                ->fieldEqual('FreeFW.Sso::LinkUser.user_id', $id)
            ;
            $result = false;
            while (($line = $query->getRow()) !== false) {
                $oneUser     = \FreeSSO\Model\User::getInstance($line);
                $oneLinkUser = \FreeSSO\Model\LinkUser::getInstance($line);
                $oneUser->setUserKey($oneLinkUser->getLkuPartnerId());
                $user = $params->buildModel('FreeFW\\Sso\\Model\\User', $id, $oneUser);
                if ($user->save()) {
                    $result = $user;
                }
                break;
            }
            if ($result) {
                return $this->createCustomResponse(200, [], $result);
            } else {
                return $this->createCustomResponse(409, []);
            }
        }
        return $this->createCustomResponse(401, []);
    }

    /**
     * Créé un utilisateur
     *
     * @param ServerRequestInterface $p_request
     *
     * @return ResponseInterface
     */
    public function createUser($p_request)
    {
        $sso       = self::getDIShared('sso');
        $brokerId  = $sso->getBrokerId();
        $brokerKey = $sso->getIdentifier();
        $params    = new \FreeFW\Model\ApiParams();
        $errors    = new \FreeFW\Http\Errors();
        if ($p_request->getAttribute('api_params', false) !== false) {
            $params = $p_request->api_params;
        }
        if ($brokerId > 0) {
            $result = false;
            $user   = $params->buildModel('FreeFW\\Sso\\Model\\User');
            // Il se peut ici qu'on soit passer par OMEGA-GRC, user sans login, email, ...
            if ($user->getUserLogin() == '') {
                // Par contre la clef est obligatoire...
                if ($user->getUserKey() == '') {
                    $errors->addError(ErrorCodes::ERROR_CREATION, 'La clef est obligatoire !');
                    return $this->createCustomResponse(409, [], $errors);
                }
                $user
                    ->setUserLogin($brokerKey . '#' . $user->getUserKey())
                    ->setUserActive(false)
                    ->createNewPassword(uniqid())
                ;
            }
            // Dans ce cas on génère tout.
            if ($user->create()) {
                $result = $user;
                // Link...
                $partId   = $user->getUserKey();
                $linkUser = new \FreeSSO\Model\LinkUser();
                $linkUser
                    ->setUserId($user->getUserId())
                    ->setBrkId($brokerId)
                    ->setLkuAuthMethod(\FreeSSO\Model\LinkUser::AUTH_METHOD_AUTO)
                    ->setLkuPartnerType('omega-util')
                    ->setLkuTs(\FreeFW\Tools\Date::getCurrentTimestamp())
                    ->setLkuPartnerId($partId)
                ;
                if (!$linkUser->create()) {
                    $errors->addError(ErrorCodes::ERROR_CREATION, 'Erreur de création du lien !');
                    return $this->createCustomResponse(409, [], $errors);
                }
            }
            if ($result) {
                return $this->createCustomResponse(201, [], $result);
            } else {
                return $this->createCustomResponse(409, []);
            }
        }
        return $this->createCustomResponse(401, []);
    }

    /**
     * Envoi d'un SMS en utilisant le compte de l'utilisateur
     *
     * @param ServerRequestInterface $p_request
     *
     * @return ResponseInterface
     */
    public function sendSmsForUserId($p_request)
    {
        $sso      = self::getDIShared('sso');
        $brokerId = $sso->getBrokerId();
        $errors   = new \FreeFW\Http\Errors();
        $params   = new \FreeFW\Model\ApiParams();
        if ($p_request->getAttribute('api_params', false) !== false) {
            $params = $p_request->api_params;
        }
        if ($brokerId > 0) {
            $id    = $p_request->getAttribute('id');
            $query = \FreeFW\Model\IterableQuery::getInstance(
                'FreeFW.Sso::User',
                \FreeFW\Model\IterableQuery::SELECT_DISTINCT,
                ['FreeFW.Sso::User']
            );
            $query
                ->joinModel('FreeFW.Sso::User.user_id', 'FreeFW.Sso::LinkUser.user_id')
                ->fieldEqual('FreeFW.Sso::LinkUser.brk_id', $brokerId)
                ->fieldEqual('FreeFW.Sso::LinkUser.user_id', $id)
            ;
            $result = false;
            while (($line = $query->getRow()) !== false) {
                $oneUser = \FreeSSO\Model\User::getInstance($line);
                $oneMsg  = $params->buildModel('FreeFW\\Sms\\Model\\Message');
                if (!$oneMsg->isValid()) {
                    var_export($oneMsg);
                    return $this->createCustomResponse(409, [], $oneMsg->getHttpErrors());
                }
                try {
                    $smsService = $this::getDIService('FreeFW.Sms::Sms');
                    $account    = $oneUser->getAccount(\FreeSSO\Model\UserAccount::TYPE_SMS);
                    if ($account !== false) {
                        $result = $smsService->sendSms(
                            $account->getProvider(),
                            $account->getKey1(),
                            $account->getKey2(),
                            $oneMsg->getMsgDest(),
                            $oneMsg->getMsgText()
                        );
                    } else {
                        $errors->addError(0, 'L\'utilisateur n\' pas de compte SMS !');
                    }
                } catch (\Exception $ex) {
                    $errors->addError($ex->getCode(), $ex->getMessage());
                }
                break;
            }
            if ($errors->hasErrors()) {
                return $this->createCustomResponse(409, [], $errors);
            }
            return $this->createCustomResponse(200, [], $result);
        } else {
            return $this->createCustomResponse(401, []);
        }
    }
}
