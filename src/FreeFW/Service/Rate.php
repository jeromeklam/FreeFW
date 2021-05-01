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
        $config = $this->getAppConfig();
        $key    = $config->get('api:currency');
        $p_list = ['EUR', 'INR', 'IDR', 'CHF', 'GBP'];
        foreach ($p_list as $moneyFrom) {
            $url    = 'https://v6.exchangerate-api.com/v6/' . $key . '/latest/' . $moneyFrom;
            $curl   = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            $data = curl_exec($curl);
            curl_close($curl);
            $datas = json_decode($data, true);
            if (array_key_exists('conversion_rates', $datas)) {
                foreach ($datas['conversion_rates'] as $money => $mnt) {
                    if (in_array($money, $p_list)) {
                        $rate = \FreeFW\DI\DI::get('FreeFW::Model::Rate');
                        $ts   = date('c', $datas['time_last_update_unix']);
                        $rate
                            ->setRateMoneyFrom($moneyFrom)
                            ->setRateMoneyTo($money)
                            ->setRateTs(\FreeFW\Tools\Date::stringToMysql($ts))
                            ->setRateChange($mnt)
                        ;
                        $rate->create();
                    }
                }
            }
        }
        return true;
    }
}