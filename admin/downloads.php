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

$currentFile = basename(__FILE__);
require_once __DIR__ . '/admin_header.php';
xoops_load('XoopsLocal');

$helper       = Wfdownloads\Helper::getInstance();

// Check directories
if (!is_dir($helper->getConfig('uploaddir'))) {
    redirect_header('index.php', 4, _AM_WFDOWNLOADS_ERROR_UPLOADDIRNOTEXISTS);
}
if (!is_dir(XOOPS_ROOT_PATH . '/' . $helper->getConfig('mainimagedir'))) {
    redirect_header('index.php', 4, _AM_WFDOWNLOADS_ERROR_MAINIMAGEDIRNOTEXISTS);
}
if (!is_dir(XOOPS_ROOT_PATH . '/' . $helper->getConfig('screenshots'))) {
    redirect_header('index.php', 4, _AM_WFDOWNLOADS_ERROR_SCREENSHOTSDIRNOTEXISTS);
}
if (!is_dir(XOOPS_ROOT_PATH . '/' . $helper->getConfig('catimage'))) {
    redirect_header('index.php', 4, _AM_WFDOWNLOADS_ERROR_CATIMAGEDIRNOTEXISTS);
}

$op = Request::getString('op', 'downloads.list');
switch ($op) {
    case 'download.edit':
    case 'download.add':
    case 'Download':
        Wfdownloads\Utility::getCpHeader();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation($currentFile);

        //$adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->addItemButton(_MI_WFDOWNLOADS_MENU_DOWNLOADS, "{$currentFile}?op=downloads.list", 'list');
        $adminObject->displayButton('left');

        $lid = Request::getInt('lid', 0);

        $categoriesCount = $helper->getHandler('category')->getCount();
        if ($categoriesCount) {
            // Allowed mimetypes list
            echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WFDOWNLOADS_FILE_ALLOWEDAMIME . "</legend>\n";
            echo "<div style='padding: 8px;'>\n";
            $criteria       = new \Criteria('mime_admin', true);
            $mimetypes      = $helper->getHandler('mimetype')->getList($criteria);
            $allowMimetypes = implode(' | ', $mimetypes);
            echo $allowMimetypes;
            echo "</div>\n";
            echo "</fieldset><br>\n";

            if ($lid) {
                // edit download
                if (!$downloadObj = $helper->getHandler('download')->get($lid)) {
                    redirect_header($currentFile, 4, _AM_WFDOWNLOADS_DOWN_ERROR_FILENOTFOUND);
                }
                $cid = $downloadObj->getVar('cid');
                if (!$categoryObj = $helper->getHandler('category')->get($cid)) {
                    redirect_header($currentFile, 4, _AM_WFDOWNLOADS_DOWN_ERROR_CATEGORYNOTFOUND);
                }
                $title   = preg_replace('/{category}/', $categoryObj->getVar('title'), _AM_WFDOWNLOADS_FILE_EDIT);
                $title12 = preg_replace('/{category}/', $categoryObj->getVar('title'), _AM_WFDOWNLOADS_FFS_1STEP);
                $title22 = preg_replace('/{category}/', $categoryObj->getVar('title'), _AM_WFDOWNLOADS_FFS_EDITDOWNLOADTITLE);
            } else {
                // create download
                $downloadObj = $helper->getHandler('download')->create();
                $cid         = Request::getInt('cid', 0, 'POST');
                $categoryObj = $helper->getHandler('category')->get($cid);
                $downloadObj->setVar('cid', $cid);
                $title   = preg_replace('/{category}/', $categoryObj->getVar('title'), _AM_WFDOWNLOADS_FILE_CREATE);
                $title12 = preg_replace('/{category}/', $categoryObj->getVar('title'), _AM_WFDOWNLOADS_FFS_1STEP);
                $title22 = preg_replace('/{category}/', $categoryObj->getVar('title'), _AM_WFDOWNLOADS_FFS_DOWNLOADTITLE);
            }

            // Formulize module support (2006/05/04) jpc - start
            if (!Wfdownloads\Utility::checkModule('formulize')) {
                // one step form: 1st step
                $sform = $downloadObj->getAdminForm($title);
            } elseif (isset($_POST['submit_category']) && !empty($_POST['submit_category'])) {
                // two steps form: 2nd step
                $fid         = $categoryObj->getVar('formulize_fid');
                $customArray = [];
                if ($fid) {
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
                    $ownerGroups                 = $memberHandler->getGroupsByUser($owner, false);
                    $customArray['owner_groups'] = $ownerGroups;
                }
                $sform = $downloadObj->getAdminForm($title22, $customArray);
            } else {
                // two steps form: 1st step
                $sform = $downloadObj->getCategoryForm($title12);
            }
            // Formulize module support (2006/05/04) jpc - end
            $sform->display();
        } else {
            redirect_header('categories.php', 1, _AM_WFDOWNLOADS_CCATEGORY_NOEXISTS);
        }

        // Vote data list/manager
        if ($lid) {
            $ratingCount = $helper->getHandler('rating')->getCount();

            $regUserCriteria = new \CriteriaCompo(new \Criteria('lid', $lid));
            $regUserCriteria->add(new \Criteria('ratinguser', 0, '>'));
            $regUserRatingCount = $helper->getHandler('rating')->getCount($regUserCriteria);
            $regUserCriteria->setSort('ratingtimestamp');
            $regUserCriteria->setOrder('DESC');
            $regUserRatingObjs = $helper->getHandler('rating')->getObjects($regUserCriteria);

            $anonUserCriteria = new \CriteriaCompo(new \Criteria('lid', $lid));
            $anonUserCriteria->add(new \Criteria('ratinguser', 0, '='));
            $anonUserRatingCount = $helper->getHandler('rating')->getCount($anonUserCriteria);
            $anonUserCriteria->setSort('ratingtimestamp');
            $anonUserCriteria->setOrder('DESC');

            echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WFDOWNLOADS_VOTE_RATINGINFOMATION . "</legend>\n";
            echo "<div style='padding: 8px;'><b>" . _AM_WFDOWNLOADS_VOTE_TOTALVOTES . "</b>{$ratingCount}<br><br>\n";

            printf(_AM_WFDOWNLOADS_VOTE_REGUSERVOTES, $regUserRatingCount);

            echo '<br>';

            printf(_AM_WFDOWNLOADS_VOTE_ANONUSERVOTES, $anonUserRatingCount);

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

            if (0 == $regUserRatingCount) {
                echo "<tr><td colspan='7' class='even'><b>" . _AM_WFDOWNLOADS_VOTE_NOREGVOTES . '</b></td></tr>';
            } else {
                foreach ($regUserRatingObjs as $regUserRatingObj) {
                    $uids[] = $regUserRatingObj->getVar('ratinguser');
                }

                $criteria = new \Criteria('ratinguser', '(' . implode(',', $uids) . ')', 'IN');
                $criteria->setGroupBy('ratinguser');
                $userRatings = $helper->getHandler('rating')->getUserAverage($criteria);

                foreach ($regUserRatingObjs as $regUserRatingObj) {
                    $formatted_date = formatTimestamp($regUserRatingObj->getVar('ratingtimestamp'), 'l');
                    $userAvgRating  = isset($userRatings[$regUserRatingObj->getVar('ratinguser')]) ? $userRatings[$regUserRatingObj->getVar('ratinguser')]['avg'] : 0;
                    $userVotes      = isset($userRatings[$regUserRatingObj->getVar('ratinguser')]) ? $userRatings[$regUserRatingObj->getVar('ratinguser')]['count'] : 0;
                    $ratingUserName = \XoopsUser::getUnameFromId($regUserRatingObj->getVar('ratinguser'));

                    echo "
                        <tr><td class='head'>$ratingUserName</td>\n
                        <td class='even'>" . $regUserRatingObj->getVar('ratinghostname') . "</th>\n
                        <td class='even'>" . $regUserRatingObj->getVar('rating') . "</th>\n
                        <td class='even'>$userAvgRating</th>\n
                        <td class='even'>$userVotes</th>\n
                        <td class='even'>$formatted_date</th>\n
                        <td class='even'>\n
                        <a href='{$currentFile}?op=vote.delete&amp;lid={$lid}&amp;rid=" . $regUserRatingObj->getVar('ratingid') . "'>" . $imagearray['deleteimg'] . "</a>\n
                        </th></tr>\n
                        ";
                }
            }
            echo "
                </table>\n
                <br>\n
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
            if (0 == $anonUserRatingCount) {
                echo "<tr><td colspan='7' class='even'><b>" . _AM_WFDOWNLOADS_VOTE_NOUNREGVOTES . '</b></td></tr>';
            } else {
                $criteria           = new \Criteria('ratinguser', 0);
                $userRatings        = $helper->getHandler('rating')->getUserAverage($criteria);
                $anonUserRatingObjs = $helper->getHandler('rating')->getObjects($anonUserCriteria);

                foreach (array_keys($anonUserRatingObjs) as $anonUserRatingObj) {
                    $formatted_date = formatTimestamp($anonUserRatingObj->getVar('ratingtimestamp'), 'l');
                    $userAvgRating  = isset($userRatings['avg']) ? $userRatings['avg'] : 0;
                    $userVotes      = isset($userRatings['count']) ? $userRatings['count'] : 0;

                    $ratingUserName = $GLOBALS['xoopsConfig']['anonymous'];

                    echo "
                        <tr><td class='head'>$ratingUserName</td>\n
                        <td class='even'>" . $anonUserRatingObj->getVar('ratinghostname') . "</th>\n
                        <td class='even'>" . $anonUserRatingObj->getVar('rating') . "</th>\n
                        <td class='even'>$userAvgRating</th>\n
                        <td class='even'>$userVotes</th>\n
                        <td class='even'>$formatted_date</th>\n
                        <td class='even'>\n
                        <a href='{$currentFile}?op=vote.delete&amp;lid={$lid}&amp;rid=" . $anonUserRatingObj->getVar('ratingid') . "'>" . $imagearray['deleteimg'] . "</a>\n
                        </th></tr>\n
                        ";
                }
            }
            echo "</table>\n";
            echo "</fieldset>\n";
        }
        require_once __DIR__ . '/admin_footer.php';
        break;

    case 'download.save':
    case 'addDownload':
        $lid    = Request::getInt('lid', 0, 'POST');
        $cid    = Request::getInt('cid', 0, 'POST');
        $status = Request::getInt('status', _WFDOWNLOADS_STATUS_UPDATED, 'POST');

        if ($lid > 0) {
            $thisIsANewRecord = false; /* Added by Lankford on 2007/3/21 */
            $downloadObj      = $helper->getHandler('download')->get($lid);
        } else {
            $thisIsANewRecord = true; /* Added by Lankford on 2007/3/21 */
            $downloadObj      = $helper->getHandler('download')->create();
        }
        // Define URL
        if (empty($_FILES['userfile']['name'])) {
            if ($_POST['url'] && '' != $_POST['url'] && 'http://' !== $_POST['url']) {
                $url      = ('http://' !== $_POST['url']) ? $_POST['url'] : '';
                $filename = '';
                $filetype = '';
                // Get size from form
                $size = (empty($_POST['size']) || !is_numeric($_POST['size'])) ? 0 : (int)$_POST['size'];
            } else {
                $url      = ('http://' !== $_POST['url']) ? $_POST['url'] : '';
                $filename = $_POST['filename'];
                $filetype = $_POST['filetype'];
                $filePath = $helper->getConfig('uploaddir') . '/' . $filename;
                // Get size from filesystem
                $size = @filesize($filePath);
            }
            $title = trim($_POST['title']);
            $downloadObj->setVar('filename', $filename);
            $downloadObj->setVar('filetype', $filetype);
        } else {
            $down  = Wfdownloads\Utility::uploading($_FILES, $helper->getConfig('uploaddir'), '', $currentFile, 0, false, true);
            $url   = ('http://' !== $_POST['url']) ? $_POST['url'] : '';
            $size  = $down['size'];
            $title = $_FILES['userfile']['name'];

            $ext   = rtrim(strrchr($title, '.'), '.');
            $title = str_replace($ext, '', $title);
            $title = (isset($_POST['title_checkbox']) && 1 == $_POST['title_checkbox']) ? $title : trim($_POST['title']);

            $filename = $down['filename'];
            $filetype = $_FILES['userfile']['type'];
            $downloadObj->setVar('filename', $filename);
            $downloadObj->setVar('filetype', $filetype);
        }
        // Get data from form
        $screenshots   = [];
        $screenshots[] = ('blank.png' !== $_POST['screenshot']) ? $_POST['screenshot'] : '';
        $screenshots[] = ('blank.png' !== $_POST['screenshot2']) ? $_POST['screenshot2'] : '';
        $screenshots[] = ('blank.png' !== $_POST['screenshot3']) ? $_POST['screenshot3'] : '';
        $screenshots[] = ('blank.png' !== $_POST['screenshot4']) ? $_POST['screenshot4'] : '';

        if (!empty($_POST['homepage']) || 'http://' !== $_POST['homepage']) {
            $downloadObj->setVar('homepage', trim($_POST['homepage']));
            $downloadObj->setVar('homepagetitle', trim($_POST['homepagetitle']));
        }

        $version = !empty($_POST['version']) ? trim($_POST['version']) : 0;

        /* Added by Lankford on 2007/3/21 */
        // Here, I want to know if:
        //    a) Are they actually changing the value of version, or is it the same?
        //    b) Are they actually modifying the record, or is this a new one?
        //  If both conditions are true, then trigger all three notifications related to modified records.
        if (!$thisIsANewRecord && ($downloadObj->getVar('version') != $version)) {
            // Trigger the three events related to modified files (one for the file, category, and global event categories respectively)
            $tags                  = [];
            $tags['FILE_NAME']     = $title;
            $tags['FILE_URL']      = WFDOWNLOADS_URL . "/singlefile.php?cid={$cid}&amp;lid={$lid}";
            $categoryObj           = $helper->getHandler('category')->get($cid);
            $tags['FILE_VERSION']  = $version;
            $tags['CATEGORY_NAME'] = $categoryObj->getVar('title');
            $tags['CATEGORY_URL']  = WFDOWNLOADS_URL . "/viewcat.php?cid='{$cid}";

            if (_WFDOWNLOADS_AUTOAPPROVE_DOWNLOAD == $helper->getConfig('autoapprove') || _WFDOWNLOADS_AUTOAPPROVE_BOTH == $helper->getConfig('autoapprove')) {
                // Then this change will be automatically approved, so the notification needs to go out.
                $notificationHandler->triggerEvent('global', 0, 'filemodified', $tags);
                $notificationHandler->triggerEvent('category', $cid, 'filemodified', $tags);
                $notificationHandler->triggerEvent('file', $lid, 'filemodified', $tags);
            }
        }
        /* End add block */
        $downloadObj->setVar('version', $version);
        $downloadObj->setVar('url', $url);
        $downloadObj->setVar('cid', $cid);
        $downloadObj->setVar('title', $title);
        $downloadObj->setVar('status', $status);
        $downloadObj->setVar('size', $size);
        $downloadObj->setVar('screenshot', $screenshots[0]); // old style
        $downloadObj->setVar('screenshot2', $screenshots[1]); // old style
        $downloadObj->setVar('screenshot3', $screenshots[2]); // old style
        $downloadObj->setVar('screenshot4', $screenshots[3]); // old style
        $downloadObj->setVar('screenshots', $screenshots); // new style
        $downloadObj->setVar('platform', trim($_POST['platform']));
        $downloadObj->setVar('summary', trim($_POST['summary']));
        $downloadObj->setVar('description', trim($_POST['description']));
        $downloadObj->setVar('dohtml', isset($_POST['dohtml']));
        $downloadObj->setVar('dosmiley', isset($_POST['dosmiley']));
        $downloadObj->setVar('doxcode', isset($_POST['doxcode']));
        $downloadObj->setVar('doimage', isset($_POST['doimage']));
        $downloadObj->setVar('dobr', isset($_POST['dobr']));
        $downloadObj->setVar('submitter', trim($_POST['submitter']));
        $downloadObj->setVar('publisher', trim($_POST['publisher']));
        $downloadObj->setVar('price', trim($_POST['price']));
        if (!$helper->getConfig('enable_mirrors')) {
            $downloadObj->setVar('mirror', formatURL(trim($_POST['mirror'])));
        }
        $downloadObj->setVar('license', trim($_POST['license']));
        $downloadObj->setVar('features', trim($_POST['features']));
        $downloadObj->setVar('requirements', trim($_POST['requirements']));
        $limitations = \Xmf\Request::getString('limitations', '', 'POST');
        $downloadObj->setVar('limitations', $limitations);
        $versiontypes = \Xmf\Request::getString('versiontypes', '', 'POST');
        $downloadObj->setVar('versiontypes', $versiontypes);

        $dhistory        = \Xmf\Request::getString('dhistory', '', 'POST');
        $dhistoryhistory = \Xmf\Request::getString('dhistoryaddedd', '', 'POST');

        if ($lid > 0 && !empty($dhistoryhistory)) {
            $dhistory .= "\n\n";
            $time     = time();
            $dhistory .= _AM_WFDOWNLOADS_FILE_HISTORYVERS . $version . _AM_WFDOWNLOADS_FILE_HISTORDATE . XoopsLocal::formatTimestamp($time, 'l') . "\n\n";
            $dhistory .= $dhistoryhistory;
        }
        $downloadObj->setVar('dhistory', $dhistory);
        $downloadObj->setVar('dhistoryhistory', $dhistoryhistory);

        $updated = (isset($_POST['was_published']) && 0 == $_POST['was_published']) ? 0 : time();

        if (0 == $_POST['up_dated']) {
            $updated = 0;
        }
        $downloadObj->setVar('updated', $updated);

        $offline = (true === $_POST['offline']) ? true : false;
        $downloadObj->setVar('offline', $offline);
        $approved  = (isset($_POST['approved']) && true === $_POST['approved']) ? true : false;
        $notifypub = (isset($_POST['notifypub']) && true === $_POST['notifypub']);

        $expiredate = 0;
        if (!$lid) {
            $publishdate = time();
        } else {
            $publishdate = $_POST['was_published'];
            $expiredate  = $_POST['was_expired'];
        }
        if (1 == $approved && empty($publishdate)) {
            $publishdate = time();
        }
        if (isset($_POST['publishdateactivate'])) {
            $publishdate = strtotime($_POST['published']['date']) + $_POST['published']['time'];
        }
        if ($_POST['clearpublish']) {
            $publishdate = $downloadObj->getVar('published');
        }
        if (isset($_POST['expiredateactivate'])) {
            $expiredate = strtotime($_POST['expired']['date']) + $_POST['expired']['time'];
        }
        if ($_POST['clearexpire']) {
            $expiredate = '0';
        }

        $downloadObj->setVar('expired', $expiredate);
        $downloadObj->setVar('published', $publishdate);
        $downloadObj->setVar('date', time());
        // Update or insert download data into database
        if (!$lid) {
            $downloadObj->setVar('ipaddress', $_SERVER['REMOTE_ADDR']);
        }

        $categoryObj = $helper->getHandler('category')->get($cid);

        // Formulize module support (2006/05/04) jpc - start
        if (Wfdownloads\Utility::checkModule('formulize')) {
            $fid = $categoryObj->getVar('formulize_fid');
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
                        print 'no idreq';
                        $entries[$fid][0] = '';
                        $owner            = '';
                    }
                    $cid = $downloadObj->getVar('cid');
                } else {
                    $entries[$fid][0] = '';
                    $owner            = '';
                }
                $ownerGroups = $memberHandler->getGroupsByUser($owner, false);
                $uid         = !empty($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getVar('uid') : 0;
                $groups      = $GLOBALS['xoopsUser'] ? $GLOBALS['xoopsUser']->getGroups() : [0 => XOOPS_GROUP_ANONYMOUS];
                $entries     = handleSubmission($formulizeElementsHandler, $entries, $uid, $owner, $fid, $ownerGroups, $groups, 'new'); // "new" causes xoops token check to be skipped, since Wfdownloads should be doing that
                if (!$owner) {
                    $id_req = $entries[$fid][0];
                    $downloadObj->setVar('formulize_idreq', $id_req);
                }
            }
        }
        // Formulize module support (2006/05/04) jpc - end
        $helper->getHandler('download')->insert($downloadObj);
        $newid = (int)$downloadObj->getVar('lid');
        // Send notifications
        if (!$lid) {
            $tags                  = [];
            $tags['FILE_NAME']     = $title;
            $tags['FILE_URL']      = WFDOWNLOADS_URL . "/singlefile.php?cid={$cid}&amp;lid={$newid}";
            $tags['CATEGORY_NAME'] = $categoryObj->getVar('title');
            $tags['CATEGORY_URL']  = WFDOWNLOADS_URL . "/viewcat.php?cid={$cid}";
            $notificationHandler->triggerEvent('global', 0, 'new_file', $tags);
            $notificationHandler->triggerEvent('category', $cid, 'new_file', $tags);
        }
        if ($lid && $approved && $notifypub) {
            $tags                  = [];
            $tags['FILE_NAME']     = $title;
            $tags['FILE_URL']      = WFDOWNLOADS_URL . "/singlefile.php?cid={$cid}&amp;lid={$lid}";
            $categoryObj           = $helper->getHandler('category')->get($cid);
            $tags['CATEGORY_NAME'] = $categoryObj->getVar('title');
            $tags['CATEGORY_URL']  = WFDOWNLOADS_URL . '/viewcat.php?cid=' . $cid;
            $notificationHandler->triggerEvent('global', 0, 'new_file', $tags);
            $notificationHandler->triggerEvent('category', $cid, 'new_file', $tags);
            $notificationHandler->triggerEvent('file', $lid, 'approve', $tags);
        }
        $message = (!$lid) ? _AM_WFDOWNLOADS_FILE_NEWFILEUPLOAD : _AM_WFDOWNLOADS_FILE_FILEMODIFIEDUPDATE;
        $message = ($lid && !$_POST['was_published'] && $approved) ? _AM_WFDOWNLOADS_FILE_FILEAPPROVED : $message;

        redirect_header($currentFile, 1, $message);
        break;

    case 'download.delete':
        $lid = Request::getInt('lid', 0);
        $ok  = Request::getBool('ok', false, 'POST');
        if (!$downloadObj = $helper->getHandler('download')->get($lid)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_DOWNLOADNOTFOUND);
        }
        $title = $downloadObj->getVar('title');
        if (true === $ok) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            $file = $helper->getConfig('uploaddir') . '/' . $downloadObj->getVar('filename');
            if (is_file($file)) {
                @chmod($file, 0777);
                @unlink($file);
            }
            if ($helper->getHandler('download')->delete($downloadObj)) {
                redirect_header($currentFile, 1, sprintf(_AM_WFDOWNLOADS_FILE_FILEWASDELETED, $title));
            } else {
                echo $downloadObj->getHtmlErrors();
            }
        } else {
            Wfdownloads\Utility::getCpHeader();
            xoops_confirm(['op' => 'download.delete', 'lid' => $lid, 'ok' => true, 'title' => $title], $currentFile, _AM_WFDOWNLOADS_FILE_REALLYDELETEDTHIS . '<br><br>' . $title, _DELETE);
            xoops_cp_footer();
        }
        break;

    case 'vote.delete':
    case 'delVote':
        $ratingObj = $helper->getHandler('rating')->get($_GET['rid']);
        if ($helper->getHandler('rating')->delete($ratingObj, true)) {
            Wfdownloads\Utility::updateRating((int)$ratingObj->getVar('lid'));
        }
        redirect_header($currentFile, 1, _AM_WFDOWNLOADS_VOTE_VOTEDELETED);
        break;

    // Formulize module support (2006/05/04) jpc - start
    case 'patch_formulize':
        if (Wfdownloads\Utility::checkModule('formulize')) {
            if (!isset($_POST['patch_formulize'])) {
                print "<form action=\"{$currentFile}?op=patch_formulize\" method=post>";
                print '<input type = submit name=patch_formulize value="Apply Patch for Formulize">';
                print '</form>';
            } else {
                $sqls[] = 'ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('wfdownloads_cat') . " ADD formulize_fid INT(5) NOT NULL DEFAULT '0';";
                $sqls[] = 'ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('wfdownloads_downloads') . " ADD formulize_idreq INT(5) NOT NULL DEFAULT '0';";
                foreach ($sqls as $sql) {
                    if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
                        exit('Error patching for Formulize.<br>SQL dump:<br>' . $sql . '<br>Please contact <a href=support@freeformsolutions.ca>Freeform Solutions</a> for assistance.');
                    }
                }
                print 'Patching for Formulize completed.';
            }
        }
        break;
    // Formulize module support (2006/05/04) jpc - end

    case 'newdownload.approve':
    case 'approve':
        $lid = Request::getInt('lid', 0);
        if (!$downloadObj = $helper->getHandler('download')->get($lid)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_DOWNLOADNOTFOUND);
        }
        // Update the database
        $downloadObj->setVar('published', time());
        $downloadObj->setVar('status', _WFDOWNLOADS_STATUS_APPROVED);
        if (!$helper->getHandler('download')->insert($downloadObj, true)) {
            echo $downloadObj->getHtmlErrors();
            exit();
        }
        // Trigger notify
        $title                 = $downloadObj->getVar('title');
        $cid                   = $downloadObj->getVar('cid');
        $categoryObj           = $helper->getHandler('category')->get($cid);
        $tags                  = [];
        $tags['FILE_NAME']     = $title;
        $tags['FILE_URL']      = WFDOWNLOADS_URL . "/singlefile.php?cid={$cid}&amp;lid={$lid}";
        $tags['CATEGORY_NAME'] = $categoryObj->getVar('title');
        $tags['CATEGORY_URL']  = WFDOWNLOADS_URL . "/viewcat.php?cid={$cid}";
        $notificationHandler->triggerEvent('global', 0, 'new_file', $tags);
        $notificationHandler->triggerEvent('category', $cid, 'new_file', $tags);
        if ($downloadObj->getVar('notifypub')) {
            $notificationHandler->triggerEvent('file', $lid, 'approve', $tags);
        }
        redirect_header($currentFile, 1, _AM_WFDOWNLOADS_SUB_NEWFILECREATED);
        break;

    case 'downloads.list':
    case 'downloads.filter':
    default:
        // get filter conditions
        $filter_title_condition          = Request::getString('filter_title_condition', '=');
        $filter_title                    = Request::getString('filter_title', '');
        $filter_category_title_condition = Request::getString('filter_category_title_condition', '=');
        $filter_category_title           = Request::getString('filter_category_title', '');
        $filter_submitter                = Request::getArray('filter_submitter', null);
        $filter_date                     = Request::getArray('filter_date', null);
        $filter_date_condition           = Request::getString('filter_date_condition', '<');
        // check filter conditions
        if ('downloads.filter' === $op) {
            if ('' == $filter_title && '' == $filter_category_title && null === $filter_submitter) {
                $op = 'downloads.list';
            }
        }

        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';

        $categoryObjs = $helper->getHandler('category')->getObjects();

        $start_published     = Request::getInt('start_published', 0);
        $start_new           = Request::getInt('start_new', 0);
        $start_autopublished = Request::getInt('start_autopublished', 0);
        $start_expired       = Request::getInt('start_expired', 0);
        $start_offline       = Request::getInt('start_offline', 0);

        $totalCategoriesCount = Wfdownloads\Utility::categoriesCount();
        $categoryObjs         = $helper->getHandler('category')->getObjects(null, true, false);

        $totalDownloadsCount = $helper->getHandler('download')->getCount();
