<?php

namespace App\UristBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 */
class User implements UserInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $username;
    
    /**
     * 
     * @var string
     */
    protected $email;
    
    /**
     * @var string
     */
    protected $password;

    /**
     * @var \DateTime
     */
    protected $created_at;

    /**
     * @var boolean
     */
    protected $is_active;

    /**
     * @var string
     */
    protected $salt;

    /**
     * @var \App\UristBundle\Entity\UserProfile
     */
    protected $user_profile;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $user_roles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->is_active = TRUE;
        $this->user_roles = new \Doctrine\Common\Collections\ArrayCollection();
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
    
    public function getSalt()
    {
        return null;
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
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
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
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set userprofile
     *
     * @param \App\UristBundle\Entity\UserProfile $userProfile
     *
     * @return User
     */
    public function setUserProfile(\App\UristBundle\Entity\UserProfile $userProfile = null)
    {
        $this->user_profile = $userProfile;

        return $this;
    }

    /**
     * Get userprofile
     *
     * @return \App\UristBundle\Entity\UserProfile
     */
    public function getUserProfile()
    {
        return $this->user_profile;
    }

    /**
     * Add userRole
     *
     * @param \App\UristBundle\Entity\Role $userRole
     *
     * @return User
     */
    public function addUserRole(\App\UristBundle\Entity\Role $userRole)
    {
        $this->user_roles[] = $userRole;

        return $this;
    }

    /**
     * Remove userRole
     *
     * @param \App\UristBundle\Entity\Role $userRole
     */
    public function removeUserRole(\App\UristBundle\Entity\Role $userRole)
    {
        $this->user_roles->removeElement($userRole);
    }

    /**
     * Get userRoles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserRoles()
    {
        return $this->user_roles;
    }
    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        if (!$this->getCreatedAt())
        {
            $this->created_at = new \DateTime();
        }
    }
    /**
     * Сброс прав пользователя
     */
    public function eraseCredentials()
    {

    }
    /**
     * Геттер для массива ролей
     * 
     * @return array An Array of Role objects
     */
    public function getRoles()
    {
        return $this->getUserRoles()->toArray();
    }
    /**
     * Сравнивает пользователя с другим пользователем и определяет
     * один и тот же ли это человек.
     * 
     * @param UserInterface $user The user
     * @return boolean True if equal, false othwerwise.
     */
    public function equals(UserInterface $user)
    {
        return md5($this->getUsername()) == md5($user->getUsername());
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
        ) = unserialize($serialized);
    }
}
