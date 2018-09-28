<?php
namespace FreeFW\Validators;

/**
 * Validateur de numéro de téléphone
 * @author jeromeklam
 */
class PhoneNumber extends \FreeFW\Validators\AbstractValidator
{

    /**
     * Numéro de téléphone
     * @var unknown
     */
    protected $value = null;

    /**
     * Constructeur
     *
     * @param string $p_value
     */
    public function __construct($p_value)
    {
        $this->value = $p_value;
    }

    /**
     * Numéro au format E.164
     *
     * @return string|boolean
     */
    public function getAsE164()
    {
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $numberProto = $phoneUtil->parse($p_object, "FR");
            return $numberProto;
        } catch (\libphonenumber\NumberParseException $e) {
            return false;
        }
    }

    /**
     * Retourne le numéro au format internationnal
     *
     * @return string|boolean
     */
    public function getAsInternationalNumber()
    {
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $numberProto = $phoneUtil->parse($this->value, "FR");
            return $phoneUtil->format($numberProto, \libphonenumber\PhoneNumberFormat::INTERNATIONAL);
        } catch (\libphonenumber\NumberParseException $e) {
            return false;
        }
    }

    /**
     * Validation du numéro
     *
     * @param mixed $p_object
     *
     * @return boolean
     */
    public static function validate($p_object)
    {
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $numberProto = $phoneUtil->parse($p_object, "FR");
            return true;
        } catch (\libphonenumber\NumberParseException $e) {
            return false;
        }
    }
}
