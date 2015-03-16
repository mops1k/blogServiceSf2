<?php

namespace Blog\ServiceBundle\Entity;

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

    public function __construct()
    {
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
}
