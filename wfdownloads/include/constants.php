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
// CONFIG displayicons
define('_WFDOWNLOADS_DISPLAYICONS_ICON', 1);
define('_WFDOWNLOADS_DISPLAYICONS_TEXT', 2);
define('_WFDOWNLOADS_DISPLAYICONS_NO', 3);

// CONFIG submissions
define('_WFDOWNLOADS_SUBMISSIONS_NONE', 1);
define('_WFDOWNLOADS_SUBMISSIONS_DOWNLOAD', 2);
define('_WFDOWNLOADS_SUBMISSIONS_MIRROR', 3);
define('_WFDOWNLOADS_SUBMISSIONS_BOTH', 4);

// CONFIG anonpost
define('_WFDOWNLOADS_ANONPOST_NONE', 1);
define('_WFDOWNLOADS_ANONPOST_DOWNLOAD', 2);
define('_WFDOWNLOADS_ANONPOST_MIRROR', 3);
define('_WFDOWNLOADS_ANONPOST_BOTH', 4);

// CONFIG autoapprove
define('_WFDOWNLOADS_AUTOAPPROVE_NONE', 1);
define('_WFDOWNLOADS_AUTOAPPROVE_DOWNLOAD', 2);
define('_WFDOWNLOADS_AUTOAPPROVE_MIRROR', 3);
define('_WFDOWNLOADS_AUTOAPPROVE_BOTH', 4);

// CONFIG autosummary
define('_WFDOWNLOADS_AUTOSUMMARY_NO', 1);
define('_WFDOWNLOADS_AUTOSUMMARY_IFBLANK', 2);
define('_WFDOWNLOADS_AUTOSUMMARY_YES', 3);

// DOWNLOADS status
define('_WFDOWNLOADS_STATUS_WAITING', 0);
define('_WFDOWNLOADS_STATUS_APPROVED', 1);
define('_WFDOWNLOADS_STATUS_UPDATED', 2);
