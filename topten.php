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
    Common\LetterChoice,
    Helper,
    Utility,
    DownloadHandler,
    ObjectTree
};

/** @var Helper $helper */
/** @var Utility $utility */

$currentFile = basename(__FILE__);
require_once __DIR__ . '/header.php';

$GLOBALS['xoopsOption']['template_main'] = "{$helper->getModule()->dirname()}_topten.tpl";

// Check permissions
if ('rate' === Request::getString('list', '', 'GET')
    && false === $helper->getConfig('enable_ratings') && !Utility::userIsAdmin()) {
    redirect_header('index.php', 3, _NOPERM);
}
$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : [0 => XOOPS_GROUP_ANONYMOUS];

require_once XOOPS_ROOT_PATH . '/header.php';

$xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
$xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

$xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

$action_array = ['hit' => 0, 'rate' => 1];
$list_array   = ['hits', 'rating'];
$lang_array   = [_MD_WFDOWNLOADS_HITS, _MD_WFDOWNLOADS_RATING];

$sort         = (isset($_GET['list']) && in_array($_GET['list'], $action_array)) ? $_GET['list'] : 'hit';
$thisselected = $action_array[$sort];
$sortDB       = $list_array[$thisselected];

$catarray['imageheader'] = Utility::headerImage();
//$catarray['letters']     = Utility::lettersChoice();
/** @var \XoopsDatabase $db */
$db         = XoopsDatabaseFactory::getDatabaseConnection();
$objHandler = new DownloadHandler($db);
/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler    = xoops_getHandler('groupperm');
$choicebyletter      = new LetterChoice($objHandler, null, null, range('a', 'z'), 'letter');
$catarray['letters'] = $choicebyletter->render();

$catarray['toolbar'] = Utility::toolbar();

$xoopsTpl->assign('catarray', $catarray);

$arr = [];

$categoryObjs = $helper->getHandler('Category')->getObjects();

$categoryObjsTree     = new ObjectTree($categoryObjs, 'cid', 'pid');
$mainCategoryObjs     = $categoryObjsTree->getFirstChild(0);
$allowedCategoriesIds = $grouppermHandler->getItemIds('WFDownCatPerm', $groups, $helper->getModule()->mid());

$e        = 0;
$rankings = [];
foreach ($mainCategoryObjs as $mainCategoryObj) {
    $cid = (int)$mainCategoryObj->getVar('cid');
    if (in_array($cid, $allowedCategoriesIds)) {
        $allSubCategoryObjs = $categoryObjsTree->getAllChild($cid);
        $cids               = []; //initialise array
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
        $downloadObjs = $helper->getHandler('Download')->getActiveDownloads($criteria);
        $filecount    = count($downloadObjs);

        if ($filecount > 0) {
            $rankings[$e]['title'] = $mainCategoryObj->getVar('title');
            $rank                  = 1;

            foreach (array_keys($downloadObjs) as $k) {
                $parentCategory_titles = [];
                $parentCategoryObjs    = $categoryObjsTree->getAllParent($downloadObjs[$k]->getVar('cid'));
                if (count($parentCategoryObjs) > 0) {
                    foreach ($parentCategoryObjs as $parentCategoryObj) {
                        $parentCategory_titles[] = $parentCategoryObj->getVar('title');
                    }
                }
                $thisCategoryObj         = &$categoryObjsTree->getByKey($downloadObjs[$k]->getVar('cid'));
                $parentCategory_titles[] = $thisCategoryObj->getVar('title');

                $rankings[$e]['file'][] = [
                    'id'       => (int)$downloadObjs[$k]->getVar('lid'),
                    'cid'      => (int)$downloadObjs[$k]->getVar('cid'),
                    'rank'     => $rank,
                    'title'    => $downloadObjs[$k]->getVar('title'),
                    'category' => implode('/', $parentCategory_titles),
                    'hits'     => $downloadObjs[$k]->getVar('hits'),
                    'rating'   => number_format($downloadObjs[$k]->getVar('rating'), 2),
                    'votes'    => $downloadObjs[$k]->getVar('votes'),
                ];
                ++$rank;
            }
            ++$e;
        }
    }
}

$xoopsTpl->assign('lang_sortby', $lang_array[$thisselected]);
$xoopsTpl->assign('rankings', $rankings);

// Breadcrumb
$breadcrumb = new Common\Breadcrumb();
$breadcrumb->addLink($helper->getModule()->getVar('name'), WFDOWNLOADS_URL);
$breadcrumb->addLink($lang_array[$thisselected], '');
$xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

if ('rate' === Request::getString('list', '', 'GET')) {
    $xoopsTpl->assign('categoryPath', _MD_WFDOWNLOADS_DOWNLOAD_MOST_RATED);
} else {
    $xoopsTpl->assign('categoryPath', _MD_WFDOWNLOADS_DOWNLOAD_MOST_POPULAR);
}

$xoopsTpl->assign('module_home', Utility::moduleHome(true));

require_once __DIR__ . '/footer.php';
