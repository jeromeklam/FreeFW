<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Model Alert
 *
 * @author jeromeklam
 */
class Alert extends \FreeFW\Model\Base\Alert
{

    /**
     * Comportements
     */
    use \FreeSSO\Model\Behaviour\User;
    use \FreeFW\Model\Behaviour\AlertCategory;

    /**
     * Constants
     * @var string
     */
    const PRIORITY_NONE        = 'NONE';
    const PRIORITY_TODO        = 'TODO';
    const PRIORITY_IMPORTANT   = 'IMPORTANT';
    const PRIORITY_CRITICAL    = 'CRITICAL';
    const PRIORITY_INFORMATION = 'INFORMATION';

    /**
     * Recurrence
     * @var string
     */
    const RECUR_TYPE_NONE   = 'NONE';
    const RECUR_TYPE_HOUR   = 'HOUR';
    const RECUR_TYPE_MINUTE = 'MINUTE';
    const RECUR_TYPE_DAY    = 'DAY';
    const RECUR_TYPE_MONTH  = 'MONTH';
    const RECUR_TYPE_YEAR   = 'YEAR';
    const RECUR_TYPE_MANUAL = 'MANUAL';

    /**
     * User
     * @var \FreeSSO\Model\User
     */
    protected $done_by = null;

    /**
     * AlertDoneUserId
     * @var int
     */
    protected $alert_done_user_id = null;

    /**
     * Parent
     * @var \FreeFW\Model\Alert
     */
    protected $parent = null;

    /**
     * AlertParentId
     * @var int
     */
    protected $alert_parent_id = null;

    /**
     * Object
     * @var mixed
     */
    protected $object = null;

    /**
     * Set todo
     *
     * @return \FreeFW\Model\Alert
     */
    public function setTodoAlert()
    {
        $this->setAlertPriority(self::PRIORITY_TODO);
        return $this;
    }

    /**
     * Set done by
     *
     * @param \FreeSSO\Model\User $p_done_by
     *
     * @return \FreeFW\Model\Alert
     */
    public function setDoneBy($p_done_by)
    {
        $this->done_by = $p_done_by;
        if ($this->done_by instanceof \FreeSSO\Model\User) {
            $this->setAlertDoneUserId($this->done_by->getUserId());
        } else {
            $this->setAlertDoneUserId(null);
        }
        return $this;
    }

    /**
     * Get done by
     *
     * @return \FreeSSO\Model\User
     */
    public function getDoneBy()
    {
        if ($this->done_by === null) {
            if ($this->alert_done_user_id > 0) {
                $this->done_by = \FreeSSO\Model\User::findFirst(['user_id' => $this->alert_done_user_id]);
            }
        }
        return $this->done_by;
    }

    /**
     * Set done by id
     *
     * @param int $p_id
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertDoneUserId($p_id)
    {
        $this->alert_done_user_id = $p_id;
        if ($this->done_by !== null) {
            if ($this->alert_done_user_id != $this->done_by->getUserId()) {
                $this->done_by = null;
            }
        }
        return $this;
    }

    /**
     * Get done by id
     *
     * @return int
     */
    public function getAlertDoneUserId()
    {
        return $this->alert_done_user_id;
    }

    /**
     * Set parent
     *
     * @param \FreeFW\Model\Alert $p_parent
     *
     * @return \FreeFW\Model\Alert
     */
    public function setParent($p_parent)
    {
        $this->parent = $p_parent;
        if ($this->parent instanceof \FreeFW\Model\Alert) {
            $this->setAlertParentId($this->parent->getAlertId());
        } else {
            $this->setAlertParentId(null);
        }
        return $this;
    }

    /**
     * Get parent
     *
     * @return \FreeFW\Model\Alert
     */
    public function getParent()
    {
        if ($this->parent === null) {
            if ($this->alert_parent_id > 0) {
                $this->parent = \FreeFW\Model\Alert::findFirst(['alert_id' => $this->alert_parent_id]);
            }
        }
        return $this->parent;
    }

