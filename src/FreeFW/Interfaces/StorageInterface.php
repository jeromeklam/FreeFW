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
     * @param bool                      $p_with_transaction
     * @param bool                      $p_raw
     *
     * @return \FreeFW\Core\StorageModel
     */
    public function create(\FreeFW\Core\StorageModel &$p_model, bool $p_with_transaction = true, bool $p_raw = false): bool;

    /**
     * Find a model
     *
     * @param \FreeFW\Core\StorageModel $p_model
     * @param \FreeFW\Model\Conditions $p_conditions
     *
     * @return \FreeFW\Core\StorageModel
     */
    public function findFirst(\FreeFW\Core\StorageModel &$p_model, \FreeFW\Model\Conditions $p_conditions = null);

    /**
     * Persist the model
     *
     * @param \FreeFW\Core\StorageModel $p_model
     * @param bool                      $p_with_transaction
     * @param bool                      $p_raw
     *
     * @return boolean
     */
    public function save(\FreeFW\Core\StorageModel &$p_model, bool $p_with_transaction = true, bool $p_raw = false): bool;

    /**
     * Remove the model
     *
     * @param \FreeFW\Core\StorageModel $p_model
     * @param bool                      $p_with_transaction
     * @param bool                      $p_raw
     *
     * @return boolean
     */
    public function remove(\FreeFW\Core\StorageModel &$p_model, bool $p_with_transaction = true, bool $p_raw = false): bool;

    /**
     * Select the model
     *
     * @param \FreeFW\Core\StorageModel $p_model
     * @param \FreeFW\Model\Conditions $p_conditions
     * @param array  $p_relations
     * @param number $p_from
     * @param number $p_length
     * @param array  $p_sort
     * @param string $p_force_select
     * @param string $p_function
     * @param array  $p_fields
     * @param string $p_special
     * @param array  $p_parameters
     * @param bool   $p_force_blob
     *
     * @return \FreeFW\Model\ResultSet
     */
    public function select(\FreeFW\Core\StorageModel &$p_model, \FreeFW\Model\Conditions $p_conditions = null, array $p_relations = [], int $p_from = 0, int $p_length = 0, array $p_sort = [], string $p_force_select = '', $p_function = null, array $p_fields = [], $p_special = 'SELECT', $p_parameters = [], $p_force_blob = false);

    /**
     * Update the model
     *
     * @param \FreeFW\Core\StorageModel $p_model
     * @param array $p_fields
     * @param \FreeFW\Model\Conditions $p_conditions
     *
     * @return boolean
     */
    public function update(\FreeFW\Core\StorageModel &$p_model, array $p_fields, \FreeFW\Model\Conditions $p_conditions = null);

    /**
     * Remove the model
     *
     * @param \FreeFW\Core\StorageModel $p_model
     * @param \FreeFW\Model\Conditions $p_conditions
     *
     * @return boolean
     */
    public function delete(\FreeFW\Core\StorageModel &$p_model, \FreeFW\Model\Conditions $p_conditions = null);

    /**
     * Get fields
     *
     * @param string $p_object
     *
     * @return [\FreeFW\Model\Field]
     */
    public function getFields(string $p_object): array;

    /**
     * Get provider
     *
     * @return \FreeFW\Interfaces\StorageProviderInterface
     */
    public function getProvider(): \FreeFW\Interfaces\StorageProviderInterface;
}
