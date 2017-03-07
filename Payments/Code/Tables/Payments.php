<?php

namespace Finance\Payments\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Payments
 *
 * @ORM\Table(name="finance_payments", indexes={@ORM\Index(name="gateway_id_index", columns={"gateway_id"}), @ORM\Index(name="user_id_index", columns={"user_id"}), @ORM\Index(name="subset_id_index", columns={"subset_id"}), @ORM\Index(name="created_by_index", columns={"created_by"}), @ORM\Index(name="modified_by_index", columns={"modified_by"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Payments extends \Kazist\Table\BaseTable
{
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
     * @ORM\Column(name="gateway_id", type="integer", length=11, nullable=true)
     */
    protected $gateway_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", length=11, nullable=false)
     */
    protected $user_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="subset_id", type="integer", length=11, nullable=false)
     */
    protected $subset_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="item_id", type="integer", length=11, nullable=true)
     */
    protected $item_id;

    /**
     * @var string
     *
     * @ORM\Column(name="receipt_no", type="string", length=255, nullable=true)
     */
    protected $receipt_no;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="string", length=255, nullable=true)
     */
    protected $amount;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount_required", type="integer", length=11, nullable=true)
     */
    protected $amount_required;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount_paid", type="integer", length=11, nullable=true)
     */
    protected $amount_paid;

    /**
     * @var string
     *
     * @ORM\Column(name="quantity", type="string", length=255, nullable=true)
     */
    protected $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="params", type="text", nullable=true)
     */
    protected $params;

    /**
     * @var string
     *
     * @ORM\Column(name="deductions", type="text", nullable=true)
     */
    protected $deductions;

    /**
     * @var integer
     *
     * @ORM\Column(name="completed", type="integer", length=11, nullable=true)
     */
    protected $completed;

    /**
     * @var integer
     *
     * @ORM\Column(name="successful", type="integer", length=11, nullable=true)
     */
    protected $successful;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_canceled", type="integer", length=11, nullable=true)
     */
    protected $is_canceled;

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
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set gateway_id
     *
     * @param integer $gatewayId
     * @return Payments
     */
    public function setGatewayId($gatewayId)
    {
        $this->gateway_id = $gatewayId;

        return $this;
    }

    /**
     * Get gateway_id
     *
     * @return integer 
     */
    public function getGatewayId()
    {
        return $this->gateway_id;
    }

    /**
     * Set user_id
     *
     * @param integer $userId
     * @return Payments
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get user_id
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set subset_id
     *
     * @param integer $subsetId
     * @return Payments
     */
    public function setSubsetId($subsetId)
    {
        $this->subset_id = $subsetId;

        return $this;
    }

    /**
     * Get subset_id
     *
     * @return integer 
     */
    public function getSubsetId()
    {
        return $this->subset_id;
    }

    /**
     * Set item_id
     *
     * @param integer $itemId
     * @return Payments
     */
    public function setItemId($itemId)
    {
        $this->item_id = $itemId;

        return $this;
    }

    /**
     * Get item_id
     *
     * @return integer 
     */
    public function getItemId()
    {
        return $this->item_id;
    }

    /**
     * Set receipt_no
     *
     * @param string $receiptNo
     * @return Payments
     */
    public function setReceiptNo($receiptNo)
    {
        $this->receipt_no = $receiptNo;

        return $this;
    }

    /**
     * Get receipt_no
     *
     * @return string 
     */
    public function getReceiptNo()
    {
        return $this->receipt_no;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Payments
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Payments
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Payments
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set amount
     *
     * @param string $amount
     * @return Payments
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set amount_required
     *
     * @param integer $amountRequired
     * @return Payments
     */
    public function setAmountRequired($amountRequired)
    {
        $this->amount_required = $amountRequired;

        return $this;
    }

    /**
     * Get amount_required
     *
     * @return integer 
     */
    public function getAmountRequired()
    {
        return $this->amount_required;
    }

    /**
     * Set amount_paid
     *
     * @param integer $amountPaid
     * @return Payments
     */
    public function setAmountPaid($amountPaid)
    {
        $this->amount_paid = $amountPaid;

        return $this;
    }

    /**
     * Get amount_paid
     *
     * @return integer 
     */
    public function getAmountPaid()
    {
        return $this->amount_paid;
    }

    /**
     * Set quantity
     *
     * @param string $quantity
     * @return Payments
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return string 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set params
     *
     * @param string $params
     * @return Payments
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get params
     *
     * @return string 
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set deductions
     *
     * @param string $deductions
     * @return Payments
     */
    public function setDeductions($deductions)
    {
        $this->deductions = $deductions;

        return $this;
    }

    /**
     * Get deductions
     *
     * @return string 
     */
    public function getDeductions()
    {
        return $this->deductions;
    }

    /**
     * Set completed
     *
     * @param integer $completed
     * @return Payments
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * Get completed
     *
     * @return integer 
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * Set successful
     *
     * @param integer $successful
     * @return Payments
     */
    public function setSuccessful($successful)
    {
        $this->successful = $successful;

        return $this;
    }

    /**
     * Get successful
     *
     * @return integer 
     */
    public function getSuccessful()
    {
        return $this->successful;
    }

    /**
     * Set is_canceled
     *
     * @param integer $isCanceled
     * @return Payments
     */
    public function setIsCanceled($isCanceled)
    {
        $this->is_canceled = $isCanceled;

        return $this;
    }

    /**
     * Get is_canceled
     *
     * @return integer 
     */
    public function getIsCanceled()
    {
        return $this->is_canceled;
    }

    /**
     * Get created_by
     *
     * @return integer 
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Get date_created
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * Get modified_by
     *
     * @return integer 
     */
    public function getModifiedBy()
    {
        return $this->modified_by;
    }

    /**
     * Get date_modified
     *
     * @return \DateTime 
     */
    public function getDateModified()
    {
        return $this->date_modified;
    }
    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        // Add your code here
    }
}
