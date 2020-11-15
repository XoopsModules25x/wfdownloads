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
    Helper,
    Utility
};
/** @var Helper $helper */
/** @var Utility $utility */

$currentFile = basename(__FILE__);
require_once __DIR__ . '/header.php';

$lid         = Request::getInt('lid', 0);
$downloadObj = $helper->getHandler('Download')->get($lid);
if (null === $downloadObj) {
    redirect_header('index.php', 3, _CO_WFDOWNLOADS_ERROR_NODOWNLOAD);
}
$cid         = Request::getInt('cid', $downloadObj->getVar('cid'));
$categoryObj = $helper->getHandler('Category')->get($cid);
if (null === $categoryObj) {
    redirect_header('index.php', 3, _CO_WFDOWNLOADS_ERROR_NOCATEGORY);
}

// Download not published, expired or taken offline - redirect
if (0 == $downloadObj->getVar('published') || $downloadObj->getVar('published') > time()
    || true === $downloadObj->getVar('offline')
    || (0 != $downloadObj->getVar('expired')
        && $downloadObj->getVar('expired') < time())
    || _WFDOWNLOADS_STATUS_WAITING == $downloadObj->getVar('status')) {
    redirect_header('index.php', 3, _MD_WFDOWNLOADS_NODOWNLOAD);
}

// Check permissions
if (false === $helper->getConfig('enable_mirrors') && !Utility::userIsAdmin()) {
    redirect_header('index.php', 3, _NOPERM);
}
$userGroups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : [0 => XOOPS_GROUP_ANONYMOUS];
if (!$grouppermHandler->checkRight('WFDownCatPerm', $cid, $userGroups, $helper->getModule()->mid())) {
    redirect_header('index.php', 3, _NOPERM);
}

// Breadcrumb
require_once XOOPS_ROOT_PATH . '/class/tree.php';
$categoryObjsTree = new Wfdownloads\ObjectTree($helper->getHandler('Category')->getObjects(), 'cid', 'pid');
$breadcrumb       = new Common\Breadcrumb();
$breadcrumb->addLink($helper->getModule()->getVar('name'), WFDOWNLOADS_URL);
foreach (array_reverse($categoryObjsTree->getAllParent($cid)) as $parentCategory) {
    $breadcrumb->addLink($parentCategory->getVar('title'), 'viewcat.php?cid=' . $parentCategory->getVar('cid'));
}
$breadcrumb->addLink($categoryObj->getVar('title'), "viewcat.php?cid={$cid}");
$breadcrumb->addLink($downloadObj->getVar('title'), "singlefile.php?lid={$lid}");

