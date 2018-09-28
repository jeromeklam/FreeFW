<?php
/**
 * Classe de base des controlleurs
 *
 * @author jeromeklam
 * @package Controller
 * @category Base
 */
namespace FreeFW\Controller;

/**
 * Controller Base
 * @author jeromeklam
 */
class Base
{

    /**
     * Comportements
     */
    use \FreeFW\Behaviour\DI;
    use \FreeFW\Behaviour\LoggerAwareTrait;
    use \FreeFW\Behaviour\EventManager;
    use \FreeFW\Behaviour\Translation;

    /**
     * Retourne une session en essayant de forcer l'identifiant de session
     *
     * @param unknown $p_sid
     *
     * @return \FreeFW\Interfaces\Session
     */
    protected function getSession($p_sid = null)
    {
        $session = self::getDIShared('session');
        if ($session === false) {
            $session = new \FreeFW\Session\File(false);
            $session->start($p_sid);
            self::setDIShared('session', $session);
        }
        return $session;
    }

    /**
     * Ajout d'un message flash de succès
     *
     * @param string $p_message
     *
     * @return \AtmTools\Controller\Base
     */
    public function addFlashSuccess($p_message)
    {
        $bag = self::getDIShared('flashbag');
        if ($bag instanceof \FreeFW\Message\FlashBag) {
            $bag->add(\FreeFW\Message\Flash::getSuccessMessage($this->_($p_message)));
        }
        return $this;
    }

    /**
     * Ajout d'un message flash d'erreur
     *
     * @param string $p_message
     *
     * @return \AtmTools\Controller\Base
     */
    public function addFlashDanger($p_message)
    {
        $bag = self::getDIShared('flashbag');
        if ($bag instanceof \FreeFW\Message\FlashBag) {
            $bag->add(\FreeFW\Message\Flash::getDangerMessage($this->_($p_message)));
        }
        return $this;
    }

    /**
     * Ajout d'un message flash de warning
     *
     * @param string $p_message
     *
     * @return \AtmTools\Controller\Base
     */
    public function addFlashWarning($p_message)
    {
        $bag = self::getDIShared('flashbag');
        if ($bag instanceof \FreeFW\Message\FlashBag) {
            $bag->add(\FreeFW\Message\Flash::getWarningMessage($this->_($p_message)));
        }
        return $this;
    }

    /**
     * Retourne l'utilisateur connecté
     *
     * @todo : implements interface...
     *
     * @return \FreeFW\Sso\Model\User|false
     */
    protected function getCurrentUser()
    {
        $ssoServer = \FreeFW\Sso\Server::getInstance();
        try {
            $user = $ssoServer->getUser();
        } catch (\Exception $ex) {
            $user = false;
        }
        return $user;
    }

    /**
     * Retourne une grille satndard
     *
     * @param \FreeFW\Html\Grid $p_grid
     *
     * @return \FreeFW\Interfaces\Response
     */
    protected function getStandardHtml5Grid($p_grid)
    {
        $response = $this->getResponse();
        $response->setTemplate('@layouts/standardGrid.html');
        $response->setContent(array(
            'grid'        => $p_grid->get(),
            'constColumn' => $p_grid::PARAM_COLUMN,
            'constSearch' => $p_grid::PARAM_SEARCH
        ));
        return $response;
    }

    /**
     * Retourne un formulaire de création
     *
     * @param string $p_model
     * @param string $p_form
     *
     * @return \FreeFW\Interfaces\Response
     */
    protected function getStandardHtml5AddForm($p_model, $p_form)
    {
        $response = $this->getResponse();
        $model    = new $p_model();
        $form     = new $p_form(array(
            'mode' => \FreeFW\Html\Form::MODE_CREATE
        ));
        $form->bindWithModel($model);
        // Suite
        $response->setTemplate('@layouts/standardForm.html');
        $response->setContent(array(
            'mode' => 'create',
            'form' => $form
        ));
        return $response;
    }

    /**
     * Retourne un formulaire de modification
     *
     * @param string $p_model
     * @param string $p_form
     * @param mixed  $p_id
     *
     * @return \FreeFW\Interfaces\Response
     */
    protected function getStandardHtml5EditForm($p_model, $p_form, $p_id)
    {
        $response = $this->getResponse();
        $model    = $p_model::getById($p_id);
        if ($model!== false) {
            $form = new $p_form(array(
                'mode' => \FreeFW\Html\Form::MODE_UPDATE
            ));
            $form->bindWithModel($model);
            // Suite
            $response->setTemplate('@layouts/standardForm.html');
            $response->setContent(array(
                'mode' => 'update',
                'form' => $form
            ));
        } else {
            $response->withStatus(
                404,
                sprintf('L\'enregistrement avec l\'identifiant %s n\'a pas été trouvé !', $p_id)
            );
        }
        return $response;
    }

