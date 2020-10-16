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
// Module info/menu
$moduleInfo = $helper->getModule()->getInfo();
//$xoopsTpl->assign('wfdownloadModuleInfo', $moduleInfo); // huge array but useful?
$xoopsTpl->assign('wfdownloadModuleInfoSub', $moduleInfo['sub']);
//display type
$xoopsTpl->assign('displayType', $helper->getConfig('idxcat_items_display_type'));
//$xoopsTpl->assign('display_category_summary', $helper->getConfig('cat_display_summary'));
$xoopsTpl->assign('displayList', 'list' === $helper->getConfig('idxcat_items_display_type'));
$xoopsTpl->assign('displayFull', 'full' === $helper->getConfig('idxcat_items_display_type'));
$xoopsTpl->assign('displayLegacy', 'legacy' === $helper->getConfig('idxcat_items_display_type'));
$xoopsTpl->assign('displayCatview', 'catview' === $helper->getConfig('idxcat_items_display_type'));
$xoopsTpl->assign('module_dirname', $helper->getDirname());
$xoopsTpl->assign('showAlphabet', $helper->getConfig('showAlphabet'));
$xoopsTpl->assign('showNewArrivals', $helper->getConfig('showNewArrivals'));

// Module admin
$xoopsTpl->assign('isAdmin', $helper->isUserAdmin());
$xoopsTpl->assign('wfdownloads_adminpage', "<a href='" . WFDOWNLOADS_URL . "/admin/index.php'>" . _MD_WFDOWNLOADS_ADMIN_PAGE . '</a>');
// Extra info
$xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');  // this definition is not removed for backward compatibility issues
$xoopsTpl->assign('ref_smartfactory', 'WFDownloads is developed by The SmartFactory (http://www.smartfactory.ca), a division of InBox Solutions (http://www.inboxsolutions.net)');

require_once XOOPS_ROOT_PATH . '/footer.php';
