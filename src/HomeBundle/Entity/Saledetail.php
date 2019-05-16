<?php

namespace HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Saledetail
 *
 * @ORM\Table(name="saleDetail", indexes={@ORM\Index(name="sale_id", columns={"sale_id"}), @ORM\Index(name="product_id", columns={"product_id"})})
 * @ORM\Entity
 */
class Saledetail
{
    /**
     * @var integer
     *
     * @ORM\Column(name="saleDetail_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $saledetailId;

    /**
     * @var integer
     *
     * @ORM\Column(name="sale_id", type="integer", nullable=false)
     */
    private $saleId;

    /**
     * @var integer
     *
     * @ORM\Column(name="product_id", type="integer", nullable=false)
     */
    private $productId;

    /**
     * @var string
     *
     * @ORM\Column(name="saleDetail_unitPrice", type="decimal", precision=20, scale=2, nullable=false)
     */
    private $saledetailUnitprice;

    /**
     * @var integer
     *
     * @ORM\Column(name="saleDetail_amount", type="integer", nullable=false)
     */
    private $saledetailAmount;

    /**
     * @var integer
     *
     * @ORM\Column(name="saleDetail_download", type="integer", nullable=false)
     */
    private $saledetailDownload;



    /**
     * Get saledetailId
     *
     * @return integer
     */
    public function getSaledetailId()
    {
        return $this->saledetailId;
    }

    /**
     * Set saleId
     *
     * @param integer $saleId
     *
     * @return Saledetail
     */
    public function setSaleId($saleId)
    {
        $this->saleId = $saleId;

        return $this;
    }

    /**
     * Get saleId
     *
     * @return integer
     */
    public function getSaleId()
    {
        return $this->saleId;
    }

    /**
     * Set productId
     *
     * @param integer $productId
     *
     * @return Saledetail
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return integer
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set saledetailUnitprice
     *
     * @param string $saledetailUnitprice
     *
     * @return Saledetail
     */
    public function setSaledetailUnitprice($saledetailUnitprice)
    {
        $this->saledetailUnitprice = $saledetailUnitprice;

        return $this;
    }

    /**
     * Get saledetailUnitprice
     *
     * @return string
     */
    public function getSaledetailUnitprice()
    {
        return $this->saledetailUnitprice;
    }

    /**
     * Set saledetailAmount
     *
     * @param integer $saledetailAmount
     *
     * @return Saledetail
     */
    public function setSaledetailAmount($saledetailAmount)
    {
        $this->saledetailAmount = $saledetailAmount;

        return $this;
    }

    /**
     * Get saledetailAmount
     *
     * @return integer
     */
    public function getSaledetailAmount()
    {
        return $this->saledetailAmount;
    }

    /**
     * Set saledetailDownload
     *
     * @param integer $saledetailDownload
     *
     * @return Saledetail
     */
    public function setSaledetailDownload($saledetailDownload)
    {
        $this->saledetailDownload = $saledetailDownload;

        return $this;
    }

    /**
     * Get saledetailDownload
     *
     * @return integer
     */
    public function getSaledetailDownload()
    {
        return $this->saledetailDownload;
    }
}
