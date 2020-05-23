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
        $p_list = ['INR', 'IDR', 'CHF', 'GBP'];
        $url    = 'https://api.exchangeratesapi.io/latest?symbols=' . implode(',', $p_list) . '&base=EUR';
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
                    ->setRateMoneyFrom('EUR')
                    ->setRateMoneyTo($money)
                    ->setRateTs(\FreeFW\Tools\Date::stringToMysql($datas['date']))
                    ->setRateChange($mnt)
                ;
                $rate->create();
            }
        }
        foreach ($p_list as $money) {
            $url    = 'https://api.exchangeratesapi.io/latest?symbols=EUR&base=' . $money;
            $curl   = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            $data = curl_exec($curl);
            curl_close($curl);
            $datas = json_decode($data, true);
            if (array_key_exists('rates', $datas)) {
                foreach ($datas['rates'] as $moneyFrom => $mnt) {
                    $rate = \FreeFW\DI\DI::get('FreeFW::Model::Rate');
                    $rate
                        ->setRateMoneyFrom($money)
                        ->setRateMoneyTo($moneyFrom)
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