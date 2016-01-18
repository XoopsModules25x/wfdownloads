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
$currentFile = basename(__FILE__);
include_once __DIR__ . '/admin_header.php';

// Check directories
if (!is_dir($wfdownloads->getConfig('uploaddir'))) {
    redirect_header('index.php', 4, _AM_WFDOWNLOADS_ERROR_UPLOADDIRNOTEXISTS);
    exit();
}
if (!is_dir(XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('mainimagedir'))) {
    redirect_header('index.php', 4, _AM_WFDOWNLOADS_ERROR_MAINIMAGEDIRNOTEXISTS);
    exit();
}
if (!is_dir(XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('screenshots'))) {
    redirect_header('index.php', 4, _AM_WFDOWNLOADS_ERROR_SCREENSHOTSDIRNOTEXISTS);
    exit();
}
if (!is_dir(XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('catimage'))) {
    redirect_header('index.php', 4, _AM_WFDOWNLOADS_ERROR_CATIMAGEDIRNOTEXISTS);
    exit();
}

$op = XoopsRequest::getString('op', 'import.menu');
switch ($op) {
    case "import.MyDownloads" :
        $ok = XoopsRequest::getBool('ok', false, 'POST');
        if ($ok === true) {
            // Import data from MyDownloads
            import_mydownloads_to_wfdownloads();
            // Downloads imported
            redirect_header($currentFile, 1, _AM_WFDOWNLOADS_IMPORT_IMPORT_OK);
            exit();
        } else {
            wfdownloads_xoops_cp_header();
            xoops_confirm(array('op' => 'import.MyDownloads', 'ok' => true), $currentFile, _AM_WFDOWNLOADS_IMPORT_RUSURE);
            xoops_cp_footer();
        }
        break;

    case "import.PD-Downloads" :
        $ok = XoopsRequest::getBool('ok', false, 'POST');
        if ($ok === true) {
            // Import data from PD-Downloads
            import_pddownloads_to_wfdownloads();
            echo _AM_WFDOWNLOADS_IMPORT_IMPORT_OK;
            xoops_cp_footer();
            // Downloads imported
            //redirect_header($currentFile, 1, _AM_WFDOWNLOADS_IMPORT_IMPORT_OK);
            exit();
        } else {
            wfdownloads_xoops_cp_header();
            xoops_confirm(array('op' => 'import.PD-Downloads', 'ok' => true), $currentFile, _AM_WFDOWNLOADS_IMPORT_RUSURE);
            xoops_cp_footer();
        }
        break;

    case "import.wmpownloads" :
        $ok = XoopsRequest::getBool('ok', false, 'POST');
        if ($ok === true) {
            // Import data from wmpownloads
            import_wmpdownloads_to_wfdownloads();
            echo _AM_WFDOWNLOADS_IMPORT_IMPORT_OK;
            xoops_cp_footer();
            // Downloads imported
            //redirect_header($currentFile, 1, _AM_WFDOWNLOADS_IMPORT_IMPORT_OK);
            exit();
        } else {
            wfdownloads_xoops_cp_header();
            xoops_confirm(array('op' => 'import.wmpownloads', 'ok' => true), $currentFile, _AM_WFDOWNLOADS_IMPORT_RUSURE);
            xoops_cp_footer();
        }
        break;

    case "import.wfd322" :
        $ok = XoopsRequest::getBool('ok', false, 'POST');
        if ($ok === true) {
            // Import data from wfd322
            wfdownloads_xoops_cp_header();
            import_wfd_to_wfdownloads();
            echo _AM_WFDOWNLOADS_IMPORT_IMPORT_OK;
            xoops_cp_footer();
            // Downloads imported
            //redirect_header($currentFile, 1, _AM_WFDOWNLOADS_IMPORT_IMPORT_OK);
            exit();
        } else {
            wfdownloads_xoops_cp_header();
            xoops_confirm(array('op' => 'import.wfd322', 'ok' => true), $currentFile, _AM_WFDOWNLOADS_IMPORT_RUSURE);
            xoops_cp_footer();
        }
        break;

    case "import.TDMDownloads" :
        $ok = XoopsRequest::getBool('ok', false, 'POST');
        if ($ok === true) {
            // Import data from wfd322
            wfdownloads_xoops_cp_header();
            import_tdmdownloads_to_wfdownloads();
            echo _AM_WFDOWNLOADS_IMPORT_IMPORT_OK;
            xoops_cp_footer();
            // Downloads imported
            //redirect_header($currentFile, 1, _AM_WFDOWNLOADS_IMPORT_IMPORT_OK);
            exit();
        } else {
            wfdownloads_xoops_cp_header();
            xoops_confirm(array('op' => 'import.TDMDownloads', 'ok' => true), $currentFile, _AM_WFDOWNLOADS_IMPORT_RUSURE);
            xoops_cp_footer();
        }
        break;

    case "import.menu" :
    default:
        wfdownloads_xoops_cp_header();
        $indexAdmin = new ModuleAdmin();
        echo $indexAdmin->addNavigation($currentFile);

        echo "<fieldset><legend>" . _AM_WFDOWNLOADS_IMPORT_INFORMATION . "</legend>\n";
        echo "<div>" . _AM_WFDOWNLOADS_IMPORT_INFORMATION_TEXT . "</div>\n";
        echo "</fieldset>\n";

        //ask what to do
        include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        $form = new XoopsThemeForm(_AM_WFDOWNLOADS_IMPORT_BUTTON_IMPORT, 'form', $_SERVER['REQUEST_URI']);
        // Avoid module to import form itself
        // Is wf-downloads installed?
        if ($wfdownloads->getModule()->dirname() != 'wf' . "downloads") {
            $got_options = false;
            if (wfdownloads_checkModule('wf' . 'downloads')) { // don't modify, is for cloning
                $moduleVersion = round(wfdownloads_checkModule('wf' . 'downloads') / 100, 2); // don't modify, is for cloning
                $button        = new XoopsFormButton(_AM_WFDOWNLOADS_IMPORT_WFD . '<br />' . $moduleVersion, 'wmp_button', _AM_WFDOWNLOADS_IMPORT_BUTTON_IMPORT, 'submit');
                $button->setExtra("onclick='document.forms.form.op.value=\"import.wfd322\"'");
                $form->addElement($button);
                unset($button);
            } else {
                $label = new XoopsFormLabel(_AM_WFDOWNLOADS_IMPORT_WFD, _AM_WFDOWNLOADS_IMPORT_WFD_NOTFOUND);
                $form->addElement($label);
                unset($label);
            }
        }
        //Is MyDownloads installed?
        $got_options = false;
        if (wfdownloads_checkModule('mydownloads')) {
            $moduleVersion = round(wfdownloads_checkModule('mydownloads') / 100, 2);
            $button        = new XoopsFormButton(_AM_WFDOWNLOADS_IMPORT_MYDOWNLOADS . '<br />' . $moduleVersion, 'myd_button', _AM_WFDOWNLOADS_IMPORT_BUTTON_IMPORT, 'submit');
            $button->setExtra("onclick='document.forms.form.op.value=\"import.MyDownloads\"'");
            $form->addElement($button);
            unset($button);
        } else {
            $label = new XoopsFormLabel(_AM_WFDOWNLOADS_IMPORT_MYDOWNLOADS, _AM_WFDOWNLOADS_IMPORT_MYDOWNLOADS_NOTFOUND);
            $form->addElement($label);
            unset($label);
        }
        //Is PD-Downloads installed?
        $got_options = false;
        if (wfdownloads_checkModule('PDdownloads')) {
            $moduleVersion = round(wfdownloads_checkModule('PDdownloads') / 100, 2);
            $button        = new XoopsFormButton(_AM_WFDOWNLOADS_IMPORT_PDDOWNLOADS . '<br />' . $moduleVersion, 'pd_button', _AM_WFDOWNLOADS_IMPORT_BUTTON_IMPORT, 'submit');
            $button->setExtra("onclick='document.forms.form.op.value=\"import.PD-Downloads\"'");
            $form->addElement($button);
            unset($button);
        } else {
            $label = new XoopsFormLabel(_AM_WFDOWNLOADS_IMPORT_PDDOWNLOADS, _AM_WFDOWNLOADS_IMPORT_PDDOWNLOADS_NOTFOUND);
            $form->addElement($label);
            unset($label);
        }
        //Is wmpownloads installed?
        $got_options = false;
        if (wfdownloads_checkModule('wmpdownloads')) {
            $moduleVersion = round(wfdownloads_checkModule('wmpdownloads') / 100, 2);
            $button        = new XoopsFormButton(_AM_WFDOWNLOADS_IMPORT_WMPOWNLOADS . '<br />' . $moduleVersion, 'wmp_button', _AM_WFDOWNLOADS_IMPORT_BUTTON_IMPORT, 'submit');
            $button->setExtra("onclick='document.forms.form.op.value=\"import.wmpownloads\"'");
            $form->addElement($button);
            unset($button);
        } else {
            $label = new XoopsFormLabel(_AM_WFDOWNLOADS_IMPORT_WMPOWNLOADS, _AM_WFDOWNLOADS_IMPORT_WMPOWNLOADS_NOTFOUND);
            $form->addElement($label);
            unset($label);
        }
        //Is TDMDownloads installed?
        $got_options = false;
        if (wfdownloads_checkModule('TDMDownloads')) {
            $moduleVersion = round(wfdownloads_checkModule('TDMDownloads') / 100, 2);
            $button        = new XoopsFormButton(_AM_WFDOWNLOADS_IMPORT_TDMDOWNLOADS . '<br />' . $moduleVersion, 'wmp_button', _AM_WFDOWNLOADS_IMPORT_BUTTON_IMPORT, 'submit');
            $button->setExtra("onclick='document.forms.form.op.value=\"import.TDMDownloads\"'");
            $form->addElement($button);
            unset($button);
        } else {
            $label = new XoopsFormLabel(_AM_WFDOWNLOADS_IMPORT_TDMDOWNLOADS, _AM_WFDOWNLOADS_IMPORT_TDMDOWNLOADS_NOTFOUND);
            $form->addElement($label);
            unset($label);
        }

        $form->addElement(new XoopsFormHidden('op', 0));
        $form->display();
        include_once __DIR__ . '/admin_footer.php';
        break;
}

