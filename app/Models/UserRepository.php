<?php

namespace App\Models;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @param string $username
     *
     * @return User
     */
    public function findByUsername($username)
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq('username', $username));

        return $this->matching($criteria)->first();
    }
}
