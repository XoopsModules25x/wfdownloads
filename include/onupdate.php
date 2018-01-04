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

use Xmf\Language;

if ((!defined('XOOPS_ROOT_PATH')) || !($GLOBALS['xoopsUser'] instanceof XoopsUser)
    || !$GLOBALS['xoopsUser']->IsAdmin()) {
    exit('Restricted access' . PHP_EOL);
}

/**
 * @param string $tablename
 *
 * @return bool
 */
function tableExists($tablename)
{
    $result = $GLOBALS['xoopsDB']->queryF("SHOW TABLES LIKE '$tablename'");

    return ($GLOBALS['xoopsDB']->getRowsNum($result) > 0) ? true : false;
}

/**
 *
 * Prepares system prior to attempting to install module
 * @param XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_update_wfdownloads(\XoopsModule $module)
{
    /** @var Wfdownloads\Helper $helper */
    /** @var Wfdownloads\Utility $utility */
    $moduleDirName = basename(dirname(__DIR__));
    $helper       = Wfdownloads\Helper::getInstance();
    $utility      = new Wfdownloads\Utility();

    $xoopsSuccess = $utility::checkVerXoops($module);
    $phpSuccess   = $utility::checkVerPhp($module);
    return $xoopsSuccess && $phpSuccess;
}

/**
 *
 * Performs tasks required during update of the module
 * @param XoopsModule $module {@link XoopsModule}
 * @param null        $previousVersion
 *
 * @return void true if update successful, false if not
 */

function xoops_module_update_wfdownloads(XoopsModule $module, $previousVersion = null)
{
    $moduleDirName = basename(dirname(__DIR__));
    $capsDirName   = strtoupper($moduleDirName);

    /** @var Wfdownloads\Helper $helper */
    /** @var Wfdownloads\Utility $utility */
    /** @var Wfdownloads\Configurator $configurator */
    $helper  = Wfdownloads\Helper::getInstance();
    $utility = new Wfdownloads\Utility();
    $configurator = new Wfdownloads\Configurator();

    if ($previousVersion < 325) {

        //delete old HTML templates
        if (count($configurator->templateFolders) > 0) {
            foreach ($configurator->templateFolders as $folder) {
                $templateFolder = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $folder);
                if (is_dir($templateFolder)) {
                    $templateList = array_diff(scandir($templateFolder, SCANDIR_SORT_NONE), ['..', '.']);
                    foreach ($templateList as $k => $v) {
                        $fileInfo = new SplFileInfo($templateFolder . $v);
                        if ('html' === $fileInfo->getExtension() && 'index.html' !== $fileInfo->getFilename()) {
                            if (file_exists($templateFolder . $v)) {
                                unlink($templateFolder . $v);
                            }
                        }
                    }
                }
            }
        }

        //  ---  DELETE OLD FILES ---------------
        if (count($configurator->oldFiles) > 0) {
            //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
            foreach (array_keys($configurator->oldFiles) as $i) {
                $tempFile = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $configurator->oldFiles[$i]);
                if (is_file($tempFile)) {
                    unlink($tempFile);
                }
            }
        }

        //  ---  DELETE OLD FOLDERS ---------------
        xoops_load('XoopsFile');
        if (count($configurator->oldFolders) > 0) {
            //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
            foreach (array_keys($configurator->oldFolders) as $i) {
                $tempFolder = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $configurator->oldFolders[$i]);
                /** @var XoopsObjectHandler $folderHandler */
                $folderHandler = \XoopsFile::getHandler('folder', $tempFolder);
                $folderHandler->delete($tempFolder);
            }
        }

        //  ---  CREATE FOLDERS ---------------
        if (count($configurator->uploadFolders) > 0) {
            //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
            foreach (array_keys($configurator->uploadFolders) as $i) {
                $utilityClass::createFolder($configurator->uploadFolders[$i]);
            }
        }

        //  ---  COPY blank.png FILES ---------------
        if (count($configurator->blankFiles) > 0) {
            $file = __DIR__ . '/../assets/images/blank.png';
            foreach (array_keys($configurator->blankFiles) as $i) {
                $dest = $configurator->blankFiles[$i] . '/blank.png';
                $utilityClass::copyFile($file, $dest);
            }
        }

        //delete .html entries from the tpl table
        $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('tplfile') . " WHERE `tpl_module` = '" . $module->getVar('dirname', 'n') . "' AND `tpl_file` LIKE '%.html%'";
        $GLOBALS['xoopsDB']->queryF($sql);
    }
}

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');
require_once __DIR__ . '/common.php';
//@require_once WFDOWNLOADS_ROOT_PATH . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/admin.php';
xoops_loadLanguage('admin', $helper->getModule()->dirname());
require_once WFDOWNLOADS_ROOT_PATH . '/class/dbupdater.php';

