<?php
namespace FreeFW\Service;

/**
 *
 * @author jeromeklam
 *
 */
class Rate extends \FreeFW\Core\Service
{

    /**
     * Check rates
     *
     * @return boolean
     */
    public function checkRates()
    {
        $p_list = ['EUR', 'INR', 'IDR', 'CHF', 'GBP'];
        foreach ($p_list as $moneyFrom) {
            $dest   = implode(',', $p_list);
            $url    = 'https://api.exchangeratesapi.io/latest?symbols=' . $dest . '&base=' . $moneyFrom;
            $curl   = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            $data = curl_exec($curl);
            curl_close($curl);
            $datas = json_decode($data, true);
            if (array_key_exists('rates', $datas)) {
                foreach ($datas['rates'] as $money => $mnt) {
                    $rate = \FreeFW\DI\DI::get('FreeFW::Model::Rate');
                    $rate
                        ->setRateMoneyFrom($moneyFrom)
                        ->setRateMoneyTo($money)
                        ->setRateTs(\FreeFW\Tools\Date::stringToMysql($datas['date']))
                        ->setRateChange($mnt)
                    ;
                    $rate->create();
                }
            }
        }
        return true;
    }
}