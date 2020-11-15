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
 * Class RatingHandler
 */
class RatingHandler extends \XoopsPersistableObjectHandler
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
        parent::__construct($db, 'wfdownloads_votedata', Rating::class, 'ratingid');
        $this->helper = Helper::getInstance();
    }

    /**
     * Get average ratings of users matching a condition
     *
     * @param \CriteriaElement|\CriteriaCompo $criteria {@link \CriteriaElement} to match
     *
     * @return array|int
     */
    public function getUserAverage($criteria = null)
    {
        $groupby = false;
        $field   = '';
        if (null !== $criteria && $criteria instanceof \CriteriaElement) {
            if ('' != $criteria->groupby) {
                $groupby = true;
                $field   = $criteria->groupby . ', '; //Not entirely secure unless you KNOW that no criteria's groupby clause is going to be mis-used
            }
        }
        $sql = "SELECT {$field} AVG(rating), count(*)";
        $sql .= " FROM {$this->table}";
        if (null !== $criteria && $criteria instanceof \CriteriaElement) {
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
            [$average, $count] = $this->db->fetchRow($result);

            return [
                'avg'   => $average,
                'count' => $count,
            ];
        }
        $ret = [];
        while (list($id, $average, $count) = $this->db->fetchRow($result)) {
            $ret[$id] = [
                'avg'   => $average,
                'count' => $count,
            ];
        }

        return $ret;
    }
}
