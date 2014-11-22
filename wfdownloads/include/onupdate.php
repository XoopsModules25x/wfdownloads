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
include_once __DIR__ . '/common.php';
//@include_once WFDOWNLOADS_ROOT_PATH . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/admin.php';
xoops_loadLanguage('admin', $wfdownloads->getModule()->dirname());
include_once WFDOWNLOADS_ROOT_PATH . '/class/dbupdater.php';

/**
 * @param $xoopsModule
 * @param $previousVersion
 *
 * @return bool
 */
function xoops_module_update_wfdownloads(&$xoopsModule, $previousVersion)
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
    wfdownloads_setMeta('version', '3.23'); //Set meta version to current

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
    $download_fields = array(
        "lid"             => array("Type" => "int(11) unsigned NOT NULL auto_increment", "Default" => false),
        "cid"             => array("Type" => "int(5) unsigned NOT NULL default '0'", "Default" => true),
        "title"           => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "url"             => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "filename"        => array("Type" => "varchar(150) NOT NULL default ''", "Default" => true),
        "filetype"        => array("Type" => "varchar(100) NOT NULL default ''", "Default" => true),
        "homepage"        => array("Type" => "varchar(100) NOT NULL default ''", "Default" => true),
        "version"         => array("Type" => "varchar(20) NOT NULL default ''", "Default" => true),
        "size"            => array("Type" => "int(8) NOT NULL default '0'", "Default" => true),
        "platform"        => array("Type" => "varchar(50) NOT NULL default ''", "Default" => true),
        "screenshot"      => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "screenshot2"     => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "screenshot3"     => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "screenshot4"     => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "submitter"       => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "publisher"       => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "status"          => array("Type" => "tinyint(2) NOT NULL default '" . _WFDOWNLOADS_STATUS_WAITING . "'", "Default" => true),
        "date"            => array("Type" => "int(10) NOT NULL default '0'", "Default" => true),
        "hits"            => array("Type" => "int(11) unsigned NOT NULL default '0'", "Default" => true),
        "rating"          => array("Type" => "double(6,4) NOT NULL default '0.0000'", "Default" => true),
        "votes"           => array("Type" => "int(11) unsigned NOT NULL default '0'", "Default" => true),
        "comments"        => array("Type" => "int(11) unsigned NOT NULL default '0'", "Default" => true),
        "license"         => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "mirror"          => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "price"           => array("Type" => "varchar(10) NOT NULL default 'Free'", "Default" => true),
        "paypalemail"     => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "features"        => array("Type" => "text NOT NULL", "Default" => false),
        "requirements"    => array("Type" => "text NOT NULL", "Default" => false),
        "homepagetitle"   => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "forumid"         => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "limitations"     => array("Type" => "varchar(255) NOT NULL default '30 day trial'", "Default" => true),
        "versiontypes"    => array("Type" => "varchar(255) NOT NULL default 'None'", "Default" => true),
        "dhistory"        => array("Type" => "text NOT NULL", "Default" => false),
        'published'       => array("Type" => "int(11) NOT NULL default '1089662528'", "Default" => true),
        "expired"         => array("Type" => "int(10) NOT NULL default '0'", "Default" => true),
        "updated"         => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "offline"         => array("Type" => "tinyint(1) NOT NULL default '0'", "Default" => true),
        "description"     => array("Type" => "text NOT NULL", "Default" => false),
        "ipaddress"       => array("Type" => "varchar(120) NOT NULL default '0'", "Default" => true),
        "notifypub"       => array("Type" => "int(1) NOT NULL default '0'", "Default" => true),
        "summary"         => array("Type" => "text NOT NULL", "Default" => false),
        "formulize_idreq" => array("Type" => "int(5) NOT NULL default '0'", "Default" => true),
        // added 3.23
        "screenshots"     => array("Type" => "text NOT NULL", "Default" => true),
        "dohtml"          => array("Type" => "tinyint(1) NOT NULL default '0'", "Default" => true),
        "dosmiley"        => array("Type" => "tinyint(1) NOT NULL default '1'", "Default" => true),
        "doxcode"         => array("Type" => "tinyint(1) NOT NULL default '1'", "Default" => true),
        "doimage"         => array("Type" => "tinyint(1) NOT NULL default '1'", "Default" => true),
        "dobr"            => array("Type" => "tinyint(1) NOT NULL default '1'", "Default" => true)
    );
    //$renamed_fields = array(
    //    "old_name" => "new_name"
    //);
    echo "<br /><span style='font-weight: bold;'>Checking Download table</span><br />";
    $download_handler = xoops_getmodulehandler('download', 'wfdownloads');
    $download_table   = new WfdownloadsTable('wfdownloads_downloads');
    $fields           = get_table_info($download_handler->table, $download_fields);
    // check for renamed fields
    //rename_fields($download_table, $renamed_fields, $fields, $download_fields);
    // check for updated fields
    update_table($download_fields, $fields, $download_table);
    if ($dbupdater->updateTable($download_table)) {
        echo "Downloads table updated<br />";
    }
    unset($fields);
    // populate screenshots with screenshot, screenshot2, screenshot3, screenshot4 values
    $downloadsObjs = $download_handler->getObjects();
    foreach ($downloadsObjs as $downloadsObj) {
        $screenshots   = array();
        $screenshots[] = $downloadsObj->getVar('screenshot');
        $screenshots[] = $downloadsObj->getVar('screenshot2');
        $screenshots[] = $downloadsObj->getVar('screenshot3');
        $screenshots[] = $downloadsObj->getVar('screenshot4');
        $downloadsObj->setVar('screenshots', $screenshots);
        unset($screenshots);
        $download_handler->insert($downloadsObj);
    }

    // update wfdownloads_mod table
    $mod_fields = array(
        "requestid"       => array("Type" => "int(11) NOT NULL auto_increment", "Default" => false),
        //
        "modifysubmitter" => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "requestdate"     => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        //
        "lid"             => array("Type" => "int(11) unsigned NOT NULL default '0'", "Default" => true),
        "cid"             => array("Type" => "int(5) unsigned NOT NULL default '0'", "Default" => true),
        "title"           => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "url"             => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "filename"        => array("Type" => "varchar(150) NOT NULL default ''", "Default" => true),
        "filetype"        => array("Type" => "varchar(100) NOT NULL default ''", "Default" => true),
        "homepage"        => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "version"         => array("Type" => "varchar(20) NOT NULL default ''", "Default" => true),
        "size"            => array("Type" => "int(8) NOT NULL default '0'", "Default" => true),
        "platform"        => array("Type" => "varchar(50) NOT NULL default ''", "Default" => true),
        "screenshot"      => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "screenshot2"     => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "screenshot3"     => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "screenshot4"     => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "submitter"       => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "publisher"       => array("Type" => "text NOT NULL", "Default" => false),
        "status"          => array("Type" => "tinyint(2) NOT NULL default '" . _WFDOWNLOADS_STATUS_WAITING . "'", "Default" => true),
        "date"            => array("Type" => "int(10) NOT NULL default '0'", "Default" => true),
        "hits"            => array("Type" => "int(11) unsigned NOT NULL default '0'", "Default" => true),
        "rating"          => array("Type" => "double(6,4) NOT NULL default '0.0000'", "Default" => true),
        "votes"           => array("Type" => "int(11) unsigned NOT NULL default '0'", "Default" => true),
        "comments"        => array("Type" => "int(11) unsigned NOT NULL default '0'", "Default" => true),
        "license"         => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "mirror"          => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "price"           => array("Type" => "varchar(10) NOT NULL default 'Free'", "Default" => true),
        "paypalemail"     => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "features"        => array("Type" => "text NOT NULL", "Default" => false),
        "requirements"    => array("Type" => "text NOT NULL", "Default" => false),
        "homepagetitle"   => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "forumid"         => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "limitations"     => array("Type" => "varchar(255) NOT NULL default '30 day trial'", "Default" => true),
        "versiontypes"    => array("Type" => "varchar(255) NOT NULL default 'None'", "Default" => true),
        "dhistory"        => array("Type" => "text NOT NULL", "Default" => false),
        'published'       => array("Type" => "int(10) NOT NULL default '0'", "Default" => true),
        "expired"         => array("Type" => "int(10) NOT NULL default '0'", "Default" => true),
        "updated"         => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "offline"         => array("Type" => "tinyint(1) NOT NULL default '0'", "Default" => true),
        "summary"         => array("Type" => "text NOT NULL", "Default" => false),
        "description"     => array("Type" => "text NOT NULL", "Default" => false),
        // ???
        "formulize_idreq" => array("Type" => "int(5) NOT NULL default '0'", "Default" => true),
        // added 3.23
        "screenshots"     => array("Type" => "text NOT NULL", "Default" => true),
        "dohtml"          => array("Type" => "tinyint(1) NOT NULL default '0'", "Default" => true),
        "dosmiley"        => array("Type" => "tinyint(1) NOT NULL default '1'", "Default" => true),
        "doxcode"         => array("Type" => "tinyint(1) NOT NULL default '1'", "Default" => true),
        "doimage"         => array("Type" => "tinyint(1) NOT NULL default '1'", "Default" => true),
        "dobr"            => array("Type" => "tinyint(1) NOT NULL default '1'", "Default" => true)
    );
    //$renamed_fields = array(
    //    "old_name" => "new_name"
    //);
    echo "<br /><span style='font-weight: bold;'>Checking Modified Downloads table</span><br />";
    $mod_handler = xoops_getmodulehandler('modification', 'wfdownloads');
    $mod_table   = new WfdownloadsTable('wfdownloads_mod');
    $fields      = get_table_info($mod_handler->table, $mod_fields);
    // check for renamed fields
    //rename_fields($mod_table, $renamed_fields, $fields, $mod_fields);
    // check for updated fields
    update_table($mod_fields, $fields, $mod_table);
    if ($dbupdater->updateTable($mod_table)) {
        echo "Modified Downloads table updated <br />";
    }
    unset($fields);

}

