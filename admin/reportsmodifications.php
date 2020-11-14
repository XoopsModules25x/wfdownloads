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
use Xmf\Request;
use XoopsModules\Wfdownloads;

$currentFile = basename(__FILE__);
require_once __DIR__ . '/admin_header.php';

/** @var \XoopsMemberHandler $memberHandler */
$memberHandler = xoops_getHandler('member');

$op = Request::getString('op', 'reports.modifications.list');
switch ($op) {
    case 'reports.update':
        $lid      = Request::getInt('lid', 0);
        $criteria = new \Criteria('lid', $lid);
        if (\Xmf\Request::hasVar('ack', 'GET')) {
            $acknowledged = (isset($_GET['ack']) && 0 == $_GET['ack']) ? 1 : 0;
            $helper->getHandler('Report')->updateAll('acknowledged', $acknowledged, $criteria, true);
            $update_mess = _AM_WFDOWNLOADS_BROKEN_NOWACK;
        }
        if (\Xmf\Request::hasVar('con', 'GET')) {
            $confirmed = (isset($_GET['con']) && 0 == $_GET['con']) ? 1 : 0;
            $helper->getHandler('Report')->updateAll('confirmed', $confirmed, $criteria, true);
            $update_mess = _AM_WFDOWNLOADS_BROKEN_NOWCON;
        }
        redirect_header($currentFile, 1, $update_mess);
        break;
    case 'report.delete':
        $lid        = Request::getInt('lid', 0);
        $criteria   = new \Criteria('lid', $lid);
        $reportObjs = $helper->getHandler('Report')->getObjects($criteria);
        if (isset($reportObjs[0])) {
            $helper->getHandler('Report')->delete($reportObjs[0], true);
        }
        $downloadObj = $helper->getHandler('Download')->get($lid);
        $helper->getHandler('Download')->delete($downloadObj, true);
        redirect_header($currentFile, 1, _AM_WFDOWNLOADS_BROKENFILEDELETED);
        break;
    case 'report.ignore':
        $lid        = Request::getInt('lid', 0);
        $criteria   = new \Criteria('lid', $lid);
        $reportObjs = $helper->getHandler('Report')->getObjects($criteria);
        if (isset($reportObjs[0])) {
            $helper->getHandler('Report')->delete($reportObjs[0], true);
        }
        redirect_header($currentFile, 1, _AM_WFDOWNLOADS_BROKEN_FILEIGNORED);
        break;
    case 'modification.show':
        $requestid = Request::getInt('requestid', 0);

        $modificationObj = $helper->getHandler('Modification')->get($requestid);
        $modify_user     = new \XoopsUser($modificationObj->getVar('modifysubmitter'));
        $modifyname      = \XoopsUserUtility::getUnameFromId((int)$modify_user->getVar('uid'));
        $modifyemail     = $modify_user->getVar('email');

        $downloadObj    = $helper->getHandler('Download')->get($modificationObj->getVar('lid'));
        $orig_user      = new \XoopsUser($downloadObj->getVar('submitter'));
        $submittername  = \XoopsUserUtility::getUnameFromId($downloadObj->getVar('submitter')); // $orig_user->getvar("uname");
        $submitteremail = $orig_user->getVar('email');

        $categoryObjs     = $helper->getHandler('Category')->getObjects();
        $categoryObjsTree = new Wfdownloads\ObjectTree($categoryObjs, 'cid', 'pid');

        Wfdownloads\Utility::getCpHeader();

        // IN PROGRESS
        // IN PROGRESS
        // IN PROGRESS
        // IN PROGRESS NEW FROM HERE

        echo '<div><b>' . _AM_WFDOWNLOADS_MOD_MODPOSTER . "</b> $submittername</div>";
        echo '<div><b>' . _AM_WFDOWNLOADS_MOD_MODIFYSUBMITTER . "</b> $modifyname</div>";

        $mcform = new Wfdownloads\MulticolumnsThemeForm('', 'modificationform', $currentFile);

        // Get download keys
        $downloadVars = $downloadObj->getVars();
        $downloadKeys = array_keys($downloadVars);
        // Get modification keys
        $modificationVars = $modificationObj->getVars();
        $modificationKeys = array_keys($modificationVars);
        // Get common keys
        $commonKeys = array_intersect($downloadKeys, $modificationKeys);
        // Set not allowed keys
        $notAllowedKeys = [
            'lid',
            'submitter',
            'publisher',
            'requestid',
            'forumid',
            'modifysubmitter',
            'paypalemail',
        ];

        $i = 0;
        $mcform->addElement(null, false, null, null);
        $mcform->setTitles(['', _AM_WFDOWNLOADS_MOD_ORIGINAL, _AM_WFDOWNLOADS_MOD_PROPOSED]);
        $i = 1;
        foreach ($commonKeys as $key) {
            if (in_array($key, $notAllowedKeys)) {
                continue;
            }
            $caption             = constant('_AM_WFDOWNLOADS_MOD_' . mb_strtoupper($key));
            $downloadContent     = $downloadObj->getVar($key);
            $modificationContent = $modificationObj->getVar($key);
            // Extra jobs for some keys
            switch ($key) {
                case 'title':
                case 'url':
                    // NOP
                    break;
                case 'size':
                    $downloadContent = Wfdownloads\Utility::bytesToSize1024($downloadContent);

                    $modificationContent = Wfdownloads\Utility::bytesToSize1024($modificationContent);
                    break;
                case 'date':
                case 'published':
                case 'expired':
                case 'updated':
                    $downloadContent = (false !== $downloadContent) ? XoopsLocal::formatTimestamp($downloadContent, 'l') : _NO;

                    $modificationContent = (false !== $modificationContent) ? XoopsLocal::formatTimestamp($modificationContent, 'l') : _NO;
                    break;
                case 'platform':
                case 'license':
                case 'limitations':
                case 'versiontypes':
                    $tempArray       = $helper->getConfig($key);
                    $downloadContent = isset($tempArray[$downloadObj->getVar($key)]) ? $tempArray[$downloadObj->getVar($key)] : '';

                    $modificationContent = isset($tempArray[$modificationObj->getVar($key)]) ? $tempArray[$modificationObj->getVar($key)] : '';
                    break;
                case 'cid':
                    $category_list = $helper->getHandler('Category')->getObjects(new \Criteria('cid', $downloadObj->getVar($key)));
                    if (!isset($category_list[0])) {
                        continue 2;
                    }
                    $downloadContent = $category_list[0]->getVar('title', 'e');

                    $category_list = $helper->getHandler('Category')->getObjects(new \Criteria('cid', $modificationObj->getVar($key)));
                    if (!isset($category_list[0])) {
                        continue 2;
                    }
                    $modificationContent = $category_list[0]->getVar('title', 'e');
                    break;
                case 'screenshot':
                case 'screenshot2':
                case 'screenshot3':
                case 'screenshot4':
                    if ('' != $downloadContent) {
                        $downloadContent = "<img src='" . XOOPS_URL . "/{$helper->getConfig('screenshots')}/{$downloadContent}' width='{$helper->getConfig('shotwidth')}' alt='' title=''>";
                    }

                    if ('' != $modificationContent) {
                        $modificationContent = "<img src='" . XOOPS_URL . "/{$helper->getConfig('screenshots')}/{$modificationContent}' width='{$helper->getConfig('shotwidth')}' alt='' title=''>";
                    }
                    break;
                case 'screenshots':
                    $downloadScreenshots     = $downloadContent;
                    $modificationScreenshots = $modificationContent;
                    unset($downloadContent, $modificationContent);
                    $downloadContent     = '';
                    $modificationContent = '';
                    foreach ($downloadScreenshots as $key => $value) {
                        $downloadScreenshot     = $downloadScreenshots[$key];
                        $modificationScreenshot = $modificationScreenshots[$key];
                        if ('' != $downloadScreenshot) {
                            $downloadContent += "<img src='" . XOOPS_URL . "/{$helper->getConfig('screenshots')}/{$downloadScreenshot}' width='{$helper->getConfig(
                                    'shotwidth'
                                )}' alt='' title=''>";
                        }

                        if ('' != $modificationContent) {
                            $modificationContent += "<img src='" . XOOPS_URL . "/{$helper->getConfig('screenshots')}/{$modificationScreenshot}' width='{$helper->getConfig(
                                    'shotwidth'
                                )}' alt='' title=''>";
                        }
                    }
                    break;
                case 'publisher':
                    $downloadContent = \XoopsUserUtility::getUnameFromId($downloadContent);

                    $modificationContent = \XoopsUserUtility::getUnameFromId($modificationContent);
                    break;
                case 'features':
                case 'requirements':
                    if ('' != $downloadContent) {
                        $downrequirements = explode('|', trim($downloadContent));
                        $downloadContent  = '<ul>';
                        foreach ($downrequirements as $bi) {
                            $downloadContent .= "<li>{$bi}</li>";
                        }
                        $downloadContent .= '</ul>';
                    }

                    if ('' != $modificationContent) {
                        $downrequirements    = explode('|', trim($modificationContent));
                        $modificationContent = '<ul>';
                        foreach ($downrequirements as $bi) {
                            $modificationContent .= "<li>{$bi}</li>";
                        }
                        $modificationContent .= '</ul>';
                    }
                    break;
                case 'dhistory':
                    $downloadContent = &$myts->displayTarea($downloadContent, true, false, false, false, true);

                    $modificationContent = &$myts->displayTarea($modificationContent, true, false, false, false, true);
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

                    $modificationContent = $modificationContent ? _YES : _NO;
            }
            $mcform->addElement($caption, false, $i, 0);
            if ($downloadContent != $modificationContent) {
                $modificationContent = "<span style='color:red;'>" . $modificationContent . '</span>';
            }
            $downloadFormElement     = new \XoopsFormLabel('', $downloadContent);
            $modificationFormElement = new \XoopsFormLabel('', $modificationContent);
            $mcform->addElement($downloadFormElement, false, $i, 1);
            $mcform->addElement($modificationFormElement, false, $i, 2);
            ++$i;
        }

        $buttonTray = new \XoopsFormElementTray('', '');
        $buttonTray->addElement(new \XoopsFormHidden('requestid', $requestid));
        $buttonTray->addElement(new \XoopsFormHidden('lid', (int)$modificationObj->getVar('lid')));
        $hidden = new \XoopsFormHidden('op', 'modification.change');
        $buttonTray->addElement($hidden);
        if (!$modificationObj->isNew()) {
            $approveButton = new \XoopsFormButton('', '', _AM_WFDOWNLOADS_BAPPROVE, 'submit');
            $approveButton->setExtra('onclick="this.form.elements.op.value=\'modification.change\'"');
            $buttonTray->addElement($approveButton);
        }
        $ignoreButton = new \XoopsFormButton('', '', _AM_WFDOWNLOADS_BIGNORE, 'submit');
        $ignoreButton->setExtra('onclick="this.form.elements.op.value=\'modification.ignore\'"');
        $buttonTray->addElement($ignoreButton);
        $buttonCancel = new \XoopsFormButton('', '', _CANCEL, 'button');
        $buttonCancel->setExtra('onclick="history.go(-1)"');
        $buttonTray->addElement($buttonCancel);
        $mcform->addElement($buttonTray, false, $i, 2);

        $mcform->display();

        xoops_cp_footer();
        exit();
        break;
    case 'modification.change':
        /* Added by Lankford on 2007/3/21 */
        // Get a pointer to the download record and the modification record, then compare their 'versions' to see if they are different.  If they are, then raise filemodify events.
        $requestid = Request::getInt('requestid', 0, 'POST');

        $modificationObj = $helper->getHandler('Modification')->get($requestid);
        $downloadObj     = $helper->getHandler('Download')->get($modificationObj->getVar('lid'));

        $raiseModifyEvents = true;
        if ($modificationObj->getVar('version') == $downloadObj->getVar('version')) {
            $raiseModifyEvents = false;
        }
        /* end add block */
        $helper->getHandler('Modification')->approveModification($_POST['requestid']);

        $cid = $downloadObj->getVar('cid');
        $lid = $downloadObj->getVar('lid');

        /* Added by lankford on 2007/3/21 */
        if ($raiseModifyEvents) {
            // Trigger the three events related to modified files (one for the file, category, and global event categories respectively)
            $tags                  = [];
            $tags['FILE_NAME']     = $downloadObj->getVar('title');
            $tags['FILE_URL']      = WFDOWNLOADS_URL . '/singlefile.php?cid=' . $cid . '&amp;lid=' . $lid;
            $category              = $helper->getHandler('Category')->get($cid);
            $tags['FILE_VERSION']  = $downloadObj->getVar('version');
            $tags['CATEGORY_NAME'] = $category->getVar('title');
            $tags['CATEGORY_URL']  = WFDOWNLOADS_URL . '/viewcat.php?cid=' . $cid;

            $notificationHandler->triggerEvent('global', 0, 'filemodified', $tags);
            $notificationHandler->triggerEvent('category', $cid, 'filemodified', $tags);
            $notificationHandler->triggerEvent('file', $lid, 'filemodified', $tags);
        }
        /* end add block */

        redirect_header($currentFile, 1, _AM_WFDOWNLOADS_MOD_REQUPDATED);
        break;
    case 'modification.ignore':
    case 'modification.delete':
        $requestid = Request::getInt('requestid', 0);
        $ok        = Request::getBool('ok', false, 'POST');
        if (!$modificationObj = $helper->getHandler('Modification')->get($requestid)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_MOD_NOTFOUND);
        }
        $title = $modificationObj->getVar('title');
        if (true === $ok) {
            /*
            IN PROGRESS
            IN PROGRESS
            IN PROGRESS
                        if (!$GLOBALS['xoopsSecurity']->check()) {
                            redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
                        }
            */
            if ($helper->getHandler('Modification')->deleteAll(new \Criteria('requestid', $requestid), true)) {
                redirect_header(WFDOWNLOADS_URL . '/admin/index.php', 1, _AM_WFDOWNLOADS_MOD_REQDELETED);
            } else {
                echo $modificationObj->getHtmlErrors();
            }
        } else {
            Wfdownloads\Utility::getCpHeader();
            xoops_confirm(['op' => 'modification.ignore', 'requestid' => $requestid, 'ok' => true, 'title' => $title], $currentFile, _AM_WFDOWNLOADS_MOD_REALLYIGNOREDTHIS . '<br><br>' . $title, _DELETE);
            xoops_cp_footer();
        }
        break;
    case 'reports.modifications.list':
    default:
        $start_report = Request::getInt('start_report', 0);

        $criteria      = new \CriteriaCompo();
        $reports_count = $helper->getHandler('Report')->getCount();
        $criteria->setSort('date');
        $criteria->setOrder('DESC');
        $criteria->setLimit($helper->getConfig('admin_perpage'));
        $criteria->setStart($start_report);
        $reportObjs = $helper->getHandler('Report')->getObjects($criteria);

        Wfdownloads\Utility::getCpHeader();
        $adminObject = Admin::getInstance();
        $adminObject->displayNavigation($currentFile);

        $GLOBALS['xoopsTpl']->assign('reports_count', $reports_count);

        if ($reports_count > 0) {
            foreach ($reportObjs as $reportObj) {
                $lids[] = $reportObj->getVar('lid');
                $uids[] = $reportObj->getVar('sender');
            }
            $downloadObjs = $helper->getHandler('Download')->getObjects(new \Criteria('lid', '(' . implode(',', array_unique($lids)) . ')', 'IN'), true);
            foreach (array_keys($downloadObjs) as $i) {
                $uids[] = $downloadObjs[$i]->getVar('submitter');
            }
            $users = $memberHandler->getUsers(new \Criteria('uid', '(' . implode(',', array_unique($uids)) . ')', 'IN'), true);

            foreach ($reportObjs as $reportObj) {
                $report_array = $reportObj->toArray();
                // Does the download exists ?
                if (isset($downloadObjs[$reportObj->getVar('lid')])) {
                    $downloadObj                     = $downloadObjs[$reportObj->getVar('lid')];
                    $report_array['download_lid']    = $downloadObj->getVar('lid');
                    $report_array['download_cid']    = $downloadObj->getVar('cid');
                    $report_array['download_title']  = $downloadObj->getVar('title');
                    $submitter                       = isset($users[$downloadObjs[$reportObj->getVar('lid')]->getVar('submitter')]) ? $users[$downloadObjs[$reportObj->getVar('lid')]->getVar('submitter')] : false;
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
                $report_array['formatted_date'] = formatTimestamp($reportObj->getVar('date'), 'l');
                $GLOBALS['xoopsTpl']->append('reports', $report_array);
            }
            //Include page navigation
            require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $pagenav_report = new \XoopsPageNav($reports_count, $helper->getConfig('admin_perpage'), $start_report, 'start_report');
            $GLOBALS['xoopsTpl']->assign('reports_pagenav', $pagenav_report->renderNav());
        }

        $start_modification = Request::getInt('start_modification', 0);

        $modifications_count = $helper->getHandler('Modification')->getCount();
        $criteria            = new \CriteriaCompo();
        $criteria->setLimit($helper->getConfig('admin_perpage'));
        $criteria->setStart($start_modification);
        $criteria->setSort('requestdate');
        $modificationObjs = $helper->getHandler('Modification')->getObjects($criteria);

        $GLOBALS['xoopsTpl']->assign('modifications_count', $modifications_count);

        if ($modifications_count > 0) {
            foreach ($modificationObjs as $modificationObj) {
                $modification_array                    = $modificationObj->toArray();
                $modification_array['title']           = $modificationObj->getVar('title');
                $modification_array['submitter_uname'] = \XoopsUserUtility::getUnameFromId($modificationObj->getVar('submitter'));
                $modification_array['formatted_date']  = formatTimestamp($modificationObj->getVar('requestdate'), 'l');
                $downloadObj                           = $helper->getHandler('Download')->get($modificationObj->getVar('lid'));
                $modification_array['download']        = $downloadObj->toArray();
                $GLOBALS['xoopsTpl']->append('modifications', $modification_array);
            }
            //Include page navigation
            require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $pagenav_modification = new \XoopsPageNav($modifications_count, $helper->getConfig('admin_perpage'), $start_modification, 'start_modification');
            $GLOBALS['xoopsTpl']->assign('modifications_pagenav', $pagenav_modification->renderNav());
        }

        $xoopsTpl->assign('use_mirrors', $helper->getConfig('enable_mirrors'));
        $xoopsTpl->assign('use_ratings', $helper->getConfig('enable_ratings'));
        $xoopsTpl->assign('use_reviews', $helper->getConfig('enable_reviews'));
        $xoopsTpl->assign('use_brokenreports', $helper->getConfig('enable_brokenreports'));

        $GLOBALS['xoopsTpl']->display("db:{$helper->getModule()->dirname()}_am_reportsmodificationslist.tpl");

        require_once __DIR__ . '/admin_footer.php';
        break;
}
