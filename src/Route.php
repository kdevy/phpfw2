<?php

/**
 * Kdevy framework - My original second php framework.
 *
 * Copyright © 2021 kdevy. All Rights Reserved.
 */

namespace Framework;

use Psr\Http\Message\ServerRequestInterface;

class Route
{
    /**
     * @var string
     */
    private string $module_name;

    /**
     * @var string
     */
    private string $action_name;

    /**
     * @param string|array|ServerRequestInterface $request_path
     */
    public function __construct($request_path)
    {
        list($this->module_name, $this->action_name) = static::parse($request_path);
    }

    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return $this->module_name;
    }

    /**
     * @return string
     */
    public function getActionName(): string
    {
        return $this->action_name;
    }

    /**
     * @return array
     */
    public function getPath(): array
    {
        return [$this->module_name, $this->action_name];
    }

    /**
     * @return string
     */
    public function getPathName(): string
    {
        return "/{$this->module_name}/{$this->action_name}";
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getPathName();
    }

    /**
     * @param string|array|ServerRequestInterface $request_path
     * @return array
     */
    public static function parse($request_path): array
    {
        $result = $request_path;

        if ($request_path instanceof ServerRequestInterface) {
            $result = $request_path->getUri()->getPath();
        }

        if (is_string($result)) {
            $result = explode("/", explode("?", $result)[0]);
            array_shift($result);
        }

        if (count($result) == 1) {
            $result[1] = (isset($result[0]) && trim($result[0]) !== "" ? $result[0] : "index");
            $result[0] = "index";
        } else {
            $result[0] = (isset($result[0]) && trim($result[0]) !== "" ? $result[0] : "index");
            $result[1] = (isset($result[1]) && trim($result[1]) !== "" ? $result[1] : "index");
        }

        $result[0] = basename($result[0]);
        $result[1] = basename($result[1]);
        return $result;
    }
}
