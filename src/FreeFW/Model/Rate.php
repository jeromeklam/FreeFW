<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Model Rate
 *
 * @author jeromeklam
 */
class Rate extends \FreeFW\Model\Base\Rate
{

    /**
     * Prevent from saving history
     * @var bool
     */
    protected $no_history = true;

    /**
     * Cached values
     * @var [float]
     */
    protected static $_cached = [];

    /**
     * Find best rate
     *
     * @param string $p_from
     * @param string $p_to
     * @param mixed  $p_ts
     *
     * @return \FreeFW\Model\Rate || false
     */
    public static function findBest($p_from, $p_to, $p_ts = null)
    {
        $ts = $p_ts;
        if (!$ts) {
            $ts = \FreeFW\Tools\Date::getCurrentTimestamp();
        }
        $key = $p_from . '.' . $p_to . '.' . $ts;
        if (isset(self::$_cached[$key])) {
            return self::$_cached[$key];
        }
        $rate = self::findFirst(
            [
                'rate_money_from' => $p_from,
                'rate_money_to'   => $p_to,
                'rate_ts'         => [ \FreeFW\Storage\Storage::COND_LOWER_EQUAL => $ts ]
            ],
            [
                'rate_ts' => \FreeFW\Storage\Storage::SORT_DESC
            ]
        );
        if (!$rate instanceof \FreeFW\Model\Rate) {
            $rate = self::findFirst(
                [
                    'rate_money_from' => $p_from,
                    'rate_money_to'   => $p_to,
                    'rate_ts'         => [ \FreeFW\Storage\Storage::COND_GREATER_EQUAL => $ts ]
                ],
                [
                    'rate_ts' => \FreeFW\Storage\Storage::SORT_ASC
                ]
            );
            if (!$rate instanceof \FreeFW\Model\Rate) {
                $rate = false;
            }
        }
        self::$_cached[$key] = $rate;
        return $rate;
    }

    /**
     * Convert
     * 
     * @param string $p_from
     * @param string $p_to
     * @param number $p_amount
     * 
     * @return number
     */
    public static function convert($p_from, $p_to, $p_amount)
    {
        /**
         * @var float $amount
         */
        $amount = $p_amount;
        if (strtoupper($p_from) != strtoupper($p_to)) {
            /**
             * @var \FreeSW\Model\rate $best
             */
            $best = self::findBest($p_from, $p_to);
            if ($best) {
                return $amount * $best->getRateChange();
            }
        }
        return $amount;
    }
}
