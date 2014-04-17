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
include 'header.php';

$lid      = WfdownloadsRequest::getInt('lid', 0);
$download = $wfdownloads->getHandler('download')->get($lid);
if (empty($download)) {
    redirect_header('index.php', 3, _CO_WFDOWNLOADS_ERROR_NODOWNLOAD);
}
$cid      = WfdownloadsRequest::getInt('cid', $download->getVar('cid'));
$category = $wfdownloads->getHandler('category')->get($cid);
if (empty($category)) {
    redirect_header('index.php', 3, _CO_WFDOWNLOADS_ERROR_NOCATEGORY);
}

// Download not published, expired or taken offline - redirect
if (
    $download->getVar('published') == 0 ||
    $download->getVar('published') > time() ||
    $download->getVar('offline') == true ||
    ($download->getVar('expired') != 0 && $download->getVar('expired') < time()) ||
    $download->getVar('status') == _WFDOWNLOADS_STATUS_WAITING) {
    redirect_header('index.php', 3, _MD_WFDOWNLOADS_NODOWNLOAD);
}

// Check permissions
if ($wfdownloads->getConfig('enable_reviews') == false && !wfdownloads_userIsAdmin()) {
    redirect_header('index.php', 3, _NOPERM);
}
$userGroups = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);
if (!$gperm_handler->checkRight('WFDownCatPerm', $cid, $userGroups, $wfdownloads->getModule()->mid())) {
    redirect_header('index.php', 3, _NOPERM);
}

// Breadcrumb
include_once XOOPS_ROOT_PATH . "/class/tree.php";
$categoriesTree = new XoopsObjectTree($wfdownloads->getHandler('category')->getObjects(), 'cid', 'pid');
$breadcrumb     = new WfdownloadsBreadcrumb();
$breadcrumb->addLink($wfdownloads->getModule()->getVar('name'), WFDOWNLOADS_URL);
foreach (array_reverse($categoriesTree->getAllParent($cid)) as $parentCategory) {
    $breadcrumb->addLink($parentCategory->getVar('title'), "viewcat.php?cid=" . $parentCategory->getVar('cid'));
}
$breadcrumb->addLink($category->getVar('title'), "viewcat.php?cid={$cid}");
$breadcrumb->addLink($download->getVar('title'), "singlefile.php?lid={$lid}");

