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

use Xmf\Request;
use XoopsModules\Wfdownloads\{
    Common,
    Helper,
    Utility,
    ObjectTree
};
/** @var Helper $helper */
/** @var Utility $utility */

$currentFile = basename(__FILE__);
require_once __DIR__ . '/header.php';

$lid         = Request::getInt('lid', 0);
$downloadObj = $helper->getHandler('Download')->get($lid);
if (null === $downloadObj) {
    redirect_header('index.php', 3, _CO_WFDOWNLOADS_ERROR_NODOWNLOAD);
}
$cid         = Request::getInt('cid', $downloadObj->getVar('cid'));
$categoryObj = $helper->getHandler('Category')->get($cid);
if (null === $categoryObj) {
    redirect_header('index.php', 3, _CO_WFDOWNLOADS_ERROR_NOCATEGORY);
}

// Download not published, expired or taken offline - redirect
if (0 == $downloadObj->getVar('published') || $downloadObj->getVar('published') > time()
    || true === $downloadObj->getVar('offline')
    || (0 != $downloadObj->getVar('expired')
        && $downloadObj->getVar('expired') < time())
    || _WFDOWNLOADS_STATUS_WAITING == $downloadObj->getVar('status')) {
    redirect_header('index.php', 3, _MD_WFDOWNLOADS_NODOWNLOAD);
}

// Check permissions
if (false === $helper->getConfig('enable_reviews') && !Utility::userIsAdmin()) {
    redirect_header('index.php', 3, _NOPERM);
}
$userGroups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : [0 => XOOPS_GROUP_ANONYMOUS];
if (!$grouppermHandler->checkRight('WFDownCatPerm', $cid, $userGroups, $helper->getModule()->mid())) {
    redirect_header('index.php', 3, _NOPERM);
}

// Breadcrumb
require_once XOOPS_ROOT_PATH . '/class/tree.php';
$categoryObjsTree = new ObjectTree($helper->getHandler('Category')->getObjects(), 'cid', 'pid');
$breadcrumb       = new Common\Breadcrumb();
$breadcrumb->addLink($helper->getModule()->getVar('name'), WFDOWNLOADS_URL);
foreach (array_reverse($categoryObjsTree->getAllParent($cid)) as $parentCategory) {
    $breadcrumb->addLink($parentCategory->getVar('title'), 'viewcat.php?cid=' . $parentCategory->getVar('cid'));
}
$breadcrumb->addLink($categoryObj->getVar('title'), "viewcat.php?cid={$cid}");
$breadcrumb->addLink($downloadObj->getVar('title'), "singlefile.php?lid={$lid}");

