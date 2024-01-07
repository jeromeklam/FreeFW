<?php
namespace FreeFW\Command;

/**
 * FreeFW OpenData
 *
 * @author jeromeklam
 */
class OpenData
{

    /**
     * Vérification des pays
     *
     * @param \FreeFW\Console\Input\AbstractInput $p_input
     * @param \FreeFW\Console\Output\AbstractOutput $p_output
     */
    public function checkCountries(
        \FreeFW\Console\Input\AbstractInput $p_input,
        \FreeFW\Console\Output\AbstractOutput $p_output
    ) {
        $p_output->write("Gestion des pays", true);
        $sso      = \FreeFW\DI\DI::getShared('sso');
        $brokerId = $sso->getBrokerId();
        $p_output->write("Broker : " . $brokerId, true);

        $odTerritories = \FreeFW\OpenData\DataSet::getFactory('\\FreeFW\\OpenData\\Object\\Territory');
        $territory = new \FreeFW\OpenData\Object\Territory();

        $countries = \FreeFW\Model\Country::find();
        foreach ($countries as $oneCountry) {
            if ($oneCountry->getCntyIso3() == '' || $oneCountry->getCntyCog() == 'XXXXX') {
                $territory->actual = 1;
                if ($oneCountry->getCntyCode() != '') {
                    $territory->codeiso2 = $oneCountry->getCntyCode();
                    $territory->libcog = null;
                } else {
                    $territory->codeiso2 = null;
                    $territory->libcog = $oneCountry->getCntyName();
                }
                $result = $odTerritories->find($territory);
                if (is_array($result) && count($result) == 1) {
                    $oneCountry
                        ->setCntyCode($result[0]->codeiso2)
                        ->setCntyIso2($result[0]->codeiso2)
                        ->setCntyIso3($result[0]->codeiso3)
                        ->setCntyCog($result[0]->cog)
                        ->setCntyNum($result[0]->codenum3)
                    ;
                    if ($oneCountry->getCntyCog() == 'XXXXX') {
                        $oneCountry->setCntyCog('99100');
                    }
                    $oneCountry->save();
                } else {
                    $territory->actual = null;
                    $result = $odTerritories->find($territory);
                    if (is_array($result) && count($result) == 1) {
                        $oneCountry
                            ->setCntyCode($result[0]->codeiso2)
                            ->setCntyIso2($result[0]->codeiso2)
                            ->setCntyIso3($result[0]->codeiso3)
                            ->setCntyCog($result[0]->cog)
                            ->setCntyNum($result[0]->codenum3)
                        ;
                        if ($oneCountry->getCntyCog() == 'XXXXX') {
                            $oneCountry->setCntyCog('99100');
                        }
                        $oneCountry->save();
                    } else {
                        echo $oneCountry->getCntyName() . ' not found !' . PHP_EOL;
                    }
                }
            }
        }
        
        
        $p_output->write("Fin de la gestion", true);
    }
}