// =========================================================================================
// This function updates permissions to Wfdownloads 3.23 permissions
// add 'WFUpCatPerm' permission where is set 'WFDownCatPerm' permission
// =========================================================================================
/**
 * @param $module
 */
function update_permissions_to_323($module)
{
    $gperm_handler    = xoops_gethandler('groupperm');
    $category_handler = xoops_getmodulehandler('category', $module->dirname());

    $cids = $category_handler->getIds();
    if (count($cids) > 0) {
        echo "<br /><span style='font-weight: bold;'>Adding upload permissions to categories</span><br />";
        foreach ($cids as $cid) {
            $down_groups_ids = $gperm_handler->getGroupIds('WFDownCatPerm', $cid, $module->mid());
            foreach ($down_groups_ids as $down_group_id) {
                //$gperm_handler->deleteByModule($module->mid(), 'WFUpCatPerm', $cid);
                $gperm_handler->addRight('WFUpCatPerm', $cid, $down_group_id, $module->mid());
            }
        }
    } else {
        echo "<br /><span style='font-weight: bold;'>No categories, no permissions</span><br />";
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
    if (!wfdownloads_tableExists('wfdownloads_meta')) {
        $table = new WfdownloadsTable('wfdownloads_meta');
        $table->setStructure(
            "CREATE TABLE %s (
                metakey varchar(50) NOT NULL default '',
                metavalue varchar(255) NOT NULL default '',
                PRIMARY KEY (metakey))
                ENGINE=MyISAM;"
        );
        $table->setData(sprintf("'version', %s", round($GLOBALS['xoopsModule']->getVar('version') / 100, 2)));
        if ($dbupdater->updateTable($table)) {
            echo "wfdownloads_meta table created<br />";
        }
    }

    // create wfdownloads_mirror table
    if (!wfdownloads_tableExists('wfdownloads_mirrors')) {
        $table = new WfdownloadsTable('wfdownloads_mirrors');
        $table->setStructure(
            "CREATE TABLE %s (
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
                ENGINE=MyISAM;"
        );
        if ($dbupdater->updateTable($table)) {
            echo "wfdownloads_mirrors table created<br />";
        }
    }

    // create wfdownloads_ip_log table
    if (!wfdownloads_tableExists('wfdownloads_ip_log')) {
        $table = new WfdownloadsTable('wfdownloads_ip_log');
        $table->setStructure(
            "CREATE TABLE %s (
                ip_logid int(11) NOT NULL auto_increment,
                lid int(11) NOT NULL default '0',
                uid int(11) NOT NULL default '0',
                date int(11) NOT NULL default '0',
                ip_address varchar(20) NOT NULL default '',
                PRIMARY KEY  (ip_logid)
                ENGINE=MyISAM;"
        );
        if ($dbupdater->updateTable($table)) {
            echo "wfdownloads_mirrors table created<br />";
        }
    }

    // update wfdownloads_downloads table
    $download_fields = array(
        "lid"             => array("Type" => "int(11) unsigned NOT NULL auto_increment", "Default" => false),
        "cid"             => array("Type" => "int(5) unsigned NOT NULL default '0'", "Default" => true),
        "title"           => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "url"             => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "filename"        => array("Type" => "varchar(150) NOT NULL default ''", "Default" => true),
        "filetype"        => array("Type" => "varchar(100) NOT NULL default ''", "Default" => true),
        "homepage"        => array("Type" => "varchar(100) NOT NULL default ''", "Default" => true),
        "version"         => array("Type" => "varchar(20) NOT NULL default ''", "Default" => true),
        "size"            => array("Type" => "int(8) NOT NULL default '0'", "Default" => true),
        "platform"        => array("Type" => "varchar(50) NOT NULL default ''", "Default" => true),
        "screenshot"      => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "screenshot2"     => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "screenshot3"     => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "screenshot4"     => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "submitter"       => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "publisher"       => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "status"          => array("Type" => "tinyint(2) NOT NULL default '" . _WFDOWNLOADS_STATUS_WAITING . "'", "Default" => true),
        "date"            => array("Type" => "int(10) NOT NULL default '0'", "Default" => true),
        "hits"            => array("Type" => "int(11) unsigned NOT NULL default '0'", "Default" => true),
        "rating"          => array("Type" => "double(6,4) NOT NULL default '0.0000'", "Default" => true),
        "votes"           => array("Type" => "int(11) unsigned NOT NULL default '0'", "Default" => true),
        "comments"        => array("Type" => "int(11) unsigned NOT NULL default '0'", "Default" => true),
        "license"         => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "mirror"          => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "price"           => array("Type" => "varchar(10) NOT NULL default 'Free'", "Default" => true),
        "paypalemail"     => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "features"        => array("Type" => "text NOT NULL", "Default" => false),
        "requirements"    => array("Type" => "text NOT NULL", "Default" => false),
        "homepagetitle"   => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "forumid"         => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "limitations"     => array("Type" => "varchar(255) NOT NULL default '30 day trial'", "Default" => true),
        "versiontypes"    => array("Type" => "varchar(255) NOT NULL default 'None'", "Default" => true),
        "dhistory"        => array("Type" => "text NOT NULL", "Default" => false),
        'published'       => array("Type" => "int(11) NOT NULL default '1089662528'", "Default" => true),
        "expired"         => array("Type" => "int(10) NOT NULL default '0'", "Default" => true),
        "updated"         => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "offline"         => array("Type" => "tinyint(1) NOT NULL default '0'", "Default" => true),
        "description"     => array("Type" => "text NOT NULL", "Default" => false),
        "ipaddress"       => array("Type" => "varchar(120) NOT NULL default '0'", "Default" => true),
        "notifypub"       => array("Type" => "int(1) NOT NULL default '0'", "Default" => true),
        "summary"         => array("Type" => "text NOT NULL", "Default" => false),
        "formulize_idreq" => array("Type" => "int(5) NOT NULL default '0'", "Default" => true)
    );
    $renamed_fields  = array(
        "logourl" => "screenshot"
    );
    echo "<br /><span style='font-weight: bold;'>Checking Download table</span><br />";
    $download_handler = xoops_getmodulehandler('download', 'wfdownloads');
    $download_table   = new WfdownloadsTable('wfdownloads_downloads');
    $fields           = get_table_info($download_handler->table, $download_fields);
    // check for renamed fields
    rename_fields($download_table, $renamed_fields, $fields, $download_fields);
    update_table($download_fields, $fields, $download_table);
    if ($dbupdater->updateTable($download_table)) {
        echo "Downloads table updated<br />";
    }
    unset($fields);

    // update wfdownloads_mod table
    $mod_fields     = array(
        "requestid"       => array("Type" => "int(11) NOT NULL auto_increment", "Default" => false),
        //
        "modifysubmitter" => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "requestdate"     => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        //
        "lid"             => array("Type" => "int(11) unsigned NOT NULL default '0'", "Default" => true),
        "cid"             => array("Type" => "int(5) unsigned NOT NULL default '0'", "Default" => true),
        "title"           => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "url"             => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "filename"        => array("Type" => "varchar(150) NOT NULL default ''", "Default" => true),
        "filetype"        => array("Type" => "varchar(100) NOT NULL default ''", "Default" => true),
        "homepage"        => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "version"         => array("Type" => "varchar(20) NOT NULL default ''", "Default" => true),
        "size"            => array("Type" => "int(8) NOT NULL default '0'", "Default" => true),
        "platform"        => array("Type" => "varchar(50) NOT NULL default ''", "Default" => true),
        "screenshot"      => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "screenshot2"     => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "screenshot3"     => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "screenshot4"     => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "submitter"       => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "publisher"       => array("Type" => "text NOT NULL", "Default" => false),
        "status"          => array("Type" => "tinyint(2) NOT NULL default '" . _WFDOWNLOADS_STATUS_WAITING . "'", "Default" => true),
        "date"            => array("Type" => "int(10) NOT NULL default '0'", "Default" => true),
        "hits"            => array("Type" => "int(11) unsigned NOT NULL default '0'", "Default" => true),
        "rating"          => array("Type" => "double(6,4) NOT NULL default '0.0000'", "Default" => true),
        "votes"           => array("Type" => "int(11) unsigned NOT NULL default '0'", "Default" => true),
        "comments"        => array("Type" => "int(11) unsigned NOT NULL default '0'", "Default" => true),
        "license"         => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "mirror"          => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "price"           => array("Type" => "varchar(10) NOT NULL default 'Free'", "Default" => true),
        "paypalemail"     => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "features"        => array("Type" => "text NOT NULL", "Default" => false),
        "requirements"    => array("Type" => "text NOT NULL", "Default" => false),
        "homepagetitle"   => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "forumid"         => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "limitations"     => array("Type" => "varchar(255) NOT NULL default '30 day trial'", "Default" => true),
        "versiontypes"    => array("Type" => "varchar(255) NOT NULL default 'None'", "Default" => true),
        "dhistory"        => array("Type" => "text NOT NULL", "Default" => false),
        'published'       => array("Type" => "int(10) NOT NULL default '0'", "Default" => true),
        "expired"         => array("Type" => "int(10) NOT NULL default '0'", "Default" => true),
        "updated"         => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "offline"         => array("Type" => "tinyint(1) NOT NULL default '0'", "Default" => true),
        "summary"         => array("Type" => "text NOT NULL", "Default" => false),
        "description"     => array("Type" => "text NOT NULL", "Default" => false),
        // ???
        "formulize_idreq" => array("Type" => "int(5) NOT NULL default '0'", "Default" => true)
    );
    $renamed_fields = array(
        "logourl" => "screenshot"
    );
    echo "<br /><span style='font-weight: bold;'>Checking Modified Downloads table</span><br />";
    $mod_handler = xoops_getmodulehandler('modification', 'wfdownloads');
    $mod_table   = new WfdownloadsTable('wfdownloads_mod');
    $fields      = get_table_info($mod_handler->table, $mod_fields);
    rename_fields($mod_table, $renamed_fields, $fields, $mod_fields);
    update_table($mod_fields, $fields, $mod_table);
    if ($dbupdater->updateTable($mod_table)) {
        echo "Modified Downloads table updated <br />";
    }
    unset($fields);

    // update wfdownloads_cat table
    $cat_fields = array(
        "cid"           => array("Type" => "int(5) unsigned NOT NULL auto_increment", "Default" => false),
        "pid"           => array("Type" => "int(5) unsigned NOT NULL default '0'", "Default" => true),
        "title"         => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "imgurl"        => array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "description"   => array("Type" => "text NOT NULL default ''", "Default" => true),
        "total"         => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "summary"       => array("Type" => "text NOT NULL", "Default" => false),
        "spotlighttop"  => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "spotlighthis"  => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "dohtml"        => array("Type" => "tinyint(1) NOT NULL default '1'", "Default" => true),
        "dosmiley"      => array("Type" => "tinyint(1) NOT NULL default '1'", "Default" => true),
        "doxcode"       => array("Type" => "tinyint(1) NOT NULL default '1'", "Default" => true),
        "doimage"       => array("Type" => "tinyint(1) NOT NULL default '1'", "Default" => true),
        "dobr"          => array("Type" => "tinyint(1) NOT NULL default '1'", "Default" => true),
        "weight"        => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "formulize_fid" => array("Type" => "int(5) NOT NULL default '0'", "Default" => true)
    );
    echo "<br /><span style='font-weight: bold;'>Checking Category table</span><br />";
    $cat_handler = xoops_getmodulehandler('category', 'wfdownloads');
    $cat_table   = new WfdownloadsTable('wfdownloads_cat');
    $fields      = get_table_info($cat_handler->table, $cat_fields);
    update_table($cat_fields, $fields, $cat_table);
    if ($dbupdater->updateTable($cat_table)) {
        echo "Category table updated<br />";
    }
    unset($fields);

    // update wfdownloads_broken table
    $broken_fields = array(
        "reportid"     => array("Type" => "int(5) NOT NULL auto_increment", "Default" => false),
        "lid"          => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "sender"       => array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "ip"           => array("Type" => "varchar(20) NOT NULL default ''", "Default" => true),
        "date"         => array("Type" => "varchar(11) NOT NULL default '0'", "Default" => true),
        "confirmed"    => array("Type" => "tinyint(1) NOT NULL default '0'", "Default" => true),
        "acknowledged" => array("Type" => "tinyint(1) NOT NULL default '0'", "Default" => true)
    );
    echo "<br /><span style='font-weight: bold;'>Checking Broken Report table</span><br />";
    $broken_handler = xoops_getmodulehandler('report', 'wfdownloads');
    $broken_table   = new WfdownloadsTable('wfdownloads_broken');
    $fields         = get_table_info($broken_handler->table, $broken_fields);
    update_table($broken_fields, $fields, $broken_table);
    if ($dbupdater->updateTable($broken_table)) {
        echo "Broken Reports table updated<br />";
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
    $ret         = array();
    $cat_handler = xoops_getmodulehandler('category', 'wfdownloads');
    $result      = $GLOBALS['xoopsDB']->query("SHOW COLUMNS FROM " . $cat_handler->table);
    while ($existing_field = $GLOBALS['xoopsDB']->fetchArray($result)) {
        $fields[$existing_field['Field']] = $existing_field['Type'];
    }
    if (in_array("nohtml", array_keys($fields))) {
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
 * @param WfDownloadsTable $table
 *
 * @return void
 */
function update_table($new_fields, $existing_fields, &$table)
{
    foreach ($new_fields as $field => $fieldinfo) {
        $type = $fieldinfo["Type"];
        if (!in_array($field, array_keys($existing_fields))) {
            //Add field as it is missing
            $table->addNewField($field, $type);
            //$GLOBALS['xoopsDB']->query("ALTER TABLE " . $table . " ADD " . $field . " " . $type);
            //echo $field . "(" . $type . ") <FONT COLOR='##22DD51'>Added</FONT><br />";
        } elseif ($existing_fields[$field] != $type) {
            $table->addAlteredField($field, $field . " " . $type);
            // check $fields[$field]['type'] for things like "int(10) unsigned"
            //$GLOBALS['xoopsDB']->query("ALTER TABLE " . $table . " CHANGE " . $field . " " . $field . " " . $type);
            //echo $field . " <FONT COLOR='#FF6600'>Changed to</FONT> " . $type . "<br />";
        } else {
            //echo $field . " <FONT COLOR='#0033FF'>Uptodate</FONT><br />";
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
    $result = $GLOBALS['xoopsDB']->query("SHOW COLUMNS FROM " . $table);
    while ($existing_field = $GLOBALS['xoopsDB']->fetchArray($result)) {
        $fields[$existing_field['Field']] = $existing_field['Type'];
        if ($existing_field['Null'] != "YES") {
            $fields[$existing_field['Field']] .= " NOT NULL";
        }
        if ($existing_field['Extra']) {
            $fields[$existing_field['Field']] .= " " . $existing_field['Extra'];
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
 * @param WfDownloadsTable $table
 * @param array            $renamed_fields
 * @param array            $fields
 * @param array            $new_fields
 *
 * @return array
 */
function rename_fields(&$table, $renamed_fields, &$fields, $new_fields)
{
    foreach (array_keys($fields) as $field) {
        if (in_array($field, array_keys($renamed_fields))) {
            $new_field_name = $renamed_fields[$field];
            $new_field_type = $new_fields[$new_field_name]["Type"];
            $table->addAltered($field, $new_field_name . " " . $new_field_type);
            //$GLOBALS['xoopsDB']->query("ALTER TABLE " . $table . " CHANGE " . $field . " " . $new_field_name . " " . $new_field_type);
            //echo $field." Renamed to ". $new_field_name . "<br />";
            $fields[$new_field_name] = $new_field_type;
        }
    }
    //return $fields;
}
