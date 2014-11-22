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

include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

if ($wfdownloads->getHandler('category')->getCount() == 0) {
    redirect_header('categories.php', 1, _AM_WFDOWNLOADS_CCATEGORY_NOEXISTS);
    exit();
}
$categoryObjObjs = $wfdownloads->getHandler('category')->getObjects();

$WFDownCatPermForm = new XoopsGroupPermForm(
    _AM_WFDOWNLOADS_FCATEGORY_GROUPPROMPT,
    $wfdownloads->getModule()->mid(),
    'WFDownCatPerm',
    _AM_WFDOWNLOADS_PERM_CSELECTPERMISSIONS,
    "admin/{$currentFile}",
    true
);
$WFUpCatPermForm   = new XoopsGroupPermForm(
    _AM_WFDOWNLOADS_FCATEGORY_GROUPPROMPT_UP,
    $wfdownloads->getModule()->mid(),
    'WFUpCatPerm',
    _AM_WFDOWNLOADS_PERM_CSELECTPERMISSIONS_UP,
    "admin/{$currentFile}",
    true
);
foreach ($categoryObjObjs as $categoryObj) {
    $WFDownCatPermForm->addItem($categoryObj->getVar('cid'), $categoryObj->getVar('title'), $categoryObj->getVar('pid'));
    $WFUpCatPermForm->addItem($categoryObj->getVar('cid'), $categoryObj->getVar('title'), $categoryObj->getVar('pid'));
}

wfdownloads_xoops_cp_header();
$indexAdmin = new ModuleAdmin();
echo $indexAdmin->addNavigation($currentFile);

$GLOBALS['xoopsTpl']->assign('down_cat_form', $WFDownCatPermForm->render());
$GLOBALS['xoopsTpl']->assign('up_cat_form', $WFUpCatPermForm->render());

$GLOBALS['xoopsTpl']->display("db:{$wfdownloads->getModule()->dirname()}_am_permissions.tpl");

include_once __DIR__ . '/admin_footer.php';
