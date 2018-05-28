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

use XoopsModules\Wfdownloads;
use XoopsModules\Wfdownloads\Common;

$currentFile = basename(__FILE__);
require_once __DIR__ . '/admin_header.php';

define('INDEX_FILE_PATH', XOOPS_ROOT_PATH . '/uploads/index.html');
define('BLANK_FILE_PATH', XOOPS_ROOT_PATH . '/uploads/blank.png');

// require_once  dirname(__DIR__) . '/class/common/directorychecker.php';
// require_once  dirname(__DIR__) . '/class/common/filechecker.php';

xoops_cp_header();
$adminObject = \Xmf\Module\Admin::getInstance();

/** @var \XoopsModules\Wfdownloads\Helper $helper */
$helper = \XoopsModules\Wfdownloads\Helper::getInstance();

$moduleDirName = basename(dirname(__DIR__));
$moduleDirNameUpper = strtoupper($moduleDirName);

//--------------------------
$categories_count           = Wfdownloads\Utility::categoriesCount();
$votes_count                = $helper->getHandler('Rating')->getCount();
$brokenDownloads_count      = $helper->getHandler('Report')->getCount();
$modificationRequests_count = $helper->getHandler('Modification')->getCount();
$newReviews_count           = $helper->getHandler('Review')->getCount();
$newMirrors_count           = $helper->getHandler('Mirror')->getCount();
$newDownloads_count         = $helper->getHandler('Download')->getCount(new \Criteria('published', 0));
$downloads_count            = $helper->getHandler('Download')->getCount(new \Criteria('published', 0, '>'));

$adminObject->addInfoBox(_AM_WFDOWNLOADS_MINDEX_DOWNSUMMARY);
// Categories
if ($categories_count > 0) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel><a href="categories.php">' . _AM_WFDOWNLOADS_SCATEGORY . '</a></infolabel>', $categories_count, 'green'));
} else {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_SCATEGORY . '</infolabel>', $categories_count, 'green'));
}
// Downloads
if ($downloads_count > 0) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel><a href="downloads.php">' . _AM_WFDOWNLOADS_SFILES . '</a><b></infolabel>', $downloads_count, 'green'));
} else {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_SFILES . '</infolabel>', $downloads_count, 'green'));
}
// New/waiting downloads
if ($newDownloads_count > 0) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel><a href="downloads.php">' . _AM_WFDOWNLOADS_SNEWFILESVAL . '</a></infolabel>', $newDownloads_count, 'green'));
} else {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_SNEWFILESVAL . '</infolabel>', $newDownloads_count, 'green'));
}
// Reviews
if (false === $helper->getConfig('enable_reviews')) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_SREVIEWS . '</infolabel>', _CO_WFDOWNLOADS_DISABLED, 'red'));
} elseif ($newReviews_count > 0) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel><a href="reviews.php">' . _AM_WFDOWNLOADS_SREVIEWS . '</a></infolabel>', $newReviews_count, 'green'));
} else {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_SREVIEWS . '</infolabel>', $newReviews_count, 'green'));
}
// Ratings
if (false === $helper->getConfig('enable_ratings')) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_SVOTES . '</infolabel>', _CO_WFDOWNLOADS_DISABLED, 'red'));
} elseif ($votes_count > 0) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel><a href="ratings.php">' . _AM_WFDOWNLOADS_SVOTES . '</a></infolabel>', $votes_count, 'green'));
} else {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_SVOTES . '</infolabel>', $votes_count, 'green'));
}
// Modifications
if ($modificationRequests_count > 0) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel><a href="reportsmodifications.php">' . _AM_WFDOWNLOADS_SMODREQUEST . '</a></infolabel>', $modificationRequests_count, 'green'));
} else {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_SMODREQUEST . '</infolabel>', $modificationRequests_count, 'green'));
}
// Brokens reports
if (false === $helper->getConfig('enable_brokenreports')) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_SBROKENSUBMIT . '</infolabel>', _CO_WFDOWNLOADS_DISABLED, 'red'));
} elseif ($brokenDownloads_count > 0) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel><a href="reportsmodifications.php">' . _AM_WFDOWNLOADS_SBROKENSUBMIT . '</a></infolabel>', $brokenDownloads_count, 'green'));
} else {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_SBROKENSUBMIT . '</infolabel>', $brokenDownloads_count, 'green'));
}
// Mirrors
if (false === $helper->getConfig('enable_mirrors')) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_SMIRRORS . '</infolabel>', _CO_WFDOWNLOADS_DISABLED, 'red'));
} elseif ($newMirrors_count > 0) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel><a href="mirrors.php">' . _AM_WFDOWNLOADS_SMIRRORS . '</a></infolabel>', $newMirrors_count, 'green'));
} else {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_SMIRRORS . '</infolabel>', $newMirrors_count, 'green'));
}
// module max file size
$adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_DOWN_MODULE_MAXFILESIZE . '</infolabel>', Wfdownloads\Utility::bytesToSize1024($helper->getConfig('maxfilesize')), 'green'));
// upload file size limit
// get max file size (setup and php.ini)
$phpiniMaxFileSize = min((int)ini_get('upload_max_filesize'), (int)ini_get('post_max_size'), (int)ini_get('memory_limit')) * 1024 * 1024; // bytes
$maxFileSize       = Wfdownloads\Utility::bytesToSize1024(min($helper->getConfig('maxfilesize'), $phpiniMaxFileSize));
$adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_UPLOAD_MAXFILESIZE . '</infolabel>', $maxFileSize, 'green'));

