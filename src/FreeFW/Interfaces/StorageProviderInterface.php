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
}
