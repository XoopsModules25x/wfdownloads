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
require_once __DIR__ . '/../include/common.php';


/**
 * Class CategoryHandler
 */
class CategoryHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @access public
     */
    public $helper = null;

    public $allCategories = false;
    public $topCategories = false;

    /**
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'wfdownloads_cat', Category::class, 'cid', 'title');
        $this->helper = Wfdownloads\Helper::getInstance();
    }

    /**
     * @param        $cid
     * @param string $root_filename
     * @param string $item_filename
     *
     * @return mixed|string
     */
    public function getNicePath($cid, $root_filename = 'index.php', $item_filename = 'viewcat.php?op=')
    {
        require_once WFDOWNLOADS_ROOT_PATH . '/class/xoopstree.php';
        $mytree     = new Wfdownloads\XoopsTree($this->table, $this->keyName, 'pid');
        $pathString = $mytree->getNicePathFromId($cid, $this->identifierName, $item_filename);

        /**
         * Replacing the " with ">" and deleteing the last ">" at the end
         */
        $pathString = trim($pathString);
        $pathString = str_replace(':', '>', $pathString);

        //      $pathString = substr($pathString, 0, strlen($pathString) - 13); // not needed now with fixed icms core! but required for XOOPS
        return $pathString;
    }

    /**
     * Get categories that the current user has permissions for
     *
     * @param bool $id_as_key
     * @param bool $as_object
     *
     * @return array
     */
    public function getUserCategories($id_as_key = false, $as_object = true)
    {
        $grouppermHandler = xoops_getHandler('groupperm');

        $groups                   = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : [0 => XOOPS_GROUP_ANONYMOUS];
        $allowedDownCategoriesIds = $grouppermHandler->getItemIds('WFDownCatPerm', $groups, $this->wfdownloads->getModule()->mid());

        return $this->getObjects(new \Criteria('cid', '(' . implode(',', $allowedDownCategoriesIds) . ')', 'IN'), $id_as_key, $as_object);
    }

    /**
     * Get categories that the current user has permissions for
     *
     * @param bool $id_as_key
     * @param bool $as_object
     *
     * @return array
     */
    public function getUserDownCategories($id_as_key = false, $as_object = true)
    {
        $grouppermHandler = xoops_getHandler('groupperm');

        $groups                   = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : [0 => XOOPS_GROUP_ANONYMOUS];
        $allowedDownCategoriesIds = $grouppermHandler->getItemIds('WFDownCatPerm', $groups, $this->wfdownloads->getModule()->mid());

        return $this->getObjects(new \Criteria('cid', '(' . implode(',', $allowedDownCategoriesIds) . ')', 'IN'), $id_as_key, $as_object);
    }

    /**
     * @param bool $id_as_key
     * @param bool $as_object
     *
     * @return array
     */
    public function getUserUpCategories($id_as_key = false, $as_object = true)
    {
        $grouppermHandler = xoops_getHandler('groupperm');

        $groups                 = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $allowedUpCategoriesIds = $grouppermHandler->getItemIds('WFUpCatPerm', $groups, $this->wfdownloads->getModule()->mid());

        return $this->getObjects(new \Criteria('cid', '(' . implode(',', $allowedUpCategoriesIds) . ')', 'IN'), $id_as_key, $as_object);
    }

    /**
     * @param $category
     *
     * @return array
     */
    public function getChildCats($category)
    {
        $categoryObjs =& $this->getObjects();
        require_once XOOPS_ROOT_PATH . '/class/tree.php';
        $categoryObjsTree = new Wfdownloads\ObjectTree($categoryObjs, $this->keyName, 'pid');

        return $categoryObjsTree->getAllChild($category->getVar($this->keyName));
    }

    /**
     * @return array
     */
    public function getAllSubcatsTopParentCid()
    {
        if (!$this->allCategories) {
            $this->allCategories =& $this->getObjects(null, true);
        }

        require_once XOOPS_ROOT_PATH . '/class/tree.php';
        $categoryObjsTree = new Wfdownloads\ObjectTree($this->allCategories, $this->keyName, 'pid');

        $allsubcats_linked_totop = [];
        foreach ($this->allCategories as $cid => $category) {
            $parentCategoryObjs = $categoryObjsTree->getAllParent($cid);
            if (0 == count($parentCategoryObjs)) {
                // is a top category
                $allsubcats_linked_totop[$cid] = $cid;
            } else {
                // is not a top category
                $topParentCategoryObj          = end($parentCategoryObjs);
                $allsubcats_linked_totop[$cid] = $topParentCategoryObj->getVar($cid);
            }
            unset($parentCategoryObjs);
        }

        return $allsubcats_linked_totop;
        /*
                $categoryObjsTreeNodes = $categoryObjsTree->getTree();

                // Let's create an array where key will be cid of the top categories and
                // value will be an array containing all the cid of its subcategories
                // If value = 0, then this topcat does not have any subcats
                $topCategories = array();
                foreach ($categoryObjsTreeNodes[0]['child'] as $topCategory_cid) {
                    if (!isset($this->topCategories[$topCategory_cid])) {
                        $this->topCategories[$topCategory_cid] = $topCategory_cid;
                    }
                    foreach ($categoryObjsTree->getAllChild($topCategory_cid) as $key => $childCategory) {
                        $childCategory_cids[] = $childCategory->getVar('cid');
                    }
                    $childCategory_cids = isset($childCategory_cids) ? $childCategory_cids : 0;
                    $topCategories[$topCategory_cid] = $childCategory_cids;
                    unset($childCategory_cids);
                }

                // Now we need to create another array where key will be all subcategories cid and
                // value will be the cid of its top most category
                $allsubcats_linked_totop = array();

                foreach ($topCategories as $topCategory_cid => $childCategory_cids) {
                    if ($childCategory_cids == 0) {
                        $allsubcats_linked_totop[$topCategory_cid] = $topCategory_cid;
                    } else {
                        foreach ($childCategory_cids as $childCategory_cid) {
                            $allsubcats_linked_totop[$childCategory_cid] = $topCategory_cid;
                        }
                    }
                }

                // Finally, let's finish by adding to this array, all the top categories which values
                // will be their cid
                  foreach ($topCategories as $topCategory_cid) {
                    $allsubcats_linked_totop[$topCategory_cid] = $topCategory_cid;
                }

                return $allsubcats_linked_totop;
        */
    }
}
