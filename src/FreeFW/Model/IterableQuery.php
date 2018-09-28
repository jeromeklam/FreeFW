<?php
/**
 * Requête itérable
 *
 * @author jeromeklam
 * @package SQL
 */
namespace FreeFW\Model;

/**
 * Requête simplifiée
 * @author jeromeklam
 */
class IterableQuery extends \FreeFW\Model\SimpleQuery {

    /**
     * PDO statement
     * @var Resource
     */
    protected $statement = null;

    /**
     * Start query
     *
     * @return boolean
     */
    protected function startQuery()
    {
        $this->setSelectFields(true);
        $class     = $this->getModelClass();
        $resultSet = new \FreeFW\Model\ResultSet();
        $pdo       = self::getConnexion($class::getCnxName());
        $sqlQuery  = $this->getSQLQuery([], true, $pdo->hasSqlCalcFoundRows());
        if ($sqlQuery !== false) {
            $count            = 0;
            $this->statement  = $pdo->prepare($sqlQuery['sql'], array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            try {
                self::debug('simpleQuery.sql : ' . $sqlQuery['sql']);
                self::debug('simpleQuery.sql : ' . json_encode($sqlQuery['fields']));
                $pdores = $this->statement->execute($sqlQuery['fields']);
                if (!$pdores) {
                    $this->statement = null;
                } else {
                    return true;
                }
            } catch (\Exception $ex) {
                // @todo
                //var_dump($ex);
                //die('ici');
            }
        }
        return false;
    }

    /**
     * Get one Row
     *
     * @return boolean|unknown
     */
    public function getRow()
    {
        if ($this->statement === null) {
            if (!$this->startQuery()) {
                return false;
            }
        }
        return $this->statement->fetch(\PDO::FETCH_OBJ);
    }
}
