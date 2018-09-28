<?php
namespace FreeFW\Tools\Vad\PayZen;

use Psr\Http\Message\ServerRequestInterface;

/**
 *
 * @author jeromeklam
 *
 */
class Response
{

    /**
     * Instance
     * @var \FreeFW\Tools\Vad\PayZen\Response
     */
    protected static $instance = null;

    /**
     * Statut de la transaction
     * @var string
     */
    protected $vads_trans_status = null;

    /**
     * CMD
     * @var string
     */
    protected $vads_order_id = null;

    /**
     * Infos payeur, ...
     * @var string
     */
    protected $vads_cust_id = null;

    /**
     * Email payeur, ...
     * @var string
     */
    protected $vads_cust_email = null;

    /**
     * Resultat
     * @var string
     */
    protected $vads_result = null;

    /**
     * Montant
     * @var string
     */
    protected $vads_amount = null;

    /**
     * Date de création
     * @var string
     */
    protected $vads_effective_creation_date = null;

    /**
     * Séquence
     * @var string
     */
    protected $vads_sequence_number = null;

    /**
     * All attributes...
     * @var string
     */
    protected $real = null;

    /**
     * Constructeur
     *
     * @param ServerRequestInterface $request
     */
    protected function __construct($p_request)
    {
        if (($this->vads_trans_status = $p_request->getAttribute('vads_trans_status', '')) == '') {
            throw new \FreeFW\Tools\Vad\VadException('Le champ vads_trans_status est obligatoire !');
        }
        if (($this->vads_order_id = $p_request->getAttribute('vads_order_id', '')) == '') {
            throw new \FreeFW\Tools\Vad\VadException('Le champ vads_order_id est obligatoire !');
        }
        if (($this->vads_cust_id = $p_request->getAttribute('vads_cust_id', '')) == '') {
            throw new \FreeFW\Tools\Vad\VadException('Le champ vads_cust_id est obligatoire !');
        }
        if (($this->vads_cust_email = $p_request->getAttribute('vads_cust_email', '')) == '') {
            throw new \FreeFW\Tools\Vad\VadException('Le champ vads_cust_email est obligatoire !');
        }
        if (($this->vads_result = $p_request->getAttribute('vads_result', '')) == '') {
            throw new \FreeFW\Tools\Vad\VadException('Le champ vads_result est obligatoire !');
        }
        if (($this->vads_amount = $p_request->getAttribute('vads_amount', '')) == '') {
            throw new \FreeFW\Tools\Vad\VadException('Le champ vads_amount est obligatoire !');
        }
        if (($this->vads_effective_creation_date = $p_request->getAttribute('vads_effective_creation_date', '')) == '') {
            throw new \FreeFW\Tools\Vad\VadException('Le champ vads_effective_creation_date est obligatoire !');
        }
        if (($this->vads_sequence_number = $p_request->getAttribute('vads_sequence_number', '')) == '') {
            throw new \FreeFW\Tools\Vad\VadException('Le champ vads_sequence_number est obligatoire !');
        }
        $this->real = json_encode($p_request->getAttributes());
    }

    /**
     * Retourne une instance
     *
     * @param ServerRequestInterface $request
     *
     * @return \FreeFW\Tools\Vad\PayZen\Response
     */
    public static function getInstance($p_request)
    {
        if (self::$instance === null) {
            self::$instance = new static($p_request);
        }
        return self::$instance;
    }

    /**
     * Retourne un modèle VAD
     *
     * @return \FreeFW\Admin\Model\Vad
     */
    public function getAsVadModel()
    {
        $vadDate = \FreeFW\Tools\Date::getCurrentTimestamp();
        if (strlen($this->vads_effective_creation_date) == 14) {
            $date    = \DateTime::createFromFormat('YmdHis', $this->vads_effective_creation_date);
            $vadDate = \FreeFW\Tools\Date::datetimeToMysql($date);
        }
        $vad = new \FreeFW\Admin\Model\Vad();
        $vad
            ->setVadCmd($this->vads_order_id)
            ->setVadEmail($this->vads_cust_email)
            ->setVadMnt($this->vads_amount)
            ->setVadTransid($this->vads_sequence_number)
            ->setVadObjet($this->vads_cust_id)
            ->setVadType('PAYZEN')
            ->setVadTs(\FreeFW\Tools\Date::getCurrentTimestamp())
            ->setVadReal($this->real)
            ->setVadDate($vadDate)
        ;
        $p1 = strpos($this->vads_cust_id, 'UID');
        $p2 = strpos($this->vads_cust_id, 'FID');
        $p3 = strpos($this->vads_cust_id, 'BRK');
        if ($p1 !== false && $p2 !== false && $p3 !== false) {
            $uid = intval(substr($this->vads_cust_id, $p1+3, $p2-$p1+3));
            $fid = intval(substr($this->vads_cust_id, $p2+3, $p3-$p2+3));
            $brk = intval(substr($this->vads_cust_id, $p3+3));
            $vad
                ->setUserId($uid)
                ->setExternId($fid)
                ->setBrkId($brk)
            ;
        }
        if ($this->vads_result == '00') {
            $vad->setVadtatus(\FreeFW\Admin\Model\Vad::STATUS_OK);
        } else {
            $vad->setVadtatus(\FreeFW\Admin\Model\Vad::STATUS_REFUSED);
        }
        return $vad;
    }
}
