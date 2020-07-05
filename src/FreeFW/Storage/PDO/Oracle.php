<?php
namespace FreeFW\Storage\PDO;

use \FreeFW\COnstants as FFCST;

/**
 * ...
 * @author jeromeklam
 */
class Oracle extends \PDO implements \FreeFW\Interfaces\StorageProviderInterface
{

    /**
     * comportements
     */
    use \Psr\Log\LoggerAwareTrait;
    use \FreeFW\Behaviour\EventManagerAwareTrait;

    /**
     * Transaction
     * @var boolean
     */
    protected $transaction = false;

    /**
     * Level
     * @var integer
     */
    protected $levels = 0;

    /**
     * Constructeur
     */
    public function __construct($p_dsn, $p_user, $p_password)
    {
        parent::__construct(
            $p_dsn,
            $p_user,
            $p_password
        );
        $this->query("ALTER SESSION SET NLS_DATE_FORMAT = 'YYYY-MM-DD HH24:MI:SS'");
        $this->query("ALTER SESSION SET NLS_DATE_LANGUAGE = 'AMERICAN'");
        $this->query("ALTER SESSION SET NLS_NUMERIC_CHARACTERS = '.,'");
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\StorageProviderInterface::startTransaction()
     */
    public function startTransaction()
    {
        if (!$this->transaction) {
            if ($this->levels <= 0) {
                $this->transaction = $this->beginTransaction();
                $this->forwardRawEvent(FFCST::EVENT_STORAGE_BEGIN);
            }
            if ($this->transaction) {
                $this->levels = 1;
            }
        } else {
            $this->levels = $this->levels + 1;
        }
        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\StorageProviderInterface::commitTransaction()
     */
    public function commitTransaction()
    {
        if ($this->transaction) {
            $this->levels = $this->levels - 1;
            if ($this->levels <= 0) {
                $this->commit();
                $this->forwardRawEvent(FFCST::EVENT_STORAGE_COMMIT);
                $this->transaction = false;
            }
        }
        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\StorageProviderInterface::rollbackTransaction()
     */
    public function rollbackTransaction()
    {
        if ($this->transaction) {
            $this->levels = $this->levels - 1;
            if ($this->levels <= 0) {
                $this->rollBack();
                $this->forwardRawEvent(FFCST::EVENT_STORAGE_ROLLBACK);
                $this->transaction = false;
            }
        }
        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\StorageProviderInterface::hasSqlCalcFoundRows()
     */
    public function hasSqlCalcFoundRows()
    {
        return false;
    }

    /**
     * Convert a function in SQL
     *
     * @param string $p_function
     * @param string $p_field
     *
     * @return string
     */
    public function convertFunction($p_function, $p_field)
    {
        switch ($p_function) {
            case \FreeFW\Storage\Storage::FUNCTION_YEAR:
                return ('EXTRACT year FROM ' . $p_field);
            case \FreeFW\Storage\Storage::FUNCTION_MONTH:
                return ('EXTRACT month FROM ' . $p_field);
            case \FreeFW\Storage\Storage::FUNCTION_DAY:
                return ('EXTRACT day FROM ' . $p_field);
            case \FreeFW\Storage\Storage::FUNCTION_MIN:
                return ('MIN(' . $p_field . ')');
            case \FreeFW\Storage\Storage::FUNCTION_MAX:
                return ('MAX(' . $p_field . ')');
            case \FreeFW\Storage\Storage::FUNCTION_SUM:
                return ('SUM(' . $p_field . ')');
        }
        return $p_field;
    }
}
