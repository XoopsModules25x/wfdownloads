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
//$module = $module_handler->getByDirname(basename(dirname(__DIR__)));
include_once dirname(__DIR__) . '/include/common.php';
$wfdownloads = WfdownloadsWfdownloads::getInstance();
$pathIcon32  = '../../' . $wfdownloads->getModule()->getInfo('icons32');

xoops_loadLanguage('modinfo', $wfdownloads->getModule()->dirname());

$adminmenu              = array();

$adminmenu[] = array(
    'title' => _MI_WFDOWNLOADS_MENU_HOME,
    'desc'  => _MI_WFDOWNLOADS_MENU_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32.'/home.png'
);

$adminmenu[] = array(
    'title' => _MI_WFDOWNLOADS_MENU_CATEGORIES,
    'desc'  => _MI_WFDOWNLOADS_MENU_CATEGORIES,
    'link'  => 'admin/categories.php',
    'icon'  => $pathIcon32.'/category.png'
);

$adminmenu[] = array(
    'title' => _MI_WFDOWNLOADS_MENU_DOWNLOADS,
    'desc'  => _MI_WFDOWNLOADS_MENU_DOWNLOADS,
    'link'  => 'admin/downloads.php',
    'icon'  => $pathIcon32.'/download.png'
);

$adminmenu[] = array(
    'title' => _MI_WFDOWNLOADS_MENU_REVIEWS,
    'desc'  => _MI_WFDOWNLOADS_MENU_REVIEWS,
    'link'  => 'admin/reviews.php',
    'icon'  => $pathIcon32.'/translations.png'
);

$adminmenu[] = array(
    'title' => _MI_WFDOWNLOADS_MENU_RATINGS,
    'desc'  => _MI_WFDOWNLOADS_MENU_RATINGS,
    'link'  => 'admin/ratings.php',
    'icon'  => $pathIcon32.'/button_ok.png'
);

$adminmenu[] = array(
    'title' => _MI_WFDOWNLOADS_MENU_REPORTSMODIFICATIONS,
    'desc'  => _MI_WFDOWNLOADS_MENU_REPORTSMODIFICATIONS,
    'link'  => 'admin/reportsmodifications.php',
    'icon'  => $pathIcon32.'/alert.png'
);

$adminmenu[] = array(
    'title' => _MI_WFDOWNLOADS_MENU_MIRRORS,
    'desc'  => _MI_WFDOWNLOADS_MENU_MIRRORS,
    'link'  => 'admin/mirrors.php',
    'icon'  => $pathIcon32.'/list.png'
);

$adminmenu[] = array(
    'title' => _MI_WFDOWNLOADS_MENU_INDEXPAGE,
    'desc'  => _MI_WFDOWNLOADS_MENU_INDEXPAGE,
    'link'  => 'admin/indexpage.php',
    'icon'  => $pathIcon32.'/index.png'
);

/*
// Swish-e support EXPERIMENTAL
if ($wfdownloads->getConfig('enable_swishe') == true) {
$adminmenu[] = array(
    'title' => _MI_WFDOWNLOADS_MENU_SWISHE,
    'desc'  => _MI_WFDOWNLOADS_MENU_SWISHE,
    'link'  => 'admin/swishe.php',
    'icon'  => $pathIcon32.'/search.png'
);
}
// Swish-e support EXPERIMENTAL
*/

$adminmenu[] = array(
    'title' => _MI_WFDOWNLOADS_MENU_IMAGES,
    'desc'  => _MI_WFDOWNLOADS_MENU_IMAGES,
    'link'  => 'admin/images.php',
    'icon'  => $pathIcon32.'/photo.png'
);

$adminmenu[] = array(
    'title' => _MI_WFDOWNLOADS_MENU_MIMETYPES,
    'desc'  => _MI_WFDOWNLOADS_MENU_MIMETYPES,
    'link'  => 'admin/mimetypes.php',
    'icon'  => $pathIcon32.'/type.png'
);

$adminmenu[] = array(
    'title' => _MI_WFDOWNLOADS_MENU_PERMISSIONS,
    'desc'  => _MI_WFDOWNLOADS_MENU_PERMISSIONS,
    'link'  => 'admin/permissions.php',
    'icon'  => $pathIcon32.'/permissions.png'
);

$adminmenu[] = array(
    'title' => _MI_WFDOWNLOADS_MENU_IMPORT,
    'desc'  => _MI_WFDOWNLOADS_MENU_IMPORT,
    'link'  => 'admin/import.php',
    'icon'  => $pathIcon32.'/database_go.png'
);

$adminmenu[] = array(
    'title' => _MI_WFDOWNLOADS_MENU_CLONE,
    'desc'  => _MI_WFDOWNLOADS_MENU_CLONE,
    'link'  => 'admin/clone.php',
    'icon'  => './assets/images/icon32/editcopy.png'
);

$adminmenu[] = array(
    'title' => _MI_WFDOWNLOADS_MENU_ABOUT,
    'desc'  => _MI_WFDOWNLOADS_MENU_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32.'/about.png'
);
