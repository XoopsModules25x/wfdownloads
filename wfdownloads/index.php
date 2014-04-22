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
$currentFile = pathinfo(__FILE__, PATHINFO_BASENAME);
include 'header.php';

// Check directories
if (!is_dir($wfdownloads->getConfig('uploaddir'))) {
    redirect_header(XOOPS_URL, 4, _MD_WFDOWNLOADS_ERROR_UPLOADDIRNOTEXISTS);
    exit();
}
if (!is_dir(XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('mainimagedir'))) {
    redirect_header(XOOPS_URL, 4, _MD_WFDOWNLOADS_ERROR_MAINIMAGEDIRNOTEXISTS);
    exit();
}
if (!is_dir(XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('screenshots'))) {
    redirect_header(XOOPS_URL, 4, _MD_WFDOWNLOADS_ERROR_SCREENSHOTSDIRNOTEXISTS);
    exit();
}
if (!is_dir(XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('catimage'))) {
    redirect_header(XOOPS_URL, 4, _MD_WFDOWNLOADS_ERROR_CATIMAGEDIRNOTEXISTS);
    exit();
}

$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);

// Check if submission is allowed
$isSubmissionAllowed = false;
if (is_object($xoopsUser)
    && ($wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_DOWNLOAD
        || $wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_BOTH)
) {
    // if user is a registered user
    $groups = $xoopsUser->getGroups();
    if (count(array_intersect($wfdownloads->getConfig('submitarts'), $groups)) > 0) {
        $isSubmissionAllowed = true;
    }
} else {
    // if user is anonymous
    if ($wfdownloads->getConfig('anonpost') == _WFDOWNLOADS_ANONPOST_DOWNLOAD || $wfdownloads->getConfig('anonpost') == _WFDOWNLOADS_ANONPOST_BOTH) {
        $isSubmissionAllowed = true;
    }
}

// Get download/upload permissions
$allowedDownCategoriesIds = $gperm_handler->getItemIds('WFDownCatPerm', $groups, $wfdownloads->getModule()->mid());
$allowedUpCategoriesIds   = $gperm_handler->getItemIds('WFUpCatPerm', $groups, $wfdownloads->getModule()->mid());

$xoopsOption['template_main'] = "{$wfdownloads->getModule()->dirname()}_index.tpl";
include XOOPS_ROOT_PATH . '/header.php';

$xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
$xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

$xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

// Breadcrumb
$breadcrumb = new WfdownloadsBreadcrumb();
$breadcrumb->addLink($wfdownloads->getModule()->getVar('name'), WFDOWNLOADS_URL);

$xoopsTpl->assign('module_home', wfdownloads_module_home(false)); // this definition is not removed for backward compatibility issues
$xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

$cat_criteria = new CriteriaCompo();
$cat_criteria->setSort('weight ASC, title');
$categories = $wfdownloads->getHandler('category')->getObjects($cat_criteria);
unset($cat_criteria);

$categoriesTree = new XoopsObjectTree($categories, "cid", "pid");

// Generate content header
$sql                          = "SELECT * FROM " . $xoopsDB->prefix('wfdownloads_indexpage') . " ";
$head_arr                     = $xoopsDB->fetchArray($xoopsDB->query($sql));
$catarray['imageheader']      = wfdownloads_headerImage();
$catarray['indexheaderalign'] = $head_arr['indexheaderalign'];
$catarray['indexfooteralign'] = $head_arr['indexfooteralign'];
$html                         = ($head_arr['nohtml']) ? 1 : 0;
$smiley                       = ($head_arr['nosmiley']) ? 1 : 0;
$xcodes                       = ($head_arr['noxcodes']) ? 1 : 0;
$images                       = ($head_arr['noimages']) ? 1 : 0;
$breaks                       = ($head_arr['nobreak']) ? 1 : 0;
$catarray['indexheader']      = $myts->displayTarea($head_arr['indexheader'], $html, $smiley, $xcodes, $images, $breaks);
$catarray['indexfooter']      = $myts->displayTarea($head_arr['indexfooter'], $html, $smiley, $xcodes, $images, $breaks);
$catarray['letters']          = wfdownloads_lettersChoice();
$catarray['toolbar']          = wfdownloads_toolbar();
$xoopsTpl->assign('catarray', $catarray);

// Begin Main page download info
$chcount = 0;
$countin = 0;

$listings = wfdownloads_getTotalDownloads($allowedDownCategoriesIds);

