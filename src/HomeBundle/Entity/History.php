<?php

namespace HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * History
 *
 * @ORM\Table(name="history", indexes={@ORM\Index(name="video_id", columns={"video_id"}), @ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="userIp_id", columns={"userIp_id"})})
 * @ORM\Entity
 */
class History
{
    /**
     * @var integer
     *
     * @ORM\Column(name="history_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $historyId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="history_date", type="datetime", nullable=false)
     */
    private $historyDate;

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
     * Get historyId
     *
     * @return integer
     */
    public function getHistoryId()
    {
        return $this->historyId;
    }

    /**
     * Set historyDate
     *
     * @param \DateTime $historyDate
     *
     * @return History
     */
    public function setHistoryDate($historyDate)
    {
        $this->historyDate = $historyDate;

        return $this;
    }

    /**
     * Get historyDate
     *
     * @return \DateTime
     */
    public function getHistoryDate()
    {
        return $this->historyDate;
    }

    /**
     * Set video
     *
     * @param \HomeBundle\Entity\Video $video
     *
     * @return History
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

    /**
     * Set user
     *
     * @param \HomeBundle\Entity\User $user
     *
     * @return History
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
     * @return History
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
