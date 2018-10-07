<?php
namespace FreeFW\Interfaces;

/**
 * Storage strategy
 *
 * @author jeromeklam
 */
interface StorageStrategyInterface
{

    /**
     * Set strategy
     *
     * @param \FreeFW\Interfaces\StorageInterface $p_strategy
     *
     * @return \FreeFW\Core\StorageModel
     */
    public function setStrategy(\FreeFW\Interfaces\StorageInterface $p_strategy);
}
