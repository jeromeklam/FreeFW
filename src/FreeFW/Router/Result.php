<?php
namespace FreeFW\Router;

/**
 *
 * @author jeromeklam
 *
 */
class Result
{

    /**
     * Types
     * @var string
     */
    const TYPE_STRING  = 'string';
    const TYPE_NUMBER  = 'number';
    const TYPE_DATE    = 'date';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_ARRAY   = 'array';
    const TYPE_OBJECT  = 'object';

    /**
     * Code http de la réponse
     * @var number
     */
    protected $http = null;

    /**
     * Le type de résultat
     * @var string
     */
    protected $type = null;

    /**
     * L'objet retourné
     * @var string
     */
    protected $object = null;

    /**
     * Commentaires
     * @var string
     */
    protected $comments = null;

    /**
     * Affectation du code Http
     *
     * @param number $p_code
     *
     * @return \FreeFW\Router\Result
     */
    public function setHttp($p_code)
    {
        $this->http = $p_code;
        return $this;
    }

    /**
     * Retourne le code http
     *
     * @return number
     */
    public function getHttp()
    {
        return $this->http;
    }

    /**
     * Affectation de l'objet
     *
     * @return string
     */
    public function setObject($p_object)
    {
        $this->object = $p_object;
        return $this;
    }

    /**
     * Retourne l'object
     *
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Affectation des commentaires
     *
     * @param string $p_comments
     *
     * @return \FreeFW\Router\Result
     */
    public function setComments($p_comments)
    {
        $this->comments = $p_comments;
        return $this;
    }

    /**
     * Retourne les commentaires
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Affectatiob du type
     *
     * @param string $p_type
     *
     * @throws \InvalidArgumentException
     *
     * @return \FreeFW\Router\Result
     */
    public function setType($p_type)
    {
        if (in_array($p_type, self::getTypes())) {
            $this->type = $p_type;
        } else {
            throw new \InvalidArgumentException(sprintf('%s type is unknown !', $p_type));
        }
        return $this;
    }

    /**
     * Retourne le type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * Retourne la liste des types disponibles
     *
     * @return array
     */
    public static function getTypes()
    {
        return array(
            self::TYPE_STRING,
            self::TYPE_NUMBER,
            self::TYPE_DATE,
            self::TYPE_BOOLEAN,
            self::TYPE_ARRAY,
            self::TYPE_OBJECT
        );
    }
}
