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
     * @return \FreeFW\Core\StorageModel
     */
    public function create(\FreeFW\Core\StorageModel &$p_model);

    /**
     * Find a model
     *
     * @return \FreeFW\Core\StorageModel
     */
    public function findFirst(\FreeFW\Core\StorageModel &$p_model, $p_filters = null);
}
