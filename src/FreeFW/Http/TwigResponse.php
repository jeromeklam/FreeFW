<?php
/**
 * Réponse au format twig
 *
 * @author jeromeklam
 * @package Response
 * @category Twig
 */
namespace FreeFW\Http;

/**
 * Response au format excel
 *
 * @author jeromeklam
 * @package Templater
 * @package Web\Response
 */
class TwigResponse extends \FreeFW\Http\HtmlResponse
{

    /**
     * Génération de la réponse
     *
     * @return void;
     */
    public function render()
    {
        if ($this->getStatusCode() >= 300 && $this->getStatusCode() < 400) {
            if ($this->routeName !== 'referer') {
                $router = \FreeFW\Http\Router::getInstance();
                $home   = $router->getRouteByName($this->routeName);
                $url    = '/';
                if ($home !== false) {
                    $url = $home->renderHref($this->routeParams);
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
            if ($this->routeName == 'referer') {
                $request = $this->getDIRequest();
                if ($request->hasAttribute('HTTP_REFERER')) {
                    $url = $request->getAttribute('HTTP_REFERER');
                } else {
                    $url = $request->getReferer();
                }
                header('location: ' . $url);
                exit(0);
            }
            $twig   = \FreeFW\Templater\Twig::getInstance();
            $params = $this->getContent();
            if (!is_array($params)) {
                $params = array();
            }
            $params['errors'] = $this->getErrors();
            $bag              = self::getDIShared('flashbag');
            $this->flushErrors();
            if ($bag instanceof \FreeFW\Message\FlashBag) {
                $params['flashbag'] = $bag;
            }
            echo $twig->render($this->getTemplate(), $params);
        }
    }
}
