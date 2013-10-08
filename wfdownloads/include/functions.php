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
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}
include_once dirname(__FILE__) . '/common.php';

/**
 *
 * Standard functions
 *
 */

/**
 * This function transforms a numerical size (like 2048) to a letteral size (like 2MB)
 *
 * @param   integer $bytes numerical size
 *
 * @return  string     $size     letteral size
 **/
function wfdownloads_bytesToSize1000($bytes, $precision = 2)
{
    // human readable format -- powers of 1000
    $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb', 'eb');

    return @round(
            $bytes / pow(1000, ($i = floor(log($bytes, 1000)))),
            $precision
        ) . ' ' . $unit[$i];
}

function wfdownloads_bytesToSize1024($bytes, $precision = 2)
{
    // human readable format -- powers of 1024
    $unit = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');

    return @round(
            $bytes / pow(1024, ($i = floor(log($bytes, 1024)))),
            $precision
        ) . ' ' . $unit[$i];
}

/**
 * This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
 *
 * @param   string $size letteral size
 *
 * @return  integer    $ret     numerical size
 **/
function wfdownloads_sizeToBytes1024($size)
{
    $l   = substr($size, -1);
    $ret = substr($size, 0, -1);
    switch (strtoupper($l)) {
        case 'P':
        case 'p':
            $ret *= 1024;
            break;
        case 'T':
        case 't':
            $ret *= 1024;
            break;
        case 'G':
        case 'g':
            $ret *= 1024;
            break;
        case 'M':
        case 'm':
            $ret *= 1024;
            break;
        case 'K':
        case 'k':
            $ret *= 1024;
            break;
    }

    return $ret;
}

/**
 *
 * Filesystem functions
 *
 */

/**
 * This function will read the full structure of a directory.
 * It's recursive because it doesn't stop with the one directory,
 * it just keeps going through all of the directories in the folder you specify.
 *
 * @param   string $path path to the directory to make
 * @param   int    $level
 *
 * @return  array
 */
function wfdownloads_getDir($path = '.', $level = 0)
{
    $ret    = array();
    $ignore = array('cgi-bin', '.', '..');
    // Directories to ignore when listing output. Many hosts will deny PHP access to the cgi-bin.
    $dirHandler = @opendir($path);
    // Open the directory to the handle $dirHandler
    while (false !== ($file = readdir($dirHandler))) {
        // Loop through the directory
        if (!in_array($file, $ignore)) {
            // Check that this file is not to be ignored
            $spaces = str_repeat('&nbsp;', ($level * 4));
            // Just to add spacing to the list, to better show the directory tree.
            if (is_dir("$path/$file")) {
                // Its a directory, so we need to keep reading down...
                $ret[] = "<strong>{$spaces} {$file}</strong>";
                $ret   = array_merge($ret, wfdownloads_getDir($path . DIRECTORY_SEPARATOR . $file, ($level + 1)));
                // Re-call this same function but on a new directory.
                // this is what makes function recursive.
            } else {
                $ret[] = "{$spaces} {$file}";
                // Just print out the filename
            }
        }
    }
    closedir($dirHandler);
    // Close the directory handle
    return $ret;
}

/**
 * Create a new directory that contains the file index.html
 *
 * @param   string $dir          path to the directory to make
 * @param   int    $perm         mode
 * @param   bool   $create_index if true create index.html
 *
 * @return  bool                    Returns true on success or false on failure
 */
function wfdownloads_makeDir($dir, $perm = 0777, $create_index = true)
{
    if (!is_dir($dir)) {
        if (!@mkdir($dir, $perm)) {
            return false;
        } else {
            if ($create_index) {
                if ($fileHandler = @fopen($dir . '/index.html', 'w')) {
                    fwrite($fileHandler, '<script>history.go(-1);</script>');
                }
                @fclose($fileHandler);
            }

            return true;
        }
    }
}

/**
 * Copy a file
 *
 * @param   string $source      is the original directory
 * @param   string $destination is the destination directory
 *
 * @return  bool                    Returns true on success or false on failure
 *
 */
function wfdownloads_copyFile($source, $destination)
{
    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $destination);
    } else {
        return false;
    }
}

/**
 * Copy a directory and its contents
 *
 * @param   string $source      is the original directory
 * @param   string $destination is the destination directory
 *
 * @return  bool                    Returns true on success or false on failure
 *
 */
function wfdownloads_copyDir($source, $destination)
{
    if (!$dirHandler = opendir($source)) {
        return false;
    }
    @mkdir($destination);
    while (false !== ($file = readdir($dirHandler))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir("{$source}/{$file}")) {
                if (!wfdownloads_copyDir("{$source}/{$file}", "{$destination}/{$file}")) {
                    return false;
                }
            } else {
                if (!copy("{$source}/{$file}", "{$destination}/{$file}")) {
                    return false;
                }
            }
        }
    }
    closedir($dirHandler);

    return true;
}

/**
 * Delete a empty/not empty directory
 *
 * @param   string $dir          path to the directory to delete
 * @param   bool   $if_not_empty if false it delete directory only if false
 *
 * @return  bool                    Returns true on success or false on failure
 */
function wfdownloads_delDir($dir, $if_not_empty = true)
{
    if (!file_exists($dir)) {
        return true;
    }
    if ($if_not_empty == true) {
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!wfdownloads_delDir("{$dir}/{$item}")) {
                return false;
            }
        }
    } else {
        // NOP
    }

    return rmdir($dir);
}

/**
 *
 * Module functions
 *
 */

/**
 * Check if a module exist and return module verision
 *
 * @param   string $dirname
 *
 * @return  boolean, integer   false if module not installed or not active, module version if installed
 *
 * @access  public
 * @author  luciorota
 */
function wfdownloads_checkModule($dirname)
{
    if (!xoops_isActiveModule($dirname)) {
        return false;
    }
    $module_handler =& xoops_gethandler('module');
    $module         = $module_handler->getByDirname($dirname);

    return $module->getVar('version');
}

/**
 * Recursively sort categories by level and weight
 *
 * @param   integer $pid
 * @param   integer $level
 *
 * @return  array   array of arrays: 'pid', 'cid', 'level', 'category' as array
 *
 * @access  public
 * @author  luciorota
 */
function wfdownloads_sortCategories($pid = 0, $level = 0)
{
    $wfdownloads = WfdownloadsWfdownloads::getInstance();

    $sorted   = array();
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria("pid", $pid));
    $criteria->setSort("weight");
    $criteria->setOrder("ASC");
    $subCategories = $wfdownloads->getHandler('category')->getObjects($criteria);
    if (count($subCategories) > 0) {
        $level++;
        foreach ($subCategories as $subCategory) {
            $pid      = $subCategory->getVar('pid');
            $cid      = $subCategory->getVar('cid');
            $sorted[] = array('pid' => $pid, 'cid' => $cid, 'level' => $level, 'category' => $subCategory->toArray());
            if ($subSorted = wfdownloads_sortCategories($cid, $level)) {
                $sorted = array_merge($sorted, $subSorted);
            }
        }
    }

    return $sorted;
}

