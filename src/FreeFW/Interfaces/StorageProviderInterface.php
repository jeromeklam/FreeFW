<?php
namespace FreeFW\Interfaces;

/**
 * Storage provider interface
 *
 * @author jeromeklam
 */
interface StorageProviderInterface
{

    /**
     * Start transaction
     *
     * @return \FreeFW\Interfaces\StorageProviderInterface
     */
    public function startTransaction();

    /**
     * Commit transaction
     *
     * @return \FreeFW\Interfaces\StorageProviderInterface
     */
    public function commitTransaction();

    /**
     * Rollback transaction
     *
     * @return \FreeFW\Interfaces\StorageProviderInterface
     */
    public function rollbackTransaction();

    /**
     * SQL_CALC_FOUND_ROWS available ?
     *
     * @return boolean
     */
    public function hasSqlCalcFoundRows();

    /**
     * Convert a function in SQL
     *
     * @param string $p_function
     * @param string $p_field
     *
     * @return string
     */
    public function convertFunction($p_function, $p_field);
}
