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

// Download not published, expired or taken offline - redirect
if (0 == $downloadObj->getVar('published') || $downloadObj->getVar('published') > time()
    || true === $downloadObj->getVar('offline')
    || (true === $downloadObj->getVar('expired')
        && $downloadObj->getVar('expired') < time())
    || _WFDOWNLOADS_STATUS_WAITING == $downloadObj->getVar('status')) {
    redirect_header('index.php', 3, _MD_WFDOWNLOADS_NODOWNLOAD);
}

// Check permissions
if (false === $helper->getConfig('enable_brokenreports') && !Wfdownloads\Utility::userIsAdmin()) {
    redirect_header('index.php', 3, _NOPERM);
}

// Breadcrumb
require_once XOOPS_ROOT_PATH . '/class/tree.php';
$categoryObjsTree = new Wfdownloads\ObjectTree($helper->getHandler('category')->getObjects(), 'cid', 'pid');
$breadcrumb       = new common\Breadcrumb();
$breadcrumb->addLink($helper->getModule()->getVar('name'), WFDOWNLOADS_URL);
foreach (array_reverse($categoryObjsTree->getAllParent($cid)) as $parentCategory) {
    $breadcrumb->addLink($parentCategory->getVar('title'), 'viewcat.php?cid=' . $parentCategory->getVar('cid'));
}
$breadcrumb->addLink($categoryObj->getVar('title'), "viewcat.php?cid={$cid}");
$breadcrumb->addLink($downloadObj->getVar('title'), "singlefile.php?lid={$lid}");

