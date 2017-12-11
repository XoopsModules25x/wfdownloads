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
defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');
require_once __DIR__ . '/common.php';
//@require_once WFDOWNLOADS_ROOT_PATH . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/admin.php';
xoops_loadLanguage('admin', $helper->getModule()->dirname());

/**
 * @param XoopsModule $xoopsModule
 *
 * @return bool
 */
function xoops_module_pre_uninstall_wfdownloads(XoopsModule $xoopsModule)
{
    // NOP
    return true;
}

/**
 * @param XoopsModule $xoopsModule
 */
function xoops_module_uninstall_wfdownloads(XoopsModule $xoopsModule)
{
    // NOP
}
