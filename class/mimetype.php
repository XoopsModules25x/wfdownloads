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
/*
CREATE TABLE wfdownloads_mimetypes (
  mime_id int(11) NOT NULL auto_increment,
  mime_ext varchar(60) NOT NULL default '',
  mime_types text NOT NULL,
  mime_name varchar(255) NOT NULL default '',
  mime_admin int(1) NOT NULL default '1',
  mime_user int(1) NOT NULL default '0',
  KEY mime_id (mime_id)
) ENGINE=MyISAM;
*/
defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');
include_once dirname(__DIR__) . '/include/common.php';

/**
 * Class WfdownloadsMimetype
 */
class WfdownloadsMimetype extends XoopsObject
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
        $this->initVar('mime_id', XOBJ_DTYPE_INT);
        $this->initVar('mime_ext', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('mime_types', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('mime_name', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('mime_admin', XOBJ_DTYPE_INT, true); // boolean
        $this->initVar('mime_user', XOBJ_DTYPE_INT, false); // boolean

        if (isset($id)) {
            $item = $this->wfdownloads->getHandler('item')->get($id);
            foreach ($item->vars as $k => $v) {
                $this->assignVar($k, $v['value']);
            }
        }
    }

    /**
     * @param bool $action
     *
     * @return XoopsThemeForm
     */
    function getForm($action = false)
    {
        include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $title = $this->isNew() ? _AM_WFDOWNLOADS_MIME_CREATEF : _AM_WFDOWNLOADS_MIME_MODIFYF;

        $form = new XoopsThemeForm($title, 'form_error', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // mime_ext
            $mime_ext_text = new XoopsFormText(_AM_WFDOWNLOADS_MIME_EXTF, 'mime_ext', 5, 60, $this->getVar('mime_ext', 'e'));
            $mime_ext_text->setDescription(_AM_WFDOWNLOADS_MIME_EXTF_DESC);
        $form->addElement($mime_ext_text, true);
        // mime_name
            $mime_name_text = new XoopsFormText(_AM_WFDOWNLOADS_MIME_NAMEF, 'mime_name', 50, 255, $this->getVar('mime_name', 'e'));
            $mime_name_text->setDescription(_AM_WFDOWNLOADS_MIME_NAMEF_DESC);
        $form->addElement($mime_name_text, true);
        // mime_type
            $mime_type_textarea = new XoopsFormTextArea(_AM_WFDOWNLOADS_MIME_TYPEF, 'mime_type', $this->getVar('mime_types', 'e'));
            $mime_type_textarea->setDescription(_AM_WFDOWNLOADS_MIME_TYPEF_DESC);
        $form->addElement($mime_type_textarea, 7, 60);
        // mime_admin
            $madmin_radio = new XoopsFormRadioYN(_AM_WFDOWNLOADS_MIME_ADMINF, 'mime_admin', $this->getVar('mime_admin', 'e'));
        $form->addElement($madmin_radio);
        // mime_user
            $muser_radio = new XoopsFormRadioYN(_AM_WFDOWNLOADS_MIME_USERF, 'mime_user', $this->getVar('mime_user', 'e'));
        $form->addElement($muser_radio);
        // op
        $form->addElement(new XoopsFormHidden('op', 'save'));
        // buttons
            $button_tray = new XoopsFormElementTray('', '');
        if ($this->isNew()) {
                $butt_create = new XoopsFormButton('', '', _AM_WFDOWNLOADS_MIME_CREATE, 'submit');
                $butt_create->setExtra('onclick="this.form.elements.op.value=\'mimetype.save\'"');
            $button_tray->addElement($butt_create);
                $butt_clear = new XoopsFormButton('', '', _AM_WFDOWNLOADS_MIME_CLEAR, 'reset');
            $button_tray->addElement($butt_clear);
                $butt_cancel = new XoopsFormButton('', '', _AM_WFDOWNLOADS_MIME_CANCEL, 'button');
                $butt_cancel->setExtra('onclick="history.go(-1)"');
            $button_tray->addElement($butt_cancel);
        } else {
            $form->addElement(new XoopsFormHidden('mime_id', $this->getVar('mime_id')));
                $butt_create = new XoopsFormButton('', '', _AM_WFDOWNLOADS_MIME_MODIFY, 'submit');
                $butt_create->setExtra('onclick="this.form.elements.op.value=\'mimetype.save\'"');
            $button_tray->addElement($butt_create);
                $butt_cancel = new XoopsFormButton('', '', _AM_WFDOWNLOADS_MIME_CANCEL, 'button');
                $butt_cancel->setExtra('onclick="history.go(-1)"');
            $button_tray->addElement($butt_cancel);
        }
        $form->addElement($button_tray);

        return $form;
    }
}

/**
 * Class WfdownloadsMimetypeHandler
 */
class WfdownloadsMimetypeHandler extends XoopsPersistableObjectHandler
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
        parent::__construct($db, 'wfdownloads_mimetypes', 'WfdownloadsMimetype', 'mime_id', 'mime_ext');
        $this->wfdownloads = WfdownloadsWfdownloads::getInstance();
    }
}
