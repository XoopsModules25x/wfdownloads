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

$cid   = WfdownloadsRequest::getInt('cid', 0);
$start = WfdownloadsRequest::getInt('start', 0);
//$list = WfdownloadsRequest::getString('list', null);
//$orderby = WfdownloadsRequest::getString('orderby', null);
$orderby = isset($_GET['orderby']) ? convertorderbyin($_GET['orderby']) : $wfdownloads->getConfig('filexorder');

$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);

// Check permissions
if (in_array(XOOPS_GROUP_ANONYMOUS, $groups)) {
    if (!$gperm_handler->checkRight('WFDownCatPerm', $cid, $groups, $wfdownloads->getModule()->mid())) {
        redirect_header(XOOPS_URL . '/user.php', 3, _MD_WFDOWNLOADS_NEEDLOGINVIEW);
    }
} else {
    if (!$gperm_handler->checkRight('WFDownCatPerm', $cid, $groups, $wfdownloads->getModule()->mid())) {
        redirect_header('index.php', 3, _NOPERM);
    }
}

// Check if submission is allowed
$isSubmissionAllowed = false;
if (is_object($xoopsUser)
    && ($wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_DOWNLOAD
        || $wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_BOTH)
) {
    // if user is a registered user
    $groups = $xoopsUser->getGroups();
    if (array_intersect($xoopsModuleConfig['submitarts'], $groups)) {
        $isSubmissionAllowed = true;
    }
} else {
    // if user is anonymous
    if ($wfdownloads->getConfig('anonpost') == _WFDOWNLOADS_ANONPOST_DOWNLOAD || $wfdownloads->getConfig('anonpost') == _WFDOWNLOADS_ANONPOST_BOTH) {
        $isSubmissionAllowed = true;
    }
}

// Get category object
$category = $wfdownloads->getHandler('category')->get($cid);
if (empty($category)) {
    redirect_header('index.php', 3, _CO_WFDOWNLOADS_ERROR_NOCATEGORY);
}

// Get download/upload permissions
$allowedDownCategoriesIds = $gperm_handler->getItemIds('WFDownCatPerm', $groups, $wfdownloads->getModule()->mid());
$allowedUpCategoriesIds   = $gperm_handler->getItemIds('WFUpCatPerm', $groups, $wfdownloads->getModule()->mid());

$xoopsOption['template_main'] = 'wfdownloads_viewcat.html';
include XOOPS_ROOT_PATH . '/header.php';

$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/module.css');
$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/thickbox.css');
$xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

$xoopsTpl->assign('cid', $cid); // this definition is not removed for backward compatibility issues
$xoopsTpl->assign('category_id', $cid); // this definition is not removed for backward compatibility issues
$xoopsTpl->assign('category_cid', $cid);

// Retreiving the top parent category
if (!isset($_GET['list']) && !isset($_GET['selectdate'])) {
    $allSubcatsTopParentCid = $wfdownloads->getHandler('category')->getAllSubcatsTopParentCid();
    $topCategory            = $wfdownloads->getHandler('category')->allCategories[$allSubcatsTopParentCid[$cid]];
    $xoopsTpl->assign('topcategory_title', $topCategory->getVar('title'));
    $xoopsTpl->assign('topcategory_image', $topCategory->getVar('imgurl')); // this definition is not removed for backward compatibility issues
    $xoopsTpl->assign('topcategory_image_URL', $topCategory->getVar('imgurl'));
    $xoopsTpl->assign('topcategory_cid', $topCategory->getVar('cid'));
}

// Added Formulize module support (2006/05/04) jpc - start
if (wfdownloads_checkModule('formulize')) {
    $formulize_fid = $category->getVar('formulize_fid');
    if ($formulize_fid) {
        $xoopsTpl->assign('custom_form', true);
    } else {
        $xoopsTpl->assign('custom_form', false);
    }
}
// Added Formulize module support (2006/05/04) jpc - end

// Generate Header
$catArray['imageheader'] = wfdownloads_headerImage();
$catArray['letters']     = wfdownloads_lettersChoice();
$catArray['toolbar']     = wfdownloads_toolbar();
$xoopsTpl->assign('catarray', $catArray);