/**
 * Create download by letter choice bar/menu
 * updated starting from this idea http://xoops.org/modules/news/article.php?storyid=6497
 *
 * @return  string   html
 *
 * @access  public
 * @author  luciorota
 */
function wfdownloads_lettersChoice()
{
    $wfdownloads = WfdownloadsWfdownloads::getInstance();

    $criteria = $wfdownloads->getHandler('download')->getActiveCriteria();
    $criteria->setGroupby('UPPER(LEFT(title,1))');
    $countsByLetters = $wfdownloads->getHandler('download')->getCounts($criteria);
    // Fill alphabet array
    $alphabet       = wfdownloads_alphabet();
    $alphabet_array = array();
    foreach ($alphabet as $letter) {
        $letter_array = array();
        if (isset($countsByLetters[$letter])) {
            $letter_array['letter'] = $letter;
            $letter_array['count']  = $countsByLetters[$letter];
            $letter_array['url']    = "" . XOOPS_URL . "/modules/" . $wfdownloads->getModule()->dirname() . "/viewcat.php?list={$letter}";
        } else {
            $letter_array['letter'] = $letter;
            $letter_array['count']  = 0;
            $letter_array['url']    = "";
        }
        $alphabet_array[$letter] = $letter_array;
        unset($letter_array);
    }
    // Render output
    if (!isset($GLOBALS['xoTheme']) || !is_object($GLOBALS['xoTheme'])) {
        include_once $GLOBALS['xoops']->path("/class/theme.php");
        $GLOBALS['xoTheme'] = new xos_opal_Theme();
    }
    require_once $GLOBALS['xoops']->path('class/template.php');
    $letterschoiceTpl          = new XoopsTpl();
    $letterschoiceTpl->caching = false; // Disable cache
    $letterschoiceTpl->assign('alphabet', $alphabet_array);
    $html = $letterschoiceTpl->fetch("db:" . $wfdownloads->getModule()->dirname() . "_common_letterschoice.html");
    unset($letterschoiceTpl);

    return $html;
}

/**
 * Checks if a user is admin of Wfdownloads
 *
 * @return boolean
 */
function wfdownloads_userIsAdmin()
{
    global $xoopsUser;
    $wfdownloads = WfdownloadsWfdownloads::getInstance();

    static $wfdownloads_isAdmin;

    if (isset($wfdownloads_isAdmin)) {
        return $wfdownloads_isAdmin;
    }

    if (!$xoopsUser) {
        $wfdownloads_isAdmin = false;
    } else {
        $wfdownloads_isAdmin = $xoopsUser->isAdmin($wfdownloads->getModule()->getVar('mid'));
    }

    return $wfdownloads_isAdmin;
}

function wfdownloads_xoops_cp_header()
{
    xoops_cp_header();
}

function wfdownloads_module_home($withLink = true)
{
    $wfdownloads = WfdownloadsWfdownloads::getInstance();

    $wfdownloadsModuleName = $wfdownloads->getModule()->getVar('name');
    if (!$withLink) {
        return $wfdownloadsModuleName;
    } else {
        return '<a href="' . WFDOWNLOADS_URL . '/">{$wfdownloadsModuleName}</a>';
    }
}

/**
 * Detemines if a table exists in the current db
 *
 * @param string $table the table name (without XOOPS prefix)
 *
 * @return bool True if table exists, false if not
 *
 * @access public
 * @author xhelp development team
 */
function wfdownloads_tableExists($table)
{
    $bRetVal = false;
    //Verifies that a MySQL table exists
    $xoopsDB  =& XoopsDatabaseFactory::getDatabaseConnection();
    $realName = $xoopsDB->prefix($table);

    $sql = "SHOW TABLES FROM " . XOOPS_DB_NAME;
    $ret = $xoopsDB->queryF($sql);

    while (list($m_table) = $xoopsDB->fetchRow($ret)) {
        if ($m_table == $realName) {
            $bRetVal = true;
            break;
        }
    }
    $xoopsDB->freeRecordSet($ret);

    return ($bRetVal);
}

/**
 * Gets a value from a key in the xhelp_meta table
 *
 * @param string $key
 *
 * @return string $value
 *
 * @access public
 * @author xhelp development team
 */
function wfdownloads_getMeta($key)
{
    $xoopsDB =& XoopsDatabaseFactory::getDatabaseConnection();
    $sql     = sprintf("SELECT metavalue FROM %s WHERE metakey=%s", $xoopsDB->prefix('wfdownloads_meta'), $xoopsDB->quoteString($key));
    $ret     = $xoopsDB->query($sql);
    if (!$ret) {
        $value = false;
    } else {
        list($value) = $xoopsDB->fetchRow($ret);

    }

    return $value;
}

/**
 * Sets a value for a key in the xhelp_meta table
 *
 * @param string $key
 * @param string $value
 *
 * @return bool true if success, false if failure
 *
 * @access public
 * @author xhelp development team
 */
function wfdownloads_setMeta($key, $value)
{
    $xoopsDB =& XoopsDatabaseFactory::getDatabaseConnection();
    if ($ret = wfdownloads_getMeta($key)) {
        $sql = sprintf(
            "UPDATE %s SET metavalue = %s WHERE metakey = %s",
            $xoopsDB->prefix('wfdownloads_meta'),
            $xoopsDB->quoteString($value),
            $xoopsDB->quoteString($key)
        );
    } else {
        $sql = sprintf(
            "INSERT INTO %s (metakey, metavalue) VALUES (%s, %s)",
            $xoopsDB->prefix('wfdownloads_meta'),
            $xoopsDB->quoteString($key),
            $xoopsDB->quoteString($value)
        );
    }
    $ret = $xoopsDB->queryF($sql);
    if (!$ret) {
        return false;
    }

    return true;
}

function wfdownloads_setCookieVar($name, $value, $time = 0)
{
    if ($time == 0) {
        $time = time() + 3600 * 24 * 365;
        //$time = '';
    }
    setcookie($name, $value, $time, '/');
}

function wfdownloads_getCookieVar($name, $default = '')
{
    if ((isset($_COOKIE[$name])) && ($_COOKIE[$name] > '')) {
        return $_COOKIE[$name];
    } else {
        return $default;
    }
}

