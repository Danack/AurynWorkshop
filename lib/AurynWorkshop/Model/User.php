<?php

namespace AurynWorkshop\Model;

use AurynWorkshop\Model\UserPasswordLogin;

/**
 * @Entity @Table(name="user")
 **/
class User
{
    /** @Id @Column(type="integer", name="id") @GeneratedValue **/
    protected $id;

    /**
     * Many SiteUserPermissions for one adminUser .
     * @OneToOne(targetEntity="AurynWorkshop\Model\UserPasswordLogin", mappedBy="user", cascade={"all"}, orphanRemoval=true)
     */
    private $userPasswordLogin;

    private function __construct()
    {
        //not allowed.
    }

    public static function fromPartial()
    {
        $instance = new self();

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
     * @param mixed $userPasswordLogin
     */
    public function setUserPasswordLogin(UserPasswordLogin $userPasswordLogin): void
    {
        $this->userPasswordLogin = $userPasswordLogin;
    }
}
