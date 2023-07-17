<?php

namespace App\Traits\Policies;

trait BasePolicyTrait
{
    public function unauthorizedMessage()
    {
        return $this->deny(getUnauthorizedMessage());
    }
}
