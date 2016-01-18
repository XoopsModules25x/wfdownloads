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

$xoopsOption['template_main'] = "{$wfdownloads->getModule()->dirname()}_topten.tpl";

// Check permissions
if (($_GET['list'] == 'rate') && $wfdownloads->getConfig('enable_ratings') == false && !wfdownloads_userIsAdmin()) {
    redirect_header('index.php', 3, _NOPERM);
}
$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);

include_once XOOPS_ROOT_PATH . '/header.php';

$xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
$xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

$xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

$action_array = array('hit' => 0, 'rate' => 1);
$list_array = array('hits', 'rating');
$lang_array = array(_MD_WFDOWNLOADS_HITS, _MD_WFDOWNLOADS_RATING);

$sort = (isset($_GET['list']) && in_array($_GET['list'], $action_array)) ? $_GET['list'] : 'hit';
$thisselected = $action_array[$sort];
$sortDB = $list_array[$thisselected];

$catarray['imageheader'] = wfdownloads_headerImage();
$catarray['letters'] = wfdownloads_lettersChoice();
$catarray['toolbar'] = wfdownloads_toolbar();
$xoopsTpl->assign('catarray', $catarray);

$arr = array();

$categoryObjs = $wfdownloads->getHandler('category')->getObjects();

$categoryObjsTree = new XoopsObjectTree($categoryObjs, 'cid', 'pid');
$mainCategoryObjs = $categoryObjsTree->getFirstChild(0);
$allowedCategoriesIds = $gperm_handler->getItemIds('WFDownCatPerm', $groups, $wfdownloads->getModule()->mid());

$e = 0;
$rankings = array();
foreach ($mainCategoryObjs as $mainCategoryObj) {
    $cid = (int) $mainCategoryObj->getVar('cid');
    if (in_array($cid, $allowedCategoriesIds)) {
        $allSubCategoryObjs = $categoryObjsTree->getAllChild($cid);
        $cids = array(); //initialise array
        if (count($allSubCategoryObjs) > 0) {
            foreach ($allSubCategoryObjs as $allSubCategoryObj) {
                $cids[] = $allSubCategoryObj->getVar('cid');
            }
        }
        $cids[] = $cid;

        $criteria = new CriteriaCompo(new Criteria('cid', '(' . implode(',', $cids) . ')', 'IN'));
        $criteria->setSort($sortDB);
        $criteria->setOrder('DESC');
        $criteria->setLimit(10);
        $downloadObjs = $wfdownloads->getHandler('download')->getActiveDownloads($criteria);
        $filecount = count($downloadObjs);

        if ($filecount > 0) {
            $rankings[$e]['title'] = $mainCategoryObj->getVar('title');
            $rank                  = 1;

            foreach (array_keys($downloadObjs) as $k) {
                $parentCategory_titles = array();
                $parentCategoryObjs = $categoryObjsTree->getAllParent($downloadObjs[$k]->getVar('cid'));
                if (count($parentCategoryObjs) > 0) {
                    foreach ($parentCategoryObjs as $parentCategoryObj) {
                        $parentCategory_titles[] = $parentCategoryObj->getVar('title');
                    }
                }
                $thisCategoryObj = $categoryObjsTree->getByKey($downloadObjs[$k]->getVar('cid'));
                $parentCategory_titles[] = $thisCategoryObj->getVar('title');

                $rankings[$e]['file'][] = array(
                    'id' => (int) $downloadObjs[$k]->getVar('lid'),
                    'cid' => (int) $downloadObjs[$k]->getVar('cid'),
                    'rank' => $rank,
                    'title' => $downloadObjs[$k]->getVar('title'),
                    'category' => implode('/', $parentCategory_titles),
                    'hits' => $downloadObjs[$k]->getVar('hits'),
                    'rating' => number_format($downloadObjs[$k]->getVar('rating'), 2),
                    'votes' => $downloadObjs[$k]->getVar('votes')
                );
                ++$rank;
            }
            ++$e;
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

include_once __DIR__ . '/footer.php';
