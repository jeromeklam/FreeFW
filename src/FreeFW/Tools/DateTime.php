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
class DateTime
{

    /**
     * Get EN month
     *
     * @param integer $p_month
     * 
     * @return string
     */
    public static function getENMonth($p_month)
    {
        switch ($p_month) {
            case 1:
                return 'january';
            case 2:
                return 'february';
            case 3:
                return 'march';
            case 4:
                return 'april';
            case 5:
                return 'may';
            case 6:
                return 'june';
            case 7:
                return 'july';
            case 8:
                return 'august';
            case 9:
                return 'september';
            case 10:
                return 'october';
            case 11:
                return 'november';
            case 12:
                return 'december';
            default:
                return '';
        }
    }

    /**
     * Get FR month
     *
     * @param integer $p_month
     * 
     * @return string
     */
    public static function getFRMonth($p_month)
    {
        switch ($p_month) {
            case 1:
                return 'janvier';
            case 2:
                return 'février';
            case 3:
                return 'mars';
            case 4:
                return 'avril';
            case 5:
                return 'mai';
            case 6:
                return 'juin';
            case 7:
                return 'juillet';
            case 8:
                return 'août';
            case 9:
                return 'septembre';
            case 10:
                return 'octobre';
            case 11:
                return 'novembre';
            case 12:
                return 'décembre';
            default:
                return '';
        }
    }

    /**
     * As FR string
     *
     * @return string
     */
    public static function toFRLetter(\DateTime $p_datetime, $p_add_day = true)
    {
        $date  = '';
        $day   = intval($p_datetime->format('d'));
        $month = intval($p_datetime->format('m'));
        $year  = intval($p_datetime->format('Y'));
        if ($p_add_day) {
            if ($day == 1) {
                $date = '1er';
            } else {
                $date = '' . $day;
            }
            $date .= ' ' . self::getFRMonth($month) . ' ' . $year;
        } else {
            $date = self::getFRMonth($month) . ' ' . $year;
        }
        return $date;
    }

    /**
     * As EN string
     *
     * @return string
     */
    public static function toENLetter(\DateTime $p_datetime, $p_add_day = true)
    {
        $day   = intval($p_datetime->format('d'));
        $month = intval($p_datetime->format('m'));
        $year  = intval($p_datetime->format('Y'));
        if ($p_add_day) {
            $date  = self::getENMonth($month) . ' ' . $day . ', ' . $year;
        } else {
            $date  = self::getENMonth($month) . ' ' . $year;
        }
        return $date;
    }
}
