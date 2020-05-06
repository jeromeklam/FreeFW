<?php
namespace FreeFW\OpenApi\V3;

/**
 * OpenApi v3 Info object
 *
 * @author jeromeklam
 */
class Info extends \FreeFW\OpenApi\V3\Base
{

    /**
     * Titre
     * @var string
     */
    protected $title = null;

    /**
     * Description
     * @var string
     */
    protected $description = null;

    /**
     * Terms
     * @var string
     */
    protected $termsOfService = null;

    /**
     * Contact
     * @var \FreeFW\OpenApi\V3\Contact
     */
    protected $contact = null;

    /**
     * Licence
     * @var \FreeFW\OpenApi\V3\License
     */
    protected $license = null;

    /**
     * Version
     * @var string
     */
    protected $version = null;
}
