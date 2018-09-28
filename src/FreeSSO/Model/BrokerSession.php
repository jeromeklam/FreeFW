<?php
/**
 * ... ...
 * @author jeromeklam
 */
namespace FreeSSO\Model;

/**
 * Classe : BrokerSession
 * @author : jeromeklam
 */
class BrokerSession extends \FreeFW\Model\AbstractPDOStorage
{

    /**
     * brs_id
     * @var Bigint
     */
    protected $brs_id = null;
    const DESC_BRS_ID = array(
        'name'   => 'brs_id',
        'column' => 'brs_id',
        'field'  => 'brs_id',
        'type'   => \FreeFW\Constants::TYPE_BIGINT,
        'camel'  => 'BrsId',
        'snake'  => 'brs_id',
        'getter' => 'getBrsId',
        'setter' => 'setBrsId',
        'search' => false,
        'key'    => true,
        'uniq'   => true
    );
    /**
     * brk_key
     * @var String
     */
    protected $brk_key = null;
    const DESC_BRK_KEY = array(
        'name'   => 'brk_key',
        'column' => 'brk_key',
        'field'  => 'brk_key',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'BrkKey',
        'snake'  => 'brk_key',
        'getter' => 'getBrkKey',
        'setter' => 'setBrkKey',
        'search' => true
    );
    /**
     * brs_token
     * @var String
     */
    protected $brs_token = null;
    const DESC_BRS_TOKEN = array(
        'name'   => 'brs_token',
        'column' => 'brs_token',
        'field'  => 'brs_token',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'BrsToken',
        'snake'  => 'brs_token',
        'getter' => 'getBrsToken',
        'setter' => 'setBrsToken',
        'search' => true
    );
    /**
     * brs_session_id
     * @var String
     */
    protected $brs_session_id = null;
    const DESC_BRS_SESSION_ID = array(
        'name'   => 'brs_session_id',
        'column' => 'brs_session_id',
        'field'  => 'brs_session_id',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'BrsSessionId',
        'snake'  => 'brs_session_id',
        'getter' => 'getBrsSessionId',
        'setter' => 'setBrsSessionId',
        'search' => true
    );
    /**
     * brs_client_address
     * @var String
     */
    protected $brs_client_address = null;
    const DESC_BRS_CLIENT_ADDRESS = array(
        'name'   => 'brs_client_address',
        'column' => 'brs_client_address',
        'field'  => 'brs_client_address',
        'type'   => \FreeFW\Constants::TYPE_STRING,
        'camel'  => 'BrsClientAddress',
        'snake'  => 'brs_client_address',
        'getter' => 'getBrsClientAddress',
        'setter' => 'setBrsClientAddress',
        'search' => true
    );
    /**
     * brs_date_created
     * @var Timestamp
     */
    protected $brs_date_created = null;
    const DESC_BRS_DATE_CREATED = array(
        'name'   => 'brs_date_created',
        'column' => 'brs_date_created',
        'field'  => 'brs_date_created',
        'type'   => \FreeFW\Constants::TYPE_DATETIME,
        'camel'  => 'BrsDateCreated',
        'snake'  => 'brs_date_created',
        'getter' => 'getBrsDateCreated',
        'setter' => 'setBrsDateCreated',
        'search' => false
    );
    /**
     * brs_end
     * @var Timestamp
     */
    protected $brs_end = null;
    const DESC_BRS_END = array(
        'name'   => 'brs_end',
        'column' => 'brs_end',
        'field'  => 'brs_end',
        'type'   => \FreeFW\Constants::TYPE_DATETIME,
        'camel'  => 'BrsEnd',
        'snake'  => 'brs_end',
        'getter' => 'getBrsEnd',
        'setter' => 'setBrsEnd',
        'search' => false
    );
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
        'search' => false
    );
    /**
     * Source
     * @var String
     */
    protected static $source = 'FreeFW_brokers_sessions';

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
            'brs_id'             => self::DESC_BRS_ID,
            'brk_key'            => self::DESC_BRK_KEY,
            'brs_token'          => self::DESC_BRS_TOKEN,
            'brs_session_id'     => self::DESC_BRS_SESSION_ID,
            'brs_client_address' => self::DESC_BRS_CLIENT_ADDRESS,
            'brs_date_created'   => self::DESC_BRS_DATE_CREATED,
            'brs_end'            => self::DESC_BRS_END,
            'sess_id'            => self::DESC_SESS_ID
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
            'brs_id'             => self::DESC_BRS_ID,
            'brk_key'            => self::DESC_BRK_KEY,
            'brs_token'          => self::DESC_BRS_TOKEN,
            'brs_session_id'     => self::DESC_BRS_SESSION_ID,
            'brs_client_address' => self::DESC_BRS_CLIENT_ADDRESS,
            'brs_date_created'   => self::DESC_BRS_DATE_CREATED,
            'brs_end'            => self::DESC_BRS_END,
            'sess_id'            => self::DESC_SESS_ID
        );
    }

    /**
     * Setter brs_id
     *
     * @param bigint $p_value
     *
     * @return \FreeSSO\Model\BrokerSession
     */
    public function setBrsId($p_value)
    {
        $this->brs_id = $p_value;
        return $this;
    }

    /**
     * Getter brs_id
     *
     * @return bigint
     */
    public function getBrsId()
    {
        return $this->brs_id;
    }

    /**
     * Setter brk_key
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\BrokerSession
     */
    public function setBrkKey($p_value)
    {
        $this->brk_key = $p_value;
        return $this;
    }

    /**
     * Getter brk_key
     *
     * @return string
     */
    public function getBrkKey()
    {
        return $this->brk_key;
    }

    /**
     * Setter brs_token
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\BrokerSession
     */
    public function setBrsToken($p_value)
    {
        $this->brs_token = $p_value;
        return $this;
    }

    /**
     * Getter brs_token
     *
     * @return string
     */
    public function getBrsToken()
    {
        return $this->brs_token;
    }

    /**
     * Setter brs_session_id
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\BrokerSession
     */
    public function setBrsSessionId($p_value)
    {
        $this->brs_session_id = $p_value;
        return $this;
    }

    /**
     * Getter brs_session_id
     *
     * @return string
     */
    public function getBrsSessionId()
    {
        return $this->brs_session_id;
    }

    /**
     * Setter brs_client_address
     *
     * @param string $p_value
     *
     * @return \FreeSSO\Model\BrokerSession
     */
    public function setBrsClientAddress($p_value)
    {
        $this->brs_client_address = $p_value;
        return $this;
    }

    /**
     * Getter brs_client_address
     *
     * @return string
     */
    public function getBrsClientAddress()
    {
        return $this->brs_client_address;
    }

    /**
     * Setter brs_date_created
     *
     * @param timestamp $p_value
     *
     * @return \FreeSSO\Model\BrokerSession
     */
    public function setBrsDateCreated($p_value)
    {
        $this->brs_date_created = $p_value;
        return $this;
    }

    /**
     * Getter brs_date_created
     *
     * @return timestamp
     */
    public function getBrsDateCreated()
    {
        return $this->brs_date_created;
    }

    /**
     * Setter brs_end
     *
     * @param timestamp $p_value
     *
     * @return \FreeSSO\Model\BrokerSession
     */
    public function setBrsEnd($p_value)
    {
        $this->brs_end = $p_value;
        return $this;
    }

    /**
     * Getter brs_end
     *
     * @return timestamp
     */
    public function getBrsEnd()
    {
        return $this->brs_end;
    }

    /**
     * Setter sess_id
     *
     * @param bigint $p_value
     *
     * @return \FreeSSO\Model\BrokerSession
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
     * Affichage de la date de fin
     *
     * @return string
     */
    public function displayBrsEnd()
    {
        $date = \FreeFW\Tools\Date::mysqlToDatetime($this->getBrsEnd());
        return $date->format('d/m/Y H:i');
    }

    /**
     * Retourne la liste des colonnes
     *
     * @return array
     */
    public static function columnMap()
    {
        return array(
            'brs_id'             => 'brs_id',
            'brk_key'            => 'brk_key',
            'brs_token'          => 'brs_token',
            'brs_session_id'     => 'brs_session_id',
            'brs_client_address' => 'brs_client_address',
            'brs_date_created'   => 'brs_date_created',
            'brs_end'            => 'brs_end',
            'sess_id'            => 'sess_id'
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
            'brs_id'
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
            'brk_key', 'brs_token', 'brs_session_id', 'brs_client_address'
        );
    }
}
