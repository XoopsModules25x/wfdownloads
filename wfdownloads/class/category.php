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
 * Class WfdownloadsCategory
 */
class WfdownloadsCategory extends XoopsObject
{
    /**
     * @var WfdownloadsWfdownloads
     * @access public
     */
    public $wfdownloads = null;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->wfdownloads = WfdownloadsWfdownloads::getInstance();
        $this->db          = XoopsDatabaseFactory::getDatabaseConnection();
        $this->initVar('cid', XOBJ_DTYPE_INT);
        $this->initVar('pid', XOBJ_DTYPE_INT, 0);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('imgurl', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('description', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('total', XOBJ_DTYPE_INT, 0);
        $this->initVar('summary', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('spotlighttop', XOBJ_DTYPE_INT, 0);
        $this->initVar('spotlighthis', XOBJ_DTYPE_INT, 0);
        $this->initVar('dohtml', XOBJ_DTYPE_INT, false); // boolean
        $this->initVar('dosmiley', XOBJ_DTYPE_INT, true); // boolean
        $this->initVar('doxcode', XOBJ_DTYPE_INT, true); // boolean
        $this->initVar('doimage', XOBJ_DTYPE_INT, true); // boolean
        $this->initVar('dobr', XOBJ_DTYPE_INT, true); // boolean
        $this->initVar('weight', XOBJ_DTYPE_INT, 0);
        // Formulize module support (2006/05/04) jpc - start
        $this->initVar('formulize_fid', XOBJ_DTYPE_INT, 0);
    }

    /**
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        $arg = isset($args[0]) ? $args[0] : null;

        return $this->getVar($method, $arg);
    }

    /**
     * @param bool $action
     *
     * @return XoopsThemeForm
     */
    function getForm($action = false)
    {
        $gperm_handler = xoops_gethandler('groupperm');

        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $title = $this->isNew() ? _AM_WFDOWNLOADS_CCATEGORY_CREATENEW : _AM_WFDOWNLOADS_CCATEGORY_MODIFY;

        include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        include_once WFDOWNLOADS_ROOT_PATH . '/class/wfdownloads_lists.php';

        $form = new XoopsThemeForm($title, 'form_error', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // category: title
        $form->addElement(new XoopsFormText(_AM_WFDOWNLOADS_FCATEGORY_TITLE, 'title', 50, 255, $this->getVar('title', 'e')), true);
        // category: pid
        if (wfdownloads_categoriesCount() > 0) {
            $categoryObjs = $this->wfdownloads->getHandler('category')->getObjects();
            $categoryObjsTree = new XoopsObjectTree($categoryObjs, 'cid', 'pid');
            $form->addElement(new XoopsFormLabel(_AM_WFDOWNLOADS_FCATEGORY_SUBCATEGORY, $categoryObjsTree->makeSelBox('pid', 'title', '-', $this->getVar('pid', 'e'), true)));
        }
        // category: weight
        $form->addElement(new XoopsFormText(_AM_WFDOWNLOADS_FCATEGORY_WEIGHT, 'weight', 11, 11, $this->getVar('weight')), false);
        // permission: WFDownCatPerm
        $groups = $gperm_handler->getGroupIds('WFDownCatPerm', $this->getVar('cid'), $this->wfdownloads->getModule()->mid());
        $groups_down_select = new XoopsFormSelectGroup(_AM_WFDOWNLOADS_FCATEGORY_GROUPPROMPT, 'groups', true, $groups, 5, true);
        $groups_down_select->setDescription(_AM_WFDOWNLOADS_FCATEGORY_GROUPPROMPT_DESC);
        $form->addElement($groups_down_select);
        // permission: WFUpCatPerm
        $up_groups = $gperm_handler->getGroupIds('WFUpCatPerm', $this->getVar('cid'), $this->wfdownloads->getModule()->mid());
        $groups_up_select = new XoopsFormSelectGroup(_AM_WFDOWNLOADS_FCATEGORY_GROUPPROMPT_UP, 'up_groups', true, $up_groups, 5, true);
        $groups_up_select->setDescription(_AM_WFDOWNLOADS_FCATEGORY_GROUPPROMPT_UP_DESC);
        $form->addElement($groups_up_select);
        // category: imgurl
        $imgurl_path = $this->getVar('imgurl') ? $this->wfdownloads->getConfig('catimage') . '/' . $this->getVar('imgurl') : WFDOWNLOADS_IMAGES_URL . '/blank.gif';
        $imgurl_tray = new XoopsFormElementTray(_AM_WFDOWNLOADS_FCATEGORY_CIMAGE, '<br />');
            $imgurl_tray->addElement(new XoopsFormLabel(_AM_WFDOWNLOADS_DOWN_FUPLOADPATH, XOOPS_ROOT_PATH . '/' . $this->wfdownloads->getConfig('catimage')));
            $imgurl_tray->addElement(new XoopsFormLabel(_AM_WFDOWNLOADS_DOWN_FUPLOADURL, XOOPS_URL . '/' . $this->wfdownloads->getConfig('catimage')));
                $graph_array = WfsLists::getListTypeAsArray(XOOPS_ROOT_PATH . '/' . $this->wfdownloads->getConfig('catimage'), 'images');
                $imgurl_select = new XoopsFormSelect('', 'imgurl', $this->getVar('imgurl'));
                $imgurl_select->addOptionArray($graph_array);
                $imgurl_select->setExtra("onchange='showImgSelected(\"image\", \"imgurl\", \"" . $this->wfdownloads->getConfig('catimage') . "\", \"\", \"" . XOOPS_URL . "\")'");
            $imgurl_tray->addElement($imgurl_select, false);
            $imgurl_tray->addElement(new XoopsFormLabel( '', "<img src='" . XOOPS_URL . "/" . $imgurl_path . "' name='image' id='image' alt='' />"));
            $imgurl_tray->addElement(new XoopsFormFile(_AM_WFDOWNLOADS_BUPLOAD , 'uploadfile', 0), false);
        $form->addElement($imgurl_tray);
        // category: description
        $description_textarea = new XoopsFormDhtmlTextArea(_AM_WFDOWNLOADS_FCATEGORY_DESCRIPTION, 'description', $this->getVar('description', 'e'), 15, 60);
        $description_textarea->setDescription(_AM_WFDOWNLOADS_FCATEGORY_DESCRIPTION_DESC);
        $form->addElement($description_textarea, true);
        // category: summary
        $summary_textarea = new XoopsFormTextArea(_AM_WFDOWNLOADS_FCATEGORY_SUMMARY, 'summary', $this->getVar('summary'), 10, 60);
        $summary_textarea->setDescription(_AM_WFDOWNLOADS_FCATEGORY_SUMMARY_DESC);
        $form->addElement($summary_textarea);
        // category: dohtml, dosmiley, doxcode, doimage, dobr
        $options_tray = new XoopsFormElementTray(_AM_WFDOWNLOADS_TEXTOPTIONS, ' ');
        $options_tray->setDescription(_AM_WFDOWNLOADS_TEXTOPTIONS_DESC);
        $html_checkbox = new XoopsFormCheckBox('', 'dohtml', $this->getVar('dohtml'));
        $html_checkbox->addOption(1, _AM_WFDOWNLOADS_ALLOWHTML);
        $options_tray->addElement($html_checkbox);
        $smiley_checkbox = new XoopsFormCheckBox('', 'dosmiley', $this->getVar('dosmiley'));
        $smiley_checkbox->addOption(1, _AM_WFDOWNLOADS_ALLOWSMILEY);
        $options_tray->addElement($smiley_checkbox);
        $xcodes_checkbox = new XoopsFormCheckBox('', 'doxcode', $this->getVar('doxcode'));
        $xcodes_checkbox->addOption(1, _AM_WFDOWNLOADS_ALLOWXCODE);
        $options_tray->addElement($xcodes_checkbox);
        $noimages_checkbox = new XoopsFormCheckBox('', 'doimage', $this->getVar('doimage'));
        $noimages_checkbox->addOption(1, _AM_WFDOWNLOADS_ALLOWIMAGES);
        $options_tray->addElement($noimages_checkbox);
        $breaks_checkbox = new XoopsFormCheckBox('', 'dobr', $this->getVar('dobr'));
        $breaks_checkbox->addOption(1, _AM_WFDOWNLOADS_ALLOWBREAK);
        $options_tray->addElement($breaks_checkbox);
        $form->addElement($options_tray);
// Formulize module support (2006/05/04) jpc - start
        // category: formulize_fid
        if (wfdownloads_checkModule('formulize')) {
            if (file_exists(XOOPS_ROOT_PATH . '/modules/formulize/include/functions.php')) {
                include_once XOOPS_ROOT_PATH . '/modules/formulize/include/functions.php';
                $fids = allowedForms(); // is a Formulize function
                $fids_select = array();
                $fids_select[0] = _AM_WFDOWNLOADS_FFS_STANDARD_FORM;
                foreach ($fids as $fid) {
                    $fids_select[$fid] = getFormTitle($fid); // is a Formulize function
                }

                $formulize_forms = new XoopsFormSelect(_AM_WFDOWNLOADS_FFS_CUSTOM_FORM, 'formulize_fid', $this->getVar('formulize_fid'));
                $formulize_forms->addOptionArray($fids_select);
                $form->addElement($formulize_forms);
            }
        }
// Formulize module support (2006/05/04) jpc - end
        // form: buttons
        $button_tray = new XoopsFormElementTray('', '');
        $button_tray->addElement(new XoopsFormHidden('op', 'category.save'));
        if ($this->isNew()) {
            $button_create = new XoopsFormButton('', '', _SUBMIT, 'submit');
            $button_create->setExtra('onclick="this.form.elements.op.value=\'category.save\'"');
            $button_tray->addElement($button_create);
        } else {
            $form->addElement(new XoopsFormHidden('cid', $this->getVar('cid')));
            $button_create = new XoopsFormButton('', '', _SUBMIT, 'submit');
            $button_create->setExtra('onclick="this.form.elements.op.value=\'category.save\'"');
            $button_tray->addElement($button_create);
            $button_delete = new XoopsFormButton('', '', _DELETE, 'submit');
            $button_delete->setExtra('onclick="this.form.elements.op.value=\'category.delete\'"');
            $button_tray->addElement($button_delete);
        }
        $button_reset = new XoopsFormButton('', '', _RESET, 'reset');
        $button_tray->addElement($button_reset);
        $button_cancel = new XoopsFormButton('', '', _CANCEL, 'button');
        $button_cancel->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement($button_cancel);
        $form->addElement($button_tray);

        return $form;
    }
}

/**
 * Class WfdownloadsCategoryHandler
 */
class WfdownloadsCategoryHandler extends XoopsPersistableObjectHandler
{
    /**
     * @var WfdownloadsWfdownloads
     * @access public
     */
    public $wfdownloads = null;

    var $allCategories = false;
    var $topCategories = false;

    /**
     * @param null|object $db
     */
    public function __construct(&$db)
    {
        parent::__construct($db, 'wfdownloads_cat', 'WfdownloadsCategory', 'cid', 'title');
        $this->wfdownloads = WfdownloadsWfdownloads::getInstance();
    }

    /**
     * @param        $cid
     * @param string $root_filename
     * @param string $item_filename
     *
     * @return mixed|string
     */
    function getNicePath($cid, $root_filename = 'index.php', $item_filename = 'viewcat.php?op=')
    {
        include_once WFDOWNLOADS_ROOT_PATH . '/class/xoopstree.php';
        $mytree = new WfdownloadsXoopsTree($this->table, $this->keyName, 'pid');
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
    function getUserCategories($id_as_key = false, $as_object = true)
    {
        $gperm_handler = xoops_gethandler('groupperm');

        $groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);
        $allowedDownCategoriesIds = $gperm_handler->getItemIds('WFDownCatPerm', $groups, $this->wfdownloads->getModule()->mid());

        return $this->getObjects(new Criteria('cid', '(' . implode(',', $allowedDownCategoriesIds) . ')', 'IN'), $id_as_key, $as_object);
    }

    /**
     * Get categories that the current user has permissions for
     *
     * @param bool $id_as_key
     * @param bool $as_object
     *
     * @return array
     */
    function getUserDownCategories($id_as_key = false, $as_object = true)
    {
        $gperm_handler = xoops_gethandler('groupperm');

        $groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);
        $allowedDownCategoriesIds = $gperm_handler->getItemIds('WFDownCatPerm', $groups, $this->wfdownloads->getModule()->mid());

        return $this->getObjects(new Criteria('cid', '(' . implode(',', $allowedDownCategoriesIds) . ')', 'IN'), $id_as_key, $as_object);
    }

    /**
     * @param bool $id_as_key
     * @param bool $as_object
     *
     * @return array
     */
    function getUserUpCategories($id_as_key = false, $as_object = true)
    {
        $gperm_handler = xoops_gethandler('groupperm');

        $groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $allowedUpCategoriesIds = $gperm_handler->getItemIds('WFUpCatPerm', $groups, $this->wfdownloads->getModule()->mid());

        return $this->getObjects(new Criteria('cid', '(' . implode(',', $allowedUpCategoriesIds) . ')', 'IN'), $id_as_key, $as_object);
    }

    /**
     * @param $category
     *
     * @return array
     */
    function getChildCats($category)
    {
        $categoryObjs = $this->getObjects();
        include_once XOOPS_ROOT_PATH . '/class/tree.php';
        $categoryObjsTree = new XoopsObjectTree($categoryObjs, $this->keyName, 'pid');

        return $categoryObjsTree->getAllChild($category->getVar($this->keyName));
    }

    /**
     * @return array
     */
    function getAllSubcatsTopParentCid()
    {
        if (!$this->allCategories) {
            $this->allCategories = $this->getObjects(null, true);
        }

        include_once XOOPS_ROOT_PATH . '/class/tree.php';
        $categoryObjsTree = new XoopsObjectTree($this->allCategories, $this->keyName, 'pid');

        $allsubcats_linked_totop = array();
        foreach ($this->allCategories as $cid => $category) {
            $parentCategoryObjs = $categoryObjsTree->getAllParent($cid);
            if (count($parentCategoryObjs) == 0) {
                // is a top category
                $allsubcats_linked_totop[$cid] = $cid;
            } else {
                // is not a top category
                $topParentCategoryObj = end($parentCategoryObjs);
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
