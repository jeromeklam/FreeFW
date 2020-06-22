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
     * @return boolean
     */
    public function create();

    /**
     * Save an object
     *
     * @return boolean
     */
    public function save();

    /**
     * Remove an object
     *
     * @return boolean
     */
    public function remove();

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
