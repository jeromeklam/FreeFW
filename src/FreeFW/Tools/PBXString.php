<?php
/**
 * Outils sur les chaines
 *
 * @author jeromeklam
 * @package String
 * @category Tools
 */
namespace FreeFW\Tools;

/**
 * Outils sur les chaines
 * @author jeromeklam
 */
class PBXString
{

    /**
     * Enter description here ...
     *
     * @var unknown_type
     */
    const REGEX_PARAM_PLACEHOLDER = '#\[\[:(.*?):\]\]#sim';

    /**
     * Parse et remplace suivant les marqueur
     *
     * @param string $p_string
     * @param array  $p_data
     * @param string $p_regex
     *
     * @return string
     */
    public static function parse($p_string, $p_data = array(), $p_regex = null)
    {
        if (! is_array($p_data)) {
            if (is_object($p_data) && method_exists($p_data, '__toArray')) {
                $datas = $p_data->__toArray();
            }
        } else {
            $datas = $p_data;
        }
        if ($p_regex === null) {
            $p_regex = self::REGEX_PARAM_PLACEHOLDER;
        }
        if (0 < preg_match_all($p_regex, $p_string, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $replace = '';
                if (isset($datas[$match[1]])) {
                    $replace = $datas[$match[1]];
                }
                $p_string = str_replace(
                    $match[0],
                    $replace,
                    $p_string
                );
            }

            return self::parse($p_string, $datas, $p_regex);
        }

        return $p_string;
    }

    /**
     * Conversion en CamelCase
     *
     * @param string  $p_str
     * @param boolean $p_first
     * @param string  $p_glue
     *
     * @return string
     */
    public static function toCamelCase($p_str, $p_first = false, $p_glue = '_')
    {
        $p_str = '' . trim($p_str);
        if ($p_str == '') {
            return $p_str;
        }
        if ($p_first) {
            $p_str[0] = strtoupper($p_str[0]);
        }
        return preg_replace_callback(
            "|{$p_glue}([a-z])|",
            function ($matches) use ($p_glue) {
                return str_replace($p_glue, '', strtoupper($matches[0]));
            },
            $p_str,
            24
        );
    }

    /**
     * Converti une chaine camelcase en format _
     *
     * @param string $p_str
     *
     * @return string
     */
    public static function fromCamelCase($p_str)
    {
        return self::hToSnakeCase($p_str);
    }

    /**
     * Converti en CamelCase
     *
     * @link https://en.wikipedia.org/wiki/CamelCase
     *
     * @param string $str
     * @return string
     */
    protected static function hToCamelCase($str)
    {
        return str_replace(
            ' ',
            '',
            ucwords(str_replace(array('-', '_'), ' ', $str))
        );
    }

    /**
     * Converti en snake-case
     * @link https://en.wikipedia.org/wiki/Snake_case
     *
     * @param string $str
     * @param string $delimiter
     *
     * @return string
     */
    protected static function hToSnakeCase($str, $delimiter = '_')
    {
        $str = lcfirst($str);
        $lowerCase = strtolower($str);
        $result = '';
        $length = strlen($str);
        for ($i = 0; $i < $length; $i++) {
            $result .= ($str[$i] === $lowerCase[$i] ? '' : $delimiter) . $lowerCase[$i];
        }
        return $result;
    }

    /**
     * Remove comments from string
     *
     *  @param string $output
     *
     *  @return string
     */
    public static function removeComments($output)
    {
        $lines  = explode("\n", $output);
        $output = "";
        // try to keep mem. use down
        $linecount  = count($lines);
        $in_comment = false;
        for ($i=0; $i<$linecount; $i++) {
            if (preg_match("/^\/\*/", $lines[$i])) {
                $in_comment = true;
            }
            if (!$in_comment) {
                $output .= $lines[$i] . "\n";
            }
            if (preg_match("/\*\/$/", $lines[$i])) {
                $in_comment = false;
            }
        }
        unset($lines);
        return $output;
    }

    /**
     * Split d'une chaine avec plusieurs requêtes séparées par ; en tableau
     *
     * @param string $p_sqlText
     *
     * @return array
     */
    public static function splitSql($p_sqlText)
    {
        $sqls = [];
        $p_sqlText = self::removeComments($p_sqlText);
        // Return array of ; terminated SQL statements in $sql_text.
        $re = '% # Match an SQL record ending with ";"
        \s*                                     # Discard leading whitespace.
        (                                       # $1: Trimmed non-empty SQL record.
          (?:                                   # Group for content alternatives.
            \'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\'  # Either a single quoted string,
          | "[^"\\\\]*(?:\\\\.[^"\\\\]*)*"      # or a double quoted string,
          | /*[^*]*\*+([^*/][^*]*\*+)*/         # or a multi-line comment,
          | \#.*                                # or a # single line comment,
          | --.*                                # or a -- single line comment,
          | [^"\';#]                            # or one non-["\';#-]
          )+                                    # One or more content alternatives
          (?:;|$)                               # Record end is a ; or string end.
        )                                       # End $1: Trimmed SQL record.
        %x';
        if (preg_match_all($re, $p_sqlText, $matches)) {
            $sqls = $matches[1];
        }
        return array_filter($sqls, function ($val) {return trim($val) !== '';} );
    }

    /**
     * Conversion en monétaire
     *
     * @param string $p_string
     * @param string $p_monn
     *
     * @return mixed
     */
    public static function toMonetary($p_string, $p_monn = '€')
    {
        if (is_numeric($p_string)) {
            return number_format($p_string, 2, ',', ' ') . ' ' . $p_monn;
        } else {
            return $p_string;
        }
    }

