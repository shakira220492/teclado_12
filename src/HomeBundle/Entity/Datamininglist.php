<?php

namespace HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Datamininglist
 *
 * @ORM\Table(name="dataminingList", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="video_id", columns={"video_id"}), @ORM\Index(name="userIp_id", columns={"userIp_id"})})
 * @ORM\Entity
 */
class Datamininglist
{
    /**
     * @var integer
     *
     * @ORM\Column(name="dataminingList_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $datamininglistId;

    /**
     * @var integer
     *
     * @ORM\Column(name="dataminingList_score", type="integer", nullable=false)
     */
    private $datamininglistScore;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dataminingList_date", type="datetime", nullable=false)
     */
    private $datamininglistDate;

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
     * @var \Video
     *
     * @ORM\ManyToOne(targetEntity="Video")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="video_id", referencedColumnName="video_id")
     * })
     */
    private $video;



    /**
     * Get datamininglistId
     *
     * @return integer
     */
    public function getDatamininglistId()
    {
        return $this->datamininglistId;
    }

    /**
     * Set datamininglistScore
     *
     * @param integer $datamininglistScore
     *
     * @return Datamininglist
     */
    public function setDatamininglistScore($datamininglistScore)
    {
        $this->datamininglistScore = $datamininglistScore;

        return $this;
    }

    /**
     * Get datamininglistScore
     *
     * @return integer
     */
    public function getDatamininglistScore()
    {
        return $this->datamininglistScore;
    }

    /**
     * Set datamininglistDate
     *
     * @param \DateTime $datamininglistDate
     *
     * @return Datamininglist
     */
    public function setDatamininglistDate($datamininglistDate)
    {
        $this->datamininglistDate = $datamininglistDate;

        return $this;
    }

    /**
     * Get datamininglistDate
     *
     * @return \DateTime
     */
    public function getDatamininglistDate()
    {
        return $this->datamininglistDate;
    }

    /**
     * Set user
     *
     * @param \HomeBundle\Entity\User $user
     *
     * @return Datamininglist
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
     * @return Datamininglist
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

    /**
     * Set video
     *
     * @param \HomeBundle\Entity\Video $video
     *
     * @return Datamininglist
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
