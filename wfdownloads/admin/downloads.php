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
include_once dirname(__FILE__) . '/admin_header.php';

if (!is_dir($wfdownloads->getConfig('uploaddir'))) {
    redirect_header('index.php', 4, _AM_WFDOWNLOADS_ERROR_UPLOADDIRNOTEXISTS);
    exit();
}

$op = WfdownloadsRequest::getString('op', 'downloads.list');
switch ($op) {
    case "download.edit" :
    case "download.add" :
    case "Download" :
        wfdownloads_xoops_cp_header();
        $indexAdmin = new ModuleAdmin();
        echo $indexAdmin->addNavigation($currentFile);

        $adminMenu = new ModuleAdmin();
        $adminMenu->addItemButton(_MI_WFDOWNLOADS_MENU_DOWNLOADS, "{$currentFile}?op=downloads.list", 'list');
        echo $adminMenu->renderButton();

        $lid = WfdownloadsRequest::getInt('lid', 0);

        $category_count = $wfdownloads->getHandler('category')->getCount();
        if ($category_count) {
            // Allowed mimetypes list
            echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WFDOWNLOADS_FILE_ALLOWEDAMIME . "</legend>\n";
            echo "<div style='padding: 8px;'>\n";
            $criteria       = new Criteria("mime_admin", true);
            $mimetypes      = $wfdownloads->getHandler('mimetype')->getList($criteria);
            $allowMimetypes = implode(' | ', $mimetypes);
            echo $allowMimetypes;
            echo "</div>\n";
            echo "</fieldset><br />\n";

            if ($lid) {
                if (!$download = $wfdownloads->getHandler('download')->get($lid)) {
                    redirect_header($currentFile, 4, _AM_WFDOWNLOADS_DOWN_ERROR_FILENOTFOUND);
                    exit();
                }
                $cid = $download->getVar('cid');
                if (!$category = $wfdownloads->getHandler('category')->get($cid)) {
                    redirect_header($currentFile, 4, _AM_WFDOWNLOADS_DOWN_ERROR_CATEGORYNOTFOUND);
                    exit();
                }
                $title = preg_replace("/{category}/", $category->getVar('title'), _AM_WFDOWNLOADS_FFS_EDITDOWNLOADTITLE);
            } else {
                $download = $wfdownloads->getHandler('download')->create();
                $cid      = WfdownloadsRequest::getInt('cid', 0, 'POST');
                $category = $wfdownloads->getHandler('category')->get($cid);
                $download->setVar('cid', $cid);
                $title = preg_replace("/{category}/", $category->getVar('title'), _AM_WFDOWNLOADS_FFS_DOWNLOADTITLE);
            }

            // Added Formulize module support (2006/05/04) jpc - start
            if (!wfdownloads_checkModule('formulize')) {
                $sform = $download->getAdminForm($title);
            } elseif ((isset($_POST['submit_category']) && !empty($_POST['submit_category'])) || $lid) {
                $fid         = $category->getVar('formulize_fid');
                $customArray = array();
                if ($fid) {
                    include_once XOOPS_ROOT_PATH . "/modules/formulize/include/formdisplay.php";
                    include_once XOOPS_ROOT_PATH . "/modules/formulize/include/functions.php";
                    $customArray['fid']           = $fid;
                    $customArray['formulize_mgr'] =& xoops_getmodulehandler('elements', 'formulize');
                    $customArray['groups']        = $xoopsUser ? $xoopsUser->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);
                    $customArray['prevEntry']     = getEntryValues( // is a 'formulize' function
                        $download->getVar('formulize_idreq'),
                        $customArray['formulize_mgr'],
                        $customArray['groups'],
                        $fid
                    );
                    $customArray['entry']         = $download->getVar('formulize_idreq');
                    $customArray['go_back']       = "";
                    $customArray['parentLinks']   = "";
                    if (wfdownloads_checkModule('formulize') < 300) {
                        $owner = getEntryOwner($entry); // is a 'formulize' function
                    } else {
                        $owner = getEntryOwner($entry, $fid); // is a 'formulize' function
                    }
                    $ownerGroups                 = $member_handler->getGroupsByUser($owner, false);
                    $customArray['owner_groups'] = $ownerGroups;
                }
                $sform = $download->getAdminForm($title, $customArray);
            } else {
                $sform = $download->getCategoryForm();
            }
            $sform->display();
            // Added Formulize module support (2006/05/04) jpc - end
        } else {
            redirect_header('categories.php', 1, _AM_WFDOWNLOADS_CCATEGORY_NOEXISTS);
            exit();
        }

        // Vote data list/manager
        if ($lid) {
            $votes_count = $wfdownloads->getHandler('rating')->getCount();

            $registeredCriteria = new CriteriaCompo(new Criteria('lid', $lid));
            $registeredCriteria->add(new Criteria('ratinguser', 0, '>'));
            $votesreg = $wfdownloads->getHandler('rating')->getCount($registeredCriteria);
            $registeredCriteria->setSort('ratingtimestamp');
            $registeredCriteria->setOrder('DESC');
            $regvotes = $wfdownloads->getHandler('rating')->getObjects($registeredCriteria);

            $anonymousCriteria = new CriteriaCompo(new Criteria('lid', $lid));
            $anonymousCriteria->add(new Criteria('ratinguser', 0, '='));
            $votesanon = $wfdownloads->getHandler('rating')->getCount($anonymousCriteria);
            $anonymousCriteria->setSort('ratingtimestamp');
            $anonymousCriteria->setOrder('DESC');

            echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WFDOWNLOADS_VOTE_RATINGINFOMATION . "</legend>\n";
            echo "<div style='padding: 8px;'><b>" . _AM_WFDOWNLOADS_VOTE_TOTALVOTES . "</b>{$votes_count}<br /><br />\n";

            printf(_AM_WFDOWNLOADS_VOTE_REGUSERVOTES, $votesreg);

            echo "<br />";

            printf(_AM_WFDOWNLOADS_VOTE_ANONUSERVOTES, $votesanon);

            echo "
                </div>\n
                <table width='100%' cellspacing='1' cellpadding='2' class='outer'>\n
                <tr>\n
                <th>" . _AM_WFDOWNLOADS_VOTE_USER . "</td>\n
                <th>" . _AM_WFDOWNLOADS_VOTE_IP . "</td>\n
                <th>" . _AM_WFDOWNLOADS_VOTE_RATING . "</td>\n
                <th>" . _AM_WFDOWNLOADS_VOTE_USERAVG . "</td>\n
                <th>" . _AM_WFDOWNLOADS_VOTE_TOTALRATE . "</td>\n
                <th>" . _AM_WFDOWNLOADS_VOTE_DATE . "</td>\n
                <th>" . _AM_WFDOWNLOADS_MINDEX_ACTION . "</td>\n
                </tr>\n
                ";

            if ($votesreg == 0) {
                echo "<tr><td colspan='7' class='even'><b>" . _AM_WFDOWNLOADS_VOTE_NOREGVOTES . "</b></td></tr>";
            } else {
                foreach (array_keys($regvotes) as $i) {
                    $uids[] = $regvotes[$i]->getVar('ratinguser');
                }

                $criteria = new Criteria("ratinguser", "(" . implode(',', $uids) . ")", "IN");
                $criteria->setGroupby("ratinguser");
                $userRatings = $wfdownloads->getHandler('rating')->getUserAverage($criteria);

                foreach (array_keys($regvotes) as $i) {
                    $formatted_date = XoopsLocal::formatTimestamp($regvotes[$i]->getVar('ratingtimestamp'), 'l');
                    $userAvgRating  = isset($userRatings[$regvotes[$i]->getVar('ratinguser')]) ? $userRatings[$regvotes[$i]->getVar(
                        'ratinguser'
                    )]["avg"] : 0;
                    $userVotes      = isset($userRatings[$regvotes[$i]->getVar('ratinguser')]) ? $userRatings[$regvotes[$i]->getVar(
                        'ratinguser'
                    )]["count"] : 0;
                    $ratingUserName    = XoopsUser :: getUnameFromId($regvotes[$i]->getVar('ratinguser'));

                    echo "
                        <tr><td class='head'>$ratingUserName</td>\n
                        <td class='even'>" . $regvotes[$i]->getVar('ratinghostname') . "</th>\n
                        <td class='even'>" . $regvotes[$i]->getVar('rating') . "</th>\n
                        <td class='even'>$userAvgRating</th>\n
                        <td class='even'>$userVotes</th>\n
                        <td class='even'>$formatted_date</th>\n
                        <td class='even'>\n
                        <a href='{$currentFile}?op=vote.delete&amp;lid={$lid}&amp;rid=" . $regvotes[$i]->getVar('ratingid') . "'>"
                        . $imagearray['deleteimg'] . "</a>\n
                        </th></tr>\n
                        ";
                }
            }
            echo "
                </table>\n
                <br />\n
                <table width='100%' cellspacing='1' cellpadding='2' class='outer'>\n
                <tr>\n
                <th>" . _AM_WFDOWNLOADS_VOTE_USER . "</td>\n
                <th>" . _AM_WFDOWNLOADS_VOTE_IP . "</td>\n
                <th>" . _AM_WFDOWNLOADS_VOTE_RATING . "</td>\n
                <th>" . _AM_WFDOWNLOADS_VOTE_USERAVG . "</td>\n
                <th>" . _AM_WFDOWNLOADS_VOTE_TOTALRATE . "</td>\n
                <th>" . _AM_WFDOWNLOADS_VOTE_DATE . "</td>\n
                <th>" . _AM_WFDOWNLOADS_MINDEX_ACTION . "</td>\n
                </tr>\n
                ";
            if ($votesanon == 0) {
                echo "<tr><td colspan='7' class='even'><b>" . _AM_WFDOWNLOADS_VOTE_NOUNREGVOTES . "</b></td></tr>";
            } else {
                $criteria       = new Criteria('ratinguser', 0);
                $userRatings    = $wfdownloads->getHandler('rating')->getUserAverage($criteria);
                $anonymousVotes = $wfdownloads->getHandler('rating')->getObjects($anonymousCriteria);

                foreach (array_keys($anonymousVotes) as $i) {
                    $formatted_date = XoopsLocal::formatTimestamp($anonymousVotes[$i]->getVar('ratingtimestamp'), 'l');
                    $userAvgRating  = isset($userRatings['avg']) ? $userRatings["avg"] : 0;
                    $userVotes      = isset($userRatings['count']) ? $userRatings["count"] : 0;

                    $ratingUserName = $GLOBALS['xoopsConfig']['anonymous'];

                    echo "
                        <tr><td class='head'>$ratingUserName</td>\n
                        <td class='even'>" . $anonymousVotes[$i]->getVar('ratinghostname') . "</th>\n
                        <td class='even'>" . $anonymousVotes[$i]->getVar('rating') . "</th>\n
                        <td class='even'>$userAvgRating</th>\n
                        <td class='even'>$userVotes</th>\n
                        <td class='even'>$formatted_date</th>\n
                        <td class='even'>\n
                        <a href='{$currentFile}?op=vote.delete&amp;lid={$lid}&amp;rid=" . $anonymousVotes[$i]->getVar('ratingid') . "'>"
                        . $imagearray['deleteimg'] . "</a>\n
                        </th></tr>\n
                        ";
                }
            }
            echo "</table>\n";
            echo "</fieldset>\n";
        }
        include 'admin_footer.php';
        break;

    case "download.save" :
    case "addDownload" :
        $lid    = WfdownloadsRequest::getInt('lid', 0, 'POST');
        $cid    = WfdownloadsRequest::getInt('cid', 0, 'POST');
        $status = WfdownloadsRequest::getInt('status', _WFDOWNLOADS_STATUS_UPDATED, 'POST');

        if ($lid > 0) {
            $thisIsANewRecord = false; /* Added by Lankford on 2007/3/21 */
            $download         = $wfdownloads->getHandler('download')->get($lid);
        } else {
            $thisIsANewRecord = true; /* Added by Lankford on 2007/3/21 */
            $download         = $wfdownloads->getHandler('download')->create();
        }
        // Define URL
        if (empty($_FILES['userfile']['name'])) {
            if ($_POST['url'] && $_POST['url'] != "" && $_POST['url'] != "http://") {
                $url      = ($_POST['url'] != "http://") ? $_POST['url'] : '';
                $filename = '';
                $filetype = '';
                // Get size from form
                $size = (empty($_POST['size']) || !is_numeric($_POST['size'])) ? 0 : (int) $_POST["size"];
            } else {
                $url      = ($_POST["url"] != "http://") ? $_POST['url'] : '';
                $filename = $_POST['filename'];
                $filetype = $_POST['filetype'];
                $filePath = $wfdownloads->getConfig('uploaddir') . '/' . $filename;
                // Get size from filesystem
                $size = @filesize($filePath);
            }
            $title = trim($_POST['title']);
            $download->setVar('filename', $filename);
            $download->setVar('filetype', $filetype);
        } else {
            $down  = wfdownloads_uploading($_FILES, $wfdownloads->getConfig('uploaddir'), '', $currentFile, 0, false, true);
            $url   = ($_POST['url'] != "http://") ? $_POST["url"] : '';
            $size  = $down['size'];
            $title = $_FILES['userfile']['name'];

            $ext   = rtrim(strrchr($title, '.'), '.');
            $title = str_replace($ext, '', $title);
            $title = (isset($_POST['title_checkbox']) && $_POST['title_checkbox'] == 1) ? $title : trim($_POST['title']);

            $filename = $down['filename'];
            $filetype = $_FILES['userfile']['type'];
            $download->setVar('filename', $filename);
            $download->setVar('filetype', $filetype);
        }
        // Get data from form
        $screenshot  = ($_POST['screenshot'] != 'blank.png') ? $_POST['screenshot'] : '';
        $screenshot2 = ($_POST['screenshot2'] != 'blank.png') ? $_POST['screenshot2'] : '';
        $screenshot3 = ($_POST['screenshot3'] != 'blank.png') ? $_POST['screenshot3'] : '';
        $screenshot4 = ($_POST['screenshot4'] != 'blank.png') ? $_POST['screenshot4'] : '';

        if (!empty($_POST['homepage']) || $_POST['homepage'] != "http://") {
            $download->setVar('homepage', trim($_POST['homepage']));
            $download->setVar('homepagetitle', trim($_POST['homepagetitle']));
        }

        $version = !empty($_POST['version']) ? trim($_POST['version']) : 0;

        /* Added by Lankford on 2007/3/21 */
        // Here, I want to know if:
        //    a) Are they actually changing the value of version, or is it the same?
        //    b) Are they actually modifying the record, or is this a new one?
        //  If both conditions are true, then trigger all three notifications related to modified records.
        if (!$thisIsANewRecord && ($download->getVar('version') != $version)) {
            // Trigger the three events related to modified files (one for the file, category, and global event categories respectively)
            $tags                  = array();
            $tags['FILE_NAME']     = $title;
            $tags['FILE_URL']      = WFDOWNLOADS_URL . "/singlefile.php?cid={$cid}&amp;lid={$lid}";
            $category              = $wfdownloads->getHandler('category')->get($cid);
            $tags['FILE_VERSION']  = $version;
            $tags['CATEGORY_NAME'] = $category->getVar('title');
            $tags['CATEGORY_URL']  = WFDOWNLOADS_URL . "/viewcat.php?cid='{$cid}";

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
        $download->setVar('version', $version);
        $download->setVar('url', $url);
        $download->setVar('cid', $cid);
        $download->setVar('title', $title);
        $download->setVar('status', $status);
        $download->setVar('size', $size);
        $download->setVar('screenshot', $screenshot);
        $download->setVar('screenshot2', $screenshot2);
        $download->setVar('screenshot3', $screenshot3);
        $download->setVar('screenshot4', $screenshot4);
        $download->setVar('platform', trim($_POST['platform']));
        $download->setVar('summary', trim($_POST['summary']));
        $download->setVar('description', trim($_POST['description']));
        $download->setVar('dohtml', isset($_POST['dohtml']));
        $download->setVar('dosmiley', isset($_POST['dosmiley']));
        $download->setVar('doxcode', isset($_POST['doxcode']));
        $download->setVar('doimage', isset($_POST['doimage']));
        $download->setVar('dobr', isset($_POST['dobr']));
        $download->setVar('submitter', trim($_POST['submitter']));
        $download->setVar('publisher', trim($_POST['publisher']));
        $download->setVar('price', trim($_POST['price']));
        if (!$wfdownloads->getConfig('enable_mirrors')) {
            $download->setVar('mirror', formatURL(trim($_POST['mirror'])));
        }
        $download->setVar('license', trim($_POST['license']));
        $download->setVar('features', trim($_POST['features']));
        $download->setVar('requirements', trim($_POST['requirements']));
        $limitations = (isset($_POST['limitations'])) ? $_POST['limitations'] : '';
        $download->setVar('limitations', $limitations);
        $versiontypes = (isset($_POST['versiontypes'])) ? $_POST['versiontypes'] : '';
        $download->setVar('versiontypes', $versiontypes);

        $dhistory        = (isset($_POST['dhistory'])) ? $_POST['dhistory'] : '';
        $dhistoryhistory = (isset($_POST['dhistoryaddedd'])) ? $_POST['dhistoryaddedd'] : '';

        if ($lid > 0 && !empty($dhistoryhistory)) {
            $dhistory = $dhistory . "\n\n";
            $time     = time();
            $dhistory .= _AM_WFDOWNLOADS_FILE_HISTORYVERS . $version . _AM_WFDOWNLOADS_FILE_HISTORDATE . XoopsLocal::formatTimestamp($time, 'l') . "\n\n";
            $dhistory .= $dhistoryhistory;
        }
        $download->setVar('dhistory', $dhistory);
        $download->setVar('dhistoryhistory', $dhistoryhistory);

        $updated = (isset($_POST['was_published']) && $_POST['was_published'] == 0) ? 0 : time();

        if ($_POST['up_dated'] == 0) {
            $updated = 0;
        }
        $download->setVar('updated', $updated);

        $offline = ($_POST['offline'] == true) ? true : false;
        $download->setVar('offline', $offline);
        $approved  = (isset($_POST['approved']) && $_POST['approved'] == true) ? true : false;
        $notifypub = (isset($_POST['notifypub']) && $_POST['notifypub'] == true);

        $expiredate = 0;
        if (!$lid) {
            $publishdate = time();
        } else {
            $publishdate = $_POST['was_published'];
            $expiredate  = $_POST['was_expired'];
        }
        if ($approved == 1 && empty($publishdate)) {
            $publishdate = time();
        }
        if (isset($_POST['publishdateactivate'])) {
            $publishdate = strtotime($_POST['published']['date']) + $_POST['published']['time'];
        }
        if ($_POST['clearpublish']) {
            $publishdate = $download->getVar('published');
        }
        if (isset($_POST['expiredateactivate'])) {
            $expiredate = strtotime($_POST['expired']['date']) + $_POST['expired']['time'];
        }
        if ($_POST['clearexpire']) {
            $expiredate = '0';
        }

        $download->setVar('expired', $expiredate);
        $download->setVar('published', $publishdate);
        $download->setVar('date', time());
        // Update or insert download data into database
        if (!$lid) {
            $download->setVar('ipaddress', $_SERVER['REMOTE_ADDR']);
        }

        $category = $wfdownloads->getHandler('category')->get($cid);

        // Added Formulize module support (2006/05/04) jpc - start
        if (wfdownloads_checkModule('formulize')) {
            $fid = $category->getVar('formulize_fid');
            if ($fid) {
                include_once XOOPS_ROOT_PATH . "/modules/formulize/include/formread.php";
                include_once XOOPS_ROOT_PATH . "/modules/formulize/include/functions.php";
                $formulize_mgr =& xoops_getmodulehandler('elements', 'formulize');
                if ($lid) {
                    $entries[$fid][0] = $download->getVar('formulize_idreq');
                    if ($entries[$fid][0]) {
                        if (wfdownloads_checkModule('formulize') < 300) {
                            $owner = getEntryOwner($entries[$fid][0]); // is a 'formulize' function
                        } else {
                            $owner = getEntryOwner($entries[$fid][0], $fid); // is a 'formulize' function
                        }
                    } else {
                        print "no idreq";
                        $entries[$fid][0] = "";
                        $owner            = "";
                    }
                    $cid = $download->getVar('cid');
                } else {
                    $entries[$fid][0] = "";
                    $owner            = "";
                }
                $ownerGroups =& $member_handler->getGroupsByUser($owner, false);
                $uid         = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
                $groups      = $xoopsUser ? $xoopsUser->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);
                $entries     = handleSubmission(
                    $formulize_mgr,
                    $entries,
                    $uid,
                    $owner,
                    $fid,
                    $ownerGroups,
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
        $wfdownloads->getHandler('download')->insert($download);
        $newid = (int) $download->getVar('lid');
        // Send notifications
        if (!$lid) {
            $tags                  = array();
            $tags['FILE_NAME']     = $title;
            $tags['FILE_URL']      = WFDOWNLOADS_URL . "/singlefile.php?cid={$cid}&amp;lid={$newid}";
            $tags['CATEGORY_NAME'] = $category->getVar('title');
            $tags['CATEGORY_URL']  = WFDOWNLOADS_URL . "/viewcat.php?cid={$cid}";
            $notification_handler->triggerEvent('global', 0, 'new_file', $tags);
            $notification_handler->triggerEvent('category', $cid, 'new_file', $tags);
        }
        if ($lid && $approved && $notifypub) {
            $tags                  = array();
            $tags['FILE_NAME']     = $title;
            $tags['FILE_URL']      = WFDOWNLOADS_URL . "/singlefile.php?cid={$cid}&amp;lid={$lid}";
            $category              = $wfdownloads->getHandler('category')->get($cid);
            $tags['CATEGORY_NAME'] = $category->getVar('title');
            $tags['CATEGORY_URL']  = WFDOWNLOADS_URL . '/viewcat.php?cid=' . $cid;
            $notification_handler->triggerEvent('global', 0, 'new_file', $tags);
            $notification_handler->triggerEvent('category', $cid, 'new_file', $tags);
            $notification_handler->triggerEvent('file', $lid, 'approve', $tags);
        }
        $message = (!$lid) ? _AM_WFDOWNLOADS_FILE_NEWFILEUPLOAD : _AM_WFDOWNLOADS_FILE_FILEMODIFIEDUPDATE;
        $message = ($lid && !$_POST['was_published'] && $approved) ? _AM_WFDOWNLOADS_FILE_FILEAPPROVED : $message;

        redirect_header($currentFile, 1, $message);
        break;

    case "download.delete" :
        $lid = WfdownloadsRequest::getInt('lid', 0);
        $ok  = WfdownloadsRequest::getBool('ok', false, 'POST');
        if (!$download = $wfdownloads->getHandler('download')->get($lid)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_DOWNLOADNOTFOUND);
            exit();
        }
        $title = $download->getVar('title');
        if ($ok == true) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            $file = $wfdownloads->getConfig('uploaddir') . '/' . $download->getVar('filename');
            if (is_file($file)) {
                @chmod($file, 0777);
                @unlink($file);
            }
            if ($wfdownloads->getHandler('download')->delete($download)) {
                redirect_header($currentFile, 1, sprintf(_AM_WFDOWNLOADS_FILE_FILEWASDELETED, $title));
            } else {
                echo $download->getHtmlErrors();
            }
        } else {
            wfdownloads_xoops_cp_header();
            xoops_confirm(
                array('op' => 'download.delete', 'lid' => $lid, 'ok' => true, 'title' => $title),
                $currentFile,
                _AM_WFDOWNLOADS_FILE_REALLYDELETEDTHIS . "<br /><br>" . $title,
                _DELETE
            );
            xoops_cp_footer();
        }
        break;

    case "vote.delete" :
    case "delVote" :
        $rating = $wfdownloads->getHandler('rating')->get($_GET['rid']);
        if ($wfdownloads->getHandler('rating')->delete($rating, true)) {
            wfdownloads_updateRating(intval($rating->getVar('lid')));
        }
        redirect_header($currentFile, 1, _AM_WFDOWNLOADS_VOTE_VOTEDELETED);
        break;

    // Added Formulize module support (2006/05/04) jpc - start
    case "patch_formulize" :
        if (wfdownloads_checkModule('formulize')) {
            if (!isset($_POST['patch_formulize'])) {
                print "<form action=\"{$currentFile}?op=patch_formulize\" method=post>";
                print "<input type = submit name=patch_formulize value=\"Apply Patch for Formulize\">";
                print "</form>";
            } else {
                global $xoopsDB;
                $sqls[] = "ALTER TABLE " . $xoopsDB->prefix("wfdownloads_cat") . " ADD formulize_fid int(5) NOT NULL default '0';";
                $sqls[] = "ALTER TABLE " . $xoopsDB->prefix("wfdownloads_downloads") . " ADD formulize_idreq int(5) NOT NULL default '0';";
                foreach ($sqls as $sql) {
                    if (!$result = $xoopsDB->queryF($sql)) {
                        exit("Error patching for Formulize.<br>SQL dump:<br>" . $sql
                            . "<br>Please contact <a href=support@freeformsolutions.ca>Freeform Solutions</a> for assistance.");
                    }
                }
                print "Patching for Formulize completed.";
            }
        }
        break;
    // Added Formulize module support (2006/05/04) jpc - end

    case "newdownload.approve" :
    case "approve" :
        $lid = WfdownloadsRequest::getInt('lid', 0);
        if (!$download = $wfdownloads->getHandler('download')->get($lid)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_DOWNLOADNOTFOUND);
            exit();
        }
        // Update the database
        $download->setVar('published', time());
        $download->setVar('status', _WFDOWNLOADS_STATUS_APPROVED);
        if (!$wfdownloads->getHandler('download')->insert($download, true)) {
            echo $download->getHtmlErrors();
            exit();
        }
        // Trigger notify
        $title                 = $download->getVar('title');
        $cid                   = $download->getVar('cid');
        $category              = $wfdownloads->getHandler('category')->get($cid);
        $tags                  = array();
        $tags['FILE_NAME']     = $title;
        $tags['FILE_URL']      = WFDOWNLOADS_URL . "/singlefile.php?cid={$cid}&amp;lid={$lid}";
        $tags['CATEGORY_NAME'] = $category->getVar('title');
        $tags['CATEGORY_URL']  = WFDOWNLOADS_URL . "/viewcat.php?cid={$cid}";
        $notification_handler->triggerEvent('global', 0, 'new_file', $tags);
        $notification_handler->triggerEvent('category', $cid, 'new_file', $tags);
        if ($download->getVar('notifypub')) {
            $notification_handler->triggerEvent('file', $lid, 'approve', $tags);
        }
        redirect_header($currentFile, 1, _AM_WFDOWNLOADS_SUB_NEWFILECREATED);
        break;

    case "downloads.list" :
    case "downloads.filter" :
    default :
        $filter_title_condition          = WfdownloadsRequest::getString('filter_title_condition', '=');
        $filter_title                    = WfdownloadsRequest::getString('filter_title', '');
        $filter_category_title_condition = WfdownloadsRequest::getString('filter_category_title_condition', '=');
        $filter_category_title           = WfdownloadsRequest::getString('filter_category_title', '');
        $filter_submitter                = WfdownloadsRequest::getArray('filter_submitter', null);
        $filter_date                     = WfdownloadsRequest::getArray('filter_date', null);
        $filter_date_condition           = WfdownloadsRequest::getString('filter_date_condition', '<');

        if ($op == 'downloads.filter') {
            if ($filter_title == '' && $filter_category_title == '' && is_null($filter_submitter)) {
                $op = 'downloads.list';
            }
        }

        include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

        $categories = $wfdownloads->getHandler('category')->getObjects();

        $start_published     = WfdownloadsRequest::getInt('start_published', 0);
        $start_new           = WfdownloadsRequest::getInt('start_new', 0);
        $start_autopublished = WfdownloadsRequest::getInt('start_autopublished', 0);
        $start_expired       = WfdownloadsRequest::getInt('start_expired', 0);
        $start_offline       = WfdownloadsRequest::getInt('start_offline', 0);

        $totalCategoriesCount  = wfdownloads_categoriesCount();
        $categories = $wfdownloads->getHandler('category')->getObjects(null, true, false);

        $totalDownloadsCount = $wfdownloads->getHandler('download')->getCount();

        wfdownloads_xoops_cp_header();
        $indexAdmin = new ModuleAdmin();
        echo $indexAdmin->addNavigation($currentFile);

        $adminMenu = new ModuleAdmin();
        $adminMenu->addItemButton(_AM_WFDOWNLOADS_FFS_DOWNLOADTITLE, $currentFile . "?op=download.add", 'add');
        echo $adminMenu->renderButton();

        if ($totalDownloadsCount > 0) {
            // Published Downloads
            $criteria = new CriteriaCompo();
            if ($op == 'downloads.filter') {
                // Evaluate title criteria
                if ($filter_title != '') {
                    if ($filter_title_condition == 'LIKE') {
                        $criteria->add(new Criteria('title', "%{$filter_title}%", 'LIKE'));
                    } else {
                        $criteria->add(new Criteria('title', $filter_title, '='));
                    }
                }
                // Evaluate cid criteria
                if ($filter_category_title != '') {
                    if ($filter_category_title_condition == 'LIKE') {
                        $cids = $wfdownloads->getHandler('category')->getIds(new Criteria('title', "%{$filter_category_title}%", 'LIKE'));
                        $criteria->add(new Criteria('cid', '(' . implode(',', $cids) . ')', 'IN'));
                    } else {
                        $cids = $wfdownloads->getHandler('category')->getIds(new Criteria('title', $filter_category_title, '='));
                        $criteria->add(new Criteria('cid', '(' . implode(',', $cids) . ')', 'IN'));
                    }
                }
                // Evaluate submitter criteria
                if (!is_null($filter_submitter)) {
                    $criteria->add(new Criteria('submitter', '(' . implode(',', $filter_submitter) . ')', 'IN'));
                }
                // Evaluate date criteria
                if (!empty($filter_date)) {
                    // TODO: IN PROGRESS
                }
            }

            $criteria->setSort('published');
            $criteria->setOrder('DESC');
            $criteria->setStart($start_published);
            $criteria->setLimit($wfdownloads->getConfig('admin_perpage'));
            $publishedDownloads      = $wfdownloads->getHandler('download')->getActiveDownloads($criteria);
            $publishedDownloadsCount = $wfdownloads->getHandler('download')->getActiveCount();
            $GLOBALS['xoopsTpl']->assign('published_downloads_count', $publishedDownloadsCount);

            if ($publishedDownloadsCount > 0) {
                foreach ($publishedDownloads as $publishedDownload) {
                    $publishedDownload_array                        = $publishedDownload->toArray();
                    $publishedDownload_array['title_html']          = $myts->htmlSpecialChars(trim($publishedDownload_array['title']));
                    $publishedDownload_array['category_title']      = $categories[$publishedDownload_array['cid']]['title'];
                    $publishedDownload_array['submitter_uname']     = XoopsUserUtility::getUnameFromId($publishedDownload_array['submitter']);
                    $publishedDownload_array['published_timestamp'] = XoopsLocal::formatTimestamp($publishedDownload_array['published'], 'l');
                    $GLOBALS['xoopsTpl']->append('published_downloads', $publishedDownload_array);
                }
            }

            $pagenav = new XoopsPageNav($publishedDownloadsCount, $wfdownloads->getConfig('admin_perpage'), $start_published, 'start_published');
            $GLOBALS['xoopsTpl']->assign('filter_title', $filter_title);
            $GLOBALS['xoopsTpl']->assign('filter_title_condition', $filter_title_condition);
            $GLOBALS['xoopsTpl']->assign('filter_category_title', $filter_category_title);
            $GLOBALS['xoopsTpl']->assign('filter_category_title_condition', $filter_category_title_condition);
            $submitters = array();
            $downloadsSubmitters_array = $wfdownloads->getHandler('download')->getAll(null, array('submitter'), false, false);
            foreach ($downloadsSubmitters_array as $downloadSubmitters_array) {
                $submitters[$downloadSubmitters_array['submitter']] = XoopsUserUtility::getUnameFromId($downloadSubmitters_array['submitter']);
            }
            asort($submitters);
            $submitter_select = new XoopsFormSelect('', 'filter_submitter', $filter_submitter, (count($submitters) > 5) ? 5 : count($submitters), true);
            foreach ($submitters as $submitter_uid => $submitter_uname) {
                $submitter_select->addOption($submitter_uid, $submitter_uname);
            }
            $GLOBALS['xoopsTpl']->assign('filter_submitter_select', $submitter_select->render());
            $date_select = new XoopsFormDateTime (null, 'filter_date', 15, time(), false);
            $GLOBALS['xoopsTpl']->assign('filter_date_select', $date_select->render());
            $GLOBALS['xoopsTpl']->assign('filter_date_condition', $filter_date_condition);

            // New Downloads
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('published', 0));
            $criteria->setStart($start_new);
            $criteria->setLimit($wfdownloads->getConfig('admin_perpage'));
            $newDownloads      = $wfdownloads->getHandler('download')->getObjects($criteria);
            $newDownloadsCount = $wfdownloads->getHandler('download')->getCount($criteria);
            $GLOBALS['xoopsTpl']->assign('new_downloads_count', $newDownloadsCount);
            if ($newDownloadsCount > 0) {
                foreach ($newDownloads as $newDownload) {
                    $newDownload_array                    = $newDownload->toArray();
                    $newDownload_array['rating']          = number_format($newDownload_array['rating'], 2);
                    $newDownload_array['title_html']      = $myts->htmlSpecialChars($newDownload_array['title']);
                    $newDownload_array['category_title']  = $categories[$newDownload_array['cid']]['title'];
                    $url                                  = urldecode($myts->htmlSpecialChars($newDownload_array['url']));
                    $homepage                             = $myts->htmlSpecialChars($newDownload_array['homepage']);
                    $version                              = $myts->htmlSpecialChars($newDownload_array['version']);
                    $size                                 = $myts->htmlSpecialChars($newDownload_array['size']);
                    $platform                             = $myts->htmlSpecialChars($newDownload_array['platform']);
                    $logourl                              = $myts->htmlSpecialChars($newDownload_array['screenshot']);
                    $newDownload_array['submitter_uname'] = XoopsUserUtility::getUnameFromId($newDownload_array['submitter']);
                    $newDownload_array['date_timestamp']  = XoopsLocal::formatTimestamp($newDownload_array['date'], 'l');
                    $GLOBALS['xoopsTpl']->append('new_downloads', $newDownload_array);
                }
            }
            $pagenav = new XoopsPageNav($newDownloadsCount, $wfdownloads->getConfig('admin_perpage'), $start_new, 'start_new');
            $GLOBALS['xoopsTpl']->assign('new_downloads_pagenav', $pagenav->renderNav());

            // Autopublished Downloads
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('published', time(), ">"));
            $criteria->setSort('published');
            $criteria->setOrder("ASC");
            $criteria->setStart($start_autopublished);
            $criteria->setLimit($wfdownloads->getConfig('admin_perpage'));
            $autopublishedDownloads      = $wfdownloads->getHandler('download')->getObjects($criteria);
            $autopublishedDownloadsCount = $wfdownloads->getHandler('download')->getCount($criteria);
            $GLOBALS['xoopsTpl']->assign('autopublished_downloads_count', $autopublishedDownloadsCount);
            if ($autopublishedDownloadsCount > 0) {
                foreach ($autopublishedDownloads as $autopublishedDownload) {
                    $autopublishedDownload_array                        = $autopublishedDownload->toArray();
                    $autopublishedDownload_array['title_html']          = $myts->htmlSpecialChars(trim($autopublishedDownload_array['title']));
                    $autopublishedDownload_array['category_title']      = $categories[$autopublishedDownload_array['cid']]['title'];
                    $autopublishedDownload_array['submitter_uname']     = XoopsUserUtility::getUnameFromId($autopublishedDownload_array['submitter']);
                    $autopublishedDownload_array['published_timestamp'] = XoopsLocal::formatTimestamp($autopublishedDownload_array['published'], 'l');
                    $GLOBALS['xoopsTpl']->append('autopublished_downloads', $autopublishedDownload_array);
                }
            }
            $pagenav = new XoopsPageNav($autopublishedDownloadsCount, $wfdownloads->getConfig(
                'admin_perpage'
            ), $start_autopublished, 'start_autopublished');
            $GLOBALS['xoopsTpl']->assign('autopublished_downloads_pagenav', $pagenav->renderNav());

            // Expired downloads
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('expired', time(), '<'), 'AND');
            $criteria->add(new Criteria('expired', 0, '<>'), 'AND');
            $criteria->setSort('expired');
            $criteria->setOrder('ASC');
            $criteria->setStart($start_expired);
            $criteria->setLimit($wfdownloads->getConfig('admin_perpage'));
            $expired_downloads     = $wfdownloads->getHandler('download')->getObjects($criteria);
            $expiredDownloadsCount = $wfdownloads->getHandler('download')->getCount($criteria);
            $GLOBALS['xoopsTpl']->assign('expired_downloads_count', $expiredDownloadsCount);
            if ($expiredDownloadsCount > 0) {
                foreach ($expired_downloads as $expired_download) {
                    $expired_download_array                        = $expired_download->toArray();
                    $expired_download_array['title_html']          = $myts->htmlSpecialChars(trim($expired_download_array['title']));
                    $expired_download_array['category_title']      = $categories[$expired_download_array['cid']]['title'];
                    $expired_download_array['submitter_uname']     = XoopsUserUtility::getUnameFromId($expired_download_array['submitter']);
                    $expired_download_array['published_timestamp'] = XoopsLocal::formatTimestamp($expired_download_array['published'], 'l');
                    $GLOBALS['xoopsTpl']->append('expired_downloads', $expired_download_array);
                }
            }
            $pagenav = new XoopsPageNav($expiredDownloadsCount, $wfdownloads->getConfig('admin_perpage'), $start_expired, 'start_expired');
            $GLOBALS['xoopsTpl']->assign('expired_downloads_pagenav', $pagenav->renderNav());

            // Offline downloads
            $criteria = new Criteria('offline', true);
            $criteria->setSort('published');
            $criteria->setOrder('ASC');
            $criteria->setStart($start_offline);
            $criteria->setLimit($wfdownloads->getConfig('admin_perpage'));
            $offlineDownloadsCount = $wfdownloads->getHandler('download')->getCount($criteria);
            $offlineDownloads      = $wfdownloads->getHandler('download')->getObjects($criteria);
            $offlineDownloadsCount = $wfdownloads->getHandler('download')->getCount($criteria);
            $GLOBALS['xoopsTpl']->assign('offline_downloads_count', $offlineDownloadsCount);
            if ($offlineDownloadsCount > 0) {
                foreach ($offlineDownloads as $offlineDownload) {
                    $offlineDownload_array                        = $offlineDownload->toArray();
                    $offlineDownload_array['title_html']          = $myts->htmlSpecialChars(trim($offlineDownload_array['title']));
                    $offlineDownload_array['category_title']      = $categories[$offlineDownload_array['cid']]['title'];
                    $offlineDownload_array['submitter_uname']     = XoopsUserUtility::getUnameFromId($offlineDownload_array['submitter']);
                    $offlineDownload_array['published_timestamp'] = XoopsLocal::formatTimestamp($offlineDownload_array['published'], 'l');

                    $GLOBALS['xoopsTpl']->append('offline_downloads', $offlineDownload_array);
                }
            }
            $pagenav = new XoopsPageNav($offlineDownloadsCount, $wfdownloads->getConfig('admin_perpage'), $start_offline, 'start_offline');
            $GLOBALS['xoopsTpl']->assign('offline_downloads_pagenav', $pagenav->renderNav());
        } else {
            // NOP
        }

        // Batch files
        $extensionToMime = include $GLOBALS['xoops']->path('include/mimetypes.inc.php');
        $batchPath = $wfdownloads->getConfig('batchdir') . '/';
        $GLOBALS['xoopsTpl']->assign('batch_path', $batchPath);
        $batchFiles = wfdownloads_getFiles($batchPath);
        $batchFilesCount = count($batchFiles);
        $GLOBALS['xoopsTpl']->assign('batch_files_count', $batchFilesCount);
        if ($batchFilesCount > 0) {
            foreach($batchFiles as $key => $batchFile) {
                $batchFile_array['id'] = $key;
                $batchFile_array['filename'] = $batchFile;
                $batchFile_array['size'] = wfdownloads_bytesToSize1024(filesize($batchPath . $batchFile));
                $batchFile_array['extension'] = pathinfo($batchFile, PATHINFO_EXTENSION);
                $batchFile_array['mimetype'] = $extensionToMime[pathinfo($batchFile, PATHINFO_EXTENSION)];
            }
                $GLOBALS['xoopsTpl']->append('batch_files', $batchFile_array);
        }

        $GLOBALS['xoopsTpl']->display("db:{$wfdownloads->getModule()->dirname()}_admin_downloadslist.tpl");

        include 'admin_footer.php';
        break;

    case "batchfile.add" :
        $batchid = WfdownloadsRequest::getInt('batchid', 0);

        $extensionToMime = include $GLOBALS['xoops']->path('include/mimetypes.inc.php');
        $batchPath = $wfdownloads->getConfig('batchdir');
        $batchFiles = wfdownloads_getFiles($batchPath . '/');

        if (!isset($batchFiles[$batchid]) || !is_file($batchPath . '/' . $batchFiles[$batchid])) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_BATCHFILENOTFOUND);
            exit();
        }
        $batchFile = $batchFiles[$batchid];

        $savedFileName = iconv("UTF-8", "ASCII//TRANSLIT", $batchFile);
        $savedFileName = preg_replace('!\s+!', '_', $savedFileName);
        $savedFileName = preg_replace("/[^a-zA-Z0-9\._-]/", "", $savedFileName);
        $savedFileName = uniqid(time()) . '--' . $savedFileName;

        if (!wfdownloads_copyFile($batchPath . '/' . $batchFile, $wfdownloads->getConfig('uploaddir') . '/' . $savedFileName)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_BATCHFILENOTCOPIED);
        }

        $download = $wfdownloads->getHandler('download')->create();
        $download->setVar('title', $batchFile);
        $download->setVar('filename', $savedFileName);
        $download->setVar('size', filesize($wfdownloads->getConfig('uploaddir') . '/' . $savedFileName));
        $download->setVar('filetype', $extensionToMime[pathinfo($batchFile, PATHINFO_EXTENSION)]);

        $download->setVar('version', 0);
        $download->setVar('status', _WFDOWNLOADS_STATUS_APPROVED); // IN PROGRESS
        $download->setVar('published', time());
        $download->setVar('date', time());
        $download->setVar('ipaddress', $_SERVER['REMOTE_ADDR']);
        $download->setVar('submitter', $xoopsUser->getVar('uid', 'e'));
        $download->setVar('publisher', $xoopsUser->getVar('uid', 'e'));

        if (!$wfdownloads->getHandler('download')->insert($download)) {
            wfdownloads_delFile($wfdownloads->getConfig('uploaddir') . '/' . $savedFileName);
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_BATCHFILENOTADDED);
        }
        $newid = (int)$download->getVar('lid');
        // Delete batch file
        wfdownloads_delFile($batchPath . '/' . $batchFile);
        redirect_header("{$currentFile}?op=download.edit&lid={$newid}", 3, _AM_WFDOWNLOADS_BATCHFILE_MOVEDEDITNOW);
        break;

    case "batchfile.delete" :
        $batchid = WfdownloadsRequest::getInt('batchid', 0);
        $ok  = WfdownloadsRequest::getBool('ok', false, 'POST');

        $batchPath = $wfdownloads->getConfig('batchdir');
        $batchFiles = wfdownloads_getFiles($batchPath);

        if (!isset($batchFiles[$batchid]) || !is_file($batchPath . '/' . $batchFiles[$batchid])) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_BATCHFILENOTFOUND);
            exit();
        }
        $title = $batchFiles[$batchid];
        if ($ok == true) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            $file = $batchPath . '/' . $batchFiles[$batchid];
            wfdownloads_delFile($file);
        } else {
            wfdownloads_xoops_cp_header();
            xoops_confirm(
                array('op' => 'batchfile.delete', 'batchid' => $batchid, 'ok' => true, 'title' => $title),
                $currentFile,
                _AM_WFDOWNLOADS_FILE_REALLYDELETEDTHIS . "<br /><br>" . $title,
                _DELETE
            );
            xoops_cp_footer();
        }
        break;

    case "ip_logs.list" :
        $lid = WfdownloadsRequest::getInt('lid', 0);
        if (!$lid) {
            header('Location index.php');
        }

        wfdownloads_xoops_cp_header();
        $indexAdmin = new ModuleAdmin();
        echo $indexAdmin->addNavigation($currentFile);

        $adminMenu = new ModuleAdmin();
        $adminMenu->addItemButton(_AM_WFDOWNLOADS_FFS_DOWNLOADTITLE, $currentFile . "?op=download.add", 'add');
        echo $adminMenu->renderButton();

        // Get ip logs
        $criteria = new CriteriaCompo();
        if ($lid != 0) {
            $criteria->add(new Criteria('lid', $lid));
        }
        $criteria->setSort('date');
        $criteria->setOrder('DESC');
        $ip_logs       = $wfdownloads->getHandler('ip_log')->getObjects($criteria);
        $ip_logs_count = $wfdownloads->getHandler('ip_log')->getCount($criteria);
        $GLOBALS['xoopsTpl']->assign('ip_logs_count', $ip_logs_count);
        unset($criteria);

        // Get download info
        if ($lid != 0) {
            $download                    = $wfdownloads->getHandler('download')->get($lid);
            $download_array              = $download->toArray();
            $download_array['log_title'] = sprintf(_AM_WFDOWNLOADS_LOG_FOR_LID, $download_array['title']);
            $GLOBALS['xoopsTpl']->assign('download', $download_array);
        }

        // Get all logged users
        $uidArray = array();
        foreach ($ip_logs as $ip_log) {
            if ($ip_log->getVar('uid') != 0 && $ip_log->getVar('uid') != '') {
                $uidArray[] = $ip_log->getVar('uid');
            }
        }
        $criteria = new CriteriaCompo();
        if (!empty($uidArray)) {
            $criteria->add(new Criteria('uid', '(' . implode(', ', $uidArray) . ')', 'IN'));
        }
        $userList = $member_handler->getUserList($criteria);
        if (empty($ip_logs)) {
            // NOP
        } else {
            foreach ($ip_logs as $ip_log) {
                $ip_log_array                   = $ip_log->toArray();
                $ip_log_array['uname']          = XoopsUserUtility::getUnameFromId(
                    $ip_log_array['uid']
                );
                //($ip_log_array['uid'] != 0) ? $userList[$ip_log_array['uid']] : _AM_WFDOWNLOADS_ANONYMOUS;
                $ip_log_array['date_timestamp'] = formatTimestamp($ip_log_array['date']);
                $GLOBALS['xoopsTpl']->append('ip_logs', $ip_log_array);
            }
        }

        $GLOBALS['xoopsTpl']->display("db:{$wfdownloads->getModule()->dirname()}_admin_ip_logslist.tpl");

        include 'admin_footer.php';
        break;
}
