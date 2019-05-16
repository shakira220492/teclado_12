<?php

namespace HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Promotedvideo
 *
 * @ORM\Table(name="promotedVideo", indexes={@ORM\Index(name="video_id", columns={"video_id"}), @ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class Promotedvideo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="promotedVideo_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $promotedvideoId;

    /**
     * @var integer
     *
     * @ORM\Column(name="promotedVideo_coin_amount", type="integer", nullable=false)
     */
    private $promotedvideoCoinAmount;

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
     * Get promotedvideoId
     *
     * @return integer
     */
    public function getPromotedvideoId()
    {
        return $this->promotedvideoId;
    }

    /**
     * Set promotedvideoCoinAmount
     *
     * @param integer $promotedvideoCoinAmount
     *
     * @return Promotedvideo
     */
    public function setPromotedvideoCoinAmount($promotedvideoCoinAmount)
    {
        $this->promotedvideoCoinAmount = $promotedvideoCoinAmount;

        return $this;
    }

    /**
     * Get promotedvideoCoinAmount
     *
     * @return integer
     */
    public function getPromotedvideoCoinAmount()
    {
        return $this->promotedvideoCoinAmount;
    }

    /**
     * Set user
     *
     * @param \HomeBundle\Entity\User $user
     *
     * @return Promotedvideo
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
     * @return Promotedvideo
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
