<?php
/**
 * Client d'accès aux données de l'AS400
 *
 * @author jeromeklam
 * @package AS400
 */
namespace FreeFW\Client;

/**
 * Classe d'interface avec les PG RPG
 * @author jeromeklam
 */
class RPG
{

    /**
     * Comportements
     */
    use \FreeFW\Behaviour\LoggerAwareTrait;
    use \FreeFW\Behaviour\DI;

    /**
     * Retourne l'url associé aux paramètres
     *
     * @param array  $p_params
     * @param string $p_type
     *
     * @return string
     */
    protected function getUrl($p_params, $p_type = 'GET')
    {
        $config  = self::getDIConfig();
        $request = self::getDIRequest();
        $caller  = $config->get('caller');
        $server  = $config->get('server');
        $url     = false;
        if (is_array($p_params)) {
            if (array_key_exists('page', $p_params)) {
                if ($server != '') {
                    $url = $server . $caller;
                } else {
                    $url = $request->getHttp() . '://' . $request->getServerName() . $caller;
                }
            }
        }
        if ($url !== false && $p_type == 'GET') {
            $first = true;
            foreach ($p_params as $key => $value) {
                if ($first) {
                    $url .= '?';
                    $first = false;
                } else {
                    $url .= '&';
                }
                $url .= $key . '=' . $value;
            }
        }
        
        return $url;
    }

    /**
     * Get content
     *
     * @param array $p_params
     *
     * @return StdObject
     */
    public function getContent($p_params)
    {
        // Hack de session
        if (!array_key_exists('SID', $p_params)) {
            $p_params['SID'] = session_id();
        }
        $content = false;
        $url     = $this->getUrl($p_params, 'GET');
        $ch      = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $curl_response = curl_exec($ch);
        //finish off the session
        if ($curl_response !== false) {
            $content = json_decode($curl_response, true);
        }
        curl_close($ch);
        
        return $content;
    }
}
