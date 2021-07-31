<?php

/**
 * Kdevy framework - My original second php framework.
 *
 * Copyright © 2021 kdevy. All Rights Reserved.
 */

namespace Framework;

class SessionContainer extends \ArrayObject
{
    /**
     * @param mixed $array
     */
    public function __construct($array)
    {
        parent::__construct($array, \ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * @param mixed $key
     * @return void
     */
    public function get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * @param mixed $key
     * @return void
     */
    public function unset($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * @param mixed $key
     * @return boolean
     */
    public function has($key): bool
    {
        return $this->offsetExists($key);
    }
}
