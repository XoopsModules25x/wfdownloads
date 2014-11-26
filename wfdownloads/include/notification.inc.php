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
 *
 * @param $category
 * @param $item_id
 *
 * @return null
 */
function wfdownloads_notify_iteminfo($category, $item_id)
{
    global $xoopsModule, $xoopsModuleConfig;

    if (empty($xoopsModule) || $xoopsModule->dirname() != 'wfdownloads') {
        $module_handler = xoops_gethandler('module');
        $module = $module_handler->getByDirname('wfdownloads');
        $config_handler = xoops_gethandler('config');
        $config = $config_handler->getConfigsByCat(0, (int) $module->mid());
    } else {
        $module = $xoopsModule;
        $config = $xoopsModuleConfig;
    }
    if ($category == 'global') {
        $item['name'] = '';
        $item['url'] = '';

        return $item;
    }
    if ($category == 'category') {
        // Assume we have a valid category id
        $sql = "SELECT title FROM " . $GLOBALS['xoopsDB']->prefix('wfdownloads_cat') . " WHERE cid = '" . (int) $item_id . "'";
        $result = $GLOBALS['xoopsDB']->query($sql); // TODO: error check
        $result_array = $GLOBALS['xoopsDB']->fetchArray($result);
        $item['name'] = $result_array['title'];
        $item['url'] = WFDOWNLOADS_URL . '/viewcat.php?cid=' . (int) $item_id;

        return $item;
    }
    if ($category == 'file') {
        // Assume we have a valid file id
        $sql = "SELECT cid,title FROM " . $GLOBALS['xoopsDB']->prefix('wfdownloads_downloads') . " WHERE lid = '" . (int) $item_id . "'";
        $result = $GLOBALS['xoopsDB']->query($sql); // TODO: error check
        $result_array = $GLOBALS['xoopsDB']->fetchArray($result);
        $item['name'] = $result_array['title'];
        $item['url'] = WFDOWNLOADS_URL . '/singlefile.php?cid=' . (int) $result_array['cid'] . '&amp;lid=' . (int) $item_id;

        return $item;
    }

    return null;
}
