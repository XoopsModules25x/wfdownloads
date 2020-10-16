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

use XoopsModules\Wfdownloads;


require_once \dirname(__DIR__) . '/include/common.php';

/**
 * Class Mirror
 */
class Mirror extends \XoopsObject
{
    /**
     * @access public
     */
    public $helper;
    public $db;

    /**
     * @param int|null $id
     */
    public function __construct($id = null)
    {
        /** @var Wfdownloads\Helper $this ->helper */
        $this->helper = Wfdownloads\Helper::getInstance();
        $this->db     = \XoopsDatabaseFactory::getDatabaseConnection();
        $this->initVar('mirror_id', \XOBJ_DTYPE_INT);
        $this->initVar('lid', \XOBJ_DTYPE_INT);
        $this->initVar('title', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('homeurl', \XOBJ_DTYPE_URL, 'http://');
        $this->initVar('location', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('continent', \XOBJ_DTYPE_TXTBOX, \_MD_WFDOWNLOADS_CONT4);
        $this->initVar('downurl', \XOBJ_DTYPE_URL, '');
        $this->initVar('submit', \XOBJ_DTYPE_INT); // boolean
        $this->initVar('date', \XOBJ_DTYPE_INT);
        $this->initVar('uid', \XOBJ_DTYPE_INT);

        if (null !== $id) {
            $item = $this->helper->getHandler('Item')->get($id);
            foreach ($item->vars as $k => $v) {
                $this->assignVar($k, $v['value']);
            }
        }
    }

    /**
     * @return \XoopsThemeForm
     */
    public function getForm()
    {
        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $uid = !empty($GLOBALS['xoopsUser']) ? (int)$GLOBALS['xoopsUser']->getVar('uid') : 0;

        $form = new \XoopsThemeForm(\_AM_WFDOWNLOADS_MIRROR_SNEWMNAMEDESC, 'mirrorform', $_SERVER['REQUEST_URI']);
        // title
        $form->addElement(new \XoopsFormText(\_AM_WFDOWNLOADS_MIRROR_FHOMEURLTITLE, 'title', 50, 255, $this->getVar('title', 'e')), true);
        // homeurl
        $form->addElement(new \XoopsFormText(\_AM_WFDOWNLOADS_MIRROR_FHOMEURL, 'homeurl', 50, 255, $this->getVar('homeurl', 'e')), true);
        // location
        $form->addElement(new \XoopsFormText(\_AM_WFDOWNLOADS_MIRROR_LOCATION, 'location', 50, 255, $this->getVar('location', 'e')), true);
        // continent
        $continent_select = new \XoopsFormSelect(\_AM_WFDOWNLOADS_MIRROR_CONTINENT, 'continent', $this->getVar('continent'));
        $continent_select->addOptionArray(
            [
                \_AM_WFDOWNLOADS_CONT1 => \_AM_WFDOWNLOADS_CONT1,
                \_AM_WFDOWNLOADS_CONT2 => \_AM_WFDOWNLOADS_CONT2,
                \_AM_WFDOWNLOADS_CONT3 => \_AM_WFDOWNLOADS_CONT3,
                \_AM_WFDOWNLOADS_CONT4 => \_AM_WFDOWNLOADS_CONT4,
                \_AM_WFDOWNLOADS_CONT5 => \_AM_WFDOWNLOADS_CONT5,
                \_AM_WFDOWNLOADS_CONT6 => \_AM_WFDOWNLOADS_CONT6,
                \_AM_WFDOWNLOADS_CONT7 => \_AM_WFDOWNLOADS_CONT7,
            ]
        );
        $form->addElement($continent_select);
        // downurl
        $form->addElement(new \XoopsFormText(\_AM_WFDOWNLOADS_MIRROR_DOWNURL, 'downurl', 50, 255, $this->getVar('downurl', 'e')), true);
        // approve
        $approved         = (0 == $this->getVar('submit')) ? 0 : 1;
        $approve_checkbox = new \XoopsFormCheckBox(\_AM_WFDOWNLOADS_MIRROR_FAPPROVE, 'approve', $approved);
        $approve_checkbox->addOption(1, ' ');
        $form->addElement($approve_checkbox);
        // lid
        $form->addElement(new \XoopsFormHidden('lid', (int)$this->getVar('lid')));
        // mirror_id
        $form->addElement(new \XoopsFormHidden('mirror_id', (int)$this->getVar('mirror_id')));
        // uid
        $form->addElement(new \XoopsFormHidden('uid', $uid));
        // confirm
        $form->addElement(new \XoopsFormHidden('confirm', 1));
        // op
        $form->addElement(new \XoopsFormHidden('op', ''));
        // buttons
        $buttonTray = new \XoopsFormElementTray('', '');
        if ($this->isNew()) {
            $createButton = new \XoopsFormButton('', '', \_AM_WFDOWNLOADS_BSAVE, 'submit');
            $createButton->setExtra('onclick="this.form.elements.op.value=\'mirror.save\'"');
            $buttonTray->addElement($createButton);
            $clearButton = new \XoopsFormButton('', '', _RESET, 'reset');
            $buttonTray->addElement($clearButton);
            $cancelButton = new \XoopsFormButton('', '', _CANCEL, 'button');
            $cancelButton->setExtra('onclick="history.go(-1)"');
            $buttonTray->addElement($cancelButton);
        } else {
            $createButton = new \XoopsFormButton('', '', \_AM_WFDOWNLOADS_BSAVE, 'submit');
            $createButton->setExtra('onclick="this.form.elements.op.value=\'mirror.save\'"');
            $buttonTray->addElement($createButton);
            $deleteButton = new \XoopsFormButton('', '', _DELETE, 'submit');
            $deleteButton->setExtra('onclick="this.form.elements.op.value=\'mirror.delete\'"');
            $buttonTray->addElement($deleteButton);
            $cancelButton = new \XoopsFormButton('', '', _CANCEL, 'button');
            $cancelButton->setExtra('onclick="history.go(-1)"');
            $buttonTray->addElement($cancelButton);
        }
        $form->addElement($buttonTray);

        return $form;
    }
}