// ========================================================
// Conversion file for any version before WF-Downloads 3
// ========================================================
// This file contains 3 functions to do necessary updates either when
// converting from mydownloads to Wfdownloads or upgrading any
// 2.x version of Wfdownloads to version 3.
//
// Starting with WF-Downloads 3.00 we will have a different procedure
// since version information will be stored in the database of Wfdownloads
//
//      function import_wfd_to_wfdownloads
//         This one is needed to import data from WF-Downloads
//
//      function import_mydownloads_to_wfdownloads
//         This one is needed to import data from MyDownloads
//
//      function import_pddownloads_to_wfdownloads
//         This one is needed to import data from PDdownloads
//
//      function import_wmpdownloads_to_wfdownloads
//         This one is needed to import data from wmpdownloads
//
//      function import_tdmdownloads_to_wfdownloads
//         This one is needed to import data from TDMDownloads
//
// =========================================================================================
// This function imports data from WF-Downloads
// =========================================================================================
/**
 * @return null
 */
function import_wfd_to_wfdownloads()
{
    $module_handler = xoops_gethandler('module');
    // Get source module/config
    $wfdDirname = 'wf' . 'downloads'; // don't modify, is for cloning
    $wfdModule  = $module_handler->getByDirname($wfdDirname);
    if (empty($wfdModuleConfig)) {
        $config_handler  = xoops_gethandler('config');
        $wfdModuleConfig = $config_handler->getConfigsByCat(0, $wfdModule->mid());
    }
    $wfdCategoriesHandler = xoops_getModuleHandler('category', $wfdDirname);
    $wfdDownloadsHandler  = xoops_getModuleHandler('download', $wfdDirname);

    // Get destination module/handlers/configs
    $wfdownloads = WfdownloadsWfdownloads::getInstance();

    echo "<br /><span style='font-weight: bold;'>Copying Files</span><br />";

    // Copy categories images/thumbnails
    if (!wfdownloads_copyDir(XOOPS_ROOT_PATH . '/' . $wfdModuleConfig['catimage'], XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('catimage'))) {
        return false;
    }
    echo "Copied categories images and thumbnails<br />";

    // Copy screenshots images/thumbnails
    if (!wfdownloads_copyDir(
        XOOPS_ROOT_PATH . '/' . $wfdModuleConfig['screenshots'],
        XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('screenshots')
    )
    ) {
        return false;
    }
    echo "Copied downloads screenshots and thumbnails<br />";

    // Copy files
    $wfdDownloadObjs = $wfdDownloadsHandler->getObjects();
    $countCopied     = 0;
    $countNotCopied  = 0;
    foreach ($wfdDownloadObjs as $wfdDownloadObj) {
        if (wfdownloads_copyFile($wfdModuleConfig['uploaddir'] . "/" . $wfdDownloadObj->getVar('filename'), $wfdownloads->getConfig('uploaddir') . "/" . $wfdDownloadObj->getVar('filename'))) {
            echo "<span style='color:green;'>" . $wfdModuleConfig['uploaddir'] . "/" . $wfdDownloadObj->getVar('filename') . " copied</span><br />";
            ++$countCopied;
        } else {
            echo "<span style='color:red;'>ERROR copying:" . $wfdModuleConfig['uploaddir'] . "/" . $wfdDownloadObj->getVar('filename') . "</span><br />";
            ++$countNotCopied;
        }
    }
    echo "Files copied: " . $countCopied;
    echo "<br />";
    echo "Files not copied: " . $countNotCopied;
    echo "<br />";
    echo _AM_WFDOWNLOADS_IMPORT_IMPORTINGDATA;
    echo "<br />";

    $destination = array(
        "cat"       => $GLOBALS['xoopsDB']->prefix("wfdownloads_cat"),
        "downloads" => $GLOBALS['xoopsDB']->prefix("wfdownloads_downloads"),
        "mirrors"   => $GLOBALS['xoopsDB']->prefix("wfdownloads_mirrors"),
        "reviews"   => $GLOBALS['xoopsDB']->prefix("wfdownloads_reviews"),
        "broken"    => $GLOBALS['xoopsDB']->prefix("wfdownloads_broken"),
        "mod"       => $GLOBALS['xoopsDB']->prefix("wfdownloads_mod"),
        "votes"     => $GLOBALS['xoopsDB']->prefix("wfdownloads_votedata")
    );

    $source = array(
        "cat"       => $GLOBALS['xoopsDB']->prefix("wf" . "downloads_cat"), // don't modify, is for cloning
        "downloads" => $GLOBALS['xoopsDB']->prefix("wf" . "downloads_downloads"), // don't modify, is for cloning
        "mirrors"   => $GLOBALS['xoopsDB']->prefix("wf" . "downloads_mirrors"), // don't modify, is for cloning
        "reviews"   => $GLOBALS['xoopsDB']->prefix("wf" . "downloads_reviews"), // don't modify, is for cloning
        "broken"    => $GLOBALS['xoopsDB']->prefix("wf" . "downloads_broken"), // don't modify, is for cloning
        "mod"       => $GLOBALS['xoopsDB']->prefix("wf" . "downloads_mod"), // don't modify, is for cloning
        "votes"     => $GLOBALS['xoopsDB']->prefix("wf" . "downloads_votedata")
    ); // don't modify, is for cloning

    //Add temporary field to category table
    $GLOBALS['xoopsDB']->query("ALTER TABLE {$destination['cat']} ADD `old_cid` int NOT NULL default 0");
    $GLOBALS['xoopsDB']->query("ALTER TABLE {$destination['cat']} ADD `old_pid` int NOT NULL default 0");
    //Add temporary fields to downloads table
    $GLOBALS['xoopsDB']->query("ALTER TABLE {$destination['downloads']} ADD `old_lid` int NOT NULL default 0");
    $GLOBALS['xoopsDB']->query("ALTER TABLE {$destination['downloads']} ADD `old_cid` int NOT NULL default 0");

    //Get latest mirror ID to determine which records will need an updated lid value afterwards
    $result = $GLOBALS['xoopsDB']->query("SELECT MAX(mirror_id) FROM {$destination['mirrors']}");
    list($max_mirrorid) = $GLOBALS['xoopsDB']->fetchRow($result);
    //Get latest review ID to determine which records will need an updated lid value afterwards
    $result = $GLOBALS['xoopsDB']->query("SELECT MAX(review_id) FROM {$destination['reviews']}");
    list($max_reviewid) = $GLOBALS['xoopsDB']->fetchRow($result);
    //Get latest mod request ID to determine which records will need an updated lid value afterwards
    $result = $GLOBALS['xoopsDB']->query("SELECT MAX(requestid) FROM {$destination['mod']}");
    list($max_requestid) = $GLOBALS['xoopsDB']->fetchRow($result);
    //Get latest report ID to determine, which records will need an updated lid value afterwards
    $result = $GLOBALS['xoopsDB']->query("SELECT MAX(reportid) FROM {$destination['broken']}");
    list($max_reportid) = $GLOBALS['xoopsDB']->fetchRow($result);
    //Get latest vote ID to determine which records will need an updated lid value afterwards
    $result = $GLOBALS['xoopsDB']->query("SELECT MAX(ratingid) FROM {$destination['votes']}");
    list($max_ratingid) = $GLOBALS['xoopsDB']->fetchRow($result);

    //Import data into category table
    if (wfdownloads_checkModule('wf' . 'downloads') >= 320) {
        $sql = "INSERT INTO {$destination['cat']} (";
        $sql .= " `old_cid`, `old_pid`, `title`, `imgurl`, `description`, `total`, `summary`, `spotlighttop`, `spotlighthis`, `dohtml`, `dosmiley`, `doxcode`, `doimage`, `dobr`, `weight`, `formulize_fid`";
        $sql .= " ) SELECT ";
        $sql .= " `cid`,     `pid`,     `title`, `imgurl`, `description`, `total`, `summary`, `spotlighttop`, `spotlighthis`, `dohtml`, `dosmiley`, `doxcode`, `doimage`, `dobr`, `weight`, `formulize_fid`";
        $sql .= " FROM {$source['cat']}";
    } else {
        $sql = "INSERT INTO {$destination['cat']} (";
        $sql .= " `old_cid`, `old_pid`, `title`, `imgurl`, `description`, `total`, `summary`, `spotlighttop`, `spotlighthis`, `dohtml`, `dosmiley`, `doxcode`, `doimage`, `dobr`, `weight`";
        $sql .= " ) SELECT ";
        $sql .= " `cid`,     `pid`,     `title`, `imgurl`, `description`, `total`, `summary`, `spotlighttop`, `spotlighthis`, `dohtml`, `dosmiley`, `doxcode`, `doimage`, `dobr`, `weight`";
        $sql .= " FROM {$source['cat']}";
    }
    $GLOBALS['xoopsDB']->query($sql);
    echo "Imported {$GLOBALS['xoopsDB']->getAffectedRows()} categories into {$destination['cat']}<br />";

    //Import data into downloads table
    if (wfdownloads_checkModule('wf' . 'downloads') >= 320) {
        $sql = "INSERT INTO {$destination['downloads']} (";
        $sql .= " `cid`, `old_lid`, `old_cid`, `title`, `url`, `filename`, `filetype`, `homepage`, `version`, `size`, `platform`, `screenshot`, `screenshot2`, `screenshot3`, `screenshot4`, `submitter`, `publisher`, `status`, `date`, `hits`, `rating`, `votes`, `comments`, `license`, `mirror`, `price`, `paypalemail`, `features`, `requirements`, `homepagetitle`, `forumid`, `limitations`, `versiontypes`, `dhistory`, `published`, `expired`, `updated`, `offline`, `summary`, `description`, `ipaddress`, `notifypub`, `formulize_idreq`";
        $sql .= " ) SELECT ";
        $sql .= " 0,     `lid`,     `cid`,     `title`, `url`, `filename`, `filetype`, `homepage`, `version`, `size`, `platform`, `screenshot`, `screenshot2`, `screenshot3`, `screenshot4`, `submitter`, `publisher`, `status`, `date`, `hits`, `rating`, `votes`, `comments`, `license`, `mirror`, `price`, `paypalemail`, `features`, `requirements`, `homepagetitle`, `forumid`, `limitations`, `versiontypes`, `dhistory`, `published`, `expired`, `updated`, `offline`, `summary`, `description`, `ipaddress`, `notifypub`, `formulize_idreq`";
        $sql .= " FROM {$source['downloads']}";
    } else {
        $sql = "INSERT INTO {$destination['downloads']} (";
        $sql .= " `cid`, `old_lid`, `old_cid`, `title`, `url`, `filename`, `filetype`, `homepage`, `version`, `size`, `platform`, `screenshot`, `submitter`, `publisher`, `status`, `date`, `hits`, `rating`, `votes`, `comments`, `license`, `mirror`, `price`, `paypalemail`, `features`, `requirements`, `homepagetitle`, `forumid`, `limitations`, `dhistory`, `published`, `expired`, `updated`, `offline`, `summary`, `description`, `ipaddress`, `notifypub`";
        $sql .= " ) SELECT ";
        $sql .= " 0,     `lid`,     `cid`,     `title`, `url`, `filename`, `filetype`, `homepage`, `version`, `size`, `platform`, `screenshot`, `submitter`, `publisher`, `status`, `date`, `hits`, `rating`, `votes`, `comments`, `license`, `mirror`, `price`, `paypalemail`, `features`, `requirements`, `homepagetitle`, `forumid`, `limitations`, `dhistory`, `published`, `expired`, `updated`, `offline`, `summary`, `description`, `ipaddress`, `notifypub`";
        $sql .= " FROM {$source['downloads']}";
    }
    $GLOBALS['xoopsDB']->query($sql);
    echo "Imported {$GLOBALS['xoopsDB']->getAffectedRows()} downloads into {$destination['downloads']}<br />";
// IN PROGRESS
// IN PROGRESS IMPORT AND UPDATE SCREENSHOTS
// IN PROGRESS


    //Import data into mirrors table
    $sql = "INSERT INTO {$destination['mirrors']} (";
    $sql .= " `lid`, `title`, `homeurl`, `location`, `continent`, `downurl`, `submit`, `date`, `uid`";
    $sql .= " ) SELECT";
    $sql .= " `lid`, `title`, `homeurl`, `location`, `continent`, `downurl`, `submit`, `date`, `uid`";
    $sql .= " FROM {$source['mirrors']}";
    $GLOBALS['xoopsDB']->query($sql);
    echo "Imported {$GLOBALS['xoopsDB']->getAffectedRows()} mirrors into {$destination['mirrors']}<br />";

    //Import data into reviews table
    if (wfdownloads_checkModule('wf' . 'downloads') >= 320) {
        $sql = "INSERT INTO {$destination['reviews']} (";
        $sql .= " `lid`, `title`, `review`, `submit`, `date`, `uid`, `rate`";
        $sql .= " ) SELECT";
        $sql .= " `lid`, `title`, `review`, `submit`, `date`, `uid`, `rate`";
        $sql .= " FROM {$source['reviews']}";
    } else {
        $sql = "INSERT INTO {$destination['reviews']} (";
        $sql .= " `lid`, `title`, `review`, `submit`, `date`, `uid`";
        $sql .= " ) SELECT";
        $sql .= " `lid`, `title`, `review`, `submit`, `date`, `uid`";
        $sql .= " FROM {$source['reviews']}";
    }
    $GLOBALS['xoopsDB']->query($sql);
    echo "Imported {$GLOBALS['xoopsDB']->getAffectedRows()} reviews into {$destination['reviews']}<br />";

    //Import data into brokens table
    $sql = "INSERT INTO {$destination['broken']} (";
    $sql .= " `lid`, `sender`, `date`, `ip`";
    $sql .= " ) SELECT";
    $sql .= " `lid`, `sender`, `date`, `ip`";
    $sql .= " FROM {$source['broken']}";
    $GLOBALS['xoopsDB']->query($sql);
    echo "Imported {$GLOBALS['xoopsDB']->getAffectedRows()} broken reports into {$destination['broken']}<br />";

    //Import data into votedata table
    $sql = "INSERT INTO {$destination['votes']} (";
    $sql .= " `lid`, `ratinguser`, `rating`, `ratinghostname`, `ratingtimestamp`";
    $sql .= " ) SELECT";
    $sql .= " `lid`, `ratinguser`, `rating`, `ratinghostname`, `ratingtimestamp`";
    $sql .= " FROM {$source['votes']}";
    $GLOBALS['xoopsDB']->query($sql);
    echo "Imported {$GLOBALS['xoopsDB']->getAffectedRows()} votes into {$destination['votes']}<br />";

    //Import data into mod request table
    $sql = "INSERT INTO {$destination['mod']} (";
    $sql .= " `lid`, `cid`, `title`, `url`, `homepage`, `version`, `size`, `platform`, `screenshot`, `description`, `modifysubmitter`, `features`, `requirements`, `publisher`, `dhistory`, `summary`)";
    $sql .= " SELECT";
    $sql .= " `lid`, `cid`, `title`, `url`, `homepage`, `version`, `size`, `platform`, `screenshot`, `description`, `modifysubmitter`, `features`, `requirements`, `publisher`, `dhistory`, `summary`";
    $sql .= " FROM {$source['mod']}";
    $GLOBALS['xoopsDB']->query($sql);
    echo "Imported {$GLOBALS['xoopsDB']->getAffectedRows()} modification requests into {$destination['mod']}<br />";

    // Update category ID to new value
    $GLOBALS['xoopsDB']->query("UPDATE {$destination['downloads']} d, {$destination['cat']} c SET d.cid=c.cid WHERE d.old_cid=c.old_cid AND d.old_cid != 0");
    $GLOBALS['xoopsDB']->query("UPDATE {$destination['cat']} c1, {$destination['cat']} c2 SET c1.pid=c2.cid WHERE c1.old_pid=c2.old_cid AND c1.old_pid != 0");
    // Update lid values in mod table
    if ($max_requestid) {
        $GLOBALS['xoopsDB']->query(
            "UPDATE {$destination['mod']} m, {$destination['cat']} c SET m.cid=c.cid WHERE m.requestid > {$max_requestid} AND c.old_cid=m.cid"
        );
        $GLOBALS['xoopsDB']->query(
            "UPDATE {$destination['mod']} m, {$destination['downloads']} d SET m.lid=d.lid WHERE m.requestid > {$max_requestid} AND m.lid=d.old_lid"
        );
    }
    // Update lid values in mirrors table
    if ($max_mirrorid) {
        $GLOBALS['xoopsDB']->query(
            "UPDATE {$destination['mirrors']} v, {$destination['downloads']} d SET v.lid=d.lid WHERE v.mirror_id > {$max_mirrorid} AND v.lid=d.old_lid"
        );
    }
    // Update lid values in reviews table
    if ($max_reviewid) {
        $GLOBALS['xoopsDB']->query(
            "UPDATE {$destination['reviews']} v, {$destination['downloads']} d SET v.lid=d.lid WHERE v.review_id > {$max_reviewid} AND v.lid=d.old_lid"
        );
    }
    // Update lid values in votedata table
    if ($max_ratingid) {
        $GLOBALS['xoopsDB']->query(
            "UPDATE {$destination['votes']} v, {$destination['downloads']} d SET v.lid=d.lid WHERE v.ratingid > {$max_ratingid} AND v.lid=d.old_lid"
        );
    }
    // Update lid values in broken table
    if ($max_reportid) {
        $GLOBALS['xoopsDB']->query(
            "UPDATE {$destination['broken']} b, {$destination['downloads']} d SET b.lid=d.lid WHERE b.reportid > {$max_reportid} AND b.lid=d.old_lid"
        );
    }

    //Remove temporary fields
    $GLOBALS['xoopsDB']->query("ALTER TABLE " . $destination['cat'] . " DROP `old_cid`");
    $GLOBALS['xoopsDB']->query("ALTER TABLE " . $destination['cat'] . " DROP `old_pid`");
    $GLOBALS['xoopsDB']->query("ALTER TABLE " . $destination['downloads'] . " DROP `old_cid`");
    $GLOBALS['xoopsDB']->query("ALTER TABLE " . $destination['downloads'] . " DROP `old_lid`");

    return null;
}

