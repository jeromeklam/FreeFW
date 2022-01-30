<?php
namespace FreeFW\Tools\NumbersWords\Locale;

/**
 * Numbers_Words
 *
 * @author   jeromeklam
 */
class FrFr extends AbstractNumbersWordsLocale
{

    public function __construct()
    {
        $this
            ->setLocale(\FreeFW\Constants::LOCALE_FR)
            ->setDecimalPoint('.')
            ->setDefaultCurrency(\FreeFW\Constants::CURRENCY_EURO)
            ->setLang('French')
            ->setNativeLang('Français')
            ->setMinus('moins')
            ->setAnd('et')
            ->setPlural('s')
            ->setDash('-')
            ->setSeparator(' ')
            ->addCurrency(\FreeFW\Constants::CURRENCY_EURO, 'euro', 'euro-cent')
            ->addCurrency(\FreeFW\Constants::CURRENCY_DOLLAR, 'dollar', 'cent')
            ->addCurrency(\FreeFW\Constants::CURRENCY_CHF, 'franc', 'centimes')
            ->setDigits(array(
                0 => 'zéro',
                1 => 'un',
                2 => 'deux',
                3 => 'trois',
                4 => 'quatre',
                5 => 'cinq',
                6 => 'six',
                7 => 'sept',
                8 => 'huit',
                9 => 'neuf'
            ))
            ->setExponents(array(
                0 => '',
                3 => 'mille',
                6 => 'million',
                9 => 'milliard',
                12 => 'trillion',
                15 => 'quadrillion',
                18 => 'quintillion',
                21 => 'sextillion',
                24 => 'septillion',
                27 => 'octillion',
                30 => 'nonillion',
                33 => 'decillion',
                36 => 'undecillion',
                39 => 'duodecillion',
                42 => 'tredecillion',
                45 => 'quattuordecillion',
                48 => 'quindecillion',
                51 => 'sexdecillion',
                54 => 'septendecillion',
                57 => 'octodecillion',
                60 => 'novemdecillion',
                63 => 'vigintillion',
                66 => 'unvigintillion',
                69 => 'duovigintillion',
                72 => 'trevigintillion',
                75 => 'quattuorvigintillion',
                78 => 'quinvigintillion',
                81 => 'sexvigintillion',
                84 => 'septenvigintillion',
                87 => 'octovigintillion',
                90 => 'novemvigintillion',
                93 => 'trigintillion',
                96 => 'untrigintillion',
                99 => 'duotrigintillion',
                102 => 'trestrigintillion',
                105 => 'quattuortrigintillion',
                108 => 'quintrigintillion',
                111 => 'sextrigintillion',
                114 => 'septentrigintillion',
                117 => 'octotrigintillion',
                120 => 'novemtrigintillion',
                123 => 'quadragintillion',
                126 => 'unquadragintillion',
                129 => 'duoquadragintillion',
                132 => 'trequadragintillion',
                135 => 'quattuorquadragintillion',
                138 => 'quinquadragintillion',
                141 => 'sexquadragintillion',
                144 => 'septenquadragintillion',
                147 => 'octoquadragintillion',
                150 => 'novemquadragintillion',
                153 => 'quinquagintillion',
                156 => 'unquinquagintillion',
                159 => 'duoquinquagintillion',
                162 => 'trequinquagintillion',
                165 => 'quattuorquinquagintillion',
                168 => 'quinquinquagintillion',
                171 => 'sexquinquagintillion',
                174 => 'septenquinquagintillion',
                177 => 'octoquinquagintillion',
                180 => 'novemquinquagintillion',
                183 => 'sexagintillion',
                186 => 'unsexagintillion',
                189 => 'duosexagintillion',
                192 => 'tresexagintillion',
                195 => 'quattuorsexagintillion',
                198 => 'quinsexagintillion',
                201 => 'sexsexagintillion',
                204 => 'septensexagintillion',
                207 => 'octosexagintillion',
                210 => 'novemsexagintillion',
                213 => 'septuagintillion',
                216 => 'unseptuagintillion',
                219 => 'duoseptuagintillion',
                222 => 'treseptuagintillion',
                225 => 'quattuorseptuagintillion',
                228 => 'quinseptuagintillion',
                231 => 'sexseptuagintillion',
                234 => 'septenseptuagintillion',
                237 => 'octoseptuagintillion',
                240 => 'novemseptuagintillion',
                243 => 'octogintillion',
                246 => 'unoctogintillion',
                249 => 'duooctogintillion',
                252 => 'treoctogintillion',
                255 => 'quattuoroctogintillion',
                258 => 'quinoctogintillion',
                261 => 'sexoctogintillion',
                264 => 'septoctogintillion',
                267 => 'octooctogintillion',
                270 => 'novemoctogintillion',
                273 => 'nonagintillion',
                276 => 'unnonagintillion',
                279 => 'duononagintillion',
                282 => 'trenonagintillion',
                285 => 'quattuornonagintillion',
                288 => 'quinnonagintillion',
                291 => 'sexnonagintillion',
                294 => 'septennonagintillion',
                297 => 'octononagintillion',
                300 => 'novemnonagintillion',
                303 => 'centillion'
            ))
            ->setMiscNumbers(array(
                10=>'dix',      // 10
                    'onze',     // 11
                    'douze',    // 12
                    'treize',   // 13
                    'quatorze', // 14
                    'quinze',   // 15
                    'seize',    // 16
                20=>'vingt',    // 20
                30=>'trente',   // 30
                40=>'quarante', // 40
                50=>'cinquante',// 50
                60=>'soixante', // 60
               100=>'cent'      // 100
            ))
        ;
    }

