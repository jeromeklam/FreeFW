<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Model Inbox
 *
 * @author jeromeklam
 */
class Inbox extends \FreeFW\Model\Base\Inbox
{

    /**
     * Comportements
     */
    use \FreeSSO\Model\Behaviour\User;
    use \FreeSSO\Model\Behaviour\Group;

    /**
     * Prevent from saving history
     * @var bool
     */
    protected $no_history = true;

    /**
     * Temp file content
     * @var mixed
     */
    protected $content = null;

    /**
     * Before create
     * 
     * @return bool
     */
    public function beforeCreate()
    {
        $this->content = $this->getInboxContent();
        $this->setInboxContent(null);
        return true;
    }

    /**
     * Before save
     * 
     * @return bool
     */
    public function beforeSave()
    {
        $this->content = $this->getInboxContent();
        $this->setInboxContent(null);
        return true;
    }

    /**
     * After create
     * 
     * @return bool
     */
    public function afterCreate()
    {
        $config = \FreeFW\DI\Di::getShared('config');
        $dir = $config->get('ged:dir', '/tmp');
        $dir = rtrim($dir, '/') . '/inbox/';
        $file = $dir . $this->getInboxId() . '.data';
        return file_put_contents($file, $this->content);
    }

    /**
     * After save
     * 
     * @return bool
     */
    public function afterSave()
    {
        if ($this->content !== null) {
            $config = \FreeFW\DI\Di::getShared('config');
            $dir = $config->get('ged:dir', '/tmp');
            $dir = rtrim($dir, '/') . '/inbox/';
            $file = $dir . $this->getInboxId() . '.data';
            return file_put_contents($file, $this->content);
        } else {
            return true;
        }
    }
}
