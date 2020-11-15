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

use XoopsModules\Wfdownloads\{
    Common\LetterChoice,
    Category,
    Common,
    Helper,
    Utility,
    ObjectTree,
    DownloadHandler,
};
/** @var Helper $helper */
/** @var Utility $utility */
/** @var Category $categoryObj_a */

//$currentFile = pathinfo(__FILE__, PATHINFO_BASENAME);
//$currentFile = basename(__FILE__);
require_once __DIR__ . '/header.php';

$moduleDirName      = basename(__DIR__);
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

// Check directories
if (!is_dir($helper->getConfig('uploaddir'))) {
    redirect_header(XOOPS_URL, 4, _MD_WFDOWNLOADS_ERROR_UPLOADDIRNOTEXISTS);
}
if (!is_dir(XOOPS_ROOT_PATH . '/' . $helper->getConfig('mainimagedir'))) {
    redirect_header(XOOPS_URL, 4, _MD_WFDOWNLOADS_ERROR_MAINIMAGEDIRNOTEXISTS);
}
if (!is_dir(XOOPS_ROOT_PATH . '/' . $helper->getConfig('screenshots'))) {
    redirect_header(XOOPS_URL, 4, _MD_WFDOWNLOADS_ERROR_SCREENSHOTSDIRNOTEXISTS);
}
if (!is_dir(XOOPS_ROOT_PATH . '/' . $helper->getConfig('catimage'))) {
    redirect_header(XOOPS_URL, 4, _MD_WFDOWNLOADS_ERROR_CATIMAGEDIRNOTEXISTS);
}

$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : [0 => XOOPS_GROUP_ANONYMOUS];

// Check if submission is allowed
$isSubmissionAllowed = false;
if (is_object($GLOBALS['xoopsUser']) && (_WFDOWNLOADS_SUBMISSIONS_DOWNLOAD == $helper->getConfig('submissions') || _WFDOWNLOADS_SUBMISSIONS_BOTH == $helper->getConfig('submissions'))) {
    // if user is a registered user
    $groups = $GLOBALS['xoopsUser']->getGroups();
    if (count(array_intersect($helper->getConfig('submitarts'), $groups)) > 0) {
        $isSubmissionAllowed = true;
    }
} else {
    // if user is anonymous
    if (_WFDOWNLOADS_ANONPOST_DOWNLOAD == $helper->getConfig('anonpost') || _WFDOWNLOADS_ANONPOST_BOTH == $helper->getConfig('anonpost')) {
        $isSubmissionAllowed = true;
    }
}

// Get download/upload permissions

/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler = xoops_getHandler('groupperm');

$allowedDownCategoriesIds = $grouppermHandler->getItemIds('WFDownCatPerm', $groups, $helper->getModule()->mid());
$allowedUpCategoriesIds   = $grouppermHandler->getItemIds('WFUpCatPerm', $groups, $helper->getModule()->mid());

$GLOBALS['xoopsOption']['template_main'] = "{$helper->getDirname()}_index.tpl";

require_once XOOPS_ROOT_PATH . '/header.php';

$xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
$xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

$xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

// Breadcrumb
$breadcrumb = new Common\Breadcrumb();
$breadcrumb->addLink($helper->getModule()->getVar('name'), WFDOWNLOADS_URL);

$xoopsTpl->assign('module_home', Utility::moduleHome(false)); // this definition is not removed for backward compatibility issues
$xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

$categoryCriteria = new CriteriaCompo();
$categoryCriteria->setSort('weight ASC, title');
$categoryObjs = $helper->getHandler('Category')->getObjects($categoryCriteria);
unset($categoryCriteria);

$categoryObjsTree = new ObjectTree($categoryObjs, 'cid', 'pid');

