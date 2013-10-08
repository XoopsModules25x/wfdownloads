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
defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");
include_once dirname(dirname(__FILE__)) . '/include/common.php';
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
        // Added Formulize module support (2006/05/04) jpc - start
        $this->initVar('formulize_fid', XOBJ_DTYPE_INT, 0);
        // Added Formulize module support (2006/05/04) jpc - end
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

        $form->addElement(new XoopsFormText(_AM_WFDOWNLOADS_FCATEGORY_TITLE, 'title', 50, 255, $this->getVar('title', 'e')), true);

        $totalcats = wfdownloads_categoriesCount();
        if ($totalcats > 0) {
            $categories     = $this->wfdownloads->getHandler('category')->getObjects();
            $categoriesTree = new XoopsObjectTree($categories, 'cid', 'pid');
            $form->addElement(
                new XoopsFormLabel(_AM_WFDOWNLOADS_FCATEGORY_SUBCATEGORY, $categoriesTree->makeSelBox(
                    'pid',
                    'title',
                    "-",
                    $this->getVar('pid', 'e'),
                    true
                ))
            );
        }

        $form->addElement(new XoopsFormText(_AM_WFDOWNLOADS_FCATEGORY_WEIGHT, 'weight', 10, 80, $this->getVar('weight')), false);

        $groups             = $gperm_handler->getGroupIds('WFDownCatPerm', $this->getVar('cid'), $this->wfdownloads->getModule()->mid());
        $groups_down_select = new XoopsFormSelectGroup(_AM_WFDOWNLOADS_FCATEGORY_GROUPPROMPT, "groups", true, $groups, 5, true);
        $groups_down_select->setDescription(_AM_WFDOWNLOADS_FCATEGORY_GROUPPROMPT_DESC);
        $form->addElement($groups_down_select);
        $up_groups        = $gperm_handler->getGroupIds('WFUpCatPerm', $this->getVar('cid'), $this->wfdownloads->getModule()->mid());
        $groups_up_select = new XoopsFormSelectGroup(_AM_WFDOWNLOADS_FCATEGORY_GROUPPROMPT_UP, "up_groups", true, $up_groups, 5, true);
        $groups_up_select->setDescription(_AM_WFDOWNLOADS_FCATEGORY_GROUPPROMPT_UP_DESC);
        $form->addElement($groups_up_select);

        $graph_array       = & WfsLists::getListTypeAsArray(XOOPS_ROOT_PATH . '/' . $this->wfdownloads->getConfig('catimage'), $type = "images");
        $indeximage_select = new XoopsFormSelect('', 'imgurl', $this->getVar('imgurl'));
        $indeximage_select->addOptionArray($graph_array);
        $indeximage_select->setExtra(
            "onchange='showImgSelected(\"image\", \"imgurl\", \"" . $this->wfdownloads->getConfig('catimage') . "\", \"\", \"" . XOOPS_URL . "\")'"
        );
        $indeximage_tray = new XoopsFormElementTray(_AM_WFDOWNLOADS_FCATEGORY_CIMAGE, '&nbsp;');
        $indeximage_tray->addElement($indeximage_select);
        if ($this->getVar('imgurl') != "") {
            $indeximage_tray->addElement(
                new XoopsFormLabel('',
                    "<br /><br /><img src='" . XOOPS_URL . '/' . $this->wfdownloads->getConfig('catimage') . '/' . $this->getVar('imgurl')
                    . "' name='image' id='image' alt='' title='image' />")
            );
        } else {
            $indeximage_tray->addElement(
                new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/uploads/blank.gif' name='image' id='image' alt='' title='image' />")
            );
        }
        $form->addElement($indeximage_tray);

        $description_textarea = new XoopsFormDhtmlTextArea(_AM_WFDOWNLOADS_FCATEGORY_DESCRIPTION, 'description', $this->getVar(
            'description',
            'e'
        ), 15, 60);
        $form->addElement($description_textarea, true);

        $summary_textarea = new XoopsFormTextArea(_AM_WFDOWNLOADS_FCATEGORY_SUMMARY, 'summary', $this->getVar('summary'), 10, 60);
        $form->addElement($summary_textarea);

        $options_tray  = new XoopsFormElementTray(_AM_WFDOWNLOADS_TEXTOPTIONS, '<br />');
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

        // Added Formulize module support (2006/05/04) jpc - start
        if (wfdownloads_checkModule('formulize')) {
            if (file_exists(XOOPS_ROOT_PATH . "/modules/formulize/include/functions.php")) {
                include_once XOOPS_ROOT_PATH . "/modules/formulize/include/functions.php";
                $fids           = allowedForms();
                $fids_select    = array();
                $fids_select[0] = _AM_WFDOWNLOADS_FFS_STANDARD_FORM;
                foreach ($fids as $fid) {
                    $fids_select[$fid] = getFormTitle($fid);
                }

                $formulize_forms = new XoopsFormSelect(_AM_WFDOWNLOADS_FFS_CUSTOM_FORM, "formulize_fid", $this->getVar('formulize_fid'));
                $formulize_forms->addOptionArray($fids_select);
                $form->addElement($formulize_forms);
            }
        }
        // Added Formulize module support (2006/05/04) jpc - end

        $button_tray = new XoopsFormElementTray('', '');
        $button_tray->addElement(new XoopsFormHidden('op', 'save'));
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

    function getNicePath($cid, $root_filename = "index.php", $item_filename = "viewcat.php?op=")
    {
        include_once WFDOWNLOADS_ROOT_PATH . '/class/xoopstree.php';
        $mytree     = new WfdownloadsXoopsTree($this->table, $this->keyName, 'pid');
        $pathstring = $mytree->getNicePathFromId($cid, $this->identifierName, $item_filename);

        /**
         * Replacing the " with ">" and deleteing the last ">" at the end
         */
        $pathstring = trim($pathstring);
        $pathstring = str_replace(':', '>', $pathstring);
//      $pathstring = substr($pathstring, 0, strlen($pathstring) - 13); // not needed now with fixed icms core! but required for XOOPS
        return $pathstring;
    }

    /**
     * Get categories that the current user has permissions for
     *
     * @param  bool $id_as_key
     * @param  bool $as_object
     *
     * @return array
     */
    function getUserCategories($id_as_key = false, $as_object = true)
    {
        global $xoopsUser;
        $gperm_handler = xoops_gethandler('groupperm');

        $groups                   = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);
        $allowedDownCategoriesIds = $gperm_handler->getItemIds('WFDownCatPerm', $groups, $this->wfdownloads->getModule()->mid());

        return $this->getObjects(new Criteria('cid', '(' . implode(',', $allowedDownCategoriesIds) . ')', 'IN'), $id_as_key, $as_object);
    }

    /**
     * Get categories that the current user has permissions for
     *
     * @param  bool $id_as_key
     * @param  bool $as_object
     *
     * @return array
     */
    function getUserDownCategories($id_as_key = false, $as_object = true)
    {
        global $xoopsUser;
        $gperm_handler = xoops_gethandler('groupperm');

        $groups                   = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);
        $allowedDownCategoriesIds = $gperm_handler->getItemIds('WFDownCatPerm', $groups, $this->wfdownloads->getModule()->mid());

        return $this->getObjects(new Criteria('cid', '(' . implode(',', $allowedDownCategoriesIds) . ')', 'IN'), $id_as_key, $as_object);
    }

    function getUserUpCategories($id_as_key = false, $as_object = true)
    {
        global $xoopsUser;
        $gperm_handler = xoops_gethandler('groupperm');

        $groups                 = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $allowedUpCategoriesIds = $gperm_handler->getItemIds('WFUpCatPerm', $groups, $this->wfdownloads->getModule()->mid());

        return $this->getObjects(new Criteria('cid', '(' . implode(',', $allowedUpCategoriesIds) . ')', 'IN'), $id_as_key, $as_object);
    }

    function getChildCats($category)
    {
        $allcats = $this->getObjects();
        include_once XOOPS_ROOT_PATH . '/class/tree.php';
        $tree = new XoopsObjectTree($allcats, $this->keyName, 'pid');

        return $tree->getAllChild($category->getVar($this->keyName));
    }

    function getAllSubcatsTopParentCid()
    {
        if (!$this->allCategories) {
            $this->allCategories = $this->getObjects(null, true);
        }

        include_once XOOPS_ROOT_PATH . '/class/tree.php';
        $tree    = new XoopsObjectTree($this->allCategories, $this->keyName, 'pid');
        $treeobj = $tree->getTree();

        /**
         * Let's create an array where key will be cid of the top categories and
         * value will be an array containing all the cid of its subcategories
         * If value = 0, then this topcat does not have any subcats
         */
        $topcatsarray = array();
        foreach ($treeobj[0]['child'] as $topcid) {
            if (!isset($this->topCategories[$topcid])) {
                $this->topCategories[$topcid] = $topcid;
            }
            foreach ($tree->getAllChild($topcid) as $key => $category) {
                $childrenids[] = $category->getVar('cid');
            }
            $childrenids           = isset($childrenids) ? $childrenids : 0;
            $topcatsarray[$topcid] = $childrenids;
            unset($childrenids);
        }

        /**
         * Now we need to create another array where key will be all subcategories cid and
         * value will be the cid of its top most category
         */
        $allsubcats_linked_totop = array();

        foreach ($topcatsarray as $topcatcid => $subcatsarray) {
            if ($subcatsarray == 0) {
                $allsubcats_linked_totop[$topcatcid] = $topcatcid;
            } else {
                foreach ($subcatsarray as $subcatcid) {
                    $allsubcats_linked_totop[$subcatcid] = $topcatcid;
                }
            }
        }

        /**
         * Finally, let's finish by adding to this array, all the top categories which values
         * will be their cid
         */
        foreach ($this->topCategories as $topcid) {
            $allsubcats_linked_totop[$topcid] = $topcid;
        }

        return $allsubcats_linked_totop;
    }
}
