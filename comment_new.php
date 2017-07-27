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

$currentFile = basename(__FILE__);
require_once __DIR__ . '/header.php';

$com_itemid = Request::getInt('com_itemid', 0);
if ($com_itemid > 0) {
    // Get file title
    $downloadObj    = $wfdownloads->getHandler('download')->get($com_itemid);
    $com_replytitle = $downloadObj->getVar('title');
    require_once XOOPS_ROOT_PATH . '/include/comment_new.php';
}
