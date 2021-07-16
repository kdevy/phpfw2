<?php

/**
 * Kdevy framework - My original second php framework.
 *
 * Copyright © 2021 kdevy. All Rights Reserved.
 */

namespace Framework;

class Log
{
    const LOG_DEBUG = 1;
    const LOG_INFO = 2;
    const LOG_WARN = 3;
    const LOG_ERROR = 4;

    const LOG_NAMES = [
        self::LOG_DEBUG => "DEBUG",
        self::LOG_INFO => "INFO",
        self::LOG_WARN => "WARN",
        self::LOG_ERROR => "ERROR",
    ];

    /**
     * @param string $location
     * @param mixed $value
     * @param boolean $is_minify
     * @return void
     */
    static public function info(string $location, $value, bool $is_minify = true)
    {
        self::writeLine($location, $value, self::LOG_INFO, $is_minify);
    }

    /**
     * @param string $location
     * @param mixed $value
     * @param boolean $is_minify
     * @return void
     */
    static public function debug(string $location, $value, bool $is_minify = true)
    {
        self::writeLine($location, $value, self::LOG_DEBUG, $is_minify);
    }

    /**
     * @param string $location
     * @param mixed $value
     * @param boolean $is_minify
     * @return void
     */
    static public function warning(string $location, $value, bool $is_minify = true)
    {
        self::writeLine($location, $value, self::LOG_WARN, $is_minify);
    }

    /**
     * @param string $location
     * @param mixed $value
     * @param boolean $is_minify
     * @return void
     */
    static public function error(string $location, $value, bool $is_minify = true)
    {
        self::writeLine($location, $value, self::LOG_ERROR, $is_minify);
    }

    /**
     * @param string $location
     * @param mixed $value
     * @param integer $level
     * @param boolean $is_minify
     * @return void
     */
    static public function writeLine(string $location, $value, int $level = null, bool $is_minify = true)
    {
        if ($level == null) {
            $level = self::LOG_INFO;
        }
        if (LOG_LEVEL > $level) {
            return;
        }

        if (strpos($location, ":") !== false) {
            $exp = explode(":", $location);
            $location = $exp[1];
            $filename = STATUS_DIR . DS . $exp[0] . ".log";
        } else {
            $filename = STATUS_DIR . DS . DEFAULT_LOG_FILENAME;
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
