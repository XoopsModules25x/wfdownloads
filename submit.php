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

// Check if submissions are allowed
$isSubmissionAllowed = false;
if (is_object($GLOBALS['xoopsUser'])
    && (_WFDOWNLOADS_SUBMISSIONS_DOWNLOAD == $helper->getConfig('submissions')
        || _WFDOWNLOADS_SUBMISSIONS_BOTH == $helper->getConfig('submissions'))) {
    // if user is a registered user
    $groups = $GLOBALS['xoopsUser']->getGroups();
    if (count(array_intersect($helper->getConfig('submitarts'), $groups)) > 0) {
        $isSubmissionAllowed = true;
    }
} else {
    // if user is ANONYMOUS
    if (!is_object($GLOBALS['xoopsUser'])
        && (_WFDOWNLOADS_ANONPOST_DOWNLOAD == $helper->getConfig('anonpost')
            || _WFDOWNLOADS_ANONPOST_BOTH == $helper->getConfig('anonpost'))) {
        $isSubmissionAllowed = true;
    } else {
        redirect_header(XOOPS_URL . '/user.php', 5, _MD_WFDOWNLOADS_MUSTREGFIRST);
    }
}
// Get categories where user can submit
$categoryObjs = $helper->getHandler('category')->getUserUpCategories();
if (0 == count($categoryObjs)) {
    $isSubmissionAllowed = false;
}
if (false === $isSubmissionAllowed) {
    redirect_header('index.php', 5, _MD_WFDOWNLOADS_NOTALLOWESTOSUBMIT);
}
// Check posts if user is not an ADMIN
if (is_object($GLOBALS['xoopsUser']) && !$GLOBALS['xoopsUser']->isAdmin()) {
    if ($GLOBALS['xoopsUser']->getVar('posts') < $helper->getConfig('upload_minposts')) {
        redirect_header('index.php', 5, _MD_WFDOWNLOADS_UPLOADMINPOSTS);
    }
}

$lid    = Request::getInt('lid', 0);
$cid    = Request::getInt('cid', 0);
$agreed = Request::getBool('agreed', false, 'POST');
$op     = Request::getString('op', 'download.form');
$notify = Request::getBool('notify', false);

if ($helper->getConfig('showdisclaimer') && ('download.form' === $op) && false === $agreed) {
    $op = 'download.disclaimer';
}

