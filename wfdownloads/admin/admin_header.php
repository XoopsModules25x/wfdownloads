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
include_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';
include_once dirname(__DIR__) . '/include/common.php';

// Include xoops admin header
include_once XOOPS_ROOT_PATH . '/include/cp_header.php';

$pathIcon16 = XOOPS_URL . '/' . $wfdownloads->getModule()->getInfo('icons16');
$pathIcon32 = XOOPS_URL . '/' . $wfdownloads->getModule()->getInfo('icons32');
$pathModuleAdmin = XOOPS_ROOT_PATH . '/' . $wfdownloads->getModule()->getInfo('dirmoduleadmin');
require_once $pathModuleAdmin . '/moduleadmin/moduleadmin.php';

// Load language files
xoops_loadLanguage('admin', $wfdownloads->getModule()->dirname());
xoops_loadLanguage('modinfo', $wfdownloads->getModule()->dirname());
xoops_loadLanguage('main', $wfdownloads->getModule()->dirname());

if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
    include_once XOOPS_ROOT_PATH . '/class/template.php';
    $xoopsTpl = new XoopsTpl();
}
