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

$lid         = XoopsRequest::getInt('lid', 0);
$downloadObj = $wfdownloads->getHandler('download')->get($lid);
if (empty($downloadObj)) {
    redirect_header('index.php', 3, _CO_WFDOWNLOADS_ERROR_NODOWNLOAD);
}
$cid         = XoopsRequest::getInt('cid', $downloadObj->getVar('cid'));
$categoryObj = $wfdownloads->getHandler('category')->get($cid);
if (empty($categoryObj)) {
    redirect_header('index.php', 3, _CO_WFDOWNLOADS_ERROR_NOCATEGORY);
}

// Download not published, expired or taken offline - redirect
if ($downloadObj->getVar('published') == 0
    || $downloadObj->getVar('published') > time()
    || $downloadObj->getVar('offline') == true
    || ($downloadObj->getVar('expired') != 0 && $downloadObj->getVar('expired') < time())
    || $downloadObj->getVar('status') == _WFDOWNLOADS_STATUS_WAITING
) {
    redirect_header('index.php', 3, _MD_WFDOWNLOADS_NODOWNLOAD);
}

// Check permissions
if ($wfdownloads->getConfig('enable_mirrors') == false && !wfdownloads_userIsAdmin()) {
    redirect_header('index.php', 3, _NOPERM);
}
$userGroups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);
if (!$gperm_handler->checkRight('WFDownCatPerm', $cid, $userGroups, $wfdownloads->getModule()->mid())) {
    redirect_header('index.php', 3, _NOPERM);
}

// Breadcrumb
include_once XOOPS_ROOT_PATH . '/class/tree.php';
$categoryObjsTree = new XoopsObjectTree($wfdownloads->getHandler('category')->getObjects(), 'cid', 'pid');
$breadcrumb       = new WfdownloadsBreadcrumb();
$breadcrumb->addLink($wfdownloads->getModule()->getVar('name'), WFDOWNLOADS_URL);
foreach (array_reverse($categoryObjsTree->getAllParent($cid)) as $parentCategory) {
    $breadcrumb->addLink($parentCategory->getVar('title'), "viewcat.php?cid=" . $parentCategory->getVar('cid'));
}
$breadcrumb->addLink($categoryObj->getVar('title'), "viewcat.php?cid={$cid}");
$breadcrumb->addLink($downloadObj->getVar('title'), "singlefile.php?lid={$lid}");

