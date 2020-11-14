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

$op = Request::getString('op', 'mirrors.list');
switch ($op) {
    case 'mirror.delete':
        $mirror_id = Request::getInt('mirror_id', 0);
        $ok        = Request::getBool('ok', false, 'POST');
        if (!$mirrorObj = $helper->getHandler('Mirror')->get($mirror_id)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_MIRRORNOTFOUND);
        }
        if (true === $ok) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($helper->getHandler('Mirror')->delete($mirrorObj)) {
                redirect_header($currentFile, 1, sprintf(_AM_WFDOWNLOADS_FILE_FILEWASDELETED, $mirrorObj->getVar('title')));
            } else {
                echo $mirrorObj->getHtmlErrors();
                exit();
            }
        } else {
            Wfdownloads\Utility::getCpHeader();
            xoops_confirm(['op' => 'del_mirror', 'mirror_id' => $mirror_id, 'ok' => true], $currentFile, _AM_WFDOWNLOADS_FILE_REALLYDELETEDTHIS . '<br><br>' . $mirrorObj->getVar('title'), _AM_WFDOWNLOADS_BDELETE);
            xoops_cp_footer();
        }
        break;
    case 'mirror.approve':
        $mirror_id = Request::getInt('mirror_id', 0);
        $ok        = Request::getBool('ok', false, 'POST');
        if (!$mirrorObj = $helper->getHandler('Mirror')->get($mirror_id)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_MIRRORNOTFOUND);
        }
        if (true === $ok) {
            $mirrorObj->setVar('submit', true);
            $helper->getHandler('Mirror')->insert($mirrorObj);
            redirect_header($currentFile, 1, sprintf(_AM_WFDOWNLOADS_MIRROR_MIRROR_UPDATED, $mirrorObj->getVar('title')));
        } else {
            Wfdownloads\Utility::getCpHeader();
            xoops_confirm(['op' => 'mirror.approve', 'mirror_id' => $mirror_id, 'ok' => true], $currentFile, _AM_WFDOWNLOADS_MIRROR_APPROVETHIS . '<br><br>' . $mirrorObj->getVar('title'), _AM_WFDOWNLOADS_MIRROR_APPROVETHIS);
            xoops_cp_footer();
        }
        break;
    case 'mirror.edit':
        $mirror_id = Request::getInt('mirror_id', 0);
        if (!$mirrorObj = $helper->getHandler('Mirror')->get($mirror_id)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_MIRRORNOTFOUND);
        }
        Wfdownloads\Utility::getCpHeader();
        $sform = $mirrorObj->getForm();
        $sform->display();
        xoops_cp_footer();
        break;
    case 'mirror.save':
        $mirror_id = Request::getInt('mirror_id', 0);
        if (!$mirrorObj = $helper->getHandler('Mirror')->get($mirror_id)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_MIRRORNOTFOUND);
        }
        $mirrorObj->setVar('title', trim($_POST['title']));
        $mirrorObj->setVar('homeurl', formatURL(trim($_POST['homeurl'])));
        $mirrorObj->setVar('location', trim($_POST['location']));
        $mirrorObj->setVar('continent', trim($_POST['continent']));
        $mirrorObj->setVar('downurl', formatURL(trim($_POST['downurl'])));
        $mirrorObj->setVar('submit', \Xmf\Request::getInt('approve', 0, 'POST'));
        $helper->getHandler('Mirror')->insert($mirrorObj);
        redirect_header($currentFile, 1, _AM_WFDOWNLOADS_MIRROR_MIRROR_UPDATED);
        break;
    case 'mirrors.list':
    default:
        Wfdownloads\Utility::getCpHeader();
        $adminObject = Admin::getInstance();
        $adminObject->displayNavigation($currentFile);

        $start_waiting   = Request::getInt('start_waiting', 0);
        $start_published = Request::getInt('start_published', 0);

        $criteria_waiting = new \Criteria('submit', 0); // false
        $waiting_count    = $helper->getHandler('Mirror')->getCount($criteria_waiting);
        $criteria_waiting->setSort('date');
        $criteria_waiting->setOrder('DESC');
        $criteria_waiting->setLimit($helper->getConfig('admin_perpage'));
        $criteria_waiting->setStart($start_waiting);
        $mirrors_waiting = $helper->getHandler('Mirror')->getObjects($criteria_waiting);

        $criteria_published = new \Criteria('submit', 1); // true
        $published_count    = $helper->getHandler('Mirror')->getCount($criteria_published);
        $criteria_published->setSort('date');
        $criteria_published->setOrder('DESC');
        $criteria_published->setLimit($helper->getConfig('admin_perpage'));
        $criteria_published->setStart($start_published);
        $mirrors_published = $helper->getHandler('Mirror')->getObjects($criteria_published);

        $GLOBALS['xoopsTpl']->assign('mirrors_waiting_count', $waiting_count);
        $GLOBALS['xoopsTpl']->assign('mirrors_published_count', $published_count);

        if ($waiting_count > 0) {
            foreach ($mirrors_waiting as $mirror_waiting) {
                $lids_waiting[] = $mirror_waiting->getVar('lid');
                $uids_waiting[] = $mirror_waiting->getVar('uid');
            }
            $downloads = $helper->getHandler('Download')->getObjects(new \Criteria('lid', '(' . implode(',', array_unique($lids_waiting)) . ')', 'IN'), true, false);
            $users     = $memberHandler->getUserList(new \Criteria('uid', '(' . implode(',', $uids_waiting) . ')'));
            foreach ($mirrors_waiting as $mirror_waiting) {
                $mirror_waiting_array                    = $mirror_waiting->toArray();
                $mirror_waiting_array['download_title']  = isset($downloads[$mirror_waiting->getVar('lid')]) ? $downloads[$mirror_waiting->getVar('lid')]['title'] : '';
                $mirror_waiting_array['submitter_uname'] = \XoopsUserUtility::getUnameFromId($mirror_waiting->getVar('uid'));
                $mirror_waiting_array['formatted_date']  = formatTimestamp($mirror_waiting->getVar('date'), 'l');
                $GLOBALS['xoopsTpl']->append('mirrors_waiting', $mirror_waiting_array);
            }
            //Include page navigation
            require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $pagenav_waiting = new \XoopsPageNav($waiting_count, $helper->getConfig('admin_perpage'), $start_waiting, 'start_waiting');
            $GLOBALS['xoopsTpl']->assign('mirrors_waiting_pagenav', $pagenav_waiting->renderNav());
        }

        if ($published_count > 0) {
            foreach ($mirrors_published as $mirror_published) {
                $lids_published[] = $mirror_published->getVar('lid');
                $uids_published[] = $mirror_published->getVar('uid');
            }
            $downloads = $helper->getHandler('Download')->getObjects(new \Criteria('lid', '(' . implode(',', array_unique($lids_published)) . ')', 'IN'), true, false);
            $users     = $memberHandler->getUserList(new \Criteria('uid', '(' . implode(',', $uids_published) . ')'));
            foreach ($mirrors_published as $mirror_published) {
                $mirror_published_array                    = $mirror_published->toArray();
                $mirror_published_array['download_title']  = isset($downloads[$mirror_published->getVar('lid')]) ? $downloads[$mirror_published->getVar('lid')]['title'] : '';
                $mirror_published_array['submitter_uname'] = \XoopsUserUtility::getUnameFromId($mirror_published->getVar('uid'));
                $mirror_published_array['formatted_date']  = formatTimestamp($mirror_published->getVar('date'), 'l');
                $GLOBALS['xoopsTpl']->append('mirrors_published', $mirror_published_array);
            }
            //Include page navigation
            require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $pagenav_published = new \XoopsPageNav($published_count, $helper->getConfig('admin_perpage'), $start_published, 'start_published');
            $GLOBALS['xoopsTpl']->assign('mirrors_published_pagenav', $pagenav_published->renderNav());
        }

        $xoopsTpl->assign('use_mirrors', $helper->getConfig('enable_mirrors'));
        $xoopsTpl->assign('use_ratings', $helper->getConfig('enable_ratings'));
        $xoopsTpl->assign('use_reviews', $helper->getConfig('enable_reviews'));
        $xoopsTpl->assign('use_brokenreports', $helper->getConfig('enable_brokenreports'));

        $GLOBALS['xoopsTpl']->display("db:{$helper->getModule()->dirname()}_am_mirrorslist.tpl");

        require_once __DIR__ . '/admin_footer.php';
        break;
}
