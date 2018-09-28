<?php
namespace FreeFW\Tools\Vad\Tipi;

/**
 *
 * @author jeromeklam
 *
 */
class Tools {

    /**
     * Retourne l'identifiant
     *
     * @param string $brk_key
     * @param string $user_id
     * @param string $p_facture_id
     * @param string $p_nofacture
     *
     * @return string
     */
    public static function getObject($brk_key, $user_id, $p_facture_id, $p_nofacture)
    {
        $cmd =
            'UID' . $user_id .
            'FID' . $p_facture_id .
            'BRK' . $brk_key .
            'NOF' . strtolower($p_nofacture)
        ;
        return $cmd;
    }

    /**
     * Retourne tous les paiements d'une facture
     *
     * @param string $brk_key
     * @param string $p_facture_id
     *
     * @return Iterable
     */
    public static function findPayments($brk_key, $p_facture_id)
    {
        $pattern  = '%FID' . $p_facture_id . 'BRK' . strtolower(str_replace('-', 'ZZ', $brk_key)) . 'NOF%';
        $payments = \FreeFW\Admin\Model\Vad::find(
            [
                'vad_objet' => [\FreeFW\Admin\Model\Vad::FIND_LIKE => $pattern]
            ]
        );
        return $payments;
    }
}
