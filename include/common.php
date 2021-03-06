<?php

###############################################################################
##                Liaise -- Contact forms generator for XOOPS                ##
##                 Copyright (c) 2003-2005 NS Tai (aka tuff)                 ##
##                       <http://www.brandycoke.com>                        ##
###############################################################################
##                   XOOPS - PHP Content Management System                   ##
##                       Copyright (c) 2000-2020 XOOPS.org                        ##
##                          <https://xoops.org>                          ##
###############################################################################
##  This program is free software; you can redistribute it and/or modify     ##
##  it under the terms of the GNU General Public License as published by     ##
##  the Free Software Foundation; either version 2 of the License, or        ##
##  (at your option) any later version.                                      ##
##                                                                           ##
##  You may not change or alter any portion of this comment or credits       ##
##  of supporting developers from this source code or any supporting         ##
##  source code which is considered copyrighted (c) material of the          ##
##  original comment or credit authors.                                      ##
##                                                                           ##
##  This program is distributed in the hope that it will be useful,          ##
##  but WITHOUT ANY WARRANTY; without even the implied warranty of           ##
##  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            ##
##  GNU General Public License for more details.                             ##
##                                                                           ##
##  You should have received a copy of the GNU General Public License        ##
##  along with this program; if not, write to the Free Software              ##
##  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA ##
###############################################################################
##  Author of this file: NS Tai (aka tuff)                                   ##
##  URL: http://www.brandycoke.com/                                          ##
##  Project: Liaise                                                          ##
###############################################################################

use XoopsModules\Liaise\{
    Helper
};
/** @var Helper $helper */

include dirname(__DIR__) . '/preloads/autoloader.php';

$helper = Helper::getInstance();
$dirname = $helper->getDirname();

if (!defined('LIAISE_CONSTANTS_DEFINED')) {
    define('LIAISE_URL', XOOPS_URL . '/modules/' . $dirname . '/');
    define('LIAISE_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . $dirname . '/');
    define('LIAISE_UPLOAD_PATH', $helper->getConfig('uploaddir') . '/');

    define('LIAISE_CONSTANTS_DEFINED', true);
}

$formsHandler = $helper->getHandler('Forms');

if (false !== LIAISE_UPLOAD_PATH) {
    if (!is_dir(LIAISE_UPLOAD_PATH)) {
        $oldumask = umask(0);
        if (!mkdir($concurrentDirectory = LIAISE_UPLOAD_PATH, 0777) && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }
        umask($oldumask);
    }
    if (is_dir(LIAISE_UPLOAD_PATH) && !is_writable(LIAISE_UPLOAD_PATH)) {
        chmod(LIAISE_UPLOAD_PATH, 0777);
    }
}

// ------------------ INFORMATUX
/**
 * @param $result
 * @return array
 */
function dbResultToArray($result)
{
    // construction d'un tableau pour les scripts
    global $xoopsDB;
    $result_array = [];

    for ($count = 0; $myrow = $xoopsDB->fetchArray($result); $count++) {
        $result_array[$count] = $myrow;
    }

    return $result_array;
}

//error_reporting(E_ALL);
