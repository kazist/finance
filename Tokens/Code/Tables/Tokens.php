<?php

namespace Finance\Tokens\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tokens
 *
 * @ORM\Table(name="finance_tokens", indexes={@ORM\Index(name="type_id_index", columns={"type_id"}), @ORM\Index(name="used_by_index", columns={"used_by"}), @ORM\Index(name="created_by_index", columns={"created_by"}), @ORM\Index(name="modified_by_index", columns={"modified_by"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Tokens extends \Kazist\Table\BaseTable
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
     * @ORM\Column(name="type_id", type="integer", length=11, nullable=true)
     */
    protected $type_id;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, nullable=false)
     */
    protected $token;

    /**
     * @var integer
     *
     * @ORM\Column(name="used", type="integer", length=11, nullable=true)
     */
    protected $used;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_used", type="datetime", nullable=true)
     */
    protected $date_used;

    /**
     * @var integer
     *
     * @ORM\Column(name="used_by", type="integer", length=11, nullable=true)
     */
    protected $used_by;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer", length=11, nullable=false)
     */
    protected $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="unique_name", type="string", length=255, nullable=false)
     */
    protected $unique_name;

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
     * Set type_id
     *
     * @param integer $typeId
     * @return Tokens
     */
    public function setTypeId($typeId)
    {
        $this->type_id = $typeId;

        return $this;
    }

    /**
     * Get type_id
     *
     * @return integer 
     */
    public function getTypeId()
    {
        return $this->type_id;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Tokens
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set used
     *
     * @param integer $used
     * @return Tokens
     */
    public function setUsed($used)
    {
        $this->used = $used;

        return $this;
    }

    /**
     * Get used
     *
     * @return integer 
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * Set date_used
     *
     * @param \DateTime $dateUsed
     * @return Tokens
     */
    public function setDateUsed($dateUsed)
    {
        $this->date_used = $dateUsed;

        return $this;
    }

    /**
     * Get date_used
     *
     * @return \DateTime 
     */
    public function getDateUsed()
    {
        return $this->date_used;
    }

    /**
     * Set used_by
     *
     * @param integer $usedBy
     * @return Tokens
     */
    public function setUsedBy($usedBy)
    {
        $this->used_by = $usedBy;

        return $this;
    }

    /**
     * Get used_by
     *
     * @return integer 
     */
    public function getUsedBy()
    {
        return $this->used_by;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return Tokens
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set unique_name
     *
     * @param string $uniqueName
     * @return Tokens
     */
    public function setUniqueName($uniqueName)
    {
        $this->unique_name = $uniqueName;

        return $this;
    }

    /**
     * Get unique_name
     *
     * @return string 
     */
    public function getUniqueName()
    {
        return $this->unique_name;
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