/**
 * @param XoopsModule $xoopsModule
 * @param             $previousVersion
 *
 * @return bool
 */

function xoops_module_update_wfdownloads2(XoopsModule $xoopsModule, $previousVersion)
{
    ob_start();
    invert_nohtm_dohtml_values();
    if ($previousVersion < 322) {
        update_tables_to_322($xoopsModule);
    }
    if ($previousVersion < 323) {
        update_permissions_to_323($xoopsModule);
        update_tables_to_323($xoopsModule);
    }
    $feedback = ob_get_clean();
    if (method_exists($xoopsModule, 'setMessage')) {
        $xoopsModule->setMessage($feedback);
    } else {
        echo $feedback;
    }
    Wfdownloads\Utility::setMeta('version', '3.23'); //Set meta version to current

    return true;
}

// =========================================================================================
// This function updates any existing table of a 3.22 version to the format used
// in the release of Wfdownloads 3.23
// =========================================================================================
/**
 * @param $module
 */
function update_tables_to_323($module)
{
    $dbupdater = new WfdownloadsDbupdater();

    // update wfdownloads_downloads table
    $download_fields = [
        'lid'             => ['Type' => 'int(11) unsigned NOT NULL auto_increment', 'Default' => false],
        'cid'             => ['Type' => "int(5) unsigned NOT NULL default '0'", 'Default' => true],
        'title'           => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'url'             => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'filename'        => ['Type' => "varchar(150) NOT NULL default ''", 'Default' => true],
        'filetype'        => ['Type' => "varchar(100) NOT NULL default ''", 'Default' => true],
        'homepage'        => ['Type' => "varchar(100) NOT NULL default ''", 'Default' => true],
        'version'         => ['Type' => "varchar(20) NOT NULL default ''", 'Default' => true],
        'size'            => ['Type' => "int(8) NOT NULL default '0'", 'Default' => true],
        'platform'        => ['Type' => "varchar(50) NOT NULL default ''", 'Default' => true],
        'screenshot'      => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'screenshot2'     => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'screenshot3'     => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'screenshot4'     => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'submitter'       => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        'publisher'       => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'status'          => ['Type' => "tinyint(2) NOT NULL default '" . _WFDOWNLOADS_STATUS_WAITING . "'", 'Default' => true],
        'date'            => ['Type' => "int(10) NOT NULL default '0'", 'Default' => true],
        'hits'            => ['Type' => "int(11) unsigned NOT NULL default '0'", 'Default' => true],
        'rating'          => ['Type' => "double(6,4) NOT NULL default '0.0000'", 'Default' => true],
        'votes'           => ['Type' => "int(11) unsigned NOT NULL default '0'", 'Default' => true],
        'comments'        => ['Type' => "int(11) unsigned NOT NULL default '0'", 'Default' => true],
        'license'         => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'mirror'          => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'price'           => ['Type' => "varchar(10) NOT NULL default 'Free'", 'Default' => true],
        'paypalemail'     => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'features'        => ['Type' => 'text NULL', 'Default' => false],
        'requirements'    => ['Type' => 'text NULL', 'Default' => false],
        'homepagetitle'   => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'forumid'         => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        'limitations'     => ['Type' => "varchar(255) NOT NULL default '30 day trial'", 'Default' => true],
        'versiontypes'    => ['Type' => "varchar(255) NOT NULL default 'None'", 'Default' => true],
        'dhistory'        => ['Type' => 'text NULL', 'Default' => false],
        'published'       => ['Type' => "int(11) NOT NULL default '1089662528'", 'Default' => true],
        'expired'         => ['Type' => "int(10) NOT NULL default '0'", 'Default' => true],
        'updated'         => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        'offline'         => ['Type' => "tinyint(1) NOT NULL default '0'", 'Default' => true],
        'description'     => ['Type' => 'text NULL', 'Default' => false],
        'ipaddress'       => ['Type' => "varchar(120) NOT NULL default '0'", 'Default' => true],
        'notifypub'       => ['Type' => "int(1) NOT NULL default '0'", 'Default' => true],
        'summary'         => ['Type' => 'text NULL', 'Default' => false],
        'formulize_idreq' => ['Type' => "int(5) NOT NULL default '0'", 'Default' => true],
        // added 3.23
        'screenshots'     => ['Type' => 'text NOT NULL', 'Default' => true],
        'dohtml'          => ['Type' => "tinyint(1) NOT NULL default '0'", 'Default' => true],
        'dosmiley'        => ['Type' => "tinyint(1) NOT NULL default '1'", 'Default' => true],
        'doxcode'         => ['Type' => "tinyint(1) NOT NULL default '1'", 'Default' => true],
        'doimage'         => ['Type' => "tinyint(1) NOT NULL default '1'", 'Default' => true],
        'dobr'            => ['Type' => "tinyint(1) NOT NULL default '1'", 'Default' => true]
    ];
    //$renamed_fields = array(
    //    "old_name" => "new_name"
    //);
    echo "<br><span style='font-weight: bold;'>Checking Download table</span><br>";
    $downloadHandler = xoops_getModuleHandler('download', 'wfdownloads');
    $download_table  = new WfdownloadsTable('wfdownloads_downloads');
    $fields          = get_table_info($downloadHandler->table, $download_fields);
    // check for renamed fields
    //rename_fields($download_table, $renamed_fields, $fields, $download_fields);
    // check for updated fields
    update_table($download_fields, $fields, $download_table);
    if ($dbupdater->updateTable($download_table)) {
        echo 'Downloads table updated<br>';
    }
    unset($fields);
    // populate screenshots with screenshot, screenshot2, screenshot3, screenshot4 values
    $downloadsObjs = $downloadHandler->getObjects();
    foreach ($downloadsObjs as $downloadsObj) {
        $screenshots   = [];
        $screenshots[] = $downloadsObj->getVar('screenshot');
        $screenshots[] = $downloadsObj->getVar('screenshot2');
        $screenshots[] = $downloadsObj->getVar('screenshot3');
        $screenshots[] = $downloadsObj->getVar('screenshot4');
        $downloadsObj->setVar('screenshots', $screenshots);
        unset($screenshots);
        $downloadHandler->insert($downloadsObj);
    }

    // update wfdownloads_mod table
    $mod_fields = [
        'requestid'       => ['Type' => 'int(11) NOT NULL auto_increment', 'Default' => false],
        //
        'modifysubmitter' => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        'requestdate'     => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        //
        'lid'             => ['Type' => "int(11) unsigned NOT NULL default '0'", 'Default' => true],
        'cid'             => ['Type' => "int(5) unsigned NOT NULL default '0'", 'Default' => true],
        'title'           => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'url'             => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'filename'        => ['Type' => "varchar(150) NOT NULL default ''", 'Default' => true],
        'filetype'        => ['Type' => "varchar(100) NOT NULL default ''", 'Default' => true],
        'homepage'        => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'version'         => ['Type' => "varchar(20) NOT NULL default ''", 'Default' => true],
        'size'            => ['Type' => "int(8) NOT NULL default '0'", 'Default' => true],
        'platform'        => ['Type' => "varchar(50) NOT NULL default ''", 'Default' => true],
        'screenshot'      => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'screenshot2'     => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'screenshot3'     => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'screenshot4'     => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'submitter'       => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        'publisher'       => ['Type' => 'text NULL', 'Default' => false],
        'status'          => ['Type' => "tinyint(2) NOT NULL default '" . _WFDOWNLOADS_STATUS_WAITING . "'", 'Default' => true],
        'date'            => ['Type' => "int(10) NOT NULL default '0'", 'Default' => true],
        'hits'            => ['Type' => "int(11) unsigned NOT NULL default '0'", 'Default' => true],
        'rating'          => ['Type' => "double(6,4) NOT NULL default '0.0000'", 'Default' => true],
        'votes'           => ['Type' => "int(11) unsigned NOT NULL default '0'", 'Default' => true],
        'comments'        => ['Type' => "int(11) unsigned NOT NULL default '0'", 'Default' => true],
        'license'         => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'mirror'          => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'price'           => ['Type' => "varchar(10) NOT NULL default 'Free'", 'Default' => true],
        'paypalemail'     => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'features'        => ['Type' => 'text NULL', 'Default' => false],
        'requirements'    => ['Type' => 'text NULL', 'Default' => false],
        'homepagetitle'   => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'forumid'         => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        'limitations'     => ['Type' => "varchar(255) NOT NULL default '30 day trial'", 'Default' => true],
        'versiontypes'    => ['Type' => "varchar(255) NOT NULL default 'None'", 'Default' => true],
        'dhistory'        => ['Type' => 'text NOT NULL', 'Default' => false],
        'published'       => ['Type' => "int(10) NOT NULL default '0'", 'Default' => true],
        'expired'         => ['Type' => "int(10) NOT NULL default '0'", 'Default' => true],
        'updated'         => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        'offline'         => ['Type' => "tinyint(1) NOT NULL default '0'", 'Default' => true],
        'summary'         => ['Type' => 'text NULL', 'Default' => false],
        'description'     => ['Type' => 'text NULL', 'Default' => false],
        // ???
        'formulize_idreq' => ['Type' => "int(5) NOT NULL default '0'", 'Default' => true],
        // added 3.23
        'screenshots'     => ['Type' => 'text NULL', 'Default' => true],
        'dohtml'          => ['Type' => "tinyint(1) NOT NULL default '0'", 'Default' => true],
        'dosmiley'        => ['Type' => "tinyint(1) NOT NULL default '1'", 'Default' => true],
        'doxcode'         => ['Type' => "tinyint(1) NOT NULL default '1'", 'Default' => true],
        'doimage'         => ['Type' => "tinyint(1) NOT NULL default '1'", 'Default' => true],
        'dobr'            => ['Type' => "tinyint(1) NOT NULL default '1'", 'Default' => true]
    ];
    //$renamed_fields = array(
    //    "old_name" => "new_name"
    //);
    echo "<br><span style='font-weight: bold;'>Checking Modified Downloads table</span><br>";
    $modificationHandler = xoops_getModuleHandler('modification', 'wfdownloads');
    $mod_table           = new WfdownloadsTable('wfdownloads_mod');
    $fields              = get_table_info($modificationHandler->table, $mod_fields);
    // check for renamed fields
    //rename_fields($mod_table, $renamed_fields, $fields, $mod_fields);
    // check for updated fields
    update_table($mod_fields, $fields, $mod_table);
    if ($dbupdater->updateTable($mod_table)) {
        echo 'Modified Downloads table updated <br>';
    }
    unset($fields);
}

