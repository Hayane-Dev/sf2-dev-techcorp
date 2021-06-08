<?php 

namespace TechCorp\FrontBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use TechCorp\FrontBundle\Entity\Status;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User {
  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @var string
   *
   * @ORM\Column(name="username", type="string", length=255)
   */
  protected $username;

  /**
   * @var string
   *
   * @ORM\Column(name="password", type="string", length=255)
   */
  protected $password;

  /**
   * @ORM\OneToMany(targetEntity="Status", mappedBy="user")
   */
  protected $statuses;

  /**
   * @ORM\ManyToMany(targetEntity="User", inversedBy="friendsWithMe")
   * @ORM\JoinTable(
   *    name="friends",
   *    joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
   *    inverseJoinColumns={@ORM\JoinColumn(name="friend_user_id", referencedColumnName="id")},
   * )
   */
  private $friends;

  /**
   * @ORM\ManyToMany(targetEntity="User", mappedBy="friends")
   *
   */
  private $friendsWithMe;

  /**
   * @ORM\OneToMany(targetEntity="Comment", mappedBy="user")
   */
  protected $comments;

  public function __construct() {
    // parent::__construct();
    $this->statuses = new ArrayCollection();
    $this->friends = new ArrayCollection();
    $this->friendsWithMe = new ArrayCollection();
    $this->comments = new ArrayCollection();
  }


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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Add status
     *
     * @param \TechCorp\FrontBundle\Entity\Status $status
     *
     * @return User
     */
    public function addStatus(\TechCorp\FrontBundle\Entity\Status $status)
    {
        $this->statuses[] = $status;

        return $this;
    }

    /**
     * Remove status
     *
     * @param \TechCorp\FrontBundle\Entity\Status $status
     */
    public function removeStatus(\TechCorp\FrontBundle\Entity\Status $status)
    {
        $this->statuses->removeElement($status);
    }

    /**
     * Get statuses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStatuses()
    {
        return $this->statuses;
    }

    /**
     * Add friend
     *
     * @param \TechCorp\FrontBundle\Entity\User $friend
     *
     * @return User
     */
    public function addFriend(\TechCorp\FrontBundle\Entity\User $friend)
    {
        $this->friends[] = $friend;

        return $this;
    }

    /**
     * Remove friend
     *
     * @param \TechCorp\FrontBundle\Entity\User $friend
     */
    public function removeFriend(\TechCorp\FrontBundle\Entity\User $friend)
    {
        $this->friends->removeElement($friend);
    }

    /**
     * Get friends
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFriends()
    {
        return $this->friends;
    }

    /**
     * Add friendsWithMe
     *
     * @param \TechCorp\FrontBundle\Entity\User $friendsWithMe
     *
     * @return User
     */
    public function addFriendsWithMe(\TechCorp\FrontBundle\Entity\User $friendsWithMe)
    {
        $this->friendsWithMe[] = $friendsWithMe;

        return $this;
    }

    /**
     * Remove friendsWithMe
     *
     * @param \TechCorp\FrontBundle\Entity\User $friendsWithMe
     */
    public function removeFriendsWithMe(\TechCorp\FrontBundle\Entity\User $friendsWithMe)
    {
        $this->friendsWithMe->removeElement($friendsWithMe);
    }

    /**
     * Get friendsWithMe
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFriendsWithMe()
    {
        return $this->friendsWithMe;
    }

    // Ajout de 2 méthodes supplémentaires

    /**
     * hasFriend
     * 
     * @param \TechCorp\FrontBundle\Entity\User $friend
     * @return boolean
     */
    public function hasFriend(\TechCorp\FrontBundle\Entity\User $friend) {
      return $this->friends->contains($friend);
    }

    /**
     * canAddFriend
     * 
     * @param \TechCorp\FrontBundle\Entity\User $friend
     * @return boolean
     */
    public function canAddFriend(\TechCorp\FrontBundle\Entity\User $friend) {
      return $this!=$friend && !$this->hasFriend($friend);
    }


    /**
     * Set comments
     *
     * @param \TechCorp\FrontBundle\Entity\Comment $comments
     *
     * @return User
     */
    public function setComments(\TechCorp\FrontBundle\Entity\Comment $comments = null)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return \TechCorp\FrontBundle\Entity\Comment
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add comment
     *
     * @param \TechCorp\FrontBundle\Entity\Comment $comment
     *
     * @return User
     */
    public function addComment(\TechCorp\FrontBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \TechCorp\FrontBundle\Entity\Comment $comment
     */
    public function removeComment(\TechCorp\FrontBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }
}