// =========================================================================================
// This function imports data from mydownloads
// =========================================================================================
function import_wmpdownloads_to_wfdownloads()
{
    echo "<br />";
    echo _AM_WFDOWNLOADS_IMPORT_IMPORTINGDATA;
    echo "<br />";

    $destination = array(
        "cat"       => $GLOBALS['xoopsDB']->prefix("wfdownloads_cat"),
        "downloads" => $GLOBALS['xoopsDB']->prefix("wfdownloads_downloads"),
        "broken"    => $GLOBALS['xoopsDB']->prefix("wfdownloads_broken"),
        "mod"       => $GLOBALS['xoopsDB']->prefix("wfdownloads_mod"),
        "votes"     => $GLOBALS['xoopsDB']->prefix("wfdownloads_votedata")
    );

    $source = array(
        "cat"       => $GLOBALS['xoopsDB']->prefix("wmpdownloads_cat"),
        "downloads" => $GLOBALS['xoopsDB']->prefix("wmpdownloads_downloads"),
        "broken"    => $GLOBALS['xoopsDB']->prefix("wmpdownloads_broken"),
        "mod"       => $GLOBALS['xoopsDB']->prefix("wmpdownloads_mod"),
        "votes"     => $GLOBALS['xoopsDB']->prefix("wmpdownloads_votedata"),
        "text"      => $GLOBALS['xoopsDB']->prefix("wmpdownloads_text")
    );

    //Add temporary field to category table
    $GLOBALS['xoopsDB']->query("ALTER TABLE " . $destination['cat'] . " ADD `old_cid` int NOT NULL default 0");
    $GLOBALS['xoopsDB']->query("ALTER TABLE " . $destination['cat'] . " ADD `old_pid` int NOT NULL default 0");

    //Add temporary fields to downloads table
    $GLOBALS['xoopsDB']->query(
        "ALTER TABLE " . $destination['downloads'] . " ADD `old_lid` int NOT NULL default 0,
                                                               ADD `old_cid` int NOT NULL default 0"
    );

    //Get latest mod request ID to determine which records will need an updated lid value afterwards
    $result = $GLOBALS['xoopsDB']->query("SELECT MAX(requestid) FROM " . $destination['mod']);
    list($max_requestid) = $GLOBALS['xoopsDB']->fetchRow($result);
    //Get latest report ID to determine, which records will need an updated lid value afterwards
    $result = $GLOBALS['xoopsDB']->query("SELECT MAX(reportid) FROM " . $destination['broken']);
    list($max_reportid) = $GLOBALS['xoopsDB']->fetchRow($result);
    //Get latest vote ID to determine which records will need an updated lid value afterwards
    $result = $GLOBALS['xoopsDB']->query("SELECT MAX(ratingid) FROM " . $destination['votes']);
    list($max_ratingid) = $GLOBALS['xoopsDB']->fetchRow($result);

    //Import data into category table
    $GLOBALS['xoopsDB']->query(
        "INSERT" . " INTO " . $destination['cat'] . " (`old_cid`, `old_pid`, `title`, `imgurl`, `summary`)" . " SELECT `cid`, `pid`, `title`, `imgurl`, ''" . " FROM " . $source['cat']
    );
    echo "Imported " . $GLOBALS['xoopsDB']->getAffectedRows() . " categories into " . $destination['cat'] . "<br />";
    //Import data into downloads table
    $GLOBALS['xoopsDB']->query(
        "INSERT" . " INTO " . $destination['downloads']
        . " (`cid`, `old_lid`, `old_cid`, `title`, `url`, `homepage`, `version`, `size`, `platform`, `screenshot`, `submitter`, `status`, `published`, `hits`, `rating`, `votes`, `comments`, `features`, `requirements`, `dhistory`, `summary`, `description`)"
        . " SELECT 0,`lid`, `cid`, `title`, `url`, `homepage`, `version`, `size`, `platform`, `logourl`, `submitter`, `status`, `date`, `hits`, `rating`, `votes`, `comments`, '', '','','', ''"
        . " FROM " . $source['downloads']
    );
    echo "Imported " . $GLOBALS['xoopsDB']->getAffectedRows() . " downloads into " . $destination['downloads'] . "<br />";
    //Import data into brokens table
    $GLOBALS['xoopsDB']->query(
        "INSERT" . " INTO " . $destination['broken'] . " (`lid`, `sender`, `ip`)" . " SELECT `lid`, `sender`, `ip`" . " FROM " . $source['broken']
    );
    echo "Imported " . $GLOBALS['xoopsDB']->getAffectedRows() . " broken reports into " . $destination['broken'] . "<br />";
    //Import data into votedata table
    $GLOBALS['xoopsDB']->query(
        "INSERT" . " INTO " . $destination['votes'] . " (`lid`, `ratinguser`, `rating`, `ratinghostname`, `ratingtimestamp`)"
        . "SELECT `lid`, `ratinguser`, `rating`, `ratinghostname`, `ratingtimestamp`" . " FROM " . $source['votes']
    );
    echo "Imported " . $GLOBALS['xoopsDB']->getAffectedRows() . " votes into " . $destination['votes'] . "<br />";
    //Import data into mod request table
    $GLOBALS['xoopsDB']->query(
        "INSERT INTO " . $destination['mod']
        . " (`lid`, `cid`, `title`, `url`, `homepage`, `version`, `size`, `platform`, `screenshot`, `description`, `modifysubmitter`,`features`, `requirements`, `publisher`, `dhistory`, `summary`)"
        . " SELECT `lid`, `cid`, `title`, `url`, `homepage`, `version`, `size`, `platform`, `logourl`, `description`, `modifysubmitter`,'','','','',''" . " FROM " . $source['mod']
    );
    echo "Imported " . $GLOBALS['xoopsDB']->getAffectedRows() . " modification requests into " . $destination['mod'] . "<br />";

    //Update category ID to new value
    $GLOBALS['xoopsDB']->query(
        "UPDATE " . $destination['downloads'] . " d, " . $destination['cat'] . " c SET d.cid=c.cid" . " WHERE d.old_cid=c.old_cid AND d.old_cid != 0"
    );
    $GLOBALS['xoopsDB']->query(
        "UPDATE " . $destination['cat'] . " c1, " . $destination['cat'] . " c2 SET c1.pid=c2.cid" . " WHERE c1.old_pid=c2.old_cid AND c1.old_pid != 0"
    );
    if ($max_requestid) {
        $GLOBALS['xoopsDB']->query(
            "UPDATE " . $destination['mod'] . " m, " . $destination['cat'] . " c SET m.cid=c.cid" . " WHERE m.requestid > " . $max_requestid . " AND c.old_cid=m.cid"
        );
        //Update lid values in mod table
        $GLOBALS['xoopsDB']->query(
            "UPDATE " . $destination['mod'] . " m, " . $destination['downloads'] . " d SET m.lid=d.lid" . " WHERE m.requestid > " . $max_requestid . " AND m.lid=d.old_lid"
        );
    }
    if ($max_ratingid) {
        //Update lid values in votedata table
        $GLOBALS['xoopsDB']->query(
            "UPDATE " . $destination['votes'] . " v, " . $destination['downloads'] . " d SET v.lid=d.lid" . " WHERE v.ratingid > " . $max_ratingid . " AND v.lid=d.old_lid"
        );
    }
    if ($max_reportid) {
        //Update lid values in brokens table
        $GLOBALS['xoopsDB']->query(
            "UPDATE " . $destination['broken'] . " b, " . $destination['downloads'] . " d SET b.lid=d.lid" . " WHERE b.reportid > " . $max_reportid . " AND b.lid=d.old_lid"
        );
    }
    //Update description
    $GLOBALS['xoopsDB']->query(
        "UPDATE " . $destination['downloads'] . " d, " . $source['text'] . " t SET d.description=t.description" . " WHERE t.lid=d.old_lid"
    );

    //Remove temporary fields
    $GLOBALS['xoopsDB']->query("ALTER TABLE ." . $destination['cat'] . " DROP `old_cid`");
    $GLOBALS['xoopsDB']->query("ALTER TABLE ." . $destination['cat'] . " DROP `old_pid`");
    $GLOBALS['xoopsDB']->query("ALTER TABLE ." . $destination['downloads'] . " DROP `old_cid`");
    $GLOBALS['xoopsDB']->query("ALTER TABLE ." . $destination['downloads'] . " DROP `old_lid`");

}

