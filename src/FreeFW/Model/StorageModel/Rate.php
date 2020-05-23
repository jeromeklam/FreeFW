<?php
namespace FreeFW\Model\StorageModel;

use \FreeFW\Constants as FFCST;

/**
 * Rate
 *
 * @author jeromeklam
 */
abstract class Rate extends \FreeFW\Core\StorageModel
{

    /**
     * Field properties as static arrays
     * @var array
     */
    protected static $PRP_RATE_ID = [
        FFCST::PROPERTY_PRIVATE => 'rate_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_PK],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
    protected static $PRP_RATE_MONEY_FROM = [
        FFCST::PROPERTY_PRIVATE => 'rate_money_from',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_MAX     => 3,
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_RATE_MONEY_TO = [
        FFCST::PROPERTY_PRIVATE => 'rate_money_to',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_MAX     => 3,
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_RATE_TS = [
        FFCST::PROPERTY_PRIVATE => 'rate_ts',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => '',
    ];
    protected static $PRP_RATE_CHANGE = [
        FFCST::PROPERTY_PRIVATE => 'rate_change',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_MONETARY,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => '',
        FFCST::PROPERTY_SAMPLE  => '',
    ];

    /**
     * get properties
     *
     * @return array[]
     */
    public static function getProperties()
    {
        return [
            'rate_id'         => self::$PRP_RATE_ID,
            'rate_money_from' => self::$PRP_RATE_MONEY_FROM,
            'rate_money_to'   => self::$PRP_RATE_MONEY_TO,
            'rate_ts'         => self::$PRP_RATE_TS,
            'rate_change'     => self::$PRP_RATE_CHANGE
        ];
    }

    /**
     * Set object source
     *
     * @return string
     */
    public static function getSource()
    {
        return 'sys_rate';
    }

    /**
     * Get object short description
     *
     * @return string
     */
    public static function getSourceComments()
    {
        return '';
    }

    /**
     * Get autocomplete field
     *
     * @return string
     */
    public static function getAutocompleteField()
    {
        return '';
    }
}
