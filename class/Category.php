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
 * Class Category
 */
class Category extends \XoopsObject
{
    /**
     * @access public
     */
    public $wfdownloads = null;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->wfdownloads = wfdownloads\Helper::getInstance();
        $this->db          = \XoopsDatabaseFactory::getDatabaseConnection();
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
     * @return \XoopsThemeForm
     */
    public function getForm($action = false)
    {
        $gpermHandler = xoops_getHandler('groupperm');

        if (false === $action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $title = $this->isNew() ? _AM_WFDOWNLOADS_CCATEGORY_CREATENEW : _AM_WFDOWNLOADS_CCATEGORY_MODIFY;

        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        require_once WFDOWNLOADS_ROOT_PATH . '/class/WfsLists.php';

        $form = new \XoopsThemeForm($title, 'form_error', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // category: title
        $form->addElement(new \XoopsFormText(_AM_WFDOWNLOADS_FCATEGORY_TITLE, 'title', 50, 255, $this->getVar('title', 'e')), true);
        // category: pid
        if (wfdownloads\Utility::categoriesCount() > 0) {
            $categoryObjs     = $this->wfdownloads->getHandler('category')->getObjects();
            $categoryObjsTree = new \XoopsObjectTree($categoryObjs, 'cid', 'pid');

            if (wfdownloads\Utility::checkVerXoops($GLOBALS['xoopsModule'], '2.5.9')) {
                $catSelect = $categoryObjsTree->makeSelectElement('pid', 'title', '-', $this->getVar('pid'), true, 0, '', _AM_WFDOWNLOADS_FCATEGORY_SUBCATEGORY);
                $form->addElement($catSelect);
            } else {
                $form->addElement(new \XoopsFormLabel(_AM_WFDOWNLOADS_FCATEGORY_SUBCATEGORY, $categoryObjsTree->makeSelBox('pid', 'title', '-', $this->getVar('pid', 'e'), true)));
            }


        }
        // category: weight
        $form->addElement(new \XoopsFormText(_AM_WFDOWNLOADS_FCATEGORY_WEIGHT, 'weight', 11, 11, $this->getVar('weight')), false);
        // permission: WFDownCatPerm
        $groups             = $gpermHandler->getGroupIds('WFDownCatPerm', $this->getVar('cid'), $this->wfdownloads->getModule()->mid());
        $groups_down_select = new \XoopsFormSelectGroup(_AM_WFDOWNLOADS_FCATEGORY_GROUPPROMPT, 'groups', true, $groups, 5, true);
        $groups_down_select->setDescription(_AM_WFDOWNLOADS_FCATEGORY_GROUPPROMPT_DESC);
        $form->addElement($groups_down_select);
        // permission: WFUpCatPerm
        $up_groups        = $gpermHandler->getGroupIds('WFUpCatPerm', $this->getVar('cid'), $this->wfdownloads->getModule()->mid());
        $groups_up_select = new \XoopsFormSelectGroup(_AM_WFDOWNLOADS_FCATEGORY_GROUPPROMPT_UP, 'up_groups', true, $up_groups, 5, true);
        $groups_up_select->setDescription(_AM_WFDOWNLOADS_FCATEGORY_GROUPPROMPT_UP_DESC);
        $form->addElement($groups_up_select);
        // category: imgurl
        $imgurl_path = $this->getVar('imgurl') ? $this->wfdownloads->getConfig('catimage') . '/' . $this->getVar('imgurl') : WFDOWNLOADS_IMAGES_URL . '/blank.png';
        $imgurl_tray = new \XoopsFormElementTray(_AM_WFDOWNLOADS_FCATEGORY_CIMAGE, '<br>');
        $imgurl_tray->addElement(new \XoopsFormLabel(_AM_WFDOWNLOADS_DOWN_FUPLOADPATH, XOOPS_ROOT_PATH . '/' . $this->wfdownloads->getConfig('catimage')));
        $imgurl_tray->addElement(new \XoopsFormLabel(_AM_WFDOWNLOADS_DOWN_FUPLOADURL, XOOPS_URL . '/' . $this->wfdownloads->getConfig('catimage')));
        $graph_array   =& WfsLists::getListTypeAsArray(XOOPS_ROOT_PATH . '/' . $this->wfdownloads->getConfig('catimage'), 'images');
        $imgurl_select = new \XoopsFormSelect('', 'imgurl', $this->getVar('imgurl'));
        $imgurl_select->addOptionArray($graph_array);
        $imgurl_select->setExtra("onchange='showImgSelected(\"image\", \"imgurl\", \"" . $this->wfdownloads->getConfig('catimage') . '", "", "' . XOOPS_URL . "\")'");
        $imgurl_tray->addElement($imgurl_select, false);
        $imgurl_tray->addElement(new \XoopsFormLabel('', "<img src='" . XOOPS_URL . '/' . $imgurl_path . "' name='image' id='image' alt=''>"));
        $imgurl_tray->addElement(new \XoopsFormFile(_AM_WFDOWNLOADS_BUPLOAD, 'uploadfile', 0), false);
        $form->addElement($imgurl_tray);
        // category: description
        $description_textarea = new \XoopsFormDhtmlTextArea(_AM_WFDOWNLOADS_FCATEGORY_DESCRIPTION, 'description', $this->getVar('description', 'e'), 15, 60);
        $description_textarea->setDescription(_AM_WFDOWNLOADS_FCATEGORY_DESCRIPTION_DESC);
        $form->addElement($description_textarea, true);
        // category: summary
        $summary_textarea = new \XoopsFormTextArea(_AM_WFDOWNLOADS_FCATEGORY_SUMMARY, 'summary', $this->getVar('summary'), 10, 60);
        $summary_textarea->setDescription(_AM_WFDOWNLOADS_FCATEGORY_SUMMARY_DESC);
        $form->addElement($summary_textarea);
        // category: dohtml, dosmiley, doxcode, doimage, dobr
        $options_tray = new \XoopsFormElementTray(_AM_WFDOWNLOADS_TEXTOPTIONS, ' ');
        $options_tray->setDescription(_AM_WFDOWNLOADS_TEXTOPTIONS_DESC);
        $html_checkbox = new \XoopsFormCheckBox('', 'dohtml', $this->getVar('dohtml'));
        $html_checkbox->addOption(1, _AM_WFDOWNLOADS_ALLOWHTML);
        $options_tray->addElement($html_checkbox);
        $smiley_checkbox = new \XoopsFormCheckBox('', 'dosmiley', $this->getVar('dosmiley'));
        $smiley_checkbox->addOption(1, _AM_WFDOWNLOADS_ALLOWSMILEY);
        $options_tray->addElement($smiley_checkbox);
        $xcodes_checkbox = new \XoopsFormCheckBox('', 'doxcode', $this->getVar('doxcode'));
        $xcodes_checkbox->addOption(1, _AM_WFDOWNLOADS_ALLOWXCODE);
        $options_tray->addElement($xcodes_checkbox);
        $noimages_checkbox = new \XoopsFormCheckBox('', 'doimage', $this->getVar('doimage'));
        $noimages_checkbox->addOption(1, _AM_WFDOWNLOADS_ALLOWIMAGES);
        $options_tray->addElement($noimages_checkbox);
        $breaks_checkbox = new \XoopsFormCheckBox('', 'dobr', $this->getVar('dobr'));
        $breaks_checkbox->addOption(1, _AM_WFDOWNLOADS_ALLOWBREAK);
        $options_tray->addElement($breaks_checkbox);
        $form->addElement($options_tray);
        // Formulize module support (2006/05/04) jpc - start
        // category: formulize_fid
        if (wfdownloads\Utility::checkModule('formulize')) {
            if (file_exists(XOOPS_ROOT_PATH . '/modules/formulize/include/functions.php')) {
                require_once XOOPS_ROOT_PATH . '/modules/formulize/include/functions.php';
                $fids           = allowedForms(); // is a Formulize function
                $fids_select    = [];
                $fids_select[0] = _AM_WFDOWNLOADS_FFS_STANDARD_FORM;
                foreach ($fids as $fid) {
                    $fids_select[$fid] = getFormTitle($fid); // is a Formulize function
                }

                $formulize_forms = new \XoopsFormSelect(_AM_WFDOWNLOADS_FFS_CUSTOM_FORM, 'formulize_fid', $this->getVar('formulize_fid'));
                $formulize_forms->addOptionArray($fids_select);
                $form->addElement($formulize_forms);
            }
        }
        // Formulize module support (2006/05/04) jpc - end
        // form: buttons
        $button_tray = new \XoopsFormElementTray('', '');
        $button_tray->addElement(new \XoopsFormHidden('op', 'category.save'));
        if ($this->isNew()) {
            $button_create = new \XoopsFormButton('', '', _SUBMIT, 'submit');
            $button_create->setExtra('onclick="this.form.elements.op.value=\'category.save\'"');
            $button_tray->addElement($button_create);
        } else {
            $form->addElement(new \XoopsFormHidden('cid', $this->getVar('cid')));
            $button_create = new \XoopsFormButton('', '', _SUBMIT, 'submit');
            $button_create->setExtra('onclick="this.form.elements.op.value=\'category.save\'"');
            $button_tray->addElement($button_create);
            $button_delete = new \XoopsFormButton('', '', _DELETE, 'submit');
            $button_delete->setExtra('onclick="this.form.elements.op.value=\'category.delete\'"');
            $button_tray->addElement($button_delete);
        }
        $button_reset = new \XoopsFormButton('', '', _RESET, 'reset');
        $button_tray->addElement($button_reset);
        $button_cancel = new \XoopsFormButton('', '', _CANCEL, 'button');
        $button_cancel->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement($button_cancel);
        $form->addElement($button_tray);

        return $form;
    }
}