// =========================================================================================
// This function imports data from pd-downloads
// =========================================================================================
function import_pddownloads_to_wfdownloads()
{
    echo "<br />";
    echo _AM_WFDOWNLOADS_IMPORT_IMPORTINGDATA;
    echo "<br />";

    $destination = array(
        "cat"       => $GLOBALS['xoopsDB']->prefix("wfdownloads_cat"),
        "downloads" => $GLOBALS['xoopsDB']->prefix("wfdownloads_downloads"),
        "broken"    => $GLOBALS['xoopsDB']->prefix("wfdownloads_broken"),
        "mod"       => $GLOBALS['xoopsDB']->prefix("wfdownloads_mod"),
        "votes"     => $GLOBALS['xoopsDB']->prefix("wfdownloads_votedata")
    );

    $source = array(
        "cat"       => $GLOBALS['xoopsDB']->prefix("PDdownloads_cat"),
        "downloads" => $GLOBALS['xoopsDB']->prefix("PDdownloads_downloads"),
        "broken"    => $GLOBALS['xoopsDB']->prefix("PDdownloads_broken"),
        "mod"       => $GLOBALS['xoopsDB']->prefix("PDdownloads_mod"),
        "votes"     => $GLOBALS['xoopsDB']->prefix("PDdownloads_votedata")
    );

    //Add temporary field to category table
    $GLOBALS['xoopsDB']->query("ALTER TABLE " . $destination['cat'] . " ADD `old_cid` int NOT NULL default 0");
    $GLOBALS['xoopsDB']->query("ALTER TABLE " . $destination['cat'] . " ADD `old_pid` int NOT NULL default 0");

    //Add temporary fields to downloads table
    $GLOBALS['xoopsDB']->query(
        "ALTER TABLE " . $destination['downloads'] . " ADD `old_lid` int NOT NULL default 0,
                                                               ADD `old_cid` int NOT NULL default 0"
    );

    //Get latest mod request ID to determine which records will need an updated lid value afterwards
    $result = $GLOBALS['xoopsDB']->query("SELECT MAX(requestid) FROM " . $destination['mod']);
    list($max_requestid) = $GLOBALS['xoopsDB']->fetchRow($result);
    //Get latest report ID to determine, which records will need an updated lid value afterwards
    $result = $GLOBALS['xoopsDB']->query("SELECT MAX(reportid) FROM " . $destination['broken']);
    list($max_reportid) = $GLOBALS['xoopsDB']->fetchRow($result);
    //Get latest vote ID to determine which records will need an updated lid value afterwards
    $result = $GLOBALS['xoopsDB']->query("SELECT MAX(ratingid) FROM " . $destination['votes']);
    list($max_ratingid) = $GLOBALS['xoopsDB']->fetchRow($result);

    //Import data into category table
    $GLOBALS['xoopsDB']->query(
        "INSERT INTO " . $destination['cat'] . " (`old_cid`, `old_pid`, `title`, `imgurl`, `description`, `total`, `weight`)"
        . " SELECT `cid`, `pid`, `title`, `imgurl`, `description`, `total`, `weight`" . " FROM " . $source['cat']
    );
    echo "Imported " . $GLOBALS['xoopsDB']->getAffectedRows() . " categories into " . $destination['cat'] . "<br />";
    //Import data into downloads table
    $GLOBALS['xoopsDB']->query(
        "INSERT INTO " . $destination['downloads']
        . " (`cid`, `old_lid`, `old_cid`, `title`, `url`, `homepage`, `homepagetitle`, `version`, `size`, `platform`, `screenshot`, `submitter`, `publisher`, `status`, `date`, `hits`, `rating`, `votes`, `comments`, `features`, `forumid`, `dhistory`, `published`, `expired`, `updated`, `offline`, `description`, `ipaddress`, `notifypub`)"
        . " SELECT 0,`lid`, `cid`, `title`, `url`, `homepage`, `homepagetitle`, `version`, `size`, `platform`, `screenshot`, `submitter`, `publisher`, `status`, `date`, `hits`, `rating`, `votes`, `comments`, `features`, `forumid`, `dhistory`, `published`, `expired`, `updated`, `offline`, `description`, `ipaddress`, `notifypub`"
        . " FROM " . $source['downloads']
    );
    echo "Imported " . $GLOBALS['xoopsDB']->getAffectedRows() . " downloads into " . $destination['downloads'] . "<br />";
    //Import data into brokens table
    $GLOBALS['xoopsDB']->query(
        "INSERT INTO " . $destination['broken'] . " (`reportid`, `lid`, `sender`, `ip`, `date`, `confirmed`, `acknowledged`)"
        . " SELECT `reportid`, `lid`, `sender`, `ip`, `date`, `confirmed`, `acknowledged`" . " FROM " . $source['broken']
    );
    echo "Imported " . $GLOBALS['xoopsDB']->getAffectedRows() . " broken reports into " . $destination['broken'] . "<br />";
    //Import data into votedata table
    $GLOBALS['xoopsDB']->query(
        "INSERT INTO " . $destination['votes'] . " (`ratingid`, `lid`, `ratinguser`, `rating`, `ratinghostname`, `ratingtimestamp`)"
        . " SELECT `ratingid`, `lid`, `ratinguser`, `rating`, `ratinghostname`, `ratingtimestamp`" . " FROM " . $source['votes']
    );
    echo "Imported " . $GLOBALS['xoopsDB']->getAffectedRows() . " votes into " . $destination['votes'] . "<br />";
    //Import data into mod request table
    $GLOBALS['xoopsDB']->query(
        "INSERT INTO " . $destination['mod']
        . " (`lid`, `cid`, `title`, `url`, `homepage`, `homepagetitle`, `version`, `size`, `platform`, `screenshot`, `submitter`, `publisher`, `status`, `date`, `hits`, `rating`, `votes`, `comments`, `features`, `forumid`, `dhistory`, `published`, `expired`, `updated`, `offline`, `description`, `modifysubmitter`, `requestdate`)"
        . " SELECT `lid`, `cid`, `title`, `url`, `homepage`, `homepagetitle`, `version`, `size`, `platform`, `screenshot`, `submitter`, `publisher`, `status`, `date`, `hits`, `rating`, `votes`, `comments`, `features`, `forumid`, `dhistory`, `published`, `expired`, `updated`, `offline`, `description`, `modifysubmitter`, `requestdate`"
        . " FROM " . $source['mod']
    );
    echo "Imported " . $GLOBALS['xoopsDB']->getAffectedRows() . " modification requests into " . $destination['mod'] . "<br />";

    //Update category ID to new value
    $GLOBALS['xoopsDB']->query(
        "UPDATE " . $destination['downloads'] . " d, " . $destination['cat'] . " c SET d.cid=c.cid" . " WHERE d.old_cid=c.old_cid AND d.old_cid != 0"
    );
    $GLOBALS['xoopsDB']->query(
        "UPDATE " . $destination['cat'] . " c1, " . $destination['cat'] . " c2 SET c1.pid=c2.cid" . " WHERE c1.old_pid=c2.old_cid AND c1.old_pid != 0"
    );
    if ($max_requestid) {
        $GLOBALS['xoopsDB']->query(
            "UPDATE " . $destination['mod'] . " m, " . $destination['cat'] . " c SET m.cid=c.cid" . " WHERE m.requestid > " . $max_requestid . " AND c.old_cid=m.cid"
        );
        //Update lid values in mod table
        $GLOBALS['xoopsDB']->query(
            "UPDATE " . $destination['mod'] . " m, " . $destination['downloads'] . " d SET m.lid=d.lid" . " WHERE m.requestid > " . $max_requestid . " AND m.lid=d.old_lid"
        );
    }
    if ($max_ratingid) {
        //Update lid values in votedata table
        $GLOBALS['xoopsDB']->query(
            "UPDATE " . $destination['votes'] . " v, " . $destination['downloads'] . " d SET v.lid=d.lid" . " WHERE v.ratingid > " . $max_ratingid . " AND v.lid=d.old_lid"
        );
    }
    if ($max_reportid) {
        //Update lid values in brokens table
        $GLOBALS['xoopsDB']->query(
            "UPDATE " . $destination['broken'] . " b, " . $destination['downloads'] . " d SET b.lid=d.lid" . " WHERE b.reportid > " . $max_reportid . " AND b.lid=d.old_lid"
        );
    }

    //Remove temporary fields
    $GLOBALS['xoopsDB']->query("ALTER TABLE ." . $destination['cat'] . " DROP `old_cid`");
    $GLOBALS['xoopsDB']->query("ALTER TABLE ." . $destination['cat'] . " DROP `old_pid`");
    $GLOBALS['xoopsDB']->query("ALTER TABLE ." . $destination['downloads'] . " DROP `old_cid`");
    $GLOBALS['xoopsDB']->query("ALTER TABLE ." . $destination['downloads'] . " DROP `old_lid`");

}

