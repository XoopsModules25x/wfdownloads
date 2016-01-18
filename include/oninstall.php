<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * Wfdownloads module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         wfdownload
 * @since           3.23
 * @author          Xoops Development Team
 * @version         svn:$id$
 */
defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');
include_once __DIR__ . '/common.php';
//@include_once WFDOWNLOADS_ROOT_PATH . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/admin.php';
xoops_loadLanguage('admin', $wfdownloads->getModule()->dirname());

define('INDEX_FILE_PATH', XOOPS_ROOT_PATH . '/uploads/index.html');
define('BLANK_FILE_PATH', XOOPS_ROOT_PATH . '/uploads/blank.gif');

/**
 * @param $xoopsModule
 *
 * @return bool
 */
function xoops_module_pre_install_wfdownloads(&$xoopsModule)
{
    // NOP
    return true;
}

/**
 * @param $xoopsModule
 *
 * @return bool
 */
function xoops_module_install_wfdownloads(&$xoopsModule)
{
    // get module config values
    $hModConfig  = xoops_gethandler('config');
    $configArray = $hModConfig->getConfigsByCat(0, $xoopsModule->getVar('mid'));

    // create and populate directories with empty blank.gif and index.html
    $path = $configArray['uploaddir'];
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
    chmod($path, 0777);
    copy(INDEX_FILE_PATH, $path . '/index.html');
    //
    $path = $configArray['batchdir'];
    if (!is_dir($path)) {
        mkdir($path, 0777);
    }
    chmod($path, 0777);
    copy(INDEX_FILE_PATH, $path . '/index.html');
    //
    $path = XOOPS_ROOT_PATH . '/' . $configArray['mainimagedir'];
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
    chmod($path, 0777);
    copy(INDEX_FILE_PATH, $path . '/index.html');
    copy(BLANK_FILE_PATH, $path . '/blank.gif');
    //
    $path = XOOPS_ROOT_PATH . '/' . $configArray['screenshots'];
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
    chmod($path, 0777);
    copy(INDEX_FILE_PATH, $path . '/index.html');
    copy(BLANK_FILE_PATH, $path . '/blank.gif');
    //
    $path = XOOPS_ROOT_PATH . '/' . $configArray['screenshots'] . '/' . 'thumbs';
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
    chmod($path, 0777);
    copy(INDEX_FILE_PATH, $path . '/index.html');
    copy(BLANK_FILE_PATH, $path . '/blank.gif');
    //
    $path = XOOPS_ROOT_PATH . '/' . $configArray['catimage'];
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
    chmod($path, 0777);
    copy(INDEX_FILE_PATH, $path . '/index.html');
    copy(BLANK_FILE_PATH, $path . '/blank.gif');
    //
    $path = XOOPS_ROOT_PATH . '/' . $configArray['catimage'] . '/' . 'thumbs';
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
    chmod($path, 0777);
    copy(INDEX_FILE_PATH, $path . '/index.html');
    copy(BLANK_FILE_PATH, $path . '/blank.gif');

    return true;
}
