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
$currentFile = basename(__FILE__);
include_once __DIR__ . '/admin_header.php';

if (@$_POST['op'] == 'submit') {
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header($currentFile, 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
        exit();
    }

    wfdownloads_xoops_cp_header();
    $indexAdmin = new ModuleAdmin();
    echo $indexAdmin->addNavigation($currentFile);

    // Swish-e support EXPERIMENTAL
    wfdownloads_swishe_config();
    // Swish-e support EXPERIMENTAL

    include_once __DIR__ . '/admin_footer.php';
    exit();

} else {
    wfdownloads_xoops_cp_header();
    $indexAdmin = new ModuleAdmin();
    echo $indexAdmin->addNavigation($currentFile);

    // Swish-e support EXPERIMENTAL
    if (wfdownloads_swishe_check() == true) {
        echo "OK";
    } else {
        echo "NOT OK" . "<br />";
        include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $form = new XoopsThemeForm(_AM_WFDOWNLOADS_SWISHE_CONFIG, 'config', $currentFile, 'post', true);
        $form->addElement(new XoopsFormHidden('op', 'submit'));
        $form->addElement(new XoopsFormButton('', '', _SUBMIT, 'submit'));
        $form->display();
    }

    // Get the location of the document repository (the index files are located in the root)
    $swisheDocPath = $wfdownloads->getConfig('uploaddir');

    // Get the location of the SWISH-E executable
    $swisheExePath = $wfdownloads->getConfig('swishe_exe_path');

    // check if _binfilter.sh exists
    echo "{$swisheDocPath}/_binfilter.sh" . "<br />";
// IN PROGRESS
    // check if swish-e.conf exists
    echo "{$swisheDocPath}/swish-e.conf" . "<br />";
// IN PROGRESS
    // check if swish-e exists
    echo "{$swisheExePath}/swish-e" . "<br />"; // path of swish-e command
    echo "{$swisheDocPath}/index.swish-e" . "<br />"; // path of swish-e index file
// IN PROGRESS
    // Swish-e support EXPERIMENTAL

    include_once __DIR__ . '/admin_footer.php';
    exit();
}