$xoopsTpl->assign('categoryPath', $wfdownloads->getHandler('category')->getNicePath($cid)); // this definition is not removed for backward compatibility issues
$xoopsTpl->assign('module_home', wfdownloads_module_home(true)); // this definition is not removed for backward compatibility issues

// Get categories tree
$criteria = new CriteriaCompo();
$criteria->setSort('weight ASC, title');
$categories = $wfdownloads->getHandler('category')->getObjects($criteria, true);
include_once XOOPS_ROOT_PATH . '/class/tree.php';
$categoriesTree = new XoopsObjectTree($categories, 'cid', 'pid');

// Breadcrumb
$breadcrumb = new WfdownloadsBreadcrumb();
$breadcrumb->addLink($wfdownloads->getModule()->getVar('name'), WFDOWNLOADS_URL);
foreach (array_reverse($categoriesTree->getAllParent($cid)) as $parentCategory) {
    $breadcrumb->addLink($parentCategory->getVar('title'), 'viewcat.php?cid=' . $parentCategory->getVar('cid'));
}
if ($category->getVar('title') != '') {
    $breadcrumb->addLink($category->getVar('title'), '');
}
if (isset($_GET['list'])) {
    $breadcrumb->addLink($_GET['list'], '');
}
$xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

// Display Subcategories for selected Category
$allSubcategories = $categoriesTree->getFirstChild($cid);

