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

use Xmf\Request;
use Xoopsmodules\wfdownloads;

$currentFile = basename(__FILE__);
require_once __DIR__ . '/admin_header.php';

$op = Request::getString('op', 'votes.list');
switch ($op) {
    case 'vote.delete':
        $rid = Request::getInt('rid', 0);
        $lid = Request::getInt('lid', 0);
        $helper->getHandler('rating')->deleteAll(new \Criteria('ratingid', $rid), true);
        wfdownloads\Utility::updateRating($lid);
        redirect_header($currentFile, 1, _AM_WFDOWNLOADS_VOTEDELETED);
        break;

    case 'votes.list':
    default:
        $start         = Request::getInt('start', 0);
        $useravgrating = '0';
        $uservotes     = '0';

        $criteria      = new \CriteriaCompo();
        $votes         = $helper->getHandler('rating')->getCount($criteria);
        $ratings_count = $helper->getHandler('rating')->getCount($criteria);
        $criteria->setSort('ratingtimestamp');
        $criteria->setOrder('DESC');
        $criteria->setStart($start);
        $criteria->setLimit(20);
        $ratingObjs = $helper->getHandler('rating')->getObjects($criteria);

        $useravgrating = $helper->getHandler('rating')->getUserAverage();
        $useravgrating = number_format($useravgrating['avg'], 2);

        wfdownloads\Utility::getCpHeader();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation($currentFile);

        $GLOBALS['xoopsTpl']->assign('votes', $votes);
        $GLOBALS['xoopsTpl']->assign('ratings_count', $ratings_count);
        $GLOBALS['xoopsTpl']->assign('useravgrating', $useravgrating);
        if ($ratings_count > 0) {
            foreach ($ratingObjs as $ratingObj) {
                $lids[] = $ratingObj->getVar('lid');
            }
            $downloads = $helper->getHandler('download')->getObjects(new \Criteria('lid', '(' . implode(',', array_unique($lids)) . ')', 'IN'), true);
            foreach ($ratingObjs as $ratingObj) {
                $rating_array                    = $ratingObj->toArray();
                $rating_array['formatted_date']  = \XoopsLocal::formatTimestamp($ratingObj->getVar('ratingtimestamp'), 'l');
                $rating_array['submitter_uname'] = \XoopsUser::getUnameFromId($ratingObj->getVar('ratinguser'));
                $rating_array['submitter_uid']   = $ratingObj->getVar('ratinguser');
                $rating_array['download_title']  = $downloads[$ratingObj->getVar('lid')]->getVar('title');
                $GLOBALS['xoopsTpl']->append('ratings', $rating_array);
            }
        }
        //Include page navigation
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $ratings_pagenav = new \XoopsPageNav($ratings_count, $helper->getConfig('admin_perpage'), $start, 'start');
        $GLOBALS['xoopsTpl']->assign('ratings_pagenav', $ratings_pagenav->renderNav());

        $xoopsTpl->assign('use_mirrors', $helper->getConfig('enable_mirrors'));
        $xoopsTpl->assign('use_ratings', $helper->getConfig('enable_ratings'));
        $xoopsTpl->assign('use_reviews', $helper->getConfig('enable_reviews'));
        $xoopsTpl->assign('use_brokenreports', $helper->getConfig('enable_brokenreports'));

        $GLOBALS['xoopsTpl']->display("db:{$helper->getModule()->dirname()}_am_ratingslist.tpl");

        require_once __DIR__ . '/admin_footer.php';
        break;
}
