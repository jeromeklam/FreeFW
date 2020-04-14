<?php 
namespace FreeFW\Tools;

/**
 * 
 * @author jeromeklam
 *
 */
class Monetary
{

    /**
     * Convert mnt to mnt
     * 
     * @param mixed  $p_input_mnt
     * @param string $p_input_money
     * @param string $p_output_money
     * 
     * @return number
     */
    static public function convert($p_input_mnt, $p_input_money = 'EUR', $p_output_money = 'EUR')
    {
        $mnt = 0.00;
        try {
            $mnt += $p_input_mnt;
        } catch (\Exception $ex) {
            $mnt = 0.00;
        }
        return $mnt;
    }
}