//    $totalDownloadsCount = $downloadHandler->getCount();

        Wfdownloads\Utility::getCpHeader();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation($currentFile);

        //$adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->addItemButton(_AM_WFDOWNLOADS_FILE_CREATE, $currentFile . '?op=download.add', 'add');
        $adminObject->displayButton('left');

        if ($totalDownloadsCount > 0) {
            // Published Downloads
            $criteria = new \CriteriaCompo();
            if ('downloads.filter' === $op) {
                // Evaluate title criteria
                if ('' != $filter_title) {
                    if ('LIKE' === $filter_title_condition) {
                        $criteria->add(new \Criteria('title', "%{$filter_title}%", 'LIKE'));
                    } else {
                        $criteria->add(new \Criteria('title', $filter_title, '='));
                    }
                }
                // Evaluate cid criteria
                if ('' != $filter_category_title) {
                    if ('LIKE' === $filter_category_title_condition) {
                        $cids = $helper->getHandler('category')->getIds(new \Criteria('title', "%{$filter_category_title}%", 'LIKE'));
                        $criteria->add(new \Criteria('cid', '(' . implode(',', $cids) . ')', 'IN'));
                    } else {
                        $cids = $helper->getHandler('category')->getIds(new \Criteria('title', $filter_category_title, '='));
                        $criteria->add(new \Criteria('cid', '(' . implode(',', $cids) . ')', 'IN'));
                    }
                }
                // Evaluate submitter criteria
                if (!null === $filter_submitter) {
                    $criteria->add(new \Criteria('submitter', '(' . implode(',', $filter_submitter) . ')', 'IN'));
                }
                // Evaluate date criteria
                if (!empty($filter_date)) {
                    // TODO: IN PROGRESS
                }
            }

            $criteria->setSort('published');
            $criteria->setOrder('DESC');
            $criteria->setStart($start_published);
            $criteria->setLimit($helper->getConfig('admin_perpage'));
            $publishedDownloadObjs  = $helper->getHandler('download')->getActiveDownloads($criteria);
            $publishedDownloadCount = $helper->getHandler('download')->getActiveCount();
            $GLOBALS['xoopsTpl']->assign('published_downloads_count', $publishedDownloadCount);

            if ($publishedDownloadCount > 0) {
                foreach ($publishedDownloadObjs as $publishedDownloadObj) {
                    $publishedDownload_array                        = $publishedDownloadObj->toArray();
                    $publishedDownload_array['title_html']          = $myts->htmlSpecialChars(trim($publishedDownload_array['title']));
                    $publishedDownload_array['category_title']      = $categoryObjs[$publishedDownload_array['cid']]['title'];
                    $publishedDownload_array['submitter_uname']     = \XoopsUserUtility::getUnameFromId($publishedDownload_array['submitter']);
                    $publishedDownload_array['published_formatted'] = formatTimestamp($publishedDownload_array['published'], 'l');
                    $GLOBALS['xoopsTpl']->append('published_downloads', $publishedDownload_array);
                }
            }

            $pagenav = new \XoopsPageNav($publishedDownloadCount, $helper->getConfig('admin_perpage'), $start_published, 'start_published');
            $GLOBALS['xoopsTpl']->assign('filter_title', $filter_title);
            $GLOBALS['xoopsTpl']->assign('filter_title_condition', $filter_title_condition);
            $GLOBALS['xoopsTpl']->assign('filter_category_title', $filter_category_title);
            $GLOBALS['xoopsTpl']->assign('filter_category_title_condition', $filter_category_title_condition);
            $submitters                = [];
            $downloadsSubmitters_array = $helper->getHandler('download')->getAll(null, ['submitter'], false, false);
            foreach ($downloadsSubmitters_array as $downloadSubmitters_array) {
                $submitters[$downloadSubmitters_array['submitter']] = \XoopsUserUtility::getUnameFromId($downloadSubmitters_array['submitter']);
            }
            asort($submitters);
            $submitter_select = new \XoopsFormSelect('', 'filter_submitter', $filter_submitter, (count($submitters) > 5) ? 5 : count($submitters), true);
            foreach ($submitters as $submitter_uid => $submitter_uname) {
                $submitter_select->addOption($submitter_uid, $submitter_uname);
            }
            $GLOBALS['xoopsTpl']->assign('filter_submitter_select', $submitter_select->render());
            $date_select = new \XoopsFormDateTime(null, 'filter_date', 15, time(), false);
            $GLOBALS['xoopsTpl']->assign('filter_date_select', $date_select->render());
            $GLOBALS['xoopsTpl']->assign('filter_date_condition', $filter_date_condition);

            // New Downloads
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('published', 0));
            $criteria->setStart($start_new);
            $criteria->setLimit($helper->getConfig('admin_perpage'));
            $newDownloadObjs  = $helper->getHandler('download')->getObjects($criteria);
            $newDownloadCount = $helper->getHandler('download')->getCount($criteria);
            $GLOBALS['xoopsTpl']->assign('new_downloads_count', $newDownloadCount);
            if ($newDownloadCount > 0) {
                foreach ($newDownloadObjs as $newDownloadObj) {
                    $newDownload_array                   = $newDownloadObj->toArray();
                    $newDownload_array['rating']         = number_format($newDownload_array['rating'], 2);
                    $newDownload_array['title_html']     = $myts->htmlSpecialChars($newDownload_array['title']);
                    $newDownload_array['category_title'] = $categories[$newDownload_array['cid']]['title'];
                    /*
                                        $url                                  = urldecode($myts->htmlSpecialChars($newDownload_array['url']));
                                        $homepage                             = $myts->htmlSpecialChars($newDownload_array['homepage']);
                                        $version                              = $myts->htmlSpecialChars($newDownload_array['version']);
                                        $size                                 = $myts->htmlSpecialChars($newDownload_array['size']);
                                        $platform                             = $myts->htmlSpecialChars($newDownload_array['platform']);
                                        $logourl                              = $myts->htmlSpecialChars($newDownload_array['screenshot']); // IN PROGRESS
                    */
                    $newDownload_array['submitter_uname'] = \XoopsUserUtility::getUnameFromId($newDownload_array['submitter']);
                    $newDownload_array['date_formatted']  = formatTimestamp($newDownload_array['date'], 'l');
                    $GLOBALS['xoopsTpl']->append('new_downloads', $newDownload_array);
                }
            }
            $pagenav = new \XoopsPageNav($newDownloadCount, $helper->getConfig('admin_perpage'), $start_new, 'start_new');
            $GLOBALS['xoopsTpl']->assign('new_downloads_pagenav', $pagenav->renderNav());

            // Autopublished Downloads
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('published', time(), '>'));
            $criteria->setSort('published');
            $criteria->setOrder('ASC');
            $criteria->setStart($start_autopublished);
            $criteria->setLimit($helper->getConfig('admin_perpage'));
            $autopublishedDownloadObjs  = $helper->getHandler('download')->getObjects($criteria);
            $autopublishedDownloadCount = $helper->getHandler('download')->getCount($criteria);
            $GLOBALS['xoopsTpl']->assign('autopublished_downloads_count', $autopublishedDownloadCount);
            if ($autopublishedDownloadCount > 0) {
                foreach ($autopublishedDownloadObjs as $autopublishedDownloadObj) {
                    $autopublishedDownload_array                        = $autopublishedDownloadObj->toArray();
                    $autopublishedDownload_array['title_html']          = $myts->htmlSpecialChars(trim($autopublishedDownload_array['title']));
                    $autopublishedDownload_array['category_title']      = $categories[$autopublishedDownload_array['cid']]['title'];
                    $autopublishedDownload_array['submitter_uname']     = \XoopsUserUtility::getUnameFromId($autopublishedDownload_array['submitter']);
                    $autopublishedDownload_array['published_formatted'] = formatTimestamp($autopublishedDownload_array['published'], 'l');
                    $GLOBALS['xoopsTpl']->append('autopublished_downloads', $autopublishedDownload_array);
                }
            }
            $pagenav = new \XoopsPageNav($autopublishedDownloadCount, $helper->getConfig('admin_perpage'), $start_autopublished, 'start_autopublished');
            $GLOBALS['xoopsTpl']->assign('autopublished_downloads_pagenav', $pagenav->renderNav());

            // Expired downloads
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('expired', time(), '<'), 'AND');
            $criteria->add(new \Criteria('expired', 0, '<>'), 'AND');
            $criteria->setSort('expired');
            $criteria->setOrder('ASC');
            $criteria->setStart($start_expired);
            $criteria->setLimit($helper->getConfig('admin_perpage'));
            $expiredDownloadObjs  = $helper->getHandler('download')->getObjects($criteria);
            $expiredDownloadCount = $helper->getHandler('download')->getCount($criteria);
            $GLOBALS['xoopsTpl']->assign('expired_downloads_count', $expiredDownloadCount);
            if ($expiredDownloadCount > 0) {
                foreach ($expiredDownloadObjs as $expiredDownloadObj) {
                    $expiredDownload_array                        = $expiredDownloadObj->toArray();
                    $expiredDownload_array['title_html']          = $myts->htmlSpecialChars(trim($expiredDownload_array['title']));
                    $expiredDownload_array['category_title']      = $categories[$expiredDownload_array['cid']]['title'];
                    $expiredDownload_array['submitter_uname']     = \XoopsUserUtility::getUnameFromId($expiredDownload_array['submitter']);
                    $expiredDownload_array['published_formatted'] = formatTimestamp($expiredDownload_array['published'], 'l');
                    $GLOBALS['xoopsTpl']->append('expired_downloads', $expiredDownload_array);
                }
            }
            $pagenav = new \XoopsPageNav($expiredDownloadCount, $helper->getConfig('admin_perpage'), $start_expired, 'start_expired');
            $GLOBALS['xoopsTpl']->assign('expired_downloads_pagenav', $pagenav->renderNav());

            // Offline downloads
            $criteria = new \Criteria('offline', true);
            $criteria->setSort('published');
            $criteria->setOrder('ASC');
            $criteria->setStart($start_offline);
            $criteria->setLimit($helper->getConfig('admin_perpage'));
            $offlineDownloadObjs  = $helper->getHandler('download')->getObjects($criteria);
            $offlineDownloadCount = $helper->getHandler('download')->getCount($criteria);
            $GLOBALS['xoopsTpl']->assign('offline_downloads_count', $offlineDownloadCount);
            if ($offlineDownloadCount > 0) {
                foreach ($offlineDownloadObjs as $offlineDownloadObj) {
                    $offlineDownload_array                        = $offlineDownloadObj->toArray();
                    $offlineDownload_array['title_html']          = $myts->htmlSpecialChars(trim($offlineDownload_array['title']));
                    $offlineDownload_array['category_title']      = $categories[$offlineDownload_array['cid']]['title'];
                    $offlineDownload_array['submitter_uname']     = \XoopsUserUtility::getUnameFromId($offlineDownload_array['submitter']);
                    $offlineDownload_array['published_formatted'] = formatTimestamp($offlineDownload_array['published'], 'l');
                    $GLOBALS['xoopsTpl']->append('offline_downloads', $offlineDownload_array);
                }
            }
            $pagenav = new \XoopsPageNav($offlineDownloadCount, $helper->getConfig('admin_perpage'), $start_offline, 'start_offline');
            $GLOBALS['xoopsTpl']->assign('offline_downloads_pagenav', $pagenav->renderNav());
        } else {
            // NOP
        }

        // Batch files
        $extensionToMime = include $GLOBALS['xoops']->path('include/mimetypes.inc.php');
        $batchPath       = $helper->getConfig('batchdir');
        $GLOBALS['xoopsTpl']->assign('batch_path', $batchPath);
        $batchFiles      = Wfdownloads\Utility::getFiles($batchPath . '/');
        $batchFilesCount = count($batchFiles);
        $GLOBALS['xoopsTpl']->assign('batch_files_count', $batchFilesCount);
        if ($batchFilesCount > 0) {
            foreach ($batchFiles as $key => $batchFile) {
                $batchFile_array              = [];
                $batchFile_array['id']        = $key;
                $batchFile_array['filename']  = $batchFile;
                $batchFile_array['size']      = Wfdownloads\Utility::bytesToSize1024(filesize($batchPath . '/' . $batchFile));
                $batchFile_array['extension'] = pathinfo($batchFile, PATHINFO_EXTENSION);
                $batchFile_array['mimetype']  = $extensionToMime[pathinfo($batchFile, PATHINFO_EXTENSION)];
                $GLOBALS['xoopsTpl']->append('batch_files', $batchFile_array);
                unset($batchFile_array);
            }
        }

        $GLOBALS['xoopsTpl']->display("db:{$helper->getModule()->dirname()}_am_downloadslist.tpl");

        require_once __DIR__ . '/admin_footer.php';
        break;

    case 'batchfile.add':
        $batchid = Request::getInt('batchid', 0);

        $extensionToMime = include $GLOBALS['xoops']->path('include/mimetypes.inc.php');
        $batchPath       = $helper->getConfig('batchdir');
        $batchFiles      = Wfdownloads\Utility::getFiles($batchPath . '/');

        if (!isset($batchFiles[$batchid]) || !is_file($batchPath . '/' . $batchFiles[$batchid])) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_BATCHFILENOTFOUND);
        }
        $batchFile = $batchFiles[$batchid];

        $savedFileName = iconv('UTF-8', 'ASCII//TRANSLIT', $batchFile);
        $savedFileName = preg_replace('!\s+!', '_', $savedFileName);
        $savedFileName = preg_replace('/[^a-zA-Z0-9\._-]/', '', $savedFileName);
        $savedFileName = uniqid(time()) . '--' . $savedFileName;

        if (!Wfdownloads\Utility::copyFile($batchPath . '/' . $batchFile, $helper->getConfig('uploaddir') . '/' . $savedFileName)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_BATCHFILENOTCOPIED);
        }

        $downloadObj = $helper->getHandler('download')->create();
        $downloadObj->setVar('title', $batchFile);
        $downloadObj->setVar('filename', $savedFileName);
        $downloadObj->setVar('size', filesize($helper->getConfig('uploaddir') . '/' . $savedFileName));
        $downloadObj->setVar('filetype', $extensionToMime[pathinfo($batchFile, PATHINFO_EXTENSION)]);
        $downloadObj->setVar('version', 0);
        $downloadObj->setVar('status', _WFDOWNLOADS_STATUS_APPROVED); // IN PROGRESS
        $downloadObj->setVar('published', time());
        $downloadObj->setVar('date', time());
        $downloadObj->setVar('ipaddress', $_SERVER['REMOTE_ADDR']);
        $downloadObj->setVar('submitter', $GLOBALS['xoopsUser']->getVar('uid', 'e'));
        $downloadObj->setVar('publisher', $GLOBALS['xoopsUser']->getVar('uid', 'e'));

        if (!$helper->getHandler('download')->insert($downloadObj)) {
            Wfdownloads\Utility::delFile($helper->getConfig('uploaddir') . '/' . $savedFileName);
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_BATCHFILENOTADDED);
        }
        $newid = (int)$downloadObj->getVar('lid');
        // Delete batch file
        Wfdownloads\Utility::delFile($batchPath . '/' . $batchFile);
        redirect_header("{$currentFile}?op=download.edit&lid={$newid}", 3, _AM_WFDOWNLOADS_BATCHFILE_MOVEDEDITNOW);
        break;

    case 'batchfile.delete':
        $batchid = Request::getInt('batchid', 0);
        $ok      = Request::getBool('ok', false, 'POST');

        $batchPath  = $helper->getConfig('batchdir');
        $batchFiles = Wfdownloads\Utility::getFiles($batchPath);

        if (!isset($batchFiles[$batchid]) || !is_file($batchPath . '/' . $batchFiles[$batchid])) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_BATCHFILENOTFOUND);
        }
        $title = $batchFiles[$batchid];
        if (true === $ok) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            $file = $batchPath . '/' . $batchFiles[$batchid];
            Wfdownloads\Utility::delFile($file);
        } else {
            Wfdownloads\Utility::getCpHeader();
            xoops_confirm(['op' => 'batchfile.delete', 'batchid' => $batchid, 'ok' => true, 'title' => $title], $currentFile, _AM_WFDOWNLOADS_FILE_REALLYDELETEDTHIS . '<br><br>' . $title, _DELETE);
            xoops_cp_footer();
        }
        break;

    case 'ip_logs.list':
        $lid = Request::getInt('lid', 0);
        if (!$lid) {
            header('Location index.php');
        }

        Wfdownloads\Utility::getCpHeader();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation($currentFile);

        //$adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->addItemButton(_AM_WFDOWNLOADS_FILE_CREATE, $currentFile . '?op=download.add', 'add');
        $adminObject->displayButton('left');

        // Get ip logs
        $criteria = new \CriteriaCompo();
        if (0 != $lid) {
            $criteria->add(new \Criteria('lid', $lid));
        }
        $criteria->setSort('date');
        $criteria->setOrder('DESC');
        $ip_logObjs  = $helper->getHandler('iplog')->getObjects($criteria);
        $ip_logCount = $helper->getHandler('iplog')->getCount($criteria);
        $GLOBALS['xoopsTpl']->assign('ip_logs_count', $ip_logCount);
        unset($criteria);

        // Get download info
        if (0 != $lid) {
            $downloadObj                 = $helper->getHandler('download')->get($lid);
            $download_array              = $downloadObj->toArray();
            $download_array['log_title'] = sprintf(_AM_WFDOWNLOADS_LOG_FOR_LID, $download_array['title']);
            $GLOBALS['xoopsTpl']->assign('download', $download_array);
        }

        // Get all logged users
        $uidArray = [];
        foreach ($ip_logObjs as $ip_logObj) {
            if (0 != $ip_logObj->getVar('uid') && '' != $ip_logObj->getVar('uid')) {
                $uidArray[] = $ip_logObj->getVar('uid');
            }
        }
        $criteria = new \CriteriaCompo();
        if (!empty($uidArray)) {
            $criteria->add(new \Criteria('uid', '(' . implode(', ', $uidArray) . ')', 'IN'));
        }
        $userList = $memberHandler->getUserList($criteria);
        if (empty($ip_logObjs)) {
            // NOP
        } else {
            foreach ($ip_logObjs as $ip_logObj) {
                $ip_log_array          = $ip_logObj->toArray();
                $ip_log_array['uname'] = \XoopsUserUtility::getUnameFromId($ip_log_array['uid']);
                //($ip_log_array['uid'] != 0) ? $userList[$ip_log_array['uid']] : _AM_WFDOWNLOADS_ANONYMOUS;
                $ip_log_array['date_formatted'] = formatTimestamp($ip_log_array['date']);
                $GLOBALS['xoopsTpl']->append('ip_logs', $ip_log_array);
            }
        }

        $GLOBALS['xoopsTpl']->display("db:{$helper->getModule()->dirname()}_am_ip_logslist.tpl");

        require_once __DIR__ . '/admin_footer.php';
        break;
}
