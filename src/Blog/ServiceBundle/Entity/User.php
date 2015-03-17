<?php

namespace Blog\ServiceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="realname", type="string", length=64, nullable=false)
     * @Assert\NotBlank(message="Please enter your name.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=6,
     *     max="64",
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $realname;

    /**
     * @ORM\Column(name="blog_name", type="string", length=128, nullable=false)
     * @Assert\NotBlank(message="Please enter your blog name.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=6,
     *     max="128",
     *     minMessage="The blog name is too short.",
     *     maxMessage="The blog name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     *
     */
    private $blogName;

    /**
     * @ORM\ManyToMany(targetEntity="Role", mappedBy="users", cascade={"persist"})
     */
    protected $userRoles;

    /**
     * @ORM\OneToMany(targetEntity="Blog",mappedBy="user")
     */
    private $posts;

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
        parent::__construct();
        // your own logic
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
     * Set realname
     *
     * @param string $realname
     * @return User
     */
    public function setRealname($realname)
    {
        $this->realname = $realname;

        return $this;
    }

    /**
     * Get realname
     *
     * @return string 
     */
    public function getRealname()
    {
        return $this->realname;
    }

    /**
     * Set blogName
     *
     * @param string $blogName
     * @return User
     */
    public function setBlogName($blogName)
    {
        $this->blogName = $blogName;

        return $this;
    }

    /**
     * Get blogName
     *
     * @return string 
     */
    public function getBlogName()
    {
        return $this->blogName;
    }

    /**
     * Add userRoles
     *
     * @param \Blog\ServiceBundle\Entity\Role $userRoles
     * @return User
     */
    public function addUserRole(\Blog\ServiceBundle\Entity\Role $userRoles)
    {
        $this->userRoles[] = $userRoles;

        return $this;
    }

    /**
     * Remove userRoles
     *
     * @param \Blog\ServiceBundle\Entity\Role $userRoles
     */
    public function removeUserRole(\Blog\ServiceBundle\Entity\Role $userRoles)
    {
        $this->userRoles->removeElement($userRoles);
    }

    /**
     * Get userRoles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * Get Roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->userRoles->toArray();
    }

    /**
     * Add posts
     *
     * @param \Blog\ServiceBundle\Entity\Blog $posts
     * @return User
     */
    public function addPost(\Blog\ServiceBundle\Entity\Blog $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts
     *
     * @param \Blog\ServiceBundle\Entity\Blog $posts
     */
    public function removePost(\Blog\ServiceBundle\Entity\Blog $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }
}
