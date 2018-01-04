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
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         wfdownload
 * @since           3.23
 * @author          Xoops Development Team
 */

use Xmf\Request;
use XoopsModules\Wfdownloads;
use XoopsModules\Wfdownloads\Common;

$currentFile = basename(__FILE__);
require_once __DIR__ . '/header.php';

$lid         = Request::getInt('lid', 0);
$downloadObj = $helper->getHandler('download')->get($lid);
if (empty($downloadObj)) {
    redirect_header('index.php', 3, _CO_WFDOWNLOADS_ERROR_NODOWNLOAD);
}
$cid         = Request::getInt('cid', $downloadObj->getVar('cid'));
$categoryObj = $helper->getHandler('category')->get($cid);
if (empty($categoryObj)) {
    redirect_header('index.php', 3, _CO_WFDOWNLOADS_ERROR_NOCATEGORY);
}

// Check permissions
$userGroups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : [0 => XOOPS_GROUP_ANONYMOUS];
if (!$gpermHandler->checkRight('WFDownCatPerm', $cid, $userGroups, $helper->getModule()->mid())) {
    if (in_array(XOOPS_GROUP_ANONYMOUS, $userGroups)) {
        redirect_header(XOOPS_URL . '/user.php', 3, _MD_WFDOWNLOADS_NEEDLOGINVIEW);
    } else {
        redirect_header('index.php', 3, _NOPERM);
    }
}

// Get download
if ($downloadObj->isNew()) {
    redirect_header('index.php', 1, _MD_WFDOWNLOADS_NODOWNLOAD);
}

// If Download not published, expired or taken offline - redirect
if (0 == $downloadObj->getVar('published') || $downloadObj->getVar('published') > time()
    || true === $downloadObj->getVar('offline')
    || (0 != $downloadObj->getVar('expired')
        && $downloadObj->getVar('expired') < time())
    || _WFDOWNLOADS_STATUS_WAITING == $downloadObj->getVar('status')) {
    redirect_header('index.php', 3, _MD_WFDOWNLOADS_NODOWNLOAD);
}

// Load Template
$GLOBALS['xoopsOption']['template_main'] = "{$helper->getModule()->dirname()}_singlefile.tpl";
require_once XOOPS_ROOT_PATH . '/header.php';

$xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
$xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
$xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

$xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

// Making the category image and title available in the template
if (('' != $categoryObj->getVar('imgurl'))
    && is_file(XOOPS_ROOT_PATH . '/' . $helper->getConfig('catimage') . '/' . $categoryObj->getVar('imgurl'))) {
    if ($helper->getConfig('usethumbs') && function_exists('gd_info')) {
        $imgurl = Wfdownloads\Utility::createThumb(
            $categoryObj->getVar('imgurl'),
            $helper->getConfig('catimage'),
            'thumbs',
            $helper->getConfig('cat_imgwidth'),
            $helper->getConfig('cat_imgheight'),
            $helper->getConfig('imagequality'),
            $helper->getConfig('updatethumbs'),
                                                  $helper->getConfig('keepaspect')
        );
    } else {
        $imgurl = XOOPS_URL . '/' . $helper->getConfig('catimage') . '/' . $categoryObj->getVar('imgurl');
    }
} else {
    $imgurl = XOOPS_URL . '/' . $helper->getConfig('catimage') . '/blank.png';
}
$xoopsTpl->assign('category_title', $categoryObj->getVar('title'));
$xoopsTpl->assign('category_image', $imgurl);

// Retreiving the top parent category
$categoriesTopParentByCid = $helper->getHandler('category')->getAllSubcatsTopParentCid();
$topCategoryObj           = $helper->getHandler('category')->get($categoriesTopParentByCid[$cid]);

$xoopsTpl->assign('topcategory_title', $topCategoryObj->getVar('title'));
$xoopsTpl->assign('topcategory_image', $topCategoryObj->getVar('imgurl'));
$xoopsTpl->assign('topcategory_cid', $topCategoryObj->getVar('cid'));

