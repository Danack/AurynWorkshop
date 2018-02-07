<?php

namespace AurynWorkshop\Model;

/**
 * @Entity @Table(name="user_password_login")
 **/
class UserPasswordLogin
{
    /** @Id @Column(type="integer", name="id") @GeneratedValue **/
    protected $id;

    /** @Column(type="string") **/
    protected $username;

    /** @Column(type="string") **/
    protected $password_hash;

    /**
     * One UserPasswordLogin is usable by one user.
     * @OneToOne(targetEntity="AurynWorkshop\Model\User", inversedBy="userPasswordLogin")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     * @var \AurynWorkshop\Model\User
     */
    private $user;


    private function __construct()
    {
        //not allowed.
    }

    public static function fromPartial($username, $password_hash, User $user)
    {
        $instance = new self();
        $instance->username = $username;
        $instance->password_hash = $password_hash;
        $instance->user = $user;
        return $instance;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @param mixed $password_hash
     */
    public function setPasswordHash($password_hash)
    {
        $this->password_hash = $password_hash;
    }

    /**
     * @return mixed
     */
    public function getPasswordHash()
    {
        return $this->password_hash;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }


    public function toArray()
    {
         return [
             'id' => $this->id,
             'username' => $this->username
         ];
    }
}
