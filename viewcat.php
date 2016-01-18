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

$cid   = XoopsRequest::getInt('cid', 0);
$start = XoopsRequest::getInt('start', 0);
//$list = XoopsRequest::getString('list', null);
//$orderby = XoopsRequest::getString('orderby', null);
$orderby = isset($_GET['orderby']) ? convertorderbyin($_GET['orderby']) : $wfdownloads->getConfig('filexorder');

$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);

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
if (is_object($GLOBALS['xoopsUser'])
    && ($wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_DOWNLOAD
        || $wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_BOTH)
) {
    // if user is a registered user
    $groups = $GLOBALS['xoopsUser']->getGroups();
    if (count(array_intersect($wfdownloads->getConfig('submitarts'), $groups)) > 0) {
        $isSubmissionAllowed = true;
    }
} else {
    // if user is anonymous
    if ($wfdownloads->getConfig('anonpost') == _WFDOWNLOADS_ANONPOST_DOWNLOAD || $wfdownloads->getConfig('anonpost') == _WFDOWNLOADS_ANONPOST_BOTH) {
        $isSubmissionAllowed = true;
    }
}

// Get category object
$categoryObj = $wfdownloads->getHandler('category')->get($cid);
if (empty($categoryObj)) {
    redirect_header('index.php', 3, _CO_WFDOWNLOADS_ERROR_NOCATEGORY);
}

// Get download/upload permissions
$allowedDownCategoriesIds = $gperm_handler->getItemIds('WFDownCatPerm', $groups, $wfdownloads->getModule()->mid());
$allowedUpCategoriesIds   = $gperm_handler->getItemIds('WFUpCatPerm', $groups, $wfdownloads->getModule()->mid());

$xoopsOption['template_main'] = "{$wfdownloads->getModule()->dirname()}_viewcat.tpl";
include_once XOOPS_ROOT_PATH . '/header.php';

$xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
$xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

$xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

$xoopsTpl->assign('cid', $cid); // this definition is not removed for backward compatibility issues
$xoopsTpl->assign('category_id', $cid); // this definition is not removed for backward compatibility issues
$xoopsTpl->assign('category_cid', $cid);

// Retreiving the top parent category
if (!isset($_GET['list']) && !isset($_GET['selectdate'])) {
    $categoriesTopParentByCid = $wfdownloads->getHandler('category')->getAllSubcatsTopParentCid();
    $topCategoryObj           = $wfdownloads->getHandler('category')->get($categoriesTopParentByCid[$cid]);

    $xoopsTpl->assign('topcategory_title', $topCategoryObj->getVar('title'));
    $xoopsTpl->assign('topcategory_image', $topCategoryObj->getVar('imgurl')); // this definition is not removed for backward compatibility issues
    $xoopsTpl->assign('topcategory_image_URL', $topCategoryObj->getVar('imgurl'));
    $xoopsTpl->assign('topcategory_cid', $topCategoryObj->getVar('cid'));
}

// Formulize module support (2006/05/04) jpc - start
if (wfdownloads_checkModule('formulize')) {
    $formulize_fid = $categoryObj->getVar('formulize_fid');
    if ($formulize_fid) {
        $xoopsTpl->assign('custom_form', true);
    } else {
        $xoopsTpl->assign('custom_form', false);
    }
}
// Formulize module support (2006/05/04) jpc - end

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
$categoryObjs = $wfdownloads->getHandler('category')->getObjects($criteria, true);
include_once XOOPS_ROOT_PATH . '/class/tree.php';
$categoryObjsTree = new XoopsObjectTree($categoryObjs, 'cid', 'pid');

// Breadcrumb
$breadcrumb = new WfdownloadsBreadcrumb();
$breadcrumb->addLink($wfdownloads->getModule()->getVar('name'), WFDOWNLOADS_URL);
foreach (array_reverse($categoryObjsTree->getAllParent($cid)) as $parentCategoryObj) {
    $breadcrumb->addLink($parentCategoryObj->getVar('title'), 'viewcat.php?cid=' . $parentCategoryObj->getVar('cid'));
}
if ($categoryObj->getVar('title') != '') {
    $breadcrumb->addLink($categoryObj->getVar('title'), '');
}
if (isset($_GET['list'])) {
    $breadcrumb->addLink($_GET['list'], '');
}
$xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

