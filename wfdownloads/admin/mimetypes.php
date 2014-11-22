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

$op = XoopsRequest::getString('op', 'mimetypes.list');
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
    case "mimetype.edit" :
    case "mimetype.add" :
        wfdownloads_xoops_cp_header();
        $indexAdmin = new ModuleAdmin();
        echo $indexAdmin->addNavigation($currentFile);

        $adminMenu = new ModuleAdmin();
        $adminMenu->addItemButton(_MI_WFDOWNLOADS_MENU_MIMETYPES, "{$currentFile}?op=mimetypes.list", 'list');
        $adminMenu->addItemButton(_AM_WFDOWNLOADS_MIME_CREATEF, "{$currentFile}?op=mimetype.add", 'add');
        echo $adminMenu->renderButton();

        echo "<fieldset>\n";
        echo "<legend style='font-weight: bold; color: #900;'>" . _AM_WFDOWNLOADS_MIME_CREATEF . '/' . _AM_WFDOWNLOADS_MIME_MODIFYF . "</legend>\n";
        echo "<div>" . _AM_WFDOWNLOADS_MIME_INFOTEXT . "</div>\n";
        echo "</fieldset>\n";

        if (isset($_REQUEST['mime_id'])) {
            $mimetypeObj = $wfdownloads->getHandler('mimetype')->get($_REQUEST['mime_id']);
        } else {
            $mimetypeObj = $wfdownloads->getHandler('mimetype')->create();

        }
        $form = $mimetypeObj->getForm();
        $form->display();

        $extform = new XoopsThemeForm(_AM_WFDOWNLOADS_MIME_FINDMIMETYPE, 'op', $_SERVER['REQUEST_URI']);

        $fileext_text = new XoopsFormText(_AM_WFDOWNLOADS_MIME_EXTFIND, 'fileext', 5, 60, $mimetypeObj->getVar('mime_ext'));
        $fileext_text->setDescription(_AM_WFDOWNLOADS_MIME_EXTFIND_DESC);
        $extform->addElement($fileext_text);
        $button_open = new XoopsFormButton('', '', _AM_WFDOWNLOADS_MIME_FINDIT, 'button');
        $button_open->setExtra(
            'onclick="document.getElementById(\'filext_iframe\').src = \'http://filext.com/detaillist.php?extdetail=\' + this.form.elements.fileext.value"'
        );
        $extform->addElement($button_open);
        $extform->addElement($button_tray);
        $extform->display();

        echo "<iframe src='http://filext.com/detaillist.php?extdetail=" . $mimetypeObj->getVar('mime_ext')
            . "' id='filext_iframe' name='filext_iframe' class='outer' style='width:100%; height:400px;'></iframe>";

        xoops_cp_footer();
        break;

    case "mimetype.save" :
        $mime_id = XoopsRequest::getInt('mime_id', 0, 'POST');
        if (!$mimetypeObj = $wfdownloads->getHandler('mimetype')->get($mime_id)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_MIMETYPENOTFOUND);
            exit();
        }

        $mimetypeObj->setVar('mime_ext', $_POST['mime_ext']);
        $mimetypeObj->setVar('mime_name', $_POST['mime_name']);
        $mimetypeObj->setVar('mime_types', $_POST['mime_type']);
        $mimetypeObj->setVar('mime_admin', (int)$_POST['mime_admin']);
        $mimetypeObj->setVar('mime_user', (int)$_POST['mime_user']);
        if (!$wfdownloads->getHandler('mimetype')->insert($mimetypeObj)) {
            $error = "Could not update mimetype information";
            trigger_error($error, E_USER_ERROR);
        }
        $dbupted = ($mime_id == 0) ? _AM_WFDOWNLOADS_MIME_CREATED : _AM_WFDOWNLOADS_MIME_MODIFIED;
        redirect_header($currentFile, 1, $dbupted);
        break;

    case "mimetype.update" :
        $mime_id = XoopsRequest::getInt('mime_id', 0);
        if (!$mimetypeObj = $wfdownloads->getHandler('mimetype')->get($mime_id)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_MIMETYPENOTFOUND);
            exit();
        }

        if (isset($_REQUEST['admin']) && $_REQUEST['admin'] == true) {
            if ($mimetypeObj->getVar('mime_admin') == true) {
                $mimetypeObj->setVar('mime_admin', false);
            } else {
                $mimetypeObj->setVar('mime_admin', true);
            }
        }
        if (isset($_REQUEST['user']) && $_REQUEST['user'] == true) {
            if ($mimetypeObj->getVar('mime_user') == true) {
                $mimetypeObj->setVar('mime_user', false);
            } else {
                $mimetypeObj->setVar('mime_user', true);
            }
        }
        if (!$wfdownloads->getHandler('mimetype')->insert($mimetypeObj, true)) {
            trigger_error($error, E_USER_ERROR);
        }
        redirect_header("{$currentFile}?start=" . (int)($_GET['start']) . "", 0, _AM_WFDOWNLOADS_MIME_MODIFIED);
        break;

    case "mimetypes.update" :
        $mime_admin = XoopsRequest::getBool('admin', false);
        $mime_user  = XoopsRequest::getBool('user', false);
        $type_all   = (int)($_GET['type_all']);

        if ($mime_admin == true) {
            $field = 'mime_admin';
        } else {
            $field = 'mime_user';
        }
        $criteria = new CriteriaCompo();
        $criteria->setStart($start);
        $criteria->setLimit(20);
        if (!$wfdownloads->getHandler('mimetype')->updateAll($field, $type_all, $criteria, true)) {
            $error = "Could not update mimetype information";
            trigger_error($error, E_USER_ERROR);
        }
        redirect_header("{$currentFile}?start=" . (int)($_GET['start']) . "", 1, _AM_WFDOWNLOADS_MIME_MODIFIED);
        break;

    case "mimetype.delete" :
        $mime_id = XoopsRequest::getInt('mime_id', 0);
        $ok      = XoopsRequest::getBool('ok', false, 'POST');
        if (!$mimetypeObj = $wfdownloads->getHandler('mimetype')->get($mime_id)) {
            redirect_header($currentFile, 4, _AM_WFDOWNLOADS_ERROR_MIMETYPENOTFOUND);
            exit();
        }
        if ($ok === true) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($wfdownloads->getHandler('mimetype')->delete($mimetypeObj)) {
                redirect_header($currentFile, 1, sprintf(_AM_WFDOWNLOADS_MIME_MIMEDELETED, $mimetypeObj->getVar('mime_name')));
                exit();
            } else {
                echo $mimetypeObj->getHtmlErrors();
                exit();
            }
        } else {
            wfdownloads_xoops_cp_header();
            xoops_confirm(
                array('op' => 'mimetype.delete', 'mime_id' => $mime_id, 'ok' => true),
                $currentFile,
                _AM_WFDOWNLOADS_MIME_DELETETHIS . "<br /><br>" . $mimetypeObj->getVar('mime_name'),
                _AM_WFDOWNLOADS_MIME_DELETE
            );
            xoops_cp_footer();
        }
        break;

    case "mimetypes.list" :
    default :
        $start = XoopsRequest::getInt('start', 0);

        // Get mimetypes (20 per page)
        $criteria = new CriteriaCompo();
        $criteria->setSort('mime_name');
        $criteria->setStart($start);
        $criteria->setLimit(20);
        $mimetypeObjs    = $wfdownloads->getHandler('mimetype')->getObjects($criteria);
        $mimetypes_count = $wfdownloads->getHandler('mimetype')->getCount();

        wfdownloads_xoops_cp_header();
        $indexAdmin = new ModuleAdmin();
        echo $indexAdmin->addNavigation($currentFile);

        $adminMenu = new ModuleAdmin();
        $adminMenu->addItemButton(_MI_WFDOWNLOADS_MENU_MIMETYPES, "{$currentFile}?op=mimetypes.list", 'list');
        $adminMenu->addItemButton(_AM_WFDOWNLOADS_MIME_CREATEF, "{$currentFile}?op=mimetype.add", 'add');
        echo $adminMenu->renderButton();

        $GLOBALS['xoopsTpl']->assign('mimetypes_count', $mimetypes_count);
        $GLOBALS['xoopsTpl']->assign('start', $start);

        if ($mimetypes_count > 0) {
            $allowAdminMimetypes = array();
            $allowUserMimetypes  = array();
            foreach ($mimetypeObjs as $mimetypeObj) {
                $mimetype_array = $mimetypeObj->toArray();
                $GLOBALS['xoopsTpl']->append('mimetypes', $mimetype_array);
            }
            //Include page navigation
            include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $pagenav = new XoopsPageNav($mimetypes_count, 20, $start, 'start');
            $GLOBALS['xoopsTpl']->assign('mimetypes_pagenav', $pagenav->renderNav());
        }

        // Get allowed mimetypes/estensione
        $allowAdminMimetypes = $wfdownloads->getHandler('mimetype')->getList(new Criteria('mime_admin', true));
        $allowUserMimetypes  = $wfdownloads->getHandler('mimetype')->getList(new Criteria('mime_user', true));
        $GLOBALS['xoopsTpl']->assign('allowAdminMimetypes', $allowAdminMimetypes);
        $GLOBALS['xoopsTpl']->assign('allowUserMimetypes', $allowUserMimetypes);

        $GLOBALS['xoopsTpl']->display("db:{$wfdownloads->getModule()->dirname()}_am_mimetypeslist.tpl");

        include_once __DIR__ . '/admin_footer.php';
        break;
}
