<?php
/* ******************************************* */

/*                INFORMATUX                   */
/*         http://www.informatux.com/          */
/*       SOLUTIONS AND WEB DEVELOPMENT         */
/*             Patrice BOUTHIER                */
/*                   2015                      */
/* ------------------------------------------- */
/*    XOOPS - PHP Content Management System    */
/*         Copyright (c) 2000-2020 XOOPS.org        */
/*            <https://xoops.org>          */
/* ******************************************* */

/**
 * @param false $form_order
 * @param bool  $created
 * @return false|int
 */
function getFormsCount($form_order = false, $created = true)
{
    // query database for forms created
    global $xoopsDB;
    if ($created && false === $form_order) {
        // Created Forms
        $sql    = 'SELECT * FROM ' . $xoopsDB->prefix('xliaise_forms');
        $result = $xoopsDB->query($sql);
        if (!$result) {
            return false;
        }
        $num_forms = $xoopsDB->getRowsNum($result);
        if (0 == $num_forms) {
            return false;
        }
    } else {
        // Activated Forms
        $sql    = 'SELECT * FROM ' . $xoopsDB->prefix('xliaise_forms') . ' WHERE form_order >= "1"';
        $result = $xoopsDB->query($sql);
        if (!$result) {
            return false;
        }
        $num_forms = $xoopsDB->getRowsNum($result);
        if (0 == $num_forms) {
            return false;
        }
    }

    return $num_forms;
}

/**
 * @param $form_id
 * @return array|false
 */
function getFormTitle($form_id)
{
    // query database for form title
    global $xoopsDB;
    if (isset($form_id)) {
        $sql    = 'SELECT form_title FROM ' . $xoopsDB->prefix('xliaise_forms') . ' WHERE form_id = "' . $form_id . '"';
        $result = $xoopsDB->query($sql);
        if (!$result) {
            return false;
        }

        return $xoopsDB->fetchArray($result);
    }

    return false;
}
