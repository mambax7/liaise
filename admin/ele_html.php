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

use XoopsModules\Liaise;

if (!defined('LIAISE_ROOT_PATH')) {
    exit();
}

$helper = Liaise\Helper::getInstance();

$rows    = !empty($value[1]) ? $value[1] : $helper->getConfig('ta_rows');
$cols    = !empty($value[2]) ? $value[2] : $helper->getConfig('ta_cols');
$rows    = new \XoopsFormText(_AM_ELE_ROWS, 'ele_value[1]', 3, 3, $rows);
$cols    = new \XoopsFormText(_AM_ELE_COLS, 'ele_value[2]', 3, 3, $cols);
$default = new \XoopsFormDhtmlTextArea(_AM_ELE_DEFAULT, 'ele_value[0]', isset($value[0]) ? htmlspecialchars(($value[0]), ENT_QUOTES | ENT_HTML5) : '', 10, 50);
$output->addElement($rows, 1);
$output->addElement($cols, 1);
$output->addElement($default);
