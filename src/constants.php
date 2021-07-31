<?php

/**
 * Kdevy framework - My original second php framework.
 *
 * Copyright © 2021 kdevy. All Rights Reserved.
 */

/**
 * define framework constants.
 */

if (!defined("DS")) {
    define("DS", DIRECTORY_SEPARATOR);
}

/**
 * directory path
 */

// base project directory.
define("PROJECT_DIR", dirname(__DIR__));

// action files directory.
define("MODULE_DIR", PROJECT_DIR . DS . "module");

// html template files directory.
define("TEMPLATE_DIR", PROJECT_DIR . DS . "template");

// temporary files directory.
define("VAR_DIR", PROJECT_DIR . DS . "var");

// application log files directory.
define("STATUS_DIR", VAR_DIR . DS . "status");

// application setting files directory.
define("APP_DIR", PROJECT_DIR . DS . "app");
