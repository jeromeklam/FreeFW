<?php
namespace FreeFW\Interfaces;

/**
 * Storage strategy
 *
 * @author jeromeklam
 */
interface DirectStorageInterface
{

    /**
     * Start transaction helper
     */
    public function startTransaction();

    /**
     * Rollback transaction helper
     */
    public function rollbackTransaction();

    /**
     * Commit transaction helper
     */
    public function commitTransaction();

    /**
     * Create an object
     *
     * @param bool $p_with_transaction
     * @param bool $p_raw
     *
     * @return bool
     */
    public function create(bool $p_with_transaction = true, bool $p_raw = false) : bool;

    /**
     * Save an object
     *
     * @param bool $p_with_transaction
     * @param bool $p_raw
     *
     * @return bool
     */
    public function save(bool $p_with_transaction = true, bool $p_raw = false) : bool;

    /**
     * Remove an object
     *
     * @param bool $p_with_transaction
     * @param bool $p_raw
     *
     * @return bool
     */
    public function remove(bool $p_with_transaction = true, bool $p_raw = false) : bool;

    /**
     * Find all objects
     *
     * @param array $p_filters
     *
     * @return \FreeFW\Core\StorageModel
     */
    public static function find(array $p_filters = []);

    /**
     * Find an object
     *
     * @param array $p_filters
     *
     * @return \FreeFW\Core\StorageModel
     */
    public static function findFirst(array $p_filters = [], array $p_sort = []);

    /**
     * Count
     *
     * @param array $p_filters
     *
     * @return number
     */
    public static function count(array $p_filters = []);
}
