<?php

/**
 * CAPTCHA image server
 */

use XoopsModules\Liaise\Captchax;

//require_once __DIR__ . '/class.captcha_x.php';
$server = new Captchax();
$server->handle_request();
