<?php

namespace XoopsModules\Wfdownloads;

/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

/**
 * interface Constants
 */
class Constants
{
    /**#@+
     * Constant definition
     */

    public const DISALLOW = 0;
    public const WFDOWNLOADS_DISPLAYICONS_ICON = 1;
    public const WFDOWNLOADS_DISPLAYICONS_TEXT = 2;
    public const WFDOWNLOADS_DISPLAYICONS_NO = 3;
    // CONFIG submissions
    public const WFDOWNLOADS_SUBMISSIONS_NONE = 1;
    public const WFDOWNLOADS_SUBMISSIONS_DOWNLOAD = 2;
    public const WFDOWNLOADS_SUBMISSIONS_MIRROR = 3;
    public const WFDOWNLOADS_SUBMISSIONS_BOTH = 4;
    // CONFIG anonpost
    public const WFDOWNLOADS_ANONPOST_NONE = 1;
    public const WFDOWNLOADS_ANONPOST_DOWNLOAD = 2;
    public const WFDOWNLOADS_ANONPOST_MIRROR = 3;
    public const WFDOWNLOADS_ANONPOST_BOTH = 4;
    // CONFIG autoapprove
    public const WFDOWNLOADS_AUTOAPPROVE_NONE = 1;
    public const WFDOWNLOADS_AUTOAPPROVE_DOWNLOAD = 2;
    public const WFDOWNLOADS_AUTOAPPROVE_MIRROR = 3;
    public const WFDOWNLOADS_AUTOAPPROVE_BOTH = 4;
    // CONFIG autosummary
    public const WFDOWNLOADS_AUTOSUMMARY_NO = 1;
    public const WFDOWNLOADS_AUTOSUMMARY_IFBLANK = 2;
    public const WFDOWNLOADS_AUTOSUMMARY_YES = 3;
    // DOWNLOADS status
    public const WFDOWNLOADS_STATUS_WAITING = 0;
    public const WFDOWNLOADS_STATUS_APPROVED = 1;
    public const WFDOWNLOADS_STATUS_UPDATED = 2;
    public const WFDOWNLOADS_STATUS_PUBLISHED = 3;
    /**#@-*/
}