// Generate content header
$sql                          = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('wfdownloads_indexpage') . ' ';
$head_arr                     = $GLOBALS['xoopsDB']->fetchArray($GLOBALS['xoopsDB']->query($sql));
$catarray['imageheader']      = Utility::headerImage();
$catarray['indexheaderalign'] = $head_arr['indexheaderalign'];
$catarray['indexfooteralign'] = $head_arr['indexfooteralign'];
$html                         = $head_arr['nohtml'] ? 1 : 0;
$smiley                       = $head_arr['nosmiley'] ? 1 : 0;
$xcodes                       = $head_arr['noxcodes'] ? 1 : 0;
$images                       = $head_arr['noimages'] ? 1 : 0;
$breaks                       = $head_arr['nobreak'] ? 1 : 0;
$catarray['indexheader']      = &$myts->displayTarea($head_arr['indexheader'], $html, $smiley, $xcodes, $images, $breaks);
$catarray['indexfooter']      = &$myts->displayTarea($head_arr['indexfooter'], $html, $smiley, $xcodes, $images, $breaks);

$showAlphabet = $helper->getConfig('showAlphabet');
if ($showAlphabet) {
    //$catarray['letters']          = Utility::lettersChoice();

    // Letter Choice Start ---------------------------------------

    $helper->loadLanguage('common');
    $xoopsTpl->assign('letterChoiceTitle', constant('CO_' . $moduleDirNameUpper . '_' . 'BROWSETOTOPIC'));
    /** @var \XoopsDatabase $db */
    $db             = XoopsDatabaseFactory::getDatabaseConnection();
    $objHandler     = new DownloadHandler($db);
    $choicebyletter = new LetterChoice($objHandler, null, null, range('a', 'z'), 'letter', 'viewcat.php');
    //$choicebyletter = new LetterChoice($objHandler, null, null, range('a', 'z'), 'init', XOOPSTUBE_URL . '/letter.php');
    //render the LetterChoice partial and story as part of the Category array
    //$catarray['letters']  = $choicebyletter->render($alphaCount, $howmanyother);

    $catarray['letters'] = $choicebyletter->render();

    //now assign it to the Smarty variable
    $xoopsTpl->assign('catarray', $catarray);
    // Letter Choice End ------------------------------------
}
$catarray['toolbar'] = Utility::toolbar();
$xoopsTpl->assign('catarray', $catarray);

// Begin Main page download info
$chcount = 0;
$countin = 0;

$listings = Utility::getTotalDownloads($allowedDownCategoriesIds);

// Get total amount of categories
$total_cat = count($allowedDownCategoriesIds);
// Get all main categories
$mainCategoryObjs = $categoryObjsTree->getFirstChild(0);
$count            = 0;

// Comparison functions for uasort()
/**
 * @param Category $categoryObj_a
 * @param Category $categoryObj_b
 *
 * @return int
 */
function categoriesCompareCid(Category $categoryObj_a, Category $categoryObj_b)
{
    if ($categoryObj_a->getVar('cid') == $categoryObj_b->getVar('cid')) {
        return 0;
    }

    return ($categoryObj_a->getVar('cid') < $categoryObj_b->getVar('cid')) ? -1 : 1;
}

/**
 * @param Category $categoryObj_a
 * @param Category $categoryObj_b
 *
 * @return int
 */
function categoriesCompareTitle(Category $categoryObj_a, Category $categoryObj_b)
{
    if ($categoryObj_a->getVar('title') == $categoryObj_b->getVar('title')) {
        return 0;
    }

    return ($categoryObj_a->getVar('title') < $categoryObj_b->getVar('title')) ? -1 : 1;
}

/**
 * @param Category $categoryObj_a
 * @param Category $categoryObj_b
 *
 * @return int
 */
function categoriesCompareWeight(Category $categoryObj_a, Category $categoryObj_b)
{
    if ($categoryObj_a->getVar('weight') == $categoryObj_b->getVar('weight')) {
        return 0;
    }

    return ($categoryObj_a->getVar('weight') < $categoryObj_b->getVar('weight')) ? -1 : 1;
}

