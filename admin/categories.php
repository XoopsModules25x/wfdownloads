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

$op = Request::getString('op', 'categories.list');
switch ($op) {
    case 'category.move':
    case 'move':
        $ok = Request::getBool('ok', false, 'POST');
        if (false === $ok) {
            $cid = Request::getInt('cid', 0);

            Wfdownloads\Utility::getCpHeader();

            xoops_load('XoopsFormLoader');
            $sform = new \XoopsThemeForm(_AM_WFDOWNLOADS_CCATEGORY_MOVE, 'move', xoops_getenv('PHP_SELF'), 'post', true);

            $categoryObjs     = $helper->getHandler('category')->getObjects();
            $categoryObjsTree = new Wfdownloads\ObjectTree($categoryObjs, 'cid', 'pid');

            if (Wfdownloads\Utility::checkVerXoops($GLOBALS['xoopsModule'], '2.5.9')) {
                $catSelect = $categoryObjsTree->makeSelectElement('target', 'title', '--', $this->getVar('target'), true, 0, '', _AM_WFDOWNLOADS_BMODIFY);
                $sform->addElement($catSelect);
            } else {
                $sform->addElement(new \XoopsFormLabel(_AM_WFDOWNLOADS_BMODIFY, $categoryObjsTree->makeSelBox('target', 'title')));
            }

            $create_tray = new \XoopsFormElementTray('', '');
            $create_tray->addElement(new \XoopsFormHidden('source', $cid));
            $create_tray->addElement(new \XoopsFormHidden('ok', true));
            $create_tray->addElement(new \XoopsFormHidden('op', 'category.move'));
            $butt_save = new \XoopsFormButton('', '', _AM_WFDOWNLOADS_BMOVE, 'submit');
            $butt_save->setExtra('onclick="this.form.elements.op.value=\'category.move\'"');
            $create_tray->addElement($butt_save);
            $butt_cancel = new \XoopsFormButton('', '', _AM_WFDOWNLOADS_BCANCEL, 'submit');
            $butt_cancel->setExtra('onclick="this.form.elements.op.value=\'cancel\'"');
            $create_tray->addElement($butt_cancel);
            $sform->addElement($create_tray);
            $sform->display();
            xoops_cp_footer();
        } else {
            $source = Request::getInt('source', 0, 'POST');
            $target = Request::getInt('target', 0, 'POST');
            if ($target == $source) {
                redirect_header($currentFile . "?op=category.move&amp;ok=0&amp;cid={$source}", 5, _AM_WFDOWNLOADS_CCATEGORY_MODIFY_FAILED);
            }
            if (!$target) {
                redirect_header($currentFile . "?op=category.move&amp;ok=0&amp;cid={$source}", 5, _AM_WFDOWNLOADS_CCATEGORY_MODIFY_FAILEDT);
            }
            $result = $helper->getHandler('download')->updateAll('cid', $target, new \Criteria('cid', $source), true);
            if (!$result) {
                $error = _AM_WFDOWNLOADS_DBERROR;
                trigger_error($error, E_USER_ERROR);
            }
            redirect_header($currentFile, 1, _AM_WFDOWNLOADS_CCATEGORY_MODIFY_MOVED);
        }
        break;

    case 'category.save':
    case 'addCat':
        $cid          = Request::getInt('cid', 0, 'POST');
        $pid          = Request::getInt('pid', 0, 'POST');
        $weight       = (isset($_POST['weight']) && $_POST['weight'] > 0) ? (int)$_POST['weight'] : 0;
        $down_groups  = isset($_POST['groups']) ? $_POST['groups'] : [];
        $up_groups    = isset($_POST['up_groups']) ? $_POST['up_groups'] : [];
        $spotlighthis = \Xmf\Request::getInt('lid', 0, 'POST');
        $spotlighttop = (isset($_POST['spotlighttop']) && (1 == $_POST['spotlighttop'])) ? 1 : 0;

        require_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $allowedMimetypes = ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'];
        $imgUrl           = 'blank.png';
        $maxFileSize      = $helper->getConfig('maxfilesize');
        $maxImgWidth      = $helper->getConfig('maximgwidth');
        $maxImgHeight     = $helper->getConfig('maximgheight');
        $uploadDirectory  = XOOPS_ROOT_PATH . '/' . $helper->getConfig('catimage');
        $uploader         = new \XoopsMediaUploader($uploadDirectory, $allowedMimetypes, $maxFileSize, $maxImgWidth, $maxImgHeight);
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            $uploader->setTargetFileName('wfdownloads_' . uniqid(time()) . '--' . strtolower($_FILES['uploadfile']['name']));
            $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
            if (!$uploader->upload()) {
                $errors = $uploader->getErrors();
                redirect_header('javascript:history.go(-1)', 3, $errors);
            } else {
                $imgUrl = $uploader->getSavedFileName();
            }
        } else {
            $imgUrl = (isset($_POST['imgurl'])
                       && 'blank.png' !== $_POST['imgurl']) ? $myts->addSlashes($_POST['imgurl']) : '';
        }

        if (!$cid) {
            $categoryObj = $helper->getHandler('category')->create();
        } else {
            $categoryObj = $helper->getHandler('category')->get($cid);
            $childcats   = $helper->getHandler('category')->getChildCats($categoryObj);
            if ($pid == $cid || in_array($pid, array_keys($childcats))) {
                $categoryObj->setErrors(_AM_WFDOWNLOADS_CCATEGORY_CHILDASPARENT);
            }
        }

        $categoryObj->setVar('title', $_POST['title']);
        $categoryObj->setVar('pid', $pid);
        $categoryObj->setVar('weight', $weight);
        $categoryObj->setVar('imgurl', $imgUrl);
        $categoryObj->setVar('description', $_POST['description']);
        $categoryObj->setVar('summary', $_POST['summary']);
        $categoryObj->setVar('dohtml', isset($_POST['dohtml']));
        $categoryObj->setVar('dosmiley', isset($_POST['dosmiley']));
        $categoryObj->setVar('doxcode', isset($_POST['doxcode']));
        $categoryObj->setVar('doimage', isset($_POST['doimage']));
        $categoryObj->setVar('dobr', isset($_POST['dobr']));
        // Formulize module support (2006/05/04) jpc - start
        if (Wfdownloads\Utility::checkModule('formulize')) {
            $formulize_fid = \Xmf\Request::getInt('formulize_fid', 0, 'POST');
            $categoryObj->setVar('formulize_fid', $formulize_fid);
        }
        // Formulize module support (2006/05/04) jpc - end
        $categoryObj->setVar('spotlighthis', $spotlighthis);
        $categoryObj->setVar('spotlighttop', $spotlighttop);

        if (!$helper->getHandler('category')->insert($categoryObj)) {
            echo $categoryObj->getHtmlErrors();
        }
        if (!$cid) {
            if (0 == $cid) {
                $newid = (int)$categoryObj->getVar('cid');
            }
            Wfdownloads\Utility::savePermissions($down_groups, $newid, 'WFDownCatPerm');
            Wfdownloads\Utility::savePermissions($up_groups, $newid, 'WFUpCatPerm');
            // Notify of new category
            $tags                  = [];
            $tags['CATEGORY_NAME'] = $_POST['title'];
            $tags['CATEGORY_URL']  = WFDOWNLOADS_URL . '/viewcat.php?cid=' . $newid;
            $notificationHandler   = xoops_getHandler('notification');
            $notificationHandler->triggerEvent('global', 0, 'new_category', $tags);
            $database_mess = _AM_WFDOWNLOADS_CCATEGORY_CREATED;
        } else {
            $database_mess = _AM_WFDOWNLOADS_CCATEGORY_MODIFIED;
            Wfdownloads\Utility::savePermissions($down_groups, $cid, 'WFDownCatPerm');
            Wfdownloads\Utility::savePermissions($up_groups, $cid, 'WFUpCatPerm');
        }
        redirect_header($currentFile, 1, $database_mess);
        break;

    case 'category.delete':
    case 'del':
        $cid              = Request::getInt('cid', 0);
        $ok               = Request::getBool('ok', false, 'POST');
        $categoryObjs     = $helper->getHandler('category')->getObjects();
        $categoryObjsTree = new Wfdownloads\ObjectTree($categoryObjs, 'cid', 'pid');
        if (true === $ok) {
            // get all subcategories under the specified category
            $childCategoryObjs = $categoryObjsTree->getAllChild($cid);
            foreach ($childCategoryObjs as $childCategoryObj) {
                // get all category ids
                $cids[] = $childCategoryObj->getVar('cid');
            }
            $cids[] = $cid;

            $criteria = new \Criteria('cid', '(' . implode(',', $cids) . ')', 'IN');

            //get list of downloads in these subcategories
            $downloads = $helper->getHandler('download')->getList($criteria);

            $download_criteria = new \Criteria('lid', '(' . implode(',', array_keys($downloads)) . ')', 'IN');

            // now for each download, delete the text data and vote data associated with the download
            $helper->getHandler('rating')->deleteAll($download_criteria);
            $helper->getHandler('report')->deleteAll($download_criteria);
            $helper->getHandler('download')->deleteAll($download_criteria);
            foreach (array_keys($downloads) as $lid) {
                xoops_comment_delete($helper->getModule()->mid(), (int)$lid);
            }

            // all downloads for each category is deleted, now delete the category data
            $helper->getHandler('category')->deleteAll($criteria);
            $error = _AM_WFDOWNLOADS_DBERROR;

            foreach ($cids as $cid) {
                xoops_groupperm_deletebymoditem($helper->getModule()->mid(), 'WFDownCatPerm', $cid);
                xoops_groupperm_deletebymoditem($helper->getModule()->mid(), 'WFUpCatPerm', $cid);
            }

            redirect_header($currentFile, 1, _AM_WFDOWNLOADS_CCATEGORY_DELETED);
        } else {
            Wfdownloads\Utility::getCpHeader();
            xoops_confirm(['op' => 'category.delete', 'cid' => $cid, 'ok' => true], $currentFile, _AM_WFDOWNLOADS_CCATEGORY_AREUSURE);
            xoops_cp_footer();
        }
        break;

    case 'category.add':
    case 'category.edit':
    case 'modCat':
        Wfdownloads\Utility::getCpHeader();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation($currentFile);

        //$adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->addItemButton(_MI_WFDOWNLOADS_MENU_CATEGORIES, "{$currentFile}?op=categories.list", 'list');
        $adminObject->displayButton('left');

        if (isset($_REQUEST['cid'])) {
            $categoryObj = $helper->getHandler('category')->get($_REQUEST['cid']);
        } else {
            $categoryObj = $helper->getHandler('category')->create();
        }
        $form = $categoryObj->getForm();
        $form->display();

        require_once __DIR__ . '/admin_footer.php';
        break;

    case 'categories.list':
    case 'main':
    default:
        Wfdownloads\Utility::getCpHeader();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation($currentFile);

        //$adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->addItemButton(_AM_WFDOWNLOADS_CCATEGORY_CREATENEW, "{$currentFile}?op=category.add", 'add');
        $adminObject->displayButton('left');

        $totalCategories = Wfdownloads\Utility::categoriesCount();
        if ($totalCategories > 0) {
            $sorted_categories = Wfdownloads\Utility::sortCategories();
            $GLOBALS['xoopsTpl']->assign('sorted_categories', $sorted_categories);
            //            $GLOBALS['xoopsTpl']->assign('securityToken', $GLOBALS['xoopsSecurity']->getTokenHTML());
            $GLOBALS['xoopsTpl']->display("db:{$helper->getModule()->dirname()}_am_categorieslist.tpl");
        } else {
            redirect_header("{$currentFile}?op=category.add", 1, _AM_WFDOWNLOADS_CCATEGORY_NOEXISTS);
        }
        require_once __DIR__ . '/admin_footer.php';
        break;

    case 'categories.reorder':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($currentFile, 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }

        if (isset($_POST['new_weights']) && count($_POST['new_weights']) > 0) {
            $new_weights = $_POST['new_weights'];
            $ids         = [];
            foreach ($new_weights as $cid => $new_weight) {
                $categoryObj = $helper->getHandler('category')->get($cid);
                $categoryObj->setVar('weight', $new_weight);
                if (!$helper->getHandler('category')->insert($categoryObj)) {
                    redirect_header($currentFile, 3, $categoryObj->getErrors());
                }
                unset($categoryObj);
            }
            redirect_header($currentFile, 1, _AM_WFDOWNLOADS_CATEGORIES_REORDERED);
        }
        break;
}
