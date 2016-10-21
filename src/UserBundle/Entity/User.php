<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use AppBundle\Library\Base\BaseEntity;

/**
 * User
 *
 * @ORM\Table(name="lola_user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseEntity implements AdvancedUserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="text")
     */
    private $email;

    /**
     * @var int
     *
     * @ORM\Column(name="phoneNumber", type="integer")
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;
    /**
     * @var int
     *
     * @ORM\Column(name="gender", type="integer")
     */
    private $gender;
    
    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;
    
    /**
     *
     * @ORM\Column(name="salt", type="string")
     */
    private $salt;
    
    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     * @ORM\JoinTable(name="felix_user_users_roles")
     */
    private $roles;
    
    /**
     * @ORM\OneToMany(targetEntity="ShopBundle\Entity\Order", mappedBy="customer")
     */
    private $orders;

    /**
     * @var date
     *
     * @ORM\Column(name="dob", type="date")
     */
    private $dob;
    
    /**
     * @ORM\OneToMany(targetEntity="Address", mappedBy="customer")
     */
    private $addresses;    

    /**
     * 
     */
    public function __construct() 
    {
        parent::__construct();
        
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
        $this->roles = new ArrayCollection();        
        $this->orders = new ArrayCollection();
        $this->addresses = new ArrayCollection();
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        /*
         * Notice that the Role class implements RoleInterface. This is because Symfony's security system requires that the User::getRoles method returns an
         * array of either role strings or objects that implement this interface. If Role didn't implement this interface, then User::getRoles would need to
         * iterate over all the Role objects, call getRole on each, and create an array of strings to return. Both approaches are valid and equivalent.
         */
        $userRoles = array();
        foreach($this->roles as $role){
            $userRoles[] = $role->getRole();
        }
        
        return $userRoles;
        //return $this->roles->toArray();
    }
    
    /**
     * 
     * @param \UserBundle\Entity\Role $role
     * @return \UserBundle\Entity\User
     */
    public function addRole(Role $role)
    {
        $this->roles[] = $role;
        
        return $this;
    }
    
    /**
     * Remove roles
     *
     * @param \UserBundle\Entity\Role $roles
     */
    public function removeRole(Role $roles)
    {
        $this->roles->removeElement($roles);
    }
    
    /**
     * Check if user with admin role
     * @return boolean
     */
    public function hasAdminRole()
    {
        $arr = array_intersect(
            array(
                Role::ROLE_ADMIN,
                ), 
            $this->getRoles()
            );
        
        return !empty($arr);
    }
    
    /**
     * Check if user with Customer role
     * @return boolean
     */
    public function hasCustomerRole()
    {
        $arr = array_intersect(
            array(
                Role::ROLE_USER,
                ), 
            $this->getRoles()
            );
        
        return !empty($arr);
    }   
    
    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }
    
    /**
     * 
     * @return boolean
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * 
     * @return boolean
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * 
     * @return boolean
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * 
     * @return type
     */
    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
            $this->salt,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
            $this->salt
        ) = unserialize($serialized);
    }    
    
    /**
     * 
     * @return type
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * 
     * @return type
     */
    public function getName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }
    
    /**
     * @inheritDoc
     */
    public function getEmail()
    {
        return $this->getUsername();
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }    

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }
    
    /**
     * 
     * @param type $password
     * @return \UserBundle\Entity\User
     */
    public function setPassword($password)
    {
        $this->password = md5($password);
        
        return $this;
    }
    
    /**
     * 
     * @param type $password
     * @return type
     */
    public function checkPassword($password)
    {
        return $this->password === md5($password);
    }
    
    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set phoneNumber
     *
     * @param integer $phoneNumber
     *
     * @return User
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return integer
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return User
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set gender
     *
     * @param integer $gender
     *
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return integer
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Set dob
     *
     * @param \DateTime $dob
     *
     * @return User
     */
    public function setDob($dob)
    {
        $this->dob = $dob;

        return $this;
    }

    /**
     * Get dob
     *
     * @return \DateTime
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * Set createdTime
     *
     * @param integer $createdTime
     *
     * @return User
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;

        return $this;
    }

    /**
     * Add order
     *
     * @param \ShopBundle\Entity\Order $order
     *
     * @return User
     */
    public function addOrder(\ShopBundle\Entity\Order $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param \ShopBundle\Entity\Order $order
     */
    public function removeOrder(\ShopBundle\Entity\Order $order)
    {
        $this->orders->removeElement($order);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Add address
     *
     * @param \UserBundle\Entity\Address $address
     *
     * @return User
     */
    public function addAddress(\UserBundle\Entity\Address $address)
    {
        $this->addresses[] = $address;

        return $this;
    }

    /**
     * Remove address
     *
     * @param \UserBundle\Entity\Address $address
     */
    public function removeAddress(\UserBundle\Entity\Address $address)
    {
        $this->addresses->removeElement($address);
    }

    /**
     * Get addresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }
}
