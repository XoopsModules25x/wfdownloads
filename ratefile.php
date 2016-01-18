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
include_once __DIR__ . '/header.php';

$lid         = XoopsRequest::getInt('lid', 0);
$downloadObj = $wfdownloads->getHandler('download')->get($lid);
if (empty($downloadObj)) {
    redirect_header('index.php', 3, _CO_WFDOWNLOADS_ERROR_NODOWNLOAD);
}
$cid         = XoopsRequest::getInt('cid', $downloadObj->getVar('cid'));
$categoryObj = $wfdownloads->getHandler('category')->get($cid);
if (empty($categoryObj)) {
    redirect_header('index.php', 3, _CO_WFDOWNLOADS_ERROR_NOCATEGORY);
}

// Download not published, expired or taken offline - redirect
if ($downloadObj->getVar('published') == false
    || $downloadObj->getVar('published') > time()
    || $downloadObj->getVar('offline') == true
    || ($downloadObj->getVar('expired') != 0 && $downloadObj->getVar('expired') < time())
) {
    redirect_header("index.php", 3, _MD_WFDOWNLOADS_NODOWNLOAD);
}

// Check permissions
if ($wfdownloads->getConfig('enable_ratings') == false && !wfdownloads_userIsAdmin()) {
    redirect_header('index.php', 3, _NOPERM);
}
// Breadcrumb
include_once XOOPS_ROOT_PATH . '/class/tree.php';
$categoryObjsTree = new XoopsObjectTree($wfdownloads->getHandler('category')->getObjects(), 'cid', 'pid');
$breadcrumb       = new WfdownloadsBreadcrumb();
$breadcrumb->addLink($wfdownloads->getModule()->getVar('name'), WFDOWNLOADS_URL);
foreach (array_reverse($categoryObjsTree->getAllParent($cid)) as $parentCategory) {
    $breadcrumb->addLink($parentCategory->getVar('title'), "viewcat.php?cid=" . $parentCategory->getVar('cid'));
}
$breadcrumb->addLink($categoryObj->getVar('title'), "viewcat.php?cid={$cid}");
$breadcrumb->addLink($downloadObj->getVar('title'), "singlefile.php?lid={$lid}");

