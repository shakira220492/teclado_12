<?php

namespace HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Selectedproduct
 *
 * @ORM\Table(name="selectedProduct", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="product_id", columns={"product_id"})})
 * @ORM\Entity
 */
class Selectedproduct
{
    /**
     * @var integer
     *
     * @ORM\Column(name="selectedProduct_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $selectedproductId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="selectedProduct_date", type="datetime", nullable=false)
     */
    private $selectedproductDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="selectedProduct_amount", type="integer", nullable=false)
     */
    private $selectedproductAmount;

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
     * Get selectedproductId
     *
     * @return integer
     */
    public function getSelectedproductId()
    {
        return $this->selectedproductId;
    }

    /**
     * Set selectedproductDate
     *
     * @param \DateTime $selectedproductDate
     *
     * @return Selectedproduct
     */
    public function setSelectedproductDate($selectedproductDate)
    {
        $this->selectedproductDate = $selectedproductDate;

        return $this;
    }

    /**
     * Get selectedproductDate
     *
     * @return \DateTime
     */
    public function getSelectedproductDate()
    {
        return $this->selectedproductDate;
    }

    /**
     * Set selectedproductAmount
     *
     * @param integer $selectedproductAmount
     *
     * @return Selectedproduct
     */
    public function setSelectedproductAmount($selectedproductAmount)
    {
        $this->selectedproductAmount = $selectedproductAmount;

        return $this;
    }

    /**
     * Get selectedproductAmount
     *
     * @return integer
     */
    public function getSelectedproductAmount()
    {
        return $this->selectedproductAmount;
    }

    /**
     * Set product
     *
     * @param \HomeBundle\Entity\Product $product
     *
     * @return Selectedproduct
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
     * @return Selectedproduct
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
