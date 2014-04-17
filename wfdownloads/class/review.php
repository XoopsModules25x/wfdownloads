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

/**
 * Class WfdownloadsReview
 */
class WfdownloadsReview extends XoopsObject
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
        $this->initVar('review_id', XOBJ_DTYPE_INT);
        $this->initVar('lid', XOBJ_DTYPE_INT);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX);
        $this->initVar('review', XOBJ_DTYPE_TXTAREA);
        $this->initVar('submit', XOBJ_DTYPE_INT); // boolean
        $this->initVar('date', XOBJ_DTYPE_INT);
        $this->initVar('uid', XOBJ_DTYPE_INT);
        $this->initVar('rated', XOBJ_DTYPE_INT);

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
        $uid = !empty($xoopsUser) ? (int) ($xoopsUser->getVar('uid')) : 0;

        $sform = new XoopsThemeForm(_AM_WFDOWNLOADS_REV_SNEWMNAMEDESC, 'reviewform', $_SERVER['REQUEST_URI']);
        $sform->addElement(new XoopsFormText(_AM_WFDOWNLOADS_REV_FTITLE, 'title', 30, 40, $this->getVar('title', 'e')), true);
        $rating_select = new XoopsFormSelect(_AM_WFDOWNLOADS_REV_FRATING, 'rated', $this->getVar('rated'));
        $rating_select->addOptionArray(
            array(
                 '1'  => 1,
                 '2'  => 2,
                 '3'  => 3,
                 '4'  => 4,
                 '5'  => 5,
                 '6'  => 6,
                 '7'  => 7,
                 '8'  => 8,
                 '9'  => 9,
                 '10' => 10
            )
        );
        $sform->addElement($rating_select);
        $sform->addElement(new XoopsFormDhtmlTextArea(_AM_WFDOWNLOADS_REV_FDESCRIPTION, 'review', $this->getVar('review', 'e'), 15, 60), true);

        $approved         = ($this->getVar('submit') == 0) ? 0 : 1;
        $approve_checkbox = new XoopsFormCheckBox(_AM_WFDOWNLOADS_REV_FAPPROVE, 'approve', $approved);
        $approve_checkbox->addOption(1, " ");
        $sform->addElement($approve_checkbox);

        $sform->addElement(new XoopsFormHidden('lid', (int) ($this->getVar('lid'))));
        $sform->addElement(new XoopsFormHidden('uid', $uid));
        $sform->addElement(new XoopsFormHidden('review_id', (int) ($this->getVar('review_id'))));
        $sform->addElement(new XoopsFormHidden('confirm', 1));
        $button_tray = new XoopsFormElementTray('', '');
        $hidden      = new XoopsFormHidden('op', '');
        $button_tray->addElement($hidden);

        if ($this->isNew()) {
            $butt_create = new XoopsFormButton('', '', _AM_WFDOWNLOADS_BSAVE, 'submit');
            $butt_create->setExtra('onclick="this.form.elements.op.value=\'review.save\'"');
            $button_tray->addElement($butt_create);
            $butt_clear = new XoopsFormButton('', '', _AM_WFDOWNLOADS_BRESET, 'reset');
            $button_tray->addElement($butt_clear);
            $butt_cancel = new XoopsFormButton('', '', _AM_WFDOWNLOADS_BCANCEL, 'button');
            $butt_cancel->setExtra('onclick="history.go(-1)"');
            $button_tray->addElement($butt_cancel);
        } else {
            $butt_create = new XoopsFormButton('', '', _AM_WFDOWNLOADS_BSAVE, 'submit');
            $butt_create->setExtra('onclick="this.form.elements.op.value=\'review.save\'"');
            $button_tray->addElement($butt_create);
            $butt_delete = new XoopsFormButton('', '', _AM_WFDOWNLOADS_BDELETE, 'submit');
            $butt_delete->setExtra('onclick="this.form.elements.op.value=\'review.delete\'"');
            $button_tray->addElement($butt_delete);
            $butt_cancel = new XoopsFormButton('', '', _AM_WFDOWNLOADS_BCANCEL, 'button');
            $butt_cancel->setExtra('onclick="history.go(-1)"');
            $button_tray->addElement($butt_cancel);
        }
        $sform->addElement($button_tray);

        return $sform;
    }

    /**
     * Returns an array representation of the object
     *
     * @return array
     */
    function toArray()
    {
        $ret  = array();
        $vars = $this->getVars();
        foreach (array_keys($vars) as $i) {
            $ret[$i] = $this->getVar($i);
        }

        return $ret;
    }
}

/**
 * Class WfdownloadsReviewHandler
 */
class WfdownloadsReviewHandler extends XoopsPersistableObjectHandler
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
        parent::__construct($db, 'wfdownloads_reviews', 'WfdownloadsReview', 'review_id', 'title');
        $this->wfdownloads = WfdownloadsWfdownloads::getInstance();
    }
}
