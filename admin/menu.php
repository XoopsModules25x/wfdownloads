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

use Xmf\Module\Admin;
use XoopsModules\Wfdownloads\{
    Helper
};
/** @var Admin $adminObject */
/** @var Helper $helper */

$moduleDirName = basename(dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

$helper = Helper::getInstance();
$helper->loadLanguage('common');
$helper->loadLanguage('feedback');

$pathIcon32 = Admin::menuIconPath('');
if (is_object($helper->getModule())) {
    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');
}

$adminmenu[] = [
    'title' => _MI_WFDOWNLOADS_MENU_HOME,
    'desc'  => _MI_WFDOWNLOADS_MENU_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png',
];

$adminmenu[] = [
    'title' => _MI_WFDOWNLOADS_MENU_CATEGORIES,
    'desc'  => _MI_WFDOWNLOADS_MENU_CATEGORIES,
    'link'  => 'admin/categories.php',
    'icon'  => $pathIcon32 . '/category.png',
];

$adminmenu[] = [
    'title' => _MI_WFDOWNLOADS_MENU_DOWNLOADS,
    'desc'  => _MI_WFDOWNLOADS_MENU_DOWNLOADS,
    'link'  => 'admin/downloads.php',
    'icon'  => $pathIcon32 . '/download.png',
];

$adminmenu[] = [
    'title' => _MI_WFDOWNLOADS_MENU_REVIEWS,
    'desc'  => _MI_WFDOWNLOADS_MENU_REVIEWS,
    'link'  => 'admin/reviews.php',
    'icon'  => $pathIcon32 . '/translations.png',
];

$adminmenu[] = [
    'title' => _MI_WFDOWNLOADS_MENU_RATINGS,
    'desc'  => _MI_WFDOWNLOADS_MENU_RATINGS,
    'link'  => 'admin/ratings.php',
    'icon'  => $pathIcon32 . '/button_ok.png',
];

$adminmenu[] = [
    'title' => _MI_WFDOWNLOADS_MENU_REPORTSMODIFICATIONS,
    'desc'  => _MI_WFDOWNLOADS_MENU_REPORTSMODIFICATIONS,
    'link'  => 'admin/reportsmodifications.php',
    'icon'  => $pathIcon32 . '/alert.png',
];

$adminmenu[] = [
    'title' => _MI_WFDOWNLOADS_MENU_MIRRORS,
    'desc'  => _MI_WFDOWNLOADS_MENU_MIRRORS,
    'link'  => 'admin/mirrors.php',
    'icon'  => $pathIcon32 . '/list.png',
];

$adminmenu[] = [
    'title' => _MI_WFDOWNLOADS_MENU_INDEXPAGE,
    'desc'  => _MI_WFDOWNLOADS_MENU_INDEXPAGE,
    'link'  => 'admin/indexpage.php',
    'icon'  => $pathIcon32 . '/index.png',
];

/*
// Swish-e support EXPERIMENTAL
if ($helper->getConfig('enable_swishe') === true) {
$adminmenu[] = array(
    'title' => _MI_WFDOWNLOADS_MENU_SWISHE,
    'desc'  => _MI_WFDOWNLOADS_MENU_SWISHE,
    'link'  => 'admin/swishe.php',
    'icon'  => $pathIcon32.'/search.png'
);
}
// Swish-e support EXPERIMENTAL
*/

$adminmenu[] = [
    'title' => _MI_WFDOWNLOADS_MENU_IMAGES,
    'desc'  => _MI_WFDOWNLOADS_MENU_IMAGES,
    'link'  => 'admin/images.php',
    'icon'  => $pathIcon32 . '/photo.png',
];

$adminmenu[] = [
    'title' => _MI_WFDOWNLOADS_MENU_MIMETYPES,
    'desc'  => _MI_WFDOWNLOADS_MENU_MIMETYPES,
    'link'  => 'admin/mimetypes.php',
    'icon'  => $pathIcon32 . '/type.png',
];

$adminmenu[] = [
    'title' => _MI_WFDOWNLOADS_MENU_PERMISSIONS,
    'desc'  => _MI_WFDOWNLOADS_MENU_PERMISSIONS,
    'link'  => 'admin/permissions.php',
    'icon'  => $pathIcon32 . '/permissions.png',
];

$adminmenu[] = [
    'title' => _MI_WFDOWNLOADS_MENU_IMPORT,
    'desc'  => _MI_WFDOWNLOADS_MENU_IMPORT,
    'link'  => 'admin/import.php',
    'icon'  => $pathIcon32 . '/database_go.png',
];

$adminmenu[] = [
    'title' => _MI_WFDOWNLOADS_MENU_CLONE,
    'desc'  => _MI_WFDOWNLOADS_MENU_CLONE,
    'link'  => 'admin/clone.php',
    'icon'  => './assets/images/icon32/editcopy.png',
];

// Blocks Admin
$adminmenu[] = [
    'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'BLOCKS'),
    'link' => 'admin/blocksadmin.php',
    'icon' => $pathIcon32 . '/block.png',
];

if (is_object($helper->getModule()) && $helper->getConfig('displayDeveloperTools')) {
    $adminmenu[] = [
        'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'ADMENU_MIGRATE'),
        'link'  => 'admin/migrate.php',
        'icon'  => $pathIcon32 . '/database_go.png',
    ];
}

$adminmenu[] = [
    'title' => _MI_WFDOWNLOADS_MENU_ABOUT,
    'desc'  => _MI_WFDOWNLOADS_MENU_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png',
];
