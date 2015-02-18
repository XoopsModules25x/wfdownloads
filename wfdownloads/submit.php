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

// Check if submissions are allowed
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
    // if user is ANONYMOUS
    if (!is_object($GLOBALS['xoopsUser'])
        && ($wfdownloads->getConfig('anonpost') == _WFDOWNLOADS_ANONPOST_DOWNLOAD
            || $wfdownloads->getConfig('anonpost') == _WFDOWNLOADS_ANONPOST_BOTH)
    ) {
        $isSubmissionAllowed = true;
    } else {
        redirect_header(XOOPS_URL . '/user.php', 5, _MD_WFDOWNLOADS_MUSTREGFIRST);
    }
}
// Get categories where user can submit
$categoryObjs = $wfdownloads->getHandler('category')->getUserUpCategories();
if (count($categoryObjs) == 0) {
    $isSubmissionAllowed = false;
}
if ($isSubmissionAllowed == false) {
    redirect_header('index.php', 5, _MD_WFDOWNLOADS_NOTALLOWESTOSUBMIT);
}
// Check posts if user is not an ADMIN
if (is_object($GLOBALS['xoopsUser']) && !$GLOBALS['xoopsUser']->isAdmin()) {
    if ($GLOBALS['xoopsUser']->getVar('posts') < $wfdownloads->getConfig('upload_minposts')) {
        redirect_header('index.php', 5, _MD_WFDOWNLOADS_UPLOADMINPOSTS);
    }
}

$lid    = XoopsRequest::getInt('lid', 0);
$cid    = XoopsRequest::getInt('cid', 0);
$agreed = XoopsRequest::getBool('agreed', false, 'POST');
$op     = XoopsRequest::getString('op', 'download.form');
$notify = XoopsRequest::getBool('notify', false);

if ($wfdownloads->getConfig('showdisclaimer') && ($op == 'download.form') && $agreed == false) {
    $op = 'download.disclaimer';
}

switch ($op) {
    case 'download.disclaimer':
        // Show disclaimers
        $xoopsOption['template_main'] = "{$wfdownloads->getModule()->dirname()}_disclaimer.tpl";
        include_once XOOPS_ROOT_PATH . '/header.php';

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
            $lid = XoopsRequest::getInt('lid');
            $xoopsTpl->assign('agree_location', WFDOWNLOADS_URL . "/{$currentFile}?agreed=1&amp;lid={$lid}");
        }

        $xoopsTpl->assign('categoryPath', _MD_WFDOWNLOADS_DISCLAIMERAGREEMENT);
        $xoopsTpl->assign('module_home', wfdownloads_module_home(true));

        include_once __DIR__ . '/footer.php';
        exit();
        break;

    case 'download.form':
    case 'download.edit':
    case 'download.add':
        // Show submit form
        if (($lid != 0) && is_object($GLOBALS['xoopsUser'])) {
            $downloadObj = $wfdownloads->getHandler('download')->get($lid);
            if ($GLOBALS['xoopsUser']->uid() != $downloadObj->getVar('submitter')) {
                redirect_header('index.php', 5, _MD_WFDOWNLOADS_NOTALLOWEDTOMOD);
            }
            $cid = $downloadObj->getVar('cid');
        } else {
            $downloadObj = $wfdownloads->getHandler('download')->create();
            $downloadObj->setVar('cid', $cid);
        }
