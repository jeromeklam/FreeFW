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
     * @param \FreeFW\Model\Conditions  $p_conditions
     *
     * @return \FreeFW\Core\StorageModel
     */
    public function findFirst(
        \FreeFW\Core\StorageModel &$p_model,
        \FreeFW\Model\Conditions $p_conditions = null
    );

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
     * @param \FreeFW\Model\Conditions  $p_conditions
     *
     * @return \FreeFW\Model\ResultSet
     */
    public function select(
        \FreeFW\Core\StorageModel &$p_model,
        \FreeFW\Model\Conditions $p_conditions = null,
        array $p_relations = [],
        int $p_from = 0,
        int $p_length = 0,
        array $p_sort = []
    );

    /**
     * Remove the model
     *
     * @param \FreeFW\Core\StorageModel $p_model
     * @param \FreeFW\Model\Conditions  $p_conditions
     *
     * @return boolean
     */
    public function delete(
        \FreeFW\Core\StorageModel &$p_model,
        \FreeFW\Model\Conditions $p_conditions = null
    );

    /**
     * Get fields
     *
     * @param string $p_object
     *
     * @return [\FreeFW\Model\Field]
     */
    public function getFields(string $p_object) : array;
}
