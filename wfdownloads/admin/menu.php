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

//$module_handler = xoops_gethandler('module');
//$module = $module_handler->getByDirname(basename(dirname(dirname(__FILE__))));
include_once dirname(dirname(__FILE__)) . '/include/common.php';
$wfdownloads = WfdownloadsWfdownloads::getInstance();
$pathIcon32 = '../../' . $wfdownloads->getModule()->getInfo('icons32');

xoops_loadLanguage('modinfo', $wfdownloads->getModule()->dirname());

$adminmenu = array();
$i=0;
$adminmenu[$i]["title"] = _MI_WFDOWNLOADS_MENU_HOME;
$adminmenu[$i]['desc'] = _MI_WFDOWNLOADS_MENU_HOME;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]['icon']  = $pathIcon32 . '/home.png';
++$i;
$adminmenu[$i]['title'] = _MI_WFDOWNLOADS_MENU_CATEGORIES;
$adminmenu[$i]['desc'] = _MI_WFDOWNLOADS_MENU_CATEGORIES;
$adminmenu[$i]['link'] = "admin/categories.php";
$adminmenu[$i]['icon']  = $pathIcon32 . '/category.png';
++$i;
$adminmenu[$i]['title'] = _MI_WFDOWNLOADS_MENU_DOWNLOADS;
$adminmenu[$i]['desc'] = _MI_WFDOWNLOADS_MENU_DOWNLOADS;
$adminmenu[$i]['link'] = "admin/downloads.php";
$adminmenu[$i]['icon']  = $pathIcon32 . '/download.png';
++$i;
$adminmenu[$i]['title'] = _MI_WFDOWNLOADS_MENU_REVIEWS;
$adminmenu[$i]['desc'] = _MI_WFDOWNLOADS_MENU_REVIEWS;
$adminmenu[$i]['link'] = "admin/reviews.php";
$adminmenu[$i]['icon']  = $pathIcon32 . '/translations.png';
++$i;
$adminmenu[$i]['title'] = _MI_WFDOWNLOADS_MENU_RATINGS;
$adminmenu[$i]['desc'] = _MI_WFDOWNLOADS_MENU_RATINGS;
$adminmenu[$i]['link'] = "admin/ratings.php";
$adminmenu[$i]['icon']  = $pathIcon32 . '/button_ok.png';
++$i;
$adminmenu[$i]['title'] = _MI_WFDOWNLOADS_MENU_REPORTSMODIFICATIONS;
$adminmenu[$i]['desc'] = _MI_WFDOWNLOADS_MENU_REPORTSMODIFICATIONS;
$adminmenu[$i]['link'] = "admin/reportsmodifications.php";
$adminmenu[$i]['icon']  = $pathIcon32 . '/alert.png';
++$i;
$adminmenu[$i]['title'] = _MI_WFDOWNLOADS_MENU_MIRRORS;
$adminmenu[$i]['desc'] = _MI_WFDOWNLOADS_MENU_MIRRORS;
$adminmenu[$i]['link'] = "admin/mirrors.php";
$adminmenu[$i]['icon']  = $pathIcon32 . '/list.png';
++$i;
$adminmenu[$i]['title'] = _MI_WFDOWNLOADS_MENU_INDEXPAGE;
$adminmenu[$i]['desc'] = _MI_WFDOWNLOADS_MENU_INDEXPAGE;
$adminmenu[$i]['link'] = "admin/indexpage.php";
$adminmenu[$i]['icon']  = $pathIcon32 . '/index.png';
/*
// Swish-e support EXPERIMENTAL
if ($wfdownloads->getConfig('enable_swishe') == true) {
    ++$i;
    $adminmenu[$i]['title'] = _MI_WFDOWNLOADS_MENU_SWISHE;
    $adminmenu[$i]['desc'] =
    $adminmenu[$i]['link'] = "admin/swishe.php";
    $adminmenu[$i]['icon']  = $pathIcon32 . '/search.png';
}
// Swish-e support EXPERIMENTAL
*/
++$i;
$adminmenu[$i]['title'] = _MI_WFDOWNLOADS_MENU_IMAGES;
$adminmenu[$i]['desc'] = _MI_WFDOWNLOADS_MENU_IMAGES;
$adminmenu[$i]['link'] = "admin/images.php";
$adminmenu[$i]['icon']  = $pathIcon32 . '/photo.png';
++$i;
$adminmenu[$i]['title'] = _MI_WFDOWNLOADS_MENU_MIMETYPES;
$adminmenu[$i]['desc'] = _MI_WFDOWNLOADS_MENU_MIMETYPES;
$adminmenu[$i]['link'] = "admin/mimetypes.php";
$adminmenu[$i]['icon']  = $pathIcon32 . '/type.png';
++$i;
$adminmenu[$i]['title'] = _MI_WFDOWNLOADS_MENU_PERMISSIONS;
$adminmenu[$i]['desc'] = _MI_WFDOWNLOADS_MENU_PERMISSIONS;
$adminmenu[$i]['link'] = "admin/permissions.php";
$adminmenu[$i]['icon']  = $pathIcon32 . '/permissions.png';
++$i;
$adminmenu[$i]['title'] = _MI_WFDOWNLOADS_MENU_IMPORT;
$adminmenu[$i]['desc'] = _MI_WFDOWNLOADS_MENU_IMPORT;
$adminmenu[$i]['link'] = "admin/import.php";
$adminmenu[$i]['icon']  = './assets/images/icon32/database_go.png';
++$i;
$adminmenu[$i]['title'] = _MI_WFDOWNLOADS_MENU_CLONE;
$adminmenu[$i]['desc'] = _MI_WFDOWNLOADS_MENU_CLONE;
$adminmenu[$i]['link'] = "admin/clone.php";
$adminmenu[$i]['icon'] = './assets/images/icon32/editcopy.png';
++$i;
$adminmenu[$i]['title'] = _MI_WFDOWNLOADS_MENU_ABOUT;
$adminmenu[$i]['desc'] = _MI_WFDOWNLOADS_MENU_ABOUT;
$adminmenu[$i]['link'] =  "admin/about.php";
$adminmenu[$i]['icon']  = $pathIcon32 . '/about.png';