// =========================================================================================
// This function updates permissions to Wfdownloads 3.23 permissions
// add 'WFUpCatPerm' permission where is set 'WFDownCatPerm' permission
// =========================================================================================
/**
 * @param XoopsModule $module
 */
function update_permissions_to_323(XoopsModule $module)
{
    $gpermHandler         = xoops_getHandler('groupperm');
    $wfdCategoriesHandler = xoops_getModuleHandler('category', $module->dirname());

    $cids = $wfdCategoriesHandler->getIds();
    if (count($cids) > 0) {
        echo "<br><span style='font-weight: bold;'>Adding upload permissions to categories</span><br>";
        foreach ($cids as $cid) {
            $down_groups_ids = $gpermHandler->getGroupIds('WFDownCatPerm', $cid, $module->mid());
            foreach ($down_groups_ids as $down_group_id) {
                //$gpermHandler->deleteByModule($module->mid(), 'WFUpCatPerm', $cid);
                $gpermHandler->addRight('WFUpCatPerm', $cid, $down_group_id, $module->mid());
            }
        }
    } else {
        echo "<br><span style='font-weight: bold;'>No categories, no permissions</span><br>";
    }
}

// =========================================================================================
// This function updates any existing table of a 2.x version to the format used
// in the release of Wfdownloads 3.00
// =========================================================================================
/**
 * @param $module
 */
