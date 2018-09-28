<?php
namespace FreeFW\Model\Connexion;

/**
 * ...
 * @author jeromeklam
 */
class Oracle extends \PDO
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
            $p_password
        );
        $this->query("ALTER SESSION SET NLS_DATE_FORMAT = 'YYYY-MM-DD HH24:MI:SS'");
        $this->query("ALTER SESSION SET NLS_DATE_LANGUAGE = 'AMERICAN'");
        $this->query("ALTER SESSION SET NLS_NUMERIC_CHARACTERS = '.,'");
    }

    /**
     * Démarre la transaction
     *
     * @return \FreeFW\Model\Connexion
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
     * @return \FreeFW\Model\Connexion
     */
    public function commitTransaction()
    {
        if ($this->transaction) {
            $this->levels = $this->levels - 1;
            if ($this->levels <= 0) {
                $this->commit();
                $this->transaction = false;
            }
        }
        return $this;
    }

    /**
     * Annule la transaction
     *
     * @return \FreeFW\Model\Connexion
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
        return false;
    }
}