function wfdownloads_getCurrentUrls()
{
    $http        = ((strpos(XOOPS_URL, "https://")) === false) ? ("http://") : ("https://");
    $phpSelf     = $_SERVER['PHP_SELF'];
    $httpHost    = $_SERVER['HTTP_HOST'];
    $queryString = $_SERVER['QUERY_STRING'];

    If ($queryString != '') {
        $queryString = '?' . $queryString;
    }

    $currentURL = $http . $httpHost . $phpSelf . $queryString;

    $urls                = array();
    $urls['http']        = $http;
    $urls['httphost']    = $httpHost;
    $urls['phpself']     = $phpSelf;
    $urls['querystring'] = $queryString;
    $urls['full']        = $currentURL;

    return $urls;
}

function wfdownloads_getCurrentPage()
{
    $urls = wfdownloads_getCurrentUrls();

    return $urls['full'];
}

function wfdownloads_formatErrors($errors = array())
{
    $ret = '';
    foreach ($errors as $key => $value) {
        $ret .= "<br /> - {$value}";
    }

    return $ret;
}

// TODO : The SEO feature is not fully implemented in the module...
function wfdownloads_seo_genUrl($op, $id, $title = "")
{
    if (defined('SEO_ENABLED')) {
        if (SEO_ENABLED == 'rewrite') {
            // generate SEO url using htaccess
            return XOOPS_URL . "/wfdownloads.${op}.${id}/" . wfdownloads_seo_title($title);
        } elseif (SEO_ENABLED == 'path-info') {
            // generate SEO url using path-info
            return XOOPS_URL . "/modules/wfdownloads/seo.php/${op}.${id}/" . wfdownloads_seo_title($title);
        } else {
            die('Unknown SEO method.');
        }
    } else {
        // generate classic url
        switch ($op) {
            case 'category':
                return XOOPS_URL . "/modules/wfdownloads/${op}.php?categoryid=${id}";
            case 'item':
            case 'print':
                return XOOPS_URL . "/modules/wfdownloads/${op}.php?itemid=${id}";
            default:
                die('Unknown SEO operation.');
        }
    }
}

/**
 * save_Permissions()
 *
 * @param $groups
 * @param $id
 * @param $perm_name
 *
 * @return
 **/
function wfdownloads_savePermissions($groups, $id, $permName)
{
    $wfdownloads = WfdownloadsWfdownloads::getInstance();

    $id            = (int)$id;
    $result        = true;
    $mid           = $wfdownloads->getModule()->mid();
    $gperm_handler = xoops_gethandler('groupperm');

    // First, if the permissions are already there, delete them
    $gperm_handler->deleteByModule($mid, $permName, $id);
    // Save the new permissions
    if (is_array($groups)) {
        foreach ($groups as $group_id) {
            $gperm_handler->addRight($permName, $id, $group_id, $mid);
        }
    }

    return $result;
}

/**
 * toolbar()
 *
 * @return
 **/
function wfdownloads_toolbar()
{
    global $xoopsUser;
    $wfdownloads = WfdownloadsWfdownloads::getInstance();

    $submissions = 0;
    if (is_object($xoopsUser)
        && ($wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_DOWNLOAD
            || $wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_BOTH)
    ) {
        $groups = $xoopsUser->getGroups();
        if (array_intersect($wfdownloads->getConfig('submitarts'), $groups)) {
            $submissions = 1;
        }
    } elseif (!is_object($xoopsUser)
        && ($wfdownloads->getConfig('anonpost') == _WFDOWNLOADS_ANONPOST_DOWNLOAD
            || $wfdownloads->getConfig('anonpost') == _WFDOWNLOADS_ANONPOST_BOTH)
    ) {
        $submissions = 1;
    }

    $toolbar = "[ ";
    if ($submissions == 1) {
        $category_suffix = !empty($_GET['cid']) ? "?cid=" . (int)$_GET['cid'] : ""; //Added by Lankford
        $toolbar .= "<a href='submit.php{$category_suffix}'>" . _MD_WFDOWNLOADS_SUBMITDOWNLOAD . "</a> | ";
    }
    $toolbar .= "<a href='newlist.php'>" . _MD_WFDOWNLOADS_LATESTLIST . "</a>";
    $toolbar .= " | ";
    $toolbar .= "<a href='topten.php?list=hit'>" . _MD_WFDOWNLOADS_POPULARITY . "</a>";
    if ($wfdownloads->getConfig('enable_ratings')) {
        $toolbar .= " | ";
        $toolbar .= "<a href='topten.php?list=rate'>" . _MD_WFDOWNLOADS_TOPRATED . "</a>";
    }
    $toolbar .= " ]";

    return $toolbar;
}

/**
 * wfdownloads_serverStats()
 *
 * @return
 **/
function wfdownloads_serverStats()
{
    global $xoopsDB;
    $html = "";

    $sql = $xoopsDB->query("SELECT metavalue FROM " . $xoopsDB->prefix('wfdownloads_meta') . " WHERE metakey='version' LIMIT 1");
    list($meta) = $xoopsDB->fetchRow($sql);
    $html .= "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WFDOWNLOADS_DOWN_IMAGEINFO . "</legend>\n";
    $html .= "<div style='padding: 8px;'>\n";
    $html .= "<div>" . _AM_WFDOWNLOADS_DOWN_METAVERSION . $meta . "</div>\n";
    $html .= "<br />\n";
    $html .= "<br />\n";
    $html .= "<div>" . _AM_WFDOWNLOADS_DOWN_SPHPINI . "</div>\n";
    $html .= "<ul>\n";
    //
    $gdlib = (function_exists('gd_info')) ? "<span style=\"color: green;\">" . _AM_WFDOWNLOADS_DOWN_GDON . "</span>"
        : "<span style=\"color: red;\">" . _AM_WFDOWNLOADS_DOWN_GDOFF . "</span>";
    $html .= "<li>" . _AM_WFDOWNLOADS_DOWN_GDLIBSTATUS . $gdlib;
    if (function_exists('gd_info')) {
        if (true == $gdlib = gd_info()) {
            $html .= "<li>" . _AM_WFDOWNLOADS_DOWN_GDLIBVERSION . "<b>" . $gdlib['GD Version'] . "</b>";
        }
    }
    //
    $safemode = (ini_get('safe_mode')) ? _AM_WFDOWNLOADS_DOWN_ON . _AM_WFDOWNLOADS_DOWN_SAFEMODEPROBLEMS : _AM_WFDOWNLOADS_DOWN_OFF;
    $html .= "<li>" . _AM_WFDOWNLOADS_DOWN_SAFEMODESTATUS . $safemode;
    //
    $registerglobals = (!ini_get('register_globals')) ? "<span style=\"color: green;\">" . _AM_WFDOWNLOADS_DOWN_OFF . "</span>"
        : "<span style=\"color: red;\">" . _AM_WFDOWNLOADS_DOWN_ON . "</span>";
    $html .= "<li>" . _AM_WFDOWNLOADS_DOWN_REGISTERGLOBALS . $registerglobals;
    //
    $downloads = (ini_get('file_uploads')) ? "<span style=\"color: green;\">" . _AM_WFDOWNLOADS_DOWN_ON . "</span>"
        : "<span style=\"color: red;\">" . _AM_WFDOWNLOADS_DOWN_OFF . "</span>";
    $html .= "<li>" . _AM_WFDOWNLOADS_DOWN_SERVERUPLOADSTATUS . $downloads;
    //
    $html .= "<li>" . _AM_WFDOWNLOADS_DOWN_MAXUPLOADSIZE . " <b><span style=\"color: blue;\">" . ini_get('upload_max_filesize') . "</span></b>\n";
    $html .= "<li>" . _AM_WFDOWNLOADS_DOWN_MAXPOSTSIZE . " <b><span style=\"color: blue;\">" . ini_get('post_max_size') . "</span></b>\n";
    $html .= "</ul>\n";

    $html .= "<ul>\n";
    $html .= "<li>" . _AM_WFDOWNLOADS_DOWN_SERVERPATH . " <b>" . XOOPS_ROOT_PATH . "</b>\n";

    $html .= "</ul>\n";
    $html .= "<br />\n";
    $html .= _AM_WFDOWNLOADS_DOWN_UPLOADPATHDSC . "\n";
    $html .= "</div>";
    $html .= "</fieldset><br />";

    return $html;
}

