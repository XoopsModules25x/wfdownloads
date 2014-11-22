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
include_once dirname(__DIR__) . '/include/common.php';

/**
 * Class WfdownloadsIP_Log
 */
class WfdownloadsIP_Log extends XoopsObject
{
    /**
     * @var WfdownloadsWfdownloads
     * @access public
     */
    public $wfdownloads = null;

    /**
     * @param int|null $id
     */
    public function __construct($id = null)
    {
        $this->wfdownloads = WfdownloadsWfdownloads::getInstance();
        $this->db = XoopsDatabaseFactory::getDatabaseConnection();
        $this->initVar('ip_logid', XOBJ_DTYPE_INT);
        $this->initVar('ip_address', XOBJ_DTYPE_TXTBOX);
        $this->initVar('date', XOBJ_DTYPE_INT);
        $this->initVar('lid', XOBJ_DTYPE_INT);
        $this->initVar('uid', XOBJ_DTYPE_INT);

        if (isset($id)) {
            $item = $this->wfdownloads->getHandler('item')->get($id);
            foreach ($item->vars as $k => $v) {
                $this->assignVar($k, $v['value']);
            }
        }
    }
}

/**
 * Class WfdownloadsIP_LogHandler
 */
class WfdownloadsIP_LogHandler extends XoopsPersistableObjectHandler
{
    /**
     * @var WfdownloadsWfdownloads
     * @access public
     */
    public $wfdownloads = null;

    /**
     * @param null|object $db
     */
    public function __construct(&$db)
    {
        parent::__construct($db, 'wfdownloads_ip_log', 'WfdownloadsIP_Log', 'ip_logid');
        $this->wfdownloads = WfdownloadsWfdownloads::getInstance();
    }
}