$op = WfdownloadsRequest::getString('op', 'review.add');
switch ($op) {
    case "reviews.list" :
    case "list" : // this care is not removed for backward compatibility issues
        $start = WfdownloadsRequest::getInt('start', 0);

        $xoopsOption['template_main'] = "{$wfdownloads->getModule()->dirname()}_reviews.html";
        include XOOPS_ROOT_PATH . '/header.php';

        $xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
        $xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
        $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
        $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

        $xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

        // Generate content header
        $sql                     = "SELECT * FROM " . $xoopsDB->prefix('wfdownloads_indexpage') . " ";
        $head_arr                = $xoopsDB->fetchArray($xoopsDB->query($sql));
        $catarray['imageheader'] = wfdownloads_headerImage();
        $xoopsTpl->assign('catarray', $catarray);
        $xoopsTpl->assign('category_path', $wfdownloads->getHandler('category')->getNicePath($cid));
        $xoopsTpl->assign('category_id', $cid);

        // Breadcrumb
        $breadcrumb->addLink(_CO_WFDOWNLOADS_REVIEWS_LIST, '');
        $xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

        // Count reviews
        $criteria = new CriteriaCompo(new Criteria('lid', $lid));
        $criteria->add(new Criteria('submit', 1));
        $reviewsCount = $wfdownloads->getHandler('review')->getCount($criteria);

        // Get reviews
        $criteria->setSort('date');
        $criteria->setLimit(5);
        $criteria->setStart($start);
        $reviews = $wfdownloads->getHandler('review')->getObjects($criteria);

        $download_array = $download->toArray();
        $xoopsTpl->assign('down_arr', $download_array);

        foreach ($reviews as $review) {
            $review_array              = $review->toArray();
            $review_array['date']      = formatTimestamp($review_array['date'], $wfdownloads->getConfig('dateformat'));
            $review_array['submitter'] = XoopsUserUtility::getUnameFromId($review_array['uid']);
            $review_rating             = round(number_format($review_array['rated'], 0) / 2);
            $review_array['rated_img'] = "rate{$review_rating}.gif";
            $xoopsTpl->append('down_review', $review_array);
        }
        $xoopsTpl->assign('lang_review_found', sprintf(_MD_WFDOWNLOADS_REVIEWTOTAL, $reviewsCount));

        include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav          = new XoopsPageNav($reviewsCount, 5, $start, 'start', "op=reviews.list&amp;cid={$cid}&amp;lid={$lid}", 1);
        $navbar['navbar'] = $pagenav->renderNav();
        $xoopsTpl->assign('navbar', $navbar);

        $xoopsTpl->assign('categoryPath', $pathstring . " > " . $download_array['title']);
        $xoopsTpl->assign('module_home', wfdownloads_module_home(true));

        include 'footer.php';
        break;

    case "review.add" :
    default :
        // Check if ANONYMOUS user can review
        if (!is_object($xoopsUser) && !$wfdownloads->getConfig('rev_anonpost')) {
            redirect_header(XOOPS_URL . '/user.php', 1, _MD_WFDOWNLOADS_MUSTREGFIRST);
            exit();
        }

        // Get review poster 'uid'
        $reviewerUid = is_object($xoopsUser) ? (int) $xoopsUser->getVar('uid') : 0;

        if (!empty($_POST['submit'])) {
            $review = $wfdownloads->getHandler('review')->create();
            $review->setVar('title', trim($_POST['title']));
            $review->setVar('review', trim($_POST['review']));
            $review->setVar('lid', (int) $_POST['lid']);
            $review->setVar('rated', (int) $_POST['rated']);
            $review->setVar('date', time());
            $review->setVar('uid', $reviewerUid);
            $submit = (wfdownloads_userIsAdmin() ? wfdownloads_userIsAdmin() : ($wfdownloads->getConfig('rev_approve')) ? true : false);
            $review->setVar('submit', $submit);

            if (!$wfdownloads->getHandler('review')->insert($review)) {
                redirect_header('index.php', 3, _MD_WFDOWNLOADS_ERROR_CREATEREVIEW);
            } else {
                $databaseMessage = ($submit) ? _MD_WFDOWNLOADS_ISAPPROVED : _MD_WFDOWNLOADS_ISNOTAPPROVED;
                redirect_header('index.php', 2, $databaseMessage);
            }
        } else {
            include XOOPS_ROOT_PATH . '/header.php';

            $xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
            $xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
            $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
            $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

            $xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

            // Breadcrumb
            $breadcrumb->addLink(_MD_WFDOWNLOADS_REVIEWTHISFILE, '');
            echo $breadcrumb->render();

            echo "<div align='center'>" . wfdownloads_headerImage() . "</div><br />\n";
            echo "<div>" . _MD_WFDOWNLOADS_REV_SNEWMNAMEDESC . "</div>\n";

            // Generate form
            include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
            $sform      = new XoopsThemeForm(_MD_WFDOWNLOADS_REV_SUBMITREV, 'reviewform', xoops_getenv('PHP_SELF'));
            $title_text = new XoopsFormText(_MD_WFDOWNLOADS_REV_TITLE, 'title', 50, 255);
            //$title_text->setDescription(_MD_WFDOWNLOADS_REV_TITLE_DESC);
            $sform->addElement($title_text, true);
            $rating_select = new XoopsFormSelect(_MD_WFDOWNLOADS_REV_RATING, 'rated', '10');
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
            $review_textarea = new XoopsFormDhtmlTextArea(_MD_WFDOWNLOADS_REV_DESCRIPTION, 'review', '', 15, 60);
            //$review_textarea->setDescription(_MD_WFDOWNLOADS_REV_DESCRIPTION_DESC);
            $sform->addElement($review_textarea, true);
            $sform->addElement(new XoopsFormHidden('lid', $lid));
            $sform->addElement(new XoopsFormHidden('cid', $cid));
            $sform->addElement(new XoopsFormHidden('uid', $reviewerUid));
            $button_tray   = new XoopsFormElementTray('', '');
            $submit_button = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
            $button_tray->addElement($submit_button);
            $cancel_button = new XoopsFormButton('', '', _CANCEL, 'button');
            $cancel_button->setExtra('onclick="history.go(-1)"');
            $button_tray->addElement($cancel_button);
            $sform->addElement($button_tray);
            $sform->display();
            include 'footer.php';
        }
}