$op = XoopsRequest::getString('op', 'mirror.add');
switch ($op) {
    case 'mirrors.list':
    case 'list': // this case is not removed for backward compatibility issues
        $start = XoopsRequest::getInt('start', 0);

        $xoopsOption['template_main'] = "{$wfdownloads->getModule()->dirname()}_mirrors.tpl";
        include_once XOOPS_ROOT_PATH . '/header.php';

        $xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
        $xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
        $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
        $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

        $xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

        // Generate content header
        $sql                     = "SELECT * FROM " . $GLOBALS['xoopsDB']->prefix('wfdownloads_indexpage') . " ";
        $head_arr                = $GLOBALS['xoopsDB']->fetchArray($GLOBALS['xoopsDB']->query($sql));
        $catarray['imageheader'] = wfdownloads_headerImage();
        $xoopsTpl->assign('catarray', $catarray);
        $xoopsTpl->assign('category_path', $wfdownloads->getHandler('category')->getNicePath($cid));
        $xoopsTpl->assign('category_id', $cid);

        // Breadcrumb
        $breadcrumb->addLink(_CO_WFDOWNLOADS_MIRRORS_LIST, '');
        $xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

        // Count mirrors
        $criteria = new CriteriaCompo(new Criteria("lid", $lid));
        $criteria->add(new Criteria("submit", 1)); // true
        $mirrorsCount = $wfdownloads->getHandler('mirror')->getCount($criteria);

        // Get mirrors
        $criteria->setSort('date');
        $criteria->setLimit(5);
        $criteria->setStart($start);
        $mirrorObjs = $wfdownloads->getHandler('mirror')->getObjects($criteria);

        $download_array = $downloadObj->toArray();
        $xoopsTpl->assign('down_arr', $download_array);

        $add_mirror = false;
        if (!is_object($GLOBALS['xoopsUser'])
            && ($wfdownloads->getConfig('anonpost') == _WFDOWNLOADS_ANONPOST_MIRROR
                || $wfdownloads->getConfig('anonpost') == _WFDOWNLOADS_ANONPOST_BOTH)
            && ($wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_MIRROR
                || $wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_BOTH)
        ) {
            $add_mirror = true;
        } elseif (is_object($GLOBALS['xoopsUser'])
            && ($wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_MIRROR
                || $wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_BOTH
                || $GLOBALS['xoopsUser']->isAdmin())
        ) {
            $add_mirror = true;
        }

        foreach ($mirrorObjs as $mirrorObj) {
            $mirror_array = $mirrorObj->toArray();
            if ($wfdownloads->getConfig('enable_onlinechk') == 1) {
                $serverURL                = str_replace('http://', '', trim($mirror_array['homeurl']));
                $mirror_array['isonline'] = wfdownloads_mirrorOnline($serverURL);
            } else {
                $mirror_array['isonline'] = 2;
            }
            $mirror_array['add_mirror'] = $add_mirror;
            $mirror_array['date']       = formatTimestamp($mirror_array['date'], $wfdownloads->getConfig('dateformat'));
            $mirror_array['submitter']  = XoopsUserUtility::getUnameFromId($mirror_array['uid']);
            $xoopsTpl->append('down_mirror', $mirror_array);
        }
        $xoopsTpl->assign('lang_mirror_found', sprintf(_MD_WFDOWNLOADS_MIRROR_TOTAL, $mirrorsCount));

        include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav          = new XoopsPageNav($mirrorsCount, 5, $start, 'start', "op=mirrors.list&amp;cid={$cid}&amp;lid={$lid}", 1);
        $navbar['navbar'] = $pagenav->renderNav();
        $xoopsTpl->assign('navbar', $navbar);

        $xoopsTpl->assign('categoryPath', $pathstring . " > " . $download_array['title']);
        $xoopsTpl->assign('module_home', wfdownloads_module_home(true));

        include_once __DIR__ . '/footer.php';
        break;

    case 'mirror.add':
    default:
        // Check if ANONYMOUS user can post mirrors
        if (!is_object($GLOBALS['xoopsUser'])
            && ($wfdownloads->getConfig('anonpost') == _WFDOWNLOADS_ANONPOST_NONE
                || $wfdownloads->getConfig('anonpost') == _WFDOWNLOADS_ANONPOST_DOWNLOAD)
        ) {
            redirect_header(XOOPS_URL . '/user.php', 1, _MD_WFDOWNLOADS_MUSTREGFIRST);
            exit();
        }
        // Check if user can submit mirrors
        if (is_object($GLOBALS['xoopsUser'])
            && ($wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_NONE
                || $wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_DOWNLOAD)
            && !$GLOBALS['xoopsUser']->isAdmin()
        ) {
            redirect_header('index.php', 1, _MD_WFDOWNLOADS_MIRROR_NOTALLOWESTOSUBMIT);
            exit();
        }

        // Get mirror poster 'uid'
        $mirroruserUid = is_object($GLOBALS['xoopsUser']) ? (int)$GLOBALS['xoopsUser']->getVar('uid') : 0;

        if (!empty($_POST['submit'])) {
            $mirrorObj = $wfdownloads->getHandler('mirror')->create();
            $mirrorObj->setVar('title', trim($_POST['title']));
            $mirrorObj->setVar('homeurl', formatURL(trim($_POST['homeurl'])));
            $mirrorObj->setVar('location', trim($_POST['location']));
            $mirrorObj->setVar('continent', trim($_POST['continent']));
            $mirrorObj->setVar('downurl', trim($_POST['downurl']));
            $mirrorObj->setVar('lid', (int)$_POST['lid']);
            $mirrorObj->setVar('uid', $mirroruserUid);
            $mirrorObj->setVar('date', time());
            if (($wfdownloads->getConfig('autoapprove') == _WFDOWNLOADS_AUTOAPPROVE_NONE
                    || $wfdownloads->getConfig('autoapprove') == _WFDOWNLOADS_AUTOAPPROVE_DOWNLOAD)
                && !$wfdownloads_isAdmin
            ) {
                $approve = false;
            } else {
                $approve = true;
            }
            $submit = ($approve) ? true : false;
            $mirrorObj->setVar('submit', $submit);

            if (!$wfdownloads->getHandler('mirror')->insert($mirrorObj)) {
                redirect_header('index.php', 3, _MD_WFDOWNLOADS_ERROR_CREATEMIRROR);
            } else {
                $database_mess = ($approve) ? _MD_WFDOWNLOADS_ISAPPROVED : _MD_WFDOWNLOADS_ISNOTAPPROVED;
                redirect_header('index.php', 2, $database_mess);
            }
        } else {
            include_once XOOPS_ROOT_PATH . '/header.php';

            $xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
            $xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
            $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
            $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

            $xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

            // Breadcrumb
            $breadcrumb->addLink(_MD_WFDOWNLOADS_ADDMIRROR, '');
            echo $breadcrumb->render();

            echo "<div align='center'>" . wfdownloads_headerImage() . "</div><br />\n";
            echo "<div>" . _MD_WFDOWNLOADS_MIRROR_SNEWMNAMEDESC . "</div>\n";

            // Generate form
            include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
            $sform      = new XoopsThemeForm(_MD_WFDOWNLOADS_MIRROR_SUBMITMIRROR, 'mirrorform', xoops_getenv('PHP_SELF'));
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
                array(
                    _MD_WFDOWNLOADS_CONT1 => _MD_WFDOWNLOADS_CONT1,
                    _MD_WFDOWNLOADS_CONT2 => _MD_WFDOWNLOADS_CONT2,
                    _MD_WFDOWNLOADS_CONT3 => _MD_WFDOWNLOADS_CONT3,
                    _MD_WFDOWNLOADS_CONT4 => _MD_WFDOWNLOADS_CONT4,
                    _MD_WFDOWNLOADS_CONT5 => _MD_WFDOWNLOADS_CONT5,
                    _MD_WFDOWNLOADS_CONT6 => _MD_WFDOWNLOADS_CONT6,
                    _MD_WFDOWNLOADS_CONT7 => _MD_WFDOWNLOADS_CONT7
                )
            );
            $sform->addElement($continent_select);
            $downurl_text = new XoopsFormText(_MD_WFDOWNLOADS_MIRROR_DOWNURL, 'downurl', 50, 255);
            $downurl_text->setDescription(_MD_WFDOWNLOADS_MIRROR_DOWNURL_DESC);
            $sform->addElement($downurl_text, true);
            $sform->addElement(new XoopsFormHidden('lid', $lid));
            $sform->addElement(new XoopsFormHidden('cid', $cid));
            $sform->addElement(new XoopsFormHidden('uid', $mirroruserUid));
            $button_tray   = new XoopsFormElementTray('', '');
            $submit_button = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
            $button_tray->addElement($submit_button);
            $cancel_button = new XoopsFormButton('', '', _CANCEL, 'button');
            $cancel_button->setExtra('onclick="history.go(-1)"');
            $button_tray->addElement($cancel_button);
            $sform->addElement($button_tray);
            $sform->display();
            include_once __DIR__ . '/footer.php';
        }
        break;
}