// Formulize module support (2006/03/06, 2006/03/08) jpc - start
$formulize_idreq = $downloadObj->getVar('formulize_idreq');
if (Wfdownloads\Utility::checkModule('formulize') && $formulize_idreq) {
    $xoopsTpl->assign('custom_form', true);
    require_once XOOPS_ROOT_PATH . '/modules/formulize/include/extract.php';
    // get the form id and id_req of the user's entry
    $formulizeModule = $moduleHandler->getByDirname('formulize');
    $formulizeConfig = $configHandler->getConfigsByCat(0, $formulizeModule->mid());

    $formulize_fid = $categoryObj->getVar('formulize_fid');

    if ($formulize_fid) {
        // get Formulize form description
        $sql                 = 'SELECT desc_form';
        $sql                 .= " FROM {$GLOBALS['xoopsDB']->prefix('formulize_id')}";
        $sql                 .= " WHERE id_form = '{$formulize_fid}'";
        $formulize_formQuery = $GLOBALS['xoopsDB']->query($sql);
        if (false !== ($formulize_form_array = $GLOBALS['xoopsDB']->fetchArray($formulize_formQuery))) {
            $desc_form = $formulize_form_array['desc_form'];

            // query the form for its data
            $data = getData('', $formulize_fid, $formulize_idreq); // is a Formulize function
            // include only elements that are visible to the user's groups in the DB query below
            $userGroups = $GLOBALS['xoopsUser'] ? $GLOBALS['xoopsUser']->getGroups() : [0 => XOOPS_GROUP_ANONYMOUS];
            $start      = 1;
            foreach ($userGroups as $thisgroup) {
                if ($start) {
                    $userGroups_query = "ele_display LIKE '%,{$thisgroup},%'";
                    $start            = 0;
                } else {
                    $userGroups_query .= " OR ele_display LIKE '%,{$thisgroup},%'";
                }
            }
            // collect the element id numbers for use in a DB query, and apply the groups filter to each
            $start = 1;
            foreach ($data[0][$desc_form][$formulize_idreq] as $ele_id => $values) {
                if ($start) {
                    $ele_id_query = "(ele_id='{$ele_id}' AND (ele_display=1 OR ({$userGroups_query})))";
                    $start        = 0;
                } else {
                    $ele_id_query .= " OR (ele_id='{$ele_id}' AND (ele_display=1 OR ({$userGroups_query})))";
                }
            }
            // get the captions for the elements that are visible to the user's groups
            $sql          = 'SELECT ele_caption, ele_id, ele_display';
            $sql          .= " FROM {$GLOBALS['xoopsDB']->prefix('formulize')}";
            $sql          .= " WHERE ({$ele_id_query}) AND ele_type <> 'ib' AND ele_type <> 'sep' AND ele_type <> 'areamodif'";
            $sql          .= ' ORDER BY ele_order';
            $captionQuery = $GLOBALS['xoopsDB']->query($sql);
            // collect the captions and their values into an array for passing to the template
            $formulize_fields = [];
            $i                = 0;
            while (false !== ($caption_array = $GLOBALS['xoopsDB']->fetchArray($captionQuery))) {
                $formulize_fields[$i]['caption'] = $caption_array['ele_caption'];
                if (count($data[0][$desc_form][$formulize_idreq][$caption_array['ele_id']]) > 1) {
                    $formulize_fields[$i]['values'][] = implode(', ', $data[0][$desc_form][$formulize_idreq][$caption_array['ele_id']]);
                } else {
                    $formulize_fields[$i]['values'][] = $data[0][$desc_form][$formulize_idreq][$caption_array['ele_id']][0];
                }
                ++$i;
            }
            $xoopsTpl->assign('formulize_download', $formulize_fields); // this definition is not removed for backward compatibility issues
            $xoopsTpl->assign('custom_fields', $formulize_fields);
        }
    }
} else {
    $xoopsTpl->assign('custom_form', false);
}
// Formulize module support (2006/03/06, 2006/03/08) jpc - end

