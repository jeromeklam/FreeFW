<?php
/**
 * Class for translating numbers into American English.
 *
 * @package  Tools
 * @author   jeromeklam
 */
namespace FreeFW\Tools\NumbersWords\Locale;

/**
 * Number
 * @author   jeromeklam
 */
class EnUs extends AbstractNumbersWordsLocale
{

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this
            ->setLocale(\FreeFW\Constants::LOCALE_US)
            ->setDecimalPoint('.')
            ->setDefaultCurrency(\FreeFW\Constants::CURRENCY_DOLLAR)
            ->setLang('American English')
            ->setNativeLang('American English')
            ->setMinus('minus')
            ->setAnd('and')
            ->setSeparator(' ')
            ->setPlural('s')
            ->addCurrency(\FreeFW\Constants::CURRENCY_EURO, 'euro', 'euro-cent')
            ->addCurrency(\FreeFW\Constants::CURRENCY_DOLLAR, 'dollar', 'cent')
            ->addCurrency(\FreeFW\Constants::CURRENCY_CHF, 'franc', 'centimes')
            ->setDigits(array(
                0 => 'zero',
                1 => 'one',
                2 => 'two',
                3 => 'three',
                4 => 'four',
                5 => 'five',
                6 => 'six',
                7 => 'seven',
                8 => 'eight',
                9 => 'nine'
            ))
            ->setExponents(array(
                0 => array(''),
                3 => array('thousand'),
                6 => array('million'),
                9 => array('billion'),
                12 => array('trillion'),
                15 => array('quadrillion'),
                18 => array('quintillion'),
                21 => array('sextillion'),
                24 => array('septillion'),
                27 => array('octillion'),
                30 => array('nonillion'),
                33 => array('decillion'),
                36 => array('undecillion'),
                39 => array('duodecillion'),
                42 => array('tredecillion'),
                45 => array('quattuordecillion'),
                48 => array('quindecillion'),
                51 => array('sexdecillion'),
                54 => array('septendecillion'),
                57 => array('octodecillion'),
                60 => array('novemdecillion'),
                63 => array('vigintillion'),
                66 => array('unvigintillion'),
                69 => array('duovigintillion'),
                72 => array('trevigintillion'),
                75 => array('quattuorvigintillion'),
                78 => array('quinvigintillion'),
                81 => array('sexvigintillion'),
                84 => array('septenvigintillion'),
                87 => array('octovigintillion'),
                90 => array('novemvigintillion'),
                93 => array('trigintillion'),
                96 => array('untrigintillion'),
                99 => array('duotrigintillion'),
                102 => array('trestrigintillion'),
                105 => array('quattuortrigintillion'),
                108 => array('quintrigintillion'),
                111 => array('sextrigintillion'),
                114 => array('septentrigintillion'),
                117 => array('octotrigintillion'),
                120 => array('novemtrigintillion'),
                123 => array('quadragintillion'),
                126 => array('unquadragintillion'),
                129 => array('duoquadragintillion'),
                132 => array('trequadragintillion'),
                135 => array('quattuorquadragintillion'),
                138 => array('quinquadragintillion'),
                141 => array('sexquadragintillion'),
                144 => array('septenquadragintillion'),
                147 => array('octoquadragintillion'),
                150 => array('novemquadragintillion'),
                153 => array('quinquagintillion'),
                156 => array('unquinquagintillion'),
                159 => array('duoquinquagintillion'),
                162 => array('trequinquagintillion'),
                165 => array('quattuorquinquagintillion'),
                168 => array('quinquinquagintillion'),
                171 => array('sexquinquagintillion'),
                174 => array('septenquinquagintillion'),
                177 => array('octoquinquagintillion'),
                180 => array('novemquinquagintillion'),
                183 => array('sexagintillion'),
                186 => array('unsexagintillion'),
                189 => array('duosexagintillion'),
                192 => array('tresexagintillion'),
                195 => array('quattuorsexagintillion'),
                198 => array('quinsexagintillion'),
                201 => array('sexsexagintillion'),
                204 => array('septensexagintillion'),
                207 => array('octosexagintillion'),
                210 => array('novemsexagintillion'),
                213 => array('septuagintillion'),
                216 => array('unseptuagintillion'),
                219 => array('duoseptuagintillion'),
                222 => array('treseptuagintillion'),
                225 => array('quattuorseptuagintillion'),
                228 => array('quinseptuagintillion'),
                231 => array('sexseptuagintillion'),
                234 => array('septenseptuagintillion'),
                237 => array('octoseptuagintillion'),
                240 => array('novemseptuagintillion'),
                243 => array('octogintillion'),
                246 => array('unoctogintillion'),
                249 => array('duooctogintillion'),
                252 => array('treoctogintillion'),
                255 => array('quattuoroctogintillion'),
                258 => array('quinoctogintillion'),
                261 => array('sexoctogintillion'),
                264 => array('septoctogintillion'),
                267 => array('octooctogintillion'),
                270 => array('novemoctogintillion'),
                273 => array('nonagintillion'),
                276 => array('unnonagintillion'),
                279 => array('duononagintillion'),
                282 => array('trenonagintillion'),
                285 => array('quattuornonagintillion'),
                288 => array('quinnonagintillion'),
                291 => array('sexnonagintillion'),
                294 => array('septennonagintillion'),
                297 => array('octononagintillion'),
                300 => array('novemnonagintillion'),
                303 => array('centillion'),
                309 => array('duocentillion'),
                312 => array('trecentillion'),
                366 => array('primo-vigesimo-centillion'),
                402 => array('trestrigintacentillion'),
                603 => array('ducentillion'),
                624 => array('septenducentillion'),
                2421 => array('sexoctingentillion'),
                3003 => array('millillion'),
                3000003 => array('milli-millillion')
            ))
        ;
    }

    /**
     * Converts a number to its word representation
     * in American English language
     *
     * @param integer $p_num
     * @param integer $p_power
     * @param integer $p_powsuffix
     *
     * @return string
     */
    public function toWords($p_num, $p_power = 0, $p_powsuffix = '')
    {
        $ret = '';
        // négatif ?
        if (substr($p_num, 0, 1) == '-') {
            $ret = $this->getSeparator() . $this->getMinus();
            $p_num = substr($p_num, 1);
        }
        // on enlève les chiffres inutiles de gauche
        $p_num = trim($p_num);
        $p_num = preg_replace('/^0+/', '', $p_num);
        if (strlen($p_num) > 3) {
            $maxp = strlen($p_num)-1;
            $curp = $maxp;
            for ($p = $maxp; $p > 0; --$p) {
                if ($this->hasExponent($p)) {
                    // send substr from $curp to $p
                    $snum = substr($p_num, $maxp - $curp, $curp - $p + 1);
                    $snum = preg_replace('/^0+/', '', $snum);
                    if ($snum !== '') {
                        $exponent  = $this->getExponent($p_power);
                        $cursuffix = $exponent[count($exponent)-1];
                        if ($p_powsuffix != '') {
                            $cursuffix .= $this->getSeparator() . $p_powsuffix;
                        }
                        $ret .= $this->toWords($snum, $p, $cursuffix);
                    }
                    $curp = $p - 1;
                    continue;
                }
            }
            $p_num = substr($p_num, $maxp - $curp, $curp - $p + 1);
            if ($p_num == 0) {
                return $ret;
            }
        } else {
            if ($p_num == 0 || $p_num == '') {
                return $this->getSeparator() . $this->getDigit(0);
            }
        }
        $h = $t = $d = 0;
        switch (strlen($p_num)) {
            case 3:
                $h = (int)substr($p_num, -3, 1);
                // no break
            case 2:
                $t = (int)substr($p_num, -2, 1);
                // no break
            case 1:
                $d = (int)substr($p_num, -1, 1);
                break;
            case 0:
                return;
                break;
        }
        if ($h) {
            $ret .= $this->getSeparator() . $this->getDigit($h) . $this->getSeparator() . 'hundred';
        }
        switch ($t) {
            case 9:
            case 7:
            case 6:
                $ret .= $this->getSeparator() . $this->getDigit($t) . 'ty';
                break;
            case 8:
                $ret .= $this->getSeparator() . 'eighty';
                break;
            case 5:
                $ret .= $this->getSeparator() . 'fifty';
                break;
            case 4:
                $ret .= $this->getSeparator() . 'forty';
                break;
            case 3:
                $ret .= $this->getSeparator() . 'thirty';
                break;
            case 2:
                $ret .= $this->getSeparator() . 'twenty';
                break;
            case 1:
                switch ($d) {
                    case 0:
                        $ret .= $this->getSeparator() . 'ten';
                        break;
                    case 1:
                        $ret .= $this->getSeparator() . 'eleven';
                        break;
                    case 2:
                        $ret .= $this->getSeparator() . 'twelve';
                        break;
                    case 3:
                        $ret .= $this->getSeparator() . 'thirteen';
                        break;
                    case 4:
                    case 6:
                    case 7:
                    case 9:
                        $ret .= $this->getSeparator() . $this->getDigit($d) . 'teen';
                        break;
                    case 5:
                        $ret .= $this->getSeparator() . 'fifteen';
                        break;
                    case 8:
                        $ret .= $this->getSeparator() . 'eighteen';
                        break;
                }
                break;
        }
        if ($t != 1 && $d > 0) {
            if ($t > 1) {
                $ret .= '-' . $this->getDigit($d);
            } else {
                $ret .= $this->getSeparator() . $this->getDigit($d);
            }
        }
        if ($p_power > 0) {
            if ($this->hasExponent($p_power)) {
                $lev = $this->getExponent($p_power);
            }
            if (!isset($lev) || !is_array($lev)) {
                return null;
            }
            $ret .= $this->getSeparator() . $lev[0];
        }
        if ($p_powsuffix != '') {
            $ret .= $this->getSeparator() . $p_powsuffix;
        }

        return $ret;
    }

    /**
     * Converts a currency value to its word representation
     * (with monetary units) in English language
     *
     * @param string  $p_curr
     * @param integer $p_decimal          A money total amount without fraction part (e.g. amount of dollars)
     * @param integer $p_fraction         Fractional part of the money amount (e.g. amount of cents)
     *                                  Optional. Defaults to false.
     * @param integer $p_convertFraction Convert fraction to words (left as numeric if set to false).
     *                                  Optional. Defaults to true.
     *
     * @return string  The corresponding word representation for the currency
     *
     * @access public
     * @author Piotr Klaban <makler@man.torun.pl>
     * @since  Numbers_Words 0.4
     */
    public function toCurrencyWords($p_curr, $p_decimal, $p_fraction = false, $p_convertFraction = true)
    {
        $p_curr = strtoupper($p_curr);
        if ($p_curr == '') {
            $p_curr = $this->getDefaultCurrency();
        }
        $curr_names = $this->getCurrencyName($p_curr);
        $ret = trim($this->toWords($p_decimal));
        $lev = ($p_decimal == 1) ? 0 : 1;
        if ($lev > 0) {
            if (count($curr_names[0]) > 1) {
                $ret .= $this->getSeparator() . $curr_names[0][$lev];
            } else {
                $ret .= $this->getSeparator() . $curr_names[0][0] . $this->getPlural();
            }
        } else {
            $ret .= $this->getSeparator() . $curr_names[0][0];
        }
        if ($p_fraction !== false) {
            if ($p_convertFraction) {
                $ret .= $this->getSeparator() . $this->getAnd() . $this->getSeparator() .
                        trim($this->toWords($p_fraction));
            } else {
                $ret .= $this->getSeparator() . $this->getAnd() . $this->getSeparator() . $p_fraction;
            }
            $lev = ($p_fraction == 1) ? 0 : 1;
            if ($lev > 0) {
                if (count($curr_names[1]) > 1) {
                    $ret .= $this->getSeparator() . $curr_names[1][$lev];
                } else {
                    $ret .= $this->getSeparator() . $curr_names[1][0] . $this->getPlural();
                }
            } else {
                $ret .= $this->getSeparator() . $curr_names[1][0];
            }
        }
        return $ret;
    }
}
