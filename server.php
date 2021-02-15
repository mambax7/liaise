<?php

//================================================================
// Liaise Module
// 2006-12-20 K.OHWADA
//================================================================

use XoopsModules\Liaise\Captcha\Captchax;

require_once dirname(__DIR__, 2) . '/mainfile.php';
$server = new Captchax();
$server->handle_request();
