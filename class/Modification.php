<?php namespace XoopsModules\Wfdownloads;

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

use XoopsModules\Wfdownloads;

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');
require_once  dirname(__DIR__) . '/include/common.php';

//require_once XOOPS_ROOT_PATH . '/modules/wfdownloads/class/Download.php';

/**
 * Class Modification
 */
class Modification extends Wfdownloads\Download
{
    /**
     * @param int|null $id
     */
    public function __construct($id = null)
    {
        parent::__construct();
        $this->initVar('requestid', XOBJ_DTYPE_INT);
        //
        $this->initVar('modifysubmitter', XOBJ_DTYPE_INT, 0);
        $this->initVar('requestdate', XOBJ_DTYPE_INT, 0);
        //
        $this->initVar('lid', XOBJ_DTYPE_INT);
        $this->initVar('cid', XOBJ_DTYPE_INT, 0);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('url', XOBJ_DTYPE_URL, 'http://');
        $this->initVar('filename', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('filetype', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('homepage', XOBJ_DTYPE_URL, 'http://');
        $this->initVar('version', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('size', XOBJ_DTYPE_INT, 0);
        $this->initVar('platform', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('screenshot', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('screenshot2', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('screenshot3', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('screenshot4', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('submitter', XOBJ_DTYPE_INT);
        $this->initVar('publisher', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('status', XOBJ_DTYPE_INT, _WFDOWNLOADS_STATUS_WAITING);
        $this->initVar('date', XOBJ_DTYPE_INT);
        $this->initVar('hits', XOBJ_DTYPE_INT, 0);
        $this->initVar('rating', XOBJ_DTYPE_OTHER, 0.0);
        $this->initVar('votes', XOBJ_DTYPE_INT, 0);
        $this->initVar('comments', XOBJ_DTYPE_INT, 0);
        $this->initVar('license', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('mirror', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('price', XOBJ_DTYPE_TXTBOX, 0);
        $this->initVar('paypalemail', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('features', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('requirements', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('homepagetitle', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('forumid', XOBJ_DTYPE_INT, 0);
        $this->initVar('limitations', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('versiontypes', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('dhistory', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('published', XOBJ_DTYPE_INT, 0); // published time or 0
        $this->initVar('expired', XOBJ_DTYPE_INT, 0);
        $this->initVar('updated', XOBJ_DTYPE_INT, 0); // uploaded time or 0
        $this->initVar('offline', XOBJ_DTYPE_INT, false); // boolean
        $this->initVar('summary', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('description', XOBJ_DTYPE_TXTAREA, '');
        //        $this->initVar('ipaddress', XOBJ_DTYPE_TXTBOX, '');
        //        $this->initVar('notifypub', XOBJ_DTYPE_INT, 0);
        // added 3.23
        $this->initVar('screenshots', XOBJ_DTYPE_ARRAY, []); // IN PROGRESS
        $this->initVar('dohtml', XOBJ_DTYPE_INT, false); // boolean
        $this->initVar('dosmiley', XOBJ_DTYPE_INT, true); // boolean
        $this->initVar('doxcode', XOBJ_DTYPE_INT, true); // boolean
        $this->initVar('doimage', XOBJ_DTYPE_INT, true); // boolean
        $this->initVar('dobr', XOBJ_DTYPE_INT, true); // boolean

        //Obsolete
        unset($this->vars['ipaddress'], $this->vars['notifypub']);

        if (null !== $id) {
            $item = $this->helper->getHandler('Item')->get($id);
            foreach ($item->vars as $k => $v) {
                $this->assignVar($k, $v['value']);
            }
        }
    }
}