switch ($op) {
    case 'download.disclaimer':
        // Show disclaimers
        $GLOBALS['xoopsOption']['template_main'] = "{$helper->getModule()->dirname()}_disclaimer.tpl";
        require_once XOOPS_ROOT_PATH . '/header.php';

        $xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
        $xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
        $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
        $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

        $xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

        $catarray['imageheader'] = Wfdownloads\Utility::headerImage();
        $xoopsTpl->assign('catarray', $catarray);

        // Breadcrumb
        $breadcrumb = new common\Breadcrumb();
        $breadcrumb->addLink($helper->getModule()->getVar('name'), WFDOWNLOADS_URL);
        $breadcrumb->addLink(_MD_WFDOWNLOADS_SUBMITDOWNLOAD, '');
        $xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

        $xoopsTpl->assign('lid', $lid);
        $xoopsTpl->assign('cid', $cid);

        $xoopsTpl->assign('image_header', Wfdownloads\Utility::headerImage());

        $xoopsTpl->assign('submission_disclaimer', true);
        $xoopsTpl->assign('download_disclaimer', false);
        $xoopsTpl->assign('submission_disclaimer_content', $myts->displayTarea($helper->getConfig('disclaimer'), true, true, true, true, true));

        $xoopsTpl->assign('down_disclaimer', false); // this definition is not removed for backward compatibility issues
        $xoopsTpl->assign('disclaimer', $myts->displayTarea($helper->getConfig('disclaimer'), true, true, true, true, true)); // this definition is not removed for backward compatibility issues
        $xoopsTpl->assign('cancel_location', WFDOWNLOADS_URL . '/index.php'); // this definition is not removed for backward compatibility issues
        if (!isset($_REQUEST['lid'])) {
            $xoopsTpl->assign('agree_location', WFDOWNLOADS_URL . "/{$currentFile}?agreed=1");
        } else {
            $lid = Request::getInt('lid');
            $xoopsTpl->assign('agree_location', WFDOWNLOADS_URL . "/{$currentFile}?agreed=1&amp;lid={$lid}");
        }

        $xoopsTpl->assign('categoryPath', _MD_WFDOWNLOADS_DISCLAIMERAGREEMENT);
        $xoopsTpl->assign('module_home', Wfdownloads\Utility::moduleHome(true));

        require_once __DIR__ . '/footer.php';
        exit();
        break;

    case 'download.form':
    case 'download.edit':
    case 'download.add':
        // Show submit form
        if ((0 != $lid) && is_object($GLOBALS['xoopsUser'])) {
            $downloadObj = $helper->getHandler('download')->get($lid);
            if ($GLOBALS['xoopsUser']->uid() != $downloadObj->getVar('submitter')) {
                redirect_header('index.php', 5, _MD_WFDOWNLOADS_NOTALLOWEDTOMOD);
            }
            $cid = $downloadObj->getVar('cid');
        } else {
            $downloadObj = $helper->getHandler('download')->create();
            $downloadObj->setVar('cid', $cid);
        }
        // Formulize module support - jpc - start
        if (isset($_POST['submit_category']) && !empty($_POST['submit_category'])) {
            // two steps form: 2nd step
            $categoryObj = $helper->getHandler('category')->get($cid);
            $fid         = $categoryObj->getVar('formulize_fid');
            $customArray = [];
            if (Wfdownloads\Utility::checkModule('formulize') && $fid) {
                require_once XOOPS_ROOT_PATH . '/modules/formulize/include/formdisplay.php';
                require_once XOOPS_ROOT_PATH . '/modules/formulize/include/functions.php';
                $customArray['fid']           = $fid;
                $customArray['formulize_mgr'] = xoops_getModuleHandler('elements', 'formulize');
                $customArray['groups']        = $GLOBALS['xoopsUser'] ? $GLOBALS['xoopsUser']->getGroups() : [0 => XOOPS_GROUP_ANONYMOUS];
                $customArray['prevEntry']     = getEntryValues(// is a Formulize function
                    $downloadObj->getVar('formulize_idreq'), $customArray['formulize_mgr'], $customArray['groups'], $fid, null, null, null, null, null);
                $customArray['entry']         = $downloadObj->getVar('formulize_idreq');
                $customArray['go_back']       = '';
                $customArray['parentLinks']   = '';
                if (Wfdownloads\Utility::checkModule('formulize') < 300) {
                    $owner = getEntryOwner($customArray['entry']); // is a Formulize function
                } else {
                    $owner = getEntryOwner($customArray['entry'], $fid); // is a Formulize function
                }
                $owner_groups                = $memberHandler->getGroupsByUser($owner, false);
                $customArray['owner_groups'] = $owner_groups;
            }
            $sform = $downloadObj->getForm($customArray);
        } elseif (Wfdownloads\Utility::checkModule('formulize')) {
            // two steps form: 1st step
            $sform = $downloadObj->getCategoryForm(_MD_WFDOWNLOADS_FFS_SUBMIT1ST_STEP);
        } else {
            // one step form: 1st step
            $sform = $downloadObj->getForm();
        }
        // Formulize module support - jpc - end
        $GLOBALS['xoopsOption']['template_main'] = "{$helper->getModule()->dirname()}_submit.tpl";
        require_once XOOPS_ROOT_PATH . '/header.php';

        $xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
        $xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
        $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
        $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

        $xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

        $catarray['imageheader'] = Wfdownloads\Utility::headerImage();

        // Breadcrumb
        $breadcrumb = new common\Breadcrumb();
        $breadcrumb->addLink($helper->getModule()->getVar('name'), WFDOWNLOADS_URL);
        $breadcrumb->addLink(_MD_WFDOWNLOADS_SUBMITDOWNLOAD, '');
        $xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

        $xoopsTpl->assign('catarray', $catarray);
        $xoopsTpl->assign('categoryPath', _MD_WFDOWNLOADS_SUBMITDOWNLOAD);
        $xoopsTpl->assign('module_home', Wfdownloads\Utility::moduleHome(true));
        $xoopsTpl->assign('submit_form', $sform->render());

        require_once __DIR__ . '/footer.php';
        exit();
        break;

    case 'download.save':
        // Save submitted download
        if (empty($_FILES['userfile']['name'])) {
            if ($_POST['url'] && '' != $_POST['url'] && 'http://' !== $_POST['url']) {
                $url      = ('http://' !== $_POST['url']) ? $_POST['url'] : '';
                $filename = '';
                $filetype = '';
            } else {
                $url      = ('http://' !== $_POST['url']) ? $_POST['url'] : '';
                $filename = $_POST['filename'];
                $filetype = $_POST['filetype'];
            }
            $size  = empty($_POST['size']) || !is_numeric($_POST['size']) ? 0 : (int)$_POST['size'];
            $title = trim($_POST['title']);
        } else {
            $isAdmin  = Wfdownloads\Utility::userIsAdmin();
            $down     = Wfdownloads\Utility::uploading($_FILES, $helper->getConfig('uploaddir'), '', $currentFile, 0, false, $isAdmin);
            $url      = ('http://' !== $_POST['url']) ? $_POST['url'] : '';
            $size     = $down['size'];
            $filename = $down['filename'];
            $filetype = $_FILES['userfile']['type'];
            $title    = $_FILES['userfile']['name'];
            $title    = rtrim(Wfdownloads\Utility::strrrchr($title, '.'), '.');
            $title    = (isset($_POST['title_checkbox']) && 1 == $_POST['title_checkbox']) ? $title : trim($_POST['title']);
        }

        // Load screenshot
        require_once WFDOWNLOADS_ROOT_PATH . '/class/img_uploader.php';
        $allowedMimetypes = ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'];
        $uploadDirectory  = XOOPS_ROOT_PATH . '/' . $helper->getConfig('screenshots') . '/';

        $screenshots = [];

        // Load screenshot #1
        $screenshot1 = '';
        if (isset($_FILES['screenshot']['name']) && !empty($_FILES['screenshot']['name'])) {
            $screenshot1 = strtolower($_FILES['screenshot']['name']);
            $uploader    = new \MediaImgUploader($uploadDirectory, $allowedMimetypes, $helper->getConfig('maxfilesize'), $helper->getConfig('maximgwidth'), $helper->getConfig('maximgheight'));
            if (!$uploader->fetchMedia($_POST['xoops_upload_file'][1]) && !$uploader->upload()) {
                @unlink($uploadDirectory . $screenshot1);
                redirect_header($currentFile, 1, $uploader->getErrors());
            }
        }
        $screenshots[] = $screenshot1;
        // Load screenshot #2
        $screenshot2 = '';
        if ($helper->getConfig('max_screenshot') >= 2) {
            if (isset($_FILES['screenshot2']['name']) && !empty($_FILES['screenshot2']['name'])) {
                $screenshot2 = strtolower($_FILES['screenshot2']['name']);
                $uploader    = new \MediaImgUploader($uploadDirectory, $allowedMimetypes, $helper->getConfig('maxfilesize'), $helper->getConfig('maximgwidth'), $helper->getConfig('maximgheight'));
                if (!$uploader->fetchMedia($_POST['xoops_upload_file'][2]) && !$uploader->upload()) {
                    @unlink($uploadDirectory . $screenshot2);
                    redirect_header($currentFile, 1, $uploader->getErrors());
                }
            }
        }
        $screenshots[] = $screenshot2;
        // Load screenshot #3
        $screenshot3 = '';
        if ($helper->getConfig('max_screenshot') >= 3) {
            if (isset($_FILES['screenshot3']['name']) && !empty($_FILES['screenshot3']['name'])) {
                $screenshot3 = strtolower($_FILES['screenshot3']['name']);
                $uploader    = new \MediaImgUploader($uploadDirectory, $allowedMimetypes, $helper->getConfig('maxfilesize'), $helper->getConfig('maximgwidth'), $helper->getConfig('maximgheight'));
                if (!$uploader->fetchMedia($_POST['xoops_upload_file'][3]) && !$uploader->upload()) {
                    @unlink($uploadDirectory . $screenshot3);
                    redirect_header($currentFile, 1, $uploader->getErrors());
                }
            }
        }
        $screenshots[] = $screenshot3;
        // Load screenshot #4
        $screenshot4 = '';
        if ($helper->getConfig('max_screenshot') >= 4) {
            if (isset($_FILES['screenshot4']['name']) && !empty($_FILES['screenshot4']['name'])) {
                $screenshot4 = strtolower($_FILES['screenshot4']['name']);
                $uploader    = new \MediaImgUploader($uploadDirectory, $allowedMimetypes, $helper->getConfig('maxfilesize'), $helper->getConfig('maximgwidth'), $helper->getConfig('maximgheight'));
                if (!$uploader->fetchMedia($_POST['xoops_upload_file'][4]) && !$uploader->upload()) {
                    @unlink($uploadDirectory . $screenshot4);
                    redirect_header($currentFile, 1, $uploader->getErrors());
                }
            }
        }
        $screenshots[] = $screenshot4;

        if ($lid > 0) {
            $isANewRecord = false;
            if (_WFDOWNLOADS_AUTOAPPROVE_DOWNLOAD == $helper->getConfig('autoapprove')
                || _WFDOWNLOADS_AUTOAPPROVE_BOTH == $helper->getConfig('autoapprove')) {
                $downloadObj = $helper->getHandler('download')->get($lid);
            } else {
                $downloadObj = $helper->getHandler('modification')->create();
                $downloadObj->setVar('lid', $lid);
            }
        } else {
            $isANewRecord = true;
            $downloadObj  = $helper->getHandler('download')->create();
            if (_WFDOWNLOADS_AUTOAPPROVE_DOWNLOAD == $helper->getConfig('autoapprove')
                || _WFDOWNLOADS_AUTOAPPROVE_BOTH == $helper->getConfig('autoapprove')) {
                $downloadObj->setVar('published', time());
                $downloadObj->setVar('status', _WFDOWNLOADS_STATUS_APPROVED);
            } else {
                $downloadObj->setVar('published', false);
                $downloadObj->setVar('status', _WFDOWNLOADS_STATUS_WAITING);
            }
        }

        // Formulize module support (2006/05/04) jpc - start
        if (Wfdownloads\Utility::checkModule('formulize')) {
            // Now that the $downloadObj object has been instantiated, handle the Formulize part of the submission...
            $categoryObj = $helper->getHandler('category')->get($cid);
            $fid         = $categoryObj->getVar('formulize_fid');
            if ($fid) {
                require_once XOOPS_ROOT_PATH . '/modules/formulize/include/formread.php';
                require_once XOOPS_ROOT_PATH . '/modules/formulize/include/functions.php';
                $formulizeElementsHandler = xoops_getModuleHandler('elements', 'formulize');
                if ($lid) {
                    $entries[$fid][0] = $downloadObj->getVar('formulize_idreq');
                    if ($entries[$fid][0]) {
                        if (Wfdownloads\Utility::checkModule('formulize') < 300) {
                            $owner = getEntryOwner($entries[$fid][0]); // is a Formulize function
                        } else {
                            $owner = getEntryOwner($entries[$fid][0], $fid); // is a Formulize function
                        }
                    } else {
                        $entries[$fid][0] = '';
                        $owner            = '';
                    }
                    $cid = $downloadObj->getVar('cid');
                } else {
                    $entries[$fid][0] = '';
                    $owner            = '';
                }
                $owner_groups = $memberHandler->getGroupsByUser($owner, false);
                $uid          = is_object($GLOBALS['xoopsUser']) ? (int)$GLOBALS['xoopsUser']->getVar('uid') : 0;
                $groups       = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : [0 => XOOPS_GROUP_ANONYMOUS];
                $entries      = handleSubmission(// is a Formulize function
                    $formulizeElementsHandler, $entries, $uid, $owner, $fid, $owner_groups, $groups, 'new'); // 'new' causes xoops token check to be skipped, since Wfdownloads should be doing that
                if (!$owner) {
                    $id_req = $entries[$fid][0];
                    $downloadObj->setVar('formulize_idreq', $id_req);
                }
            }
        }
        // Formulize module support (2006/05/04) jpc - end

        if (!empty($_POST['homepage']) || 'http://' !== $_POST['homepage']) {
            $downloadObj->setVar('homepage', formatURL(trim($_POST['homepage'])));
            $downloadObj->setVar('homepagetitle', trim($_POST['homepagetitle']));
        }
        $downloadObj->setVar('title', $title);
        $downloadObj->setVar('url', $url);
        $downloadObj->setVar('cid', (int)$cid);
        $downloadObj->setVar('filename', $filename);
        $downloadObj->setVar('filetype', $filetype);

        /* Added by Lankford on 2007/3/21 */
        // Here, I want to know if:
        //    a) Are they actually changing the value of version, or is it the same?
        //    b) Are they actually modifying the record, or is this a new one?
        //  If both conditions are true, then trigger all three notifications related to modified records.
        $version = !empty($_POST['version']) ? trim($_POST['version']) : 0;

        if (!$isANewRecord && ($downloadObj->getVar('version') != $version)) {
            // Trigger the three events related to modified files (one for the file, category, and global event categories respectively)
            $tags                  = [];
            $tags['FILE_NAME']     = $title;
            $tags['FILE_URL']      = WFDOWNLOADS_URL . "/singlefile.php?cid={$cid}&amp;lid={$lid}";
            $categoryObj           = $helper->getHandler('category')->get($cid);
            $tags['FILE_VERSION']  = $version;
            $tags['CATEGORY_NAME'] = $categoryObj->getVar('title');
            $tags['CATEGORY_URL']  = WFDOWNLOADS_URL . "/viewcat.php?cid={$cid}";

            if (_WFDOWNLOADS_AUTOAPPROVE_DOWNLOAD == $helper->getConfig('autoapprove') || _WFDOWNLOADS_AUTOAPPROVE_BOTH == $helper->getConfig('autoapprove')) {
                // Then this change will be automatically approved, so the notification needs to go out.
                $notificationHandler->triggerEvent('global', 0, 'filemodified', $tags);
                $notificationHandler->triggerEvent('category', $cid, 'filemodified', $tags);
                $notificationHandler->triggerEvent('file', $lid, 'filemodified', $tags);
            }
        }
        /* End add block */

        $downloadObj->setVar('version', $_POST['version']);
        $downloadObj->setVar('size', $size);
        $downloadObj->setVar('platform', $_POST['platform']);
        $downloadObj->setVar('screenshot', $screenshots[0]); // old style
        $downloadObj->setVar('screenshot2', $screenshots[1]); // old style
        $downloadObj->setVar('screenshot3', $screenshots[2]); // old style
        $downloadObj->setVar('screenshot4', $screenshots[3]); // old style
        $downloadObj->setVar('screenshots', $screenshots); // new style
        $downloadObj->setVar('summary', $_POST['summary']);
        $downloadObj->setVar('description', $_POST['description']);
        $downloadObj->setVar('dohtml', isset($_POST['dohtml']));
        $downloadObj->setVar('dosmiley', isset($_POST['dosmiley']));
        $downloadObj->setVar('doxcode', isset($_POST['doxcode']));
        $downloadObj->setVar('doimage', isset($_POST['doimage']));
        $downloadObj->setVar('dobr', isset($_POST['dobr']));
        $submitter = is_object($GLOBALS['xoopsUser']) ? (int)$GLOBALS['xoopsUser']->getVar('uid') : 0;
        $downloadObj->setVar('submitter', $submitter);
        $downloadObj->setVar('publisher', trim($_POST['publisher']));
        $downloadObj->setVar('price', trim($_POST['price']));
        $downloadObj->setVar('mirror', isset($_POST['mirror']) ? trim($_POST['mirror']) : '');
        $downloadObj->setVar('license', trim($_POST['license']));
        $paypalEmail = '';
        $downloadObj->setVar('features', trim($_POST['features']));
        $downloadObj->setVar('requirements', trim($_POST['requirements']));
        $forumid = (isset($_POST['forumid']) && $_POST['forumid'] > 0) ? (int)$_POST['forumid'] : 0;
        $downloadObj->setVar('forumid', $forumid);
        $limitations = isset($_POST['limitations']) ? $myts->addSlashes($_POST['limitations']) : '';
        $downloadObj->setVar('limitations', $limitations);
        $versiontypes = isset($_POST['versiontypes']) ? $myts->addSlashes($_POST['versiontypes']) : '';
        $downloadObj->setVar('versiontypes', $versiontypes);
        $dhistory        = isset($_POST['dhistory']) ? $myts->addSlashes($_POST['dhistory']) : '';
        $dhistoryhistory = isset($_POST['dhistoryaddedd']) ? $myts->addSlashes($_POST['dhistoryaddedd']) : '';
        if ($lid > 0 && !empty($dhistoryhistory)) {
            $dhistory .= "\n\n";
            $dhistory .= '<b>' . formatTimestamp(time(), $helper->getConfig('dateformat')) . "</b>\n\n";
            $dhistory .= $dhistoryhistory;
        }
        $downloadObj->setVar('dhistory', $dhistory);
        $offline = (isset($_POST['offline']) && 1 == $_POST['offline']) ? true : false;
        $downloadObj->setVar('offline', $offline);
        $downloadObj->setVar('date', time());
        /*
                $screenshot1 = '';
                $screenshot2 = '';
                $screenshot3 = '';
                $screenshot4 = '';
        */
        if (0 == $lid) {
            $notifypub = (isset($_POST['notifypub']) && true === $_POST['notifypub']);
            $downloadObj->setVar('notifypub', $notifypub);
            $downloadObj->setVar('ipaddress', $_SERVER['REMOTE_ADDR']);

            if (!$helper->getHandler('download')->insert($downloadObj)) {
                $error = _MD_WFDOWNLOADS_INFONOSAVEDB;
                trigger_error($error, E_USER_ERROR);
            }
            $newid  = (int)$downloadObj->getVar('lid');
            $groups = [1, 2];
            //  Notify of new link (anywhere) and new link in category
            $tags                  = [];
            $tags['FILE_NAME']     = $title;
            $tags['FILE_URL']      = WFDOWNLOADS_URL . "/singlefile.php?cid={$cid}&amp;lid={$newid}";
            $categoryObj           = $helper->getHandler('category')->get($cid);
            $tags['CATEGORY_NAME'] = $categoryObj->getVar('title');
            $tags['CATEGORY_URL']  = WFDOWNLOADS_URL . "/viewcat.php?cid={$cid}";

            if (_WFDOWNLOADS_AUTOAPPROVE_DOWNLOAD == $helper->getConfig('autoapprove') || _WFDOWNLOADS_AUTOAPPROVE_BOTH == $helper->getConfig('autoapprove')) {
                $notificationHandler->triggerEvent('global', 0, 'new_file', $tags);
                $notificationHandler->triggerEvent('category', $cid, 'new_file', $tags);
                redirect_header('index.php', 2, _MD_WFDOWNLOADS_ISAPPROVED);
            } else {
                $tags['WAITINGFILES_URL'] = WFDOWNLOADS_URL . '/admin/downloads.php';
                $notificationHandler->triggerEvent('global', 0, 'file_submit', $tags);
                $notificationHandler->triggerEvent('category', $cid, 'file_submit', $tags);
                if ($notify) {
                    require_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
                    $notificationHandler->subscribe('file', $newid, 'approve', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE);
                }
                redirect_header('index.php', 2, _MD_WFDOWNLOADS_THANKSFORINFO);
            }
            exit();
        } else {
            if (_WFDOWNLOADS_AUTOAPPROVE_DOWNLOAD == $helper->getConfig('autoapprove') || _WFDOWNLOADS_AUTOAPPROVE_BOTH == $helper->getConfig('autoapprove')) {
                $notifypub = (isset($_POST['notifypub']) && true === $_POST['notifypub']);
                $downloadObj->setVar('notifypub', $notifypub);
                $downloadObj->setVar('ipaddress', $_SERVER['REMOTE_ADDR']);
                $downloadObj->setVar('updated', time());
                $helper->getHandler('download')->insert($downloadObj);

                $tags                  = [];
                $tags['FILE_NAME']     = $title;
                $tags['FILE_URL']      = WFDOWNLOADS_URL . "/singlefile.php?cid={$cid}&amp;lid={$lid}";
                $categoryObj           = $helper->getHandler('category')->get($cid);
                $tags['CATEGORY_NAME'] = $categoryObj->getVar('title');
                $tags['CATEGORY_URL']  = WFDOWNLOADS_URL . "/viewcat.php?cid={$cid}";
                $notificationHandler->triggerEvent('global', 0, 'file_modify', $tags);
                redirect_header('index.php', 2, _MD_WFDOWNLOADS_ISAPPROVED);
            } else {
                $updated = (isset($_POST['up_dated']) && 0 == $_POST['up_dated']) ? 0 : time();
                $downloadObj->setVar('updated', $updated);
                $downloadObj->setVar('modifysubmitter', (int)$GLOBALS['xoopsUser']->uid());
                $downloadObj->setVar('requestdate', time());
                if (!$helper->getHandler('modification')->insert($downloadObj)) {
                    $error = _MD_WFDOWNLOADS_INFONOSAVEDB;
                    trigger_error($error, E_USER_ERROR);
                }
                $tags                      = [];
                $tags['MODIFYREPORTS_URL'] = WFDOWNLOADS_URL . '/admin/reportsmodifications.php';
                $notificationHandler->triggerEvent('global', 0, 'file_modify', $tags);
                redirect_header('index.php', 2, _MD_WFDOWNLOADS_THANKSFORINFO);
            }
        }
        break;
}