// =========================================================================================
// This function imports data from mydownloads
// =========================================================================================
function import_mydownloads_to_wfdownloads()
{
    echo "<br />";
    echo _AM_WFDOWNLOADS_IMPORT_IMPORTINGDATA;
    echo "<br />";

    $destination = array(
        "cat"       => $GLOBALS['xoopsDB']->prefix("wfdownloads_cat"),
        "downloads" => $GLOBALS['xoopsDB']->prefix("wfdownloads_downloads"),
        "broken"    => $GLOBALS['xoopsDB']->prefix("wfdownloads_broken"),
        "mod"       => $GLOBALS['xoopsDB']->prefix("wfdownloads_mod"),
        "votes"     => $GLOBALS['xoopsDB']->prefix("wfdownloads_votedata")
    );

    $source = array(
        "cat"       => $GLOBALS['xoopsDB']->prefix("mydownloads_cat"),
        "downloads" => $GLOBALS['xoopsDB']->prefix("mydownloads_downloads"),
        "broken"    => $GLOBALS['xoopsDB']->prefix("mydownloads_broken"),
        "mod"       => $GLOBALS['xoopsDB']->prefix("mydownloads_mod"),
        "votes"     => $GLOBALS['xoopsDB']->prefix("mydownloads_votedata"),
        "text"      => $GLOBALS['xoopsDB']->prefix("mydownloads_text")
    );

    //Add temporary field to category table
    $GLOBALS['xoopsDB']->query("ALTER TABLE " . $destination['cat'] . " ADD `old_cid` int NOT NULL default 0");
    $GLOBALS['xoopsDB']->query("ALTER TABLE " . $destination['cat'] . " ADD `old_pid` int NOT NULL default 0");

    //Add temporary fields to downloads table
    $GLOBALS['xoopsDB']->query(
        "ALTER TABLE " . $destination['downloads'] . " ADD `old_lid` int NOT NULL default 0,
                                                               ADD `old_cid` int NOT NULL default 0"
    );

    //Get latest mod request ID to determine which records will need an updated lid value afterwards
    $result = $GLOBALS['xoopsDB']->query("SELECT MAX(requestid) FROM " . $destination['mod']);
    list($max_requestid) = $GLOBALS['xoopsDB']->fetchRow($result);
    //Get latest report ID to determine, which records will need an updated lid value afterwards
    $result = $GLOBALS['xoopsDB']->query("SELECT MAX(reportid) FROM " . $destination['broken']);
    list($max_reportid) = $GLOBALS['xoopsDB']->fetchRow($result);
    //Get latest vote ID to determine which records will need an updated lid value afterwards
    $result = $GLOBALS['xoopsDB']->query("SELECT MAX(ratingid) FROM " . $destination['votes']);
    list($max_ratingid) = $GLOBALS['xoopsDB']->fetchRow($result);

    //Import data into category table
    $GLOBALS['xoopsDB']->query(
        "INSERT INTO " . $destination['cat'] . " (`old_cid`, `old_pid`, `title`, `imgurl`, `summary`)" . " SELECT `cid`, `pid`, `title`, `imgurl`, ''" . " FROM " . $source['cat']
    );
    echo "Imported " . $GLOBALS['xoopsDB']->getAffectedRows() . " categories into " . $destination['cat'] . "<br />";
    //Import data into downloads table
    $GLOBALS['xoopsDB']->query(
        "INSERT INTO " . $destination['downloads']
        . " (`cid`, `old_lid`, `old_cid`, `title`, `url`, `homepage`, `version`, `size`, `platform`, `screenshot`, `submitter`, `status`, `published`, `hits`, `rating`, `votes`, `comments`, `features`, `requirements`, `dhistory`, `summary`, `description`)"
        . " SELECT 0,`lid`, `cid`, `title`, `url`, `homepage`, `version`, `size`, `platform`, `logourl`, `submitter`, `status`, `date`, `hits`, `rating`, `votes`, `comments`, '', '','','', ''"
        . " FROM " . $source['downloads']
    );
    echo "Imported " . $GLOBALS['xoopsDB']->getAffectedRows() . " downloads into " . $destination['downloads'] . "<br />";
    //Import data into brokens table
    $GLOBALS['xoopsDB']->query(
        "INSERT INTO " . $destination['broken'] . " (`lid`, `sender`, `ip`)" . " SELECT `lid`, `sender`, `ip`" . " FROM " . $source['broken']
    );
    echo "Imported " . $GLOBALS['xoopsDB']->getAffectedRows() . " broken reports into " . $destination['broken'] . "<br />";
    //Import data into votedata table
    $GLOBALS['xoopsDB']->query(
        "INSERT INTO " . $destination['votes'] . " (`lid`, `ratinguser`, `rating`, `ratinghostname`, `ratingtimestamp`)" . " SELECT `lid`, `ratinguser`, `rating`, `ratinghostname`, `ratingtimestamp`"
        . " FROM " . $source['votes']
    );
    echo "Imported " . $GLOBALS['xoopsDB']->getAffectedRows() . " votes into " . $destination['votes'] . "<br />";
    //Import data into mod request table
    $GLOBALS['xoopsDB']->query(
        "INSERT INTO " . $destination['mod']
        . " (`lid`, `cid`, `title`, `url`, `homepage`, `version`, `size`, `platform`, `screenshot`, `description`, `modifysubmitter`,`features`, `requirements`, `publisher`, `dhistory`, `summary`)"
        . " SELECT `lid`, `cid`, `title`, `url`, `homepage`, `version`, `size`, `platform`, `logourl`, `description`, `modifysubmitter`,'','','','',''" . " FROM " . $source['mod']
    );
    echo "Imported " . $GLOBALS['xoopsDB']->getAffectedRows() . " modification requests into " . $destination['mod'] . "<br />";

    //Update category ID to new value
    $GLOBALS['xoopsDB']->query(
        "UPDATE " . $destination['downloads'] . " d, " . $destination['cat'] . " c SET d.cid=c.cid" . " WHERE d.old_cid=c.old_cid AND d.old_cid != 0"
    );
    $GLOBALS['xoopsDB']->query(
        "UPDATE " . $destination['cat'] . " c1, " . $destination['cat'] . " c2 SET c1.pid=c2.cid" . " WHERE c1.old_pid=c2.old_cid AND c1.old_pid != 0"
    );
    if ($max_requestid) {
        $GLOBALS['xoopsDB']->query(
            "UPDATE " . $destination['mod'] . " m, " . $destination['cat'] . " c SET m.cid=c.cid" . " WHERE m.requestid > " . $max_requestid . " AND c.old_cid=m.cid"
        );
        //Update lid values in mod table
        $GLOBALS['xoopsDB']->query(
            "UPDATE " . $destination['mod'] . " m, " . $destination['downloads'] . " d SET m.lid=d.lid" . " WHERE m.requestid > " . $max_requestid . " AND m.lid=d.old_lid"
        );
    }
    if ($max_ratingid) {
        //Update lid values in votedata table
        $GLOBALS['xoopsDB']->query(
            "UPDATE " . $destination['votes'] . " v, " . $destination['downloads'] . " d SET v.lid=d.lid" . " WHERE v.ratingid > " . $max_ratingid . " AND v.lid=d.old_lid"
        );
    }
    if ($max_reportid) {
        //Update lid values in brokens table
        $GLOBALS['xoopsDB']->query(
            "UPDATE " . $destination['broken'] . " b, " . $destination['downloads'] . " d SET b.lid=d.lid" . " WHERE b.reportid > " . $max_reportid . " AND b.lid=d.old_lid"
        );
    }
    //Update description
    $GLOBALS['xoopsDB']->query(
        "UPDATE " . $destination['downloads'] . " d, " . $source['text'] . " t SET d.description=t.description" . " WHERE t.lid=d.old_lid"
    );

    //Remove temporary fields
    $GLOBALS['xoopsDB']->query("ALTER TABLE ." . $destination['cat'] . " DROP `old_cid`");
    $GLOBALS['xoopsDB']->query("ALTER TABLE ." . $destination['cat'] . " DROP `old_pid`");
    $GLOBALS['xoopsDB']->query("ALTER TABLE ." . $destination['downloads'] . " DROP `old_cid`");
    $GLOBALS['xoopsDB']->query("ALTER TABLE ." . $destination['downloads'] . " DROP `old_lid`");

}

