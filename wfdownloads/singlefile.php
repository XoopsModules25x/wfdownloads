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

// Check permissions
$userGroups = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);
if (!$gperm_handler->checkRight('WFDownCatPerm', $cid, $userGroups, $wfdownloads->getModule()->mid())) {
    if (in_array(XOOPS_GROUP_ANONYMOUS, $userGroups)) {
        redirect_header(XOOPS_URL . '/user.php', 3, _MD_WFDOWNLOADS_NEEDLOGINVIEW);
    } else {
        redirect_header('index.php', 3, _NOPERM);
    }
}

// Get download
if ($download->isNew()) {
    redirect_header('index.php', 1, _MD_WFDOWNLOADS_NODOWNLOAD);
}

// If Download not published, expired or taken offline - redirect
if (
    $download->getVar('published') == 0 ||
    $download->getVar('published') > time() ||
    $download->getVar('offline') == true ||
    ($download->getVar('expired') != 0 && $download->getVar('expired') < time()) ||
    $download->getVar('status') == _WFDOWNLOADS_STATUS_WAITING) {
    redirect_header('index.php', 3, _MD_WFDOWNLOADS_NODOWNLOAD);
}

// Load Template
$xoopsOption['template_main'] = "{$wfdownloads->getModule()->dirname()}_singlefile.tpl";
include XOOPS_ROOT_PATH . '/header.php';

$xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
$xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

$xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

// Making the category image and title available in the template
if (($category->getVar('imgurl') != "") && is_file(XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('catimage') . '/' . $category->getVar('imgurl'))) {
    if ($wfdownloads->getConfig('usethumbs') && function_exists('gd_info')) {
        $imgurl = wfdownloads_createThumb(
            $category->getVar('imgurl'),
            $wfdownloads->getConfig('catimage'),
            'thumbs',
            $wfdownloads->getConfig('cat_imgwidth'),
            $wfdownloads->getConfig('cat_imgheight'),
            $wfdownloads->getConfig('imagequality'),
            $wfdownloads->getConfig('updatethumbs'),
            $wfdownloads->getConfig('keepaspect')
        );
    } else {
        $imgurl = XOOPS_URL . '/' . $wfdownloads->getConfig('catimage') . '/' . $category->getVar('imgurl');
    }
} else {
    $imgurl = XOOPS_URL . '/' . $wfdownloads->getConfig('catimage') . '/blank.gif';
}
$xoopsTpl->assign('category_title', $category->getVar('title'));
$xoopsTpl->assign('category_image', $imgurl);

// Retreiving the top parent category
$allSubcatsTopParentCid = $wfdownloads->getHandler('category')->getAllSubcatsTopParentCid();
$topCategory            = $wfdownloads->getHandler('category')->allCategories[$allSubcatsTopParentCid[$download->getVar('cid')]];
$xoopsTpl->assign('topcategory_title', $topCategory->getVar('title'));
$xoopsTpl->assign('topcategory_image', $topCategory->getVar('imgurl'));
$xoopsTpl->assign('topcategory_cid', $topCategory->getVar('cid'));

