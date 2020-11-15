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

use Xmf\Language;
use XoopsModules\Wfdownloads\{
    Common\Configurator,
    Helper,
    Utility
};
/** @var Helper $helper */
/** @var Utility $utility */

defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');
require_once __DIR__ . '/common.php';
//@require_once WFDOWNLOADS_ROOT_PATH . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/admin.php';
$helper = Helper::getInstance();
xoops_loadLanguage('admin', $GLOBALS['xoopsModule']->dirname());

define('INDEX_FILE_PATH', XOOPS_ROOT_PATH . '/uploads/index.html');
define('BLANK_FILE_PATH', XOOPS_ROOT_PATH . '/uploads/blank.png');

/**
 * Prepares system prior to attempting to install module
 * @param \XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_install_wfdownloads(XoopsModule $module)
{
    /** @var \XoopsModules\Wfdownloads\Utility $utility */
    $utility = new Utility();

    //check for minimum XOOPS version
    $xoopsSuccess = $utility::checkVerXoops($module);

    // check for minimum PHP version
    $phpSuccess = $utility::checkVerPhp($module);

    if (false !== $xoopsSuccess && false !== $phpSuccess) {
        $moduleTables = &$module->getInfo('tables');
        foreach ($moduleTables as $table) {
            $GLOBALS['xoopsDB']->queryF('DROP TABLE IF EXISTS ' . $GLOBALS['xoopsDB']->prefix($table) . ';');
        }
    }

    return $xoopsSuccess && $phpSuccess;
}

/**
 * Performs tasks required during installation of the module
 * @param \XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if installation successful, false if not
 */
function xoops_module_install_wfdownloads(XoopsModule $module)
{
    global $xoopsModule;
    require_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';
    require_once __DIR__ . '/config.php';

    //    $moduleDirName = $xoopsModule->getVar('dirname');
    $moduleDirName = basename(dirname(__DIR__));
    xoops_loadLanguage('admin', $moduleDirName);
    xoops_loadLanguage('modinfo', $moduleDirName);

    //    $configurator = require __DIR__   . '/config.php';
    $configurator = new  Configurator();
    /** @var \XoopsModules\Wfdownloads\Utility $utility */
    $utility = new Utility();

    // default Permission Settings
    $module_id      = $xoopsModule->getVar('mid');
    $module_name    = $xoopsModule->getVar('name');
    $module_dirname = $xoopsModule->getVar('dirname');
    $module_version = $xoopsModule->getVar('version');
    /** @var \XoopsGroupPermHandler $grouppermHandler */
    $grouppermHandler = xoops_getHandler('groupperm');
    // access rights
    $grouppermHandler->addRight('nw_approve', 1, XOOPS_GROUP_ADMIN, $module_id);
    $grouppermHandler->addRight('nw_submit', 1, XOOPS_GROUP_ADMIN, $module_id);
    $grouppermHandler->addRight('nw_view', 1, XOOPS_GROUP_ADMIN, $module_id);
    $grouppermHandler->addRight('nw_view', 1, XOOPS_GROUP_USERS, $module_id);
    $grouppermHandler->addRight('nw_view', 1, XOOPS_GROUP_ANONYMOUS, $module_id);

    //  ---  CREATE FOLDERS ---------------
    /*
      if (count($configurator['uploadFolders']) > 0) {
          //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
          foreach (array_keys($configurator['uploadFolders']) as $i) {
              $utility::createFolder($configurator['uploadFolders'][$i]);
          }
      }
    */
    if (count($configurator->uploadFolders) > 0) {
        //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
        foreach (array_keys($configurator->uploadFolders) as $i) {
            $utility::createFolder($configurator->uploadFolders[$i]);
        }
    }

    //  ---  COPY blank.png FILES ---------------
    if (count($configurator->copyBlankFiles) > 0) {
        $file = dirname(__DIR__) . '/assets/images/blank.png';
        foreach (array_keys($configurator->copyBlankFiles) as $i) {
            $dest = $configurator->copyBlankFiles[$i] . '/blank.png';
            $utility::copyFile($file, $dest);
        }
    }

    return true;
}

/**
 * @param \XoopsModule $xoopsModule
 *
 * @return bool
 */
/*
function xoops_module_install_wfdownloads(\XoopsModule $xoopsModule)
{
    // get module config values
    $hModConfig  = xoops_getHandler('config');
    $configArray = $hModConfig->getConfigsByCat(0, $xoopsModule->getVar('mid'));

    // create and populate directories with empty blank.png and index.html
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
    copy(BLANK_FILE_PATH, $path . '/blank.png');
    //
    $path = XOOPS_ROOT_PATH . '/' . $configArray['screenshots'];
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
    chmod($path, 0777);
    copy(INDEX_FILE_PATH, $path . '/index.html');
    copy(BLANK_FILE_PATH, $path . '/blank.png');
    //
    $path = XOOPS_ROOT_PATH . '/' . $configArray['screenshots'] . '/' . 'thumbs';
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
    chmod($path, 0777);
    copy(INDEX_FILE_PATH, $path . '/index.html');
    copy(BLANK_FILE_PATH, $path . '/blank.png');
    //
    $path = XOOPS_ROOT_PATH . '/' . $configArray['catimage'];
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
    chmod($path, 0777);
    copy(INDEX_FILE_PATH, $path . '/index.html');
    copy(BLANK_FILE_PATH, $path . '/blank.png');
    //
    $path = XOOPS_ROOT_PATH . '/' . $configArray['catimage'] . '/' . 'thumbs';
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
    chmod($path, 0777);
    copy(INDEX_FILE_PATH, $path . '/index.html');
    copy(BLANK_FILE_PATH, $path . '/blank.png');

    return true;
}
*/
