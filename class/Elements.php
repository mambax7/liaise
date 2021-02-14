<?php

namespace XoopsModules\Liaise;

// 2006-12-20 K.OHWADA
// Notice [PHP]: Only variable references should be returned by reference

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

if (!\defined('LIAISE_ROOT_PATH')) {
    exit();
}

/**
 * Class Elements
 * @package XoopsModules\Liaise
 */
class Elements extends \XoopsObject
{
    public function __construct()
    {
        //        parent::__construct;
        parent::__construct();
        //    key, data_type, value, req, max, opt
        $this->initVar('ele_id', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('form_id', \XOBJ_DTYPE_INT);
        $this->initVar('ele_type', \XOBJ_DTYPE_TXTBOX, null, true, 10);
        $this->initVar('ele_caption', \XOBJ_DTYPE_TXTBOX, '', false, 255);
        $this->initVar('ele_order', \XOBJ_DTYPE_INT, 0);
        $this->initVar('ele_req', \XOBJ_DTYPE_INT);
        $this->initVar('ele_value', \XOBJ_DTYPE_ARRAY, '');
        $this->initVar('ele_display', \XOBJ_DTYPE_INT);
    }
}