// Added Formulize module support (2006/03/06, 2006/03/08) jpc - start
$formulize_idreq = $download->getVar('formulize_idreq');
if (wfdownloads_checkModule('formulize') && $formulize_idreq) {
    $xoopsTpl->assign('custom_form', 1);
    include_once XOOPS_ROOT_PATH . '/modules/formulize/include/extract.php';
    // get the form id and id_req of the user's entry
    $formulizeModule = $module_handler->getByDirname('formulize');
    $formulizeConfig = $config_handler->getConfigsByCat(0, $formulizeModule->mid());

    $formulize_fid = $category->getVar('formulize_fid');

    if ($formulize_fid) {
        $query = "SELECT desc_form";
        $query .= " FROM " . $xoopsDB->prefix('formulize_id');
        $query .= " WHERE id_form = '$formulize_fid'";
        $formulize_formres = $xoopsDB->query($query);
        if ($formulize_formarray = $xoopsDB->fetchArray($formulize_formres)) {
            $desc_form = $formulize_formarray['desc_form'];

            // query the form for its data
            $data = getData("", $formulize_fid, $formulize_idreq);
            // include only elements that are visible to the user's groups in the DB query below
            $userGroups = $xoopsUser ? $xoopsUser->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);
            $start      = 1;
            foreach ($userGroups as $thisgroup) {
                if ($start) {
                    $userGroups_query = "ele_display LIKE '%,$thisgroup,%'";
                    $start            = 0;
                } else {
                    $userGroups_query .= " OR ele_display LIKE '%,$thisgroup,%'";
                }
            }
            // collect the element id numbers for use in a DB query, and apply the groups filter to each
            $start = 1;
            foreach ($data[0][$desc_form][$formulize_idreq] as $ele_id => $values) {
                if ($start) {
                    $ele_id_query = "(ele_id='$ele_id' AND (ele_display=1 OR ($userGroups_query)))";
                    $start        = 0;
                } else {
                    $ele_id_query .= " OR (ele_id='$ele_id' AND (ele_display=1 OR ($userGroups_query)))";
                }
            }
            // get the captions for the elements that are visible to the user's groups
            $query = "SELECT ele_caption, ele_id, ele_display";
            $query .= " FROM " . $xoopsDB->prefix("formulize");
            $query .= " WHERE ($ele_id_query) AND ele_type <> 'ib' AND ele_type <> 'sep' AND ele_type <> 'areamodif'";
            $query .= " ORDER BY ele_order";
            $captionres = $xoopsDB->query($query);
            // collect the captions and their values into an array for passing to the template
            $indexer = 0;
            while ($captionarray = $xoopsDB->fetchArray($captionres)) {
                $formulize_download[$indexer]['caption'] = $captionarray['ele_caption'];
                if (count($data[0][$desc_form][$formulize_idreq][$captionarray['ele_id']]) > 1) {
                    $formulize_download[$indexer]['values'][] = implode(', ', $data[0][$desc_form][$formulize_idreq][$captionarray['ele_id']]);
                } else {
                    $formulize_download[$indexer]['values'][] = $data[0][$desc_form][$formulize_idreq][$captionarray['ele_id']][0];
                }
                ++$indexer;
            }
            $xoopsTpl->assign('formulize_download', $formulize_download);
        }
    }
} else {
    $xoopsTpl->assign('custom_form', 0);
}
// Added Formulize module support (2006/03/06, 2006/03/08) jpc - end

$use_mirrors = $wfdownloads->getConfig('enable_mirrors');
$add_mirror  = false;
if (!is_object($xoopsUser) && $use_mirrors == true
    && ($wfdownloads->getConfig('anonpost') == _WFDOWNLOADS_ANONPOST_MIRROR
        || $wfdownloads->getConfig('anonpost') == _WFDOWNLOADS_ANONPOST_BOTH)
    && ($wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_MIRROR
        || $wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_BOTH)
) {
    $add_mirror = true;
} elseif (is_object($xoopsUser) && $use_mirrors == true
    && ($wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_MIRROR
        || $wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_BOTH
        || wfdownloads_userIsAdmin())
) {
    $add_mirror = true;
}


// Get download informations
$downloadInfo = $download->getDownloadInfo();
$xoopsTpl->assign('categoryPath', $downloadInfo['path'] . ' > ' . $downloadInfo['title']); // this definition is not removed for backward compatibility issues
$xoopsTpl->assign('lang_dltimes', sprintf(_MD_WFDOWNLOADS_DLTIMES, $downloadInfo['hits']));
$xoopsTpl->assign('lang_subdate', $downloadInfo['is_updated']);
$xoopsTpl->append('file', $downloadInfo);
$xoopsTpl->assign('show_screenshot', false);
if ($wfdownloads->getConfig('screenshot') == 1) {
    $xoopsTpl->assign('shots_dir', $wfdownloads->getConfig('screenshots'));
    $xoopsTpl->assign('shotwidth', $wfdownloads->getConfig('shotwidth'));
    $xoopsTpl->assign('shotheight', $wfdownloads->getConfig('shotheight'));
    $xoopsTpl->assign('show_screenshot', true);
}
// Get file url
$fullFilename = trim($downloadInfo['filename']);
if ((!$downloadInfo['url'] == '' && !$downloadInfo['url'] == 'http://') || $fullFilename == '') {
    $fileUrl = $myts->htmlSpecialChars(preg_replace('/javascript:/si', 'javascript:', $downloadInfo['url']), ENT_QUOTES);
} else {
    $mimeType     = $downloadInfo['filetype'];
    $file         = strrev($fullFilename);
    $tempFilename = strtolower(strrev(substr($file, 0, strpos($file, '--'))));
    $filename     = ($tempFilename == '') ? $fullFilename : $tempFilename;
    $fileUrl     = XOOPS_URL . str_replace(XOOPS_ROOT_PATH, '', $wfdownloads->getConfig('uploaddir')) . '/' . stripslashes(trim($fullFilename));

}
$xoopsTpl->assign('file_url', $fileUrl);