$op = Request::getString('op', 'review.add');
switch ($op) {
    case 'reviews.list':
    case 'list': // this care is not removed for backward compatibility issues
        $start = Request::getInt('start', 0);

        $GLOBALS['xoopsOption']['template_main'] = "{$helper->getModule()->dirname()}_reviews.tpl";
        require_once XOOPS_ROOT_PATH . '/header.php';

        $xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
        $xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
        $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
        $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

        $xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

        // Generate content header
        $sql                     = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('wfdownloads_indexpage') . ' ';
        $head_arr                = $GLOBALS['xoopsDB']->fetchArray($GLOBALS['xoopsDB']->query($sql));
        $catarray['imageheader'] = Utility::headerImage();
        $xoopsTpl->assign('catarray', $catarray);
        $xoopsTpl->assign('category_path', $helper->getHandler('Category')->getNicePath($cid));
        $xoopsTpl->assign('category_id', $cid);

        // Breadcrumb
        $breadcrumb->addLink(_CO_WFDOWNLOADS_REVIEWS_LIST, '');
        $xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

        // Count reviews
        $criteria = new CriteriaCompo(new Criteria('lid', $lid));
        $criteria->add(new Criteria('submit', 1));
        $reviewsCount = $helper->getHandler('Review')->getCount($criteria);

        // Get reviews
        $criteria->setSort('date');
        $criteria->setLimit(5);
        $criteria->setStart($start);
        $reviewObjs = $helper->getHandler('Review')->getObjects($criteria);

        $download_array = $downloadObj->toArray();
        $xoopsTpl->assign('down_arr', $download_array);

        foreach ($reviewObjs as $reviewObj) {
            $review_array              = $reviewObj->toArray();
            $review_array['date']      = formatTimestamp($review_array['date'], $helper->getConfig('dateformat'));
            $review_array['submitter'] = XoopsUserUtility::getUnameFromId($review_array['uid']);
            $review_rating             = round(number_format($review_array['rated'], 0) / 2);
            $review_array['rated_img'] = "rate{$review_rating}.gif";
            $xoopsTpl->append('down_review', $review_array);
        }
        $xoopsTpl->assign('lang_review_found', sprintf(_MD_WFDOWNLOADS_REVIEWTOTAL, $reviewsCount));

        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav          = new XoopsPageNav($reviewsCount, 5, $start, 'start', "op=reviews.list&amp;cid={$cid}&amp;lid={$lid}", 1);
        $navbar['navbar'] = $pagenav->renderNav();
        $xoopsTpl->assign('navbar', $navbar);

        $xoopsTpl->assign('categoryPath', $pathstring . ' > ' . $download_array['title']);
        $xoopsTpl->assign('module_home', Utility::moduleHome(true));

        require_once __DIR__ . '/footer.php';
        break;
    case 'review.add':
    default:
        // Check if ANONYMOUS user can review
        if (!is_object($GLOBALS['xoopsUser']) && !$helper->getConfig('rev_anonpost')) {
            redirect_header(XOOPS_URL . '/user.php', 1, _MD_WFDOWNLOADS_MUSTREGFIRST);
        }

        // Get review poster 'uid'
        $reviewerUid = is_object($GLOBALS['xoopsUser']) ? (int)$GLOBALS['xoopsUser']->getVar('uid') : 0;

        if (Request::hasVar('submit', 'POST')) {
            $reviewObj = $helper->getHandler('Review')->create();
            $reviewObj->setVar('title', trim($_POST['title']));
            $reviewObj->setVar('review', trim($_POST['review']));
            $reviewObj->setVar('lid', Request::getInt('lid', 0, 'POST'));
            $reviewObj->setVar('rated', Request::getInt('rated', 0, 'POST'));
            $reviewObj->setVar('date', time());
            $reviewObj->setVar('uid', $reviewerUid);
            $submit = (Utility::userIsAdmin() ?: $helper->getConfig('rev_approve') ? true : false);
            $reviewObj->setVar('submit', $submit);

            if (!$helper->getHandler('Review')->insert($reviewObj)) {
                redirect_header('index.php', 3, _MD_WFDOWNLOADS_ERROR_CREATEREVIEW);
            } else {
                $databaseMessage = $submit ? _MD_WFDOWNLOADS_ISAPPROVED : _MD_WFDOWNLOADS_ISNOTAPPROVED;
                redirect_header('index.php', 2, $databaseMessage);
            }
        } else {
            require_once XOOPS_ROOT_PATH . '/header.php';

            $xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
            $xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
            $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
            $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

            $xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

            // Breadcrumb
            $breadcrumb->addLink(_MD_WFDOWNLOADS_REVIEWTHISFILE, '');
            echo $breadcrumb->render();

            echo "<div align='center'>" . Utility::headerImage() . "</div><br>\n";
            echo '<div>' . _MD_WFDOWNLOADS_REV_SNEWMNAMEDESC . "</div>\n";

            // Generate form
            require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
            $sform      = new XoopsThemeForm(_MD_WFDOWNLOADS_REV_SUBMITREV, 'reviewform', xoops_getenv('SCRIPT_NAME'), 'post', true);
            $title_text = new XoopsFormText(_MD_WFDOWNLOADS_REV_TITLE, 'title', 50, 255);
            //$title_text->setDescription(_MD_WFDOWNLOADS_REV_TITLE_DESC);
            $sform->addElement($title_text, true);
            $rating_select = new XoopsFormSelect(_MD_WFDOWNLOADS_REV_RATING, 'rated', '10');
            //$rating_select->setDescription(_MD_WFDOWNLOADS_REV_RATING_DESC);
            $rating_select->addOptionArray(
                [
                    '1'  => 1,
                    '2'  => 2,
                    '3'  => 3,
                    '4'  => 4,
                    '5'  => 5,
                    '6'  => 6,
                    '7'  => 7,
                    '8'  => 8,
                    '9'  => 9,
                    '10' => 10,
                ]
            );
            $sform->addElement($rating_select);
            $review_textarea = new XoopsFormDhtmlTextArea(_MD_WFDOWNLOADS_REV_DESCRIPTION, 'review', '', 15, 60);
            //$review_textarea->setDescription(_MD_WFDOWNLOADS_REV_DESCRIPTION_DESC);
            $sform->addElement($review_textarea, true);
            $sform->addElement(new XoopsFormHidden('lid', $lid));
            $sform->addElement(new XoopsFormHidden('cid', $cid));
            $sform->addElement(new XoopsFormHidden('uid', $reviewerUid));
            $buttonTray   = new XoopsFormElementTray('', '');
            $submitButton = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
            $buttonTray->addElement($submitButton);
            $cancelButton = new XoopsFormButton('', '', _CANCEL, 'button');
            $cancelButton->setExtra('onclick="history.go(-1)"');
            $buttonTray->addElement($cancelButton);
            $sform->addElement($buttonTray);
            $sform->display();
            require_once __DIR__ . '/footer.php';
        }
}
