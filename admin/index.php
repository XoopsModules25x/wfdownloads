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

use Xmf\Module\Helper;

$currentFile = basename(__FILE__);
require_once __DIR__ . '/admin_header.php';

define('INDEX_FILE_PATH', XOOPS_ROOT_PATH . '/uploads/index.html');
define('BLANK_FILE_PATH', XOOPS_ROOT_PATH . '/uploads/blank.png');

require_once __DIR__ . '/../class/common/directorychecker.php';
require_once __DIR__ . '/../class/common/filechecker.php';

xoops_cp_header();
$adminObject = \Xmf\Module\Admin::getInstance();

//--------------------------
$categories_count           = WfdownloadsUtility::categoriesCount();
$votes_count                = $wfdownloads->getHandler('rating')->getCount();
$brokenDownloads_count      = $wfdownloads->getHandler('report')->getCount();
$modificationRequests_count = $wfdownloads->getHandler('modification')->getCount();
$newReviews_count           = $wfdownloads->getHandler('review')->getCount();
$newMirrors_count           = $wfdownloads->getHandler('mirror')->getCount();
$newDownloads_count         = $wfdownloads->getHandler('download')->getCount(new Criteria('published', 0));
$downloads_count            = $wfdownloads->getHandler('download')->getCount(new Criteria('published', 0, '>'));

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
if ($wfdownloads->getConfig('enable_reviews') === false) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_SREVIEWS . '</infolabel>', _CO_WFDOWNLOADS_DISABLED, 'red'));
} elseif ($newReviews_count > 0) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel><a href="reviews.php">' . _AM_WFDOWNLOADS_SREVIEWS . '</a></infolabel>', $newReviews_count, 'green'));
} else {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_SREVIEWS . '</infolabel>', $newReviews_count, 'green'));
}
// Ratings
if ($wfdownloads->getConfig('enable_ratings') === false) {
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
if ($wfdownloads->getConfig('enable_brokenreports') === false) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_SBROKENSUBMIT . '</infolabel>', _CO_WFDOWNLOADS_DISABLED, 'red'));
} elseif ($brokenDownloads_count > 0) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel><a href="reportsmodifications.php">' . _AM_WFDOWNLOADS_SBROKENSUBMIT . '</a></infolabel>', $brokenDownloads_count, 'green'));
} else {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_SBROKENSUBMIT . '</infolabel>', $brokenDownloads_count, 'green'));
}
// Mirrors
if ($wfdownloads->getConfig('enable_mirrors') === false) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_SMIRRORS . '</infolabel>', _CO_WFDOWNLOADS_DISABLED, 'red'));
} elseif ($newMirrors_count > 0) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel><a href="mirrors.php">' . _AM_WFDOWNLOADS_SMIRRORS . '</a></infolabel>', $newMirrors_count, 'green'));
} else {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_SMIRRORS . '</infolabel>', $newMirrors_count, 'green'));
}
// module max file size
$adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_DOWN_MODULE_MAXFILESIZE . '</infolabel>', WfdownloadsUtility::bytesToSize1024($wfdownloads->getConfig('maxfilesize')), 'green'));
// upload file size limit
// get max file size (setup and php.ini)
$phpiniMaxFileSize = min((int)ini_get('upload_max_filesize'), (int)ini_get('post_max_size'), (int)ini_get('memory_limit')) * 1024 * 1024; // bytes
$maxFileSize       = WfdownloadsUtility::bytesToSize1024(min($wfdownloads->getConfig('maxfilesize'), $phpiniMaxFileSize));
$adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_WFDOWNLOADS_UPLOAD_MAXFILESIZE . '</infolabel>', $maxFileSize, 'green'));

//------ check directories ---------------

$adminObject->addConfigBoxLine('');
$redirectFile = $_SERVER['PHP_SELF'];

if (!WfdownloadsUtility::checkModule('formulize')) {
    $adminObject->addConfigBoxLine(_AM_WFDOWNLOADS_FORMULIZE_NOT_AVILABLE);
} else {
    $adminObject->addConfigBoxLine(_AM_WFDOWNLOADS_FORMULIZE_AVAILABLE);
}

$adminObject->addConfigBoxLine('');

//$path = $wfdownloads->getConfig('uploaddir') . '/';
$path = $moduleHelper->getConfig('uploaddir');
$adminObject->addConfigBoxLine(DirectoryChecker::getDirectoryStatus($path, 0777, $redirectFile));

$path = $moduleHelper->getConfig('batchdir') . '/';
$adminObject->addConfigBoxLine(DirectoryChecker::getDirectoryStatus($path, 0777, $redirectFile));

//$adminObject->addConfigBoxLine('');

$path = XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('mainimagedir') . '/';
$adminObject->addConfigBoxLine(DirectoryChecker::getDirectoryStatus($path, 0777, $redirectFile));
//$adminObject->addConfigBoxLine(FileChecker::getFileStatus($path . 'blank.png', BLANK_FILE_PATH, $redirectFile));

//$adminObject->addConfigBoxLine('');

$path = XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('screenshots') . '/';
$adminObject->addConfigBoxLine(DirectoryChecker::getDirectoryStatus($path, 0777, $redirectFile));
//$adminObject->addConfigBoxLine(FileChecker::getFileStatus($path . 'blank.png', BLANK_FILE_PATH, $redirectFile));
$adminObject->addConfigBoxLine(DirectoryChecker::getDirectoryStatus($path . 'thumbs' . '/', 0777, $redirectFile));
//$adminObject->addConfigBoxLine(FileChecker::getFileStatus($path . 'thumbs' . '/' . 'blank.png', BLANK_FILE_PATH, $redirectFile));

//$adminObject->addConfigBoxLine('');

$path = XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('catimage') . '/';
$adminObject->addConfigBoxLine(DirectoryChecker::getDirectoryStatus($path, 0777, $redirectFile));
//$adminObject->addConfigBoxLine(FileChecker::getFileStatus($path . 'blank.png', BLANK_FILE_PATH, $redirectFile));
$adminObject->addConfigBoxLine(DirectoryChecker::getDirectoryStatus($path . 'thumbs' . '/', 0777, $redirectFile));
//$adminObject->addConfigBoxLine(FileChecker::getFileStatus($path . 'thumbs' . '/' . 'blank.png', BLANK_FILE_PATH, $redirectFile));

//---------------------------
$adminObject->addConfigBoxLine('');

//$moduleDirName = basename(dirname(__DIR__));

/** @var WfdownloadsUtility $configuratorClass */
$configuratorClass = ucfirst($moduleDirName) . 'Configurator';
if (!class_exists($configuratorClass)) {
    xoops_load('configurator', $moduleDirName);
}

$configurator = new $configuratorClass();

/** @var WfdownloadsUtility $utilityClass */
$utilityClass = ucfirst($moduleDirName) . 'Utility';
if (!class_exists($utilityClass)) {
    xoops_load('utility', $moduleDirName);
}

foreach (array_keys($configurator->uploadFolders) as $i) {
    $utilityClass::createFolder($configurator->uploadFolders[$i]);
}

$adminObject->displayNavigation(basename(__FILE__));
$adminObject->displayIndex();
echo $utilityClass::getServerStats();

require_once __DIR__ . '/admin_footer.php';