// Breadcrumb
include_once XOOPS_ROOT_PATH . '/class/tree.php';
$categoriesTree = new XoopsObjectTree($wfdownloads->getHandler('category')->getObjects(), 'cid', 'pid');
$breadcrumb     = new WfdownloadsBreadcrumb();
$breadcrumb->addLink($wfdownloads->getModule()->getVar('name'), WFDOWNLOADS_URL);
foreach (array_reverse($categoriesTree->getAllParent($cid)) as $parentCategory) {
    $breadcrumb->addLink($parentCategory->getVar('title'), "viewcat.php?cid=" . $parentCategory->getVar('cid'));
}
$breadcrumb->addLink($category->getVar('title'), "viewcat.php?cid=" . $category->getVar('cid'));
$breadcrumb->addLink($downloadInfo['title'], '');
$xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

// Show other author downloads
$criteria = new CriteriaCompo(new Criteria('submitter', $download->getVar('submitter')));
$criteria->add(new Criteria('lid', $lid, '!='));
$criteria->setLimit(20);
$criteria->setSort('published');
$criteria->setOrder('DESC');
$downloads = $wfdownloads->getHandler('download')->getActiveDownloads($criteria);
foreach ($downloads as $download) {
    $downloadByUser['title']     = $download->getVar('title');
    $downloadByUser['lid']       = (int) $download->getVar('lid');
    $downloadByUser['cid']       = (int) $download->getVar('cid');
    $downloadByUser['published'] = formatTimestamp($download->getVar('published'), $wfdownloads->getConfig('dateformat'));
    $xoopsTpl->append('down_uid', $downloadByUser); // this definition is not removed for backward compatibility issues
    $xoopsTpl->append('downloads_by_user', $downloadByUser);
}

$cid = (int) $download->getVar('cid');
$lid = (int) $download->getVar('lid');

// User reviews
$criteria = new CriteriaCompo(new Criteria("lid", $lid));
$criteria->add(new Criteria("submit", 1));
$reviewsCount = $wfdownloads->getHandler('review')->getCount($criteria);
if ($reviewsCount > 0) {
    $user_reviews = "op=list&amp;cid={$cid}&amp;lid={$lid}\">" . _MD_WFDOWNLOADS_USERREVIEWS;
} else {
    $user_reviews = "cid={$cid}&amp;lid={$lid}\">" . _MD_WFDOWNLOADS_NOUSERREVIEWS;
}
$xoopsTpl->assign('lang_user_reviews', $xoopsConfig['sitename'] . ' ' . _MD_WFDOWNLOADS_USERREVIEWSTITLE);
$xoopsTpl->assign('lang_UserReviews', sprintf($user_reviews, $download->getVar('title')));
$xoopsTpl->assign('review_amount', $reviewsCount);

// User mirrors
$downloadInfo['add_mirror'] = $add_mirror;
$criteria                   = new CriteriaCompo(new Criteria('lid', $lid));
$criteria->add(new Criteria('submit', 1));
$mirrorsCount = $wfdownloads->getHandler('mirror')->getCount($criteria);
if ($mirrorsCount > 0) {
    $user_mirrors = "op=list&amp;cid={$cid}&amp;lid={$lid}\">" . _MD_WFDOWNLOADS_USERMIRRORS;
} else {
    $user_mirrors = "cid={$cid}&amp;lid={$lid}\">" . _MD_WFDOWNLOADS_NOUSERMIRRORS;
}
$xoopsTpl->assign('lang_user_mirrors', $xoopsConfig['sitename'] . ' ' . _MD_WFDOWNLOADS_USERMIRRORSTITLE);
$xoopsTpl->assign('lang_UserMirrors', sprintf($user_mirrors, $download->getVar('title')));
$xoopsTpl->assign('mirror_amount', $mirrorsCount);

$xoopsTpl->assign('use_ratings', $wfdownloads->getConfig('enable_mirrors'));
$xoopsTpl->assign('use_ratings', $wfdownloads->getConfig('enable_ratings'));
$xoopsTpl->assign('use_reviews', $wfdownloads->getConfig('enable_reviews'));
$xoopsTpl->assign('use_brokenreports', $wfdownloads->getConfig('enable_brokenreports'));
$xoopsTpl->assign('use_rss', $wfdownloads->getConfig('enablerss'));

// Copyright
if ($wfdownloads->getConfig('copyright') == true) {
    $xoopsTpl->assign('lang_copyright', $download->getVar('title') . ' &copy; ' . _MD_WFDOWNLOADS_COPYRIGHT . ' ' . formatTimestamp(time(), 'Y'));
}
$xoopsTpl->assign('down', $downloadInfo); // this definition is not removed for backward compatibility issues
$xoopsTpl->assign('download', $downloadInfo);

include XOOPS_ROOT_PATH . '/include/comment_view.php';

$xoopsTpl->assign('com_rule', $wfdownloads->getConfig('com_rule'));
$xoopsTpl->assign('module_home', wfdownloads_module_home(true));
include 'footer.php';

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
        gallery:{
            enabled:true
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
