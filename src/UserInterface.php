<?php

/**
 * Kdevy framework - My original second php framework.
 *
 * Copyright © 2021 kdevy. All Rights Reserved.
 */

namespace Framework;

interface UserInterface
{
    /**
     * @return boolean
     */
    public function isLogin(): bool;
}
