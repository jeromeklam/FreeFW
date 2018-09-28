<?php
/**
 * Interface modèle avec stockage
 *
 * @author jeromeklam
 * @package Model
 * @category Interface
 */
namespace FreeFW\Interfaces;

/**
 * Modèle standard
 * @author jeromeklam
 */
interface StorageModel
{

    /**
     * Retourne la source
     *
     * @return string
     */
    public static function getSource();

    /**
     * Retourne la liste des colonnes
     *
     * @return array      // Tableau de fieldName => propertyName
     */
    public static function columnMap();

    /**
     * Retourne la liste des colonnes identifiantes
     *
     * @return array      // Tableaiu de propertyName
     */
    public static function columnId();

    /**
     * Retourne les colonnes disponibles en fulltex
     *
     * @return array      // Tableaiu de propertyName
     */
    public static function columnFulltext();
    
    /**
     * Retourne un enregistrement en fonction de son identifiant(s)
     *
     * @param array $p_values
     *
     * @return object
     */
    public static function getById($p_values = array());
}