// Foreach main category
foreach (array_keys($mainCategoryObjs) as $i) {
    if (in_array($mainCategoryObjs[$i]->getVar('cid'), $allowedDownCategoriesIds)) {
        // Get this category image
        // Get this category subcategories
        $allSubcategoryObjs = $categoryObjsTree->getAllChild($mainCategoryObjs[$i]->getVar('cid'));

        // Sort subcategories by: cid or title or weight
        switch ($helper->getConfig('subcatssortby')) {
            case 'cid':
                uasort($allSubcategoryObjs, 'categoriesCompareCid');
                break;
            case 'title':
                uasort($allSubcategoryObjs, 'categoriesCompareTitle');
                break;
            case 'weight':
            default:
                uasort($allSubcategoryObjs, 'categoriesCompareWeight');
                break;
        }

        // Get this category indicator image
        $publishdate = isset($listings['published'][$mainCategoryObjs[$i]->getVar('cid')]) ? $listings['published'][$mainCategoryObjs[$i]->getVar('cid')] : 0;
        if (count($allSubcategoryObjs) > 0) {
            // Foreach subcategory
            foreach (array_keys($allSubcategoryObjs) as $k) {
                if (in_array($allSubcategoryObjs[$k]->getVar('cid'), $allowedDownCategoriesIds)) {
                    $publishdate = (isset($listings['published'][$allSubcategoryObjs[$k]->getVar('cid')])
                                    && $listings['published'][$allSubcategoryObjs[$k]->getVar('cid')] > $publishdate) ? $listings['published'][$allSubcategoryObjs[$k]->getVar('cid')] : $publishdate;
                }
            }
        }
        $isNewImage = Utility::isNewImage($publishdate);
        if (('' !== $mainCategoryObjs[$i]->getVar('imgurl')) && is_file(XOOPS_ROOT_PATH . '/' . $helper->getConfig('catimage') . '/' . $mainCategoryObjs[$i]->getVar('imgurl'))) {
            if ($helper->getConfig('usethumbs') && function_exists('gd_info')) {
                $imageURL = Utility::createThumb(
                    $mainCategoryObjs[$i]->getVar('imgurl'),
                    $helper->getConfig('catimage'),
                    'thumbs',
                    $helper->getConfig('cat_imgwidth'),
                    $helper->getConfig('cat_imgheight'),
                    $helper->getConfig('imagequality'),
                    $helper->getConfig('updatethumbs'),
                    $helper->getConfig('keepaspect')
                );
            } else {
                $imageURL = XOOPS_URL . '/' . $helper->getConfig('catimage') . '/' . $mainCategoryObjs[$i]->getVar('imgurl');
            }
        } else {
            $imageURL = $isNewImage['image'];
        }

        // Get this category subcategories id and title
        $subcategories = [];
        ++$count;
        $download_count = isset($listings['count'][$mainCategoryObjs[$i]->getVar('cid')]) ? $listings['count'][$mainCategoryObjs[$i]->getVar('cid')] : 0;
        // modified July 5 2006 by Freeform Solutions (jwe)
        // make download count recursive, to include all sub categories that the user has permission to view
        //$allSubcategoryObjs = $categoryObjsTree->getAllChild($mainCategoryObjs[$i]->getVar('cid'));
        if (count($allSubcategoryObjs) > 0) {
            foreach (array_keys($allSubcategoryObjs) as $k) {
                if (in_array($allSubcategoryObjs[$k]->getVar('cid'), $allowedDownCategoriesIds)) {
                    $download_count += isset($listings['count'][$allSubcategoryObjs[$k]->getVar('cid')]) ? $listings['count'][$allSubcategoryObjs[$k]->getVar('cid')] : 0;
                    if (1 == $helper->getConfig('subcats') && $allSubcategoryObjs[$k]->getVar('pid') == $mainCategoryObjs[$i]->getVar('cid')) {
                        // if we are collecting subcat info for displaying, and this subcat is a first level child...
                        $subcategories[] = [
                            'id'               => $allSubcategoryObjs[$k]->getVar('cid'), // this definition is not removed for backward compatibility issues
                            'cid'              => $allSubcategoryObjs[$k]->getVar('cid'),
                            'allowed_download' => in_array($allSubcategoryObjs[$k]->getVar('cid'), $allowedDownCategoriesIds),
                            'allowed_upload'   => $isSubmissionAllowed && in_array($allSubcategoryObjs[$k]->getVar('cid'), $allowedUpCategoriesIds),
                            'title'            => $allSubcategoryObjs[$k]->getVar('title'),
                        ];
                    }
                }
            }
        }

        if (true !== $helper->getConfig('subcats')) {
            unset($subcategories);
            $xoopsTpl->append(
                'categories',
                [
                    'image'            => $imageURL, // this definition is not removed for backward compatibility issues
                    'image_URL'        => $imageURL,
                    'days'             => $isNewImage['days'],
                    'id'               => (int)$mainCategoryObjs[$i]->getVar('cid'), // this definition is not removed for backward compatibility issues
                    'cid'              => (int)$mainCategoryObjs[$i]->getVar('cid'),
                    'allowed_download' => in_array($mainCategoryObjs[$i]->getVar('cid'), $allowedDownCategoriesIds),
                    'allowed_upload'   => $isSubmissionAllowed && in_array($mainCategoryObjs[$i]->getVar('cid'), $allowedUpCategoriesIds),
                    'title'            => $mainCategoryObjs[$i]->getVar('title'),
                    'summary'          => $mainCategoryObjs[$i]->getVar('summary'),
                    'totaldownloads'   => $download_count, // this definition is not removed for backward compatibility issues
                    'downloads_count'  => $download_count,
                    'count'            => $count,
                    'alttext'          => $isNewImage['alttext'],
                ]
            );
        } else {
            $xoopsTpl->append(
                'categories',
                [
                    'image'            => $imageURL, // this definition is not removed for backward compatibility issues
                    'image_URL'        => $imageURL,
                    'days'             => $isNewImage['days'],
                    'id'               => (int)$mainCategoryObjs[$i]->getVar('cid'), // this definition is not removed for backward compatibility issues
                    'cid'              => (int)$mainCategoryObjs[$i]->getVar('cid'),
                    'allowed_download' => in_array($mainCategoryObjs[$i]->getVar('cid'), $allowedDownCategoriesIds),
                    'allowed_upload'   => $isSubmissionAllowed && in_array($mainCategoryObjs[$i]->getVar('cid'), $allowedUpCategoriesIds),
                    'title'            => $mainCategoryObjs[$i]->getVar('title'),
                    'summary'          => $mainCategoryObjs[$i]->getVar('summary'),
                    'subcategories'    => $subcategories,
                    'totaldownloads'   => $download_count, // this definition is not removed for backward compatibility issues
                    'downloads_count'  => $download_count,
                    'count'            => $count,
                    'alttext'          => $isNewImage['alttext'],
                ]
            );
        }
    }
}
$lang_ThereAre = 1 != $count ? _MD_WFDOWNLOADS_THEREARE : _MD_WFDOWNLOADS_THEREIS;

$xoopsTpl->assign('lang_thereare', sprintf($lang_ThereAre, $count, array_sum($listings['count'])));

if (true === $helper->getConfig('enablerss')) {
    $rsslink_URL = WFDOWNLOADS_URL . '/rss.php';
    $xoopsTpl->assign('full_rssfeed_URL', $rsslink_URL);
    $rsslink = "<a href='" . $rsslink_URL . "' title='" . _MD_WFDOWNLOADS_LEGENDTEXTRSS . "'>";
    $rsslink .= "<img src='" . WFDOWNLOADS_URL . "/assets/images/icon/rss.gif' border='0' alt='" . _MD_WFDOWNLOADS_LEGENDTEXTRSS . "' title='" . _MD_WFDOWNLOADS_LEGENDTEXTRSS . "'>";
    $rsslink .= '</a>';
    $xoopsTpl->assign('full_rssfeed_link', $rsslink); // this definition is not removed for backward compatibility issues
}

require_once __DIR__ . '/footer.php';