    /**
     * The word for infinity.
     * @var string
     */
    protected $infinity = 'infini';

    /**
     * Découpage en groupe de 3
     *
     * @param mixed $p_num
     *
     * @return array
     */
    protected function splitNumber($p_num)
    {
        if (is_string($p_num)) {
            $ret    = array();
            $strlen = strlen($p_num);
            $first  = substr($p_num, 0, $strlen%3);
            preg_match_all('/\d{3}/', substr($p_num, $strlen%3, $strlen), $m);
            $ret =& $m[0];
            if ($first) {
                array_unshift($ret, $first);
            }
            return $ret;
        }
        return explode(' ', number_format($p_num, 0, '', ' '));
    }

    /**
     * Converti un groupe de 3 chiffre en toute lettre
     *
     * @param integer $p_num
     * @param boolean $p_last
     *
     * @return string
     */
    protected function showDigitsGroup($p_num, $p_last = false)
    {
        $ret = '';
        $e   = $p_num%10;                  // 001
        $d   = ($p_num-$e)%100/10;         // 010
        $s   = ($p_num-$d*10-$e)%1000/100; // 100s
        if ($s) {
            if ($s>1) {
                $ret .= $this->getDigit($s) . $this->getSeparator() . $this->getMiscNumber(100);
                if ($p_last && !$e && !$d) {
                    $ret .= $this->getPlural();
                }
            } else {
                $ret .= $this->getMiscNumber(100);
            }
            $ret .= $this->getSeparator();
        }
        if ($d) {
            // in the case of 1, the "ones" digit also must be processed
            if ($d==1) {
                if ($e<=6) {
                    $ret .= $this->getMiscNumber(10+$e);
                } else {
                    $ret .= $this->getMiscNumber(10) . '-' . $this->getDigit($e);
                }
                $e = 0;
            } else {
                if ($d>5) {
                    if ($d<8) {
                        $ret  .= $this->getMiscNumber(60);
                        $resto = $d*10+$e-60;
                        if ($e==1) {
                            $ret .= $this->getSeparator() . $this->getAnd() . $this->getSeparator();
                        } elseif ($resto) {
                            $ret .= $this->getDash();
                        }

                        if ($resto) {
                            $ret .= $this->showDigitsGroup($resto);
                        }
                        $e = 0;
                    } else {
                        $ret .= $this->getDigit(4) . $this->getDash() . $this->getMiscNumber(20);
                        $resto = $d*10+$e-80;
                        if ($resto) {
                            $ret .= $this->getDash();
                            $ret .= $this->showDigitsGroup($resto);
                            $e = 0;
                        } else {
                            $ret .= $this->getPlural();
                        }
                    }
                } else {
                    $ret .= $this->getMiscNumber($d*10);
                }
            }
        }
        if ($e) {
            if ($d) {
                if ($e==1) {
                    $ret .= $this->getSeparator() . $this->getAnd() . $this->getSeparator();
                } else {
                    $ret .= $this->getDash();
                }
            }
            $ret .= $this->getDigit($e);
        }
        $ret = rtrim($ret, $this->getSeparator());

        return $ret;
    }

    /**
     * Converti un nombre en toute lettre
     *
     * @param integer $p_num
     *
     * @return string
     */
    public function toWords($p_num = 0)
    {
        $ret = '';
        if (!$p_num || preg_match('/^-?0+$/', $p_num) || !preg_match('/^-?\d+$/', $p_num)) {
            return $this->getDigit(0);
        }
        if (substr($p_num, 0, 1) == '-') {
            $ret = $this->getMinus() . $this->getSeparator();
            $p_num = substr($p_num, 1);
        }
        if (strlen($p_num)>306) {
            return $ret . $this->_infinity;
        }
        $p_num = ltrim($p_num, '0');
        $num_groups = $this->splitNumber($p_num);
        $sizeof_numgroups = count($num_groups);
        foreach ($num_groups as $i => $number) {
            $pow = $sizeof_numgroups-$i;
            if ($number!='000') {
                if ($number!=1 || $pow!=2) {
                    $ret .= $this->showDigitsGroup($number, $i+1==$sizeof_numgroups||$pow>2).$this->getSeparator();
                }
                $ret .= $this->getExponent(($pow-1)*3);
                if ($pow>2 && $number>1) {
                    $ret .= $this->getPlural();
                }
                $ret .= $this->getSeparator();
            }
        }

        return rtrim($ret, $this->getSeparator());
    }

    /**
     * Converti un nombre en toute lettre
     *
     * @param integer $p_curr
     * @param integer $p_decimal
     * @param integer $p_fraction
     * @param integer $p_convertFraction
     *
     * @return string
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
                    $ret .= $this->getSeparator() . $curr_names[1][0] . 's';
                }
            } else {
                $ret .= $this->getSeparator() . $curr_names[1][0];
            }
        }
        return $ret;
    }
}