/**
 * displayicons()
 *
 * @param           $time
 * @param   integer $status
 * @param   integer $counter
 *
 * @return
 */
function wfdownloads_displayIcons($time, $status = 0, $counter = 0)
{
    $wfdownloads = WfdownloadsWfdownloads::getInstance();

    $new = '';
    $pop = '';

    $newdate = (time() - (86400 * $wfdownloads->getConfig('daysnew')));
    $popdate = (time() - (86400 * $wfdownloads->getConfig('daysupdated')));

    if ($wfdownloads->getConfig('displayicons') != _WFDOWNLOADS_DISPLAYICONS_NO) {
        if ($newdate < $time) {
            if (intval($status) > 1) {
                if ($wfdownloads->getConfig('displayicons') == _WFDOWNLOADS_DISPLAYICONS_ICON) {
                    $new = "&nbsp;<img src=" . XOOPS_URL . "/modules/wfdownloads/images/icon/update.gif alt='' align ='absmiddle'/>";
                }
                if ($wfdownloads->getConfig('displayicons') == _WFDOWNLOADS_DISPLAYICONS_TEXT) {
                    $new = "<i>" . _WFDOWNLOADS_MD_UPDATED . "</i>";
                }
            } else {
                if ($wfdownloads->getConfig('displayicons') == _WFDOWNLOADS_DISPLAYICONS_ICON) {
                    $new = "&nbsp;<img src=" . XOOPS_URL . "/modules/wfdownloads/images/icon/newred.gif alt='' align ='absmiddle'/>";
                }
                if ($wfdownloads->getConfig('displayicons') == _WFDOWNLOADS_DISPLAYICONS_TEXT) {
                    $new = "<i>" . _WFDOWNLOADS_MD_NEW . "</i>";
                }
            }
        }
        if ($popdate < $time) {
            if ($counter >= $wfdownloads->getConfig('popular')) {
                if ($wfdownloads->getConfig('displayicons') == _WFDOWNLOADS_DISPLAYICONS_ICON) {
                    $pop = "&nbsp;<img src =" . XOOPS_URL . "/modules/wfdownloads/images/icon/pop.gif alt='' align ='absmiddle'/>";
                }
                if ($wfdownloads->getConfig('displayicons') == _WFDOWNLOADS_DISPLAYICONS_TEXT) {
                    $pop = "<i>" . _WFDOWNLOADS_MD_POPULAR . "</i>";
                }
            }
        }
    }
    $icons = "{$new} {$pop}";

    return $icons;
}

if (!function_exists('convertorderbyin')) {
    // Reusable Link Sorting Functions
    /**
     * convertorderbyin()
     *
     * @param   $orderby
     *
     * @return
     **/
    function convertorderbyin($orderby)
    {
        switch (trim($orderby)) {
            case "titleA" : $orderby = "title ASC"; break;
            case "titleD" : $orderby = "title DESC"; break;

            case "dateA" : $orderby = "published ASC"; break;
            case "dateD" : $orderby = "published DESC"; break;

            case "hitsA" : $orderby = "hits ASC"; break;
            case "hitsD" : $orderby = "hits DESC"; break;

            case "ratingA" : $orderby = "rating ASC"; break;
            case "ratingD" : $orderby = "rating DESC"; break;

            case "sizeD" : $orderby = "size DESC"; break;
            case "sizeA" : $orderby = "size ASC"; break;

            default: $orderby = "published DESC"; break;
        }

        return $orderby;
    }
}

if (!function_exists('convertorderbytrans')) {
    function convertorderbytrans($orderby)
    {
        if ($orderby == "title ASC") $orderbyTrans = _MD_WFDOWNLOADS_TITLEATOZ;
        if ($orderby == "title DESC") $orderbyTrans = _MD_WFDOWNLOADS_TITLEZTOA;

        if ($orderby == "published ASC") $orderbyTrans = _MD_WFDOWNLOADS_DATEOLD;
        if ($orderby == "published DESC") $orderbyTrans = _MD_WFDOWNLOADS_DATENEW;

        if ($orderby == "hits ASC") $orderbyTrans = _MD_WFDOWNLOADS_POPULARITYLTOM;
        if ($orderby == "hits DESC") $orderbyTrans = _MD_WFDOWNLOADS_POPULARITYMTOL;

        if ($orderby == "rating ASC") $orderbyTrans = _MD_WFDOWNLOADS_RATINGLTOH;
        if ($orderby == "rating DESC") $orderbyTrans = _MD_WFDOWNLOADS_RATINGHTOL;

        if ($orderby == "size ASC") $orderbyTrans = _MD_WFDOWNLOADS_SIZELTOH;
        if ($orderby == "size DESC") $orderbyTrans = _MD_WFDOWNLOADS_SIZEHTOL;

        return $orderbyTrans;
    }
}

if (!function_exists('convertorderbyout')) {
    function convertorderbyout($orderby)
    {
        if ($orderby == "title ASC")  $orderby = "titleA";
        if ($orderby == "title DESC") $orderby = "titleD";
        
        if ($orderby == "published ASC") $orderby = "dateA";
        if ($orderby == "published DESC") $orderby = "dateD";
        
        if ($orderby == "hits ASC") $orderby = "hitsA";
        if ($orderby == "hits DESC") $orderby = "hitsD";
        
        if ($orderby == "rating ASC") $orderby = "ratingA";
        if ($orderby == "rating DESC") $orderby = "ratingD";
        
        if ($orderby == "size ASC") $orderby = "sizeA";
        if ($orderby == "size DESC") $orderby = "sizeD";
        return $orderby;
    }
}

