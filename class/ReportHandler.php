<?php

namespace XoopsModules\Wfdownloads;

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

use XoopsModules\Wfdownloads;


require_once \dirname(__DIR__) . '/include/common.php';

/**
 * Class ReportHandler
 */
class ReportHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @access public
     */
    public $helper;

    /**
     * @param \XoopsDatabase|null $db
     */
    public function __construct(\XoopsDatabase $db = null)
    {
        parent::__construct($db, 'wfdownloads_broken', Report::class, 'reportid');
        /** @var \XoopsModules\Wfdownloads\Helper $this ->helper */
        $this->helper = \XoopsModules\Wfdownloads\Helper::getInstance();
    }
}