if (is_array($allSubcategories) > 0 && !isset($_GET['list']) && !isset($_GET['selectdate'])) {
    $listings = wfdownloads_getTotalDownloads($allowedDownCategoriesIds);
    $scount   = 1;
    foreach ($allSubcategories as $subcategory) {
        $download_count = 0;
        // Check if subcategory is allowed
        if (!in_array($subcategory->getVar('cid'), $allowedDownCategoriesIds)) {
            continue;
        }

        $infercategories  = array();
        $catdowncount     = isset($listings['count'][$subcategory->getVar('cid')]) ? $listings['count'][$subcategory->getVar('cid')] : 0;
        $subsubCategories = $categoriesTree->getAllChild($subcategory->getVar('cid'));

        // ----- added for subcat images -----
        if (($subcategory->getVar('imgurl') != '')
            && is_file(
                XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('catimage') . '/' . $subcategory->getVar('imgurl')
            )
        ) {
            if ($wfdownloads->getConfig('usethumbs') && function_exists('gd_info')) {
                $imageURL = wfdownloads_createThumb(
                    $subcategory->getVar('imgurl'),
                    $wfdownloads->getConfig('catimage'),
                    'thumbs',
                    $wfdownloads->getConfig('cat_imgwidth'),
                    $wfdownloads->getConfig('cat_imgheight'),
                    $wfdownloads->getConfig('imagequality'),
                    $wfdownloads->getConfig('updatethumbs'),
                    $wfdownloads->getConfig('keepaspect')
                );
            } else {
                $imageURL = XOOPS_URL . '/' . $wfdownloads->getConfig('catimage') . '/' . $subcategory->getVar('imgurl');
            }
        } else {
            $imageURL = ''; //XOOPS_URL . '/' . $wfdownloads->getConfig('catimage') . '/blank.gif';
        }
        // ----- end subcat images -----

        if (count($subsubCategories) > 0) {
            foreach ($subsubCategories as $subsubCategory) {
                if (in_array($subsubCategory->getVar('cid'), $allowedDownCategoriesIds)) {
                    $download_count += isset($listings['count'][$subsubCategory->getVar('cid')]) ? $listings['count'][$subsubCategory->getVar('cid')]
                        : 0;
                    $infercategories[] = array(
                        'cid'             => $subsubCategory->getVar('cid'),
                        'id'              => $subsubCategory->getVar('cid'), // this definition is not removed for backward compatibility issues
                        'title'           => $subsubCategory->getVar('title'),
                        'image'           => $imageURL,
                        'image_URL'       => $imageURL,
                        'count'           => $download_count, // this definition is not removed for backward compatibility issues
                        'downloads_count' => $download_count
                    );
                }
            }
        } else {
            $download_count  = 0;
            $infercategories = array();
        }
        $catdowncount += $download_count;
        $download_count = 0;

        $xoopsTpl->append(
            'subcategories',
            array(
                 'title'               => $subcategory->getVar('title'),
                 'image'               => $imageURL, // this definition is not removed for backward compatibility issues
                 'image_URL'           => $imageURL,
                 'id'                  => $subcategory->getVar('cid'), // this definition is not removed for backward compatibility issues
                 'cid'                 => $subcategory->getVar('cid'),
                 'allowed_download'    => in_array($subcategory->getVar('cid'), $allowedDownCategoriesIds),
                 'allowed_upload'      => ($isSubmissionAllowed && in_array($subcategory->getVar('cid'), $allowedUpCategoriesIds)),
                 'summary'             => $subcategory->getVar('summary'),
                 'infercategories'     => $infercategories,
                 'subcategories'       => $infercategories,
                 'totallinks'          => $catdowncount, // this definition is not removed for backward compatibility issues
                 'downloads_count'     => $catdowncount,
                 'count'               => $scount, // this definition is not removed for backward compatibility issues
                 'subcategories_count' => $catdowncount,
            )
        );
        $scount++;
    }
}
if (isset($cid) && $cid > 0 && isset($categories[$cid])) {
    $xoopsTpl->assign('category_title', $categories[$cid]->getVar('title'));
    $xoopsTpl->assign('description', $categories[$cid]->getVar('description'));
    $xoopsTpl->assign('category_description', $categories[$cid]->getVar('description'));
    $xoopsTpl->assign('category_allowed_download', ($isSubmissionAllowed && in_array($cid, $allowedDownCategoriesIds)));
    $xoopsTpl->assign('category_allowed_upload', in_array($cid, $allowedUpCategoriesIds));

    // Making the category image and title available in the template
    if (($categories[$cid]->getVar('imgurl') != "")
        && is_file(
            XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('catimage') . '/' . $categories[$cid]->getVar('imgurl')
        )
    ) {
        if ($wfdownloads->getConfig('usethumbs') && function_exists('gd_info')) {
            $imageURL = wfdownloads_createThumb(
                $categories[$cid]->getVar('imgurl'),
                $wfdownloads->getConfig('catimage'),
                'thumbs',
                $wfdownloads->getConfig('cat_imgwidth'),
                $wfdownloads->getConfig('cat_imgheight'),
                $wfdownloads->getConfig('imagequality'),
                $wfdownloads->getConfig('updatethumbs'),
                $wfdownloads->getConfig('keepaspect')
            );
        } else {
            $imageURL = XOOPS_URL . '/' . $wfdownloads->getConfig('catimage') . '/' . $categories[$cid]->getVar('imgurl');
        }
    } else {
        $imageURL = '';
    }

    $xoopsTpl->assign('xoops_pagetitle', $categories[$cid]->getVar('title') . ' | ' . $wfdownloads->getModule()->name());
    $xoopsTpl->assign('category_image', $imageURL); // this definition is not removed for backward compatibility issues
    $xoopsTpl->assign('category_image_URL', $imageURL);
}

// Extract Download information from database
$xoopsTpl->assign('show_category_title', false);

if (isset($_GET['selectdate'])) {
    $criteria->add(new Criteria('', 'TO_DAYS(FROM_UNIXTIME(' . (int)$_GET['selectdate'] . '))', '=', '', 'TO_DAYS(FROM_UNIXTIME(published))'));
    $xoopsTpl->assign('show_categort_title', true);
} elseif (isset($_GET['list'])) {
    $criteria->setSort("{$orderby}, title");
    $criteria->add(new Criteria('title', $myts->addSlashes($_GET['list']) . '%', 'LIKE'));
    $xoopsTpl->assign('categoryPath', sprintf(_MD_WFDOWNLOADS_DOWNLOADS_LIST, htmlspecialchars($_GET['list'])));
    $xoopsTpl->assign('show_categort_title', true);
} else {
    $criteria->setSort("{$orderby}, title");
    $criteria->add(new Criteria('cid', $cid));
}
$downloads_count = $wfdownloads->getHandler('download')->getActiveCount($criteria);
$criteria->setLimit($wfdownloads->getConfig('perpage'));
$criteria->setStart($start);
$downloads = $wfdownloads->getHandler('download')->getActiveDownloads($criteria);

