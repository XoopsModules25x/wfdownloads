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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         wfdownload
 * @since           3.23
 * @author          Xoops Development Team
 */
defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

// This must contain the name of the folder in which reside Wfdownloads
define('WFDOWNLOADS_DIRNAME', basename(dirname(__DIR__)));
define('WFDOWNLOADS_URL', XOOPS_URL . '/modules/' . WFDOWNLOADS_DIRNAME);
define('WFDOWNLOADS_IMAGES_URL', WFDOWNLOADS_URL . '/assets/images');
define('WFDOWNLOADS_ADMIN_URL', WFDOWNLOADS_URL . '/admin');
define('WFDOWNLOADS_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . WFDOWNLOADS_DIRNAME);

xoops_loadLanguage('common', WFDOWNLOADS_DIRNAME);

include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
include_once XOOPS_ROOT_PATH . '/class/tree.php';
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

include_once WFDOWNLOADS_ROOT_PATH . '/class/utilities.php';
include_once WFDOWNLOADS_ROOT_PATH . '/include/constants.php';
include_once WFDOWNLOADS_ROOT_PATH . '/class/session.php'; // WfdownloadsSession class
include_once WFDOWNLOADS_ROOT_PATH . '/class/wfdownloads.php'; // WfdownloadsWfdownloads class
//include_once WFDOWNLOADS_ROOT_PATH . '/class/wfdownloads.php'; // WfdownloadsRequest class
include_once WFDOWNLOADS_ROOT_PATH . '/class/breadcrumb.php'; // WfdownloadsBreadcrumb class
include_once WFDOWNLOADS_ROOT_PATH . '/class/tree.php'; // WfdownloadsObjectTree class
include_once WFDOWNLOADS_ROOT_PATH . '/class/xoopstree.php'; // WfdownloadsXoopsTree class
//include_once WFDOWNLOADS_ROOT_PATH . '/class/formelementchoose.php'; // WfdownloadsFormElementChoose class
include_once WFDOWNLOADS_ROOT_PATH . '/class/multicolumnsthemeform.php'; // WfdownloadsMulticolumnsThemeForm class

xoops_load('XoopsUserUtility');
xoops_load('XoopsLocal');
xoops_load('XoopsRequest');
// MyTextSanitizer object
$myts = MyTextSanitizer::getInstance();

$debug       = false;
$wfdownloads = WfdownloadsWfdownloads::getInstance($debug);

//This is needed or it will not work in blocks.
global $wfdownloads_isAdmin;

// Load only if module is installed
if (is_object($wfdownloads->getModule())) {
    // Find if the user is admin of the module
    $wfdownloads_isAdmin = WfdownloadsUtilities::userIsAdmin();
}

// Load Xoops handlers
$moduleHandler       = xoops_getHandler('module');
$memberHandler       = xoops_getHandler('member');
$notificationHandler = xoops_getHandler('notification');
$gpermHandler        = xoops_getHandler('groupperm');
