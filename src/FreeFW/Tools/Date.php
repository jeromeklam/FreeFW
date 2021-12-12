<?php
/**
 * Utilitaires Dates
 *
 * @author jeromeklam
 * @package Date
 * @category Tools
 */
namespace FreeFW\Tools;

/**
 * Classe de gestion des dates
 * @author jeromeklam
 */
class Date
{

    /**
     * String to mysql datetime
     *
     * @param string $p_str
     *
     * @return string
     */
    public static function stringToMysql($p_str)
    {
        return date('Y-m-d H:i:s', strtotime($p_str));
    }

    /**
     * datetime to ISO8601
     *
     * @param string $p_date
     *
     * @return string
     */
    public static function stringToISO8601($p_date)
    {
        return date("c", strtotime($p_date));
    }

    /**
     * Return a datetime
     *
     * @param number $p_plus (minutes)
     *
     * @return string
     */
    public static function getServerDatetime($p_plus = null)
    {
        $datetime = new \Datetime();
        if ($p_plus !== null) {
            $datetime->add(new \DateInterval('PT'.$p_plus.'M'));
        }
        return $datetime;
    }

    /**
     * Return a datetime as string
     *
     * @return string
     */
    public static function getCurrentTS()
    {
        $datetime = new \Datetime();
        return $datetime->format('Y-m-d H:i:s.u');
    }

    /**
     * Return a datetime as string
     *
     * @param number $p_plus (minutes)
     *
     * @return string
     */
    public static function getCurrentTimestamp($p_plus = null)
    {
        $datetime = new \Datetime();
        if ($p_plus !== null) {
            if ($p_plus > 0) {
                $datetime->add(new \DateInterval('PT'.$p_plus.'M'));
            } else {
                $datetime->sub(new \DateInterval('PT'.(-1*$p_plus).'M'));
            }
        }
        return $datetime->format('Y-m-d H:i:s');
    }

    /**
     * Return a datetime
     *
     * @param number $p_plus (minutes)
     *
     * @return string
     */
    public static function getCurrentDate($p_plus = null)
    {
        $datetime = new \Datetime();
        if ($p_plus !== null) {
            if ($p_plus > 0) {
                $datetime->add(new \DateInterval('PT'.$p_plus.'M'));
            } else {
                $datetime->sub(new \DateInterval('PT'.(-1*$p_plus).'M'));
            }
        }
        return $datetime->format('Y-m-d');
    }

    /**
     * Conversion d'une date en DateTime
     *
     * @param string $p_date
     *
     * @return string
     */
    public static function ddmmyyyyToDateTime($p_date)
    {
        if ($p_date !== null && $p_date != '') {
            $format = 'd/m/Y H:i:s';
            $date = \DateTime::createFromFormat($format, $p_date);
            if ($date === false) {
                $format = 'd/m/Y H:i';
                $date = \DateTime::createFromFormat($format, $p_date);
            }
            if ($date === false) {
                $format = 'd/m/Y';
                $date = \DateTime::createFromFormat($format, $p_date);
            }
            return $date;
        }
        return null;
    }

    /**
     * Conversion d'une date en chaine
     *
     * @param string $p_date
     *
     * @return string
     */
    public static function ddmmyyyyToMysql($p_date)
    {
        if ($p_date !== null && $p_date != '') {
            $date = self::ddmmyyyyToDateTime($p_date);
            if ($date instanceof \DateTime) {
                return $date->format('Y-m-d H:i:s');
            }
        }
        return null;
    }

    /**
     * Affichage d'une date au format EU
     *
     * @param mixed   $p_date
     * @param boolean $p_withSeconds
     * @param boolean $p_withHour
     *
     * @return string|null
     */
    public static function mysqlToddmmyyyy($p_date, $p_withSeconds = false, $p_withHour = true)
    {
        $dt = self::mysqlToDatetime($p_date);
        if ($dt) {
            $format1 = 'Y-m-d H:i:s';
            $format2 = 'Y-m-d';
            if ($p_withSeconds && $p_withHour) {
                $oFormat = 'd/m/Y H:i:s';
            } else {
                if ($p_withHour) {
                    $oFormat = 'd/m/Y H:i';
                } else {
                    $oFormat = 'd/m/Y';
                }
            }
            if ($dt !== false) {
                return $dt->format($oFormat);
            }
        }
        return null;
    }