$use_mirrors = $helper->getConfig('enable_mirrors');
$add_mirror  = false;
if (!is_object($GLOBALS['xoopsUser']) && true === $use_mirrors
    && (_WFDOWNLOADS_ANONPOST_MIRROR == $helper->getConfig('anonpost')
        || _WFDOWNLOADS_ANONPOST_BOTH == $helper->getConfig('anonpost'))
    && (_WFDOWNLOADS_SUBMISSIONS_MIRROR == $helper->getConfig('submissions')
        || _WFDOWNLOADS_SUBMISSIONS_BOTH == $helper->getConfig('submissions'))) {
    $add_mirror = true;
} elseif (is_object($GLOBALS['xoopsUser']) && true === $use_mirrors
          && (_WFDOWNLOADS_SUBMISSIONS_MIRROR == $helper->getConfig('submissions')
              || _WFDOWNLOADS_SUBMISSIONS_BOTH == $helper->getConfig('submissions')
              || Wfdownloads\Utility::userIsAdmin())) {
    $add_mirror = true;
}

// Get download informations
$downloadInfo = $downloadObj->getDownloadInfo();
$xoopsTpl->assign('categoryPath', $downloadInfo['path'] . ' > ' . $downloadInfo['title']); // this definition is not removed for backward compatibility issues
$xoopsTpl->assign('lang_dltimes', sprintf(_MD_WFDOWNLOADS_DLTIMES, $downloadInfo['hits']));
$xoopsTpl->assign('lang_subdate', $downloadInfo['is_updated']);
$xoopsTpl->assign('file_url', $downloadInfo['file_url']); // this definition is not removed for backward compatibility issues
$xoopsTpl->append('file', $downloadInfo);
$xoopsTpl->assign('show_screenshot', false);
if (1 == $helper->getConfig('screenshot')) {
    $xoopsTpl->assign('shots_dir', $helper->getConfig('screenshots'));
    $xoopsTpl->assign('shotwidth', $helper->getConfig('shotwidth'));
    $xoopsTpl->assign('shotheight', $helper->getConfig('shotheight'));
    $xoopsTpl->assign('show_screenshot', true);
}

// Breadcrumb
require_once XOOPS_ROOT_PATH . '/class/tree.php';
$categoryObjsTree = new \XoopsObjectTree($helper->getHandler('category')->getObjects(), 'cid', 'pid');
$breadcrumb       = new common\Breadcrumb();
$breadcrumb->addLink($helper->getModule()->getVar('name'), WFDOWNLOADS_URL);
foreach (array_reverse($categoryObjsTree->getAllParent($cid)) as $parentCategory) {
    $breadcrumb->addLink($parentCategory->getVar('title'), 'viewcat.php?cid=' . $parentCategory->getVar('cid'));
}
$breadcrumb->addLink($categoryObj->getVar('title'), 'viewcat.php?cid=' . $categoryObj->getVar('cid'));
$breadcrumb->addLink($downloadInfo['title'], '');
$xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

// Show other author downloads
$downloadByUserCriteria = new \CriteriaCompo(new \Criteria('submitter', $downloadObj->getVar('submitter')));
$downloadByUserCriteria->add(new \Criteria('lid', $lid, '!='));
$downloadByUserCriteria->setLimit(20);
$downloadByUserCriteria->setSort('published');
$downloadByUserCriteria->setOrder('DESC');
$downloadByUserObjs = $helper->getHandler('download')->getActiveDownloads($downloadByUserCriteria);
foreach ($downloadByUserObjs as $downloadByUserObj) {
    $downloadByUser['title']     = $downloadByUserObj->getVar('title');
    $downloadByUser['lid']       = (int)$downloadByUserObj->getVar('lid');
    $downloadByUser['cid']       = (int)$downloadByUserObj->getVar('cid');
    $downloadByUser['published'] = formatTimestamp($downloadByUserObj->getVar('published'), $helper->getConfig('dateformat'));
    $xoopsTpl->append('down_uid', $downloadByUser); // this definition is not removed for backward compatibility issues
    $xoopsTpl->append('downloads_by_user', $downloadByUser);
}

$cid = (int)$downloadObj->getVar('cid');
$lid = (int)$downloadObj->getVar('lid');