$op = Request::getString('op', 'mirror.add');
switch ($op) {
    case 'mirrors.list':
    case 'list': // this case is not removed for backward compatibility issues
        $start = Request::getInt('start', 0);

        $GLOBALS['xoopsOption']['template_main'] = "{$helper->getModule()->dirname()}_mirrors.tpl";
        require_once XOOPS_ROOT_PATH . '/header.php';

        $xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
        $xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
        $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
        $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

        $xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

        // Generate content header
        $sql                     = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('wfdownloads_indexpage') . ' ';
        $head_arr                = $GLOBALS['xoopsDB']->fetchArray($GLOBALS['xoopsDB']->query($sql));
        $catarray['imageheader'] = Utility::headerImage();
        $xoopsTpl->assign('catarray', $catarray);
        $xoopsTpl->assign('category_path', $helper->getHandler('Category')->getNicePath($cid));
        $xoopsTpl->assign('category_id', $cid);

        // Breadcrumb
        $breadcrumb->addLink(_CO_WFDOWNLOADS_MIRRORS_LIST, '');
        $xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

        // Count mirrors
        $criteria = new CriteriaCompo(new Criteria('lid', $lid));
        $criteria->add(new Criteria('submit', 1)); // true
        $mirrorsCount = $helper->getHandler('Mirror')->getCount($criteria);

        // Get mirrors
        $criteria->setSort('date');
        $criteria->setLimit(5);
        $criteria->setStart($start);
        $mirrorObjs = $helper->getHandler('Mirror')->getObjects($criteria);

        $download_array = $downloadObj->toArray();
        $xoopsTpl->assign('down_arr', $download_array);

        $add_mirror = false;
        if (!is_object($GLOBALS['xoopsUser'])
            && (_WFDOWNLOADS_ANONPOST_MIRROR == $helper->getConfig('anonpost')
                || _WFDOWNLOADS_ANONPOST_BOTH == $helper->getConfig('anonpost'))
            && (_WFDOWNLOADS_SUBMISSIONS_MIRROR == $helper->getConfig('submissions')
                || _WFDOWNLOADS_SUBMISSIONS_BOTH == $helper->getConfig('submissions'))) {
            $add_mirror = true;
        } elseif (is_object($GLOBALS['xoopsUser'])
                  && (_WFDOWNLOADS_SUBMISSIONS_MIRROR == $helper->getConfig('submissions')
                      || _WFDOWNLOADS_SUBMISSIONS_BOTH == $helper->getConfig('submissions')
                      || $GLOBALS['xoopsUser']->isAdmin())) {
            $add_mirror = true;
        }

        foreach ($mirrorObjs as $mirrorObj) {
            $mirror_array = $mirrorObj->toArray();
            if (1 == $helper->getConfig('enable_onlinechk')) {
                $serverURL                = str_replace('http://', '', trim($mirror_array['homeurl']));
                $mirror_array['isonline'] = Utility::mirrorOnline($serverURL);
            } else {
                $mirror_array['isonline'] = 2;
            }
            $mirror_array['add_mirror'] = $add_mirror;
            $mirror_array['date']       = formatTimestamp($mirror_array['date'], $helper->getConfig('dateformat'));
            $mirror_array['submitter']  = XoopsUserUtility::getUnameFromId($mirror_array['uid']);
            $xoopsTpl->append('down_mirror', $mirror_array);
        }
        $xoopsTpl->assign('lang_mirror_found', sprintf(_MD_WFDOWNLOADS_MIRROR_TOTAL, $mirrorsCount));

        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav          = new XoopsPageNav($mirrorsCount, 5, $start, 'start', "op=mirrors.list&amp;cid={$cid}&amp;lid={$lid}", 1);
        $navbar['navbar'] = $pagenav->renderNav();
        $xoopsTpl->assign('navbar', $navbar);

        $xoopsTpl->assign('categoryPath', $pathstring . ' > ' . $download_array['title']);
        $xoopsTpl->assign('module_home', Utility::moduleHome(true));

        require_once __DIR__ . '/footer.php';
        break;
    case 'mirror.add':
    default:
        // Check if ANONYMOUS user can post mirrors
        if (!is_object($GLOBALS['xoopsUser'])
            && (_WFDOWNLOADS_ANONPOST_NONE == $helper->getConfig('anonpost')
                || _WFDOWNLOADS_ANONPOST_DOWNLOAD == $helper->getConfig('anonpost'))) {
            redirect_header(XOOPS_URL . '/user.php', 1, _MD_WFDOWNLOADS_MUSTREGFIRST);
        }
        // Check if user can submit mirrors
        if (is_object($GLOBALS['xoopsUser'])
            && (_WFDOWNLOADS_SUBMISSIONS_NONE == $helper->getConfig('submissions')
                || _WFDOWNLOADS_SUBMISSIONS_DOWNLOAD == $helper->getConfig('submissions'))
            && !$GLOBALS['xoopsUser']->isAdmin()) {
            redirect_header('index.php', 1, _MD_WFDOWNLOADS_MIRROR_NOTALLOWESTOSUBMIT);
        }

        // Get mirror poster 'uid'
        $mirroruserUid = is_object($GLOBALS['xoopsUser']) ? (int)$GLOBALS['xoopsUser']->getVar('uid') : 0;

        if (Request::hasVar('submit', 'POST')) {
            $mirrorObj = $helper->getHandler('Mirror')->create();
            $mirrorObj->setVar('title', trim($_POST['title']));
            $mirrorObj->setVar('homeurl', formatURL(trim($_POST['homeurl'])));
            $mirrorObj->setVar('location', trim($_POST['location']));
            $mirrorObj->setVar('continent', trim($_POST['continent']));
            $mirrorObj->setVar('downurl', trim($_POST['downurl']));
            $mirrorObj->setVar('lid', Request::getInt('lid', 0, 'POST'));
            $mirrorObj->setVar('uid', $mirroruserUid);
            $mirrorObj->setVar('date', time());
            $approve = true;
            if ((_WFDOWNLOADS_AUTOAPPROVE_NONE == $helper->getConfig('autoapprove') || _WFDOWNLOADS_AUTOAPPROVE_DOWNLOAD == $helper->getConfig('autoapprove')) && !$wfdownloads_isAdmin) {
                $approve = false;
            }
            $submit = $approve ? true : false;
            $mirrorObj->setVar('submit', $submit);

            if (!$helper->getHandler('Mirror')->insert($mirrorObj)) {
                redirect_header('index.php', 3, _MD_WFDOWNLOADS_ERROR_CREATEMIRROR);
            } else {
                $database_mess = $approve ? _MD_WFDOWNLOADS_ISAPPROVED : _MD_WFDOWNLOADS_ISNOTAPPROVED;
                redirect_header('index.php', 2, $database_mess);
            }
        } else {
            require_once XOOPS_ROOT_PATH . '/header.php';

            $xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
            $xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
            $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
            $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

            $xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

            // Breadcrumb
            $breadcrumb->addLink(_MD_WFDOWNLOADS_ADDMIRROR, '');
            echo $breadcrumb->render();

            echo "<div align='center'>" . Utility::headerImage() . "</div><br>\n";
            echo '<div>' . _MD_WFDOWNLOADS_MIRROR_SNEWMNAMEDESC . "</div>\n";

            // Generate form
            require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
            $sform      = new XoopsThemeForm(_MD_WFDOWNLOADS_MIRROR_SUBMITMIRROR, 'mirrorform', xoops_getenv('SCRIPT_NAME'), 'post', true);
            $title_text = new XoopsFormText(_MD_WFDOWNLOADS_MIRROR_HOMEURLTITLE, 'title', 50, 255);
            $title_text->setDescription(_MD_WFDOWNLOADS_MIRROR_HOMEURLTITLE_DESC);
            $sform->addElement($title_text, true);
            $homeurl_text = new XoopsFormText(_MD_WFDOWNLOADS_MIRROR_HOMEURL, 'homeurl', 50, 255);
            $homeurl_text->setDescription(_MD_WFDOWNLOADS_MIRROR_HOMEURL_DESC);
            $sform->addElement($homeurl_text, true);
            $location_text = new XoopsFormText(_MD_WFDOWNLOADS_MIRROR_LOCATION, 'location', 50, 255);
            $location_text->setDescription(_MD_WFDOWNLOADS_MIRROR_LOCATION_DESC);
            $sform->addElement($location_text, true);
            $continent_select = new XoopsFormSelect(_MD_WFDOWNLOADS_MIRROR_CONTINENT, 'continent');
            $continent_select->addOptionArray(
                [
                    _MD_WFDOWNLOADS_CONT1 => _MD_WFDOWNLOADS_CONT1,
                    _MD_WFDOWNLOADS_CONT2 => _MD_WFDOWNLOADS_CONT2,
                    _MD_WFDOWNLOADS_CONT3 => _MD_WFDOWNLOADS_CONT3,
                    _MD_WFDOWNLOADS_CONT4 => _MD_WFDOWNLOADS_CONT4,
                    _MD_WFDOWNLOADS_CONT5 => _MD_WFDOWNLOADS_CONT5,
                    _MD_WFDOWNLOADS_CONT6 => _MD_WFDOWNLOADS_CONT6,
                    _MD_WFDOWNLOADS_CONT7 => _MD_WFDOWNLOADS_CONT7,
                ]
            );
            $sform->addElement($continent_select);
            $downurl_text = new XoopsFormText(_MD_WFDOWNLOADS_MIRROR_DOWNURL, 'downurl', 50, 255);
            $downurl_text->setDescription(_MD_WFDOWNLOADS_MIRROR_DOWNURL_DESC);
            $sform->addElement($downurl_text, true);
            $sform->addElement(new XoopsFormHidden('lid', $lid));
            $sform->addElement(new XoopsFormHidden('cid', $cid));
            $sform->addElement(new XoopsFormHidden('uid', $mirroruserUid));
            $buttonTray   = new XoopsFormElementTray('', '');
            $submitButton = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
            $buttonTray->addElement($submitButton);
            $cancelButton = new XoopsFormButton('', '', _CANCEL, 'button');
            $cancelButton->setExtra('onclick="history.go(-1)"');
            $buttonTray->addElement($cancelButton);
            $sform->addElement($buttonTray);
            $sform->display();
            require_once __DIR__ . '/footer.php';
        }
        break;
}
