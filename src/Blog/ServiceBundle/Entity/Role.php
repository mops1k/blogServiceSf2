<?php

namespace Blog\ServiceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Role
 *
 * @ORM\Table(name="roles")
 * @ORM\Entity
 */
class Role implements RoleInterface, \Serializable
{
    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="userRoles", cascade={"persist"})
     * @ORM\JoinTable(name="roles_to_users",
     *      joinColumns={@ORM\JoinColumn(name="role", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user", referencedColumnName="id")}
     *      )
     */
    protected $users;

    /**
     * @ORM\ManyToMany(targetEntity="Role", mappedBy="children", cascade={"persist"})
     */
    protected $parents;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="parents", cascade={"persist"})
     * @ORM\JoinTable(name="roles_to_roles",
     *      joinColumns={@ORM\JoinColumn(name="child", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="parent", referencedColumnName="id")}
     *      )
     */
    protected $children;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false, unique=true)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title = '';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description = '';

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
     * Set name
     *
     * @param string $name
     * @return Role
     */
    public function setName($name)
    {
        $name = strtoupper(str_replace("ROLE_","",$name));
        $this->name = "ROLE_".$name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Role
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Role
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->parents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add users
     *
     * @param User $users
     * @return Role
     */
    public function addUser(User $users)
    {
        $this->users[] = $users;
        return $this;
    }

    /**
     * Remove users
     *
     * @param User $users
     */
    public function removeUser(User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Returns the role.
     *
     * This method returns a string representation whenever possible.
     *
     * When the role cannot be represented with sufficient precision by a
     * string, it should return null.
     *
     * @return string|null A string representation of the role, or null
     */
    public function getRole()
    {
        // TODO: Implement getRole() method.
        return $this->getName();
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->name,
            // see section on salt below
            // $this->salt,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->name,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    /**
     * Add parents
     *
     * @param Role $parents
     * @return Role
     */
    public function addParent(Role $parents)
    {
        $this->parents[] = $parents;

        return $this;
    }

    /**
     * Remove parents
     *
     * @param Role $parents
     */
    public function removeParent(Role $parents)
    {
        $this->parents->removeElement($parents);
    }

    /**
     * Get parents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * Add children
     *
     * @param Role $children
     * @return Role
     */
    public function addChild(Role $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param Role $children
     */
    public function removeChild(Role $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }
}
