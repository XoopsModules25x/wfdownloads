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
 * Class RatingHandler
 */
class RatingHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @access public
     */
    public $wfdownloads = null;

    /**
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'wfdownloads_votedata', 'Rating', 'ratingid');
        $this->wfdownloads = wfdownloads\Helper::getInstance();
    }

    /**
     * Get average ratings of users matching a condition
     *
     * @param CriteriaElement $criteria {@link CriteriaElement} to match
     *
     * @return array|int
     */
    public function getUserAverage($criteria = null)
    {
        $groupby = false;
        $field   = '';
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            if ('' != $criteria->groupby) {
                $groupby = true;
                $field   = $criteria->groupby . ', '; //Not entirely secure unless you KNOW that no criteria's groupby clause is going to be mis-used
            }
        }
        $sql = "SELECT {$field} AVG(rating), count(*)";
        $sql .= " FROM {$this->table}";
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
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
            list($average, $count) = $this->db->fetchRow($result);

            return [
                'avg'   => $average,
                'count' => $count
            ];
        } else {
            $ret = [];
            while (false !== (list($id, $average, $count) = $this->db->fetchRow($result))) {
                $ret[$id] = [
                    'avg'   => $average,
                    'count' => $count
                ];
            }

            return $ret;
        }
    }
}
