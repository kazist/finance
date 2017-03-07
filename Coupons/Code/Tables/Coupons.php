<?php

namespace Finance\Coupons\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Coupons
 *
 * @ORM\Table(name="finance_coupons", indexes={@ORM\Index(name="used_by_index", columns={"used_by"}), @ORM\Index(name="created_by_index", columns={"created_by"}), @ORM\Index(name="modified_by_index", columns={"modified_by"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Coupons extends \Kazist\Table\BaseTable {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="coupon", type="string", length=255, nullable=false)
     */
    protected $coupon;

    /**
     * @var integer
     *
     * @ORM\Column(name="used", type="integer", length=11, nullable=false)
     */
    protected $used;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_used", type="datetime", nullable=false)
     */
    protected $date_used;

    /**
     * @var integer
     *
     * @ORM\Column(name="used_by", type="integer", length=11, nullable=false)
     */
    protected $used_by;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer", length=11, nullable=false)
     */
    protected $amount;

    /**
     * @var integer
     *
     * @ORM\Column(name="used_once", type="integer", length=11, nullable=false)
     */
    protected $used_once;

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
     * Set coupon
     *
     * @param string $coupon
     * @return Coupons
     */
    public function setCoupon($coupon) {
        $this->coupon = $coupon;

        return $this;
    }

    /**
     * Get coupon
     *
     * @return string 
     */
    public function getCoupon() {
        return $this->coupon;
    }

    /**
     * Set used
     *
     * @param integer $used
     * @return Coupons
     */
    public function setUsed($used) {
        $this->used = $used;

        return $this;
    }

    /**
     * Get used
     *
     * @return integer 
     */
    public function getUsed() {
        return $this->used;
    }

    /**
     * Set date_used
     *
     * @param \DateTime $dateUsed
     * @return Coupons
     */
    public function setDateUsed($dateUsed) {
        $this->date_used = $dateUsed;

        return $this;
    }

    /**
     * Get date_used
     *
     * @return \DateTime 
     */
    public function getDateUsed() {
        return $this->date_used;
    }

    /**
     * Set used_by
     *
     * @param integer $usedBy
     * @return Coupons
     */
    public function setUsedBy($usedBy) {
        $this->used_by = $usedBy;

        return $this;
    }

    /**
     * Get used_by
     *
     * @return integer 
     */
    public function getUsedBy() {
        return $this->used_by;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return Coupons
     */
    public function setAmount($amount) {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * Set used_once
     *
     * @param integer $usedOnce
     * @return Coupons
     */
    public function setUsedOnce($usedOnce) {
        $this->used_once = $usedOnce;

        return $this;
    }

    /**
     * Get used_once
     *
     * @return integer 
     */
    public function getUsedOnce() {
        return $this->used_once;
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
