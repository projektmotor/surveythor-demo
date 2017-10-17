<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Ramsey\Uuid\Uuid;

class User extends BaseUser
{
    /** @var Uuid $id */
    protected $id;

    public function __construct()
    {
        parent::__construct();
    }
}