// Show Downloads by file
if ($downloads_count > 0) {
    foreach (array_keys($downloads) as $i) {
        $downloadInfo = $downloads[$i]->getDownloadInfo();
        $xoopsTpl->assign('lang_dltimes', sprintf(_MD_WFDOWNLOADS_DLTIMES, $downloadInfo['hits']));
        $xoopsTpl->assign('lang_subdate', $downloadInfo['is_updated']);
        $xoopsTpl->append('file', $downloadInfo); // this definition is not removed for backward compatibility issues
        $xoopsTpl->append('downloads', $downloadInfo);
    }

    // Show order box
    $xoopsTpl->assign('show_links', false);
    if ($downloads_count > 1 && $cid != 0) {
        $xoopsTpl->assign('show_links', true);
        $orderbyTrans = convertorderbytrans($orderby);
        $xoopsTpl->assign('orderby', convertorderbyout($orderby));
        $xoopsTpl->assign('lang_cursortedby', sprintf(_MD_WFDOWNLOADS_CURSORTBY, convertorderbytrans($orderby)));
        $orderby = convertorderbyout($orderby);
    }
    // Screenshots display
    $xoopsTpl->assign('show_screenshot', false);
    if ($wfdownloads->getConfig('screenshot') == 1) {
        $xoopsTpl->assign('shots_dir', $wfdownloads->getConfig('screenshots'));
        $xoopsTpl->assign('shotwidth', $wfdownloads->getConfig('shotwidth'));
        $xoopsTpl->assign('shotheight', $wfdownloads->getConfig('shotheight'));
        $xoopsTpl->assign('viewcat', true);
        $xoopsTpl->assign('show_screenshot', true);
    }

    // Nav page render
    include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
    if (isset($_GET['selectdate'])) {
        $pagenav = new XoopsPageNav($downloads_count, $wfdownloads->getConfig('perpage'), $start, 'start', 'list=' . urlencode($_GET['selectdate']));
    } elseif (isset($_GET['list'])) {
        $pagenav = new XoopsPageNav($downloads_count, $wfdownloads->getConfig('perpage'), $start, 'start', 'list=' . urlencode($_GET['list']));
    } else {
        $pagenav = new XoopsPageNav($downloads_count, $wfdownloads->getConfig('perpage'), $start, 'start', 'cid=' . $cid);
    }
    $page_nav = $pagenav->renderNav();
    $xoopsTpl->assign('page_nav', (isset($page_nav) && !empty($page_nav))); // this definition is not removed for backward compatibility issues
    $xoopsTpl->assign('pagenav', $pagenav->renderNav());
}

$xoopsTpl->assign('use_mirrors', $wfdownloads->getConfig('enable_mirrors'));
$xoopsTpl->assign('use_ratings', $wfdownloads->getConfig('enable_ratings'));
$xoopsTpl->assign('use_reviews', $wfdownloads->getConfig('enable_reviews'));
$xoopsTpl->assign('use_rss', $wfdownloads->getConfig('enablerss'));

if ($wfdownloads->getConfig('enablerss') == true && $downloads_count > 0) {
    $rsslink_URL = WFDOWNLOADS_URL . "/rss.php?cid={$cid}";
    $xoopsTpl->assign('category_rssfeed_URL', $rsslink_URL);
    $rsslink = "<a href='" . $rsslink_URL . "' title='" . _MD_WFDOWNLOADS_LEGENDTEXTCATRSS . "'><img src='" . XOOPS_URL . "/modules/"
        . $wfdownloads->getModule()->getVar('dirname') . "/images/icon/rss.gif' border='0' alt='" . _MD_WFDOWNLOADS_LEGENDTEXTCATRSS . "' title='"
        . _MD_WFDOWNLOADS_LEGENDTEXTCATRSS . "'></a>";
    $xoopsTpl->assign('cat_rssfeed_link', $rsslink); // this definition is not removed for backward compatibility issues
}

include 'footer.php';