// Formulize module support - jpc - start
        if (isset($_POST['submit_category']) && !empty($_POST['submit_category'])) {
            // two steps form: 2nd step
            $categoryObj = $wfdownloads->getHandler('category')->get($cid);
            $fid         = $categoryObj->getVar('formulize_fid');
            $customArray = array();
            if (wfdownloads_checkModule('formulize') && $fid) {
                include_once XOOPS_ROOT_PATH . '/modules/formulize/include/formdisplay.php';
                include_once XOOPS_ROOT_PATH . '/modules/formulize/include/functions.php';
                $customArray['fid']           = $fid;
                $customArray['formulize_mgr'] = xoops_getmodulehandler('elements', 'formulize');
                $customArray['groups']        = $GLOBALS['xoopsUser'] ? $GLOBALS['xoopsUser']->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);
                $customArray['prevEntry']     = getEntryValues( // is a Formulize function
                    $downloadObj->getVar('formulize_idreq'),
                    $customArray['formulize_mgr'],
                    $customArray['groups'],
                    $fid,
                    null,
                    null,
                    null,
                    null,
                    null
                );
                $customArray['entry']         = $downloadObj->getVar('formulize_idreq');
                $customArray['go_back']       = '';
                $customArray['parentLinks']   = '';
                if (wfdownloads_checkModule('formulize') < 300) {
                    $owner = getEntryOwner($customArray['entry']); // is a Formulize function
                } else {
                    $owner = getEntryOwner($customArray['entry'], $fid); // is a Formulize function
                }
                $owner_groups                = $member_handler->getGroupsByUser($owner, false);
                $customArray['owner_groups'] = $owner_groups;
            }
            $sform = $downloadObj->getForm($customArray);
        } elseif (wfdownloads_checkModule('formulize')) {
            // two steps form: 1st step
            $sform = $downloadObj->getCategoryForm(_MD_WFDOWNLOADS_FFS_SUBMIT1ST_STEP);
        } else {
            // one step form: 1st step
            $sform = $downloadObj->getForm();
        }
// Formulize module support - jpc - end
        $xoopsOption['template_main'] = "{$wfdownloads->getModule()->dirname()}_submit.tpl";
        include_once XOOPS_ROOT_PATH . '/header.php';

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

        include_once __DIR__ . '/footer.php';
        exit();
        break;

    case 'download.save':
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
            $size  = ((empty($_POST['size']) || !is_numeric($_POST['size']))) ? 0 : (int)$_POST['size'];
            $title = trim($_POST['title']);
        } else {
            $isAdmin  = wfdownloads_userIsAdmin();
            $down     = wfdownloads_uploading($_FILES, $wfdownloads->getConfig('uploaddir'), '', $currentFile, 0, false, $isAdmin);
            $url      = ($_POST['url'] != "http://") ? $_POST['url'] : '';
            $size     = $down['size'];
            $filename = $down['filename'];
            $filetype = $_FILES['userfile']['type'];
            $title    = $_FILES['userfile']['name'];
            $title    = rtrim(wfdownloads_strrrchr($title, '.'), '.');
            $title    = (isset($_POST['title_checkbox']) && $_POST['title_checkbox'] == 1) ? $title : trim($_POST['title']);
        }

        // Load screenshot
        include_once WFDOWNLOADS_ROOT_PATH . '/class/img_uploader.php';
        $allowedMimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
        $uploadDirectory  = XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('screenshots') . '/';

        $screenshots = array();

        // Load screenshot #1
        $screenshot1 = '';
        if (isset($_FILES['screenshot']['name']) && !empty($_FILES['screenshot']['name'])) {
            $screenshot1 = strtolower($_FILES['screenshot']['name']);
            $uploader    = new XoopsMediaImgUploader(
                $uploadDirectory, $allowedMimetypes, $wfdownloads->getConfig(
                    'maxfilesize'
                ), $wfdownloads->getConfig('maximgwidth'), $wfdownloads->getConfig('maximgheight')
            );
            if (!$uploader->fetchMedia($_POST['xoops_upload_file'][1]) && !$uploader->upload()) {
                @unlink($uploadDirectory . $screenshot1);
                redirect_header($currentFile, 1, $uploader->getErrors());
            }
        }
        $screenshots[] = $screenshot1;
        // Load screenshot #2
        $screenshot2 = '';
        if ($wfdownloads->getConfig('max_screenshot') >= 2) {
            if (isset($_FILES['screenshot2']['name']) && !empty($_FILES['screenshot2']['name'])) {
                $screenshot2 = strtolower($_FILES['screenshot2']['name']);
                $uploader    = new XoopsMediaImgUploader(
                    $uploadDirectory, $allowedMimetypes, $wfdownloads->getConfig(
                        'maxfilesize'
                    ), $wfdownloads->getConfig('maximgwidth'), $wfdownloads->getConfig('maximgheight')
                );
                if (!$uploader->fetchMedia($_POST['xoops_upload_file'][2]) && !$uploader->upload()) {
                    @unlink($uploadDirectory . $screenshot2);
                    redirect_header($currentFile, 1, $uploader->getErrors());
                }
            }
        }
        $screenshots[] = $screenshot2;
        // Load screenshot #3
        $screenshot3 = '';
        if ($wfdownloads->getConfig('max_screenshot') >= 3) {
            if (isset($_FILES['screenshot3']['name']) && !empty($_FILES['screenshot3']['name'])) {
                $screenshot3 = strtolower($_FILES['screenshot3']['name']);
                $uploader    = new XoopsMediaImgUploader(
                    $uploadDirectory, $allowedMimetypes, $wfdownloads->getConfig(
                        'maxfilesize'
                    ), $wfdownloads->getConfig('maximgwidth'), $wfdownloads->getConfig('maximgheight')
                );
                if (!$uploader->fetchMedia($_POST['xoops_upload_file'][3]) && !$uploader->upload()) {
                    @unlink($uploadDirectory . $screenshot3);
                    redirect_header($currentFile, 1, $uploader->getErrors());
                }
            }
        }
        $screenshots[] = $screenshot3;
        // Load screenshot #4
        $screenshot4 = '';
        if ($wfdownloads->getConfig('max_screenshot') >= 4) {
            if (isset($_FILES['screenshot4']['name']) && !empty($_FILES['screenshot4']['name'])) {
                $screenshot4 = strtolower($_FILES['screenshot4']['name']);
                $uploader    = new XoopsMediaImgUploader(
                    $uploadDirectory, $allowedMimetypes, $wfdownloads->getConfig(
                        'maxfilesize'
                    ), $wfdownloads->getConfig('maximgwidth'), $wfdownloads->getConfig('maximgheight')
                );
                if (!$uploader->fetchMedia($_POST['xoops_upload_file'][4]) && !$uploader->upload()) {
                    @unlink($uploadDirectory . $screenshot4);
                    redirect_header($currentFile, 1, $uploader->getErrors());
                }
            }
        }
        $screenshots[] = $screenshot4;

        if ($lid > 0) {
            $isANewRecord = false;
            if ($wfdownloads->getConfig('autoapprove') == _WFDOWNLOADS_AUTOAPPROVE_DOWNLOAD
                || $wfdownloads->getConfig('autoapprove') == _WFDOWNLOADS_AUTOAPPROVE_BOTH
            ) {
                $downloadObj = $wfdownloads->getHandler('download')->get($lid);
            } else {
                $downloadObj = $wfdownloads->getHandler('modification')->create();
                $downloadObj->setVar('lid', $lid);
            }
        } else {
            $isANewRecord = true;
            $downloadObj  = $wfdownloads->getHandler('download')->create();
            if ($wfdownloads->getConfig('autoapprove') == _WFDOWNLOADS_AUTOAPPROVE_DOWNLOAD
                || $wfdownloads->getConfig('autoapprove') == _WFDOWNLOADS_AUTOAPPROVE_BOTH
            ) {
                $downloadObj->setVar('published', time());
                $downloadObj->setVar('status', _WFDOWNLOADS_STATUS_APPROVED);
            } else {
                $downloadObj->setVar('published', false);
                $downloadObj->setVar('status', _WFDOWNLOADS_STATUS_WAITING);
            }
        }