    /**
     * Conversion d'une date mysql en DateTime
     *
     * @param mixed $p_date
     *
     * @return \Datetime
     */
    public static function mysqlToDatetime($p_date)
    {
        $date = null;
        if ($p_date !== null && $p_date != '' && $p_date != '0000-00-00' && $p_date != '0000-00-00 00:00:00' ) {
            $format = 'Y-m-d\TH:i:sP';
            $date = \DateTime::createFromFormat($format, $p_date);
            if ($date === false) {
                $format = 'Y-m-d\TH:i:s.uT';
                $date = \DateTime::createFromFormat($format, $p_date);
                if ($date === false) {
                    $format = 'Y-m-d H:i:s';
                    $date = \DateTime::createFromFormat($format, $p_date);
                    if ($date === false) {
                        $format = 'Y-m-d H:i';
                        $date = \DateTime::createFromFormat($format, $p_date);
                        if ($date === false) {
                            $format = 'Y-m-d';
                            $date = \DateTime::createFromFormat($format, $p_date);
                            if ($date === false) {
                                $date = null;
                            }
                        }
                    }
                }
            }
        }
        if ($date) {
            $date->setTimezone(new \DateTimeZone('Europe/Paris'));
        }
        return $date;
    }

    /**
     * Converti un datetime en format de base
     *
     * @param \Datetime $p_date
     * @param boolean   $p_withSeconds
     * @param boolean   $p_withHour
     *
     * @return string
     */
    public static function datetimeToMysql($p_date, $p_withSeconds = false, $p_withHour = true)
    {
        $format = 'Y-m-d';
        if ($p_withSeconds && $p_withHour) {
            $format = 'Y-m-d H:i:s';
        } else {
            if ($p_withHour) {
                $format = 'Y-m-d H:i';
            }
        }
        return $p_date->format($format);
    }

    /**
     * Retourne le libellé d'un mois
     *
     * @param string  $p_month
     * @param string  $p_lang
     * @param boolean $p_html
     *
     * @return string
     */
    public static function getMonthAsString($p_month, $p_lang, $p_html = true)
    {
        $month = '';
        switch ($p_lang) {
            case \FreeFW\Constants::LANG_FR:
                switch ($p_month) {
                    case 1:
                        $month = 'Janvier';
                        break;
                    case 2:
                        $month = 'Février';
                        break;
                    case 3:
                        $month = 'Mars';
                        break;
                    case 4:
                        $month = 'Avril';
                        break;
                    case 5:
                        $month = 'Mai';
                        break;
                    case 6:
                        $month = 'Juin';
                        break;
                    case 7:
                        $month = 'Juillet';
                        break;
                    case 8:
                        $month = 'Août';
                        break;
                    case 9:
                        $month = 'Septembre';
                        break;
                    case 10:
                        $month = 'Octobre';
                        break;
                    case 11:
                        $month = 'Novembre';
                        break;
                    case 12:
                        $month = 'Décembre';
                        break;
                }
                break;
            default:
                break;
        }
        if ($p_html) {
            return htmlentities($month);
        }
        return $month;
    }

    /**
     * NbDays at now
     *
     * @param \DateTime $p_date
     * 
     * @return int
     */
    public static function nbDaysAtNow(\DateTime $p_date)
    {
        $now = new \DateTime();
        return $now->diff($p_date)->format("%r%a");
    }

    /**
     * NbHours at now
     *
     * @param \DateTime $p_date
     * 
     * @return int
     */
    public static function nbHoursAtNow(\DateTime $p_date)
    {
        $now  = new \DateTime();
        $diff = $now->diff($p_date);
        return $diff-> h + ($diff->days * 24);
    }
}
