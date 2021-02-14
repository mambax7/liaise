<<<<<<<< HEAD:class/ElementRenderer.php
<?php

namespace XoopsModules\Liaise;

// 2006-12-20 K.OHWADA
// reload form

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

/**
 * Class ElementRenderer
 * @package XoopsModules\Liaise
 */
class ElementRenderer
{
    private $_ele;

    /**
     * ElementRenderer constructor.
     * @param $element
     */
    public function __construct(&$element)
    {
        $this->_ele = &$element;
    }

    /**
     * @param false $admin
     * @return false|\XoopsFormDhtmlTextArea|\XoopsFormElementTray|\XoopsFormFile|\XoopsFormLabel|\XoopsFormRadio|\XoopsFormSelect|\XoopsFormText|\XoopsFormTextArea
     */
    public function &constructElement($admin = false)
    {
        global $xoopsUser, $form;
        $myts        = \MyTextSanitizer::getInstance();
        $ele_caption = $this->_ele->getVar('ele_caption');
        $ele_value   = $this->_ele->getVar('ele_value');
        $e           = $this->_ele->getVar('ele_type');
        $delimiter   = $form->getVar('form_delimiter');
        $form_ele_id = $admin ? 'ele_value[' . $this->_ele->getVar('ele_id') . ']' : 'ele_' . $this->_ele->getVar('ele_id');

        // --- reload ---
        $post_val = null;
        if (isset($_POST[$form_ele_id])) {
            $post_val = $_POST[$form_ele_id];
        }
        // ---

        switch ($e) {
            case 'text':

                // --- reload ---
                if ($post_val) {
                    $ele_value[2] = $post_val;
                }
                // ---

                if (!\is_object($xoopsUser)) {
                    $ele_value[2] = \str_replace('{UNAME}', '', $ele_value[2]);
                    $ele_value[2] = \str_replace('{EMAIL}', '', $ele_value[2]);
                } elseif (!$admin) {
                    $ele_value[2] = \str_replace('{UNAME}', $xoopsUser->getVar('uname', 'e'), $ele_value[2]);
                    $ele_value[2] = \str_replace('{EMAIL}', $xoopsUser->getVar('email', 'e'), $ele_value[2]);
                }
                $form_ele = new \XoopsFormText(
                    $ele_caption, $form_ele_id, $ele_value[0],    //    box width
                    $ele_value[1],    //    max width
                    htmlspecialchars(($ele_value[2]))    //    default value
                );
                break;
            case 'textarea':

                // --- reload ---
                if ($post_val) {
                    $ele_value[0] = $post_val;
                }
                // ---

                $form_ele = new \XoopsFormTextArea(
                    $ele_caption, $form_ele_id, htmlspecialchars(($ele_value[0])), //    default value
                    $ele_value[1],    //    rows
                    $ele_value[2]    //    cols
                );
                break;
            case 'html':

                // --- reload ---
                if ($post_val) {
                    $ele_value[0] = $post_val;
                }
                // ---

                global $check_req;
                if (!$admin) {
                    $form_ele = new \XoopsFormLabel($ele_caption, $myts->displayTarea(($ele_value[0]), 1));
                } else {
                    $form_ele = new \XoopsFormDhtmlTextArea(
                        $ele_caption, $form_ele_id, htmlspecialchars(($ele_value[0])), //    default value
                        $ele_value[1],    //    rows
                        $ele_value[2]    //    cols
                    );
                    $check_req->setExtra('disabled="disabled"');
                }
                break;
            case 'select':
                $selected  = [];
                $options   = [];
                $opt_count = 1;
                //                while ($i = each($ele_value[2])) {
                foreach ($ele_value[2] as $k => $v) {
                    $options[$opt_count] = $k;

                    // --- reload ---
                    //                    if( $i['value'] > 0 ){
                    //                        $selected[] = $opt_count;
                    //                    }
                    if ($post_val) {
                        if (\is_array($post_val)) {
                            foreach ($post_val as $val) {
                                if ($val == $opt_count) {
                                    $selected[] = $opt_count;
                                }
                            }
                        } elseif ($post_val == $opt_count) {
                                $selected[] = $opt_count;
                        }
                    } elseif ($v > 0) {
                            $selected[] = $opt_count;
                    }
                    // ---

                    ++$opt_count;
                }
                $form_ele = new \XoopsFormSelect(
                    $ele_caption, $form_ele_id, $selected, $ele_value[0],    //    size
                    $ele_value[1]    //    multiple
                );
                if ($ele_value[1]) {
                    $this->_ele->setVar('ele_req', 0);
                }
                $form_ele->addOptionArray($options);
                break;
            case 'checkbox':
                $selected  = [];
                $options   = [];
                $opt_count = 1;
                //                while ($i = each($ele_value)) {
                foreach ($ele_value as $k => $v) {
                    $options[$opt_count] = $k;

                    // --- reload ---
                    //                    if( $i['value'] > 0 ){
                    //                        $selected[] = $opt_count;
                    //                    }
                    if ($post_val) {
                        if (\is_array($post_val)) {
                            foreach ($post_val as $val) {
                                if ($val == $opt_count) {
                                    $selected[] = $opt_count;
                                }
                            }
                        } elseif ($post_val == $opt_count) {
                                $selected[] = $opt_count;
                        }
                    } elseif ($v > 0) {
                            $selected[] = $opt_count;
                    }
                    // ---

                    ++$opt_count;
                }

                $form_ele = new \XoopsFormElementTray($ele_caption, 'b' === $delimiter ? '<br>' : ' ');
                foreach ($options as $k => $v) {
                    $t     = new \XoopsFormCheckBox(// =&   -- INFORMATUX
                        '', $form_ele_id . '[]', $selected
                    );
                    $other = $this->optOther($v, $form_ele_id);
                    if (false !== $other && !$admin) {
                        $t->addOption($k, _LIAISE_OPT_OTHER . $other);
                    } else {
                        $t->addOption($k, $v);
                    }
                    $form_ele->addElement($t);
                }
                break;
            case 'radio':
            case 'yn':
                $selected  = '';
                $options   = [];
            $options2   = [];
                $opt_count = 1;
                foreach ($ele_value as $k => $v) {
                    switch ($e) {
                        case 'radio':
                            $options[$opt_count] = $k;
                            break;
                        case 'yn':
                            $options[$opt_count] = constant($k);
                            break;
                    }

                    // --- reload ---
                    //                    if( $i['value'] > 0 ){
                    //                        $selected = $opt_count;
                    //                    }
                    if ($post_val) {
                        if ($post_val == $opt_count) {
                            $selected = $opt_count;
                        }
                    } elseif ($v > 0) {
                            $selected = $opt_count;
                    }
                    // ---

                    ++$opt_count;
                }
                switch ($delimiter) {
                    case 'b':
                        $form_ele = new \XoopsFormElementTray($ele_caption, '<br>');
                        foreach ($options as $k => $v) {
                            $t     = new \XoopsFormRadio(// =&   -- INFORMATUX
                                '', $form_ele_id, $selected
                            );
                            $other = $this->optOther($v, $form_ele_id);
                            if (false !== $other && !$admin) {
                                $t->addOption($k, _LIAISE_OPT_OTHER . $other);
                            } else {
                                $t->addOption($k, $v);
                            }
                            $form_ele->addElement($t);
                        }
                        break;
                    case 's':
                    default:
                        $form_ele = new \XoopsFormRadio($ele_caption, $form_ele_id, $selected);
                        //                        while ($o = each($options)) {
                        foreach ($options as $k => $v) {
                            $other = $this->optOther($v, $form_ele_id);
                            if (false !== $other && !$admin) {
                                $form_ele->addOption($k, _LIAISE_OPT_OTHER . $other);
                            } else {
                                $form_ele->addOption($k, $v);
                            }
                        }
                        break;
                }
                break;
            case 'upload':
            case 'uploadimg':
                if ($admin) {
                    $form_ele = new \XoopsFormElementTray('', '<br>');
                    $form_ele->addElement(new \XoopsFormText(_AM_ELE_UPLOAD_MAXSIZE, $form_ele_id . '[0]', 10, 20, $ele_value[0]));
                    if ('uploadimg' === $e) {
                        $form_ele->addElement(new \XoopsFormText(_AM_ELE_UPLOADIMG_MAXWIDTH, $form_ele_id . '[4]', 10, 20, $ele_value[4]));
                        $form_ele->addElement(new \XoopsFormText(_AM_ELE_UPLOADIMG_MAXHEIGHT, $form_ele_id . '[5]', 10, 20, $ele_value[5]));
                    }
                } else {
                    global $form_output;
                    $form_output->setExtra('enctype="multipart/form-data"');
                    $form_ele = new \XoopsFormFile($ele_caption, $form_ele_id, $ele_value[0]);
                }
                break;
            //  --- INFORMATUX
            case 'break':
                global $form_output, $xoopsDB;
                //$form_output->insertBreak(htmlspecialchars(($ele_value[0])),'bg3');
                $ele_id = \str_replace('ele_', '', $form_ele_id);
                $sql    = 'SELECT ele_caption FROM ' . $xoopsDB->prefix() . '_xliaise_formelements WHERE ele_id = ' . $ele_id;
                $result = $xoopsDB->query($sql);
                [$element_caption] = $xoopsDB->fetchRow($result);
                $form_ele = new \XoopsFormElementTray(htmlspecialchars(('[BREAK]' . $element_caption)), '&nbsp;');
                break;
            // ---------------

            default:
                $form_ele = false;
                break;
        }

        return $form_ele;
    }

