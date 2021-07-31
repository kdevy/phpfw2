<?php

/**
 * Kdevy framework - My original second php framework.
 *
 * Copyright © 2021 kdevy. All Rights Reserved.
 */

namespace Framework;

class User implements UserInterface
{
    /**
     * @var bool
     */
    private bool $is_login = false;

    /**
     * @return boolean
     */
    public function isLogin(): bool
    {
        return $this->is_login;
    }
}
