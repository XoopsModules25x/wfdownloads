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

$xoopsOption['template_main'] = "{$wfdownloads->getModule()->dirname()}_newlistindex.tpl";
include_once XOOPS_ROOT_PATH . '/header.php';

$xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
$xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

$xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);

$catArray['imageheader'] = wfdownloads_headerImage();
$catArray['letters'] = wfdownloads_lettersChoice();
$catArray['toolbar'] = wfdownloads_toolbar();
$xoopsTpl->assign('catarray', $catArray);

// Breadcrumb
$breadcrumb = new WfdownloadsBreadcrumb();
$breadcrumb->addLink($wfdownloads->getModule()->getVar('name'), WFDOWNLOADS_URL);

// Get number of downloads...
$allowedCategories = $gperm_handler->getItemIds('WFDownCatPerm', $groups, $wfdownloads->getModule()->mid());
// ... in the last week
$oneWeekAgo = strtotime('-1 week'); //$oneWeekAgo = time() - 3600*24*7; //@TODO: Change to strtotime (TODAY-1week);
$criteria = new Criteria('published', $oneWeekAgo, ">=");
$allWeekDownloads = $wfdownloads->getHandler('download')->getActiveCount($criteria);
// ... in the last month
$oneMonthAgo = strtotime('-1 month'); //$one_month_ago = time() - 3600*24*7; //@TODO: Change to strtotime (TODAY-1month);
$criteria = new Criteria('published', $oneMonthAgo, ">=");
$allMonthDownloads = $wfdownloads->getHandler('download')->getActiveCount($criteria);
$xoopsTpl->assign('allweekdownloads', $allWeekDownloads);
$xoopsTpl->assign('allmonthdownloads', $allMonthDownloads);

// Get latest downloads
$criteria = new CriteriaCompo(new Criteria('offline', 0));
if (isset($_GET['newdownloadshowdays'])) {
    $days = (int) $_GET['newdownloadshowdays'];
    $days_limit = array(7, 14, 30);
    if (in_array($days, $days_limit)) {
        $xoopsTpl->assign('newdownloadshowdays', $days);
        $downloadshowdays = time() - (3600 * 24 * $days);
        $criteria->add(new Criteria('published', $downloadshowdays, '>='), 'AND');
    }
}
$criteria->setSort('published');
$criteria->setOrder('DESC');
$criteria->setLimit($wfdownloads->getConfig('perpage'));
$criteria->setStart(0);
$downloadObjs = $wfdownloads->getHandler('download')->getActiveDownloads($criteria);
foreach ($downloadObjs as $downloadObj) {
    $downloadInfo = $downloadObj->getDownloadInfo();
    $xoopsTpl->assign('lang_dltimes', sprintf(_MD_WFDOWNLOADS_DLTIMES, $downloadInfo['hits']));
    $xoopsTpl->assign('lang_subdate', $downloadInfo['is_updated']);
    $xoopsTpl->append('file', $downloadInfo);
    $xoopsTpl->append('downloads', $downloadInfo); // this definition is not removed for backward compatibility issues
}

// Screenshots display
$xoopsTpl->assign('show_screenshot', false);
if ($wfdownloads->getConfig('screenshot') == 1) {
    $xoopsTpl->assign('shots_dir', $wfdownloads->getConfig('screenshots'));
    $xoopsTpl->assign('shotwidth', $wfdownloads->getConfig('shotwidth'));
    $xoopsTpl->assign('shotheight', $wfdownloads->getConfig('shotheight'));
    $xoopsTpl->assign('show_screenshot', true);
    $xoopsTpl->assign('viewcat', true);
}
if (isset($days)) {
    $which_new_downloads = " > " . sprintf(_MD_WFDOWNLOADS_NEWDOWNLOADS_INTHELAST, (int) $days);
    $xoopsTpl->assign(
        'categoryPath',
        '<a href="' . WFDOWNLOADS_URL . '/newlist.php">' . _MD_WFDOWNLOADS_NEWDOWNLOADS . '</a>' . $which_new_downloads
    );
    $breadcrumb->addLink(_MD_WFDOWNLOADS_LATESTLIST, $currentFile);
    $breadcrumb->addLink(sprintf(_MD_WFDOWNLOADS_NEWDOWNLOADS_INTHELAST, (int) $days), '');
} else {
    $xoopsTpl->assign('categoryPath', _MD_WFDOWNLOADS_NEWDOWNLOADS);
    $breadcrumb->addLink(_MD_WFDOWNLOADS_LATESTLIST, '');
}

// Breadcrumb
$xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

$xoopsTpl->assign('module_home', wfdownloads_module_home(true));
include_once __DIR__ . '/footer.php';