// Formulize module support (2006/05/04) jpc - start
        if (wfdownloads_checkModule('formulize')) {
            // Now that the $downloadObj object has been instantiated, handle the Formulize part of the submission...
            $categoryObj = $wfdownloads->getHandler('category')->get($cid);
            $fid         = $categoryObj->getVar('formulize_fid');
            if ($fid) {
                include_once XOOPS_ROOT_PATH . '/modules/formulize/include/formread.php';
                include_once XOOPS_ROOT_PATH . '/modules/formulize/include/functions.php';
                $formulizeElements_handler = xoops_getmodulehandler('elements', 'formulize');
                if ($lid) {
                    $entries[$fid][0] = $downloadObj->getVar('formulize_idreq');
                    if ($entries[$fid][0]) {
                        if (wfdownloads_checkModule('formulize') < 300) {
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
                $owner_groups =& $member_handler->getGroupsByUser($owner, false);
                $uid          = is_object($GLOBALS['xoopsUser']) ? (int)$GLOBALS['xoopsUser']->getVar('uid') : 0;
                $groups       = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);
                $entries      = handleSubmission( // is a Formulize function
                    $formulizeElements_handler,
                    $entries,
                    $uid,
                    $owner,
                    $fid,
                    $owner_groups,
                    $groups,
                    'new'
                ); // 'new' causes xoops token check to be skipped, since Wfdownloads should be doing that
                if (!$owner) {
                    $id_req = $entries[$fid][0];
                    $downloadObj->setVar('formulize_idreq', $id_req);
                }
            }
        }
// Formulize module support (2006/05/04) jpc - end

        if (!empty($_POST['homepage']) || $_POST['homepage'] != 'http://') {
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
            $tags                  = array();
            $tags['FILE_NAME']     = $title;
            $tags['FILE_URL']      = WFDOWNLOADS_URL . "/singlefile.php?cid={$cid}&amp;lid={$lid}";
            $categoryObj           = $wfdownloads->getHandler('category')->get($cid);
            $tags['FILE_VERSION']  = $version;
            $tags['CATEGORY_NAME'] = $categoryObj->getVar('title');
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
        $limitations = isset($_POST['limitations']) ? $myts->addslashes($_POST['limitations']) : '';
        $downloadObj->setVar('limitations', $limitations);
        $versiontypes = isset($_POST['versiontypes']) ? $myts->addslashes($_POST['versiontypes']) : '';
        $downloadObj->setVar('versiontypes', $versiontypes);
        $dhistory        = isset($_POST['dhistory']) ? $myts->addslashes($_POST['dhistory']) : '';
        $dhistoryhistory = isset($_POST['dhistoryaddedd']) ? $myts->addslashes($_POST['dhistoryaddedd']) : '';
        if ($lid > 0 && !empty($dhistoryhistory)) {
            $dhistory = $dhistory . "\n\n";
            $dhistory .= "<b>" . formatTimestamp(time(), $wfdownloads->getConfig('dateformat')) . "</b>\n\n";
            $dhistory .= $dhistoryhistory;
        }
        $downloadObj->setVar('dhistory', $dhistory);
        $offline = (isset($_POST['offline']) && $_POST['offline'] == 1) ? true : false;
        $downloadObj->setVar('offline', $offline);
        $downloadObj->setVar('date', time());
        /*
                $screenshot1 = '';
                $screenshot2 = '';
                $screenshot3 = '';
                $screenshot4 = '';
        */
        if ($lid == 0) {
            $notifypub = (isset($_POST['notifypub']) && $_POST['notifypub'] == true);
            $downloadObj->setVar('notifypub', $notifypub);
            $downloadObj->setVar('ipaddress', $_SERVER['REMOTE_ADDR']);

            if (!$wfdownloads->getHandler('download')->insert($downloadObj)) {
                $error = _MD_WFDOWNLOADS_INFONOSAVEDB;
                trigger_error($error, E_USER_ERROR);
            }
            $newid  = (int)$downloadObj->getVar('lid');
            $groups = array(1, 2);
            //  Notify of new link (anywhere) and new link in category
            $tags                  = array();
            $tags['FILE_NAME']     = $title;
            $tags['FILE_URL']      = WFDOWNLOADS_URL . "/singlefile.php?cid={$cid}&amp;lid={$newid}";
            $categoryObj           = $wfdownloads->getHandler('category')->get($cid);
            $tags['CATEGORY_NAME'] = $categoryObj->getVar('title');
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
                $downloadObj->setVar('notifypub', $notifypub);
                $downloadObj->setVar('ipaddress', $_SERVER['REMOTE_ADDR']);
                $downloadObj->setVar('updated', time());
                $wfdownloads->getHandler('download')->insert($downloadObj);

                $tags                  = array();
                $tags['FILE_NAME']     = $title;
                $tags['FILE_URL']      = WFDOWNLOADS_URL . "/singlefile.php?cid={$cid}&amp;lid={$lid}";
                $categoryObj           = $wfdownloads->getHandler('category')->get($cid);
                $tags['CATEGORY_NAME'] = $categoryObj->getVar('title');
                $tags['CATEGORY_URL']  = WFDOWNLOADS_URL . "/viewcat.php?cid={$cid}";
                $notification_handler->triggerEvent('global', 0, 'file_modify', $tags);
                redirect_header('index.php', 2, _MD_WFDOWNLOADS_ISAPPROVED);
            } else {
                $updated = (isset($_POST['up_dated']) && $_POST['up_dated'] == 0) ? 0 : time();
                $downloadObj->setVar('updated', $updated);
                $downloadObj->setVar('modifysubmitter', (int)$GLOBALS['xoopsUser']->uid());
                $downloadObj->setVar('requestdate', time());
                if (!$wfdownloads->getHandler('modification')->insert($downloadObj)) {
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
