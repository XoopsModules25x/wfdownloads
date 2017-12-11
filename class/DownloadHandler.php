<?php namespace Xoopsmodules\wfdownloads;
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

use Xoopsmodules\wfdownloads;

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');
require_once __DIR__ . '/../include/common.php';

/**
 * Class DownloadHandler
 */
class DownloadHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @access public
     */
    public $wfdownloads = null;

    /**
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'wfdownloads_downloads', 'Download', 'lid', 'title');
        $this->wfdownloads = wfdownloads\Helper::getInstance();
    }

    /**
     * Get maximum published date from a criteria
     *
     * @param CriteriaElement $criteria
     *
     * @return mixed
     */
    public function getMaxPublishdate($criteria = null)
    {
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            if ('' != $criteria->groupby) {
                $groupby = true;
                $field   = $criteria->groupby . ', '; //Not entirely secure unless you KNOW that no criteria's groupby clause is going to be mis-used
            }
        }
        $sql = 'SELECT ' . $field . 'MAX(published) FROM ' . $this->table;
        if (is_object($criteria)) {
            $sql .= ' ' . $criteria->renderWhere();
            if ('' != $criteria->groupby) {
                $sql .= $criteria->getGroupby();
            }
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        if (false === $groupby) {
            list($count) = $this->db->fetchRow($result);

            return $count;
        } else {
            $ret = [];
            while (false !== (list($id, $count) = $this->db->fetchRow($result))) {
                $ret[$id] = $count;
            }

            return $ret;
        }
    }

    /**
     * Get criteria for active downloads
     *
     * @return \CriteriaCompo
     */
    public function getActiveCriteria()
    {
        $gpermHandler = xoops_getHandler('groupperm');

        $criteria = new \CriteriaCompo(new \Criteria('offline', false));
        $criteria->add(new \Criteria('published', 0, '>'));
        $criteria->add(new \Criteria('published', time(), '<='));
        $expiredCriteria = new \CriteriaCompo(new \Criteria('expired', 0));
        $expiredCriteria->add(new \Criteria('expired', time(), '>='), 'OR');
        $criteria->add($expiredCriteria);
        // add criteria for categories that the user has permissions for
        $groups                   = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : [0 => XOOPS_GROUP_ANONYMOUS];
        $allowedDownCategoriesIds = $gpermHandler->getItemIds('WFDownCatPerm', $groups, $this->wfdownloads->getModule()->mid());
        $criteria->add(new \Criteria('cid', '(' . implode(',', $allowedDownCategoriesIds) . ')', 'IN'));

        return $criteria;
    }

    /**
     * Get array of active downloads with optional additional criteria
     *
     * @param \CriteriaCompo $crit Additional criteria
     *
     * @return array
     */
    public function getActiveDownloads(\CriteriaCompo $crit = null)
    {
        if (is_object($crit)) {
            $criteria = $crit;
        } else {
            $criteria = new \CriteriaCompo();
        }
        $active_crit = $this->getActiveCriteria();
        $criteria->add($active_crit);

        return $this->getObjects($criteria);
    }

    /**
     * Get count of active downloads
     *
     * @param CriteriaElement $crit Additional criteria
     *
     * @return int /int
     */
    public function getActiveCount($crit = null)
    {
        $criteria = $this->getActiveCriteria();
        if (is_object($crit)) {
            $criteria->add($crit);
        }

        return $this->getCount($criteria);
    }

    /**
     * Increment hit counter for a download
     *
     * @param int $lid
     *
     * @return bool
     */
    public function incrementHits($lid)
    {
        $sql = 'UPDATE ' . $this->table . " SET hits=hits+1 WHERE lid='" . (int)$lid . "'";

        return $this->db->queryF($sql);
    }

    /**
     * @param \XoopsObject $download
     * @param bool        $force
     *
     * @return bool
     */
    public function delete(\XoopsObject $download, $force = false)
    {
        if (parent::delete($download, $force)) {
            $criteria = new \Criteria('lid', (int)$download->getVar('lid'));
            $this->wfdownloads->getHandler('rating')->deleteAll($criteria);
            $this->wfdownloads->getHandler('mirror')->deleteAll($criteria);
            $this->wfdownloads->getHandler('review')->deleteAll($criteria);
            $this->wfdownloads->getHandler('report')->deleteAll($criteria);
            // delete comments
            xoops_comment_delete((int)$this->wfdownloads->getModule()->mid(), (int)$download->getVar('lid'));

            // Formulize module support (2006/05/04) jpc - start
            if (wfdownloads\Utility::checkModule('formulize')) {
                if (file_exists(XOOPS_ROOT_PATH . '/modules/formulize/include/functions.php') && $download->getVar('formulize_idreq') > 0) {
                    require_once XOOPS_ROOT_PATH . '/modules/formulize/include/functions.php';
                    //deleteFormEntries(array($download->getVar('formulize_idreq')));
                    $category = $this->wfdownloads->getHandler('category')->get($download->getVar('cid'));
                    deleteFormEntries([$download->getVar('formulize_idreq')], $category->getVar('formulize_fid'));
                }
            }

            // Formulize module support (2006/05/04) jpc - end
            return true;
        }

        return false;
    }
}
