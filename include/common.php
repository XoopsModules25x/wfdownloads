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
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         wfdownload
 * @since           3.23
 * @author          Xoops Development Team
 */

use Xoopsmodules\wfdownloads;

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

include __DIR__ . '/../preloads/autoloader.php';

$moduleDirName = basename(dirname(__DIR__));

require_once __DIR__ . '/../class/Helper.php';
require_once __DIR__ . '/../class/Utility.php';


if (!defined('WFDOWNLOADS_MODULE_PATH')) {
    define('WFDOWNLOADS_DIRNAME', basename(dirname(__DIR__)));
    define('WFDOWNLOADS_URL', XOOPS_URL . '/modules/' . WFDOWNLOADS_DIRNAME);
    define('WFDOWNLOADS_IMAGE_URL', WFDOWNLOADS_URL . '/assets/images/');
    define('WFDOWNLOADS_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . WFDOWNLOADS_DIRNAME);
    define('WFDOWNLOADS_IMAGE_PATH', WFDOWNLOADS_ROOT_PATH . '/assets/images');
    define('WFDOWNLOADS_ADMIN_URL', WFDOWNLOADS_URL . '/admin/');
    define('WFDOWNLOADS_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . WFDOWNLOADS_DIRNAME);
    define('WFDOWNLOADS_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . WFDOWNLOADS_DIRNAME);
}


// This must contain the name of the folder in which reside Wfdownloads
//define('WFDOWNLOADS_DIRNAME', basename(dirname(__DIR__)));
//define('WFDOWNLOADS_URL', XOOPS_URL . '/modules/' . WFDOWNLOADS_DIRNAME);
//define('WFDOWNLOADS_IMAGES_URL', WFDOWNLOADS_URL . '/assets/images');
//define('WFDOWNLOADS_ADMIN_URL', WFDOWNLOADS_URL . '/admin');
//define('WFDOWNLOADS_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . WFDOWNLOADS_DIRNAME);

/** @var \XoopsDatabase $db */
/** @var wfdownloads\Helper $helper */
/** @var wfdownloads\Utility $utility */
$db           = \XoopsDatabaseFactory::getDatabase();
$helper       = wfdownloads\Helper::getInstance();
$utility      = new wfdownloads\Utility();
$configurator = new wfdownloads\Configurator();

$categoryHandler     = new wfdownloads\CategoryHandler($db);
$downloadHandler     = new wfdownloads\DownloadHandler($db);
$ipLogHandler        = new wfdownloads\IpLogHandler($db);
$mimetypeHandler     = new wfdownloads\MimetypeHandler($db);
$mirrorHandler       = new wfdownloads\MirrorHandler($db);
$modificationHandler = new wfdownloads\ModificationHandler($db);
$ratingHandler       = new wfdownloads\RatingHandler($db);
$reportHandler       = new wfdownloads\ReportHandler($db);
$reviewHandler       = new wfdownloads\ReviewHandler($db);


$helper->loadLanguage('common');

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/class/tree.php';
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';

require_once WFDOWNLOADS_ROOT_PATH . '/include/constants.php';
require_once WFDOWNLOADS_ROOT_PATH . '/class/session.php'; // WfdownloadsSession class
//require_once WFDOWNLOADS_ROOT_PATH . '/class/wfdownloads.php'; // WfdownloadsWfdownloads class
//require_once WFDOWNLOADS_ROOT_PATH . '/class/wfdownloads.php'; // WfdownloadsRequest class
require_once WFDOWNLOADS_ROOT_PATH . '/class/breadcrumb.php'; // Breadcrumb class
require_once WFDOWNLOADS_ROOT_PATH . '/class/ObjectTree.php'; // WfdownloadsObjectTree class
require_once WFDOWNLOADS_ROOT_PATH . '/class/XoopsTree.php'; // WfdownloadsXoopsTree class
//require_once WFDOWNLOADS_ROOT_PATH . '/class/formelementchoose.php'; // WfdownloadsFormElementChoose class
require_once WFDOWNLOADS_ROOT_PATH . '/class/multicolumnsthemeform.php'; // WfdownloadsMulticolumnsThemeForm class

xoops_load('XoopsUserUtility');
xoops_load('XoopsLocal');


$pathIcon16    = Xmf\Module\Admin::iconUrl('', 16);
$pathIcon32    = Xmf\Module\Admin::iconUrl('', 32);
$pathModIcon16 = $helper->getModule()->getInfo('modicons16');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

$icons = [
    'edit'    => "<img src='" . $pathIcon16 . "/edit.png'  alt=" . _EDIT . "' align='middle'>",
    'delete'  => "<img src='" . $pathIcon16 . "/delete.png' alt='" . _DELETE . "' align='middle'>",
    'clone'   => "<img src='" . $pathIcon16 . "/editcopy.png' alt='" . _CLONE . "' align='middle'>",
    'preview' => "<img src='" . $pathIcon16 . "/view.png' alt='" . _PREVIEW . "' align='middle'>",
    'print'   => "<img src='" . $pathIcon16 . "/printer.png' alt='" . _CLONE . "' align='middle'>",
    'pdf'     => "<img src='" . $pathIcon16 . "/pdf.png' alt='" . _CLONE . "' align='middle'>",
    'add'     => "<img src='" . $pathIcon16 . "/add.png' alt='" . _ADD . "' align='middle'>",
    '0'       => "<img src='" . $pathIcon16 . "/0.png' alt='" . _ADD . "' align='middle'>",
    '1'       => "<img src='" . $pathIcon16 . "/1.png' alt='" . _ADD . "' align='middle'>",
];

// MyTextSanitizer object
$myts = \MyTextSanitizer::getInstance();

$debug = false;

//This is needed or it will not work in blocks.
global $wfdownloads_isAdmin;

// Load only if module is installed

if (is_object($helper->getModule())) {
    // Find if the user is admin of the module
    //    $wfdownloads_isAdmin = wfdownloads\Utility::userIsAdmin();
}

// Load Xoops handlers
$moduleHandler       = xoops_getHandler('module');
$memberHandler       = xoops_getHandler('member');
$notificationHandler = xoops_getHandler('notification');
$gpermHandler        = xoops_getHandler('groupperm');
