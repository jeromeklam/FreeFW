<?php
namespace FreeFW\Model\Connexion;

/**
 * ...
 * @author jeromeklam
 */
class Mysql extends \PDO
{

    /**
     * Transaction
     * @var boolean
     */
    protected $transaction = false;

    /**
     * Niveaux
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
            $p_password,
            array(\PDO::MYSQL_ATTR_FOUND_ROWS => true)
        );
    }

    /**
     * Démarre la transaction
     *
     * @return \FreeFW\Model\Connexion\Mysql
     */
    public function startTransaction()
    {
        if (!$this->transaction) {
            if ($this->levels <= 0) {
                $this->transaction = $this->beginTransaction();
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
     * Valide la transaction
     *
     * @return \FreeFW\Model\Connexion\Mysql
     */
    public function commitTransaction()
    {
        if ($this->transaction) {
            $this->levels = $this->levels - 1;
            if ($this->levels <= 0) {
                $this->commit();
                if (self::inTransaction()) {
                    // No data modified or error... not normal...
                    $this->rollBack();
                }
                $this->transaction = false;
            }
        }
        return $this;
    }

    /**
     * Annule la transaction
     *
     * @return \FreeFW\Model\Connexion\Mysql
     */
    public function rollbackTransaction()
    {
        if ($this->transaction) {
            $this->levels = $this->levels - 1;
            if ($this->levels <= 0) {
                $this->rollBack();
                $this->transaction = false;
            }
        }
        return $this;
    }

    /**
     * SQL_CALC_FOUND_ROWS available ?
     *
     * @return boolean
     */
    public function hasSqlCalcFoundRows()
    {
        return true;
    }
}
