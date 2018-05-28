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

/** @var \XoopsModules\Wfdownloads\Helper $helper */
$helper = \XoopsModules\Wfdownloads\Helper::getInstance();

$op = Request::getString('op', 'mimetypes.list');
switch ($op) {
    /*
        case 'openurl':
            $fileext = trim($_POST['fileext']);
            $url = "http://filext.com/detaillist.php?extdetail={$fileext}";
            if (!headers_sent()) {
                header("Location: $url");
            } else {
                echo "<meta http-equiv='refresh' content='0;url={$url} target='_blank''>\r\n";
            }
            break;
    */
    case 'mimetype.edit':
    case 'mimetype.add':
        Wfdownloads\Utility::getCpHeader();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation($currentFile);

        //$adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->addItemButton(_MI_WFDOWNLOADS_MENU_MIMETYPES, "{$currentFile}?op=mimetypes.list", 'list');
        $adminObject->addItemButton(_AM_WFDOWNLOADS_MIME_CREATEF, "{$currentFile}?op=mimetype.add", 'add');
        $adminObject->displayButton('left');

        echo "<fieldset>\n";
        echo "<legend style='font-weight: bold; color: #900;'>" . _AM_WFDOWNLOADS_MIME_CREATEF . '/' . _AM_WFDOWNLOADS_MIME_MODIFYF . "</legend>\n";
        echo '<div>' . _AM_WFDOWNLOADS_MIME_INFOTEXT . "</div>\n";
        echo "</fieldset>\n";

    /** @var Wfdownloads\Mimetype $mimetypeObj */
        if (isset($_REQUEST['mime_id'])) {
            $mimetypeObj = $helper->getHandler('Mimetype')->get($_REQUEST['mime_id']);
        } else {
            $mimetypeObj = $helper->getHandler('Mimetype')->create();
        }
        $form = $mimetypeObj->getForm();
        $form->display();

        $extform = new \XoopsThemeForm(_AM_WFDOWNLOADS_MIME_FINDMIMETYPE, 'op', $_SERVER['REQUEST_URI']);

        $fileext_text = new \XoopsFormText(_AM_WFDOWNLOADS_MIME_EXTFIND, 'fileext', 5, 60, $mimetypeObj->getVar('mime_ext'));
        $fileext_text->setDescription(_AM_WFDOWNLOADS_MIME_EXTFIND_DESC);
        $extform->addElement($fileext_text);
        $button_open = new \XoopsFormButton('', '', _AM_WFDOWNLOADS_MIME_FINDIT, 'button');
        $button_open->setExtra('onclick="document.getElementById(\'filext_iframe\').src = \'http://filext.com/detaillist.php?extdetail=\' + this.form.elements.fileext.value"');
        $extform->addElement($button_open);
        $extform->addElement($button_tray);
        $extform->display();

        echo "<iframe src='http://filext.com/detaillist.php?extdetail=" . $mimetypeObj->getVar('mime_ext') . "' id='filext_iframe' name='filext_iframe' class='outer' style='width:100%; height:400px;'></iframe>";

        xoops_cp_footer();
        break;

    case 'mimetype.save':
        $mime_id = Request::getInt('mime_id', 0, 'POST');
        if (!$mimetypeObj = $helper->getHandler('Mimetype')->get($mime_id)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_MIMETYPENOTFOUND);
        }

        $mimetypeObj->setVar('mime_ext', $_POST['mime_ext']);
        $mimetypeObj->setVar('mime_name', $_POST['mime_name']);
        $mimetypeObj->setVar('mime_types', $_POST['mime_type']);
        $mimetypeObj->setVar('mime_admin', \Xmf\Request::getInt('mime_admin', 0, 'POST'));
        $mimetypeObj->setVar('mime_user', \Xmf\Request::getInt('mime_user', 0, 'POST'));
        if (!$helper->getHandler('Mimetype')->insert($mimetypeObj)) {
            $error = 'Could not update mimetype information';
            trigger_error($error, E_USER_ERROR);
        }
        $dbupted = (0 == $mime_id) ? _AM_WFDOWNLOADS_MIME_CREATED : _AM_WFDOWNLOADS_MIME_MODIFIED;
        redirect_header($currentFile, 1, $dbupted);
        break;

    case 'mimetype.update':
        $mime_id = Request::getInt('mime_id', 0);
        if (!$mimetypeObj = $helper->getHandler('Mimetype')->get($mime_id)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_MIMETYPENOTFOUND);
        }

        if (isset($_REQUEST['admin']) && true === $_REQUEST['admin']) {
            if (true === $mimetypeObj->getVar('mime_admin')) {
                $mimetypeObj->setVar('mime_admin', false);
            } else {
                $mimetypeObj->setVar('mime_admin', true);
            }
        }
        if (isset($_REQUEST['user']) && true === $_REQUEST['user']) {
            if (true === $mimetypeObj->getVar('mime_user')) {
                $mimetypeObj->setVar('mime_user', false);
            } else {
                $mimetypeObj->setVar('mime_user', true);
            }
        }
        if (!$helper->getHandler('Mimetype')->insert($mimetypeObj, true)) {
            trigger_error($error, E_USER_ERROR);
        }
        redirect_header("{$currentFile}?start=" . \Xmf\Request::getInt('start', 0, 'GET') . '', 0, _AM_WFDOWNLOADS_MIME_MODIFIED);
        break;

    case 'mimetypes.update':
        $mime_admin = Request::getBool('admin', false);
        $mime_user  = Request::getBool('user', false);
        $type_all   = \Xmf\Request::getInt('type_all', 0, 'GET');

        $field = 'mime_user';
        if (true === $mime_admin) {
            $field = 'mime_admin';
        }
        $criteria = new \CriteriaCompo();
        $criteria->setStart($start);
        $criteria->setLimit(20);
        if (!$helper->getHandler('Mimetype')->updateAll($field, $type_all, $criteria, true)) {
            $error = 'Could not update mimetype information';
            trigger_error($error, E_USER_ERROR);
        }
        redirect_header("{$currentFile}?start=" . \Xmf\Request::getInt('start', 0, 'GET') . '', 1, _AM_WFDOWNLOADS_MIME_MODIFIED);
        break;

    case 'mimetype.delete':
        $mime_id = Request::getInt('mime_id', 0);
        $ok      = Request::getBool('ok', false, 'POST');
        if (!$mimetypeObj = $helper->getHandler('Mimetype')->get($mime_id)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_MIMETYPENOTFOUND);
        }
        if (true === $ok) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($helper->getHandler('Mimetype')->delete($mimetypeObj)) {
                redirect_header($currentFile, 1, sprintf(_AM_WFDOWNLOADS_MIME_MIMEDELETED, $mimetypeObj->getVar('mime_name')));
            } else {
                echo $mimetypeObj->getHtmlErrors();
                exit();
            }
        } else {
            Wfdownloads\Utility::getCpHeader();
            xoops_confirm(['op' => 'mimetype.delete', 'mime_id' => $mime_id, 'ok' => true], $currentFile, _AM_WFDOWNLOADS_MIME_DELETETHIS . '<br><br>' . $mimetypeObj->getVar('mime_name'), _AM_WFDOWNLOADS_MIME_DELETE);
            xoops_cp_footer();
        }
        break;

    case 'mimetypes.list':
    default:
        $start = Request::getInt('start', 0);

        // Get mimetypes (20 per page)
        $criteria = new \CriteriaCompo();
        $criteria->setSort('mime_name');
        $criteria->setStart($start);
        $criteria->setLimit(20);
        $mimetypeObjs    = $helper->getHandler('Mimetype')->getObjects($criteria);
        $mimetypes_count = $helper->getHandler('Mimetype')->getCount();

        Wfdownloads\Utility::getCpHeader();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation($currentFile);

        //$adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->addItemButton(_MI_WFDOWNLOADS_MENU_MIMETYPES, "{$currentFile}?op=mimetypes.list", 'list');
        $adminObject->addItemButton(_AM_WFDOWNLOADS_MIME_CREATEF, "{$currentFile}?op=mimetype.add", 'add');
        $adminObject->displayButton('left');

        $GLOBALS['xoopsTpl']->assign('mimetypes_count', $mimetypes_count);
        $GLOBALS['xoopsTpl']->assign('start', $start);

        if ($mimetypes_count > 0) {
            $allowAdminMimetypes = [];
            $allowUserMimetypes  = [];
            foreach ($mimetypeObjs as $mimetypeObj) {
                $mimetype_array = $mimetypeObj->toArray();
                $GLOBALS['xoopsTpl']->append('mimetypes', $mimetype_array);
            }
            //Include page navigation
            require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $pagenav = new \XoopsPageNav($mimetypes_count, 20, $start, 'start');
            $GLOBALS['xoopsTpl']->assign('mimetypes_pagenav', $pagenav->renderNav());
        }

        // Get allowed mimetypes/estensione
        $allowAdminMimetypes = $helper->getHandler('Mimetype')->getList(new \Criteria('mime_admin', true));
        $allowUserMimetypes  = $helper->getHandler('Mimetype')->getList(new \Criteria('mime_user', true));
        $GLOBALS['xoopsTpl']->assign('allowAdminMimetypes', $allowAdminMimetypes);
        $GLOBALS['xoopsTpl']->assign('allowUserMimetypes', $allowUserMimetypes);

        $GLOBALS['xoopsTpl']->display("db:{$helper->getModule()->dirname()}_am_mimetypeslist.tpl");

        require_once __DIR__ . '/admin_footer.php';
        break;
}
