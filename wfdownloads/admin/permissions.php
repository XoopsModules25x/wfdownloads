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

include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

if ($wfdownloads->getHandler('category')->getCount() == 0) {
    redirect_header('categories.php', 1, _AM_WFDOWNLOADS_CCATEGORY_NOEXISTS);
    exit();
}
$categories = $wfdownloads->getHandler('category')->getObjects();

$down_cat_form = new XoopsGroupPermForm(_AM_WFDOWNLOADS_FCATEGORY_GROUPPROMPT, $wfdownloads->getModule()->mid(
), 'WFDownCatPerm', _AM_WFDOWNLOADS_PERM_CSELECTPERMISSIONS, "admin/{$currentFile}", true);
$up_cat_form   = new XoopsGroupPermForm(_AM_WFDOWNLOADS_FCATEGORY_GROUPPROMPT_UP, $wfdownloads->getModule()->mid(
), 'WFUpCatPerm', _AM_WFDOWNLOADS_PERM_CSELECTPERMISSIONS_UP, "admin/{$currentFile}", true);
foreach ($categories as $category) {
    $down_cat_form->addItem($category->getVar('cid'), $category->getVar('title'), $category->getVar('pid'));
    $up_cat_form->addItem($category->getVar('cid'), $category->getVar('title'), $category->getVar('pid'));
}

wfdownloads_xoops_cp_header();
$indexAdmin = new ModuleAdmin();
echo $indexAdmin->addNavigation($currentFile);

$GLOBALS['xoopsTpl']->assign('down_cat_form', $down_cat_form->render());
$GLOBALS['xoopsTpl']->assign('up_cat_form', $up_cat_form->render());

$GLOBALS['xoopsTpl']->display("db:{$wfdownloads->getModule()->dirname()}_am_permissions.tpl");

include 'admin_footer.php';
