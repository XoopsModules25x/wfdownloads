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

// Check directories
if (!is_dir($wfdownloads->getConfig('uploaddir'))) {
    redirect_header('index.php', 4, _AM_WFDOWNLOADS_ERROR_UPLOADDIRNOTEXISTS);
    exit();
}
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

$op = WfdownloadsRequest::getString('op', 'categories.list');
switch ($op) {
    case "category.move":
    case "move":
        $ok = WfdownloadsRequest::getBool('ok', false, 'POST');
        if ($ok == false) {
            $cid = WfdownloadsRequest::getInt('cid', 0);

            wfdownloads_xoops_cp_header();

            include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
            $sform = new XoopsThemeForm(_AM_WFDOWNLOADS_CCATEGORY_MOVE, "move", xoops_getenv('PHP_SELF'));

            $categories = $wfdownloads->getHandler('category')->getObjects();
            $mytree = new XoopsObjectTree($categories, "cid", "pid");
            $sform->addElement(new XoopsFormLabel(_AM_WFDOWNLOADS_BMODIFY, $mytree->makeSelBox('target', 'title')));
                $create_tray = new XoopsFormElementTray('', '');
                $create_tray -> addElement(new XoopsFormHidden('source', $cid));
                $create_tray -> addElement(new XoopsFormHidden('ok', true));
                $create_tray -> addElement(new XoopsFormHidden('op', 'category.move'));
                    $butt_save = new XoopsFormButton('', '', _AM_WFDOWNLOADS_BMOVE, 'submit');
                    $butt_save -> setExtra('onclick="this.form.elements.op.value=\'category.move\'"');
                $create_tray -> addElement($butt_save);
                    $butt_cancel = new XoopsFormButton('', '', _AM_WFDOWNLOADS_BCANCEL, 'submit');
                    $butt_cancel -> setExtra('onclick="this.form.elements.op.value=\'cancel\'"');
                $create_tray -> addElement($butt_cancel);
            $sform -> addElement($create_tray);
            $sform -> display();
            xoops_cp_footer();
        } else {
            $source = WfdownloadsRequest::getInt('source', 0, 'POST');
            $target = WfdownloadsRequest::getInt('target', 0, 'POST');
            if ($target == $source) {
                redirect_header($currentFile . "?op=category.move&amp;ok=0&amp;cid={$source}", 5, _AM_WFDOWNLOADS_CCATEGORY_MODIFY_FAILED);
            }
            if (!$target) {
                redirect_header($currentFile . "?op=category.move&amp;ok=0&amp;cid={$source}", 5, _AM_WFDOWNLOADS_CCATEGORY_MODIFY_FAILEDT);
            }
            $result = $wfdownloads->getHandler('download')->updateAll("cid", $target, new Criteria("cid", $source), true);
            if (!$result) {
                $error = _AM_WFDOWNLOADS_DBERROR;
                trigger_error($error, E_USER_ERROR);
            }
            redirect_header($currentFile, 1, _AM_WFDOWNLOADS_CCATEGORY_MODIFY_MOVED);
            exit();
        }
        break;

    case "category.save" :
    case "addCat" :
        $cid = WfdownloadsRequest::getInt('cid', 0, 'POST');
        $pid = WfdownloadsRequest::getInt('pid', 0, 'POST');
        $weight = (isset($_POST["weight"]) && $_POST["weight"] > 0) ? (int) $_POST["weight"] : 0;
        $down_groups = isset($_POST['groups']) ? $_POST['groups'] : array();
        $up_groups = isset($_POST['up_groups']) ? $_POST['up_groups'] : array();
        $spotlighthis = (isset($_POST["lid"])) ? (int) $_POST["lid"] : 0;
        $spotlighttop = (isset($_POST["spotlighttop"]) && ($_POST["spotlighttop"] == 1)) ? 1 : 0;

        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
        $allowedMimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
        $maxFileSize      = $wfdownloads->getConfig('maxfilesize');
        $maxImgWidth      = $wfdownloads->getConfig('maximgwidth');
        $maxImgHeight     = $wfdownloads->getConfig('maximgheight');
        $uploadDirectory  = XOOPS_ROOT_PATH . '/' . $wfdownloads->getConfig('catimage');
        $uploader = new XoopsMediaUploader($uploadDirectory, $allowedMimetypes, $maxFileSize, $maxImgWidth, $maxImgHeight);
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            $uploader->setTargetFileName('wfdownloads_' . uniqid(time()) . '--' . strtolower($_FILES['uploadfile']['name']));
            $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
            if (!$uploader->upload()) {
                $errors = $uploader->getErrors();
                redirect_header("javascript:history.go(-1)",3, $errors);
            } else {
                $imgurl = $uploader->getSavedFileName();
            }
        } else {
            $imgurl = (isset($_POST["imgurl"]) && $_POST["imgurl"] != "blank.png") ? $myts -> addslashes($_POST["imgurl"]) : "";
        }
        // Formulize module support (2006/05/04) jpc
        if (wfdownloads_checkModule('formulize')) {
            $formulize_fid = (isset($_POST["formulize_fid"])) ? (int) $_POST["formulize_fid"] : 0;
        }

        if (!$cid) {
            $category = $wfdownloads->getHandler('category')->create();
        } else {
            $category = $wfdownloads->getHandler('category')->get($cid);
            $childcats = $wfdownloads->getHandler('category')->getChildCats($category);
            if ($pid == $cid || in_array($pid, array_keys($childcats))) {
                $category->setErrors(_AM_WFDOWNLOADS_CCATEGORY_CHILDASPARENT);
            }
        }

        $category->setVar('title', $_POST["title"]);
        $category->setVar('pid', $pid);
        $category->setVar('weight', $weight);
        $category->setVar('imgurl', $imgurl);
        $category->setVar('description', $_POST["description"]);
        $category->setVar('summary', $_POST["summary"]);
        $category->setVar('dohtml', isset($_POST['dohtml']));
        $category->setVar('dosmiley', isset($_POST['dosmiley']));
        $category->setVar('doxcode', isset($_POST['doxcode']));
        $category->setVar('doimage', isset($_POST['doimage']));
        $category->setVar('dobr', isset($_POST['dobr']));
        // Formulize module support (2006/05/04) jpc
        if (wfdownloads_checkModule('formulize')) {
            $category->setVar('formulize_fid', $formulize_fid);
        }
        $category->setVar('spotlighthis', $spotlighthis);
        $category->setVar('spotlighttop', $spotlighttop);

        if (!$wfdownloads->getHandler('category')->insert($category)) {
            echo $category->getHtmlErrors();
        }
        if (!$cid) {
            if ($cid == 0) {
                $newid = (int) $category->getVar('cid');
            }
            wfdownloads_savePermissions($down_groups, $newid, 'WFDownCatPerm');
            wfdownloads_savePermissions($up_groups, $newid, 'WFUpCatPerm');
            // Notify of new category
            $tags = array();
            $tags['CATEGORY_NAME'] = $_POST['title'];
            $tags['CATEGORY_URL'] = WFDOWNLOADS_URL . '/viewcat.php?cid=' . $newid;
            $notification_handler = & xoops_gethandler('notification');
            $notification_handler -> triggerEvent('global', 0, 'new_category', $tags);
            $database_mess = _AM_WFDOWNLOADS_CCATEGORY_CREATED;
        } else {
            $database_mess = _AM_WFDOWNLOADS_CCATEGORY_MODIFIED;
            wfdownloads_savePermissions($down_groups, $cid, 'WFDownCatPerm');
            wfdownloads_savePermissions($up_groups, $cid, 'WFUpCatPerm');
        }
        redirect_header($currentFile, 1, $database_mess);
        break;

    case "category.delete" :
    case "del" :
        $cid = WfdownloadsRequest::getInt('cid', 0);
        $ok = WfdownloadsRequest::getBool('ok', false, 'POST');
        $categories = $wfdownloads->getHandler('category')->getObjects();
        $mytree = new XoopsObjectTree($categories, "cid", "pid");
        if ($ok == true) {
            // get all subcategories under the specified category
            $arr = $mytree -> getAllChild($cid);
            foreach ($arr as $child) {
                // get all category ids
                $cids[] = $child->getVar('cid');
            }
            $cids[] = $cid;

            $criteria = new Criteria("cid", "(" . implode(',', $cids) . ")", "IN");

            //get list of downloads in these subcategories
            $downloads = $wfdownloads->getHandler('download')->getList($criteria);

            $download_criteria = new Criteria("lid", "(" . implode(',', array_keys($downloads)) . ")", "IN");

            // now for each download, delete the text data and vote data associated with the download
            $wfdownloads->getHandler('rating')->deleteAll($download_criteria);
            $wfdownloads->getHandler('report')->deleteAll($download_criteria);
            $wfdownloads->getHandler('download')->deleteAll($download_criteria);
            foreach (array_keys($downloads) as $lid) {
                xoops_comment_delete($wfdownloads->getModule()->mid(), (int) $lid);
            }

            // all downloads for each category is deleted, now delete the category data
            $wfdownloads->getHandler('category')->deleteAll($criteria);
            $error = _AM_WFDOWNLOADS_DBERROR;

            foreach ($cids as $cid) {
                xoops_groupperm_deletebymoditem ($wfdownloads->getModule()->mid(), 'WFDownCatPerm', $cid);
                xoops_groupperm_deletebymoditem ($wfdownloads->getModule() -> mid(), 'WFUpCatPerm', $cid);
            }

            redirect_header($currentFile, 1, _AM_WFDOWNLOADS_CCATEGORY_DELETED);
            exit();
        } else {
            wfdownloads_xoops_cp_header();
            xoops_confirm(array('op' => 'category.delete', 'cid' => $cid, 'ok' => true), $currentFile, _AM_WFDOWNLOADS_CCATEGORY_AREUSURE);
            xoops_cp_footer();
        }
        break;

    case "category.add" :
    case "category.edit" :
    case "modCat":
        wfdownloads_xoops_cp_header();
        $indexAdmin = new ModuleAdmin();
        echo $indexAdmin->addNavigation($currentFile);

        $adminMenu = new ModuleAdmin();
        $adminMenu->addItemButton(_MI_WFDOWNLOADS_MENU_CATEGORIES, "{$currentFile}?op=categories.list", 'list');
        echo $adminMenu->renderButton();

        if (isset($_REQUEST['cid'])) {
            $category = $wfdownloads->getHandler('category')->get($_REQUEST['cid']);
        } else {
            $category = $wfdownloads->getHandler('category')->create();
        }
        $form = $category->getForm();
        $form -> display();

        include 'admin_footer.php';
        break;

    case 'categories.list' :
    case 'main' :
    default :
        wfdownloads_xoops_cp_header();
        $indexAdmin = new ModuleAdmin();
        echo $indexAdmin->addNavigation($currentFile);

        $adminMenu = new ModuleAdmin();
        $adminMenu->addItemButton(_AM_WFDOWNLOADS_CCATEGORY_CREATENEW, "{$currentFile}?op=category.add", 'add');
        echo $adminMenu->renderButton();

        $totalCategories = wfdownloads_categoriesCount();
        if ($totalCategories > 0) {
            $sorted_categories = wfdownloads_sortCategories();
            $GLOBALS['xoopsTpl']->assign('sorted_categories', $sorted_categories);
            $GLOBALS['xoopsTpl']->assign('token', $GLOBALS['xoopsSecurity']->getTokenHTML() );
            $GLOBALS['xoopsTpl']->display("db:{$wfdownloads->getModule()->dirname()}_admin_categorieslist.tpl");
        } else {
            redirect_header("{$currentFile}?op=category.add", 1, _AM_WFDOWNLOADS_CCATEGORY_NOEXISTS);
        }
        include 'admin_footer.php';
        break;

    case 'categories.reorder' :
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors() ));
        }

        if (isset($_POST['new_weights']) && count($_POST['new_weights']) > 0) {
            $new_weights = $_POST['new_weights'];
            $ids = array();
            foreach ($new_weights as $cid => $new_weight) {
                $category = $wfdownloads->getHandler('category')->get($cid);
                $category->setVar('weight', $new_weight);
                if (!$wfdownloads->getHandler('category')->insert($category)) {
                    redirect_header($currentFile, 3, $category->getErrors());
                }
                unset($category);
            }
            redirect_header($currentFile, 1, _AM_WFDOWNLOADS_CATEGORIES_REORDERED);
            exit();
        }
        break;
}