    /**
     * Sauvegarde générique d'un modèle simple
     *
     * @param string $p_model
     * @param string $p_addform
     * @param string $p_editform
     * @param mixed  $p_id
     *
     * @return \FreeFW\Interfaces\Response
     */
    protected function saveStandardHtml5Model($p_model, $p_addform, $p_editform, $p_id)
    {
        $response = $this->getResponse();
        $request  = self::getDIRequest();
        $config   = self::getDIConfig();
        if ($request->hasAttribute('submit')) {
            if ($request->getMethod() == \FreeFW\Http\Request::METHOD_POST) {
                if ($p_id !== null && $p_id > 0) {
                    $model = $p_model::getById($p_id);
                    $form = new $p_editform(array(
                        'mode' => \FreeFW\Html\Form::MODE_UPDATE
                    ));
                    $model->bindFromRequest($request);
                    $form->bindWithModel($model);
                    if ($form->isValide() && $model->isValide()) {
                        if ($model->save()) {
                            $this->addFlashSuccess('message.success.update');
                            $response->redirectReferer();
                            return $response;
                        } else {
                            $this->addFlashDanger($model->getPdoError());
                        }
                    }
                    $response->setTemplate('@layouts/standardForm.html');
                    $response->setContent(array(
                        'mode' => 'update',
                        'form' => $form->bindWithModel($model)
                    ));
                    $this->addFlashDanger('message.error.update');
                } else {
                    $form = new $p_addform(array(
                        'mode' => \FreeFW\Html\Form::MODE_CREATE
                    ));
                    $model = new $p_model();
                    $model->bindFromRequest($request);
                    $form->bindWithModel($model);
                    if ($form->isValide() && $model->isValide()) {
                        if ($model->create()) {
                            $this->addFlashSuccess('message.success.create');
                            $response->redirectReferer();
                            return $response;
                        } else {
                            $this->addFlashDanger($model->getPdoError());
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
     * Suppression générique d'un modèle simple
     *
     * @param string $p_model
     * @param mixed  $p_id
     *
     * @return \FreeFW\Interfaces\Response
     */
    protected function deleteStandardHtml5Model($p_model, $p_id)
    {
        $response = $this->getResponse();
        $model    = $p_model::getById($p_id);
        if ($model) {
            if ($model->remove()) {
                $this->addFlashSuccess('message.success.delete');
            } else {
                $this->addFlashWarning('message.error.delete');
            }
        } else {
            $this->addFlashWarning('message.error.delete');
        }
        $response->redirectReferer();
        return $response;
    }

    /**
     * Découpe la requeêtes en filtres, tri, ...
     *
     * @param ServerRequestInterface $p_request
     *
     * @return array[]
     */
    public function splitRequestForFind($p_request)
    {
        $filters  = [];
        $sort     = [];
        $from     = false;
        $len      = false;
        $included = '';
        $fields   = '';
        // parcours et classement des attributs
        $attributes = $p_request->getAttributes();
        $attributes = array_merge($attributes, $p_request->getQueryParams());
        foreach ($attributes as $key => $value) {
            switch ($key) {
                case 'depuis':
                case 'from' :
                    $from = intval($value);
                    break;
                case 'longueur':
                case 'len' :
                    $len = intval($value);
                    break;
                case 'query_id':
                    $filters['query_id'] = $value;
                    break;
                case 'included':
                    $included = $value;
                    break;
                case 'fields':
                    $fields = $value;
                    break;
                default:
                    if (strpos($key, 'sort_by_') === 0) {
                        $sort[str_replace('sort_by_', '', $key)] = strtoupper($value);
                    } else {
                        $filters[$key] = $value;
                    }
                    break;
            }
        }
        return [
            'filters'  => $filters,
            'from'     => $from,
            'len'      => $len,
            'sort'     => $sort,
            'included' => $included,
            'fields'   => $fields
        ];
    }

    /**
     * Get value from cache
     *
     * @param string $p_key
     *
     * @return mixed
     */
    protected function getFromCache($p_key)
    {
        $cache = $this->getDIShared('cache');
        if ($cache) {
            if ($cache->hasItem($p_key)) {
                $cacheItem = $cache->getItem($p_key);
                return $cacheItem->get();
            }
        }
        return false;
    }

    /**
     * Set in cache
     *
     * @param string $p_key
     * @param mixed  $p_value
     *
     * @return \FreeFW\Controller\Base
     */
    protected function setInCache($p_key, $p_value)
    {
        $cache = $this->getDIShared('cache');
        if ($cache) {
            $cacheItem = new \FreeFW\Cache\Item($p_key);
            $cacheItem
                ->set($p_value)
                ->expiresAfter(3600)
            ;
            $cache->save($cacheItem);
        }
        return $this;
    }
}
