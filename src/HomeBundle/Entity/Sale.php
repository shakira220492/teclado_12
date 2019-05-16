<?php

namespace HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sale
 *
 * @ORM\Table(name="sale")
 * @ORM\Entity
 */
class Sale
{
    /**
     * @var integer
     *
     * @ORM\Column(name="sale_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $saleId;

    /**
     * @var string
     *
     * @ORM\Column(name="sale_transactionKey", type="string", length=250, nullable=false)
     */
    private $saleTransactionkey;

    /**
     * @var string
     *
     * @ORM\Column(name="sale_paypalData", type="text", length=65535, nullable=false)
     */
    private $salePaypaldata;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sale_date", type="datetime", nullable=false)
     */
    private $saleDate;

    /**
     * @var string
     *
     * @ORM\Column(name="sale_email", type="string", length=5000, nullable=false)
     */
    private $saleEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="sale_total", type="decimal", precision=60, scale=0, nullable=false)
     */
    private $saleTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="sale_status", type="string", length=200, nullable=false)
     */
    private $saleStatus;



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
     * Set saleTransactionkey
     *
     * @param string $saleTransactionkey
     *
     * @return Sale
     */
    public function setSaleTransactionkey($saleTransactionkey)
    {
        $this->saleTransactionkey = $saleTransactionkey;

        return $this;
    }

    /**
     * Get saleTransactionkey
     *
     * @return string
     */
    public function getSaleTransactionkey()
    {
        return $this->saleTransactionkey;
    }

    /**
     * Set salePaypaldata
     *
     * @param string $salePaypaldata
     *
     * @return Sale
     */
    public function setSalePaypaldata($salePaypaldata)
    {
        $this->salePaypaldata = $salePaypaldata;

        return $this;
    }

    /**
     * Get salePaypaldata
     *
     * @return string
     */
    public function getSalePaypaldata()
    {
        return $this->salePaypaldata;
    }

    /**
     * Set saleDate
     *
     * @param \DateTime $saleDate
     *
     * @return Sale
     */
    public function setSaleDate($saleDate)
    {
        $this->saleDate = $saleDate;

        return $this;
    }

    /**
     * Get saleDate
     *
     * @return \DateTime
     */
    public function getSaleDate()
    {
        return $this->saleDate;
    }

    /**
     * Set saleEmail
     *
     * @param string $saleEmail
     *
     * @return Sale
     */
    public function setSaleEmail($saleEmail)
    {
        $this->saleEmail = $saleEmail;

        return $this;
    }

    /**
     * Get saleEmail
     *
     * @return string
     */
    public function getSaleEmail()
    {
        return $this->saleEmail;
    }

    /**
     * Set saleTotal
     *
     * @param string $saleTotal
     *
     * @return Sale
     */
    public function setSaleTotal($saleTotal)
    {
        $this->saleTotal = $saleTotal;

        return $this;
    }

    /**
     * Get saleTotal
     *
     * @return string
     */
    public function getSaleTotal()
    {
        return $this->saleTotal;
    }

    /**
     * Set saleStatus
     *
     * @param string $saleStatus
     *
     * @return Sale
     */
    public function setSaleStatus($saleStatus)
    {
        $this->saleStatus = $saleStatus;

        return $this;
    }

    /**
     * Get saleStatus
     *
     * @return string
     */
    public function getSaleStatus()
    {
        return $this->saleStatus;
    }
}
