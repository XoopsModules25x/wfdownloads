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

use Xmf\Module\Admin;
use Xmf\Request;
use XoopsModules\Wfdownloads;

$currentFile = basename(__FILE__);
require_once __DIR__ . '/admin_header.php';

$op = Request::getString('op', 'indexpage.form');
switch ($op) {
    case 'indexpage.save':
        // Get post parameters
        $indexheading = $myts->addSlashes($_POST['indexheading']);
        $indexheader  = $myts->addSlashes($_POST['indexheader']);
        $indexfooter  = $myts->addSlashes($_POST['indexfooter']);
        require_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $allowedMimetypes = ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'];
        $maxFileSize      = $helper->getConfig('maxfilesize');
        $maxImgWidth      = $helper->getConfig('maximgwidth');
        $maxImgHeight     = $helper->getConfig('maximgheight');
        $uploadDirectory  = XOOPS_ROOT_PATH . '/' . $helper->getConfig('mainimagedir');
        $uploader         = new \XoopsMediaUploader($uploadDirectory, $allowedMimetypes, $maxFileSize, $maxImgWidth, $maxImgHeight);
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            $uploader->setTargetFileName('wfdownloads_' . uniqid(time(), true) . '--' . mb_strtolower($_FILES['uploadfile']['name']));
            $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
            if (!$uploader->upload()) {
                $errors = $uploader->getErrors();
                redirect_header('<script>javascript:history.go(-1)</script>', 3, $errors);
            } else {
                $indeximage = $uploader->getSavedFileName();
            }
        } else {
            $indeximage = (isset($_POST['indeximage']) && 'blank.png' !== $_POST['indeximage']) ? $myts->addSlashes($_POST['indeximage']) : '';
        }
        $nohtml           = isset($_POST['nohtml']);
        $nosmiley         = isset($_POST['nosmiley']);
        $noxcodes         = isset($_POST['noxcodes']);
        $noimages         = isset($_POST['noimages']);
        $nobreak          = isset($_POST['nobreak']);
        $indexheaderalign = $_POST['indexheaderalign'];
        $indexfooteralign = $_POST['indexfooteralign'];
        // Update db
        $sql = "update {$GLOBALS['xoopsDB']->prefix('wfdownloads_indexpage')} set";
        $sql .= " indexheading='{$indexheading}',";
        $sql .= " indexheader='{$indexheader}',";
        $sql .= " indexfooter='{$indexfooter}',";
        $sql .= " indeximage='{$indeximage}',";
        $sql .= " indexheaderalign='{$indexheaderalign}',";
        $sql .= " indexfooteralign='{$indexfooteralign}',";
        $sql .= " nohtml='{$nohtml}',";
        $sql .= " nosmiley='{$nosmiley}',";
        $sql .= " noxcodes='{$noxcodes}',";
        $sql .= " noimages='{$noimages}',";
        $sql .= " nobreak='{$nobreak}' ";
        $GLOBALS['xoopsDB']->query($sql);
        redirect_header(WFDOWNLOADS_URL . '/admin/indexpage.php', 1, _AM_WFDOWNLOADS_IPAGE_UPDATED);
        break;
    case 'indexpage.form':
    default:
        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        $sql    = 'SELECT indeximage, indexheading, indexheader, indexfooter, nohtml, nosmiley, noxcodes, noimages, nobreak, indexheaderalign, indexfooteralign';
        $sql    .= " FROM {$GLOBALS['xoopsDB']->prefix('wfdownloads_indexpage')} ";
        $result = $GLOBALS['xoopsDB']->query($sql);
        [$indeximage, $indexheading, $indexheader, $indexfooter, $nohtml, $nosmiley, $noxcodes, $noimages, $nobreak, $indexheaderalign, $indexfooteralign] = $GLOBALS['xoopsDB']->fetchrow($result);

        Wfdownloads\Utility::getCpHeader();
        $adminObject = Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));

        echo '<fieldset><legend>' . _AM_WFDOWNLOADS_IPAGE_INFORMATION . "</legend>\n";
        echo '<div>' . _AM_WFDOWNLOADS_MINDEX_PAGEINFOTXT . "</div>\n";
        echo "</fieldset>\n";

        $sform = new \XoopsThemeForm(_AM_WFDOWNLOADS_IPAGE_MODIFY, 'op', xoops_getenv('SCRIPT_NAME'), 'post', true);
        $sform->setExtra('enctype="multipart/form-data"');
        // indexpage: indexheading
        $sform->addElement(new \XoopsFormText(_AM_WFDOWNLOADS_IPAGE_CTITLE, 'indexheading', 60, 60, $indexheading), false);
        // indexpage: indeximage
        $indeximage_path = $indeximage ? $helper->getConfig('mainimagedir') . '/' . $indeximage : WFDOWNLOADS_IMAGE_URL . '/blank.png';
        $indeximage_tray = new \XoopsFormElementTray(_AM_WFDOWNLOADS_IPAGE_CIMAGE, '<br>');
        $indeximage_tray->addElement(new \XoopsFormLabel(_AM_WFDOWNLOADS_DOWN_FUPLOADPATH, XOOPS_ROOT_PATH . '/' . $helper->getConfig('mainimagedir')));
        $indeximage_tray->addElement(new \XoopsFormLabel(_AM_WFDOWNLOADS_DOWN_FUPLOADURL, XOOPS_URL . '/' . $helper->getConfig('mainimagedir')));
        $graph_array       = Wfdownloads\WfsLists::getListTypeAsArray(XOOPS_ROOT_PATH . '/' . $helper->getConfig('mainimagedir'), 'images');
        $indeximage_select = new \XoopsFormSelect('', 'indeximage', $indeximage);
        $indeximage_select->addOptionArray($graph_array);
        $indeximage_select->setExtra("onchange='showImgSelected(\"image\", \"indeximage\", \"" . $helper->getConfig('mainimagedir') . '", "", "' . XOOPS_URL . "\")'");
        $indeximage_tray->addElement($indeximage_select, false);
        $indeximage_tray->addElement(new \XoopsFormLabel('', "<img src='" . XOOPS_URL . '/' . $indeximage_path . "' name='image' id='image' alt=''>"));
        $indeximage_tray->addElement(new \XoopsFormFile(_AM_WFDOWNLOADS_BUPLOAD, 'uploadfile', 0), false);
        $sform->addElement($indeximage_tray);
        // indexpage: indexheader
        $sform->addElement(new \XoopsFormDhtmlTextArea(_AM_WFDOWNLOADS_IPAGE_CHEADING, 'indexheader', $indexheader, 15, 60));
        // indexpage: indexheaderalign
        $headeralign_select = new \XoopsFormSelect(_AM_WFDOWNLOADS_IPAGE_CHEADINGA, 'indexheaderalign', $indexheaderalign);
        $headeralign_select->addOptionArray(['left' => _AM_WFDOWNLOADS_IPAGE_CLEFT, 'right' => _AM_WFDOWNLOADS_IPAGE_CRIGHT, 'center' => _AM_WFDOWNLOADS_IPAGE_CCENTER]);
        $sform->addElement($headeralign_select);
        // indexpage: indexfooter
        $sform->addElement(new \XoopsFormDhtmlTextArea(_AM_WFDOWNLOADS_IPAGE_CFOOTER, 'indexfooter', $indexfooter, 15, 60));
        // indexpage: indexfooteralign
        $footeralign_select = new \XoopsFormSelect(_AM_WFDOWNLOADS_IPAGE_CFOOTERA, 'indexfooteralign', $indexfooteralign);
        $footeralign_select->addOptionArray(['left' => _AM_WFDOWNLOADS_IPAGE_CLEFT, 'right' => _AM_WFDOWNLOADS_IPAGE_CRIGHT, 'center' => _AM_WFDOWNLOADS_IPAGE_CCENTER]);
        $sform->addElement($footeralign_select);
        // indexpage: nohtml, nosmailey, noxcodes, noimages, nobreak
        $options_tray  = new \XoopsFormElementTray(_AM_WFDOWNLOADS_TEXTOPTIONS, '<br>');
        $html_checkbox = new \XoopsFormCheckBox('', 'nohtml', $nohtml);
        $html_checkbox->addOption(1, _AM_WFDOWNLOADS_ALLOWHTML);
        $options_tray->addElement($html_checkbox);
        $smiley_checkbox = new \XoopsFormCheckBox('', 'nosmiley', $nosmiley);
        $smiley_checkbox->addOption(1, _AM_WFDOWNLOADS_ALLOWSMILEY);
        $options_tray->addElement($smiley_checkbox);
        $xcodes_checkbox = new \XoopsFormCheckBox('', 'noxcodes', $noxcodes);
        $xcodes_checkbox->addOption(1, _AM_WFDOWNLOADS_ALLOWXCODE);
        $options_tray->addElement($xcodes_checkbox);
        $noimages_checkbox = new \XoopsFormCheckBox('', 'noimages', $noimages);
        $noimages_checkbox->addOption(1, _AM_WFDOWNLOADS_ALLOWIMAGES);
        $options_tray->addElement($noimages_checkbox);
        $breaks_checkbox = new \XoopsFormCheckBox('', 'nobreak', $nobreak);
        $breaks_checkbox->addOption(1, _AM_WFDOWNLOADS_ALLOWBREAK);
        $options_tray->addElement($breaks_checkbox);
        $sform->addElement($options_tray);
        // form: button try
        $buttonTray = new \XoopsFormElementTray('', '');
        $hidden     = new \XoopsFormHidden('op', 'indexpage.save');
        $buttonTray->addElement($hidden);
        $buttonTray->addElement(new \XoopsFormButton('', 'post', _AM_WFDOWNLOADS_BSAVE, 'submit'));
        $sform->addElement($buttonTray);
        $sform->display();
        break;
}
require_once __DIR__ . '/admin_footer.php';