/**
 * updaterating()
 *
 * @param   $lid
 *
 * @return  updates rating data in itemtable for a given item
 **/
function wfdownloads_updateRating($lid)
{
    $wfdownloads = WfdownloadsWfdownloads::getInstance();

    $ratings       = $wfdownloads->getHandler('rating')->getObjects(new Criteria('lid', (int)$lid));
    $ratings_count = count($ratings);
    $totalRating   = 0;
    foreach ($ratings as $rating) {
        $totalRating += $rating->getVar('rating');
    }
    $averageRating = $totalRating / $ratings_count;
    $averageRating = number_format($averageRating, 4);
    $download      = $wfdownloads->getHandler('download')->get($lid);
    $download->setVar('rating', $averageRating);
    $download->setVar('votes', $ratings_count);
    $wfdownloads->getHandler('download')->insert($download);
}

/**
 * wfdownloads_categoriesCount()
 *
 * @return
 **/
function wfdownloads_categoriesCount()
{
    global $xoopsUser;
    $gperm_handler = xoops_gethandler('groupperm');
    $wfdownloads   = WfdownloadsWfdownloads::getInstance();

    $groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);

    $allowedDownCategoriesIds = $gperm_handler->getItemIds('WFDownCatPerm', $groups, $wfdownloads->getModule()->mid());

    return count($allowedDownCategoriesIds);
}

/**
 * wfdownloads_getTotalDownloads()
 *
 * @param   integer OR array of integer $cids
 *
 * @return  the total number of items in items table that are accociated with a given table $table id
 **/
function wfdownloads_getTotalDownloads($cids = 0)
{
    $wfdownloads = WfdownloadsWfdownloads::getInstance();

    $criteria = new CriteriaCompo(new Criteria('offline', false));
    $criteria->add(new Criteria('published', 0, '>'));
    $criteria->add(new Criteria('published', time(), '<='));
    $expiredCriteria = new CriteriaCompo(new Criteria('expired', 0));
    $expiredCriteria->add(new Criteria('expired', time(), '>='), 'OR');
    $criteria->add($expiredCriteria);
    if (is_array($cids) && count($cids) > 0) {
        $criteria->add(new Criteria('cid', '(' . implode(',', $cids) . ')', 'IN'));
    } elseif ($cids > 0) {
        $criteria->add(new Criteria('cid', (int)$cids));
    } else {
        return false;
    }
    $criteria->setGroupby('cid');

    $info['published'] = $wfdownloads->getHandler('download')->getMaxPublishdate($criteria);
    $info['count']     = $wfdownloads->getHandler('download')->getCount($criteria);

    return $info;
}

function wfdownloads_headerImage()
{
    global $xoopsDB;
    $wfdownloads = WfdownloadsWfdownloads::getInstance();

    $image  = '';
    $result = $xoopsDB->query("SELECT indeximage, indexheading FROM " . $xoopsDB->prefix('wfdownloads_indexpage') . " ");
    list($indexImage, $indexHeading) = $xoopsDB->fetchrow($result);
    if (!empty($indeximage)) {
        $image = wfdownloads_displayImage($indexImage, 'index.php', $wfdownloads->getConfig('mainimagedir'), $indexHeading);
    }

    return $image;
}

function wfdownloads_displayImage($image = '', $href = '', $imgSource = '', $altText = '')
{
    global $xoopsUser;
    $wfdownloads = WfdownloadsWfdownloads::getInstance();

    $showImage = '';

    // Check to see if link is given
    if ($href) {
        $showImage = "<a href='{$href}'>";
    }
    // checks to see if the file is valid else displays default blank image
    if (!is_dir(XOOPS_ROOT_PATH . "/{$imgSource}/{$image}") && file_exists(XOOPS_ROOT_PATH . "/{$imgSource}/{$image}")) {
        $showImage .= "<img src='" . XOOPS_URL . "/{$imgSource}/{$image}' border='0' alt='{$altText}' />";
    } else {
        if ($xoopsUser && $xoopsUser->isAdmin($wfdownloads->getModule()->mid())) {
            $showImage .= "<img src='" . XOOPS_URL . "/modules/wfdownloads/images/brokenimg.png' alt='" . _MD_WFDOWNLOADS_ISADMINNOTICE . "' />";
        } else {
            $showImage .= "<img src='" . XOOPS_URL . "/modules/wfdownloads/images/blank.gif' alt='{$altText}' />";
        }
    }
    if ($href) {
        $showImage .= "</a>";
    }
    clearstatcache();

    return $showImage;
}

/**
 * wfdownloads_createThumb()
 *
 * @param           $img_name
 * @param           $img_path
 * @param           $img_savepath
 * @param   integer $width
 * @param   integer $height
 * @param   integer $quality
 * @param   integer $update
 * @param   integer $aspect
 *
 * @return
 **/
function wfdownloads_createThumb($imgName, $imgPath, $imgSavePath, $width = 100, $height = 100, $quality = 100, $update = 0, $aspect = 1)
{
    $wfdownloads = WfdownloadsWfdownloads::getInstance();

    // paths
    if ($wfdownloads->getConfig('usethumbs') == 0) {
        $imagePath = XOOPS_URL . "/{$imgPath}/{$imgName}";

        return $imagePath;
    }
    $imagePath = XOOPS_ROOT_PATH . "/{$img_path}/{$imgName}";

    $saveFile = "{$imgPath}/{$imgSavePath}/{$width}x{$height}_{$imgName}";
    $savePath = XOOPS_ROOT_PATH . '/' . $savefile;
    // Return the image if no update and image exists
    if ($update == 0 && file_exists($savePath)) {
        return XOOPS_URL . '/' . $saveFile;
    }

    list($origWidth, $origHeight, $type, $attr) = getimagesize($imagePath, $info);

    switch ($type) {
        case 1:
            # GIF image
            if (function_exists('imagecreatefromgif')) {
                $img = @imagecreatefromgif($imagePath);
            } else {
                $img = @imageCreateFromPNG($imagePath);
            }
            break;
        case 2:
            # JPEG image
            $img = @imageCreateFromJPEG($imagePath);
            break;
        case 3:
            # PNG image
            $img = @imageCreateFromPNG($imagePath);
            break;
        default:
            return $imagePath;
            break;
    }

    if (!empty($img)) {
        // Get image size and scale ratio
        $scale = $origWidth / $origHeight;
        if ($width / $height > $scale) {
            $width = $height * $scale;
        } else {
            $height = $width / $scale;
        }
        // Create a new temporary image
        if (function_exists('imagecreatetruecolor')) {
            $tempImg = imagecreatetruecolor($width, $height);
        } else {
            $tempImg = imagecreate($width, $height);
        }
        // Copy and resize old image into new image
        ImageCopyResampled($tempImg, $img, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);
        imagedestroy($img);
        flush();
        $img = $tempImg;
    }

    switch ($type) {
        case 1:
        default:
            # GIF image
            if (function_exists('imagegif')) {
                imagegif($img, $savePath);
            } else {
                imagePNG($img, $savePath);
            }
            break;
        case 2:
            # JPEG image
            imageJPEG($img, $savePath, $quality);
            break;
        case 3:
            # PNG image
            imagePNG($img, $savePath);
            break;
    }
    imagedestroy($img);
    flush();

    return XOOPS_URL . '/' . $saveFile;
}

