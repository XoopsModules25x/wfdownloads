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

// Check if submissions are allowed
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
    // if user is ANONYMOUS
    if (!is_object($xoopsUser)
        && ($wfdownloads->getConfig('anonpost') == _WFDOWNLOADS_ANONPOST_DOWNLOAD
            || $wfdownloads->getConfig('anonpost') == _WFDOWNLOADS_ANONPOST_BOTH)
    ) {
        $isSubmissionAllowed = true;
    } else {
        redirect_header(XOOPS_URL . '/user.php', 5, _MD_WFDOWNLOADS_MUSTREGFIRST);
    }
}
// Get categories where user can submit
$categories = $wfdownloads->getHandler('category')->getUserUpCategories();
if (count($categories) == 0) {
    $isSubmissionAllowed = false;
}
if ($isSubmissionAllowed == false) {
    redirect_header('index.php', 5, _MD_WFDOWNLOADS_NOTALLOWESTOSUBMIT);
}
// Check posts if user is not an ADMIN
if (is_object($xoopsUser) && !$xoopsUser->isAdmin()) {
    if ($xoopsUser->getVar('posts') < $wfdownloads->getConfig('upload_minposts')) {
        redirect_header('index.php', 5, _MD_WFDOWNLOADS_UPLOADMINPOSTS);
    }
}

$lid    = WfdownloadsRequest::getInt('lid', 0);
$cid    = WfdownloadsRequest::getInt('cid', 0);
$agreed = WfdownloadsRequest::getBool('agreed', false, 'POST');
$op     = WfdownloadsRequest::getString('op', 'download.form');
$notify = WfdownloadsRequest::getBool('notify', false);

if ($wfdownloads->getConfig('showdisclaimer') && ($op == 'download.form') && $agreed == false) {
    $op = 'download.disclaimer';
}

