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

$op = XoopsRequest::getString('op', 'reports.modifications.list');
switch ($op) {
    case 'reports.update':
        $lid      = XoopsRequest::getInt('lid', 0);
        $criteria = new Criteria('lid', $lid);
        if (isset($_GET['ack'])) {
            $acknowledged = (isset($_GET['ack']) && $_GET['ack'] == 0) ? 1 : 0;
            $wfdownloads->getHandler('report')->updateAll('acknowledged', $acknowledged, $criteria, true);
            $update_mess = _AM_WFDOWNLOADS_BROKEN_NOWACK;
        }
        if (isset($_GET['con'])) {
            $confirmed = (isset($_GET['con']) && $_GET['con'] == 0) ? 1 : 0;
            $wfdownloads->getHandler('report')->updateAll('confirmed', $confirmed, $criteria, true);
            $update_mess = _AM_WFDOWNLOADS_BROKEN_NOWCON;
        }
        redirect_header($currentFile, 1, $update_mess);
        break;

    case 'report.delete':
        $lid        = XoopsRequest::getInt('lid', 0);
        $criteria   = new Criteria('lid', $lid);
        $reportObjs = $wfdownloads->getHandler('report')->getObjects($criteria);
        if (isset($reportObjs[0])) {
            $wfdownloads->getHandler('report')->delete($reportObjs[0], true);
        }
        $downloadObj = $wfdownloads->getHandler('download')->get($lid);
        $wfdownloads->getHandler('download')->delete($downloadObj, true);
        redirect_header($currentFile, 1, _AM_WFDOWNLOADS_BROKENFILEDELETED);
        break;

    case 'report.ignore':
        $lid        = XoopsRequest::getInt('lid', 0);
        $criteria   = new Criteria('lid', $lid);
        $reportObjs = $wfdownloads->getHandler('report')->getObjects($criteria);
        if (isset($reportObjs[0])) {
            $wfdownloads->getHandler('report')->delete($reportObjs[0], true);
        }
        redirect_header($currentFile, 1, _AM_WFDOWNLOADS_BROKEN_FILEIGNORED);
        break;

    case 'modification.show':
        $requestid = XoopsRequest::getInt('requestid', 0);

        $modificationObj = $wfdownloads->getHandler('modification')->get($requestid);
        $modify_user     = new XoopsUser($modificationObj->getVar('modifysubmitter'));
        $modifyname      = XoopsUserUtility::getUnameFromId((int)$modify_user->getVar('uid'));
        $modifyemail     = $modify_user->getVar('email');

        $downloadObj    = $wfdownloads->getHandler('download')->get($modificationObj->getVar('lid'));
        $orig_user      = new XoopsUser($downloadObj->getVar('submitter'));
        $submittername  = XoopsUserUtility::getUnameFromId($downloadObj->getVar('submitter')); // $orig_user->getvar("uname");
        $submitteremail = $orig_user->getVar('email');

        $categoryObjs     = $wfdownloads->getHandler('category')->getObjects();
        $categoryObjsTree = new XoopsObjectTree($categoryObjs, 'cid', 'pid');

        wfdownloads_xoops_cp_header();

        // IN PROGRESS
        // IN PROGRESS
        // IN PROGRESS
        // IN PROGRESS NEW FROM HERE

        echo "<div><b>" . _AM_WFDOWNLOADS_MOD_MODPOSTER . "</b> $submittername</div>";
        echo "<div><b>" . _AM_WFDOWNLOADS_MOD_MODIFYSUBMITTER . "</b> $modifyname</div>";

        $mcform = new WfdownloadsMulticolumnsThemeForm('', 'modificationform', $currentFile);

        // Get download keys
        $downloadVars = $downloadObj->getVars();
        $downloadKeys = array_keys($downloadVars);
        // Get modification keys
        $modificationVars = $modificationObj->getVars();
        $modificationKeys = array_keys($modificationVars);
        // Get common keys
        $commonKeys = array_intersect($downloadKeys, $modificationKeys);
        // Set not allowed keys
        $notAllowedKeys = array('lid', 'submitter', 'publisher', 'requestid', 'forumid', 'modifysubmitter', 'paypalemail');

        $i = 0;
        $mcform->addElement(null, false, null, null);
        $mcform->setTitles(array('', _AM_WFDOWNLOADS_MOD_ORIGINAL, _AM_WFDOWNLOADS_MOD_PROPOSED));
        $i = 1;
        foreach ($commonKeys as $key) {
            if (in_array($key, $notAllowedKeys)) {
                continue;
            }
            $caption             = constant('_AM_WFDOWNLOADS_MOD_' . strtoupper($key));
            $downloadContent     = $downloadObj->getVar($key);
            $modificationContent = $modificationObj->getVar($key);
            // Extra jobs for some keys
            switch ($key) {
                case 'title':
                case 'url':
                    // NOP
                    break;
                case 'size':
                    $downloadContent = wfdownloads_bytesToSize1024($downloadContent);
                    //
                    $modificationContent = wfdownloads_bytesToSize1024($modificationContent);
                    break;
                case 'date':
                case 'published':
                case 'expired':
                case 'updated':
                    $downloadContent = ($downloadContent != false) ? XoopsLocal::formatTimestamp($downloadContent, 'l') : _NO;
                    //
                    $modificationContent = ($modificationContent != false) ? XoopsLocal::formatTimestamp($modificationContent, 'l') : _NO;
                    break;
                case 'platform':
                case 'license':
                case 'limitations':
                case 'versiontypes':
                    $tempArray       = $wfdownloads->getConfig($key);
                    $downloadContent = isset($tempArray[$downloadObj->getVar($key)]) ? $tempArray[$downloadObj->getVar($key)] : '';
                    //
                    $modificationContent = isset($tempArray[$modificationObj->getVar($key)]) ? $tempArray[$modificationObj->getVar($key)] : '';
                    break;
                case 'cid':
                    $category_list = $wfdownloads->getHandler('category')->getObjects(new Criteria('cid', $downloadObj->getVar($key)));
                    if (!isset($category_list[0])) {
                        continue;
                    }
                    $downloadContent = $category_list[0]->getVar('title', 'e');
                    //
                    $category_list = $wfdownloads->getHandler('category')->getObjects(new Criteria('cid', $modificationObj->getVar($key)));
                    if (!isset($category_list[0])) {
                        continue;
                    }
                    $modificationContent = $category_list[0]->getVar('title', 'e');
                    break;
                case 'screenshot':
                case 'screenshot2':
                case 'screenshot3':
                case 'screenshot4':
                    if ($downloadContent != '') {
                        $downloadContent = "<img src='" . XOOPS_URL . "/{$wfdownloads->getConfig('screenshots')}/{$downloadContent}' width='{$wfdownloads->getConfig('shotwidth')}' alt='' title='' />";
                    }
                    //
                    if ($modificationContent != '') {
                        $modificationContent
                            = "<img src='" . XOOPS_URL . "/{$wfdownloads->getConfig('screenshots')}/{$modificationContent}' width='{$wfdownloads->getConfig('shotwidth')}' alt='' title='' />";
                    }
                    break;
                case 'screenshots':
                    $downloadScreenshots     = $downloadContent;
                    $modificationScreenshots = $modificationContent;
                    unset($downloadContent);
                    unset($modificationContent);
                    $downloadContent     = '';
                    $modificationContent = '';
                    foreach ($downloadScreenshots as $key => $value) {
                        $downloadScreenshot     = $downloadScreenshots[$key];
                        $modificationScreenshot = $modificationScreenshots[$key];
                        if ($downloadScreenshot != '') {
                            $downloadContent += "<img src='" . XOOPS_URL . "/{$wfdownloads->getConfig('screenshots')}/{$downloadScreenshot}' width='{$wfdownloads->getConfig(
                                    'shotwidth'
                                )}' alt='' title='' />";
                        }
                        //
                        if ($modificationContent != '') {
                            $modificationContent += "<img src='" . XOOPS_URL . "/{$wfdownloads->getConfig('screenshots')}/{$modificationScreenshot}' width='{$wfdownloads->getConfig(
                                    'shotwidth'
                                )}' alt='' title='' />";
                        }
                    }
                    break;
                case 'publisher':
                    $downloadContent = XoopsUserUtility::getUnameFromId($downloadContent);
                    //
                    $modificationContent = XoopsUserUtility::getUnameFromId($modificationContent);
                    break;
                case 'features':
                case 'requirements':
                    if ($downloadContent != '') {
                        $downrequirements = explode('|', trim($downloadContent));
                        $downloadContent  = "<ul>";
                        foreach ($downrequirements as $bi) {
                            $downloadContent .= "<li>{$bi}</li>";
                        }
                        $downloadContent .= "</ul>";
                    }
                    //
                    if ($modificationContent != '') {
                        $downrequirements    = explode('|', trim($modificationContent));
                        $modificationContent = "<ul>";
                        foreach ($downrequirements as $bi) {
                            $modificationContent .= "<li>{$bi}</li>";
                        }
                        $modificationContent .= "</ul>";
                    }
                    break;
                case 'dhistory':
                    $downloadContent = $myts->displayTarea($downloadContent, true, false, false, false, true);
                    //
                    $modificationContent = $myts->displayTarea($modificationContent, true, false, false, false, true);
                    break;
                case 'summary':
                case 'description':
                    $downloadContent = $downloadContent; //The left and the right parts of assignment are equal
                    //IN PROGRESS?
                    $modificationContent = $modificationContent; //The left and the right parts of assignment are equal
                    break;
                case 'offline':
                case 'dohtml':
                case 'dosmiley':
                case 'doxcode':
                case 'doimage':
                case 'dobr':
                    $downloadContent = $downloadContent ? _YES : _NO;
                    //
                    $modificationContent = $modificationContent ? _YES : _NO;
            }
            $mcform->addElement($caption, false, $i, 0);
            if ($downloadContent != $modificationContent) {
                $modificationContent = "<span style='color:red'>" . $modificationContent . "</span>";
            }
            $downloadFormElement     = new XoopsFormLabel('', $downloadContent);
            $modificationFormElement = new XoopsFormLabel('', $modificationContent);
            $mcform->addElement($downloadFormElement, false, $i, 1);
            $mcform->addElement($modificationFormElement, false, $i, 2);
            ++$i;
        }

        $button_tray = new XoopsFormElementTray('', '');
        $button_tray->addElement(new XoopsFormHidden('requestid', $requestid));
        $button_tray->addElement(new XoopsFormHidden('lid', (int)$modificationObj->getVar('lid')));
        $hidden = new XoopsFormHidden('op', 'modification.change');
        $button_tray->addElement($hidden);
        if (!$modificationObj->isNew()) {
            $approve_button = new XoopsFormButton('', '', _AM_WFDOWNLOADS_BAPPROVE, 'submit');
            $approve_button->setExtra('onclick="this.form.elements.op.value=\'modification.change\'"');
            $button_tray->addElement($approve_button);
        }
        $ignore_button = new XoopsFormButton('', '', _AM_WFDOWNLOADS_BIGNORE, 'submit');
        $ignore_button->setExtra('onclick="this.form.elements.op.value=\'modification.ignore\'"');
        $button_tray->addElement($ignore_button);
        $button_cancel = new XoopsFormButton('', '', _CANCEL, 'button');
        $button_cancel->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement($button_cancel);
        $mcform->addElement($button_tray, false, $i, 2);

        $mcform->display();

        xoops_cp_footer();
        exit();
        break;

    case 'modification.change':
        /* Added by Lankford on 2007/3/21 */
        // Get a pointer to the download record and the modification record, then compare their 'versions' to see if they are different.  If they are, then raise filemodify events.
        $requestid = XoopsRequest::getInt('requestid', 0, 'POST');

        $modificationObj = $wfdownloads->getHandler('modification')->get($requestid);
        $downloadObj     = $wfdownloads->getHandler('download')->get($modificationObj->getVar('lid'));

        if ($modificationObj->getVar('version') == $downloadObj->getVar('version')) {
            $raiseModifyEvents = false;
        } else {
            $raiseModifyEvents = true;
        }
        /* end add block */
        $wfdownloads->getHandler('modification')->approveModification($_POST['requestid']);

        $cid = $downloadObj->getVar('cid');
        $lid = $downloadObj->getVar('lid');

        /* Added by lankford on 2007/3/21 */
        if ($raiseModifyEvents) {
            // Trigger the three events related to modified files (one for the file, category, and global event categories respectively)
            $tags                  = array();
            $tags['FILE_NAME']     = $downloadObj->getVar('title');
            $tags['FILE_URL']      = WFDOWNLOADS_URL . '/singlefile.php?cid=' . $cid . '&amp;lid=' . $lid;
            $category              = $wfdownloads->getHandler('category')->get($cid);
            $tags['FILE_VERSION']  = $downloadObj->getVar('version');
            $tags['CATEGORY_NAME'] = $category->getVar('title');
            $tags['CATEGORY_URL']  = WFDOWNLOADS_URL . '/viewcat.php?cid=' . $cid;

            $notification_handler->triggerEvent('global', 0, 'filemodified', $tags);
            $notification_handler->triggerEvent('category', $cid, 'filemodified', $tags);
            $notification_handler->triggerEvent('file', $lid, 'filemodified', $tags);
        }
        /* end add block */

        redirect_header($currentFile, 1, _AM_WFDOWNLOADS_MOD_REQUPDATED);
        break;

    case 'modification.ignore':
    case 'modification.delete':
        $requestid = XoopsRequest::getInt('requestid', 0);
        $ok        = XoopsRequest::getBool('ok', false, 'POST');
        if (!$modificationObj = $wfdownloads->getHandler('modification')->get($requestid)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_MOD_NOTFOUND);
            exit();
        }
        $title = $modificationObj->getVar('title');
        if ($ok === true) {
            /*
            IN PROGRESS
            IN PROGRESS
            IN PROGRESS
                        if (!$GLOBALS['xoopsSecurity']->check()) {
                            redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
                        }
            */
            if ($wfdownloads->getHandler('modification')->deleteAll(new Criteria('requestid', $requestid), true)) {
                redirect_header(WFDOWNLOADS_URL . '/admin/index.php', 1, _AM_WFDOWNLOADS_MOD_REQDELETED);
            } else {
                echo $modificationObj->getHtmlErrors();
            }
        } else {
            wfdownloads_xoops_cp_header();
            xoops_confirm(
                array('op' => 'modification.ignore', 'requestid' => $requestid, 'ok' => true, 'title' => $title),
                $currentFile,
                _AM_WFDOWNLOADS_MOD_REALLYIGNOREDTHIS . "<br /><br>" . $title,
                _DELETE
            );
            xoops_cp_footer();
        }
        break;

    case 'reports.modifications.list':
    default:
        $start_report = XoopsRequest::getInt('start_report', 0);

        $criteria      = new CriteriaCompo();
        $reports_count = $wfdownloads->getHandler('report')->getCount();
        $criteria->setSort('date');
        $criteria->setOrder('DESC');
        $criteria->setLimit($wfdownloads->getConfig('admin_perpage'));
        $criteria->setStart($start_report);
        $reportObjs = $wfdownloads->getHandler('report')->getObjects($criteria);

        wfdownloads_xoops_cp_header();
        $indexAdmin = new ModuleAdmin();
        echo $indexAdmin->addNavigation($currentFile);

        $GLOBALS['xoopsTpl']->assign('reports_count', $reports_count);

        if ($reports_count > 0) {
            foreach ($reportObjs as $reportObj) {
                $lids[] = $reportObj->getVar('lid');
                $uids[] = $reportObj->getVar('sender');
            }
            $downloadObjs = $wfdownloads->getHandler('download')->getObjects(
                new Criteria('lid', '(' . implode(',', array_unique($lids)) . ')', 'IN'),
                true
            );
            foreach (array_keys($downloadObjs) as $i) {
                $uids[] = $downloadObjs[$i]->getVar('submitter');
            }
            $users = $member_handler->getUsers(new Criteria('uid', '(' . implode(',', array_unique($uids)) . ')', 'IN'), true);

            foreach ($reportObjs as $reportObj) {
                $report_array = $reportObj->toArray();
                // Does the download exists ?
                if (isset($downloadObjs[$reportObj->getVar('lid')])) {
                    $downloadObj                     = $downloadObjs[$reportObj->getVar('lid')];
                    $report_array['download_lid']    = $downloadObj->getVar('lid');
                    $report_array['download_cid']    = $downloadObj->getVar('cid');
                    $report_array['download_title']  = $downloadObj->getVar('title');
                    $submitter                       = isset($users[$downloadObjs[$reportObj->getVar('lid')]->getVar('submitter')]) ? $users[$downloadObjs[$reportObj->getVar('lid')]->getVar(
                        'submitter'
                    )] : false;
                    $report_array['submitter_email'] = is_object($submitter) ? $submitter->getVar('email') : '';
                    $report_array['submitter_uname'] = is_object($submitter) ? $submitter->getVar('uname') : $GLOBALS['xoopsConfig']['anonymous'];
                } else {
                    $report_array['download_lid']    = false;
                    $download_link                   = _AM_WFDOWNLOADS_BROKEN_DOWNLOAD_DONT_EXISTS;
                    $report_array['submitter_email'] = '';
                    $report_array['submitter_uname'] = $GLOBALS['xoopsConfig']['anonymous'];
                }
                $sender                         = isset($users[$reportObj->getVar('sender')]) ? $users[$reportObj->getVar('sender')] : '';
                $report_array['reporter_email'] = isset($users[$reportObj->getVar('sender')]) ? $users[$reportObj->getVar('sender')]->getVar('email') : '';
                $report_array['reporter_uname'] = isset($users[$reportObj->getVar('sender')]) ? $users[$reportObj->getVar('sender')]->getVar('uname') : $GLOBALS['xoopsConfig']['anonymous'];
                $report_array['formatted_date'] = XoopsLocal::formatTimestamp($reportObj->getVar('date'), 'l');
                $GLOBALS['xoopsTpl']->append('reports', $report_array);
            }
            //Include page navigation
            include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $pagenav_report = new XoopsPageNav($reports_count, $wfdownloads->getConfig('admin_perpage'), $start_report, 'start_report');
            $GLOBALS['xoopsTpl']->assign('reports_pagenav', $pagenav_report->renderNav());
        }

        $start_modification = XoopsRequest::getInt('start_modification', 0);

        $modifications_count = $wfdownloads->getHandler('modification')->getCount();
        $criteria            = new CriteriaCompo();
        $criteria->setLimit($wfdownloads->getConfig('admin_perpage'));
        $criteria->setStart($start_modification);
        $criteria->setSort("requestdate");
        $modificationObjs = $wfdownloads->getHandler('modification')->getObjects($criteria);

        $GLOBALS['xoopsTpl']->assign('modifications_count', $modifications_count);

        if ($modifications_count > 0) {
            foreach ($modificationObjs as $modificationObj) {
                $modification_array                    = $modificationObj->toArray();
                $modification_array['title']           = ($modificationObj->getVar('title'));
                $modification_array['submitter_uname'] = XoopsUserUtility::getUnameFromId($modificationObj->getVar('submitter'));
                $modification_array['formatted_date']  = XoopsLocal::formatTimestamp($modificationObj->getVar('requestdate'), 'l');
                $downloadObj                           = $wfdownloads->getHandler('download')->get($modificationObj->getVar('lid'));
                $modification_array['download']        = $downloadObj->toArray();
                $GLOBALS['xoopsTpl']->append('modifications', $modification_array);
            }
            //Include page navigation
            include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $pagenav_modification = new XoopsPageNav(
                $modifications_count, $wfdownloads->getConfig(
                    'admin_perpage'
                ), $start_modification, 'start_modification'
            );
            $GLOBALS['xoopsTpl']->assign('modifications_pagenav', $pagenav_modification->renderNav());
        }

        $xoopsTpl->assign('use_mirrors', $wfdownloads->getConfig('enable_mirrors'));
        $xoopsTpl->assign('use_ratings', $wfdownloads->getConfig('enable_ratings'));
        $xoopsTpl->assign('use_reviews', $wfdownloads->getConfig('enable_reviews'));
        $xoopsTpl->assign('use_brokenreports', $wfdownloads->getConfig('enable_brokenreports'));

        $GLOBALS['xoopsTpl']->display("db:{$wfdownloads->getModule()->dirname()}_am_reportsmodificationslist.tpl");

        include_once __DIR__ . '/admin_footer.php';
        break;
}