    /**
     * Transforme un json en liste
     *
     * @return string
     */
    public static function jsonToList($p_json)
    {
        $str = '';
        $arr = json_decode($p_json, true);
        foreach ($arr as $key => $value) {
            if ($str == '') {
                $str = $str . $key . '=' . $value;
            } else {
                $str = $str . ', ' . $key . '=' . $value;
            }
        }

        return $str;
    }

    /**
     * Determine if a given string matches a given pattern.
     *
     * @param string $p_pattern
     * @param string $p_value
     *
     * @return boolean
     */
    public static function is($p_pattern, $p_value)
    {
        if (is_array($p_value)) {
            $values = $p_value;
        } else {
            $values = [];
            $values[] = $p_value;
        }
        foreach ($values as $oneValue) {
            if ($p_pattern == $oneValue) {
                return true;
            }
            $p_pattern = preg_quote($p_pattern, '#');
            $p_pattern = str_replace('\*', '.*', $p_pattern);
            $match = (bool)preg_match('#^'.$p_pattern.'\z#u', $oneValue);
            if ($match) {
                return true;
            }
        }
        return false;
    }

    /**
     * String is UTF-8 ?
     *
     * @param string $p_string
     *
     * @return number
     */
    public static function isUtf8($p_string)
    {
        // From http://w3.org/International/questions/qa-forms-utf-8.html
        return preg_match('%^(?:
              [\x09\x0A\x0D\x20-\x7E]            # ASCII
            | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
            |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
            |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
            |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
            |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
        )*$%xs', $p_string);
    }

    /**
     * Converti une chaine en NS
     * NS.NS::Class
     *
     * @param string $p_str  Chaine à convertir
     * @param string $p_type Type de classe
     *
     * @return string
     */
    public static function toNs($p_str, $p_type)
    {
        $ns    = '';
        $parts = explode('::', $p_str);
        if (count($parts) != 2) {
            return false;
        }
        $start = str_replace('.', '\\', $parts[0]);
        return '\\' . $start . '\\' . self::toCamelCase($p_type, true) . '\\' . $parts[2];
    }

    /**
     * Hide part of string with a caracter
     *
     * @param string $p_string
     * @param string $p_replace
     * @param number $p_left
     * @param number $p_right
     *
     * @return string
     */
    public static function hidePart($p_string, $p_replace = 'X', $p_left = 4, $p_right = 4)
    {
        $len = strlen($p_string);
        $str = substr($p_string, 0, $p_left) .
            str_pad('', $len - $p_left - $p_right, $p_replace) .
            substr($p_string, $len - $p_right);
        return $str;
    }

    /**
     * Enlève les accents
     *
     * @param string $p_string
     *
     * @return mixed
     */
    public static function withoutAccent($p_string)
    {
        $a = array(
            'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð',
            'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã',
            'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ',
            'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ',
            'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę',
            'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī',
            'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ',
            'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ',
            'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š',
            'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű',
            'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ',
            'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ',
            'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί',
            'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή'

        );
        $b = array(
            'A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D',
            'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a',
            'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o',
            'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C',
            'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e',
            'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I',
            'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l',
            'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o',
            'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S',
            's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u',
            'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o',
            'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U',
            'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι',
            'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η'
        );
        return str_replace($a, $b, $p_string);
    }

    /**
     * Clean strange characters
     *
     * @param string $p_text
     *
     * @return string
     */
    public static function clean($p_text)
    {
        $p_text = str_replace("\'", "'", $p_text);
        return $p_text;
    }

    /**
     * COnvert html t simple text
     *
     * @param string $p_html
     *
     * @return string
     */
    public static function htmlToText($p_html, $p_preserve_cr = false)
    {
        $html = new \Html2Text\Html2Text($p_html, ['width' => 0]);
        return $html->getText();
    }

    /**
     * Permet de réduire un texte
     *
     * @param string $p_str
     * @param int $p_max_len
     * @param string $p_end_str (default '...')
     * @param bool $p_trunc_at_space (default true)
     * @param string $p_encoding (default null) (if null then mb_internal_encoding() is used)
     * @return string
     *
     * @example truncString('je suis un texte trop long',20) = 'je suis un texte...'
     */
    public static function truncString($p_str, $p_max_len, $p_end_str = '...', $p_trunc_at_space = true, $p_encoding = null)
    {
        /**
         * @var string $encoding
         * @var int $string_length
         * @var int $max_length
         */
        $encoding      = ($p_encoding ? $p_encoding : mb_internal_encoding());
        $string_length = mb_strlen($p_str, $encoding);
        $max_length    = $p_max_len - strlen($p_end_str);
        if ($string_length <= $max_length) {
            return $p_str;
        }
        if ($p_trunc_at_space && ($space_position = mb_strrpos($p_str, ' ', $max_length - $string_length, $encoding))) {
            $max_length = $space_position;
        }
        return mb_substr($p_str, 0, $max_length, $encoding) . $p_end_str;
    }

    /**
     * Return a sql regexp in lowercase
     *
     * @param string $p_str
     *
     * @return string
     */
    public static function toSqlRegexp($p_str)
    {
        $newStr = self::withoutAccent(strtolower($p_str));
        $newStr = str_replace('e', '[eéèêë]', $newStr);
        $newStr = str_replace('o', '[oöô]', $newStr);
        $newStr = str_replace('a', '[aàäâ]', $newStr);
        $newStr = str_replace('u', '[uüûù]', $newStr);
        $newStr = str_replace('i', '[iïî]', $newStr);
        return $newStr;
    }
}
