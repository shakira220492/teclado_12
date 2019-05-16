<?php

namespace HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Donatedvideo
 *
 * @ORM\Table(name="donatedVideo", indexes={@ORM\Index(name="video_id", columns={"video_id"}), @ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class Donatedvideo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="donatedVideo_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $donatedvideoId;

    /**
     * @var integer
     *
     * @ORM\Column(name="donatedVideo_coin_amount", type="integer", nullable=false)
     */
    private $donatedvideoCoinAmount;

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
     * @var \Video
     *
     * @ORM\ManyToOne(targetEntity="Video")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="video_id", referencedColumnName="video_id")
     * })
     */
    private $video;



    /**
     * Get donatedvideoId
     *
     * @return integer
     */
    public function getDonatedvideoId()
    {
        return $this->donatedvideoId;
    }

    /**
     * Set donatedvideoCoinAmount
     *
     * @param integer $donatedvideoCoinAmount
     *
     * @return Donatedvideo
     */
    public function setDonatedvideoCoinAmount($donatedvideoCoinAmount)
    {
        $this->donatedvideoCoinAmount = $donatedvideoCoinAmount;

        return $this;
    }

    /**
     * Get donatedvideoCoinAmount
     *
     * @return integer
     */
    public function getDonatedvideoCoinAmount()
    {
        return $this->donatedvideoCoinAmount;
    }

    /**
     * Set user
     *
     * @param \HomeBundle\Entity\User $user
     *
     * @return Donatedvideo
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
     * Set video
     *
     * @param \HomeBundle\Entity\Video $video
     *
     * @return Donatedvideo
     */
    public function setVideo(\HomeBundle\Entity\Video $video = null)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return \HomeBundle\Entity\Video
     */
    public function getVideo()
    {
        return $this->video;
    }
}