function wfdownloads_isNewImage($published)
{
    $oneDay    = (time() - (86400 * 1)); // 1 day
    $threeDays = (time() - (86400 * 3)); // 3 days
    $week      = (time() - (86400 * 7)); // 1 week
    if ($published > 0 && $published < $week) {
        $indicator['image']   = 'images/icon/download4.gif';
        $indicator['alttext'] = _MD_WFDOWNLOADS_NEWLAST;
    } elseif ($published >= $week && $published < $threeDays) {
        $indicator['image']   = 'images/icon/download3.gif';
        $indicator['alttext'] = _MD_WFDOWNLOADS_NEWTHIS;
    } elseif ($published >= $threeDays && $published < $oneDay) {
        $indicator['image']   = 'images/icon/download2.gif';
        $indicator['alttext'] = _MD_WFDOWNLOADS_THREE;
    } elseif ($published >= $oneDay) {
        $indicator['image']   = 'images/icon/download1.gif';
        $indicator['alttext'] = _MD_WFDOWNLOADS_TODAY;
    } else {
        $indicator['image']   = 'images/icon/download.gif';
        $indicator['alttext'] = _MD_WFDOWNLOADS_NO_FILES;
    }

    return $indicator;
}

// GetDownloadTime()
// This function is used to show some different download times
// BCMATH-Support in PHP needed!
// (c)02.04.04 by St@neCold, stonecold@csgui.de, http://www.csgui.de
function wfdownloads_getDownloadTime($size = 0, $gmodem = 1, $gisdn = 1, $gdsl = 1, $gslan = 0, $gflan = 0)
{
    $aflag  = array();
    $amtime = array();
    $artime = array();
    $ahtime = array();
    $asout  = array();
    $aflag  = array($gmodem, $gisdn, $gdsl, $gslan, $gflan);
    $amtime = array($size / 6300 / 60, $size / 7200 / 60, $size / 86400 / 60, $size / 1125000 / 60, $size / 11250000 / 60);
    $amname = array('Modem(56k)', 'ISDN(64k)', 'DSL(768k)', 'LAN(10M)', 'LAN(100M');
    for ($i = 0; $i < 5; $i++) {
        $artime[$i] = ($amtime[$i] % 60);
    }
    for ($i = 0; $i < 5; $i++) {
        $ahtime[$i] = sprintf(' %2.0f', $amtime[$i] / 60);
    }
    if ($size <= 0) {
        $dltime = 'N/A';
    } else {
        for ($i = 0; $i < 5; $i++) {
            if (!$aflag[$i]) {
                $asout[$i] = '';
            } else {
                if (($amtime[$i] * 60) < 1) {
                    $asout[$i] = sprintf(' : %4.2fs', $amtime[$i] * 60);
                } else {
                    if ($amtime[$i] < 1) {
                        $asout[$i] = sprintf(' : %2.0fs', round($amtime[$i] * 60));
                    } else {
                        if ($ahtime[$i] == 0) {
                            $asout[$i] = sprintf(' : %5.1fmin', $amtime[$i]);
                        } else {
                            $asout[$i] = sprintf(' : %2.0fh%2.0fmin', $ahtime[$i], $artime[$i]);
                        }
                    }
                }
                $asout[$i] = "<b>" . $amname[$i] . "</b>" . $asout[$i];
                if ($i < 4) {
                    $asout[$i] = $asout[$i] . ' | ';
                }
            }
        }
        $dltime = '';
        for ($i = 0; $i < 5; $i++) {
            $dltime = $dltime . $asout[$i];
        }
    }

    return $dltime;
}

function wfdownloads_strrrchr($haystack, $needle)
{
    return substr($haystack, 0, strpos($haystack, $needle) + 1);
}

function wfdownloads_allowedMimetypes($fileName, $isAdmin = true)
{
    $wfdownloads = WfdownloadsWfdownloads::getInstance();

    $ext      = ltrim(strrchr($fileName, '.'), '.');
    $criteria = new CriteriaCompo(new Criteria('mime_ext', strtolower($ext)));
    if ($isAdmin == true) {
        $criteria->add(new Criteria('mime_admin', true));
    } else {
        $criteria->add(new Criteria('mime_user', true));
    }
    $mimetypes = $wfdownloads->getHandler('mimetype')->getObjects($criteria);
    $mimetype  = $mimetypes[0];
    $ret       = explode(' ', $mimetype->getVar('mime_types'));

    return $ret;
}

/**
 * wfdownloads_uploading()
 *
 * @param   string  $filename
 * @param   string  $uploadDirectory
 * @param   array   $allowedMimetypes
 * @param   string  $redirectURL
 * @param   integer $num
 * @param   bool    $redirect
 * @param   bool    $isAdmin
 * @param   bool    $onlyImages
 *
 * @return  array
 **/
function wfdownloads_uploading(
    $filename,
    $uploadDirectory = 'uploads',
    $allowedMimetypes = array(),
    $redirectURL = 'index.php',
    $num = 0,
    $redirect = false,
    $isAdmin = true,
    $onlyImages = false
) {
    $wfdownloads = WfdownloadsWfdownloads::getInstance();

    $file = array();

    if (empty($allowedMimetypes)) {
        $allowedMimetypes = wfdownloads_allowedMimetypes($_FILES['userfile']['name'], $isAdmin);
    }
    $uploadDirectory = $uploadDirectory . '/';
    $file_name       = $_FILES['userfile']['name'];

    $maxFileSize    = $wfdownloads->getConfig('maxfilesize');
    $maxImageWidth  = $wfdownloads->getConfig('maximgwidth');
    $maxImageHeight = $wfdownloads->getConfig('maximgheight');

    if ($onlyImages) {
        include_once XOOPS_ROOT_PATH . '/modules/wfdownloads/class/img_uploader.php';
        $uploader = new XoopsMediaImgUploader($uploadDirectory, $allowedMimetypes, $maxFileSize, $maxImageWidth, $maxImageHeight);
    } else {
        include_once XOOPS_ROOT_PATH . '/modules/wfdownloads/class/uploader.php';
        $uploader = new XoopsMediaUploader($uploadDirectory, $allowedMimetypes, $maxFileSize, $maxImageWidth, $maxImageHeight);
    }
    $uploader->noAdminSizeCheck(1);

    if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
        if (!$uploader->upload()) {
            $errors = $uploader->getErrors();
            unlink($uploadDirectory . $uploader->savedFileName);
            redirect_header($redirectURL, 2, $errors);
        } else {
            if ($redirect) {
                redirect_header($redirectURL, 1, _AM_WFDOWNLOADS_UPLOADFILE);
            } else {
                if (is_file($uploader->savedDestination)) {
//                    $file['url'] = XOOPS_URL . '/' . $uploadDirectory . '/';
                    $file['filename'] = strtolower($uploader->savedFileName);
                    $file['filetype'] = $_FILES['userfile']['type'];
                    $file['size']     = filesize($uploadDirectory . strtolower($uploader->savedFileName));
                }

                return $file;
            }
        }
    } else {
        $errors = $uploader->getErrors();
        unlink($uploadDirectory . $uploader->savedFileName);
        redirect_header($redirectURL, 1, $errors);
    }
}