    /**
     * @param $s
     * @param $id
     * @return false|string
     */
    public function optOther($s, $id)
    {
        $helper = Liaise\Helper::getInstance();
        if (!\preg_match('/\{OTHER\|+[0-9]+\}/', $s)) {
            return false;
        }
        $s   = \explode('|', \preg_replace('/[\{\}]/', '', $s));
        $len = !empty($s[1]) ? $s[1] : $helper->getConfig('t_width');

        // --- reload ---
        //        $box = new \XoopsFormText('', 'other['.$id.']', $len, 255);
        $val = null;
        if (isset($_POST['other'][$id])) {
            $myts = \MyTextSanitizer::getInstance();
            $val  = $_POST['other'][$id];
            $val  = htmlspecialchars(($val));
        }
        $box = new \XoopsFormText('', 'other[' . $id . ']', $len, 255, $val);

        // ------

        return $box->render();
    }
}
========
<?php

namespace XoopsModules\Liaise;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 *
 * @copyright   2003-2005 NS Tai (aka tuff) http://www.brandycoke.com
 * @copyright   2003-2019 XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      NS Tai (aka tuff) URL: http://www.brandycoke.com/
 * @author      Kenichi OHWADA, http://linux2.ohwada.net/, Email:  webmaster@ohwada.jp
 * @author      Patrice BOUTHIER, contact@informatux.com, https://informatux.com/
 * @author      Michael Beck (aka Mamba), XOOPS Development Team
 * @package     Liaise -- Contact forms generator for XOOPS
 */