//------ check directories ---------------

$adminObject->addConfigBoxLine('');
$redirectFile = $_SERVER['PHP_SELF'];

//check Formulize presence
if (!Wfdownloads\Utility::checkModule('formulize')) {
    $adminObject->addConfigBoxLine(_AM_WFDOWNLOADS_FORMULIZE_NOT_AVILABLE);
} else {
    $adminObject->addConfigBoxLine(_AM_WFDOWNLOADS_FORMULIZE_AVAILABLE);
}

//check directories
$adminObject->addConfigBoxLine('');
//$path = $helper->getConfig('uploaddir') . '/';
$path = $helper->getConfig('uploaddir');
$adminObject->addConfigBoxLine(Common\DirectoryChecker::getDirectoryStatus($path, 0777, $redirectFile));

$path = $helper->getConfig('batchdir') . '/';
$adminObject->addConfigBoxLine(Common\DirectoryChecker::getDirectoryStatus($path, 0777, $redirectFile));

$path = XOOPS_ROOT_PATH . '/' . $helper->getConfig('mainimagedir') . '/';
$adminObject->addConfigBoxLine(Common\DirectoryChecker::getDirectoryStatus($path, 0777, $redirectFile));
//$adminObject->addConfigBoxLine(FileChecker::getFileStatus($path . 'blank.png', BLANK_FILE_PATH, $redirectFile));

$path = XOOPS_ROOT_PATH . '/' . $helper->getConfig('screenshots') . '/';
$adminObject->addConfigBoxLine(Common\DirectoryChecker::getDirectoryStatus($path, 0777, $redirectFile));
//$adminObject->addConfigBoxLine(FileChecker::getFileStatus($path . 'blank.png', BLANK_FILE_PATH, $redirectFile));
$adminObject->addConfigBoxLine(Common\DirectoryChecker::getDirectoryStatus($path . 'thumbs' . '/', 0777, $redirectFile));
//$adminObject->addConfigBoxLine(FileChecker::getFileStatus($path . 'thumbs' . '/' . 'blank.png', BLANK_FILE_PATH, $redirectFile));

$path = XOOPS_ROOT_PATH . '/' . $helper->getConfig('catimage') . '/';
$adminObject->addConfigBoxLine(Common\DirectoryChecker::getDirectoryStatus($path, 0777, $redirectFile));
//$adminObject->addConfigBoxLine(FileChecker::getFileStatus($path . 'blank.png', BLANK_FILE_PATH, $redirectFile));
$adminObject->addConfigBoxLine(Common\DirectoryChecker::getDirectoryStatus($path . 'thumbs' . '/', 0777, $redirectFile));
//$adminObject->addConfigBoxLine(FileChecker::getFileStatus($path . 'thumbs' . '/' . 'blank.png', BLANK_FILE_PATH, $redirectFile));

//---------------------------
$adminObject->addConfigBoxLine('');

/** @var Wfdownloads\Common\Configurator $configurator */
$configurator = new Wfdownloads\Common\Configurator();

/** @var Wfdownloads\Utility $utility */
$utility = new Wfdownloads\Utility();

foreach (array_keys($configurator->uploadFolders) as $i) {
    $utility::createFolder($configurator->uploadFolders[$i]);
}

$adminObject->displayNavigation(basename(__FILE__));

//------------- Test Data ----------------------------

if ($helper->getConfig('displaySampleButton')) {
    xoops_loadLanguage('admin/modulesadmin', 'system');
    require_once  dirname(__DIR__) . '/testdata/index.php';

    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'ADD_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=load', 'add');

    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SAVE_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=save', 'add');

    //    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA'), '__DIR__ . /../../testdata/index.php?op=exportschema', 'add');

    $adminObject->displayButton('left', '');
}

//------------- End Test Data ----------------------------

$adminObject->displayIndex();
echo $utility::getServerStats();

require_once __DIR__ . '/admin_footer.php';
