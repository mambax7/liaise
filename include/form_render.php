<?php
// 2006-12-20 K.OHWADA
// use GIJOE's Ticket Class
// use captcha

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
    Helper,
    ElementRenderer
};
/** @var Helper $helper */

if (!defined('LIAISE_ROOT_PATH')) {
    exit();
}

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
$helper = Helper::getInstance();
$elementsHandler = $helper->getHandler('Elements');
//require_once LIAISE_ROOT_PATH . 'class/elementrenderer.php';

// -------------------------------------------------------
$GLOBALS['xoopsOption']['template_main'] = 'xliaise_form.tpl';
// -------------------------------------------------------
require_once XOOPS_ROOT_PATH . '/header.php';
$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('form_id', $form->getVar('form_id')));
$criteria->add(new \Criteria('ele_display', 1));
$criteria->setSort('ele_order');
$criteria->setOrder('ASC');
$elements = $elementsHandler->getObjects($criteria, true);

$form_output = new \XoopsThemeForm($form->getVar('form_title'), 'liaise_' . $form->getVar('form_id'), LIAISE_URL . 'index.php');
foreach ($elements as $i) {
    $renderer = new ElementRenderer($i);
    $form_ele = &$renderer->constructElement();
    $req      = (int)$i->getVar('ele_req');
    $form_output->addElement($form_ele, $req);
    unset($form_ele);
}

// --- GIJOE's Ticket Class ---
//require_once LIAISE_ROOT_PATH . 'include/gtickets.php';

//$form_output->addElement($xoopsGTicket->getTicketXoopsForm(__LINE__));
// ------

// --- captcha ---
if ($helper->getConfig('captcha')) {
    $server  = LIAISE_URL . 'server.php';
    $onclick = "javasript:this.src='" . $server . "?'+Math.random();";
    $captcha = _LIAISE_CAPTCHA_DESC . "<br>\n";
    $captcha .= '<img src="' . $server . '" onclick="' . $onclick . '" alt="CAPTCHA image" style="padding: 3px">' . "<br>\n";
    $captcha .= '<input name="captcha" type="text">';
    $form_output->addElement(new \XoopsFormLabel(_LIAISE_CAPTCHA, $captcha));
}
// ------

// --- reload ---
if ($liaise_error) {
    $xoopsTpl->assign('form_error', $liaise_error);
}
// -----

$form_output->addElement(new \XoopsFormHidden('form_id', $form->getVar('form_id')));
$form_output->addElement(new \XoopsFormButton('', 'submit', $form->getVar('form_submit_text'), 'submit'));
// $form_output->assign($xoopsTpl);

$c    = 0;
$eles = [];
foreach ($form_output->getElements() as $e) {
    $id = $req = $name = $ele_type = $except = false;

    $name    = $e->getName();
    $caption = $e->getCaption();
    if (!empty($name)) {
        $id     = str_replace('ele_', '', $e->getName());
        $except = '1';
    } elseif (method_exists($e, 'getElements')) {
        $obj    = $e->getElements();
        $id     = str_replace('ele_', '', $e->getName());
        $id     = str_replace('[]', '', $id);
        $except .= '2';
    }
    if (isset($elements[$id])) {
        $req      = $elements[$id]->getVar('ele_req') ? true : false;
        $ele_type = $elements[$id]->getVar('ele_type');
        $except   .= '3';
    } else {
        $req    = false;
        $except .= '4';
    }
    $eles[$c]['insbreak'] = $name . ' - ' . $caption . ' - ' . $id . ' - ' . $except;
    $eles[$c]['caption']  = $caption;
    $eles[$c]['name']     = $name;
    $eles[$c]['body']     = $e->render();
    $eles[$c]['hidden']   = $e->isHidden();
    $eles[$c]['required'] = $req;
    $eles[$c]['ele_type'] = $ele_type;
    $c++;
}
$js = $form_output->renderValidationJS();
$xoopsTpl->assign(
    'form_output',
    [
        'title'      => $form_output->getTitle(),
        'name'       => $form_output->getName(),
        'action'     => $form_output->getAction(),
        'method'     => $form_output->getMethod(),
        'extra'      => 'onsubmit="return xoopsFormValidate_' . $form_output->getName() . '();"' . $form_output->getExtra(),
        'javascript' => $js,
        'elements'   => $eles,
    ]
);

$xoopsTpl->assign('form_req_prefix', $helper->getConfig('prefix'));
$xoopsTpl->assign('form_req_suffix', $helper->getConfig('suffix'));
$xoopsTpl->assign('form_intro', $form->getVar('form_intro'));
$xoopsTpl->assign('form_text_global', $myts->displayTarea($helper->getConfig('global')));
if (0 == $form->getVar('form_order')) {
    if (!isset($xoopsUser) || !is_object($xoopsUser) || !$xoopsUser->isAdmin()) {
        header('Location: ' . LIAISE_URL);
        exit();
    }
    $xoopsTpl->assign('form_is_hidden', _LIAISE_FORM_IS_HIDDEN);
}

$xoopsTpl->assign('xoops_pagetitle', $form->getVar('form_title'));