$op = Request::getString('op', 'report.add');
switch ($op) {
    case 'report.add':
    default:
        // Get report sender 'uid'
        $senderUid = is_object($GLOBALS['xoopsUser']) ? (int)$GLOBALS['xoopsUser']->getVar('uid') : 0;
        $senderIp  = getenv('REMOTE_ADDR');

        if (!empty($_POST['submit'])) {
            // Check if REG user is trying to report twice
            $criteria    = new \Criteria('lid', $lid);
            $reportCount = $helper->getHandler('report')->getCount($criteria);
            if ($reportCount > 0) {
                redirect_header('index.php', 2, _MD_WFDOWNLOADS_ALREADYREPORTED);
            } else {
                $reportObj = $helper->getHandler('report')->create();
                $reportObj->setVar('lid', $lid);
                $reportObj->setVar('sender', $senderUid);
                $reportObj->setVar('ip', $senderIp);
                $reportObj->setVar('date', time());
                $reportObj->setVar('confirmed', 0);
                $reportObj->setVar('acknowledged', 0);
                if ($helper->getHandler('report')->insert($reportObj)) {
                    // All is well
                    // Send notification
                    $tags                      = [];
                    $tags['BROKENREPORTS_URL'] = WFDOWNLOADS_URL . '/admin/reportsmodifications.php?op=reports.modifications.list';
                    $notificationHandler->triggerEvent('global', 0, 'file_broken', $tags);

                    // Send email to the owner of the download stating that it is broken
                    $user    = $memberHandler->getUser($downloadObj->getVar('submitter'));
                    $subdate = formatTimestamp($downloadObj->getVar('published'), $helper->getConfig('dateformat'));
                    $cid     = $downloadObj->getVar('cid');
                    $title   = $downloadObj->getVar('title');
                    $subject = _MD_WFDOWNLOADS_BROKENREPORTED;

                    $xoopsMailer = xoops_getMailer();
                    $xoopsMailer->useMail();
                    $template_dir = WFDOWNLOADS_ROOT_PATH . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/mail_template';

                    $xoopsMailer->setTemplateDir($template_dir);
                    $xoopsMailer->setTemplate('filebroken_notify.tpl');
                    $xoopsMailer->setToEmails($user->email());
                    $xoopsMailer->setFromEmail($GLOBALS['xoopsConfig']['adminmail']);
                    $xoopsMailer->setFromName($GLOBALS['xoopsConfig']['sitename']);
                    $xoopsMailer->assign('X_UNAME', $user->uname());
                    $xoopsMailer->assign('SITENAME', $GLOBALS['xoopsConfig']['sitename']);
                    $xoopsMailer->assign('X_ADMINMAIL', $GLOBALS['xoopsConfig']['adminmail']);
                    $xoopsMailer->assign('X_SITEURL', XOOPS_URL . '/');
                    $xoopsMailer->assign('X_TITLE', $title);
                    $xoopsMailer->assign('X_SUB_DATE', $subdate);
                    $xoopsMailer->assign('X_DOWNLOAD', WFDOWNLOADS_URL . "/singlefile.php?cid={$cid}&lid={$lid}");
                    $xoopsMailer->setSubject($subject);
                    $xoopsMailer->send();
                    redirect_header('index.php', 2, _MD_WFDOWNLOADS_BROKENREPORTED);
                } else {
                    echo $reportObj->getHtmlErrors();
                }
            }
        } else {
            $GLOBALS['xoopsOption']['template_main'] = "{$helper->getModule()->dirname()}_brokenfile.tpl";
            require_once XOOPS_ROOT_PATH . '/header.php';

            // Begin Main page Heading etc
            $catarray['imageheader'] = Wfdownloads\Utility::headerImage();
            $xoopsTpl->assign('catarray', $catarray);

            $xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
            $xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
            $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
            $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

            $xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

            // Breadcrumb
            $breadcrumb->addLink(_MD_WFDOWNLOADS_REPORTBROKEN, '');
            $xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

            // Generate form
            require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
            $sform = new \XoopsThemeForm(_MD_WFDOWNLOADS_RATETHISFILE, 'reportform', xoops_getenv('PHP_SELF'), 'post', true);
            $sform->addElement(new \XoopsFormHidden('lid', $lid));
            $sform->addElement(new \XoopsFormHidden('cid', $cid));
            $sform->addElement(new \XoopsFormHidden('uid', $senderUid));
            $button_tray   = new \XoopsFormElementTray('', '');
            $submit_button = new \XoopsFormButton('', 'submit', _MD_WFDOWNLOADS_SUBMITBROKEN, 'submit');
            $button_tray->addElement($submit_button);
            $cancel_button = new \XoopsFormButton('', '', _CANCEL, 'button');
            $cancel_button->setExtra('onclick="history.go(-1)"');
            $button_tray->addElement($cancel_button);
            $sform->addElement($button_tray);
            $xoopsTpl->assign('reportform', $sform->render());
            $xoopsTpl->assign('download', [
                'lid'         => $lid,
                'cid'         => $cid,
                'title'       => $downloadObj->getVar('title'),
                'description' => $downloadObj->getVar('description')
            ]);

            $criteria = new \Criteria('lid', $lid);

            $reportObjs = $helper->getHandler('report')->getObjects($criteria);

            if (count($reportObjs) > 0) {
                $reportObj = $reportObjs[0];

                $broken['title']        = trim($downloadObj->getVar('title'));
                $broken['id']           = $reportObj->getVar('reportid');
                $broken['reporter']     = \XoopsUserUtility::getUnameFromId((int)$reportObj->getVar('sender'));
                $broken['date']         = formatTimestamp($reportObj->getVar('published'), $helper->getConfig('dateformat'));
                $broken['acknowledged'] = (1 == $reportObj->getVar('acknowledged')) ? _YES : _NO;
                $broken['confirmed']    = (1 == $reportObj->getVar('confirmed')) ? _YES : _NO;

                $xoopsTpl->assign('brokenreportexists', true);
                $xoopsTpl->assign('broken', $broken);
                $xoopsTpl->assign('brokenreport', true); // this definition is not removed for backward compatibility issues
            } else {
                // file info
                $down['title']     = trim($downloadObj->getVar('title'));
                $down['homepage']  = $myts->makeClickable(formatURL(trim($downloadObj->getVar('homepage'))));
                $time              = (false !== $downloadObj->getVar('updated')) ? $downloadObj->getVar('updated') : $downloadObj->getVar('published');
                $down['updated']   = formatTimestamp($time, $helper->getConfig('dateformat'));
                $is_updated        = (false !== $downloadObj->getVar('updated')) ? _MD_WFDOWNLOADS_UPDATEDON : _MD_WFDOWNLOADS_SUBMITDATE;
                $down['publisher'] = \XoopsUserUtility::getUnameFromId((int)$downloadObj->getVar('submitter'));

                $xoopsTpl->assign('brokenreportexists', false);
                $xoopsTpl->assign('file_id', $lid);
                $xoopsTpl->assign('lang_subdate', $is_updated);
                $xoopsTpl->assign('is_updated', $downloadObj->getVar('updated'));
                $xoopsTpl->assign('lid', $lid);
                $xoopsTpl->assign('down', $down);
            }
            require_once __DIR__ . '/footer.php';
        }
        break;
}
