<?php
/**
 * Classe de controle de la partie Utilisateur
 *
 * @author jeromeklam
 * @package User
 */
namespace FreeSSO\Controller\Html5;

use \FreeSSO\Model\User as UserModel;
use \FreeFW\Lock\Model\Lock as LockModel;
use \FreeSSO\Server as SsoServer;
use \FreeSSO\ErrorCodes as SsoErrors;

/**
 * Controlleur de gestion des utilisateurs
 * @author jeromeklam
 */
class User extends \FreeFW\Controller\Base
{

    /**
     * Retourne tous les utilisateurs
     *
     * @return \FreeFW\Interfaces\Response
     */
    public function login()
    {
        $response = $this->getResponse();
        $request  = self::getDIRequest();
        $params   = array();
        if ($request->hasAttribute('error_code')) {
            $params['error_code'] = 'sso.error.' . $request->getAttribute('error_code');
        }
        $response
            ->setTemplate('@FreeFW-sso/Auth/login.html')
            ->setContent($params)
        ;
        return $response;
    }

    /**
     * Retourne la liste des utilisateurs connectés (@todo du même groupe)
     *
     * @return\FreeFW\Interfaces\Response
     */
    public function getAllConnected()
    {
        $user     = $this->getCurrentUser();
        $response = $this->getResponse();
        if ($user !== false) {
            $query    = \FreeFW\Model\SimpleQuery::getInstance(
                'FreeFW.Sso::User',
                \FreeFW\Model\SimpleQuery::SELECT_DISTINCT
            );
            $query
                ->joinModel('FreeFW.Sso::User.id', 'FreeFW.Sso::Session.user_id')
                ->joinModel('FreeFW.Sso::BrokerSession.sess_id', 'FreeFW.Sso::Session.id')
                ->fieldNotEqual('FreeFW.Sso::User.id', $user->getId())
            ;
            $result = $query->getResult();
            $response->setContent($result);
        } else {
            $response->withStatus(401);
        }
        return $response;
    }

    /**
     * Sauvegarde de l'utilisateur courant
     *
     * @return \FreeFW\Http\FreeFWResponse
     */
    public function updateCurrent()
    {
        $response = new \FreeFW\Http\FreeFWResponse();
        //
        $ssoServer = SsoServer::getInstance();
        try {
            $user = $ssoServer->getUser();
            if ($user === false) {
                $response
                    ->withStatus(401)
                    ->setContent($ssoServer->getCookies())
                ;
            } else {
                $request           = $this->getDIRequest();
                $default_dashboard = $request->getAttribute('default_dashboard');
                $user
                    ->updateCacheKey('default_dashboard', $default_dashboard)
                    ->save()
                ;
                $response->setContent(array_merge($user->__toFields(), $ssoServer->getCookies()));
            }
        } catch (\Exception $ex) {
            $response
                ->withStatus(401)
                ->setContent($ssoServer->getCookies())
            ;
        }
        return $response;
    }

    /**
     * Retourne tous les utilisateurs
     *
     * @return \FreeFW\Interfaces\Response
     */
    public function getAll($p_param = array('sort'=>'user_email'))
    {
        $response = $this->getResponse();
        $grid     = new \FreeSSO\Html\User\Grid(array());
        $result   = $grid->get();
        if ($result !== false) {
            $response->setTemplate('@layouts/standardGrid.html');
            $response->setContent(array(
                'grid'        => $grid->get(),
                'constColumn' => $grid::PARAM_COLUMN,
                'constSearch' => $grid::PARAM_SEARCH
            ));
        } else {
            $response->redirect('user:all', true, $grid->getParams());
        }
        return $response;
    }

    /**
     * Retourne un utilisteur en fonction de son identifiant
     *
     * @param integer $p_id
     *
     * @return \FreeFW\Interfaces\Response
     */
    public function editOne($p_id)
    {
        return $this->getStandardHtml5EditForm(
            '\\FreeFW\\Sso\\Model\\User',
            '\\FreeFW\\Sso\\Html\\User\\EditForm',
            $p_id
        );
    }

    /**
     * Sauvegarde
     *
     * @param number $p_id
     *
     * @return \FreeFW\Interfaces\Response
     */
    public function saveOne($p_id)
    {
        $response = $this->getResponse();
        $request  = self::getDIRequest();
        $config   = self::getDIConfig();
        if ($request->hasAttribute('submit')) {
            if ($request->getMethod() == \FreeFW\Http\Request::METHOD_POST) {
                if ($p_id !== null && $p_id > 0) {
                    $model = \FreeSSO\Model\User::getById($p_id);
                    $form  = new \FreeSSO\Html\User\EditForm(array(
                        'mode' => \FreeFW\Html\Form::MODE_UPDATE
                    ));
                    $model->bindFromRequest($request);
                    $form->bindWithModel($model);
                    if ($form->isValide() && $model->isValide()) {
                        if ($model->save()) {
                            $this->addFlashSuccess('message.success.update');
                            $response->redirectReferer();
                            return $response;
                        }
                    }
                    $response->setTemplate('@layouts/standardForm.html');
                    $response->setContent(array(
                        'mode' => 'update',
                        'form' => $form->bindWithModel($model)
                    ));
                    $this->addFlashDanger('message.error.update');
                } else {
                    $form = new \FreeSSO\Html\User\AddForm(array(
                        'mode' => \FreeFW\Html\Form::MODE_CREATE
                    ));
                    $model = new \FreeSSO\Model\User();
                    $model->bindFromRequest($request);
                    // Login == email...
                    $model->setUserLogin($model->getUserEmail());
                    $form->bindWithModel($model);
                    if ($form->isValide() && $model->isValide()) {
                        try {
                            $ssoServer = self::getDIShared('sso');
                            $creation  = $ssoServer->registerNewUser(
                                $model->getUserLogin(),
                                $model->getUserEmail(),
                                $model->getUserPassword()
                            );
                            if ($creation) {
                                $this->addFlashSuccess('message.success.create');
                                $response->redirectReferer();
                                return $response;
                            }
                        } catch (\Exception $ex) {
                        }
                    }
                    $this->addFlashDanger('message.error.create');
                    $response->setTemplate('@layouts/standardForm.html');
                    $response->setContent(array(
                        'mode' => 'create',
                        'form' => $form->bindWithModel($model)
                    ));
                }
            } else {
                // @todo oups
            }
        } else {
            $response->redirectReferer();
        }
        return $response;
    }

    /**
     * Ajout d'un utilisateur
     *
     * @return \FreeFW\Interfaces\Response
     */
    public function addOne()
    {
        $response = $this->getResponse();
        $user     = new \FreeSSO\Model\User();
        $form     = new \FreeSSO\Html\User\AddForm();
        $form->bindWithModel($user);
        // Suite
        $response->setTemplate('@layouts/standardForm.html');
        $response->setContent(array(
            'mode' => 'create',
            'form' => $form
        ));
        return $response;
    }

    /**
     * Suppression d'un utilisateur
     *
     * @return \FreeFW\Interfaces\Response
     */
    public function delOne($id)
    {
        $response = $this->getResponse();
        $response->setContent(UserModel::delete(array('id'=>$id)));
        return $response;
    }
}
