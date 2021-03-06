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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package         wfdownload
 * @since           3.23
 * @author          Xoops Development Team
 */

use XoopsModules\Wfdownloads\{
    Helper,
    Utility
};
use Xmf\Module\Admin;

/** @var Helper $helper */
/** @var Utility $utility */

$currentFile = basename(__FILE__);
require_once __DIR__ . '/admin_header.php';

if ('submit' === @$_POST['op']) {
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header($currentFile, 3, implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
    }

    $cloneDirname = $_POST['clonedirname'];

    // Check if name is valid
    if (empty($cloneDirname) || preg_match('/[^a-zA-Z0-9\_\-]/', $cloneDirname)) {
        redirect_header($currentFile, 3, sprintf(_AM_WFDOWNLOADS_CLONE_INVALIDNAME, $cloneDirname));
    }
    // Check wether the cloned module exists or not
    if ($cloneDirname && is_dir(XOOPS_ROOT_PATH . '/modules/' . $cloneDirname)) {
        redirect_header($currentFile, 3, sprintf(_AM_WFDOWNLOADS_CLONE_EXISTS, $cloneDirname));
    }
    // Check dirname length for template file name length issues (template file name cannot be longer than 50 chars)
    if (mb_strlen($cloneDirname) > 18) {
        redirect_header($currentFile, 3, sprintf(_AM_WFDOWNLOADS_CLONE_TOOLONG, $cloneDirname));
    }

    $patterns = [
        mb_strtolower(WFDOWNLOADS_DIRNAME)          => mb_strtolower($cloneDirname),
        mb_strtoupper(WFDOWNLOADS_DIRNAME)          => mb_strtoupper($cloneDirname),
        ucfirst(mb_strtolower(WFDOWNLOADS_DIRNAME)) => ucfirst(mb_strtolower($cloneDirname)),
    ];

    $patKeys   = array_keys($patterns);
    $patValues = array_values($patterns);
    wfdownloads_cloneFileDir(WFDOWNLOADS_ROOT_PATH);
    $logocreated = wfdownloads_createLogo(mb_strtolower($cloneDirname));

    $message = '';
    if (is_dir(XOOPS_ROOT_PATH . '/modules/' . mb_strtolower($cloneDirname))) {
        $message .= sprintf(_AM_WFDOWNLOADS_CLONE_CONGRAT, "<a href='" . XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin&op=installlist'>" . ucfirst(mb_strtolower($cloneDirname)) . '</a>') . "<br>\n";
        if (!$logocreated) {
            $message .= _AM_WFDOWNLOADS_CLONE_IMAGEFAIL;
        }
    } else {
        $message .= _AM_WFDOWNLOADS_CLONE_FAIL;
    }

    Utility::getCpHeader();
    $adminObject = Admin::getInstance();
    $adminObject->displayNavigation($currentFile);
    echo $message;
    require_once __DIR__ . '/admin_footer.php';
    exit();
}
Utility::getCpHeader();
$adminObject = Admin::getInstance();
$adminObject->displayNavigation($currentFile);
xoops_load('XoopsFormLoader');
$form              = new XoopsThemeForm(sprintf(_AM_WFDOWNLOADS_CLONE_TITLE, $helper->getModule()->getVar('name', 'E')), 'clone', $currentFile, 'post', true);
$cloneDirname_text = new XoopsFormText(_AM_WFDOWNLOADS_CLONE_NAME, 'clonedirname', 18, 18, '');
$cloneDirname_text->setDescription(_AM_WFDOWNLOADS_CLONE_NAME_DSC);
$form->addElement($cloneDirname_text, true);
$form->addElement(new XoopsFormHidden('op', 'submit'));
$form->addElement(new XoopsFormButton('', '', _SUBMIT, 'submit'));
$form->display();
require_once __DIR__ . '/admin_footer.php';
exit();

// recursive clonning script
/**
 * @param $path
 */
function wfdownloads_cloneFileDir($path)
{
    global $patKeys;
    global $patValues;

    $newPath = str_replace($patKeys[0], $patValues[0], $path);

    if (is_dir($path)) {
        // create new dir
        if (!mkdir($newPath) && !is_dir($newPath)) {
            throw new RuntimeException('The directory ' . $newPath . ' could not be created.');
        }

        // check all files in dir, and process it
        if (false !== ($handle = opendir($path))) {
            while (false !== ($file = readdir($handle))) {
                if ('.' !== $file && '..' !== $file && '.svn' !== $file) {
                    wfdownloads_cloneFileDir("{$path}/{$file}");
                }
            }
            closedir($handle);
        }
    } else {
        if (preg_match('/(.jpg|.gif|.png|.zip|.ttf)$/i', $path)) {
            // image
            copy($path, $newPath);
        } else {
            // file, read it
            $content = file_get_contents($path);
            $content = str_replace($patKeys, $patValues, $content);
            file_put_contents($newPath, $content);
        }
    }
}

/**
 * @param $dirname
 *
 * @return bool
 */
function wfdownloads_createLogo($dirname)
{
    $helper = Helper::getInstance();
    // Check extension/functions
    if (!extension_loaded('gd')) {
        return false;
    }
    $required_functions = [
        'imagecreatetruecolor',
        'imagecolorallocate',
        'imagefilledrectangle',
        'imagejpeg',
        'imagedestroy',
        'imageftbbox',
    ];
    foreach ($required_functions as $func) {
        if (!function_exists($func)) {
            return false;
        }
    }

    // Check original image/font
    if (!file_exists($imageBase = XOOPS_ROOT_PATH . "/modules/{$dirname}/assets/images/module_logo_blank.png")) {
        return false;
    }
    if (!file_exists($font = XOOPS_ROOT_PATH . "/modules/{$helper->getModule()->dirname()}/assets/images/VeraBd.ttf")) {
        return false;
    }
    // Create image
    $imageModule = imagecreatefrompng($imageBase);
    // Erase old text
    if ($imageModule) {
        $greyColor = imagecolorallocate($imageModule, 237, 237, 237);

        imagefilledrectangle($imageModule, 5, 35, 85, 46, $greyColor);
        // Write text
        $textColor       = imagecolorallocate($imageModule, 0, 0, 0);
        $space_to_border = (80 - mb_strlen($dirname) * 6.5) / 2;
        imagefttext($imageModule, 8.5, 0, $space_to_border, 45, $textColor, $font, ucfirst($dirname), []);
        // Set transparency color
        $whiteColor = imagecolorallocatealpha($imageModule, 255, 255, 255, 127);
        imagefill($imageModule, 0, 0, $whiteColor);
        imagecolortransparent($imageModule, $whiteColor);
        // Save new image
        imagepng($imageModule, XOOPS_ROOT_PATH . "/modules/{$dirname}/assets/images/module_logo.png");
        imagedestroy($imageModule);
    }

    return true;
}
