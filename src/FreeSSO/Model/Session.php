<?php
/**
 * ... ...
 * @author jeromeklam
 */
namespace FreeSSO\Model;

/**
 * Classe : Session
 * @author : jeromeklam
 */
class Session extends \FreeFW\Model\AbstractPDOStorage
{

    /**
     * sess_id
     * @var Bigint
     */
    protected $sess_id = null;
    const DESC_SESS_ID = array(
        'name'   => 'sess_id',
        'column' => 'sess_id',
        'field'  => 'sess_id',
        'type'   => \FreeFW\Constants::TYPE_BIGINT,
        'camel'  => 'SessId',
        'snake'  => 'sess_id',
        'getter' => 'getSessId',
        'setter' => 'setSessId',
        'search' => false,
        'key'    => true,
        'uniq'   => true
    );
    /**
     * user_id
     * @var Bigint
     */
    protected $user_id = null;
    const DESC_USER_ID = array(
        'name'   => 'user_id',
        'column' => 'user_id',
        'field'  => 'user_id',
        'type'   => \FreeFW\Constants::TYPE_BIGINT,
        'camel'  => 'UserId',
        'snake'  => 'user_id',
        'getter' => 'getUserId',
        'setter' => 'setUserId',
        'search' => false
    );
    /**
     * sess_start
     * @var Timestamp
     */
    protected $sess_start = null;
    const DESC_SESS_START = array(
        'name'   => 'sess_start',
        'column' => 'sess_start',
        'field'  => 'sess_start',
        'type'   => \FreeFW\Constants::TYPE_DATETIME,
        'camel'  => 'SessStart',
        'snake'  => 'sess_start',
        'getter' => 'getSessStart',
        'setter' => 'setSessStart',
        'search' => false
    );
    /**
     * sess_end
     * @var Timestamp
     */
    protected $sess_end = null;
    const DESC_SESS_END = array(
        'name'   => 'sess_end',
        'column' => 'sess_end',
        'field'  => 'sess_end',
        'type'   => \FreeFW\Constants::TYPE_DATETIME,
        'camel'  => 'SessEnd',
        'snake'  => 'sess_end',
        'getter' => 'getSessEnd',
        'setter' => 'setSessEnd',
        'search' => false
    );
    /**
     * sess_touch
     * @var Timestamp
     */
    protected $sess_touch = null;
    const DESC_SESS_TOUCH = array(
        'name'   => 'sess_touch',
        'column' => 'sess_touch',
        'field'  => 'sess_touch',
        'type'   => \FreeFW\Constants::TYPE_DATETIME,
        'camel'  => 'SessTouch',
        'snake'  => 'sess_touch',
        'getter' => 'getSessTouch',
        'setter' => 'setSessTouch',
        'search' => false
    );
    /**
     * sess_content
     * @var String
     */
    protected $sess_content = null;
    const DESC_SESS_CONTENT = array(
        'name'   => 'sess_content',
        'column' => 'sess_content',
        'field'  => 'sess_content',
        'type'   => \FreeFW\Constants::TYPE_TEXT,
        'camel'  => 'SessContent',
        'snake'  => 'sess_content',
        'getter' => 'getSessContent',
        'setter' => 'setSessContent',
        'search' => false
    );
    /**
     * sess_client_addr
     * @var String
     */
    protected $sess_client_addr = null;
    const DESC_SESS_CLIENT_ADDR = array(
        'name'   => 'sess_client_addr',
        'column' => 'sess_client_addr',
        'field'  => 'sess_client_addr',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'SessClientAddr',
        'snake'  => 'sess_client_addr',
        'getter' => 'getSessClientAddr',
        'setter' => 'setSessClientAddr',
        'search' => true
    );
    /**
     * Source
     * @var String
     */
    protected static $source = 'FreeFW_sessions';

    /**
     * Retourne la source
     *
     * @return string
     */
    public static function getSource()
    {
        return self::$source;
    }

