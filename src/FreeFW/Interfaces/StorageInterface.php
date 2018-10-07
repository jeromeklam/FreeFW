<?php
namespace FreeFW\Interfaces;

/**
 * Storage interface
 *
 * @author jeromeklam
 */
interface StorageInterface
{

    /**
     * Persist the model
     *
     * @param \FreeFW\Core\StorageModel $p_model
     *
     * @return \FreeFW\Core\StorageModel
     */
    public function create(\FreeFW\Core\StorageModel &$p_model);

    /**
     * Find a model
     *
     * @param \FreeFW\Core\StorageModel $p_model
     * @param array                     $p_conditions
     *
     * @return \FreeFW\Core\StorageModel
     */
    public function findFirst(\FreeFW\Core\StorageModel &$p_model, array $p_conditions = []);

    /**
     * Persist the model
     *
     * @param \FreeFW\Core\StorageModel $p_model
     *
     * @return boolean
     */
    public function save(\FreeFW\Core\StorageModel &$p_model);

    /**
     * Remove the model
     *
     * @param \FreeFW\Core\StorageModel $p_model
     *
     * @return boolean
     */
    public function remove(\FreeFW\Core\StorageModel &$p_model);

    /**
     * Select the model
     *
     * @param \FreeFW\Core\StorageModel $p_model
     * @param array                     $p_conditions
     *
     * @return boolean
     */
    public function select(\FreeFW\Core\StorageModel &$p_model, array $p_conditions = []);

    /**
     * Remove the model
     *
     * @param \FreeFW\Core\StorageModel $p_model
     * @param array                     $p_conditions
     *
     * @return boolean
     */
    public function delete(\FreeFW\Core\StorageModel &$p_model, array $p_conditions = []);
}
