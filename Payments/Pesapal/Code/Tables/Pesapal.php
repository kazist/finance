<?php

namespace Finance\Payments\Pesapal\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pesapal
 *
 * @ORM\Table(name="finance_payments_pesapal")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Pesapal extends \Kazist\Table\BaseTable
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
     * @ORM\Column(name="pesapal_merchant_reference", type="string", length=255, nullable=true)
     */
    protected $pesapal_merchant_reference;

    /**
     * @var string
     *
     * @ORM\Column(name="pesapal_notification_type", type="string", length=255, nullable=true)
     */
    protected $pesapal_notification_type;

    /**
     * @var string
     *
     * @ORM\Column(name="pesapal_transaction_tracking_id", type="string", length=255, nullable=true)
     */
    protected $pesapal_transaction_tracking_id;

    /**
     * @var string
     *
     * @ORM\Column(name="pesapal_status", type="string", length=255, nullable=true)
     */
    protected $pesapal_status;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", length=11, nullable=true)
     */
    protected $created_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=true)
     */
    protected $date_created;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer", length=11, nullable=true)
     */
    protected $modified_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modified", type="datetime", nullable=true)
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
     * Set pesapal_merchant_reference
     *
     * @param string $pesapalMerchantReference
     * @return Pesapal
     */
    public function setPesapalMerchantReference($pesapalMerchantReference)
    {
        $this->pesapal_merchant_reference = $pesapalMerchantReference;

        return $this;
    }

    /**
     * Get pesapal_merchant_reference
     *
     * @return string 
     */
    public function getPesapalMerchantReference()
    {
        return $this->pesapal_merchant_reference;
    }

    /**
     * Set pesapal_notification_type
     *
     * @param string $pesapalNotificationType
     * @return Pesapal
     */
    public function setPesapalNotificationType($pesapalNotificationType)
    {
        $this->pesapal_notification_type = $pesapalNotificationType;

        return $this;
    }

    /**
     * Get pesapal_notification_type
     *
     * @return string 
     */
    public function getPesapalNotificationType()
    {
        return $this->pesapal_notification_type;
    }

    /**
     * Set pesapal_transaction_tracking_id
     *
     * @param string $pesapalTransactionTrackingId
     * @return Pesapal
     */
    public function setPesapalTransactionTrackingId($pesapalTransactionTrackingId)
    {
        $this->pesapal_transaction_tracking_id = $pesapalTransactionTrackingId;

        return $this;
    }

    /**
     * Get pesapal_transaction_tracking_id
     *
     * @return string 
     */
    public function getPesapalTransactionTrackingId()
    {
        return $this->pesapal_transaction_tracking_id;
    }

    /**
     * Set pesapal_status
     *
     * @param string $pesapalStatus
     * @return Pesapal
     */
    public function setPesapalStatus($pesapalStatus)
    {
        $this->pesapal_status = $pesapalStatus;

        return $this;
    }

    /**
     * Get pesapal_status
     *
     * @return string 
     */
    public function getPesapalStatus()
    {
        return $this->pesapal_status;
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
