<?php

namespace TechCorp\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Comment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Status", inversedBy="comments")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    protected $status;

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
     * Set content
     *
     * @param string $content
     *
     * @return Status
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Status
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Status
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist
     *
     */
    public function prePersistEvent()
    {
        $this->createdAt = new \Datetime();
        $this->updatedAt = new \Datetime();
    }

    /**
     * @ORM\PreUpdate
     *
     */
    public function preUpdateEvent()
    {
        $this->updatedAt = new \Datetime();
    }

    /**
     * Set user
     *
     * @param \TechCorp\FrontBundle\Entity\User $user
     *
     * @return Status
     */
    public function setUser(\TechCorp\FrontBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \TechCorp\FrontBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set statuses
     *
     * @param \TechCorp\FrontBundle\Entity\User $statuses
     *
     * @return Comment
     */
    public function setStatuses(\TechCorp\FrontBundle\Entity\User $statuses = null)
    {
        $this->statuses = $statuses;

        return $this;
    }

    /**
     * Get statuses
     *
     * @return \TechCorp\FrontBundle\Entity\User
     */
    public function getStatuses()
    {
        return $this->statuses;
    }

    /**
     * Set status
     *
     * @param \TechCorp\FrontBundle\Entity\Status $status
     *
     * @return Comment
     */
    public function setStatus(\TechCorp\FrontBundle\Entity\Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \TechCorp\FrontBundle\Entity\Status
     */
    public function getStatus()
    {
        return $this->status;
    }
}
