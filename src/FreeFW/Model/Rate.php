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
     * Find best rate
     *
     * @param unknown $p_from
     * @param unknown $p_to
     * @param unknown $p_ts
     *
     * @return \FreeFW\Model\Rate || false
     */
    public static function findBest($p_from, $p_to, $p_ts)
    {
        $rate = self::findFirst(
            [
                'rate_money_from' => $p_from,
                'rate_money_to'   => $p_to,
                'rate_ts'         => [ \FreeFW\Storage\Storage::COND_LOWER_EQUAL => $p_ts ]
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
                    'rate_ts'         => [ \FreeFW\Storage\Storage::COND_GREATER_EQUAL => $p_ts ]
                ],
                [
                    'rate_ts' => \FreeFW\Storage\Storage::SORT_ASC
                ]
            );
            if (!$rate instanceof \FreeFW\Model\Rate) {
                $rate = false;
            }
        }
        return $rate;
    }
}