function update_tables_to_322($module)
{
    $dbupdater = new WfdownloadsDbupdater();

    // create wfdownloads_meta table
    if (!Wfdownloads\Utility::tableExists('wfdownloads_meta')) {
        $table = new WfdownloadsTable('wfdownloads_meta');
        $table->setStructure("CREATE TABLE %s (
                metakey varchar(50) NOT NULL default '',
                metavalue varchar(255) NOT NULL default '',
                PRIMARY KEY (metakey))
                ENGINE=MyISAM;");
        $table->setData(sprintf("'version', %s", round($GLOBALS['xoopsModule']->getVar('version') / 100, 2)));
        if ($dbupdater->updateTable($table)) {
            echo 'wfdownloads_meta table created<br>';
        }
    }

    // create wfdownloads_mirror table
    if (!Wfdownloads\Utility::tableExists('wfdownloads_mirrors')) {
        $table = new WfdownloadsTable('wfdownloads_mirrors');
        $table->setStructure("CREATE TABLE %s (
                mirror_id int(11) unsigned NOT NULL auto_increment,
                lid int(11) NOT NULL default '0',
                title varchar(255) NOT NULL default '',
                homeurl varchar(100) NOT NULL default '',
                location varchar(255) NOT NULL default '',
                continent varchar(255) NOT NULL default '',
                downurl varchar(255) NOT NULL default '',
                submit int(11) NOT NULL default '0',
                date int(11) NOT NULL default '0',
                uid int(10) NOT NULL default '0',
                PRIMARY KEY  (mirror_id),
                KEY categoryid (lid))
                ENGINE=MyISAM;");
        if ($dbupdater->updateTable($table)) {
            echo 'wfdownloads_mirrors table created<br>';
        }
    }

    // create wfdownloads_ip_log table
    if (!Wfdownloads\Utility::tableExists('wfdownloads_ip_log')) {
        $table = new WfdownloadsTable('wfdownloads_ip_log');
        $table->setStructure("CREATE TABLE %s (
                ip_logid int(11) NOT NULL auto_increment,
                lid int(11) NOT NULL default '0',
                uid int(11) NOT NULL default '0',
                date int(11) NOT NULL default '0',
                ip_address varchar(20) NOT NULL default '',
                PRIMARY KEY  (ip_logid)
                ENGINE=MyISAM;");
        if ($dbupdater->updateTable($table)) {
            echo 'wfdownloads_mirrors table created<br>';
        }
    }

    // update wfdownloads_downloads table
    $download_fields = [
        'lid'             => ['Type' => 'int(11) unsigned NOT NULL auto_increment', 'Default' => false],
        'cid'             => ['Type' => "int(5) unsigned NOT NULL default '0'", 'Default' => true],
        'title'           => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'url'             => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'filename'        => ['Type' => "varchar(150) NOT NULL default ''", 'Default' => true],
        'filetype'        => ['Type' => "varchar(100) NOT NULL default ''", 'Default' => true],
        'homepage'        => ['Type' => "varchar(100) NOT NULL default ''", 'Default' => true],
        'version'         => ['Type' => "varchar(20) NOT NULL default ''", 'Default' => true],
        'size'            => ['Type' => "int(8) NOT NULL default '0'", 'Default' => true],
        'platform'        => ['Type' => "varchar(50) NOT NULL default ''", 'Default' => true],
        'screenshot'      => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'screenshot2'     => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'screenshot3'     => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'screenshot4'     => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'submitter'       => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        'publisher'       => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'status'          => ['Type' => "tinyint(2) NOT NULL default '" . _WFDOWNLOADS_STATUS_WAITING . "'", 'Default' => true],
        'date'            => ['Type' => "int(10) NOT NULL default '0'", 'Default' => true],
        'hits'            => ['Type' => "int(11) unsigned NOT NULL default '0'", 'Default' => true],
        'rating'          => ['Type' => "double(6,4) NOT NULL default '0.0000'", 'Default' => true],
        'votes'           => ['Type' => "int(11) unsigned NOT NULL default '0'", 'Default' => true],
        'comments'        => ['Type' => "int(11) unsigned NOT NULL default '0'", 'Default' => true],
        'license'         => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'mirror'          => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'price'           => ['Type' => "varchar(10) NOT NULL default 'Free'", 'Default' => true],
        'paypalemail'     => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'features'        => ['Type' => 'text NULL', 'Default' => false],
        'requirements'    => ['Type' => 'text NULL', 'Default' => false],
        'homepagetitle'   => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'forumid'         => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        'limitations'     => ['Type' => "varchar(255) NOT NULL default '30 day trial'", 'Default' => true],
        'versiontypes'    => ['Type' => "varchar(255) NOT NULL default 'None'", 'Default' => true],
        'dhistory'        => ['Type' => 'text NULL', 'Default' => false],
        'published'       => ['Type' => "int(11) NOT NULL default '1089662528'", 'Default' => true],
        'expired'         => ['Type' => "int(10) NOT NULL default '0'", 'Default' => true],
        'updated'         => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        'offline'         => ['Type' => "tinyint(1) NOT NULL default '0'", 'Default' => true],
        'description'     => ['Type' => 'text NULL', 'Default' => false],
        'ipaddress'       => ['Type' => "varchar(120) NOT NULL default '0'", 'Default' => true],
        'notifypub'       => ['Type' => "int(1) NOT NULL default '0'", 'Default' => true],
        'summary'         => ['Type' => 'text NULL', 'Default' => false],
        'formulize_idreq' => ['Type' => "int(5) NOT NULL default '0'", 'Default' => true]
    ];
    $renamed_fields  = [
        'logourl' => 'screenshot'
    ];
    echo "<br><span style='font-weight: bold;'>Checking Download table</span><br>";
    $downloadHandler = xoops_getModuleHandler('download', 'wfdownloads');
    $download_table  = new WfdownloadsTable('wfdownloads_downloads');
    $fields          = get_table_info($downloadHandler->table, $download_fields);
    // check for renamed fields
    rename_fields($download_table, $renamed_fields, $fields, $download_fields);
    update_table($download_fields, $fields, $download_table);
    if ($dbupdater->updateTable($download_table)) {
        echo 'Downloads table updated<br>';
    }
    unset($fields);

    // update wfdownloads_mod table
    $mod_fields     = [
        'requestid'       => ['Type' => 'int(11) NOT NULL auto_increment', 'Default' => false],
        //
        'modifysubmitter' => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        'requestdate'     => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        //
        'lid'             => ['Type' => "int(11) unsigned NOT NULL default '0'", 'Default' => true],
        'cid'             => ['Type' => "int(5) unsigned NOT NULL default '0'", 'Default' => true],
        'title'           => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'url'             => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'filename'        => ['Type' => "varchar(150) NOT NULL default ''", 'Default' => true],
        'filetype'        => ['Type' => "varchar(100) NOT NULL default ''", 'Default' => true],
        'homepage'        => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'version'         => ['Type' => "varchar(20) NOT NULL default ''", 'Default' => true],
        'size'            => ['Type' => "int(8) NOT NULL default '0'", 'Default' => true],
        'platform'        => ['Type' => "varchar(50) NOT NULL default ''", 'Default' => true],
        'screenshot'      => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'screenshot2'     => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'screenshot3'     => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'screenshot4'     => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'submitter'       => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        'publisher'       => ['Type' => 'text NULL', 'Default' => false],
        'status'          => ['Type' => "tinyint(2) NOT NULL default '" . _WFDOWNLOADS_STATUS_WAITING . "'", 'Default' => true],
        'date'            => ['Type' => "int(10) NOT NULL default '0'", 'Default' => true],
        'hits'            => ['Type' => "int(11) unsigned NOT NULL default '0'", 'Default' => true],
        'rating'          => ['Type' => "double(6,4) NOT NULL default '0.0000'", 'Default' => true],
        'votes'           => ['Type' => "int(11) unsigned NOT NULL default '0'", 'Default' => true],
        'comments'        => ['Type' => "int(11) unsigned NOT NULL default '0'", 'Default' => true],
        'license'         => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'mirror'          => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'price'           => ['Type' => "varchar(10) NOT NULL default 'Free'", 'Default' => true],
        'paypalemail'     => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'features'        => ['Type' => 'text NULL', 'Default' => false],
        'requirements'    => ['Type' => 'text NULL', 'Default' => false],
        'homepagetitle'   => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'forumid'         => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        'limitations'     => ['Type' => "varchar(255) NOT NULL default '30 day trial'", 'Default' => true],
        'versiontypes'    => ['Type' => "varchar(255) NOT NULL default 'None'", 'Default' => true],
        'dhistory'        => ['Type' => 'text NULL', 'Default' => false],
        'published'       => ['Type' => "int(10) NOT NULL default '0'", 'Default' => true],
        'expired'         => ['Type' => "int(10) NOT NULL default '0'", 'Default' => true],
        'updated'         => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        'offline'         => ['Type' => "tinyint(1) NOT NULL default '0'", 'Default' => true],
        'summary'         => ['Type' => 'text NULL', 'Default' => false],
        'description'     => ['Type' => 'text NULL', 'Default' => false],
        // ???
        'formulize_idreq' => ['Type' => "int(5) NOT NULL default '0'", 'Default' => true]
    ];
    $renamed_fields = [
        'logourl' => 'screenshot'
    ];
    echo "<br><span style='font-weight: bold;'>Checking Modified Downloads table</span><br>";
    $modificationHandler = xoops_getModuleHandler('modification', 'wfdownloads');
    $mod_table           = new WfdownloadsTable('wfdownloads_mod');
    $fields              = get_table_info($modificationHandler->table, $mod_fields);
    rename_fields($mod_table, $renamed_fields, $fields, $mod_fields);
    update_table($mod_fields, $fields, $mod_table);
    if ($dbupdater->updateTable($mod_table)) {
        echo 'Modified Downloads table updated <br>';
    }
    unset($fields);

    // update wfdownloads_cat table
    $cat_fields = [
        'cid'           => ['Type' => 'int(5) unsigned NOT NULL auto_increment', 'Default' => false],
        'pid'           => ['Type' => "int(5) unsigned NOT NULL default '0'", 'Default' => true],
        'title'         => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'imgurl'        => ['Type' => "varchar(255) NOT NULL default ''", 'Default' => true],
        'description'   => ['Type' => 'text NULL', 'Default' => true],
        'total'         => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        'summary'       => ['Type' => 'text NULL', 'Default' => false],
        'spotlighttop'  => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        'spotlighthis'  => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        'dohtml'        => ['Type' => "tinyint(1) NOT NULL default '1'", 'Default' => true],
        'dosmiley'      => ['Type' => "tinyint(1) NOT NULL default '1'", 'Default' => true],
        'doxcode'       => ['Type' => "tinyint(1) NOT NULL default '1'", 'Default' => true],
        'doimage'       => ['Type' => "tinyint(1) NOT NULL default '1'", 'Default' => true],
        'dobr'          => ['Type' => "tinyint(1) NOT NULL default '1'", 'Default' => true],
        'weight'        => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        'formulize_fid' => ['Type' => "int(5) NOT NULL default '0'", 'Default' => true]
    ];
    echo "<br><span style='font-weight: bold;'>Checking Category table</span><br>";
    $wfdCategoriesHandler = xoops_getModuleHandler('category', 'wfdownloads');
    $cat_table            = new WfdownloadsTable('wfdownloads_cat');
    $fields               = get_table_info($wfdCategoriesHandler->table, $cat_fields);
    update_table($cat_fields, $fields, $cat_table);
    if ($dbupdater->updateTable($cat_table)) {
        echo 'Category table updated<br>';
    }
    unset($fields);

    // update wfdownloads_broken table
    $broken_fields = [
        'reportid'     => ['Type' => 'int(5) NOT NULL auto_increment', 'Default' => false],
        'lid'          => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        'sender'       => ['Type' => "int(11) NOT NULL default '0'", 'Default' => true],
        'ip'           => ['Type' => "varchar(20) NOT NULL default ''", 'Default' => true],
        'date'         => ['Type' => "varchar(11) NOT NULL default '0'", 'Default' => true],
        'confirmed'    => ['Type' => "tinyint(1) NOT NULL default '0'", 'Default' => true],
        'acknowledged' => ['Type' => "tinyint(1) NOT NULL default '0'", 'Default' => true]
    ];
    echo "<br><span style='font-weight: bold;'>Checking Broken Report table</span><br>";
    $brokenHandler = xoops_getModuleHandler('report', 'wfdownloads');
    $broken_table  = new WfdownloadsTable('wfdownloads_broken');
    $fields        = get_table_info($brokenHandler->table, $broken_fields);
    update_table($broken_fields, $fields, $broken_table);
    if ($dbupdater->updateTable($broken_table)) {
        echo 'Broken Reports table updated<br>';
    }
    unset($fields);
}

// =========================================================================================
// we are going to change the names for the fields like nohtml, nosmilies, noxcode, noimage, nobreak in
// the wfdownloads_cat table into dohtml, dosmilies and so on.  Therefore the logic will change
// 0=yes  1=no and the currently stored value need to be changed accordingly
// =========================================================================================
/**
 * @return array|bool
 */
function invert_nohtm_dohtml_values()
{
    $ret                  = [];
    $wfdCategoriesHandler = xoops_getModuleHandler('category', 'wfdownloads');
    $result               = $GLOBALS['xoopsDB']->query('SHOW COLUMNS FROM ' . $wfdCategoriesHandler->table);
    while (false !== ($existing_field = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $fields[$existing_field['Field']] = $existing_field['Type'];
    }
    if (in_array('nohtml', array_keys($fields))) {
        $dbupdater = new WfdownloadsDbupdater();
        //Invert column values
        // alter options in wfdownloads_cat
        $table = new WfdownloadsTable('wfdownloads_cat');
        $table->addAlteredField('nohtml', "dohtml tinyint(1) NOT NULL DEFAULT '1'");
        $table->addAlteredField('nosmiley', "dosmiley tinyint(1) NOT NULL DEFAULT '1'");
        $table->addAlteredField('noxcodes', "doxcode tinyint(1) NOT NULL DEFAULT '1'");
        $table->addAlteredField('noimages', "doimage tinyint(1) NOT NULL DEFAULT '1'");
        $table->addAlteredField('nobreak', "dobr tinyint(1) NOT NULL DEFAULT '1'");

        //inverting values no=1 <=> do=0
        // have to store teporarly as value = 2 to
        // avoid putting everithing to same value
        // if you change 1 to 0, then 0 to one,
        // every value will be 1, follow me?
        $table->addUpdatedWhere('dohtml', 2, '=1');
        $table->addUpdatedWhere('dohtml', 1, '=0');
        $table->addUpdatedWhere('dohtml', 0, '=2');

        $table->addUpdatedWhere('dosmiley', 2, '=1');
        $table->addUpdatedWhere('dosmiley', 1, '=0');
        $table->addUpdatedWhere('dosmiley', 0, '=2');

        $table->addUpdatedWhere('doxcode', 2, '=1');
        $table->addUpdatedWhere('doxcode', 1, '=0');
        $table->addUpdatedWhere('doxcode', 0, '=2');

        $table->addUpdatedWhere('doimage', 2, '=1');
        $table->addUpdatedWhere('doimage', 1, '=0');
        $table->addUpdatedWhere('doimage', 0, '=2');
        $ret = $dbupdater->updateTable($table);
    }

    return $ret;
}

/**
 * Updates a table by comparing correct fields with existing ones
 *
 * @param array            $new_fields
 * @param array            $existing_fields
 * @param WfdownloadsTable $table
 *
 * @return void
 */
function update_table($new_fields, $existing_fields, WfdownloadsTable $table)
{
    foreach ($new_fields as $field => $fieldinfo) {
        $type = $fieldinfo['Type'];
        if (!in_array($field, array_keys($existing_fields))) {
            //Add field as it is missing
            $table->addNewField($field, $type);
            //$GLOBALS['xoopsDB']->query("ALTER TABLE " . $table . " ADD " . $field . " " . $type);
            //echo $field . "(" . $type . ") <FONT COLOR='##22DD51'>Added</FONT><br>";
        } elseif ($existing_fields[$field] != $type) {
            $table->addAlteredField($field, $field . ' ' . $type);
            // check $fields[$field]['type'] for things like "int(10) unsigned"
            //$GLOBALS['xoopsDB']->query("ALTER TABLE " . $table . " CHANGE " . $field . " " . $field . " " . $type);
            //echo $field . " <FONT COLOR='#FF6600'>Changed to</FONT> " . $type . "<br>";
        } else {
            //echo $field . " <FONT COLOR='#0033FF'>Uptodate</FONT><br>";
        }
    }
}

/**
 * Get column information for a table - we'll need to send along an array of fields to determine
 * whether the "Default" index value should be appended
 *
 * @param string $table
 * @param array  $default_fields
 *
 * @return array
 */
function get_table_info($table, $default_fields)
{
    $result = $GLOBALS['xoopsDB']->query('SHOW COLUMNS FROM ' . $table);
    while (false !== ($existing_field = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $fields[$existing_field['Field']] = $existing_field['Type'];
        if ('YES' !== $existing_field['Null']) {
            $fields[$existing_field['Field']] .= ' NOT NULL';
        }
        if ($existing_field['Extra']) {
            $fields[$existing_field['Field']] .= ' ' . $existing_field['Extra'];
        }
        if ($default_fields[$existing_field['Field']]['Default']) {
            $fields[$existing_field['Field']] .= " default '" . $existing_field['Default'] . "'";
        }
    }

    return $fields;
}

/**
 * Renames fields in a table and updates the existing fields array to reflect it.
 *
 * @param WfdownloadsTable $table
 * @param array            $renamed_fields
 * @param array            $fields
 * @param array            $new_fields
 *
 * @return void
 */
function rename_fields(WfdownloadsTable $table, $renamed_fields, &$fields, $new_fields)
{
    foreach (array_keys($fields) as $field) {
        if (in_array($field, array_keys($renamed_fields))) {
            $new_field_name = $renamed_fields[$field];
            $new_field_type = $new_fields[$new_field_name]['Type'];
            $table->addAltered($field, $new_field_name . ' ' . $new_field_type);
            //$GLOBALS['xoopsDB']->query("ALTER TABLE " . $table . " CHANGE " . $field . " " . $new_field_name . " " . $new_field_type);
            //echo $field." Renamed to ". $new_field_name . "<br>";
            $fields[$new_field_name] = $new_field_type;
        }
    }
    //return $fields;
}
