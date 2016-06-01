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

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');
include_once __DIR__ . '/common.php';

// comment callback functions

/**
 * @param $download_id
 * @param $commentCount
 */
function wfdownloads_com_update($download_id, $commentCount)
{
    $wfdownloads = WfdownloadsWfdownloads::getInstance();
    $wfdownloads->getHandler('download')->updateAll('comments', (int) $commentCount, new Criteria('lid', (int) $download_id));
}

/**
 * @param $comment
 */
function wfdownloads_com_approve(&$comment)
{
    // notification mail here
}