// Display Subcategories for selected Category
$allSubCategoryObjs = $categoryObjsTree->getFirstChild($cid);

if (is_array($allSubCategoryObjs) > 0 && !isset($_GET['list']) && !isset($_GET['selectdate'])) {
    $listings = wfdownloads_getTotalDownloads($allowedDownCategoriesIds);
    $scount   = 1;
    foreach ($allSubCategoryObjs as $subCategoryObj) {
        $download_count = 0;
        // Check if subcategory is allowed
        if (!in_array($subCategoryObj->getVar('cid'), $allowedDownCategoriesIds)) {
            continue;
        }

        $infercategories    = array();
        $catdowncount       = isset($listings['count'][$subCategoryObj->getVar('cid')]) ? $listings['count'][$subCategoryObj->getVar('cid')] : 0;
        $subsubCategoryObjs = $categoryObjsTree->getAllChild($subCategoryObj->getVar('cid'));

        // ----- added for subcat images -----
        if (($subCategoryObj->getVar('imgurl') != '')
            && is_file(
                XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('catimage') . '/' . $subCategoryObj->getVar('imgurl')
            )
        ) {
            if ($wfdownloads->getConfig('usethumbs') && function_exists('gd_info')) {
                $imageURL = wfdownloads_createThumb(
                    $subCategoryObj->getVar('imgurl'),
                    $wfdownloads->getConfig('catimage'),
                    'thumbs',
                    $wfdownloads->getConfig('cat_imgwidth'),
                    $wfdownloads->getConfig('cat_imgheight'),
                    $wfdownloads->getConfig('imagequality'),
                    $wfdownloads->getConfig('updatethumbs'),
                    $wfdownloads->getConfig('keepaspect')
                );
            } else {
                $imageURL = XOOPS_URL . '/' . $wfdownloads->getConfig('catimage') . '/' . $subCategoryObj->getVar('imgurl');
            }
        } else {
            $imageURL = ''; //XOOPS_URL . '/' . $wfdownloads->getConfig('catimage') . '/blank.gif';
        }
        // ----- end subcat images -----

        if (count($subsubCategoryObjs) > 0) {
            foreach ($subsubCategoryObjs as $subsubCategoryObj) {
                if (in_array($subsubCategoryObj->getVar('cid'), $allowedDownCategoriesIds)) {
                    $download_count += isset($listings['count'][$subsubCategoryObj->getVar('cid')]) ? $listings['count'][$subsubCategoryObj->getVar('cid')] : 0;
                    $infercategories[] = array(
                        'cid'             => $subsubCategoryObj->getVar('cid'),
                        'id'              => $subsubCategoryObj->getVar('cid'), // this definition is not removed for backward compatibility issues
                        'title'           => $subsubCategoryObj->getVar('title'),
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
                'title'               => $subCategoryObj->getVar('title'),
                'image'               => $imageURL, // this definition is not removed for backward compatibility issues
                'image_URL'           => $imageURL,
                'id'                  => $subCategoryObj->getVar('cid'), // this definition is not removed for backward compatibility issues
                'cid'                 => $subCategoryObj->getVar('cid'),
                'allowed_download'    => in_array($subCategoryObj->getVar('cid'), $allowedDownCategoriesIds),
                'allowed_upload'      => ($isSubmissionAllowed && in_array($subCategoryObj->getVar('cid'), $allowedUpCategoriesIds)),
                'summary'             => $subCategoryObj->getVar('summary'),
                'infercategories'     => $infercategories,
                'subcategories'       => $infercategories,
                'totallinks'          => $catdowncount, // this definition is not removed for backward compatibility issues
                'downloads_count'     => $catdowncount,
                'count'               => $scount, // this definition is not removed for backward compatibility issues
                'subcategories_count' => $catdowncount,
            )
        );
        ++$scount;
    }
}
if (isset($cid) && $cid > 0 && isset($categoryObjs[$cid])) {
    $xoopsTpl->assign('category_title', $categoryObjs[$cid]->getVar('title'));
    $xoopsTpl->assign('description', $categoryObjs[$cid]->getVar('description'));
    $xoopsTpl->assign('category_description', $categoryObjs[$cid]->getVar('description'));
    $xoopsTpl->assign('category_allowed_download', ($isSubmissionAllowed && in_array($cid, $allowedDownCategoriesIds)));
    $xoopsTpl->assign('category_allowed_upload', in_array($cid, $allowedUpCategoriesIds));

    // Making the category image and title available in the template
    if (($categoryObjs[$cid]->getVar('imgurl') != '')
        && is_file(
            XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('catimage') . '/' . $categoryObjs[$cid]->getVar('imgurl')
        )
    ) {
        if ($wfdownloads->getConfig('usethumbs') && function_exists('gd_info')) {
            $imageURL = wfdownloads_createThumb(
                $categoryObjs[$cid]->getVar('imgurl'),
                $wfdownloads->getConfig('catimage'),
                'thumbs',
                $wfdownloads->getConfig('cat_imgwidth'),
                $wfdownloads->getConfig('cat_imgheight'),
                $wfdownloads->getConfig('imagequality'),
                $wfdownloads->getConfig('updatethumbs'),
                $wfdownloads->getConfig('keepaspect')
            );
        } else {
            $imageURL = XOOPS_URL . '/' . $wfdownloads->getConfig('catimage') . '/' . $categoryObjs[$cid]->getVar('imgurl');
        }
    } else {
        $imageURL = '';
    }

    $xoopsTpl->assign('xoops_pagetitle', $categoryObjs[$cid]->getVar('title') . ' | ' . $wfdownloads->getModule()->name());
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
$downloadObjs = $wfdownloads->getHandler('download')->getActiveDownloads($criteria);

// Show Downloads by file
if ($downloads_count > 0) {
    foreach ($downloadObjs as $downloadObj) {
        $downloadInfo = $downloadObj->getDownloadInfo();
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
    $rsslink = "<a href='" . $rsslink_URL . "' title='" . _MD_WFDOWNLOADS_LEGENDTEXTCATRSS . "'><img src='" . XOOPS_URL . "/modules/" . $wfdownloads->getModule()->getVar('dirname')
        . "/assets/images/icon/rss.gif' border='0' alt='" . _MD_WFDOWNLOADS_LEGENDTEXTCATRSS . "' title='" . _MD_WFDOWNLOADS_LEGENDTEXTCATRSS . "'></a>";
    $xoopsTpl->assign('cat_rssfeed_link', $rsslink); // this definition is not removed for backward compatibility issues
}

include_once __DIR__ . '/footer.php';

?>
<script type="text/javascript">

    $('.magnific_zoom').magnificPopup({
        type               : 'image',
        image              : {
            cursor     : 'mfp-zoom-out-cur',
            titleSrc   : "title",
            verticalFit: true,
            tError     : 'The image could not be loaded.' // Error message
        },
        iframe             : {
            patterns: {
                youtube : {
                    index: 'youtube.com/',
                    id   : 'v=',
                    src  : '//www.youtube.com/embed/%id%?autoplay=1'
                }, vimeo: {
                    index: 'vimeo.com/',
                    id   : '/',
                    src  : '//player.vimeo.com/video/%id%?autoplay=1'
                }, gmaps: {
                    index: '//maps.google.',
                    src  : '%id%&output=embed'
                }
            }
        },
        preloader          : true,
        showCloseBtn       : true,
        closeBtnInside     : false,
        closeOnContentClick: true,
        closeOnBgClick     : true,
        enableEscapeKey    : true,
        modal              : false,
        alignTop           : false,
        mainClass          : 'mfp-img-mobile mfp-fade',
        zoom               : {
            enabled : true,
            duration: 300,
            easing  : 'ease-in-out'
        },
        removalDelay       : 200
    });
</script>
