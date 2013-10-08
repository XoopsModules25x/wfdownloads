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
include_once dirname(dirname(dirname(dirname(__FILE__)))) . '/mainfile.php';

// Include xoops admin header
include_once XOOPS_ROOT_PATH . '/include/cp_header.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
include_once XOOPS_ROOT_PATH . '/class/tree.php';
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
xoops_load ('XoopsUserUtility');
$myts = &MyTextSanitizer::getInstance();

$module_handler =& xoops_gethandler('module');
$xoopsModule =& $module_handler->getByDirname(basename(dirname(dirname(__FILE__))));

$pathIcon16 = XOOPS_URL . '/' . $xoopsModule->getInfo('icons16');
$pathIcon32 = XOOPS_URL . '/' . $xoopsModule->getInfo('icons32');
$pathModuleAdmin = XOOPS_ROOT_PATH . '/' . $xoopsModule->getInfo('dirmoduleadmin');
require_once $pathModuleAdmin . '/moduleadmin/moduleadmin.php';

include_once dirname(dirname(__FILE__)) . '/include/common.php';

/*$imagearray = array(
    'editimg' => "<img src='" . $pathIcon16 . '/edit.png'."'  alt='" . _AM_WFDOWNLOADS_ICO_EDIT . "' title='" . _AM_WFDOWNLOADS_ICO_EDIT . "' align='middle'>",
    'deleteimg' => "<img src='" . $pathIcon16 . '/delete.png'."' alt='" . _AM_WFDOWNLOADS_ICO_DELETE . "' title='" . _AM_WFDOWNLOADS_ICO_DELETE . "' align='middle'>",
    'online' => "<img src='" . $pathIcon16 . '/1.png'."' alt='" . _AM_WFDOWNLOADS_ICO_ONLINE . "' title='" . _AM_WFDOWNLOADS_ICO_ONLINE . "' align='middle'>",
    'offline' => "<img src='" . $pathIcon16 . '/0.png'."' alt='" . _AM_WFDOWNLOADS_ICO_OFFLINE . "' title='" . _AM_WFDOWNLOADS_ICO_OFFLINE . "' align='middle'>",
    'approved' => "<img src='" . $pathIcon16 . '/on.png'."' alt=''" . _AM_WFDOWNLOADS_ICO_APPROVED . "' title=''" . _AM_WFDOWNLOADS_ICO_APPROVED . "' align='middle'>",
    'notapproved' => "<img src='" . $pathIcon16 . '/off.png'."'  alt='" . _AM_WFDOWNLOADS_ICO_NOTAPPROVED . "' title='" . _AM_WFDOWNLOADS_ICO_NOTAPPROVED . "' align='middle'>",
    'relatedfaq' => "<img src='../images/icon/link.png' alt='" . _AM_WFDOWNLOADS_ICO_LINK . "' title='" . _AM_WFDOWNLOADS_ICO_LINK . "' align='middle'>",
    'relatedurl' => "<img src='../images/icon/world_link.png' alt='" . _AM_WFDOWNLOADS_ICO_URL . "' title='" . _AM_WFDOWNLOADS_ICO_URL . "' align='middle'>",
    'addfaq' => "<img src='" . $pathIcon16 . '/add.png'."' alt='" . _AM_WFDOWNLOADS_ICO_ADD . "' title='" . _AM_WFDOWNLOADS_ICO_ADD . "' align='middle'>",
    'approve' => "<img src='" . $pathIcon16 . '/on.png'."' alt='" . _AM_WFDOWNLOADS_ICO_APPROVE . "' title='" . _AM_WFDOWNLOADS_ICO_APPROVE . "' align='middle'>",
    'statsimg' => "<img src='../images/icon/statistics.png' alt='" . _AM_WFDOWNLOADS_ICO_STATS . "' title='" . _AM_WFDOWNLOADS_ICO_STATS . "' align='middle'>",
    'ignore' => "<img src='../images/icon/ignore.png' alt='" . _AM_WFDOWNLOADS_ICO_IGNORE . "' title='" . _AM_WFDOWNLOADS_ICO_IGNORE . "' align='middle'>",
    'ack_yes' => "<img src='" . $pathIcon16 . '/on.png'."' alt='" . _AM_WFDOWNLOADS_ICO_ACK . "' title='" . _AM_WFDOWNLOADS_ICO_ACK . "' align='middle'>",
    'ack_no' => "<img src='" . $pathIcon16 . '/off.png'."' alt='" . _AM_WFDOWNLOADS_ICO_REPORT . "' title='" . _AM_WFDOWNLOADS_ICO_REPORT . "' align='middle'>",
    'con_yes' => "<img src='" . $pathIcon16 . '/on.png'."' alt='" . _AM_WFDOWNLOADS_ICO_CONFIRM . "' title='" . _AM_WFDOWNLOADS_ICO_CONFIRM . "' align='middle'>",
    'con_no' => "<img src='" . $pathIcon16 . '/off.png'."' alt='" . _AM_WFDOWNLOADS_ICO_CONBROKEN . "' title='" . _AM_WFDOWNLOADS_ICO_CONBROKEN . "' align='middle'>"
    );*/

//if ( file_exists($GLOBALS['xoops']->path('/' . $xoopsModule->getInfo('dirmoduleadmin') . '/moduleadmin/moduleadmin.php'))) {
//    include_once $GLOBALS['xoops']->path('/' . $xoopsModule->getInfo('dirmoduleadmin') . '/moduleadmin/moduleadmin.php');
//} else {
//    echo xoops_error('/' . $xoopsModule->getInfo('dirmoduleadmin') . '/moduleadmin/ is required!!!');
//}

// Load language files
xoops_loadLanguage('admin', $xoopsModule->dirname());
xoops_loadLanguage('modinfo', $xoopsModule->dirname());
xoops_loadLanguage('main', $xoopsModule->dirname());

// Load Xoops handlers
$member_handler = xoops_gethandler('member');
$notification_handler = &xoops_gethandler('notification');
$gperm_handler = xoops_gethandler('groupperm');

if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
    include_once(XOOPS_ROOT_PATH . "/class/template.php");
    $xoopsTpl = new XoopsTpl();
}
