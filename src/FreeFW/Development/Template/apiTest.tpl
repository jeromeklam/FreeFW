<?php
namespace [[:namespace:]];

/**
 *
 * @author jerome.klam
 *
 */
class [[:class:]] extends \PHPUnit\Framework\TestCase {

    /**
     * HTTP Client
     * @var \GuzzleHttp\Client
     */
    protected $client = null;

    /**
     * Before all
     */
    public static function setUpBeforeClass()
    {
    }

    /**
     * Before each test
     */
    protected function setUp()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => '[[:base_uri:]]'
        ]);
    }

    [[:tests:]]

    /**
     * After each test
     */
    protected function tearDown()
    {
    }

    /**
     * After all
     */
    public static function tearDownAfterClass()
    {
    }
}
