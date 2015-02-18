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
include_once __DIR__ . '/admin_header.php';

if (!is_dir(XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('mainimagedir'))) {
    redirect_header('index.php', 4, _AM_WFDOWNLOADS_ERROR_MAINIMAGEDIRNOTEXISTS);
    exit();
}
if (!is_dir(XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('screenshots'))) {
    redirect_header('index.php', 4, _AM_WFDOWNLOADS_ERROR_SCREENSHOTSDIRNOTEXISTS);
    exit();
}
if (!is_dir(XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('catimage'))) {
    redirect_header('index.php', 4, _AM_WFDOWNLOADS_ERROR_CATIMAGEDIRNOTEXISTS);
    exit();
}

$op = XoopsRequest::getString('op', 'images.list');
switch ($op) {
    case 'image.upload':
        if ($_FILES['uploadfile']['name'] != '') {
            if (file_exists(XOOPS_ROOT_PATH . '/' . $_POST['uploadpath'] . '/' . $_FILES['uploadfile']['name'])) {
                redirect_header($currentFile, 2, _AM_WFDOWNLOADS_DOWN_IMAGEEXIST);
            }
            $allowedMimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
            $maxFileSize      = $wfdownloads->getConfig('maxfilesize');
            $maxImgWidth      = $wfdownloads->getConfig('maximgwidth');
            $maxImgHeight     = $wfdownloads->getConfig('maximgheight');
            $uploadDir        = XOOPS_ROOT_PATH . '/' . $_POST['uploadpath'];
            $screenShot       = strtolower($_FILES['uploadfile']['name']);
            wfdownloads_uploading($screenShot, XOOPS_ROOT_PATH . '/' . $_POST['uploadpath'], $allowedMimetypes, $currentFile, 1, false, true);

            include_once WFDOWNLOADS_ROOT_PATH . '/class/img_uploader.php';
            $uploader = new XoopsMediaImgUploader($uploadDir . '/', $allowedMimetypes, $maxFileSize, $maxImgWidth, $maxImgHeight);

            redirect_header($currentFile, 2, _AM_WFDOWNLOADS_DOWN_IMAGEUPLOAD);
            exit();
        } else {
            redirect_header($currentFile, 2, _AM_WFDOWNLOADS_DOWN_NOIMAGEEXIST);
            exit();
        }
        break;

    case 'image.delete':
        $ok = XoopsRequest::getBool('ok', false, 'POST');

        if ($ok === true) {
            $fileToDelete = XOOPS_ROOT_PATH . '/' . $_POST['uploadpath'] . '/' . $_POST['downfile'];
            if (file_exists($fileToDelete)) {
                chmod($fileToDelete, 0666);
                if (@unlink($fileToDelete)) {
                    redirect_header($currentFile, 1, _AM_WFDOWNLOADS_DOWN_FILEDELETED);
                } else {
                    redirect_header($currentFile, 1, _AM_WFDOWNLOADS_DOWN_FILEERRORDELETE);
                }
            }
            exit();
        } else {
            if (empty($_POST['downfile'])) {
                redirect_header($currentFile, 1, _AM_WFDOWNLOADS_DOWN_NOFILEERROR);
                exit();
            }
            wfdownloads_xoops_cp_header();
            xoops_confirm(
                array('op' => 'image.delete', 'uploadpath' => $_POST['uploadpath'], 'downfile' => $_POST['downfile'], 'ok' => true),
                $currentFile,
                _AM_WFDOWNLOADS_DOWN_DELETEFILE . "<br /><br />" . $_POST['downfile'],
                _AM_WFDOWNLOADS_BDELETE
            );
            include_once __DIR__ . '/admin_footer.php';
        }
        break;

    case 'images.list':
    default:
        include_once WFDOWNLOADS_ROOT_PATH . '/class/wfdownloads_lists.php';

        $displayImage = '';
        $rootPath     = XoopsRequest::getInt('rootpath', 0);

        wfdownloads_xoops_cp_header();
        $indexAdmin = new ModuleAdmin();
        echo $indexAdmin->addNavigation($currentFile);

        $dirArray  = array(
            1 => $wfdownloads->getConfig('catimage'),
            2 => $wfdownloads->getConfig('screenshots'),
            3 => $wfdownloads->getConfig('mainimagedir')
        );
        $nameArray = array(
            1 => _AM_WFDOWNLOADS_DOWN_CATIMAGE,
            2 => _AM_WFDOWNLOADS_DOWN_SCREENSHOTS,
            3 => _AM_WFDOWNLOADS_DOWN_MAINIMAGEDIR
        );
        $listArray = array(
            1 => _AM_WFDOWNLOADS_DOWN_FCATIMAGE,
            2 => _AM_WFDOWNLOADS_DOWN_FSCREENSHOTS,
            3 => _AM_WFDOWNLOADS_DOWN_FMAINIMAGEDIR
        );

        $pathList = (isset($listArray[$rootPath])) ? $nameArray[$rootPath] : '';
        $nameList = (isset($listArray[$rootPath])) ? $nameArray[$rootPath] : '';

        $iform = new XoopsThemeForm(_AM_WFDOWNLOADS_DOWN_FUPLOADIMAGETO . $pathList, "op", xoops_getenv('PHP_SELF'));
        $iform->setExtra('enctype="multipart/form-data"');

        $iform->addElement(new XoopsFormHidden('dir', $rootPath));
        ob_start();

        echo "<select size='1' name='workd' onchange='location.href=\"{$currentFile}?rootpath=\"+this.options[this.selectedIndex].value'>";
        echo "<option value=''>" . _AM_WFDOWNLOADS_DOWN_FOLDERSELECTION . "</option>";
        foreach ($nameArray as $namearray => $workd) {
            $opt_selected = ($workd == $nameList) ? 'selected' : '';
            echo "<option value='" . htmlspecialchars($namearray, ENT_QUOTES) . "' {$opt_selected}>{$workd}</option>";
        }
        echo "</select>";
        $iform->addElement(new XoopsFormLabel(_AM_WFDOWNLOADS_DOWN_FOLDERSELECTION, ob_get_contents()));
        ob_end_clean();

        if ($rootPath > 0) {
            $iform->addElement(new XoopsFormLabel(_AM_WFDOWNLOADS_DOWN_FUPLOADPATH, XOOPS_ROOT_PATH . '/' . $dirArray[$rootPath]));
            $iform->addElement(new XoopsFormLabel(_AM_WFDOWNLOADS_DOWN_FUPLOADURL, XOOPS_URL . '/' . $dirArray[$rootPath]));

            $graph_array       = WfsLists::getListTypeAsArray(XOOPS_ROOT_PATH . '/' . $dirArray[$rootPath], $type = 'images');
            $indeximage_select = new XoopsFormSelect('', 'downfile', '');
            $indeximage_select->addOptionArray($graph_array);
            $indeximage_select->setExtra("onchange='showImgSelected(\"image\", \"downfile\", \"" . $dirArray[$rootPath] . "\", \"\", \"" . XOOPS_URL . "\")'");
            $indeximage_tray = new XoopsFormElementTray(_AM_WFDOWNLOADS_DOWN_FSHOWSELECTEDIMAGE, '&nbsp;');
            $indeximage_tray->addElement($indeximage_select);
            if (!empty($_REQUEST['downfile'])) {
                $indeximage_tray->addElement(
                    new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . '/' . $dirArray[$rootPath] . '/' . $_REQUEST['downfile'] . "' name='image' id='image' alt='' title='image' />")
                );
            } else {
                $indeximage_tray->addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/uploads/blank.gif' name='image' id='image' alt='' title='image' />"));
            }
            $iform->addElement($indeximage_tray);

            $iform->addElement(new XoopsFormFile(_AM_WFDOWNLOADS_DOWN_FUPLOADIMAGE, 'uploadfile', 0));
            $iform->addElement(new XoopsFormHidden('uploadpath', $dirArray[$rootPath]));
            $iform->addElement(new XoopsFormHidden('rootnumber', $rootPath));

            $dup_tray = new XoopsFormElementTray('', '');
            $dup_tray->addElement(new XoopsFormHidden('op', 'upload'));
            $butt_dup = new XoopsFormButton('', '', _AM_WFDOWNLOADS_BUPLOAD, 'submit');
            $butt_dup->setExtra('onclick="this.form.elements.op.value=\'image.upload\'"');
            $dup_tray->addElement($butt_dup);

            $butt_dupct = new XoopsFormButton('', '', _AM_WFDOWNLOADS_BDELETEIMAGE, 'submit');
            $butt_dupct->setExtra('onclick="this.form.elements.op.value=\'image.delete\'"');
            $dup_tray->addElement($butt_dupct);
            $iform->addElement($dup_tray);
        }
        $iform->display();
        echo wfdownloads_serverStats();
        include_once __DIR__ . '/admin_footer.php';
}
