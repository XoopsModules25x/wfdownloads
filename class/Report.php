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

require_once \dirname(__DIR__) . '/include/common.php';

/**
 * Class Report
 */
class Report extends \XoopsObject
{
    /**
     * @access public
     */
    public $helper;
    public $db;

    /**
     * @param int|null $id
     */
    public function __construct($id = null)
    {
        $this->helper = Helper::getInstance();
        $this->db     = \XoopsDatabaseFactory::getDatabaseConnection();
        $this->initVar('reportid', \XOBJ_DTYPE_INT);
        $this->initVar('lid', \XOBJ_DTYPE_INT);
        $this->initVar('sender', \XOBJ_DTYPE_INT);
        $this->initVar('date', \XOBJ_DTYPE_INT);
        $this->initVar('ip', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('confirmed', \XOBJ_DTYPE_INT);
        $this->initVar('acknowledged', \XOBJ_DTYPE_INT);

        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (isset($id)) {
            $item = $this->helper->getHandler('Item')->get($id);
            foreach ($item->vars as $k => $v) {
                $this->assignVar($k, $v['value']);
            }
        }
    }
}