// User reviews
$criteria = new \CriteriaCompo(new \Criteria('lid', $lid));
$criteria->add(new \Criteria('submit', 1));
$reviewCount = $helper->getHandler('review')->getCount($criteria);
if ($reviewCount > 0) {
    $user_reviews = "op=list&amp;cid={$cid}&amp;lid={$lid}\">" . _MD_WFDOWNLOADS_USERREVIEWS;
} else {
    $user_reviews = "cid={$cid}&amp;lid={$lid}\">" . _MD_WFDOWNLOADS_NOUSERREVIEWS;
}
$xoopsTpl->assign('lang_user_reviews', $GLOBALS['xoopsConfig']['sitename'] . ' ' . _MD_WFDOWNLOADS_USERREVIEWSTITLE);
$xoopsTpl->assign('lang_UserReviews', sprintf($user_reviews, $downloadObj->getVar('title')));
$xoopsTpl->assign('review_amount', $reviewCount);

// User mirrors
$downloadInfo['add_mirror'] = $add_mirror;
$criteria                   = new \CriteriaCompo(new \Criteria('lid', $lid));
$criteria->add(new \Criteria('submit', 1));
$mirrorCount = $helper->getHandler('mirror')->getCount($criteria);
if ($mirrorCount > 0) {
    $user_mirrors = "op=list&amp;cid={$cid}&amp;lid={$lid}\">" . _MD_WFDOWNLOADS_USERMIRRORS;
} else {
    $user_mirrors = "cid={$cid}&amp;lid={$lid}\">" . _MD_WFDOWNLOADS_NOUSERMIRRORS;
}
$xoopsTpl->assign('lang_user_mirrors', $GLOBALS['xoopsConfig']['sitename'] . ' ' . _MD_WFDOWNLOADS_USERMIRRORSTITLE);
$xoopsTpl->assign('lang_UserMirrors', sprintf($user_mirrors, $downloadObj->getVar('title')));
$xoopsTpl->assign('mirror_amount', $mirrorCount);

$xoopsTpl->assign('use_ratings', $helper->getConfig('enable_mirrors'));
$xoopsTpl->assign('use_ratings', $helper->getConfig('enable_ratings'));
$xoopsTpl->assign('use_reviews', $helper->getConfig('enable_reviews'));
$xoopsTpl->assign('use_brokenreports', $helper->getConfig('enable_brokenreports'));
$xoopsTpl->assign('use_rss', $helper->getConfig('enablerss'));

// Copyright
if (true === $helper->getConfig('copyright')) {
    $xoopsTpl->assign('lang_copyright', $downloadObj->getVar('title') . ' &copy; ' . _MD_WFDOWNLOADS_COPYRIGHT . ' ' . formatTimestamp(time(), 'Y'));
}
$xoopsTpl->assign('down', $downloadInfo); // this definition is not removed for backward compatibility issues
$xoopsTpl->assign('download', $downloadInfo);

require_once XOOPS_ROOT_PATH . '/include/comment_view.php';

$xoopsTpl->assign('com_rule', $helper->getConfig('com_rule'));
$xoopsTpl->assign('module_home', Wfdownloads\Utility::moduleHome(true));
require_once __DIR__ . '/footer.php';

?>
<script type="text/javascript">

    $('.magnific_zoom').magnificPopup({
        type: 'image',
        image: {
            cursor: 'mfp-zoom-out-cur',
            titleSrc: "title",
            verticalFit: true,
            tError: 'The image could not be loaded.' // Error message
        },
        gallery: {
            enabled: true
        },
        iframe: {
            patterns: {
                youtube: {
                    index: 'youtube.com/',
                    id: 'v=',
                    src: '//www.youtube.com/embed/%id%?autoplay=1'
                }, vimeo: {
                    index: 'vimeo.com/',
                    id: '/',
                    src: '//player.vimeo.com/video/%id%?autoplay=1'
                }, gmaps: {
                    index: '//maps.google.',
                    src: '%id%&output=embed'
                }
            }
        },
        preloader: true,
        showCloseBtn: true,
        closeBtnInside: false,
        closeOnContentClick: true,
        closeOnBgClick: true,
        enableEscapeKey: true,
        modal: false,
        alignTop: false,
        mainClass: 'mfp-img-mobile mfp-fade',
        zoom: {
            enabled: true,
            duration: 300,
            easing: 'ease-in-out'
        },
        removalDelay: 200
    });
</script>