// Get total amount of categories
$total_cat = count($allowedDownCategoriesIds);
// Get all main categories
$mainCategories = $categoriesTree->getFirstChild(0);
$count          = 0;

// Comparison functions for uasort()
/**
 * @param $category_a
 * @param $category_b
 *
 * @return int
 */
function categoriesCompareCid($category_a, $category_b)
{
    if ($category_a->getVar('cid') == $category_b->getVar('cid')) {
        return 0;
    }

    return ($category_a->getVar('cid') < $category_b->getVar('cid')) ? -1 : 1;
}

/**
 * @param $category_a
 * @param $category_b
 *
 * @return int
 */
function categoriesCompareTitle($category_a, $category_b)
{
    if ($category_a->getVar('title') == $category_b->getVar('title')) {
        return 0;
    }

    return ($category_a->getVar('title') < $category_b->getVar('title')) ? -1 : 1;
}

/**
 * @param $category_a
 * @param $category_b
 *
 * @return int
 */
function categoriesCompareWeight($category_a, $category_b)
{
    if ($category_a->getVar('weight') == $category_b->getVar('weight')) {
        return 0;
    }

    return ($category_a->getVar('weight') < $category_b->getVar('weight')) ? -1 : 1;
}

// Foreach main category
foreach (array_keys($mainCategories) as $i) {
    if (in_array($mainCategories[$i]->getVar('cid'), $allowedDownCategoriesIds)) {
        // Get this category image
        // Get this category subcategories
        $allSubcategories = $categoriesTree->getAllChild($mainCategories[$i]->getVar('cid'));

        // Sort subcategories by: cid or title or weight
        switch ($wfdownloads->getConfig('subcatssortby')) {
            case 'cid' :
                uasort($allSubcategories, 'categoriesCompareCid');
                break;
            case 'title' :
                uasort($allSubcategories, 'categoriesCompareTitle');
                break;
            case 'weight' :
            default :
                uasort($allSubcategories, 'categoriesCompareWeight');
                break;
        }

        // Get this category indicator image
        $publishdate = isset($listings['published'][$mainCategories[$i]->getVar('cid')]) ? $listings['published'][$mainCategories[$i]->getVar('cid')]
            : 0;
        if (count($allSubcategories) > 0) {
            // Foreach subcategory
            foreach (array_keys($allSubcategories) as $k) {
                if (in_array($allSubcategories[$k]->getVar('cid'), $allowedDownCategoriesIds)) {
                    $publishdate = (isset($listings['published'][$allSubcategories[$k]->getVar('cid')]) &&
                        $listings['published'][$allSubcategories[$k]->getVar('cid')] > $publishdate)
                        ? $listings['published'][$allSubcategories[$k]->getVar('cid')] : $publishdate;
                }
            }
        }
        $isNewImage = wfdownloads_isNewImage($publishdate);
        if (($mainCategories[$i]->getVar('imgurl') != "")
            && is_file(
                XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('catimage') . '/' . $mainCategories[$i]->getVar('imgurl')
            )
        ) {
            if ($wfdownloads->getConfig('usethumbs') && function_exists('gd_info')) {
                $imageURL = wfdownloads_createThumb(
                    $mainCategories[$i]->getVar('imgurl'),
                    $wfdownloads->getConfig('catimage'),
                    "thumbs",
                    $wfdownloads->getConfig('cat_imgwidth'),
                    $wfdownloads->getConfig('cat_imgheight'),
                    $wfdownloads->getConfig('imagequality'),
                    $wfdownloads->getConfig('updatethumbs'),
                    $wfdownloads->getConfig('keepaspect')
                );
            } else {
                $imageURL = XOOPS_URL . '/' . $wfdownloads->getConfig('catimage') . '/' . $mainCategories[$i]->getVar('imgurl');
            }
        } else {
            $imageURL = $isNewImage['image'];
        }

        // Get this category subcategories id and title
        $subcategories = array();
        ++$count;
        $download_count = isset($listings['count'][$mainCategories[$i]->getVar('cid')]) ? $listings['count'][$mainCategories[$i]->getVar('cid')] : 0;
        // modified July 5 2006 by Freeform Solutions (jwe)
        // make download count recursive, to include all sub categories that the user has permission to view
        //$allSubcategories = $categoriesTree->getAllChild($mainCategories[$i]->getVar('cid'));
        if (count($allSubcategories) > 0) {
            foreach (array_keys($allSubcategories) as $k) {
                if (in_array($allSubcategories[$k]->getVar('cid'), $allowedDownCategoriesIds)) {
                    $download_count += isset($listings['count'][$allSubcategories[$k]->getVar('cid')])
                        ? $listings['count'][$allSubcategories[$k]->getVar('cid')] : 0;
                    if ($wfdownloads->getConfig('subcats') == 1 && $allSubcategories[$k]->getVar('pid') == $mainCategories[$i]->getVar('cid')) {
                        // if we are collecting subcat info for displaying, and this subcat is a first level child...
                        $subcategories[] = array(
                            'id'               => $allSubcategories[$k]->getVar('cid'), // this definition is not removed for backward compatibility issues
                            'cid'              => $allSubcategories[$k]->getVar('cid'),
                            'allowed_download' => in_array($allSubcategories[$k]->getVar('cid'), $allowedDownCategoriesIds),
                            'allowed_upload'   => ($isSubmissionAllowed && in_array($allSubcategories[$k]->getVar('cid'), $allowedUpCategoriesIds)),
                            'title'            => $allSubcategories[$k]->getVar('title')
                        );
                    }
                }
            }
        }

        if ($wfdownloads->getConfig('subcats') != true) {
            unset($subcategories);
            $xoopsTpl->append(
                'categories',
                array(
                     'image'            => $imageURL, // this definition is not removed for backward compatibility issues
                     'image_URL'        => $imageURL,
                     'days'             => $isNewImage['days'],
                     'id'               => (int) $mainCategories[$i]->getVar('cid'), // this definition is not removed for backward compatibility issues
                     'cid'              => (int) $mainCategories[$i]->getVar('cid'),
                     'allowed_download' => in_array($mainCategories[$i]->getVar('cid'), $allowedDownCategoriesIds),
                     'allowed_upload'   => ($isSubmissionAllowed && in_array($mainCategories[$i]->getVar('cid'), $allowedUpCategoriesIds)),
                     'title'            => $mainCategories[$i]->getVar('title'),
                     'summary'          => $mainCategories[$i]->getVar('summary'),
                     'totaldownloads'   => (int) $download_count, // this definition is not removed for backward compatibility issues
                     'downloads_count'  => (int) $download_count,
                     'count'            => (int) $count,
                     'alttext'          => $isNewImage['alttext']
                )
            );
        } else {
            $xoopsTpl->append(
                'categories',
                array(
                     'image'            => $imageURL, // this definition is not removed for backward compatibility issues
                     'image_URL'        => $imageURL,
                     'days'             => $isNewImage['days'],
                     'id'               => (int) $mainCategories[$i]->getVar('cid'), // this definition is not removed for backward compatibility issues
                     'cid'              => (int) $mainCategories[$i]->getVar('cid'),
                     'allowed_download' => in_array($mainCategories[$i]->getVar('cid'), $allowedDownCategoriesIds),
                     'allowed_upload'   => ($isSubmissionAllowed && in_array($mainCategories[$i]->getVar('cid'), $allowedUpCategoriesIds)),
                     'title'            => $mainCategories[$i]->getVar('title'),
                     'summary'          => $mainCategories[$i]->getVar('summary'),
                     'subcategories'    => $subcategories,
                     'totaldownloads'   => (int) $download_count, // this definition is not removed for backward compatibility issues
                     'downloads_count'  => (int) $download_count,
                     'count'            => (int) $count,
                     'alttext'          => $isNewImage['alttext']
                )
            );
        }
    }
}
$lang_ThereAre = $count != 1 ? _MD_WFDOWNLOADS_THEREARE : _MD_WFDOWNLOADS_THEREIS;

$xoopsTpl->assign('lang_thereare', sprintf($lang_ThereAre, $count, array_sum($listings['count'])));

if ($wfdownloads->getConfig('enablerss') == true) {
    $rsslink_URL = WFDOWNLOADS_URL . "/rss.php";
    $xoopsTpl->assign('full_rssfeed_URL', $rsslink_URL);
    $rsslink = "<a href='" . $rsslink_URL . "' title='" . _MD_WFDOWNLOADS_LEGENDTEXTRSS . "'>";
    $rsslink.= "<img src='" . WFDOWNLOADS_URL . "/assets/images/icon/rss.gif' border='0' alt='" . _MD_WFDOWNLOADS_LEGENDTEXTRSS . "' title='" . _MD_WFDOWNLOADS_LEGENDTEXTRSS . "'>";
    $rsslink.= "</a>";
    $xoopsTpl->assign('full_rssfeed_link', $rsslink); // this definition is not removed for backward compatibility issues
}

include 'footer.php';
