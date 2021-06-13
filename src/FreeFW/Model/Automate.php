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

    /**
     * Behaviour
     */
    use \FreeSSO\Model\Behaviour\Group;
    use \FreeFW\Model\Behaviour\Email;

    /**
     * Run ?
     *
     * @param string $p_event
     *
     * @return boolean
     */
    public function runForEvent($p_event)
    {
        $events = explode(',', $this->getAutoEvents());
        if (in_array($p_event, $events)) {
            return true;
        }
        return false;
    }

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
                return call_user_func_array(
                    [$service, $method],
                    ['object' => $p_model, 'event' => $p_event_name, 'automate' => $this]
                );
            }
        }
        return false;
    }

    /**
     * Get params as array
     *
     * @return array|mixed
     */
    public function getParamsAsArray()
    {
        $params = json_decode($this->auto_params, true);
        if (!is_array($params)) {
            $params = [];
        }
        return $params;
    }
}
