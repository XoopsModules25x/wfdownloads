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

$op = XoopsRequest::getString('op', 'reviews.list');
switch ($op) {
    case "review.delete":
        $review_id = XoopsRequest::getInt('review_id', 0);
        $ok        = XoopsRequest::getBool('ok', false, 'POST');
        if (!$reviewObj = $wfdownloads->getHandler('review')->get($review_id)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_REVIEWNOTFOUND);
            exit();
        }
        if ($ok === true) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($wfdownloads->getHandler('review')->delete($reviewObj)) {
                redirect_header($currentFile, 1, sprintf(_AM_WFDOWNLOADS_FILE_FILEWASDELETED, $reviewObj->getVar('title')));
                exit();
            } else {
                echo $reviewObj->getHtmlErrors();
                exit();
            }
        } else {
            wfdownloads_xoops_cp_header();
            xoops_confirm(
                array('op' => 'review.delete', 'review_id' => $review_id, 'ok' => true),
                $currentFile,
                _AM_WFDOWNLOADS_FILE_REALLYDELETEDTHIS . "<br /><br>" . $reviewObj->getVar('title'),
                _AM_WFDOWNLOADS_BDELETE
            );
            xoops_cp_footer();
        }
        break;

    case "review.approve":
        $review_id = XoopsRequest::getInt('review_id', 0);
        $ok        = XoopsRequest::getBool('ok', false, 'POST');
        if (!$reviewObj = $wfdownloads->getHandler('review')->get($review_id)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_REVIEWNOTFOUND);
            exit();
        }
        if ($ok === true) {
            $reviewObj->setVar('submit', 1); // true
            $wfdownloads->getHandler('review')->insert($reviewObj);
            redirect_header($currentFile, 1, sprintf(_AM_WFDOWNLOADS_REV_REVIEW_UPDATED, $reviewObj->getVar('title')));
            exit();
        } else {
            wfdownloads_xoops_cp_header();
            xoops_confirm(
                array('op' => 'review.approve', 'review_id' => $reviewObj->getVar('review_id'), 'ok' => true),
                $currentFile,
                _AM_WFDOWNLOADS_REVIEW_APPROVETHIS . "<br /><br>" . $reviewObj->getVar('title'),
                _AM_WFDOWNLOADS_REVIEW_APPROVETHIS
            );
            xoops_cp_footer();
        }
        break;

    case "review.edit":
        $review_id = XoopsRequest::getInt('review_id', 0);
        if (!$reviewObj = $wfdownloads->getHandler('review')->get($review_id)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_REVIEWNOTFOUND);
            exit();
        }
        wfdownloads_xoops_cp_header();
        $indexAdmin = new ModuleAdmin();
        echo $indexAdmin->addNavigation($currentFile);
        $sform = $reviewObj->getForm();
        $sform->display();
        xoops_cp_footer();
        break;

    case "review.save":
        $review_id = XoopsRequest::getInt('review_id', 0);
        if (!$reviewObj = $wfdownloads->getHandler('review')->get($review_id)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_REVIEWNOTFOUND);
            exit();
        }
        $reviewObj->setVar('title', trim($_POST['title']));
        $reviewObj->setVar('review', trim($_POST['review']));
        $reviewObj->setVar('rated', (int)$_POST['rated']);
        $reviewObj->setVar('submit', (int)$_POST['approve']);
        $wfdownloads->getHandler('review')->insert($reviewObj);
        redirect_header($currentFile, 1, _AM_WFDOWNLOADS_REV_REVIEW_UPDATED);
        exit();
        break;

    case "reviews.list":
    default:
        $start_waiting   = XoopsRequest::getInt('start_waiting', 0);
        $start_published = XoopsRequest::getInt('start_published', 0);

        $criteria_waiting = new Criteria('submit', 0); // false
        $waiting_count    = $wfdownloads->getHandler('review')->getCount($criteria_waiting);
        $criteria_waiting->setSort('date');
        $criteria_waiting->setOrder('DESC');
        $criteria_waiting->setLimit($wfdownloads->getConfig('admin_perpage'));
        $criteria_waiting->setStart($start_waiting);
        $reviews_waiting = $wfdownloads->getHandler('review')->getObjects($criteria_waiting);

        $criteria_published = new Criteria('submit', 1); // true
        $published_count    = $wfdownloads->getHandler('review')->getCount($criteria_published);
        $criteria_published->setSort('date');
        $criteria_published->setOrder('DESC');
        $criteria_published->setLimit($wfdownloads->getConfig('admin_perpage'));
        $criteria_published->setStart($start_published);
        $reviews_published = $wfdownloads->getHandler('review')->getObjects($criteria_published);

        wfdownloads_xoops_cp_header();
        $indexAdmin = new ModuleAdmin();
        echo $indexAdmin->addNavigation($currentFile);

        $GLOBALS['xoopsTpl']->assign('reviews_waiting_count', $waiting_count);
        $GLOBALS['xoopsTpl']->assign('reviews_published_count', $published_count);

        if ($waiting_count > 0) {
            foreach ($reviews_waiting as $review_waiting) {
                $lids_waiting[] = $review_waiting->getVar('lid');
                $uids_waiting[] = $review_waiting->getVar('uid');
            }
            if (isset($lids_waiting)) {
                $downloads = $wfdownloads->getHandler('download')->getObjects(
                    new Criteria('lid', '(' . implode(',', array_unique($lids_waiting)) . ')', 'IN'),
                    true,
                    false
                );
            }
            if (isset($uids_waiting)) {
                $users = $member_handler->getUserList(new Criteria('uid', '(' . implode(',', $uids_waiting) . ')'));
            }
            foreach ($reviews_waiting as $review_waiting) {
                $review_waiting_array                   = $review_waiting->toArray();
                $review_waiting_array['download_title'] = isset($downloads[$review_waiting->getVar('lid')]) ? $downloads[$review_waiting->getVar(
                    'lid'
                )]['title'] : '';
                $review_waiting_array['reviewer_uname'] = XoopsUserUtility::getUnameFromId($review_waiting->getVar('uid'));
                $reviewer                               = $member_handler->getUser($review_waiting->getVar('uid'));
                $review_waiting_array['reviewer_email'] = is_object($reviewer) ? $reviewer->email() : '';
                $review_waiting_array['formatted_date'] = XoopsLocal::formatTimestamp($review_waiting->getVar('date'), 'l');
                $GLOBALS['xoopsTpl']->append('reviews_waiting', $review_waiting_array);
            }
            //Include page navigation
            include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $pagenav_waiting = new XoopsPageNav($waiting_count, $wfdownloads->getConfig('admin_perpage'), $start_waiting, 'start_waiting');
            $GLOBALS['xoopsTpl']->assign('reviews_waiting_pagenav', $pagenav_waiting->renderNav());
        }

        if ($published_count > 0) {
            foreach ($reviews_published as $review_published) {
                $lids_published[] = $review_published->getVar('lid');
                $uids_published[] = $review_published->getVar('uid');
            }
            if (isset($lids_published)) {
                $downloads = $wfdownloads->getHandler('download')->getObjects(
                    new Criteria('lid', '(' . implode(',', array_unique($lids_published)) . ')', 'IN'),
                    true,
                    false
                );
            }
            if (isset($uids_published)) {
                $users = $member_handler->getUserList(new Criteria('uid', '(' . implode(',', $uids_published) . ')'));
            }
            foreach ($reviews_published as $review_published) {
                $review_published_array                   = $review_published->toArray();
                $review_published_array['download_title'] = isset($downloads[$review_published->getVar('lid')]) ? $downloads[$review_published->getVar('lid')]['title'] : '';
                $review_published_array['reviewer_uname'] = XoopsUserUtility::getUnameFromId($review_published->getVar('uid'));
                $reviewer                                 = $member_handler->getUser($review_published->getVar('uid'));
                $review_published_array['reviewer_email'] = is_object($reviewer) ? $reviewer->email() : '';
                $review_published_array['formatted_date'] = XoopsLocal::formatTimestamp($review_published->getVar('date'), 'l');
                $GLOBALS['xoopsTpl']->append('reviews_published', $review_published_array);
            }
            //Include page navigation
            include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $pagenav_published = new XoopsPageNav($published_count, $wfdownloads->getConfig('admin_perpage'), $start_published, 'start_published');
            $GLOBALS['xoopsTpl']->assign('reviews_published_pagenav', $pagenav_published->renderNav());
        }

        $xoopsTpl->assign('use_mirrors', $wfdownloads->getConfig('enable_mirrors'));
        $xoopsTpl->assign('use_ratings', $wfdownloads->getConfig('enable_ratings'));
        $xoopsTpl->assign('use_reviews', $wfdownloads->getConfig('enable_reviews'));
        $xoopsTpl->assign('use_brokenreports', $wfdownloads->getConfig('enable_brokenreports'));

        $GLOBALS['xoopsTpl']->display("db:{$wfdownloads->getModule()->dirname()}_am_reviewslist.tpl");

        include_once __DIR__ . '/admin_footer.php';
        break;
}
