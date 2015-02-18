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
 * Class WfdownloadsRating
 */
class WfdownloadsRating extends XoopsObject
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
        $this->initVar('ratingid', XOBJ_DTYPE_INT);
        $this->initVar('lid', XOBJ_DTYPE_INT);
        $this->initVar('ratinguser', XOBJ_DTYPE_INT);
        $this->initVar('rating', XOBJ_DTYPE_INT);
        $this->initVar('ratinghostname', XOBJ_DTYPE_TXTBOX);
        $this->initVar('ratingtimestamp', XOBJ_DTYPE_INT);

        if (isset($id)) {
            $item = $this->wfdownloads->getHandler('item')->get($id);
            foreach ($item->vars as $k => $v) {
                $this->assignVar($k, $v['value']);
            }
        }
    }
}

/**
 * Class WfdownloadsRatingHandler
 */
class WfdownloadsRatingHandler extends XoopsPersistableObjectHandler
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
        parent::__construct($db, 'wfdownloads_votedata', 'WfdownloadsRating', 'ratingid');
        $this->wfdownloads = WfdownloadsWfdownloads::getInstance();
    }

    /**
     * Get average ratings of users matching a condition
     *
     * @param object $criteria {@link CriteriaElement} to match
     *
     * @return array/int
     */
    function getUserAverage($criteria = null)
    {
        $groupby = false;
        $field   = '';
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            if ($criteria->groupby != '') {
                $groupby = true;
                $field   = $criteria->groupby . ', '; //Not entirely secure unless you KNOW that no criteria's groupby clause is going to be mis-used
            }
        }
        $sql = "SELECT {$field} AVG(rating), count(*)";
        $sql .= " FROM {$this->table}";
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ($criteria->groupby != '') {
                $sql .= $criteria->getGroupby();
            }
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        if ($groupby == false) {
            list($average, $count) = $this->db->fetchRow($result);

            return array(
                'avg' => $average,
                'count' => $count
            );
        } else {
            $ret = array();
            while (list($id, $average, $count) = $this->db->fetchRow($result)) {
                $ret[$id] = array(
                    'avg'=> $average,
                    'count' => $count
                );
            }

            return $ret;
        }
    }
}