    /**
     * Retourne le descriptif des colonnes par nom en db
     *
     * @return array
     */
    public static function getColumnDescByName()
    {
        return array(
            'sess_id'          => self::DESC_SESS_ID,
            'user_id'          => self::DESC_USER_ID,
            'sess_start'       => self::DESC_SESS_START,
            'sess_end'         => self::DESC_SESS_END,
            'sess_touch'       => self::DESC_SESS_TOUCH,
            'sess_content'     => self::DESC_SESS_CONTENT,
            'sess_client_addr' => self::DESC_SESS_CLIENT_ADDR
        );
    }

    /**
     * Retourne le descriptif des colonnes par nom de propriété
     *
     * @return array
     */
    public static function getColumnDescByField()
    {
        return array(
            'sess_id'          => self::DESC_SESS_ID,
            'user_id'          => self::DESC_USER_ID,
            'sess_start'       => self::DESC_SESS_START,
            'sess_end'         => self::DESC_SESS_END,
            'sess_touch'       => self::DESC_SESS_TOUCH,
            'sess_content'     => self::DESC_SESS_CONTENT,
            'sess_client_addr' => self::DESC_SESS_CLIENT_ADDR
        );
    }

    /**
     * Setter sess_id
     *
     * @param bigint $p_value
     *
     * @return \FreeSSO\Model\Session
     */
    public function setSessId($p_value)
    {
        $this->sess_id = $p_value;
        return $this;
    }

    /**
     * Getter sess_id
     *
     * @return bigint
     */
    public function getSessId()
    {
        return $this->sess_id;
    }

    /**
     * Setter user_id
     *
     * @param bigint $p_value
     *
     * @return \FreeSSO\Model\Session
     */
    public function setUserId($p_value)
    {
        $this->user_id = $p_value;
        return $this;
    }

    /**
     * Getter user_id
     *
     * @return bigint
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Setter sess_start
     *
     * @param timestamp $p_value
     *
     * @return \FreeSSO\Model\Session
     */
    public function setSessStart($p_value)
    {
        $this->sess_start = $p_value;
        return $this;
    }

    /**
     * Getter sess_start
     *
     * @return timestamp
     */
    public function getSessStart()
    {
        return $this->sess_start;
    }

    /**
     * Setter sess_end
     *
     * @param timestamp $p_value
     *
     * @return \FreeSSO\Model\Session
     */
    public function setSessEnd($p_value)
    {
        $this->sess_end = $p_value;
        return $this;
    }

    /**
     * Getter sess_end
     *
     * @return timestamp
     */
    public function getSessEnd()
    {
        return $this->sess_end;
    }

    /**
     * Setter sess_touch
     *
     * @param timestamp $p_value
     *
     * @return \FreeSSO\Model\Session
     */
    public function setSessTouch($p_value)
    {
        $this->sess_touch = $p_value;
        return $this;
    }

    /**
     * Getter sess_touch
     *
     * @return timestamp
     */
    public function getSessTouch()
    {
        return $this->sess_touch;
    }

    /**
     * Setter sess_content
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Session
     */
    public function setSessContent($p_value)
    {
        $this->sess_content = $p_value;
        return $this;
    }

    /**
     * Getter sess_content
     *
     * @return string
     */
    public function getSessContent()
    {
        return $this->sess_content;
    }

    /**
     * Setter sess_client_addr
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\Session
     */
    public function setSessClientAddr($p_value)
    {
        $this->sess_client_addr = $p_value;
        return $this;
    }

    /**
     * Getter sess_client_addr
     *
     * @return string
     */
    public function getSessClientAddr()
    {
        return $this->sess_client_addr;
    }

    /**
     * Retourne la liste des colonnes
     *
     * @return array
     */
    public static function columnMap()
    {
        return array(
            'sess_id'          => 'sess_id',
            'user_id'          => 'user_id',
            'sess_start'       => 'sess_start',
            'sess_end'         => 'sess_end',
            'sess_touch'       => 'sess_touch',
            'sess_content'     => 'sess_content',
            'sess_client_addr' => 'sess_client_addr'
        );
    }

    /**
     * Retourne la liste des colonnes identifiantes
     *
     * @return array      // Tableau de propertyName
     */
    public static function columnId()
    {
        return array(
            'sess_id'
        );
    }

    /**
     * Retourne les colonnes disponibles en fulltext
     *
     * @return array
     */
    public static function columnFulltext()
    {
        return array(
            'sess_client_addr'
        );
    }
}
