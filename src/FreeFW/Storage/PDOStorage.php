<?php
namespace FreeFW\Storage;

use \FreeFW\Constants as FFCST;

/**
 *
 * @author jeromeklam
 *
 */
class PDOStorage implements
    \FreeFW\Interfaces\StorageInterface,
    \Psr\Log\LoggerAwareInterface,
    \FreeFW\Interfaces\ConfigAwareTraitInterface
{

    /**
     * comportements
     */
    use \Psr\Log\LoggerAwareTrait;
    use \FreeFW\Behaviour\EventManagerAwareTrait;
    use \FreeFW\Behaviour\ConfigAwareTrait;

    /**
     * Provider
     * @var \FreeFW\Interfaces\StorageProviderInterface
     */
    protected $provider = null;

    /**
     * Constructor
     *
     * @param \FreeFW\Interfaces\StorageProviderInterface $p_provider
     */
    public function __construct(\FreeFW\Interfaces\StorageProviderInterface $p_provider)
    {
        $this->provider = $p_provider;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\StorageStrategyInterface::create()
     */
    public function create(\FreeFW\Core\StorageModel &$p_model)
    {
        $fields     = [];
        $source     = $p_model::getSource();
        $properties = $p_model::getProperties();
        foreach ($properties as $name => $oneProperty) {
            $add = true;
            $pk  = false;
            if (array_key_exists('options', $oneProperty)) {
                if (in_array(FFCST::OPTION_LOCAL, $oneProperty['options'])) {
                    $add = false;
                }
                if (in_array(FFCST::OPTION_PK, $oneProperty['options'])) {
                    $pk = true;
                }
            }
            if ($add) {
                // PK fields must be autoincrement...
                if ($pk) {
                    $fields[$oneProperty['destination']] = null;
                } else {
                    // Compute getter
                    $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                    // Get data
                    $fields[':' . $oneProperty['destination']] = $p_model->$getter();
                }
            }
        }
        // Build query
        $sql = \FreeFW\Tools\Sql::makeInsertQuery($source, $fields);
        $this->logger->debug('PDOStorage.create : ' . $sql);
        try {
            // Get PDO and execute
            $query = $this->provider->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            if ($query->execute($fields)) {
                $code = '';
            } else {
                $this->logger->debug('PDOStorage.create.error : ' . print_r($query->errorInfo(), true));
                $localErr = $query->errorInfo();
                $code     = 0;
                $message  = 'PDOStorage.create.error : ' . print_r($query->errorInfo(), true);
                if (is_array($localErr) && count($localErr) > 1) {
                    $code    = intval($localErr[0]);
                    $message = $localErr[2];
                }
                $p_model->addError($code, $message);
            }
        } catch (\Exception $ex) {
            var_dump($ex);
        }
        return $p_model;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\StorageInterface::findFirst()
     */
    public function findFirst(\FreeFW\Core\StorageModel &$p_model, $p_filters = null)
    {
        $source     = $p_model->getSource();
        $properties = $p_model->getProperties();
        $fields     = [];
        if (is_int($p_filters)) {
            foreach ($properties as $name => $oneProperty) {
                if (array_key_exists('options', $oneProperty)) {
                    if (in_array(FFCST::OPTION_PK, $oneProperty['options'])) {
                        $fields[':' . $oneProperty['destination']] = $p_filters;
                        break;
                    }
                }
            }
        } else {
            if (is_array($p_filters)) {
                foreach ($p_filters as $field => $value) {
                    if (array_key_exists($field, $properties)) {
                        $fields[':' . $properties[$field]['destination']] = $value;
                    } else {
                        throw new \FreeFW\Core\FreeFWStorageException(sprintf('Unkown %s field !', $field));
                    }
                }
            }
        }
        // Build query
        $sql = \FreeFW\Tools\Sql::makeSimpleSelect($source, $fields);
        $this->logger->debug('PDOStorage.create : ' . $sql);
        try {
            // Get PDO and execute
            $query = $this->provider->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            if ($query->execute($fields)) {
                while ($row = $query->fetch(\PDO::FETCH_OBJ)) {
                    $p_model->setFromArray($row);
                    break;
                }
            } else {
                $this->logger->debug('PDOStorage.create.error : ' . print_r($query->errorInfo(), true));
                $localErr = $query->errorInfo();
                $code     = 0;
                $message  = 'PDOStorage.create.error : ' . print_r($query->errorInfo(), true);
                if (is_array($localErr) && count($localErr) > 1) {
                    $code    = intval($localErr[0]);
                    $message = $localErr[2];
                }
                $p_model->addError($code, $message);
            }
        } catch (\Exception $ex) {
            var_dump($ex);
        }
        return $p_model;
    }
}
