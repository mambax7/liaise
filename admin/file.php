<?php
// $Id: file.php 26 2005-09-04 09:52:40Z tuff $
###############################################################################
##                Liaise -- Contact forms generator for XOOPS                ##
##                 Copyright (c) 2003-2005 NS Tai (aka tuff)                 ##
##                       <http://www.brandycoke.com/>                        ##
###############################################################################
##                   XOOPS - PHP Content Management System                   ##
##                       Copyright (c) 2000 XOOPS.org                        ##
##                          <http://www.xoops.org/>                          ##
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
include 'admin_header.php';
$file = isset($_GET['f']) ? trim($_GET['f']) : '';
$path = LIAISE_UPLOAD_PATH . $file;
if (!$file || !preg_match('/^[0-9]+_{1}[0-9a-z]+\.[0-9a-z]+$/', $file) || !file_exists($path)) {
    redirect_header(XOOPS_URL, 0, _AM_NOTHING_SELECTED);
}

header("Content-Type: application/octet-stream");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header('Cache-Control: private, no-cache');
header("Pragma: no-cache");
header('Content-Disposition: attachment; filename="' . $file . '"');
header("Content-Length: " . filesize($path));

readfile($path);
