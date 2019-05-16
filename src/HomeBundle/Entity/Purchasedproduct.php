<?php

namespace HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Purchasedproduct
 *
 * @ORM\Table(name="purchasedProduct", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="product_id", columns={"product_id"})})
 * @ORM\Entity
 */
class Purchasedproduct
{
    /**
     * @var integer
     *
     * @ORM\Column(name="purchasedProduct_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $purchasedproductId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="purchasedProduct_date", type="datetime", nullable=false)
     */
    private $purchasedproductDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="purchasedProduct_amount", type="integer", nullable=false)
     */
    private $purchasedproductAmount;

    /**
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="product_id")
     * })
     */
    private $product;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;



    /**
     * Get purchasedproductId
     *
     * @return integer
     */
    public function getPurchasedproductId()
    {
        return $this->purchasedproductId;
    }

    /**
     * Set purchasedproductDate
     *
     * @param \DateTime $purchasedproductDate
     *
     * @return Purchasedproduct
     */
    public function setPurchasedproductDate($purchasedproductDate)
    {
        $this->purchasedproductDate = $purchasedproductDate;

        return $this;
    }

    /**
     * Get purchasedproductDate
     *
     * @return \DateTime
     */
    public function getPurchasedproductDate()
    {
        return $this->purchasedproductDate;
    }

    /**
     * Set purchasedproductAmount
     *
     * @param integer $purchasedproductAmount
     *
     * @return Purchasedproduct
     */
    public function setPurchasedproductAmount($purchasedproductAmount)
    {
        $this->purchasedproductAmount = $purchasedproductAmount;

        return $this;
    }

    /**
     * Get purchasedproductAmount
     *
     * @return integer
     */
    public function getPurchasedproductAmount()
    {
        return $this->purchasedproductAmount;
    }

    /**
     * Set product
     *
     * @param \HomeBundle\Entity\Product $product
     *
     * @return Purchasedproduct
     */
    public function setProduct(\HomeBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \HomeBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set user
     *
     * @param \HomeBundle\Entity\User $user
     *
     * @return Purchasedproduct
     */
    public function setUser(\HomeBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \HomeBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