function wfdownloads_download($filePath, $isBinary = true, $retBytes = true)
{
    $chunkSize = 1 * (1024 * 1024); // how many bytes per chunk
    $buffer    = '';
    $cnt       = 0;

    if ($isBinary == true) {
        $handler = fopen($filePath, 'rb');
    } else {
        $handler = fopen($filePath, 'r');
    }

    if ($handler === false) {
        return false;
    }
    while (!feof($handler)) {
        $buffer = fread($handler, $chunkSize);
        echo $buffer;
        ob_flush();
        flush();
        if ($retBytes) {
            $cnt += strlen($buffer);
        }
    }
    $status = fclose($handler);
    if ($retBytes && $status) {
        return $cnt; // return num. bytes delivered like readfile() does.
    }

    return $status;
}

function wfdownloads_getForum($selectedForumId)
{
    global $xoopsDB;

    $selectedForumId = (int)$selectedForumId;

    echo "<select name='forumid'>";
    echo "<option value='0'>----------------------</option>";
    $result = $xoopsDB->query("SELECT forum_name, forum_id FROM " . $xoopsDB->prefix("bb_forums") . " ORDER BY forum_id");
    while (list($forumName, $forumId) = $xoopsDB->fetchRow($result)) {
        if ($forumId == $selectedForumId) {
            $optionSelected = "selected='selected'";
        } else {
            $optionSelected = "";
        }
        echo "<option value='{$forumId}' {$optionSelected}>{$forumName}</option>";
    }
    echo "</select></div>";

    return $selectedForumId;
}

function wfdownloads_mirrorOnline($serverURL)
{
    $fp = @fsockopen($serverURL, 80, $errno, $errstr, 5);
    if (!$fp) {
        $online = false;
    } else {
        $online = true;
        fclose($fp);
    }

    return $online;
}

/**
 * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
 * www.gsdesign.ro/blog/cut-html-string-without-breaking-the-tags
 * www.cakephp.org
 *
 * @param string  $text         String to truncate.
 * @param integer $length       Length of returned string, including ellipsis.
 * @param string  $ending       Ending to be appended to the trimmed string.
 * @param boolean $exact        If false, $text will not be cut mid-word
 * @param boolean $considerHtml If true, HTML tags would be handled correctly
 *
 * @return string Trimmed string.
 */
function wfdownloads_truncateHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true)
{
    if ($considerHtml) {
        // if the plain text is shorter than the maximum length, return the whole text
        if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            return $text;
        }
        // splits all html-tags to scanable lines
        preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
        $total_length = strlen($ending);
        $open_tags    = array();
        $truncate     = '';
        foreach ($lines as $line_matchings) {
            // if there is any html-tag in this line, handle it and add it (uncounted) to the output
            if (!empty($line_matchings[1])) {
                // if it's an "empty element" with or without xhtml-conform closing slash
                if (preg_match(
                    '/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is',
                    $line_matchings[1]
                )
                ) {
                    // do nothing
                    // if tag is a closing tag
                } elseif (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                    // delete tag from $open_tags list
                    $pos = array_search($tag_matchings[1], $open_tags);
                    if ($pos !== false) {
                        unset($open_tags[$pos]);
                    }
                    // if tag is an opening tag
                } elseif (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                    // add tag to the beginning of $open_tags list
                    array_unshift($open_tags, strtolower($tag_matchings[1]));
                }
                // add html-tag to $truncate'd text
                $truncate .= $line_matchings[1];
            }
            // calculate the length of the plain text part of the line; handle entities as one character
            $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
            if ($total_length + $content_length > $length) {
                // the number of characters which are left
                $left            = $length - $total_length;
                $entities_length = 0;
                // search for html entities
                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                    // calculate the real length of all entities in the legal range
                    foreach ($entities[0] as $entity) {
                        if ($entity[1] + 1 - $entities_length <= $left) {
                            $left--;
                            $entities_length += strlen($entity[0]);
                        } else {
                            // no more characters left
                            break;
                        }
                    }
                }
                $truncate .= substr($line_matchings[2], 0, $left + $entities_length);
                // maximum lenght is reached, so get off the loop
                break;
            } else {
                $truncate .= $line_matchings[2];
                $total_length += $content_length;
            }
            // if the maximum length is reached, get off the loop
            if ($total_length >= $length) {
                break;
            }
        }
    } else {
        if (strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = substr($text, 0, $length - strlen($ending));
        }
    }
    // if the words shouldn't be cut in the middle...
    if (!$exact) {
        // ...search the last occurance of a space...
        $spacepos = strrpos($truncate, ' ');
        if (isset($spacepos)) {
            // ...and cut the text in this position
            $truncate = substr($truncate, 0, $spacepos);
        }
    }
    // add the defined ending to the text
    $truncate .= $ending;
    if ($considerHtml) {
        // close all unclosed html-tags
        foreach ($open_tags as $tag) {
            $truncate .= '</' . $tag . '>';
        }
    }

    return $truncate;
}


