<?php

namespace HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Specificlist
 *
 * @ORM\Table(name="specificList", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="userIp_id", columns={"userIp_id"})})
 * @ORM\Entity
 */
class Specificlist
{
    /**
     * @var integer
     *
     * @ORM\Column(name="specificList_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $specificlistId;

    /**
     * @var string
     *
     * @ORM\Column(name="specificList_name", type="string", length=100, nullable=false)
     */
    private $specificlistName;

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
     * @var \Userip
     *
     * @ORM\ManyToOne(targetEntity="Userip")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="userIp_id", referencedColumnName="userIp_id")
     * })
     */
    private $userip;



    /**
     * Get specificlistId
     *
     * @return integer
     */
    public function getSpecificlistId()
    {
        return $this->specificlistId;
    }

    /**
     * Set specificlistName
     *
     * @param string $specificlistName
     *
     * @return Specificlist
     */
    public function setSpecificlistName($specificlistName)
    {
        $this->specificlistName = $specificlistName;

        return $this;
    }

    /**
     * Get specificlistName
     *
     * @return string
     */
    public function getSpecificlistName()
    {
        return $this->specificlistName;
    }

    /**
     * Set user
     *
     * @param \HomeBundle\Entity\User $user
     *
     * @return Specificlist
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

    /**
     * Set userip
     *
     * @param \HomeBundle\Entity\Userip $userip
     *
     * @return Specificlist
     */
    public function setUserip(\HomeBundle\Entity\Userip $userip = null)
    {
        $this->userip = $userip;

        return $this;
    }

    /**
     * Get userip
     *
     * @return \HomeBundle\Entity\Userip
     */
    public function getUserip()
    {
        return $this->userip;
    }
}
