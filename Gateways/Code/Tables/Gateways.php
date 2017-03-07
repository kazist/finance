<?php

namespace Finance\Gateways\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gateways
 *
 * @ORM\Table(name="finance_gateways", indexes={@ORM\Index(name="currency_id_index", columns={"currency_id"}), @ORM\Index(name="created_by_index", columns={"created_by"}), @ORM\Index(name="modified_by_index", columns={"modified_by"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Gateways extends \Kazist\Table\BaseTable
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
     * @var string
     *
     * @ORM\Column(name="short_name", type="string", length=255, nullable=false)
     */
    protected $short_name;

    /**
     * @var string
     *
     * @ORM\Column(name="long_name", type="string", length=255, nullable=false)
     */
    protected $long_name;

    /**
     * @var integer
     *
     * @ORM\Column(name="currency_id", type="integer", length=11, nullable=true)
     */
    protected $currency_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="image", type="integer", length=11, nullable=true)
     */
    protected $image;

    /**
     * @var string
     *
     * @ORM\Column(name="input_label", type="string", length=255, nullable=true)
     */
    protected $input_label;

    /**
     * @var string
     *
     * @ORM\Column(name="input_instruction", type="text", nullable=true)
     */
    protected $input_instruction;

    /**
     * @var integer
     *
     * @ORM\Column(name="can_withdraw", type="integer", length=11, nullable=true)
     */
    protected $can_withdraw;

    /**
     * @var integer
     *
     * @ORM\Column(name="can_payment", type="integer", length=11, nullable=true)
     */
    protected $can_payment;

    /**
     * @var integer
     *
     * @ORM\Column(name="can_transfer", type="integer", length=11, nullable=true)
     */
    protected $can_transfer;

    /**
     * @var integer
     *
     * @ORM\Column(name="record_deposit", type="integer", length=11, nullable=true)
     */
    protected $record_deposit;

    /**
     * @var string
     *
     * @ORM\Column(name="params", type="text", nullable=true)
     */
    protected $params;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_instruction", type="text", nullable=true)
     */
    protected $payment_instruction;

    /**
     * @var string
     *
     * @ORM\Column(name="transfer_instruction", type="text", nullable=true)
     */
    protected $transfer_instruction;

    /**
     * @var string
     *
     * @ORM\Column(name="withdraw_instruction", type="text", nullable=true)
     */
    protected $withdraw_instruction;

    /**
     * @var string
     *
     * @ORM\Column(name="withdraw_file_prefix", type="text", nullable=true)
     */
    protected $withdraw_file_prefix;

    /**
     * @var string
     *
     * @ORM\Column(name="withdraw_file_structure", type="text", nullable=true)
     */
    protected $withdraw_file_structure;

    /**
     * @var string
     *
     * @ORM\Column(name="withdraw_file_suffix", type="text", nullable=true)
     */
    protected $withdraw_file_suffix;

    /**
     * @var integer
     *
     * @ORM\Column(name="withdraw_limit", type="integer", length=11, nullable=true)
     */
    protected $withdraw_limit;

    /**
     * @var string
     *
     * @ORM\Column(name="file_type", type="string", length=255, nullable=true)
     */
    protected $file_type;

    /**
     * @var string
     *
     * @ORM\Column(name="file_limit", type="string", length=255, nullable=true)
     */
    protected $file_limit;

    /**
     * @var integer
     *
     * @ORM\Column(name="published", type="integer", length=11, nullable=true)
     */
    protected $published;

    /**
     * @var integer
     *
     * @ORM\Column(name="ordering", type="integer", length=11, nullable=true)
     */
    protected $ordering;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    protected $url;

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
     * Set short_name
     *
     * @param string $shortName
     * @return Gateways
     */
    public function setShortName($shortName)
    {
        $this->short_name = $shortName;

        return $this;
    }

    /**
     * Get short_name
     *
     * @return string 
     */
    public function getShortName()
    {
        return $this->short_name;
    }

    /**
     * Set long_name
     *
     * @param string $longName
     * @return Gateways
     */
    public function setLongName($longName)
    {
        $this->long_name = $longName;

        return $this;
    }

    /**
     * Get long_name
     *
     * @return string 
     */
    public function getLongName()
    {
        return $this->long_name;
    }

    /**
     * Set currency_id
     *
     * @param integer $currency_id
     * @return Gateways
     */
    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;

        return $this;
    }

    /**
     * Get currency_id
     *
     * @return integer 
     */
    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    /**
     * Set image
     *
     * @param integer $image
     * @return Gateways
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return integer 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set input_label
     *
     * @param string $inputLabel
     * @return Gateways
     */
    public function setInputLabel($inputLabel)
    {
        $this->input_label = $inputLabel;

        return $this;
    }

    /**
     * Get input_label
     *
     * @return string 
     */
    public function getInputLabel()
    {
        return $this->input_label;
    }

    /**
     * Set input_instruction
     *
     * @param string $inputInstruction
     * @return Gateways
     */
    public function setInputInstruction($inputInstruction)
    {
        $this->input_instruction = $inputInstruction;

        return $this;
    }

    /**
     * Get input_instruction
     *
     * @return string 
     */
    public function getInputInstruction()
    {
        return $this->input_instruction;
    }

    /**
     * Set can_withdraw
     *
     * @param integer $canWithdraw
     * @return Gateways
     */
    public function setCanWithdraw($canWithdraw)
    {
        $this->can_withdraw = $canWithdraw;

        return $this;
    }

    /**
     * Get can_withdraw
     *
     * @return integer 
     */
    public function getCanWithdraw()
    {
        return $this->can_withdraw;
    }

    /**
     * Set can_payment
     *
     * @param integer $canPayment
     * @return Gateways
     */
    public function setCanPayment($canPayment)
    {
        $this->can_payment = $canPayment;

        return $this;
    }

    /**
     * Get can_payment
     *
     * @return integer 
     */
    public function getCanPayment()
    {
        return $this->can_payment;
    }

    /**
     * Set can_transfer
     *
     * @param integer $canTransfer
     * @return Gateways
     */
    public function setCanTransfer($canTransfer)
    {
        $this->can_transfer = $canTransfer;

        return $this;
    }

    /**
     * Get can_transfer
     *
     * @return integer 
     */
    public function getCanTransfer()
    {
        return $this->can_transfer;
    }

    /**
     * Set record_deposit
     *
     * @param integer $recordDeposit
     * @return Gateways
     */
    public function setRecordDeposit($recordDeposit)
    {
        $this->record_deposit = $recordDeposit;

        return $this;
    }

    /**
     * Get record_deposit
     *
     * @return integer 
     */
    public function getRecordDeposit()
    {
        return $this->record_deposit;
    }

    /**
     * Set params
     *
     * @param string $params
     * @return Gateways
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
     * Set payment_instruction
     *
     * @param string $paymentInstruction
     * @return Gateways
     */
    public function setPaymentInstruction($paymentInstruction)
    {
        $this->payment_instruction = $paymentInstruction;

        return $this;
    }

    /**
     * Get payment_instruction
     *
     * @return string 
     */
    public function getPaymentInstruction()
    {
        return $this->payment_instruction;
    }

    /**
     * Set transfer_instruction
     *
     * @param string $transferInstruction
     * @return Gateways
     */
    public function setTransferInstruction($transferInstruction)
    {
        $this->transfer_instruction = $transferInstruction;

        return $this;
    }

    /**
     * Get transfer_instruction
     *
     * @return string 
     */
    public function getTransferInstruction()
    {
        return $this->transfer_instruction;
    }

    /**
     * Set withdraw_instruction
     *
     * @param string $withdrawInstruction
     * @return Gateways
     */
    public function setWithdrawInstruction($withdrawInstruction)
    {
        $this->withdraw_instruction = $withdrawInstruction;

        return $this;
    }

    /**
     * Get withdraw_instruction
     *
     * @return string 
     */
    public function getWithdrawInstruction()
    {
        return $this->withdraw_instruction;
    }

    /**
     * Set withdraw_file_prefix
     *
     * @param string $withdrawFilePrefix
     * @return Gateways
     */
    public function setWithdrawFilePrefix($withdrawFilePrefix)
    {
        $this->withdraw_file_prefix = $withdrawFilePrefix;

        return $this;
    }

    /**
     * Get withdraw_file_prefix
     *
     * @return string 
     */
    public function getWithdrawFilePrefix()
    {
        return $this->withdraw_file_prefix;
    }

    /**
     * Set withdraw_file_structure
     *
     * @param string $withdrawFileStructure
     * @return Gateways
     */
    public function setWithdrawFileStructure($withdrawFileStructure)
    {
        $this->withdraw_file_structure = $withdrawFileStructure;

        return $this;
    }

    /**
     * Get withdraw_file_structure
     *
     * @return string 
     */
    public function getWithdrawFileStructure()
    {
        return $this->withdraw_file_structure;
    }

    /**
     * Set withdraw_file_suffix
     *
     * @param string $withdrawFileSuffix
     * @return Gateways
     */
    public function setWithdrawFileSuffix($withdrawFileSuffix)
    {
        $this->withdraw_file_suffix = $withdrawFileSuffix;

        return $this;
    }

    /**
     * Get withdraw_file_suffix
     *
     * @return string 
     */
    public function getWithdrawFileSuffix()
    {
        return $this->withdraw_file_suffix;
    }

    /**
     * Set withdraw_limit
     *
     * @param integer $withdrawLimit
     * @return Gateways
     */
    public function setWithdrawLimit($withdrawLimit)
    {
        $this->withdraw_limit = $withdrawLimit;

        return $this;
    }

    /**
     * Get withdraw_limit
     *
     * @return integer 
     */
    public function getWithdrawLimit()
    {
        return $this->withdraw_limit;
    }

    /**
     * Set file_type
     *
     * @param string $fileType
     * @return Gateways
     */
    public function setFileType($fileType)
    {
        $this->file_type = $fileType;

        return $this;
    }

    /**
     * Get file_type
     *
     * @return string 
     */
    public function getFileType()
    {
        return $this->file_type;
    }

    /**
     * Set file_limit
     *
     * @param string $fileLimit
     * @return Gateways
     */
    public function setFileLimit($fileLimit)
    {
        $this->file_limit = $fileLimit;

        return $this;
    }

    /**
     * Get file_limit
     *
     * @return string 
     */
    public function getFileLimit()
    {
        return $this->file_limit;
    }

    /**
     * Set published
     *
     * @param integer $published
     * @return Gateways
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return integer 
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set ordering
     *
     * @param integer $ordering
     * @return Gateways
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }

    /**
     * Get ordering
     *
     * @return integer 
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Gateways
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
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
