<?php

/**
 * Kdevy framework - My original second php framework.
 *
 * Copyright © 2021 kdevy. All Rights Reserved.
 */

namespace Framework;

class SessionHelper
{
    /**
     * @param mixed $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * @param mixed $key
     * @param mixed $value
     */
    public function __set($key, $value): void
    {
        $this->set($key, $value);
    }

    /**
     * @param void $key
     */
    public function __unset($key)
    {
        $this->unset($key);
    }

    /**
     * @param mixed $key
     * @return boolean
     */
    public function __isset($key): bool
    {
        return $this->has($key);
    }

    /**
     * @param mixed $key
     * @return mixed
     */
    public function get($key)
    {
        return $_SESSION[$key];
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param mixed $key
     * @return void
     */
    public function unset($key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * @param mixed $key
     * @return boolean
     */
    public function has($key): bool
    {
        return array_key_exists($key, $_SESSION);
    }
}