/*
php4swish-e 1.1, a web search interface for the swish-e search engine.
swish-e is a popular open-source search engine that runs on many platforms.
More information on swish-e is available at swish-e.org.
This code has been thoroughly tested and is ready for production
 on any UNIX or Linux server that has the swish-e search engine installed.
You are free to modify this code as you see fit.
You must specify the path of swish-e ($swish) and
 the location of the search index file ($search_index).
You will also need to change the default index file served if it is not index.php.
If you want the meta description information to display completely,
 be sure the <meta description... information is on *one* line for each web page.
If you wish to allow external search forms to call this script, be sure to set the
 form's action attribute to whatever you name this file.
Suggestions for enhancements are welcome.
*/
function wfdownloads_swishe_config()
{
    // IN PROGRESS
    // IN PROGRESS
    // IN PROGRESS
    global $xoopsUser;
    $wfdownloads = WfdownloadsWfdownloads::getInstance();

    // Get the path to the repository
    // IN PROGRESS
    $doc_path = $wfdownloads->getConfig('swishe_doc_path');

    // Create _binfilter.sh
    //$file = $doc_path."/_binfilter.sh";
    //$fp = fopen($file,'w') or die("<BR><BR>Unable to open $file");

    //fputs($fp,"strings \"\$1\" - 2>/dev/null\n");
    //fclose($fp);

    //chmod($file,0755);

    // Create swish-e.conf
    $file = $doc_path . "/swish-e.conf";
    $fp = fopen($file, 'w') or die("<BR><BR>Unable to open {$file}");

    $line = "IndexDir " . $doc_path . "/\n";
    fputs($fp, $line);
    $line = "IndexFile " . $doc_path . "/index.swish-e\n";
    fputs($fp, $line);
    //$line = "TruncateDocSize 100000\n";
    //fputs($fp,$line);
    $line = "IndexReport 1\n";
    fputs($fp, $line);
    $line = "IndexContents TXT* .dat\n";
    fputs($fp, $line);
    //$line = "FileFilter .dat \"".$doc_path."/_binfilter.sh\" \"'%p'\"\n";
    //fputs($fp,$line);
    $line = "IndexOnly .dat\n";
    fputs($fp, $line);
    $line = "MinWordLimit 3\n";
    fputs($fp, $line);

    fclose($fp);

    chmod($file, 0755);

    print("<SCRIPT LANGUAGE='Javascript'>\r");
    print("location='config_main.php';");
    print("</SCRIPT>");
}

function wfdownloads_swishe_search($search_query)
{
    // IN PROGRESS
    // IN PROGRESS
    // IN PROGRESS
    global $xoopsUser;
    $wfdownloads = WfdownloadsWfdownloads::getInstance();

    $search_query = stripslashes($search_query);
    if (((strlen($search_query) > 2) && ($last_query != $search_query)) && ($page == false)) {
        //print "<BR>SEARCH!";
        // Get the first word in $search_query and use it for the $summary_query.
        $summary_query   = str_replace("\"", " ", $search_query);
        $summary_query   = trim($summary_query);
        $summary_query_e = explode(" ", $summary_query);
        $summary_query   = trim($summary_query_e[0]);
        $summary_query   = rtrim($summary_query, "*");

        //print "<BR>SQ:  ".$summary_query;

        // Get the location of the document repository (the index files are located in the root)
        $repository_root        = $dms_config['doc_path'];
        $repository_root_strlen = strlen($dms_config['doc_path']);

        // Get the location of the SWISH-E executable
        $swish_e_path = $dms_config['swish-e_path'];

        // Get the search_limit to limit the search to X number of entries
        $search_limit = $dms_config['search_limit'];
        //print "<BR>Query:  ".$search_query;
        $search_query  = EscapeShellCmd($search_query); // escape potentially malicious shell commands
        $search_query  = stripslashes($search_query); // remove backslashes from search query
        $search_query  = preg_replace('#("|\')#', '', $search_query); // remove quotes from search query
        $swish         = $swish_e_path . "/swish-e"; // path of swish-e command
        $search_index  = $repository_root . "/index.swish-e"; // path of swish-e index file
        $search_params = "-H1 -m" . $search_limit; // Additional search parameters

        $pipe_handler = popen("$swish -w $search_query -f $search_index $search_params", "r")
        or die("The search request generated an error...Please try again.");

        //print "$swish -w $search_query -f $search_index $search_params<BR>";

        $line_cnt = 1;
        // loop through each line of the pipe result (i.e. swish-e output) to find hit number
        while ($nline = @fgets($pipe_handler, 1024)) {
            if ($line_cnt == 4) {
                $num_line = $nline;
                break; // grab the 4th line, which contains the number of hits returned
            }
            $line_cnt++;
        }

        // strip out all but the number of hits
        $num_results = preg_replace('/# Number of hits: /', '', $num_line);

        //$table_header_flag = false;
        //$disp_nff_flag = true;

        while ($line = @fgets($pipe_handler, 4096)) {
            // loop through each line of the pipe result (i.e. swish-e output)
            if (preg_match("/^(\d+)\s+(\S+)\s+\"(.*)\"\s+(\d+)/", $line)) {
                // Skip commented-out lines and the last line
                $line    = explode('"', $line); // split the string into an array by quotation marks
                $line[1] = preg_replace("/[[:blank:]]/", "%%", $line[1]); // replace every space with %% for the phrase in quotation marks
                $line    = implode('"', $line); // collapse the array into a string
                $line    = preg_replace("/[[:blank:]]/", "\t", $line); // replace every space with a tab
                list ($relevance, $result_url, $result_title, $file_size) = explode(
                    "\t",
                    $line
                ); // split the line into an array by tabs; assign variable names to each column
                $relevance = $relevance / 10; // format relevance as a percentage for search results

                $full_path_and_file = $result_url;
                $result_url         = trim(substr($result_url, ($repository_root_strlen - 1), strlen($result_url)));
                $file_path          = strright($result_url, (strlen($result_url) - 2));

                $query = "SELECT * ";
                $query .= "FROM " . $dmsdb->prefix("dms_object_versions") . " ";
                $query .= "WHERE file_path='{$file_path}'";
                $ver_info = $dmsdb->query($query, 'ROW');

                $query = "SELECT * ";
                $query .= "FROM " . $dmsdb->prefix("dms_objects") . " ";
                $query .= "WHERE obj_id='" . $ver_info->obj_id . "'";
                $obj_info = $dmsdb->query($query, 'ROW');
                if ($obj_info->obj_id > 0) {
                    // Permissions required to view this object:
                    // BROWSE, READONLY, EDIT, OWNER
                    if ($dms_admin_flag == 0) {
                        $perms_level = dms_perms_level($obj_info->obj_id);
                    } else {
                        $perms_level = 4;
                    }
                    if ($obj_info->obj_status < 2) {
                        if (($perms_level == 1) || ($perms_level == 2) || ($perms_level == 3) || ($perms_level == 4)) {
                            $misc_text = $obj_info->misc_text;
                            if (strlen($misc_text) > 0) {
                                $misc_text = "&nbsp;&nbsp;&nbsp;(" . $misc_text . ")";
                            } else {
                                $misc_text = "";
                            }

                            $store_obj_id      = $obj_info->obj_id;
                            $store_obj_name    = $obj_info->obj_name . $misc_text;
                            $store_version_num = $ver_info->major_version . "." . $ver_info->minor_version . "" . $ver_info->sub_minor_version;
                            $store_relevance   = $relevance;
                            dms_store_search_results(
                                $store_obj_id,
                                $store_obj_name,
                                $store_version_num,
                                $relevance,
                                $full_path_and_file,
                                $summary_query
                            );
                        }
                    }
                }
            }
        }
        // close the shell pipe
        pclose($pipe_handler);
    }
}
