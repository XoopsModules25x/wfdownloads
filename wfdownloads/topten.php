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

$xoopsOption['template_main'] = 'wfdownloads_topten.html';

// Check permissions
if (($_GET['list'] == 'rate') && $wfdownloads->getConfig('enable_ratings') == false && !wfdownloads_userIsAdmin()) {
    redirect_header('index.php', 3, _NOPERM);
}
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);

include XOOPS_ROOT_PATH . '/header.php';

$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/module.css');
$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/thickbox.css');
$xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

$action_array = array('hit' => 0, 'rate' => 1);
$list_array   = array('hits', 'rating');
$lang_array   = array(_MD_WFDOWNLOADS_HITS, _MD_WFDOWNLOADS_RATING);

$sort         = (isset($_GET['list']) && in_array($_GET['list'], $action_array)) ? $_GET['list'] : 'hit';
$thisselected = $action_array[$sort];
$sortDB       = $list_array[$thisselected];

$catarray['imageheader'] = wfdownloads_headerImage();
$catarray['letters']     = wfdownloads_lettersChoice();
$catarray['toolbar']     = wfdownloads_toolbar();
$xoopsTpl->assign('catarray', $catarray);

$arr = array();

$categories = $wfdownloads->getHandler('category')->getObjects();

$categoriesTree       = new XoopsObjectTree($categories, 'cid', 'pid');
$mainCategories       = $categoriesTree->getFirstChild(0);
$allowedCategoriesIds = $gperm_handler->getItemIds('WFDownCatPerm', $groups, $wfdownloads->getModule()->mid());

$e        = 0;
$rankings = array();
foreach ($mainCategories as $mainCategory) {
    $cid = (int)$mainCategory->getVar('cid');
    if (in_array($cid, $allowedCategoriesIds)) {
        $allSubCategories = $categoriesTree->getAllChild($cid);
        $cids             = array(); //initialise array
        if (count($allSubCategories) > 0) {
            foreach (array_keys($allSubCategories) as $k) {
                $cids[] = $allSubCategories[$k]->getVar('cid');
            }
        }
        $cids[] = $cid;

        $criteria = new CriteriaCompo(new Criteria('cid', '(' . implode(',', $cids) . ')', 'IN'));
        $criteria->setSort($sortDB);
        $criteria->setOrder('DESC');
        $criteria->setLimit(10);
        $downloads = $wfdownloads->getHandler('download')->getActiveDownloads($criteria);
        $filecount = count($downloads);

        if ($filecount > 0) {
            $rankings[$e]['title'] = $mainCategory->getVar('title');
            $rank                  = 1;

            foreach (array_keys($downloads) as $k) {
                $parent_cat_titles = array();
                $cats              = $categoriesTree->getAllParent($downloads[$k]->getVar('cid'));
                if (count($cats) > 0) {
                    foreach (array_keys($cats) as $j) {
                        $parent_cat_titles[] = $cats[$j]->getVar('title');
                    }
                }
                $thiscat             = $categoriesTree->getByKey($downloads[$k]->getVar('cid'));
                $parent_cat_titles[] = $thiscat->getVar('title');

                $catpath = implode('/', $parent_cat_titles);

                $rankings[$e]['file'][] = array(
                    'id'       => (int)$downloads[$k]->getVar('lid'),
                    'cid'      => (int)$downloads[$k]->getVar('cid'),
                    'rank'     => $rank,
                    'title'    => $downloads[$k]->getVar('title'),
                    'category' => $catpath,
                    'hits'     => $downloads[$k]->getVar('hits'),
                    'rating'   => number_format($downloads[$k]->getVar('rating'), 2),
                    'votes'    => $downloads[$k]->getVar('votes')
                );
                $rank++;
            }
            $e++;
        }
    }
}

$xoopsTpl->assign('lang_sortby', $lang_array[$thisselected]);
$xoopsTpl->assign('rankings', $rankings);

// Breadcrumb
$breadcrumb = new WfdownloadsBreadcrumb();
$breadcrumb->addLink($wfdownloads->getModule()->getVar('name'), WFDOWNLOADS_URL);
$breadcrumb->addLink($lang_array[$thisselected], '');
$xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

if ($_GET['list'] == 'rate') {
    $xoopsTpl->assign('categoryPath', _MD_WFDOWNLOADS_DOWNLOAD_MOST_RATED);
} else {
    $xoopsTpl->assign('categoryPath', _MD_WFDOWNLOADS_DOWNLOAD_MOST_POPULAR);
}

$xoopsTpl->assign('module_home', wfdownloads_module_home(true));

include 'footer.php';
