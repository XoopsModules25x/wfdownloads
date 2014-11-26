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

require_once XOOPS_ROOT_PATH . '/modules/wfdownloads/class/download.php';

/**
 * Class WfdownloadsModification
 */
class WfdownloadsModification extends WfdownloadsDownload
{
    /**
     * @param int|null $id
     */
    public function __construct($id = null)
    {
        $this->WfdownloadsDownload();
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
        $this->initVar('screenshots', XOBJ_DTYPE_ARRAY, array()); // IN PROGRESS
        $this->initVar('dohtml', XOBJ_DTYPE_INT, false); // boolean
        $this->initVar('dosmiley', XOBJ_DTYPE_INT, true); // boolean
        $this->initVar('doxcode', XOBJ_DTYPE_INT, true); // boolean
        $this->initVar('doimage', XOBJ_DTYPE_INT, true); // boolean
        $this->initVar('dobr', XOBJ_DTYPE_INT, true); // boolean

        //Obsolete
        unset($this->vars['ipaddress']);
        unset($this->vars['notifypub']);

        if (isset($id)) {
            $item = $this->wfdownloads->getHandler('item')->get($id);
            foreach ($item->vars as $k => $v) {
                $this->assignVar($k, $v['value']);
            }
        }
    }
}

/**
 * Class WfdownloadsModificationHandler
 */
class WfdownloadsModificationHandler extends XoopsPersistableObjectHandler
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
        parent::__construct($db, 'wfdownloads_mod', 'WfdownloadsModification', 'requestid', 'title');
        $this->wfdownloads = WfdownloadsWfdownloads::getInstance();
    }

    /**
     * @param $requestid
     *
     * @return bool
     */
    function approveModification($requestid)
    {
        $sql = "UPDATE {$this->table} m, {$this->wfdownloads->getHandler('download')->table} d";
        $sql
            .= " SET
            d.cid = m.cid,
            d.title = m.title,
            d.url = m.url,
            d.filename = m.filename,
            d.filetype = m.filetype,
            d.mirror = m.mirror,
            d.license = m.license,
            d.features = m.features,
            d.homepage = m.homepage,
            d.version = m.version,
            d.size = m.size,
            d.platform = m.platform,
            d.screenshot = m.screenshot,
            d.screenshot2 = m.screenshot2,
            d.screenshot3 = m.screenshot3,
            d.screenshot4 = m.screenshot4,
            d.publisher = m.publisher,
            d.status = '" . _WFDOWNLOADS_STATUS_UPDATED . "',
            d.price = m.price,
            d.requirements = m.requirements,
            d.homepagetitle = m.homepagetitle,
            d.limitations = m.limitations,
            d.versiontypes = m.versiontypes,
            d.dhistory = m.dhistory,
            d.updated = m.updated,
            d.summary = m.summary,
            d.description = m.description,
            d.screenshots = m.screenshots,
            d.dohtml = m.dohtml,
            d.dosmiley = m.dosmiley,
            d.doxcode = m.doxcode,
            d.doimage = m.doimage,
            d.dobr = m.dobr";
        $sql .= " WHERE d.lid = m.lid AND m.requestid='{$requestid}'";
        if ($this->db->query($sql)) {
            return $this->deleteAll(new Criteria('requestid', (int)$requestid));
        }

        return false;
    }
}
