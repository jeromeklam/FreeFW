<?php
namespace FreeFW\Tools\Vad\Tipi;

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
     * @var \FreeFW\Tools\Vad\Tipi\Response
     */
    protected static $instance = null;

    protected $numcli = null;

    protected $exer = null;

    protected $refdet = null;

    protected $objet = null;

    protected $montant = null;

    protected $mel = null;

    protected $saisie = null;

    /**
     * « P » payée ; « R » refusée
     * @var string
     */
    protected $resultrans = null;

    /**
     * Numéro d’autorisation délivré par le serveur d’autorisation et routé par le gestionnaire de télépaiement à TIPI
     * @var string
     */
    protected $numauto = null;

    /**
     * Date de la transaction du paiement CB : JJMMSSAA
     * @var string
     */
    protected $dattrans = null;

    /**
     * Heure de la transaction du paiement CB : HHMM
     * @var string
     */
    protected $heurtrans = null;

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
        if (($this->numcli = $p_request->getAttribute('numcli', '')) == '') {
            throw new \FreeFW\Tools\Vad\VadException('Le champ numcli est obligatoire !');
        }
        if (($this->exer = $p_request->getAttribute('exer', '')) == '') {
            throw new \FreeFW\Tools\Vad\VadException('Le champ exer est obligatoire !');
        }
        if (($this->refdet = $p_request->getAttribute('refdet', '')) == '') {
            throw new \FreeFW\Tools\Vad\VadException('Le champ refdet est obligatoire !');
        }
        if (($this->objet = $p_request->getAttribute('objet', '')) == '') {
            throw new \FreeFW\Tools\Vad\VadException('Le champ objet est obligatoire !');
        }
        if (($this->montant = $p_request->getAttribute('montant', null)) == null) {
            throw new \FreeFW\Tools\Vad\VadException('Le champ montant est obligatoire !');
        }
        if (($this->mel = $p_request->getAttribute('mel', '')) == '') {
            throw new \FreeFW\Tools\Vad\VadException('Le champ mel est obligatoire !');
        }
        if (($this->saisie = $p_request->getAttribute('saisie', '')) == '') {
            throw new \FreeFW\Tools\Vad\VadException('Le champ saisie est obligatoire !');
        }
        if (($this->resultrans = $p_request->getAttribute('resultrans', '')) == '') {
            throw new \FreeFW\Tools\Vad\VadException('Le champ resultrans est obligatoire !');
        }
        if (($this->numauto = $p_request->getAttribute('numauto', '')) == '') {
            throw new \FreeFW\Tools\Vad\VadException('Le champ numauto est obligatoire !');
        }
        if (($this->dattrans = $p_request->getAttribute('dattrans', '')) == '') {
            throw new \FreeFW\Tools\Vad\VadException('Le champ dattrans est obligatoire !');
        } else {
            if (strlen($this->dattrans) != 8) {
                throw new \FreeFW\Tools\Vad\VadException('Le champ dattrans est incorrect !');
            }
        }
        if (($this->heurtrans = $p_request->getAttribute('heurtrans', '')) == '') {
            throw new \FreeFW\Tools\Vad\VadException('Le champ heurtrans est obligatoire !');
        } else {
            if (strlen($this->heurtrans) != 4) {
                throw new \FreeFW\Tools\Vad\VadException('Le champ heurtrans est incorrect !');
            }
        }
        $this->real = json_encode($p_request->getAttributes());
    }

    /**
     * Retourne une instance
     *
     * @param ServerRequestInterface $request
     *
     * @return \FreeFW\Tools\Vad\Tipi\Response
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
        if (strlen($this->dattrans) == 8 && strlen($this->heurtrans) == 4) {
            $date = \DateTime::createFromFormat('dmYHi', $this->dattrans . $this->heurtrans);
            $vadDate = \FreeFW\Tools\Date::datetimeToMysql($date);
        }
        $vad = new \FreeFW\Admin\Model\Vad();
        $vad
            ->setVadCmd($this->refdet)
            ->setVadEmail($this->mel)
            ->setVadMnt($this->montant)
            ->setVadTransid($this->numauto)
            ->setVadObjet($this->objet)
            ->setVadType('TIPI')
            ->setVadTs(\FreeFW\Tools\Date::getCurrentTimestamp())
            ->setVadReal($this->real)
            ->setVadDate($vadDate)
        ;
        $p1 = strpos($this->objet, 'UID');
        $p2 = strpos($this->objet, 'FID');
        $p3 = strpos($this->objet, 'BRK');
        $p4 = strpos($this->objet, 'NOF');
        if ($p1 !== false && $p2 !== false && $p3 !== false && $p4 !== false) {
            $uid = intval(substr($this->objet, $p1+3, $p2-$p1+3));
            $fid = intval(substr($this->objet, $p2+3, $p3-$p2+3));
            $brk = intval(substr($this->objet, $p3+3, $p4-$p3+3));
            $vad
                ->setUserId($uid)
                ->setExternId($fid)
                ->setBrkId($brk)
            ;
        }
        if ($this->resultrans == 'P') {
            $vad->setVadtatus(\FreeFW\Admin\Model\Vad::STATUS_OK);
        } else {
            $vad->setVadtatus(\FreeFW\Admin\Model\Vad::STATUS_REFUSED);
        }
        return $vad;
    }
}
