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
 * Class WfdownloadsMirror
 */
class WfdownloadsMirror extends XoopsObject
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
        $this->db          = XoopsDatabaseFactory::getDatabaseConnection();
        $this->initVar('mirror_id', XOBJ_DTYPE_INT);
        $this->initVar('lid', XOBJ_DTYPE_INT);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX);
        $this->initVar('homeurl', XOBJ_DTYPE_URL, 'http://');
        $this->initVar('location', XOBJ_DTYPE_TXTBOX);
        $this->initVar('continent', XOBJ_DTYPE_TXTBOX, _MD_WFDOWNLOADS_CONT4);
        $this->initVar('downurl', XOBJ_DTYPE_URL, '');
        $this->initVar('submit', XOBJ_DTYPE_INT); // boolean
        $this->initVar('date', XOBJ_DTYPE_INT);
        $this->initVar('uid', XOBJ_DTYPE_INT);

        if (isset($id)) {
            $item = $this->wfdownloads->getHandler('item')->get($id);
            foreach ($item->vars as $k => $v) {
                $this->assignVar($k, $v['value']);
            }
        }
    }

    /**
     * @return XoopsThemeForm
     */
    function getForm()
    {
        include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $uid = !empty($GLOBALS['xoopsUser']) ? (int)($GLOBALS['xoopsUser']->getVar('uid')) : 0;

        $form = new XoopsThemeForm(_AM_WFDOWNLOADS_MIRROR_SNEWMNAMEDESC, 'mirrorform', $_SERVER['REQUEST_URI']);
        // title
        $form->addElement(new XoopsFormText(_AM_WFDOWNLOADS_MIRROR_FHOMEURLTITLE, 'title', 50, 255, $this->getVar('title', 'e')), true);
        // homeurl
        $form->addElement(new XoopsFormText(_AM_WFDOWNLOADS_MIRROR_FHOMEURL, 'homeurl', 50, 255, $this->getVar('homeurl', 'e')), true);
        // location
        $form->addElement(new XoopsFormText(_AM_WFDOWNLOADS_MIRROR_LOCATION, 'location', 50, 255, $this->getVar('location', 'e')), true);
        // continent
        $continent_select = new XoopsFormSelect(_AM_WFDOWNLOADS_MIRROR_CONTINENT, "continent", $this->getVar('continent'));
        $continent_select->addOptionArray(
            array(
                _AM_WFDOWNLOADS_CONT1 => _AM_WFDOWNLOADS_CONT1,
                _AM_WFDOWNLOADS_CONT2 => _AM_WFDOWNLOADS_CONT2,
                _AM_WFDOWNLOADS_CONT3 => _AM_WFDOWNLOADS_CONT3,
                _AM_WFDOWNLOADS_CONT4 => _AM_WFDOWNLOADS_CONT4,
                _AM_WFDOWNLOADS_CONT5 => _AM_WFDOWNLOADS_CONT5,
                _AM_WFDOWNLOADS_CONT6 => _AM_WFDOWNLOADS_CONT6,
                _AM_WFDOWNLOADS_CONT7 => _AM_WFDOWNLOADS_CONT7
            )
        );
        $form->addElement($continent_select);
        // downurl
        $form->addElement(new XoopsFormText(_AM_WFDOWNLOADS_MIRROR_DOWNURL, 'downurl', 50, 255, $this->getVar('downurl', 'e')), true);
        // approve
        $approved         = ($this->getVar('submit') == 0) ? 0 : 1;
        $approve_checkbox = new XoopsFormCheckBox(_AM_WFDOWNLOADS_MIRROR_FAPPROVE, 'approve', $approved);
        $approve_checkbox->addOption(1, ' ');
        $form->addElement($approve_checkbox);
        // lid
        $form->addElement(new XoopsFormHidden('lid', (int)($this->getVar('lid'))));
        // mirror_id
        $form->addElement(new XoopsFormHidden('mirror_id', (int)($this->getVar('mirror_id'))));
        // uid
        $form->addElement(new XoopsFormHidden('uid', $uid));
        // confirm
        $form->addElement(new XoopsFormHidden('confirm', 1));
        // op
        $form->addElement(new XoopsFormHidden('op', ''));
        // buttons
        $button_tray = new XoopsFormElementTray('', '');
        if ($this->isNew()) {
            $create_button = new XoopsFormButton('', '', _AM_WFDOWNLOADS_BSAVE, 'submit');
            $create_button->setExtra('onclick="this.form.elements.op.value=\'mirror.save\'"');
            $button_tray->addElement($create_button);
            $clear_button = new XoopsFormButton('', '', _RESET, 'reset');
            $button_tray->addElement($clear_button);
            $cancel_button = new XoopsFormButton('', '', _CANCEL, 'button');
            $cancel_button->setExtra('onclick="history.go(-1)"');
            $button_tray->addElement($cancel_button);
        } else {
            $create_button = new XoopsFormButton('', '', _AM_WFDOWNLOADS_BSAVE, 'submit');
            $create_button->setExtra('onclick="this.form.elements.op.value=\'mirror.save\'"');
            $button_tray->addElement($create_button);
            $button_delete = new XoopsFormButton('', '', _DELETE, 'submit');
            $button_delete->setExtra('onclick="this.form.elements.op.value=\'mirror.delete\'"');
            $button_tray->addElement($button_delete);
            $cancel_button = new XoopsFormButton('', '', _CANCEL, 'button');
            $cancel_button->setExtra('onclick="history.go(-1)"');
            $button_tray->addElement($cancel_button);
        }
        $form->addElement($button_tray);

        return $form;
    }
}

/**
 * Class WfdownloadsMirrorHandler
 */
class WfdownloadsMirrorHandler extends XoopsPersistableObjectHandler
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
        parent::__construct($db, 'wfdownloads_mirrors', 'WfdownloadsMirror', 'mirror_id', 'title');
        $this->wfdownloads = WfdownloadsWfdownloads::getInstance();
    }
}
