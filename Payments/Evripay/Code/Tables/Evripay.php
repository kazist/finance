<?php

namespace Finance\Payments\Evripay\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evripay
 *
 * @ORM\Table(name="finance_payments_evripay", indexes={@ORM\Index(name="payment_id_index", columns={"payment_id"}), @ORM\Index(name="created_by_index", columns={"created_by"}), @ORM\Index(name="modified_by_index", columns={"modified_by"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Evripay extends \Kazist\Table\BaseTable {

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
     * @ORM\Column(name="card_status", type="string", length=255, nullable=false)
     */
    protected $card_status;

    /**
     * @var string
     *
     * @ORM\Column(name="merchant_reference", type="string", length=255, nullable=false)
     */
    protected $merchant_reference;

    /**
     * @var string
     *
     * @ORM\Column(name="bank_reference", type="string", length=255, nullable=true)
     */
    protected $bank_reference;

    /**
     * @var string
     *
     * @ORM\Column(name="transaction_data", type="text", nullable=true)
     */
    protected $transaction_data;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

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
     * @return Evripay
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
     * Set card_status
     *
     * @param string $cardStatus
     * @return Evripay
     */
    public function setCardStatus($cardStatus) {
        $this->card_status = $cardStatus;

        return $this;
    }

    /**
     * Get card_status
     *
     * @return string 
     */
    public function getCardStatus() {
        return $this->card_status;
    }

    /**
     * Set merchant_reference
     *
     * @param string $merchantReference
     * @return Evripay
     */
    public function setMerchantReference($merchantReference) {
        $this->merchant_reference = $merchantReference;

        return $this;
    }

    /**
     * Get merchant_reference
     *
     * @return string 
     */
    public function getMerchantReference() {
        return $this->merchant_reference;
    }

    /**
     * Set bank_reference
     *
     * @param string $bankReference
     * @return Evripay
     */
    public function setBankReference($bankReference) {
        $this->bank_reference = $bankReference;

        return $this;
    }

    /**
     * Get bank_reference
     *
     * @return string 
     */
    public function getBankReference() {
        return $this->bank_reference;
    }

    /**
     * Set transaction_data
     *
     * @param string $transactionData
     * @return Evripay
     */
    public function setTransactionData($transactionData) {
        $this->transaction_data = $transactionData;

        return $this;
    }

    /**
     * Get transaction_data
     *
     * @return string 
     */
    public function getTransactionData() {
        return $this->transaction_data;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Evripay
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
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
