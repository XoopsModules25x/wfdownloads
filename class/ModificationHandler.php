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
 * Class ModificationHandler
 */
class ModificationHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @access public
     */
    public $helper;

    /**
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'wfdownloads_mod', Modification::class, 'requestid', 'title');
        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $this->helper = \XoopsModules\Wfdownloads\Helper::getInstance();
    }

    /**
     * @param $requestid
     *
     * @return bool
     */
    public function approveModification($requestid)
    {
        $sql = "UPDATE {$this->table} m, {$this->helper->getHandler('Download')->table} d";
        $sql .= " SET
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
            return $this->deleteAll(new \Criteria('requestid', (int)$requestid));
        }

        return false;
    }
}