    /**
     * Set parent id
     *
     * @param int $p_id
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertParentId($p_id)
    {
        $this->alert_parent_id = $p_id;
        if ($this->parent !== null) {
            if ($this->alert_parent_id != $this->parent->getAlertId()) {
                $this->parent = null;
            }
        }
        return $this;
    }

    /**
     * Get parent id
     *
     * @return int
     */
    public function getAlertParentId()
    {
        return $this->alert_parent_id;
    }

    /**
     * Set Object
     *
     * @param \FreeFw\Core\Model $p_model
     *
     * @return \FreeFw\Core\Model
     */
    public function setObject($p_model)
    {
        /**
         * @var \FreeFw\Core\Model $p_model
         */
        if ($p_model instanceof \FreeFw\Core\Model) {
            $this->setAlertObjectId($p_model->getApiId());
        } else {
            $this
                ->setAlertObjectName('')
                ->setAlertObjectId(null)
            ;
        }
        return $this;
    }

    /**
     * Get object
     *
     * @return NULL
     */
    public function getObject()
    {
        if ($this->object === null) {
            $name  = $this->getAlertObjectName();
            if ($name != '') {
                $parts = explode('_', $name);
                if (count($parts) == 2) {
                    $class = '\\' . $parts[0] . '\\Model\\' . $parts[1];
                    if (class_exists($class)) {
                        $cls = new $class();
                        $this->object = $cls::findFirst(['id' => $this->getAlertObjectId()]);
                    }
                }
            }
        }
        if ($this->object === false) {
            return null;
        }
        return $this->object;
    }

    /**
     * Add to queue, websocket, ?
     *
     * @return boolean
     */
    public function forwardStorageEvent()
    {
        return true;
    }

    /**
     * Generate next event ??
     *
     * @return boolean
     */
    public function nextAlert()
    {
        if ($this->getAlertParentId() === null || $this->getAlertParentId() <= 0) {
            $done = $this->getAlertDoneTs();
            if ($done && $this->getAlertRecurType() !== self::RECUR_TYPE_NONE) {
                /**
                 * @var \FreeFW\Model\Alert $newAlert
                 */
                $newAlert = $this->clone();
                $newAlert
                    ->setAlertParentId(null)
                    ->setAlertDoneText(null)
                    ->setAlertDoneTs(null)
                    ->setAlertDoneAction(null)
                    ->setAlertActiv(true)
                    ->setAlertDoneUserId(null)
                ;
                $newFrom  = \FreeFW\Tools\Date::mysqlToDatetime($this->getAlertFrom());
                $newTo    = \FreeFW\Tools\Date::mysqlToDatetime($this->getAlertTo());
                switch ($this->getAlertRecurType()) {
                    case self::RECUR_TYPE_DAY:
                        $interval = 'P' . $this->getAlertRecurNumber() . 'D';
                        break;
                    case self::RECUR_TYPE_MONTH:
                        $interval = 'P' . $this->getAlertRecurNumber() . 'M';
                        break;
                    case self::RECUR_TYPE_YEAR:
                        $interval = 'P' . $this->getAlertRecurNumber() . 'Y';
                        break;
                    case self::RECUR_TYPE_HOUR:
                        $interval = 'PT' . $this->getAlertRecurNumber() . 'H';
                        break;
                    case self::RECUR_TYPE_MINUTE:
                        $interval = 'PT' . $this->getAlertRecurNumber() . 'M';
                        break;
                    default:
                        $interval = 'P1D';
                        break;
                }
                $newFrom->add(new \DateInterval($interval));
                $newTo->add(new \DateInterval($interval));
                $newAlert
                    ->setAlertFrom(\FreeFW\Tools\Date::datetimeToMysql($newFrom))
                    ->setAlertTo(\FreeFW\Tools\Date::datetimeToMysql($newTo))
                ;
                if (!$newAlert->create()) {
                    return false;
                }
                $this
                    ->setAlertParentId($newAlert->getAlertId())
                    ->setParent($newAlert)
                ;
            }
        }
        return true;
    }

    /**
     * Après la création
     *
     * @return boolean
     */
    public function beforeCreate()
    {
        return $this->nextAlert();
    }

    /**
     * Après la modification
     *
     * @return boolean
     */
    public function beforeSave()
    {
        return $this->nextAlert();
    }
}
