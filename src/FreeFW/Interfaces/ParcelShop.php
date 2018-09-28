<?php
namespace FreeFW\Interfaces;

/**
 * Interface standard de gestion des points relais
 */
interface ParcelShop
{

    /**
     * Recherche des points relais par adresse
     *
     * @param string $p_pays
     * @param string $p_cp
     * @param string $p_ville
     * @param string $p_adresse
     *
     * @return \FreeFW\Model\ParcelShopList
     */
    public function findByAddress($p_pays, $p_cp, $p_ville = null, $p_adresse = null);

    /**
     * Récupération d'un point relais en fonction de son identifiant
     *
     * @var string $p_id
     *
     * @return \FreeFW\Model\ParcelShop
     */
    public function getById($p_id);
}