use XoopsModules\Liaise;

class ElementRenderer
{
    private $_ele;

    public function __construct(&$element)
    {
        $this->_ele = &$element;
    }

    public function &constructElement($admin = false)
    {
        global $xoopsUser, $form;
        $myts        = \MyTextSanitizer::getInstance();
        $ele_caption = $this->_ele->getVar('ele_caption');
        $ele_value   = $this->_ele->getVar('ele_value');
        $e           = $this->_ele->getVar('ele_type');
        $delimiter   = $form->getVar('form_delimiter');
        $form_ele_id = $admin ? 'ele_value[' . $this->_ele->getVar('ele_id') . ']' : 'ele_' . $this->_ele->getVar('ele_id');

        // --- reload ---
        $post_val = null;
        if (isset($_POST[$form_ele_id])) {
            $post_val = $_POST[$form_ele_id];
        }
        // ---

        switch ($e) {
            case 'text':

                // --- reload ---
                if ($post_val) {
                    $ele_value[2] = $post_val;
                }
                // ---

                if (!is_object($xoopsUser)) {
                    $ele_value[2] = str_replace('{UNAME}', '', $ele_value[2]);
                    $ele_value[2] = str_replace('{EMAIL}', '', $ele_value[2]);
                } elseif (!$admin) {
                    $ele_value[2] = str_replace('{UNAME}', $xoopsUser->getVar('uname', 'e'), $ele_value[2]);
                    $ele_value[2] = str_replace('{EMAIL}', $xoopsUser->getVar('email', 'e'), $ele_value[2]);
                }
                $form_ele = new \XoopsFormText($ele_caption, $form_ele_id, $ele_value[0],    //    box width
                                               $ele_value[1],    //    max width
                                               $myts->htmlSpecialChars($myts->stripSlashesGPC($ele_value[2]))    //    default value
                );
                break;
            case 'textarea':

                // --- reload ---
                if ($post_val) {
                    $ele_value[0] = $post_val;
                }
                // ---

                $form_ele = new \XoopsFormTextArea($ele_caption, $form_ele_id, $myts->htmlSpecialChars($myts->stripSlashesGPC($ele_value[0])), //    default value
                                                   $ele_value[1],    //    rows
                                                   $ele_value[2]    //    cols
                );
                break;
            case 'html':

                // --- reload ---
                if ($post_val) {
                    $ele_value[0] = $post_val;
                }
                // ---

                global $check_req;
                if (!$admin) {
                    $form_ele = new \XoopsFormLabel($ele_caption, $myts->displayTarea($myts->stripSlashesGPC($ele_value[0]), 1));
                } else {
                    $form_ele = new \XoopsFormDhtmlTextArea($ele_caption, $form_ele_id, $myts->htmlSpecialChars($myts->stripSlashesGPC($ele_value[0])), //    default value
                                                            $ele_value[1],    //    rows
                                                            $ele_value[2]    //    cols
                    );
                    $check_req->setExtra('disabled="disabled"');
                }
                break;
            case 'select':
                $selected  = [];
                $options   = [];
                $opt_count = 1;
                foreach ($ele_value[2] as $i => $value) {
                    $options[$opt_count] = $myts->stripSlashesGPC($i);

                    // --- reload ---
                    //                    if( $i['value'] > 0 ){
                    //                        $selected[] = $opt_count;
                    //                    }
                    if ($post_val) {
                        if (is_array($post_val)) {
                            foreach ($post_val as $val) {
                                if ($val == $opt_count) {
                                    $selected[] = $opt_count;
                                }
                            }
                        } else {
                            if ($post_val == $opt_count) {
                                $selected[] = $opt_count;
                            }
                        }
                    } else {
                        if ($value > 0) {
                            $selected[] = $opt_count;
                        }
                    }
                    // ---

                    $opt_count++;
                }
                $form_ele = new \XoopsFormSelect($ele_caption, $form_ele_id, $selected, $ele_value[0],    //    size
                                                 $ele_value[1]    //    multiple
                );
                if ($ele_value[1]) {
                    $this->_ele->setVar('ele_req', 0);
                }
                $form_ele->addOptionArray($options);
                break;
            case 'checkbox':
                $selected  = [];
                $options   = [];
                $opt_count = 1;
                foreach ($ele_value as $i => $value) {
                    $options[$opt_count] = $i;

                    // --- reload ---
                    //                    if( $i['value'] > 0 ){
                    //                        $selected[] = $opt_count;
                    //                    }
                    if ($post_val) {
                        if (is_array($post_val)) {
                            foreach ($post_val as $val) {
                                if ($val == $opt_count) {
                                    $selected[] = $opt_count;
                                }
                            }
                        } else {
                            if ($post_val == $opt_count) {
                                $selected[] = $opt_count;
                            }
                        }
                    } else {
                        if ($value > 0) {
                            $selected[] = $opt_count;
                        }
                    }
                    // ---

                    $opt_count++;
                }

                $form_ele = new \XoopsFormElementTray($ele_caption, 'b' === $delimiter ? '<br>' : ' ');
                foreach ($options as $o => $value) {
                    $t     = new \XoopsFormCheckBox(// =&   -- INFORMATUX
                        '', $form_ele_id . '[]', $selected);
                    $other = $this->optOther($value, $form_ele_id);
                    if (false !== $other && !$admin) {
                        $t->addOption($o, _LIAISE_OPT_OTHER . $other);
                    } else {
                        $t->addOption($o, $myts->stripSlashesGPC($value));
                    }
                    $form_ele->addElement($t);
                }
                break;
            case 'radio':
            case 'yn':
                $selected  = '';
                $options   = [];
                $opt_count = 1;
                foreach ($ele_value as $i => $value) {
                    switch ($e) {
                        case 'radio':
                            //                            $options[$opt_count] = $i['key'];
                            $options[$opt_count] = $i;
                            break;
                        case 'yn':
                            $options[$opt_count] = constant($i);
                            break;
                    }

                    // --- reload ---
                    //                    if( $i['value'] > 0 ){
                    //                        $selected = $opt_count;
                    //                    }
                    if ($post_val) {
                        if ($post_val == $opt_count) {
                            $selected = $opt_count;
                        }
                    } else {
                        if ($value > 0) {
                            $selected = $opt_count;
                        }
                    }
                    // ---

                    $opt_count++;
                }
                switch ($delimiter) {
                    case 'b':
                        $form_ele = new \XoopsFormElementTray($ele_caption, '<br>');
                        foreach ($options as $o => $value) {
                            $t     = new \XoopsFormRadio(// =&   -- INFORMATUX
                                '', $form_ele_id, $selected);
                            $other = $this->optOther($value, $form_ele_id);
                            if (false !== $other && !$admin) {
                                $t->addOption($o, _LIAISE_OPT_OTHER . $other);
                            } else {
                                $t->addOption($o, $myts->stripSlashesGPC($value));
                            }
                            $form_ele->addElement($t);
                        }
                        break;
                    case 's':
                    default:
                        $form_ele = new \XoopsFormRadio($ele_caption, $form_ele_id, $selected);
                        foreach ($options as $o => $value) {
                            $other = $this->optOther($value, $form_ele_id);
                            if (false !== $other && !$admin) {
                                $form_ele->addOption($o, _LIAISE_OPT_OTHER . $other);
                            } else {
                                $form_ele->addOption($o, $myts->stripSlashesGPC($value));
                            }
                        }
                        break;
                }
                break;
            case 'upload':
            case 'uploadimg':
                if ($admin) {
                    $form_ele = new \XoopsFormElementTray('', '<br>');
                    $form_ele->addElement(new \XoopsFormText(_AM_ELE_UPLOAD_MAXSIZE, $form_ele_id . '[0]', 10, 20, $ele_value[0]));
                    if ('uploadimg' === $e) {
                        $form_ele->addElement(new \XoopsFormText(_AM_ELE_UPLOADIMG_MAXWIDTH, $form_ele_id . '[4]', 10, 20, $ele_value[4]));
                        $form_ele->addElement(new \XoopsFormText(_AM_ELE_UPLOADIMG_MAXHEIGHT, $form_ele_id . '[5]', 10, 20, $ele_value[5]));
                    }
                } else {
                    global $form_output;
                    $form_output->setExtra('enctype="multipart/form-data"');
                    $form_ele = new \XoopsFormFile($ele_caption, $form_ele_id, $ele_value[0]);
                }
                break;
            //  --- INFORMATUX
            case 'break':
                global $form_output, $xoopsDB;
                //$form_output->insertBreak($myts->htmlSpecialChars($myts->stripSlashesGPC($ele_value[0])),'bg3');
                $ele_id = str_replace('ele_', '', $form_ele_id);
                $sql    = 'SELECT ele_caption FROM ' . $xoopsDB->prefix() . '_xliaise_formelements WHERE ele_id = ' . $ele_id;
                $result = $xoopsDB->query($sql);
                list($element_caption) = $xoopsDB->fetchRow($result);
                $form_ele = new \XoopsFormElementTray($myts->htmlSpecialChars($myts->stripSlashesGPC('[BREAK]' . $element_caption)), '&nbsp;');
                break;
            // ---------------

            default:
                $form_ele = false;
                break;
        }

        return $form_ele;
    }

    public function optOther($s, $id)
    {
        /** @var Liaise\Helper $helper */
        $helper = Liaise\Helper::getInstance();
        if (!preg_match('/\{OTHER\|+[0-9]+\}/', $s)) {
            return false;
        }
        $s   = explode('|', preg_replace('/[\{\}]/', '', $s));
        $len = !empty($s[1]) ? $s[1] : $helper->getConfig('t_width');

        // --- reload ---
        //        $box = new \XoopsFormText('', 'other['.$id.']', $len, 255);
        $val = null;
        if (isset($_POST['other'][$id])) {
            $myts = \MyTextSanitizer::getInstance();
            $val  = $_POST['other'][$id];
            $val  = $myts->htmlSpecialChars($myts->stripSlashesGPC($val));
        }
        $box = new \XoopsFormText('', 'other[' . $id . ']', $len, 255, $val);

        // ------

        return $box->render();
    }
}
>>>>>>>> 718ab762b602b170f716178088c5475585b8f195:class/elementrenderer.php
