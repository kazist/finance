<?php

namespace Finance\Payments\Ipay\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ipay
 *
 * @ORM\Table(name="finance_payments_ipay", indexes={@ORM\Index(name="payment_id_index", columns={"payment_id"}), @ORM\Index(name="created_by_index", columns={"created_by"}), @ORM\Index(name="modified_by_index", columns={"modified_by"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Ipay extends \Kazist\Table\BaseTable {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="payment_id", type="integer", length=11, nullable=false)
     */
    protected $payment_id;

    /**
     * @var string
     *
     * @ORM\Column(name="item_id", type="string", length=255, nullable=false)
     */
    protected $item_id;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=false)
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(name="ivm", type="string", length=255, nullable=true)
     */
    protected $ivm;

    /**
     * @var string
     *
     * @ORM\Column(name="qwh", type="string", length=255, nullable=true)
     */
    protected $qwh;

    /**
     * @var string
     *
     * @ORM\Column(name="afd", type="string", length=255, nullable=true)
     */
    protected $afd;

    /**
     * @var string
     *
     * @ORM\Column(name="poi", type="string", length=255, nullable=true)
     */
    protected $poi;

    /**
     * @var string
     *
     * @ORM\Column(name="uyt", type="string", length=255, nullable=true)
     */
    protected $uyt;

    /**
     * @var string
     *
     * @ORM\Column(name="ifd", type="string", length=255, nullable=true)
     */
    protected $ifd;

    /**
     * @var string
     *
     * @ORM\Column(name="agd", type="string", length=255, nullable=true)
     */
    protected $agd;

    /**
     * @var string
     *
     * @ORM\Column(name="mc", type="string", length=255, nullable=true)
     */
    protected $mc;

    /**
     * @var string
     *
     * @ORM\Column(name="p1", type="string", length=255, nullable=true)
     */
    protected $p1;

    /**
     * @var string
     *
     * @ORM\Column(name="p2", type="string", length=255, nullable=true)
     */
    protected $p2;

    /**
     * @var string
     *
     * @ORM\Column(name="p3", type="string", length=255, nullable=true)
     */
    protected $p3;

    /**
     * @var string
     *
     * @ORM\Column(name="p4", type="string", length=255, nullable=true)
     */
    protected $p4;

    /**
     * @var string
     *
     * @ORM\Column(name="txncd", type="string", length=255, nullable=true)
     */
    protected $txncd;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", length=11, nullable=false)
     */
    protected $created_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=false)
     */
    protected $date_created;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer", length=11, nullable=false)
     */
    protected $modified_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modified", type="datetime", nullable=false)
     */
    protected $date_modified;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set payment_id
     *
     * @param integer $paymentId
     * @return Ipay
     */
    public function setPaymentId($paymentId) {
        $this->payment_id = $paymentId;

        return $this;
    }

    /**
     * Get payment_id
     *
     * @return integer 
     */
    public function getPaymentId() {
        return $this->payment_id;
    }

    /**
     * Set item_id
     *
     * @param string $itemId
     * @return Ipay
     */
    public function setItemId($itemId) {
        $this->item_id = $itemId;

        return $this;
    }

    /**
     * Get item_id
     *
     * @return string 
     */
    public function getItemId() {
        return $this->item_id;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Ipay
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set ivm
     *
     * @param string $ivm
     * @return Ipay
     */
    public function setIvm($ivm) {
        $this->ivm = $ivm;

        return $this;
    }

    /**
     * Get ivm
     *
     * @return string 
     */
    public function getIvm() {
        return $this->ivm;
    }

    /**
     * Set qwh
     *
     * @param string $qwh
     * @return Ipay
     */
    public function setQwh($qwh) {
        $this->qwh = $qwh;

        return $this;
    }

    /**
     * Get qwh
     *
     * @return string 
     */
    public function getQwh() {
        return $this->qwh;
    }

    /**
     * Set afd
     *
     * @param string $afd
     * @return Ipay
     */
    public function setAfd($afd) {
        $this->afd = $afd;

        return $this;
    }

    /**
     * Get afd
     *
     * @return string 
     */
    public function getAfd() {
        return $this->afd;
    }

    /**
     * Set poi
     *
     * @param string $poi
     * @return Ipay
     */
    public function setPoi($poi) {
        $this->poi = $poi;

        return $this;
    }

    /**
     * Get poi
     *
     * @return string 
     */
    public function getPoi() {
        return $this->poi;
    }

    /**
     * Set uyt
     *
     * @param string $uyt
     * @return Ipay
     */
    public function setUyt($uyt) {
        $this->uyt = $uyt;

        return $this;
    }

    /**
     * Get uyt
     *
     * @return string 
     */
    public function getUyt() {
        return $this->uyt;
    }

    /**
     * Set ifd
     *
     * @param string $ifd
     * @return Ipay
     */
    public function setIfd($ifd) {
        $this->ifd = $ifd;

        return $this;
    }

    /**
     * Get ifd
     *
     * @return string 
     */
    public function getIfd() {
        return $this->ifd;
    }

    /**
     * Set agd
     *
     * @param string $agd
     * @return Ipay
     */
    public function setAgd($agd) {
        $this->agd = $agd;

        return $this;
    }

    /**
     * Get agd
     *
     * @return string 
     */
    public function getAgd() {
        return $this->agd;
    }

    /**
     * Set mc
     *
     * @param string $mc
     * @return Ipay
     */
    public function setMc($mc) {
        $this->mc = $mc;

        return $this;
    }

    /**
     * Get mc
     *
     * @return string 
     */
    public function getMc() {
        return $this->mc;
    }

    /**
     * Set p1
     *
     * @param string $p1
     * @return Ipay
     */
    public function setP1($p1) {
        $this->p1 = $p1;

        return $this;
    }

    /**
     * Get p1
     *
     * @return string 
     */
    public function getP1() {
        return $this->p1;
    }

    /**
     * Set p2
     *
     * @param string $p2
     * @return Ipay
     */
    public function setP2($p2) {
        $this->p2 = $p2;

        return $this;
    }

    /**
     * Get p2
     *
     * @return string 
     */
    public function getP2() {
        return $this->p2;
    }

    /**
     * Set p3
     *
     * @param string $p3
     * @return Ipay
     */
    public function setP3($p3) {
        $this->p3 = $p3;

        return $this;
    }

    /**
     * Get p3
     *
     * @return string 
     */
    public function getP3() {
        return $this->p3;
    }

    /**
     * Set p4
     *
     * @param string $p4
     * @return Ipay
     */
    public function setP4($p4) {
        $this->p4 = $p4;

        return $this;
    }

    /**
     * Get p4
     *
     * @return string 
     */
    public function getP4() {
        return $this->p4;
    }

    /**
     * Set txncd
     *
     * @param string $txncd
     * @return Ipay
     */
    public function setTxncd($txncd) {
        $this->txncd = $txncd;

        return $this;
    }

    /**
     * Get txncd
     *
     * @return string 
     */
    public function getTxncd() {
        return $this->txncd;
    }

    /**
     * Get created_by
     *
     * @return integer 
     */
    public function getCreatedBy() {
        return $this->created_by;
    }

    /**
     * Get date_created
     *
     * @return \DateTime 
     */
    public function getDateCreated() {
        return $this->date_created;
    }

    /**
     * Get modified_by
     *
     * @return integer 
     */
    public function getModifiedBy() {
        return $this->modified_by;
    }

    /**
     * Get date_modified
     *
     * @return \DateTime 
     */
    public function getDateModified() {
        return $this->date_modified;
    }

}