$op = XoopsRequest::getString('op', 'vote.add');
switch ($op) {
    case 'vote.add':
    default:
        // Get vote poster 'uid'
        $ratinguserUid = is_object($GLOBALS['xoopsUser']) ? (int)$GLOBALS['xoopsUser']->getVar('uid') : 0;
        $ratinguserIp  = getenv('REMOTE_ADDR');

        if (!empty($_POST['submit'])) {
            $rating = XoopsRequest::getString('rating', '--', 'POST');

            // Check if Rating is Null
            if ($rating == '--') {
                redirect_header("?cid={$cid}&amp;lid={$lid}", 4, _MD_WFDOWNLOADS_NORATING);
                exit();
            }
            if ($ratinguserUid != 0) {
                // Check if Download POSTER is voting (UNLESS Anonymous users allowed to post)
                if ($downloadObj->getVar('submitter') == $ratinguserUid) {
                    redirect_header(WFDOWNLOADS_URL . "/singlefile.php?cid={$cid}&amp;lid={$lid}", 4, _MD_WFDOWNLOADS_CANTVOTEOWN);
                    exit();
                }
                // Check if REG user is trying to vote twice.
                $criteria = new CriteriaCompo(new Criteria('lid', $lid));
                $criteria->add(new Criteria('ratinguser', $ratinguserUid));
                $ratingsCount = $wfdownloads->getHandler('rating')->getCount($criteria);
                if ($ratingsCount > 0) {
                    redirect_header("singlefile.php?cid={$cid}&amp;lid={$lid}", 4, _MD_WFDOWNLOADS_VOTEONCE);
                    exit();
                }
            } else {
                // Check if ANONYMOUS user is trying to vote more than once per day (only 1 anonymous from an IP in a single day).
                $anonymousWaitDays = 1;
                $yesterday         = (time() - (86400 * $anonymousWaitDays));
                $criteria          = new CriteriaCompo(new Criteria('lid', $lid));
                $criteria->add(new Criteria('ratinguser', 0));
                $criteria->add(new Criteria('ratinghostname', $ratinguserIp));
                $criteria->add(new Criteria('ratingtimestamp', $yesterday, '>'));
                $anonymousVotesCount = $wfdownloads->getHandler('rating')->getCount($criteria);
                if ($anonymousVotesCount > 0) {
                    redirect_header("singlefile.php?cid={$cid}&amp;lid={$lid}", 4, _MD_WFDOWNLOADS_VOTEONCE);
                    exit();
                }
            }
            // All is well. Add to Line Item Rate to DB.
            $ratingObj = $wfdownloads->getHandler('rating')->create();
            $ratingObj->setVar('lid', $lid);
            $ratingObj->setVar('ratinguser', $ratinguserUid);
            $ratingObj->setVar('rating', (int)$rating);
            $ratingObj->setVar('ratinghostname', $ratinguserIp);
            $ratingObj->setVar('ratingtimestamp', time());
            if ($wfdownloads->getHandler('rating')->insert($ratingObj)) {
                // All is well. Calculate Score & Add to Summary (for quick retrieval & sorting) to DB.
                wfdownloads_updateRating($lid);
                $thankyouMessage = _MD_WFDOWNLOADS_VOTEAPPRE . "<br />" . sprintf(_MD_WFDOWNLOADS_THANKYOU, $GLOBALS['xoopsConfig']['sitename']);
                redirect_header("singlefile.php?cid={$cid}&amp;lid={$lid}", 4, $thankyouMessage);
            } else {
                echo $ratingObj->getHtmlErrors();
            }
        } else {
            $xoopsOption['template_main'] = "{$wfdownloads->getModule()->dirname()}_ratefile.tpl";
            include_once XOOPS_ROOT_PATH . '/header.php';

            $xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
            $xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
            $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
            $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

            $xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

            // Breadcrumb
            $breadcrumb->addLink(_MD_WFDOWNLOADS_RATETHISFILE, '');
            $xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

            // Generate form
            include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
            $sform         = new XoopsThemeForm(_MD_WFDOWNLOADS_RATETHISFILE, 'voteform', xoops_getenv('PHP_SELF'));
            $rating_select = new XoopsFormSelect(_MD_WFDOWNLOADS_REV_RATING, 'rating', '10');
            //$rating_select->setDescription(_MD_WFDOWNLOADS_REV_RATING_DESC);
            $rating_select->addOptionArray(
                array(
                    '1'  => 1,
                    '2'  => 2,
                    '3'  => 3,
                    '4'  => 4,
                    '5'  => 5,
                    '6'  => 6,
                    '7'  => 7,
                    '8'  => 8,
                    '9'  => 9,
                    '10' => 10
                )
            );
            $sform->addElement($rating_select);
            $sform->addElement(new XoopsFormHidden('lid', $lid));
            $sform->addElement(new XoopsFormHidden('cid', $cid));
            $sform->addElement(new XoopsFormHidden('uid', $reviewerUid));
            $button_tray   = new XoopsFormElementTray('', '');
            $submit_button = new XoopsFormButton('', 'submit', _MD_WFDOWNLOADS_RATEIT, 'submit');
            $button_tray->addElement($submit_button);
            $cancel_button = new XoopsFormButton('', '', _CANCEL, 'button');
            $cancel_button->setExtra('onclick="history.go(-1)"');
            $button_tray->addElement($cancel_button);
            $sform->addElement($button_tray);
            $xoopsTpl->assign('voteform', $sform->render());
            $xoopsTpl->assign(
                'download',
                array('lid' => $lid, 'cid' => $cid, 'title' => $downloadObj->getVar('title'), 'description' => $downloadObj->getVar('description'))
            );

            $xoopsTpl->assign(
                'file',
                array('id' => $lid, 'lid' => $lid, 'cid' => $cid, 'title' => $downloadObj->getVar('title'), 'imageheader' => wfdownloads_headerImage())
            ); // this definition is not removed for backward compatibility issues
            include_once __DIR__ . '/footer.php';
        }
        break;
}
