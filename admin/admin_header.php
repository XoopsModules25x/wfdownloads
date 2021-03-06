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
 * @license         GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package         wfdownload
 * @since           3.23
 * @author          Xoops Development Team
 */

use XoopsModules\Wfdownloads\{

    Helper,
    Utility
};
use Xmf\Module\Admin;

/** @var Helper $helper */
/** @var Utility $utility */

$moduleDirName = basename(dirname(__DIR__));
require_once dirname(__DIR__, 3) . '/mainfile.php';
require_once $GLOBALS['xoops']->path('www/include/cp_functions.php');
require_once $GLOBALS['xoops']->path('www/include/cp_header.php');
require_once $GLOBALS['xoops']->path('www/class/xoopsformloader.php');
xoops_load('XoopsUserUtility');

// require_once  dirname(__DIR__) . '/class/Utility.php';
require_once dirname(__DIR__) . '/include/common.php';

$helper = Helper::getInstance();

/** @var Admin $adminObject */
$adminObject = Admin::getInstance();

$pathIcon16    = Xmf\Module\Admin::iconUrl('', 16);
$pathIcon32    = Xmf\Module\Admin::iconUrl('', 32);
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

$myts = \MyTextSanitizer::getInstance();

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof \XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $GLOBALS['xoopsTpl'] = new \XoopsTpl();
}

//Module specific elements
//require_once $GLOBALS['xoops']->path("modules/{$moduleDirName}/include/functions.php");
//require_once $GLOBALS['xoops']->path("modules/{$moduleDirName}/config/config.php");

// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('main');

//xoops_cp_header();