// =========================================================================================
// This function imports data from TDMDownloads
// =========================================================================================
function import_tdmdownloads_to_wfdownloads()
{
    echo "<br /><span style='font-weight: bold;'>Importing Data</span><br />";
    $destination = array(
        "cat"       => $GLOBALS['xoopsDB']->prefix("wfdownloads_cat"),
        "downloads" => $GLOBALS['xoopsDB']->prefix("wfdownloads_downloads"),
        "broken"    => $GLOBALS['xoopsDB']->prefix("wfdownloads_broken"),
        "mod"       => $GLOBALS['xoopsDB']->prefix("wfdownloads_mod"),
        "votes"     => $GLOBALS['xoopsDB']->prefix("wfdownloads_votedata")
    );

    $source = array(
        "cat"          => $GLOBALS['xoopsDB']->prefix("tdmdownloads_cat"),
        "downloads"    => $GLOBALS['xoopsDB']->prefix("tdmdownloads_downloads"),
        "broken"       => $GLOBALS['xoopsDB']->prefix("tdmdownloads_broken"),
        "mod"          => $GLOBALS['xoopsDB']->prefix("tdmdownloads_mod"),
        "votes"        => $GLOBALS['xoopsDB']->prefix("tdmdownloads_votedata"),
        "field"        => $GLOBALS['xoopsDB']->prefix("tdmdownloads_field"),
        "fielddata"    => $GLOBALS['xoopsDB']->prefix("tdmdownloads_fielddata"),
        "modfielddata" => $GLOBALS['xoopsDB']->prefix("tdmdownloads_modfielddata"),
        "downlimit"    => $GLOBALS['xoopsDB']->prefix("tdmdownloads_downlimit")
    );

    //Add temporary field to category table
    $GLOBALS['xoopsDB']->query("ALTER TABLE " . $destination['cat'] . " ADD `old_cid` int NOT NULL default 0");
    $GLOBALS['xoopsDB']->query("ALTER TABLE " . $destination['cat'] . " ADD `old_pid` int NOT NULL default 0");

    //Add temporary fields to downloads table
    $GLOBALS['xoopsDB']->query(
        "ALTER TABLE " . $destination['downloads'] . " ADD `old_lid` int NOT NULL default 0,
                                                               ADD `old_cid` int NOT NULL default 0"
    );

    //Get latest mod request ID to determine which records will need an updated lid value afterwards
    $result = $GLOBALS['xoopsDB']->query("SELECT MAX(requestid) FROM " . $destination['mod']);
    list($max_requestid) = $GLOBALS['xoopsDB']->fetchRow($result);
    //Get latest report ID to determine, which records will need an updated lid value afterwards
    $result = $GLOBALS['xoopsDB']->query("SELECT MAX(reportid) FROM " . $destination['broken']);
    list($max_reportid) = $GLOBALS['xoopsDB']->fetchRow($result);
    //Get latest vote ID to determine which records will need an updated lid value afterwards
    $result = $GLOBALS['xoopsDB']->query("SELECT MAX(ratingid) FROM " . $destination['votes']);
    list($max_ratingid) = $GLOBALS['xoopsDB']->fetchRow($result);

    //Import data into category table
    $GLOBALS['xoopsDB']->query(
        "INSERT" . " INTO " . $destination['cat'] . " (`old_cid`, `old_pid`, `title`, `imgurl`, `description`, `weight`, `dohtml`)"
        . " SELECT `cat_cid`, `cat_pid`, `cat_title`, `cat_imgurl`, `cat_description_main`, `cat_weight`, 1" . " FROM " . $source['cat']
    );
    echo "Imported " . $GLOBALS['xoopsDB']->getAffectedRows() . " categories into " . $destination['cat'] . "<br />";
    //Import data into downloads table
    $GLOBALS['xoopsDB']->query(
        "INSERT" . " INTO " . $destination['downloads']
        . " (`cid`, `old_lid`, `old_cid`, `title`, `url`, `homepage`, `version`, `size`, `platform`, `screenshot`, `submitter`, `status`, `published`, `hits`, `rating`, `votes`, `comments`, `features`, `requirements`, `dhistory`, `summary`, `description`, `dohtml`)"
        . " SELECT 0, `lid`, `cid`, `title`, `url`, `homepage`, `version`, `size`, `platform`, `logourl`, `submitter`, `status`, `date`, `hits`, `rating`, `votes`, `comments`, '', '', '', '', `description`, 1"
        . " FROM " . $source['downloads']
    );
    echo "Imported " . $GLOBALS['xoopsDB']->getAffectedRows() . " downloads into " . $destination['downloads'] . "<br />";
    //Import data into brokens table
    $GLOBALS['xoopsDB']->query(
        "INSERT" . " INTO " . $destination['broken'] . " (`lid`, `sender`, `ip`)" . " SELECT `lid`, `sender`, `ip`" . " FROM " . $source['broken']
    );
    echo "Imported " . $GLOBALS['xoopsDB']->getAffectedRows() . " broken reports into " . $destination['broken'] . "<br />";
    //Import data into votedata table
    $GLOBALS['xoopsDB']->query(
        "INSERT" . " INTO " . $destination['votes'] . " (`lid`, `ratinguser`, `rating`, `ratinghostname`, `ratingtimestamp`)"
        . " SELECT `lid`, `ratinguser`, `rating`, `ratinghostname`, `ratingtimestamp`" . " FROM " . $source['votes']
    );
    echo "Imported " . $GLOBALS['xoopsDB']->getAffectedRows() . " votes into " . $destination['votes'] . "<br />";
    /*
                //Import data into mod request table
                $GLOBALS['xoopsDB']->query(
                    "INSERT INTO " . $destination['mod'] . " (`lid`, `cid`, `title`, `url`, `homepage`, `version`, `size`, `platform`, `screenshot`, `description`, `modifysubmitter`,`features`, `requirements`, `publisher`, `dhistory`, `summary`)
                                 SELECT `lid`, `cid`, `title`, `url`, `homepage`, `version`, `size`, `platform`, `logourl`, `description`, `modifysubmitter`,'','','','','' FROM "
                    . $source['mod']
                );
                echo "Imported " . $GLOBALS['xoopsDB']->getAffectedRows() . " modification requests into " . $destination['mod'] . "<br />";
    */
    //Update category ID to new value
    $GLOBALS['xoopsDB']->query(
        "UPDATE " . $destination['downloads'] . " d, " . $destination['cat'] . " c SET d.cid=c.cid" . " WHERE d.old_cid=c.old_cid AND d.old_cid != 0"
    );
    $GLOBALS['xoopsDB']->query(
        "UPDATE " . $destination['cat'] . " c1, " . $destination['cat'] . " c2 SET c1.pid=c2.cid" . " WHERE c1.old_pid=c2.old_cid AND c1.old_pid != 0"
    );
    /*
                if ($max_requestid) {
                    $GLOBALS['xoopsDB']->query(
                        "UPDATE " . $destination['mod'] . " m, " . $destination['cat'] . " c SET m.cid=c.cid"
                        . " WHERE m.requestid > " . $max_requestid
                        . " AND c.old_cid=m.cid"
                    );
                    //Update lid values in mod table
                    $GLOBALS['xoopsDB']->query(
                        "UPDATE " . $destination['mod'] . " m, " . $destination['downloads'] . " d SET m.lid=d.lid"
                        . " WHERE m.requestid > " . $max_requestid
                        . " AND m.lid=d.old_lid"
                    );
                }
    */
    if ($max_ratingid) {
        //Update lid values in votedata table
        $GLOBALS['xoopsDB']->query(
            "UPDATE " . $destination['votes'] . " v, " . $destination['downloads'] . " d SET v.lid=d.lid" . " WHERE v.ratingid > " . $max_ratingid . " AND v.lid=d.old_lid"
        );
    }
    if ($max_reportid) {
        //Update lid values in brokens table
        $GLOBALS['xoopsDB']->query(
            "UPDATE " . $destination['broken'] . " b, " . $destination['downloads'] . " d SET b.lid=d.lid" . " WHERE b.reportid > " . $max_reportid . " AND b.lid=d.old_lid"
        );
    }

    //Remove temporary fields
    $GLOBALS['xoopsDB']->query("ALTER TABLE ." . $destination['cat'] . " DROP `old_cid`");
    $GLOBALS['xoopsDB']->query("ALTER TABLE ." . $destination['cat'] . " DROP `old_pid`");
    $GLOBALS['xoopsDB']->query("ALTER TABLE ." . $destination['downloads'] . " DROP `old_cid`");
    $GLOBALS['xoopsDB']->query("ALTER TABLE ." . $destination['downloads'] . " DROP `old_lid`");
}