switch ($op) {
    case "download.disclaimer" :
        // Show disclaimers
        $xoopsOption['template_main'] = "{$wfdownloads->getModule()->dirname()}_disclaimer.tpl";
        include XOOPS_ROOT_PATH . '/header.php';

        $xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
        $xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
        $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
        $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

        $xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

        $catarray['imageheader'] = wfdownloads_headerImage();
        $xoopsTpl->assign('catarray', $catarray);

        // Breadcrumb
        $breadcrumb = new WfdownloadsBreadcrumb();
        $breadcrumb->addLink($wfdownloads->getModule()->getVar('name'), WFDOWNLOADS_URL);
        $breadcrumb->addLink(_MD_WFDOWNLOADS_SUBMITDOWNLOAD, '');
        $xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

        $xoopsTpl->assign('lid', $lid);
        $xoopsTpl->assign('cid', $cid);

        $xoopsTpl->assign('image_header', wfdownloads_headerImage());

        $xoopsTpl->assign('submission_disclaimer', true);
        $xoopsTpl->assign('download_disclaimer', false);
        $xoopsTpl->assign('submission_disclaimer_content', $myts->displayTarea($wfdownloads->getConfig('disclaimer'), true, true, true, true, true));

        $xoopsTpl->assign('down_disclaimer', false); // this definition is not removed for backward compatibility issues
        $xoopsTpl->assign(
            'disclaimer',
            $myts->displayTarea($wfdownloads->getConfig('disclaimer'), true, true, true, true, true)
        ); // this definition is not removed for backward compatibility issues
        $xoopsTpl->assign('cancel_location', WFDOWNLOADS_URL . '/index.php'); // this definition is not removed for backward compatibility issues
        if (!isset($_REQUEST['lid'])) {
            $xoopsTpl->assign('agree_location', WFDOWNLOADS_URL . "/{$currentFile}?agreed=1");
        } else {
            $lid = WfdownloadsRequest::getInt('lid');
            $xoopsTpl->assign('agree_location', WFDOWNLOADS_URL . "/{$currentFile}?agreed=1&amp;lid={$lid}");
        }

        $xoopsTpl->assign('categoryPath', _MD_WFDOWNLOADS_DISCLAIMERAGREEMENT);
        $xoopsTpl->assign('module_home', wfdownloads_module_home(true));

        include 'footer.php';
        exit();
        break;

    case "download.form" :
    case "download.edit" :
    case "download.add" :
        // Show submit form
        if (($lid != 0) && is_object($xoopsUser)) {
            $download = $wfdownloads->getHandler('download')->get($lid);
            if ($xoopsUser->uid() != $download->getVar('submitter')) {
                redirect_header('index.php', 5, _MD_WFDOWNLOADS_NOTALLOWEDTOMOD);
            }
            $cid = $download->getVar('cid');
        } else {
            $download = $wfdownloads->getHandler('download')->create();
            $download->setVar('cid', $cid);
        }

        if (isset($_POST['submit_category']) && !empty($_POST['submit_category'])) {
            $category    = $wfdownloads->getHandler('category')->get($cid);
            $fid         = $category->getVar('formulize_fid');
            $customArray = array();
            if (wfdownloads_checkModule('formulize') && $fid) {
                include_once XOOPS_ROOT_PATH . "/modules/formulize/include/formdisplay.php";
                include_once XOOPS_ROOT_PATH . "/modules/formulize/include/functions.php";
                $customArray['fid']           = $fid;
                $customArray['formulize_mgr'] = xoops_getmodulehandler('elements', 'formulize');
                $customArray['groups']        = $xoopsUser ? $xoopsUser->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);
                $customArray['prevEntry']     = getEntryValues( // is a 'formulize' function
                    $download->getVar('formulize_idreq'),
                    $customArray['formulize_mgr'],
                    $customArray['groups'],
                    $fid
                );
                $customArray['entry']         = $download->getVar('formulize_idreq');
                $customArray['go_back']       = '';
                $customArray['parentLinks']   = '';
                if (wfdownloads_checkModule('formulize') < 300) {
                    $owner = getEntryOwner($entry); // is a 'formulize' function
                } else {
                    $owner = getEntryOwner($entry, $fid); // is a 'formulize' function
                }
                $owner_groups                = $member_handler->getGroupsByUser($owner, false);
                $customArray['owner_groups'] = $owner_groups;
            }
            $sform = $download->getForm($customArray);
        } elseif (wfdownloads_checkModule('formulize')) {
            $sform = $download->getCategoryForm();
        } else {
            $sform = $download->getForm();
        }

        $xoopsOption['template_main'] = "{$wfdownloads->getModule()->dirname()}_submit.tpl";
        include XOOPS_ROOT_PATH . '/header.php';

        $xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
        $xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
        $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
        $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

        $xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

        $catarray['imageheader'] = wfdownloads_headerImage();

        // Breadcrumb
        $breadcrumb = new WfdownloadsBreadcrumb();
        $breadcrumb->addLink($wfdownloads->getModule()->getVar('name'), WFDOWNLOADS_URL);
        $breadcrumb->addLink(_MD_WFDOWNLOADS_SUBMITDOWNLOAD, '');
        $xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

        $xoopsTpl->assign('catarray', $catarray);
        $xoopsTpl->assign('categoryPath', _MD_WFDOWNLOADS_SUBMITDOWNLOAD);
        $xoopsTpl->assign('module_home', wfdownloads_module_home(true));
        $xoopsTpl->assign('submit_form', $sform->render());

        include 'footer.php';
        exit();
        break;

    case "download.save" :
        // Save submitted download
        if (empty($_FILES['userfile']['name'])) {
            if ($_POST['url'] && $_POST['url'] != '' && $_POST['url'] != "http://") {
                $url      = ($_POST['url'] != "http://") ? $_POST['url'] : '';
                $filename = '';
                $filetype = '';
            } else {
                $url      = ($_POST['url'] != "http://") ? $_POST['url'] : '';
                $filename = $_POST['filename'];
                $filetype = $_POST['filetype'];
            }
            $size  = ((empty($_POST['size']) || !is_numeric($_POST['size']))) ? 0 : (int) $_POST['size'];
            $title = trim($_POST['title']);
        } else {
            $isAdmin = wfdownloads_userIsAdmin();
            $down = wfdownloads_uploading($_FILES, $wfdownloads->getConfig('uploaddir'), '', $currentFile, 0, false, $isAdmin);
            $url      = ($_POST['url'] != "http://") ? $_POST['url'] : '';
            $size     = $down['size'];
            $filename = $down['filename'];
            $filetype = $_FILES['userfile']['type'];
            $title    = $_FILES['userfile']['name'];
            $title    = rtrim(wfdownloads_strrrchr($title, "."), ".");
            $title    = (isset($_POST['title_checkbox']) && $_POST['title_checkbox'] == 1) ? $title : trim($_POST['title']);
        }

        // Load screenshot
        include_once WFDOWNLOADS_ROOT_PATH . '/class/img_uploader.php';
        $allowedMimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
        $uploadDirectory  = XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('screenshots') . '/';

        // Load screenshot #1
        $screenshot = '';
        if (isset($_FILES['screenshot']['name']) && !empty($_FILES['screenshot']['name'])) {
            $screenshot = strtolower($_FILES['screenshot']['name']);
            $uploader   = new XoopsMediaImgUploader($uploadDirectory, $allowedMimetypes, $wfdownloads->getConfig(
                'maxfilesize'
            ), $wfdownloads->getConfig('maximgwidth'), $wfdownloads->getConfig('maximgheight'));
            if (!$uploader->fetchMedia($_POST['xoops_upload_file'][1]) && !$uploader->upload()) {
                @unlink($uploadDirectory . $screenshot);
                redirect_header($currentFile, 1, $uploader->getErrors());
            }
        }
        // Load screenshot #2
        $screenshot2 = '';
        if ($wfdownloads->getConfig('max_screenshot') >= 2) {
            if (isset($_FILES['screenshot2']['name']) && !empty($_FILES['screenshot2']['name'])) {
                $screenshot2 = strtolower($_FILES['screenshot2']['name']);
                $uploader    = new XoopsMediaImgUploader($uploadDirectory, $allowedMimetypes, $wfdownloads->getConfig(
                    'maxfilesize'
                ), $wfdownloads->getConfig('maximgwidth'), $wfdownloads->getConfig('maximgheight'));
                if (!$uploader->fetchMedia($_POST['xoops_upload_file'][2]) && !$uploader->upload()) {
                    @unlink($uploadDirectory . $screenshot2);
                    redirect_header($currentFile, 1, $uploader->getErrors());
                }
            }
        }
        // Load screenshot #3
        $screenshot3 = '';
        if ($wfdownloads->getConfig('max_screenshot') >= 3) {
            if (isset($_FILES['screenshot3']['name']) && !empty($_FILES['screenshot3']['name'])) {
                $screenshot3 = strtolower($_FILES['screenshot3']['name']);
                $uploader    = new XoopsMediaImgUploader($uploadDirectory, $allowedMimetypes, $wfdownloads->getConfig(
                    'maxfilesize'
                ), $wfdownloads->getConfig('maximgwidth'), $wfdownloads->getConfig('maximgheight'));
                if (!$uploader->fetchMedia($_POST['xoops_upload_file'][3]) && !$uploader->upload()) {
                    @unlink($uploadDirectory . $screenshot3);
                    redirect_header($currentFile, 1, $uploader->getErrors());
                }
            }
        }
        // Load screenshot #4
        $screenshot4 = '';
        if ($wfdownloads->getConfig('max_screenshot') >= 4) {
            if (isset($_FILES['screenshot4']['name']) && !empty($_FILES['screenshot4']['name'])) {
                $screenshot4 = strtolower($_FILES['screenshot4']['name']);
                $uploader    = new XoopsMediaImgUploader($uploadDirectory, $allowedMimetypes, $wfdownloads->getConfig(
                    'maxfilesize'
                ), $wfdownloads->getConfig('maximgwidth'), $wfdownloads->getConfig('maximgheight'));
                if (!$uploader->fetchMedia($_POST['xoops_upload_file'][4]) && !$uploader->upload()) {
                    @unlink($uploadDirectory . $screenshot4);
                    redirect_header($currentFile, 1, $uploader->getErrors());
                }
            }
        }

        if ($lid > 0) {
            $isANewRecord = false;
            if ($wfdownloads->getConfig('autoapprove') == _WFDOWNLOADS_AUTOAPPROVE_DOWNLOAD
                || $wfdownloads->getConfig('autoapprove') == _WFDOWNLOADS_AUTOAPPROVE_BOTH
            ) {
                $download = $wfdownloads->getHandler('download')->get($lid);
            } else {
                $download = $wfdownloads->getHandler('modification')->create();
                $download->setVar('lid', $lid);
            }
        } else {
            $isANewRecord = true;
            $download     = $wfdownloads->getHandler('download')->create();
            if ($wfdownloads->getConfig('autoapprove') == _WFDOWNLOADS_AUTOAPPROVE_DOWNLOAD
                || $wfdownloads->getConfig('autoapprove') == _WFDOWNLOADS_AUTOAPPROVE_BOTH
            ) {
                $download->setVar('published', time());
                $download->setVar('status', _WFDOWNLOADS_STATUS_APPROVED);
            } else {
                $download->setVar('published', false);
                $download->setVar('status', _WFDOWNLOADS_STATUS_WAITING);
            }
        }

        // Added Formulize module support (2006/05/04) jpc - start
        if (wfdownloads_checkModule('formulize')) {
            // Now that the $download object has been instantiated, handle the Formulize part of the submission...
            $category = $wfdownloads->getHandler('category')->get($cid);
            $fid      = $category->getVar('formulize_fid');
            if ($fid) {
                include_once XOOPS_ROOT_PATH . "/modules/formulize/include/formread.php";
                include_once XOOPS_ROOT_PATH . "/modules/formulize/include/functions.php";
                $formulize_mgr =& xoops_getmodulehandler('elements', 'formulize');
                if ($lid) {
                    $entries[$fid][0] = $download->getVar('formulize_idreq');
                    if ($entries[$fid][0]) {
                        if (wfdownloads_checkModule('formulize') < 300) {
                            $owner = getEntryOwner($entries[$fid][0]);
                        } else {
                            $owner = getEntryOwner($entries[$fid][0], $fid);
                        }
                    } else {
                        $entries[$fid][0] = "";
                        $owner            = "";
                    }
                    $cid = $download->getVar('cid');
                } else {
                    $entries[$fid][0] = '';
                    $owner            = '';
                }
                $owner_groups =& $member_handler->getGroupsByUser($owner, false);
                $uid          = is_object($xoopsUser) ? (int) $xoopsUser->getVar('uid') : 0;
                $groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);
                $entries      = handleSubmission(
                    $formulize_mgr,
                    $entries,
                    $uid,
                    $owner,
                    $fid,
                    $owner_groups,
                    $groups,
                    "new"
                ); // "new" causes xoops token check to be skipped, since Wfdownloads should be doing that
                if (!$owner) {
                    $id_req = $entries[$fid][0];
                    $download->setVar('formulize_idreq', $id_req);
                }
            }
        }
        // Added Formulize module support (2006/05/04) jpc - end

        if (!empty($_POST['homepage']) || $_POST['homepage'] != "http://") {
            $download->setVar('homepage', formatURL(trim($_POST["homepage"])));
            $download->setVar('homepagetitle', trim($_POST["homepagetitle"]));
        }
        $download->setVar('title', $title);
        $download->setVar('url', $url);
        $download->setVar('cid', (int) $cid);
        $download->setVar('filename', $filename);
        $download->setVar('filetype', $filetype);

        /* Added by Lankford on 2007/3/21 */
        // Here, I want to know if:
        //    a) Are they actually changing the value of version, or is it the same?
        //    b) Are they actually modifying the record, or is this a new one?
        //  If both conditions are true, then trigger all three notifications related to modified records.
        $version = !empty($_POST["version"]) ? trim($_POST["version"]) : 0;

        if (!$isANewRecord && ($download->getVar('version') != $version)) {
            // Trigger the three events related to modified files (one for the file, category, and global event categories respectively)
            $tags                  = array();
            $tags['FILE_NAME']     = $title;
            $tags['FILE_URL']      = WFDOWNLOADS_URL . "/singlefile.php?cid={$cid}&amp;lid={$lid}";
            $category              = $wfdownloads->getHandler('category')->get($cid);
            $tags['FILE_VERSION']  = $version;
            $tags['CATEGORY_NAME'] = $category->getVar('title');
            $tags['CATEGORY_URL']  = WFDOWNLOADS_URL . "/viewcat.php?cid={$cid}";

            if ($wfdownloads->getConfig('autoapprove') == _WFDOWNLOADS_AUTOAPPROVE_DOWNLOAD
                || $wfdownloads->getConfig('autoapprove') == _WFDOWNLOADS_AUTOAPPROVE_BOTH
            ) {
                // Then this change will be automatically approved, so the notification needs to go out.
                $notification_handler->triggerEvent('global', 0, 'filemodified', $tags);
                $notification_handler->triggerEvent('category', $cid, 'filemodified', $tags);
                $notification_handler->triggerEvent('file', $lid, 'filemodified', $tags);
            }
        }
        /* End add block */

        $download->setVar('version', $_POST['version']);
        $download->setVar('size', $size);
        $download->setVar('platform', $_POST['platform']);
        $download->setVar('screenshot', $screenshot);
        $download->setVar('screenshot2', $screenshot2);
        $download->setVar('screenshot3', $screenshot3);
        $download->setVar('screenshot4', $screenshot4);
        $download->setVar('summary', $_POST['summary']);
        $download->setVar('description', $_POST['description']);
        $download->setVar('dohtml', isset($_POST['dohtml']));
        $download->setVar('dosmiley', isset($_POST['dosmiley']));
        $download->setVar('doxcode', isset($_POST['doxcode']));
        $download->setVar('doimage', isset($_POST['doimage']));
        $download->setVar('dobr', isset($_POST['dobr']));
        $submitter = is_object($xoopsUser) ? (int) $xoopsUser->getVar('uid') : 0;
        $download->setVar('submitter', $submitter);
        $download->setVar('publisher', trim($_POST['publisher']));
        $download->setVar('price', trim($_POST['price']));
        $download->setVar('mirror', isset($_POST['mirror']) ? trim($_POST['mirror']) : '');
        $download->setVar('license', trim($_POST['license']));
        $paypalemail = '';
        $download->setVar('features', trim($_POST['features']));
        $download->setVar('requirements', trim($_POST['requirements']));
        $forumid = (isset($_POST['forumid']) && $_POST["forumid"] > 0) ? (int) $_POST['forumid'] : 0;
        $download->setVar('forumid', $forumid);
        $limitations = isset($_POST['limitations']) ? $myts->addslashes($_POST['limitations']) : '';
        $download->setVar('limitations', $limitations);
        $versiontypes = isset($_POST['versiontypes']) ? $myts->addslashes($_POST['versiontypes']) : '';
        $download->setVar('versiontypes', $versiontypes);
        $dhistory        = isset($_POST['dhistory']) ? $myts->addslashes($_POST['dhistory']) : '';
        $dhistoryhistory = isset($_POST['dhistoryaddedd']) ? $myts->addslashes($_POST['dhistoryaddedd']) : '';
        if ($lid > 0 && !empty($dhistoryhistory)) {
            $dhistory = $dhistory . "\n\n";
            $dhistory .= "<b>" . formatTimestamp(time(), $wfdownloads->getConfig('dateformat')) . "</b>\n\n";
            $dhistory .= $dhistoryhistory;
        }
        $download->setVar('dhistory', $dhistory);
        $offline = (isset($_POST['offline']) && $_POST['offline'] == 1) ? true : false;
        $download->setVar('offline', $offline);
        $download->setVar('date', time());

        $screenshot  = '';
        $screenshot2 = '';
        $screenshot3 = '';
        $screenshot4 = '';

        if ($lid == 0) {
            $notifypub = (isset($_POST['notifypub']) && $_POST['notifypub'] == true);
            $download->setVar('notifypub', $notifypub);
            $download->setVar('ipaddress', $_SERVER['REMOTE_ADDR']);

            if (!$wfdownloads->getHandler('download')->insert($download)) {
                $error = _MD_WFDOWNLOADS_INFONOSAVEDB;
                trigger_error($error, E_USER_ERROR);
            }
            $newid  = (int) $download->getVar('lid');
            $groups = array(1, 2);
            //  Notify of new link (anywhere) and new link in category
            $tags                  = array();
            $tags['FILE_NAME']     = $title;
            $tags['FILE_URL']      = WFDOWNLOADS_URL . "/singlefile.php?cid={$cid}&amp;lid={$newid}";
            $category              = $wfdownloads->getHandler('category')->get($cid);
            $tags['CATEGORY_NAME'] = $category->getVar('title');
            $tags['CATEGORY_URL']  = WFDOWNLOADS_URL . "/viewcat.php?cid={$cid}";

            if ($wfdownloads->getConfig('autoapprove') == _WFDOWNLOADS_AUTOAPPROVE_DOWNLOAD
                || $wfdownloads->getConfig('autoapprove') == _WFDOWNLOADS_AUTOAPPROVE_BOTH
            ) {
                $notification_handler->triggerEvent('global', 0, 'new_file', $tags);
                $notification_handler->triggerEvent('category', $cid, 'new_file', $tags);
                redirect_header('index.php', 2, _MD_WFDOWNLOADS_ISAPPROVED);
            } else {
                $tags['WAITINGFILES_URL'] = WFDOWNLOADS_URL . '/admin/downloads.php';
                $notification_handler->triggerEvent('global', 0, 'file_submit', $tags);
                $notification_handler->triggerEvent('category', $cid, 'file_submit', $tags);
                if ($notify) {
                    include_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
                    $notification_handler->subscribe('file', $newid, 'approve', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE);
                }
                redirect_header('index.php', 2, _MD_WFDOWNLOADS_THANKSFORINFO);
            }
            exit();
        } else {
            if ($wfdownloads->getConfig('autoapprove') == _WFDOWNLOADS_AUTOAPPROVE_DOWNLOAD
                || $wfdownloads->getConfig('autoapprove') == _WFDOWNLOADS_AUTOAPPROVE_BOTH
            ) {
                $notifypub = (isset($_POST['notifypub']) && $_POST['notifypub'] == true);
                $download->setVar('notifypub', $notifypub);
                $download->setVar('ipaddress', $_SERVER['REMOTE_ADDR']);
                $download->setVar('updated', time());
                $wfdownloads->getHandler('download')->insert($download);

                $tags              = array();
                $tags['FILE_NAME'] = $title;
                $tags['FILE_URL']  = WFDOWNLOADS_URL . "/singlefile.php?cid={$cid}&amp;lid={$lid}";
                $category              = $wfdownloads->getHandler('category')->get($cid);
                $tags['CATEGORY_NAME'] = $category->getVar('title');
                $tags['CATEGORY_URL']  = WFDOWNLOADS_URL . "/viewcat.php?cid={$cid}";
                $notification_handler->triggerEvent('global', 0, 'file_modify', $tags);
                redirect_header('index.php', 2, _MD_WFDOWNLOADS_ISAPPROVED);
            } else {
                $updated = (isset($_POST['up_dated']) && $_POST['up_dated'] == 0) ? 0 : time();
                $download->setVar('updated', $updated);
                $download->setVar('modifysubmitter', (int) $xoopsUser->uid());
                $download->setVar('requestdate', time());
                if (!$wfdownloads->getHandler('modification')->insert($download)) {
                    $error = _MD_WFDOWNLOADS_INFONOSAVEDB;
                    trigger_error($error, E_USER_ERROR);
                }
                $tags                      = array();
                $tags['MODIFYREPORTS_URL'] = WFDOWNLOADS_URL . '/admin/reportsmodifications.php';
                $notification_handler->triggerEvent('global', 0, 'file_modify', $tags);
                redirect_header('index.php', 2, _MD_WFDOWNLOADS_THANKSFORINFO);
            }

        }
        break;
}
