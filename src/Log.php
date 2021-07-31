<?php

/**
 * Kdevy framework - My original second php framework.
 *
 * Copyright © 2021 kdevy. All Rights Reserved.
 */

namespace Framework;

class Log
{
    /**
     * @var int
     */
    const DEBUG = 1;
    /**
     * @var int
     */
    const INFO = 2;
    /**
     * @var int
     */
    const WARN = 3;
    /**
     * @var int
     */
    const ERROR = 4;

    /**
     * @var array
     */
    const LOG_NAMES = [
        self::DEBUG => "DEBUG",
        self::INFO => "INFO",
        self::WARN => "WARN",
        self::ERROR => "ERROR",
    ];

    /**
     * @var array
     */
    static array $configs = [
        "level" => self::INFO,
        "output_dirpath" => STATUS_DIR
    ];

    /**
     * @param array $configs
     * @return void
     */
    static public function configure(array $configs)
    {
        self::$configs = array_merge(self::$configs, $configs);
    }

    /**
     * @param string|null $location
     * @param mixed $value
     * @param boolean $is_minify
     * @return void
     */
    static public function info(?string $location, $value, bool $is_minify = true)
    {
        self::writeLine($location, $value, self::INFO, $is_minify);
    }

    /**
     * @param string|null $location
     * @param mixed $value
     * @param boolean $is_minify
     * @return void
     */
    static public function debug(?string $location, $value, bool $is_minify = true)
    {
        self::writeLine($location, $value, self::DEBUG, $is_minify);
    }

    /**
     * @param string|null $location
     * @param mixed $value
     * @param boolean $is_minify
     * @return void
     */
    static public function warning(?string $location, $value, bool $is_minify = true)
    {
        self::writeLine($location, $value, self::WARN, $is_minify);
    }

    /**
     * @param string|null $location
     * @param mixed $value
     * @param boolean $is_minify
     * @return void
     */
    static public function error(?string $location, $value, bool $is_minify = true)
    {
        self::writeLine($location, $value, self::ERROR, $is_minify);
    }

    /**
     * @param string|null $location
     * @param mixed $value
     * @param integer $level
     * @param boolean $is_minify
     * @return void
     */
    static public function writeLine(?string $location, $value, int $level = null, bool $is_minify = true)
    {
        if ($level == null) {
            $level = self::INFO;
        }
        if (self::$configs["level"] > $level) {
            return;
        }
        if ($location == null) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
            // TODO: 他にいい書き方がないか？
            if (strpos($backtrace[0]["file"], "Log") !== false) {
                array_shift($backtrace);
            }

            if (!empty($backtrace)) {
                if (!empty($backtrace[1]["class"])) {
                    $location = str_replace("::", ":", $backtrace[1]["class"]) . $backtrace[1]["type"] . $backtrace[1]["function"] . ":" . $backtrace[0]["line"];
                } else {
                    $location = $backtrace["file"] . ":" . $backtrace["line"];
                }
            }
        }

        if (strpos($location, "@") !== false) {
            $exp = explode("@", $location);
            $location = $exp[1];
            $filename = STATUS_DIR . DS . $exp[0] . ".log";
        } else {
            $filename = STATUS_DIR . DS . "default.log";
        }

        $output = "[" . date("Y-m-d H:i:s") . "][" . str_pad($location, 8) . "][" . self::LOG_NAMES[$level] . "] ";
        $file = fopen($filename, "a");

        // from exception
        if ($value instanceof \Exception) {
            $output .= self::makeErrorMessage($value);
        }
        // from string
        elseif (!is_array($value) && !is_object($value)) {
            $output .= $value . PHP_EOL;
        }
        // from array or object
        else {
            if ($is_minify) {
                $output .= str_replace(
                    ["\n", "\r", "\r\n"],
                    "",
                    var_export($value, true) . PHP_EOL
                ) . PHP_EOL;
            } else {
                $output .= var_export($value, true) . PHP_EOL;
            }
        }

        fwrite($file, $output);
    }

    /**
     * @param Exception $e
     * @return string
     */
    static public function makeErrorMessage(\Exception $e): string
    {
        return str_replace(["\n", "\r", "\r\n"], " ", sprintf(
            "%s: %s in %s(%s) Stack trace: %s",
            $e::class,
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        ));
    }
}
