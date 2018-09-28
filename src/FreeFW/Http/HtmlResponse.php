<?php
namespace FreeFW\Http;

/**
 * Response au format excel
 */
class HtmlResponse extends \FreeFW\Http\MimeResponse
{

    /**
     * Template
     *
     * @var string
     */
    protected $template = null;

    /**
     * Affectation du template
     *
     * @param string $p_name
     *
     * @return \FreeFW\Http\HtmlResponse
     */
    public function setTemplate($p_name)
    {
        $this->template = $p_name;

        return $this;
    }

    /**
     * Retourne le template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Retourne le contenu
     *
     * @return mixed
     */
    public function getContent()
    {
        $request = self::getDIRequest();
        $config  = self::getDIConfig();
        $temp    = $this->content;
        if ($temp === null || $temp === false) {
            $temp = array();
        }
        if (!array_key_exists('HTTP_REFERER', $temp)) {
            if ($request->getAttribute('HTTP_REFERER')) {
                $temp['HTTP_REFERER'] = $request->getAttribute('HTTP_REFERER');
            } else {
                $temp['HTTP_REFERER'] = $request->getReferer();
            }
        }
        $temp['basePath'] = $config->get('basePath');

        return $temp;
    }

    /**
     * Génération de la réponse
     *
     * @return void;
     */
    public function render()
    {
        $session = self::getDIShared('session');
        $session->set('redirect-content', $this->redirectcontent);
        if ($this->getStatusCode() > 300 && $this->getStatusCode() < 400) {
            if ($this->routeName !== 'referer') {
                $router = \FreeFW\Http\Router::getInstance();
                $home   = $router->getRouteByName('default');
                $url    = '/';
                $baseP  = $router->getBasePath();
                if ($baseP !== null && $baseP !== false) {
                    $url = $baseP . '/';
                }
                if ($home !== false) {
                    $url = $home->renderHref();
                }
            } else {
                $request = $this->getDIRequest();
                if ($request->hasAttribute('HTTP_REFERER')) {
                    $url = $request->getAttribute('HTTP_REFERER');
                } else {
                    $url = $request->getReferer();
                }
            }
            header('location: ' . $url);
            exit(0);
        } else {
            if ($this->getStatusCode() > 400 && $this->getStatusCode() < 500) {
                $router = \FreeFW\Http\Router::getInstance();
                if ($this->routeName !== false && $this->routeName !== '') {
                    $route  = $router->getRouteByName($this->routeName);
                    $url    = $route->renderHref();
                    header('location: ' . $url);
                    exit(0);
                } else {
                    $baseP  = $router->getBasePath();
                    if ($baseP !== null && $baseP !== false) {
                        $url = $baseP . '/';
                    } else {
                        $url = '/';
                    }
                    header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
                    exit(0);
                }
            } else {
                echo $this->content;
            }
        }
    }
}
