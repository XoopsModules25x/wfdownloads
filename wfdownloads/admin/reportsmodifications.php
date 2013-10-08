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
include_once dirname(__FILE__) . '/admin_header.php';

$op = WfdownloadsRequest::getString('op', 'reports.modifications.list');
switch ($op) {
    case "reports.update":
        $lid      = WfdownloadsRequest::getInt('lid', 0);
        $criteria = new Criteria('lid', $lid);
        if (isset($_GET['ack'])) {
            $acknowledged = (isset($_GET['ack']) && $_GET['ack'] == 0) ? 1 : 0;
            $wfdownloads->getHandler('report')->updateAll("acknowledged", $acknowledged, $criteria, true);
            $update_mess = _AM_WFDOWNLOADS_BROKEN_NOWACK;
        }
        if (isset($_GET['con'])) {
            $confirmed = (isset($_GET['con']) && $_GET['con'] == 0) ? 1 : 0;
            $wfdownloads->getHandler('report')->updateAll("confirmed", $confirmed, $criteria, true);
            $update_mess = _AM_WFDOWNLOADS_BROKEN_NOWCON;
        }
        redirect_header($currentFile, 1, $update_mess);
        break;

    case "report.delete":
        $lid      = WfdownloadsRequest::getInt('lid', 0);
        $criteria = new Criteria('lid', $lid);
        $report   = $wfdownloads->getHandler('report')->getObjects($criteria);
        if (isset($report[0])) {
            $wfdownloads->getHandler('report')->delete($report[0], true);
        }
        $download = $wfdownloads->getHandler('download')->get($lid);
        $wfdownloads->getHandler('download')->delete($download, true);
        redirect_header($currentFile, 1, _AM_WFDOWNLOADS_BROKENFILEDELETED);
        break;

    case "report.ignore":
        $lid      = WfdownloadsRequest::getInt('lid', 0);
        $criteria = new Criteria('lid', $lid);
        $report   = $wfdownloads->getHandler('report')->getObjects($criteria);
        if (isset($report[0])) {
            $wfdownloads->getHandler('report')->delete($report[0], true);
        }
        redirect_header($currentFile, 1, _AM_WFDOWNLOADS_BROKEN_FILEIGNORED);
        break;

    case "modification.show":
        wfdownloads_xoops_cp_header();

        $requestid = intval($_GET['requestid']);

        $modification = $wfdownloads->getHandler('modification')->get($requestid);
        $download     = $wfdownloads->getHandler('download')->get($modification->getVar('lid'));

        $orig_user      = new XoopsUser($download->getVar('submitter'));
        $submittername  = XoopsUserUtility::getUnameFromId($download->getVar('submitter')); // $orig_user->getvar("uname");
        $submitteremail = $orig_user->getVar('email');

        echo "<div><b>" . _AM_WFDOWNLOADS_MOD_MODPOSTER . "</b> $submittername</div>";
        $not_allowed = array('lid', 'submitter', 'requestid', 'modifysubmitter');

        $sform = new XoopsThemeForm(_AM_WFDOWNLOADS_MOD_ORIGINAL, 'storyform', 'index.php');

        $keys = $download->getVars();
        foreach (array_keys($keys) as $key) {
            if (in_array($key, $not_allowed)) {
                continue;
            }
            $lang_def = constant("_AM_WFDOWNLOADS_MOD_" . strtoupper($key));

            $content = $download->getVar($key, 'e');
            switch ($key) {
                case "platform":
                case "license":
                case "limitations":
                case "versiontypes":
                    $tempArray = $wfdownloads->getConfig($key);
                    $content   = isset($tempArray[$download->getVar($key)]) ? $tempArray[$download->getVar($key)] : '';
                    break;
                case "cid":
                    $category_list = $wfdownloads->getHandler('category')->getObjects(new Criteria("cid", $download->getVar($key)));
                    if (!isset($category_list[0])) {
                        continue;
                    }
                    $content = $category_list[0]->getVar('title', 'e');
                    break;
                case "screenshot":
                    if ($content != '') {
                        $content = "<img src='" . XOOPS_URL . '/' . $wfdownloads->getConfig('screenshots') . '/' . $content . "' width='"
                            . $wfdownloads->getConfig('shotwidth') . "' alt='' title='' />";
                    }
                    break;
                case "screenshot2":
                    if ($content != '') {
                        $content = "<img src='" . XOOPS_URL . '/' . $wfdownloads->getConfig('screenshots') . '/' . $content . "' width='"
                            . $wfdownloads->getConfig('shotwidth') . "' alt='' title='' />";
                    }
                    break;
                case "screenshot3":
                    if ($content != '') {
                        $content = "<img src='" . XOOPS_URL . '/' . $wfdownloads->getConfig('screenshots') . '/' . $content . "' width='"
                            . $wfdownloads->getConfig('shotwidth') . "' alt='' title='' />";
                    }
                    break;
                case "screenshot4":
                    if ($content != '') {
                        $content = "<img src='" . XOOPS_URL . '/' . $wfdownloads->getConfig('screenshots') . '/' . $content . "' width='"
                            . $wfdownloads->getConfig('shotwidth') . "' alt='' title='' />";
                    }
                    break;
                case "features":
                case "requirements":
                    if ($content != '') {
                        $downrequirements = explode('|', trim($content));
                        foreach ($downrequirements as $bi) {
                            $content = "<li>" . $bi;
                        }
                    }
                    break;
                case "dhistory":
                    $content = $myts->displayTarea($content, true, false, false, false, true);
                    break;
            }
            $sform->addElement(new XoopsFormLabel($lang_def, $content));
        }
        $sform->display();

        $modify_user = new XoopsUser($modification->getVar('modifysubmitter'));
        $modifyname  = XoopsUserUtility::getUnameFromId((int)$modify_user->getVar('uid'));
        $modifyemail = $modify_user->getVar('email');

        echo "<div><b>" . _AM_WFDOWNLOADS_MOD_MODIFYSUBMITTER . "</b> $modifyname</div>";
        $sform = new XoopsThemeForm(_AM_WFDOWNLOADS_MOD_PROPOSED, 'storyform', 'reportsmodifications.php');
        $keys  = $modification->getVars();
        foreach (array_keys($keys) as $key) {
            if (in_array($key, $not_allowed)) {
                continue;
            }
            $lang_def = constant("_AM_WFDOWNLOADS_MOD_" . strtoupper($key));

            $content = $modification->getVar($key, 'e');
            switch ($key) {
                case "platform":
                case "license":
                case "limitations":
                case "versiontypes":
                    $tempArray = $wfdownloads->getConfig($key);
                    $content   = isset($tempArray[$modification->getVar($key)]) ? $tempArray[$modification->getVar($key)] : '';
                    break;
                case "cid":
                    $category_list = $wfdownloads->getHandler('category')->getObjects(new Criteria('cid', $modification->getVar($key)));
                    if (!isset($category_list[0])) {
                        continue;
                    }
                    $content = $category_list[0]->getVar('title', 'e');
                    break;
                case "screenshot":
                    if ($content != '') {
                        $content = "<img src='" . XOOPS_URL . '/' . $wfdownloads->getConfig('screenshots') . '/' . $content . "' width='"
                            . $wfdownloads->getConfig('shotwidth') . "' alt='' title='' />";
                    }
                    break;
                case "screenshot2":
                    if ($content != '') {
                        $content = "<img src='" . XOOPS_URL . '/' . $wfdownloads->getConfig('screenshots') . '/' . $content . "' width='"
                            . $wfdownloads->getConfig('shotwidth') . "' alt='' title='' />";
                    }
                    break;
                case "screenshot3":
                    if ($content != '') {
                        $content = "<img src='" . XOOPS_URL . '/' . $wfdownloads->getConfig('screenshots') . '/' . $content . "' width='"
                            . $wfdownloads->getConfig('shotwidth') . "' alt='' title='' />";
                    }
                    break;
                case "screenshot4":
                    if ($content != '') {
                        $content = "<img src='" . XOOPS_URL . '/' . $wfdownloads->getConfig('screenshots') . '/' . $content . "' width='"
                            . $wfdownloads->getConfig('shotwidth') . "' alt='' title='' />";
                    }
                    break;
                case "features":
                case "requirements":
                    if ($content != '') {
                        $downrequirements = explode('|', trim($content));
                        foreach ($downrequirements as $bi) {
                            $content = "<li>" . $bi;
                        }
                    }
                    break;
                case "dhistory":
                    $content = $myts->displayTarea($content, true, false, false, false, true);
                    break;
            }
            $sform->addElement(new XoopsFormLabel($lang_def, $content));
        }

        $button_tray = new XoopsFormElementTray('', '');
        $button_tray->addElement(new XoopsFormHidden('requestid', $requestid));
        $button_tray->addElement(new XoopsFormHidden('lid', (int)$modification->getVar('lid')));
        $hidden = new XoopsFormHidden('op', 'modification.change');
        $button_tray->addElement($hidden);
        if (!$modification->isNew()) {
            $approve_button = new XoopsFormButton('', '', _AM_WFDOWNLOADS_BAPPROVE, 'submit');
            $approve_button->setExtra('onclick="this.form.elements.op.value=\'modification.change\'"');
            $button_tray->addElement($approve_button);
        }
        $ignore_button = new XoopsFormButton('', '', _AM_WFDOWNLOADS_BIGNORE, 'submit');
        $ignore_button->setExtra('onclick="this.form.elements.op.value=\'modification.ignore\'"');
        $button_tray->addElement($ignore_button);
        $sform->addElement($button_tray);
        $sform->display();

        xoops_cp_footer();
        exit();
        break;

    case "modification.change":
        /* Added by Lankford on 2007/3/21 */
        //Get a pointer to the download record and the modification record, then compare
        //their 'versions' to see if they are different.  If they are, then raise filemodify
        //events.
        $requestid = intval($_POST['requestid']);

        $modification = $wfdownloads->getHandler('modification')->get($requestid);
        $download     = $wfdownloads->getHandler('download')->get($modification->getVar('lid'));

        if ($modification->getVar('version') == $download->getVar('version')) {
            $raiseModifyEvents = false;
        } else {
            $raiseModifyEvents = true;
        }
        /* end add block */

        $wfdownloads->getHandler('modification')->approveModification($_POST['requestid']);

        $cid = $download->getVar('cid');
        $lid = $download->getVar('lid');

        /* Added by lankford on 2007/3/21 */
        if ($raiseModifyEvents) {
            // Trigger the three events related to modified files (one for the file, category, and global event categories respectively)

            $tags                  = array();
            $tags['FILE_NAME']     = $download->getVar('title');
            $tags['FILE_URL']      = WFDOWNLOADS_URL . '/singlefile.php?cid=' . $cid . '&amp;lid=' . $lid;
            $category              = $wfdownloads->getHandler('category')->get($cid);
            $tags['FILE_VERSION']  = $download->getVar('version');
            $tags['CATEGORY_NAME'] = $category->getVar('title');
            $tags['CATEGORY_URL']  = WFDOWNLOADS_URL . '/viewcat.php?cid=' . $cid;

            $notification_handler->triggerEvent('global', 0, 'filemodified', $tags);
            $notification_handler->triggerEvent('category', $cid, 'filemodified', $tags);
            $notification_handler->triggerEvent('file', $lid, 'filemodified', $tags);
        }
        /* end add block */

        redirect_header(WFDOWNLOADS_URL . '/admin/index.php', 1, _AM_WFDOWNLOADS_MOD_REQUPDATED);
        break;

    case "modification.ignore":
        $criteria = new Criteria('requestid', intval($_POST['requestid']));
        $wfdownloads->getHandler('modification')->deleteAll($criteria, true);
        redirect_header(WFDOWNLOADS_URL . '/admin/index.php', 1, _AM_WFDOWNLOADS_MOD_REQDELETED);
        break;

    case "reports.modifications.list":
    default:
        $start_report = WfdownloadsRequest::getInt('start_report', 0);

        $criteria      = new CriteriaCompo();
        $reports_count = $wfdownloads->getHandler('report')->getCount();
        $criteria->setSort('date');
        $criteria->setOrder('DESC');
        $criteria->setLimit($wfdownloads->getConfig('admin_perpage'));
        $criteria->setStart($start_report);
        $reports = $wfdownloads->getHandler('report')->getObjects($criteria);

        wfdownloads_xoops_cp_header();
        $indexAdmin = new ModuleAdmin();
        echo $indexAdmin->addNavigation($currentFile);

        $GLOBALS['xoopsTpl']->assign('reports_count', $reports_count);

        if ($reports_count > 0) {
            foreach (array_keys($reports) as $i) {
                $lids[] = $reports[$i]->getVar('lid');
                $uids[] = $reports[$i]->getVar('sender');
            }
            $downloads = $wfdownloads->getHandler('download')->getObjects(
                new Criteria('lid', '(' . implode(',', array_unique($lids)) . ')', 'IN'),
                true
            );
            foreach (array_keys($downloads) as $i) {
                $uids[] = $downloads[$i]->getVar('submitter');
            }
            $users = $member_handler->getUsers(new Criteria('uid', '(' . implode(',', array_unique($uids)) . ')', 'IN'), true);

            foreach ($reports as $report) {
                $report_array = $report->toArray();
                // Does the download exists ?
                if (isset($downloads[$report->getVar('lid')])) {
                    $download                        = $downloads[$report->getVar('lid')];
                    $report_array['download_lid']    = $download->getVar('lid');
                    $report_array['download_cid']    = $download->getVar('cid');
                    $report_array['download_title']  = $download->getVar('title');
                    $submitter                       = isset($users[$downloads[$report->getVar('lid')]->getVar('submitter')])
                        ? $users[$downloads[$report->getVar('lid')]->getVar('submitter')] : false;
                    $report_array['submitter_email'] = is_object($submitter) ? $submitter->getVar('email') : '';
                    $report_array['submitter_uname'] = is_object($submitter) ? $submitter->getVar('uname') : $xoopsConfig['anonymous'];
                } else {
                    $report_array['download_lid']    = false;
                    $download_link                   = _AM_WFDOWNLOADS_BROKEN_DOWNLOAD_DONT_EXISTS;
                    $report_array['submitter_email'] = '';
                    $report_array['submitter_uname'] = $xoopsConfig['anonymous'];
                }
                $sender                         = isset($users[$report->getVar('sender')]) ? $users[$report->getVar('sender')] : '';
                $report_array['reporter_email'] = isset($users[$report->getVar('sender')]) ? $users[$report->getVar('sender')]->getVar('email') : '';
                $report_array['reporter_uname'] = isset($users[$report->getVar('sender')]) ? $users[$report->getVar('sender')]->getVar('uname')
                    : $xoopsConfig['anonymous'];
                $report_array['formatted_date'] = formatTimestamp($report->getVar('date'), _DATESTRING);
                $GLOBALS['xoopsTpl']->append('reports', $report_array);
            }
            //Include page navigation
            include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $pagenav_report = new XoopsPageNav($reports_count, $wfdownloads->getConfig('admin_perpage'), $start_report, 'start_report');
            $GLOBALS['xoopsTpl']->assign('reports_pagenav', $pagenav_report->renderNav());
        }

        $start_modification = WfdownloadsRequest::getInt('start_modification', 0);

        $modifications_count = $wfdownloads->getHandler('modification')->getCount();
        $criteria            = new CriteriaCompo();
        $criteria->setLimit($wfdownloads->getConfig('admin_perpage'));
        $criteria->setStart($start_modification);
        $criteria->setSort("requestdate");
        $modifications = $wfdownloads->getHandler('modification')->getObjects($criteria);

        $GLOBALS['xoopsTpl']->assign('modifications_count', $modifications_count);

        if ($modifications_count > 0) {
            foreach ($modifications as $modification) {
                $modification_array                    = $modification->toArray();
                $modification_array['title']           = ($modification->getVar('title'));
                $modification_array['submitter_uname'] = XoopsUserUtility::getUnameFromId($modification->getVar('submitter'));
                $modification_array['formatted_date']  = formatTimestamp($modification->getVar('requestdate'), _DATESTRING);
                $GLOBALS['xoopsTpl']->append('modifications', $modification_array);
            }
            //Include page navigation
            include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $pagenav_modification = new XoopsPageNav($modifications_count, $wfdownloads->getConfig(
                'admin_perpage'
            ), $start_modification, 'start_modification');
            $GLOBALS['xoopsTpl']->assign('modifications_pagenav', $pagenav_modification->renderNav());
        }

        $xoopsTpl->assign('use_mirrors', $wfdownloads->getConfig('enable_mirrors'));
        $xoopsTpl->assign('use_ratings', $wfdownloads->getConfig('enable_ratings'));
        $xoopsTpl->assign('use_reviews', $wfdownloads->getConfig('enable_reviews'));
        $xoopsTpl->assign('use_brokenreports', $wfdownloads->getConfig('enable_brokenreports'));

        $GLOBALS['xoopsTpl']->display("db:" . $xoopsModule->dirname() . "_admin_reportsmodificationslist.html");

        include 'admin_footer.php';
        break;
}
