<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Model Automate
 *
 * @author jeromeklam
 */
class Automate extends \FreeFW\Model\Base\Automate
{

    use \FreeSSO\Model\Behaviour\Group;

    /**
     * Run automate
     *
     * @param \FreeFW\Core\Model $p_model
     *
     * @return mixed|boolean
     */
    public function run($p_model, $p_event_name)
    {
        $service = \FreeFW\DI\DI::get($this->getAutoService());
        if ($service) {
            $method = $this->getAutoMethod();
            if (method_exists($service, $method)) {
                $params = json_decode($this->getAutoParams(), true);
                if (!is_array($params)) {
                    $params = [];
                }
                return call_user_func_array(
                    [$service, $method],
                    ['object' => $p_model, 'event' => $p_event_name, 'params' => $params]
                );
            }
        }
        return false;
    }
}
