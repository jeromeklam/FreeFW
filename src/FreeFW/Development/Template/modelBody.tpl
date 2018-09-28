<?php
/**
 * [[:description:]]
 * @author jerome.klam
 */
namespace [[:namespace:]];

/**
 * Classe : [[:class:]]
 * @author : jerome.klam
 */
class [[:class:]] extends \PawBx\Core\Model\AbstractPDOStorage
{
[[:fields:]]
    /**
     * Source
     * @var String
     */
    protected static $source = '[[:table:]]';

    /**
     * Retourne la source
     *
     * @return string
     */
    public static function getSource()
    {
        return self::$source;
    }

    /**
     * Retourne le descriptif des colonnes par nom en db
     *
     * @return array
     */
    public static function getColumnDescByName()
    {
        return [[:desccol:]]
    }

    /**
     * Retourne le descriptif des colonnes par nom de propriété
     *
     * @return array
     */
    public static function getColumnDescByField()
    {
        return [[:descprop:]]
    }
[[:getset:]]
    /**
     * Retourne la liste des colonnes
     *
     * @return array
     */
    public static function columnMap()
    {
        return array(
[[:colmap:]]
        );
    }

    /**
     * Retourne la liste des colonnes identifiantes
     *
     * @return array      // Tableau de propertyName
     */
    public static function columnId()
    {
        return array(
[[:colid:]]
        );
    }

    /**
     * Retourne les colonnes disponibles en fulltext
     *
     * @return array
     */
    public static function columnFulltext()
    {
        return array(
[[:colfull:]]
        );
    }
}
