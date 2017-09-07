<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

require_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';
require_once __DIR__ . '/common.php';

$moduleDirName = basename(dirname(__DIR__));
$capsDirName   = strtoupper($moduleDirName);

//if (!defined($capsDirName . '_DIRNAME')) {
//if (!defined(constant($capsDirName . '_DIRNAME'))) {
//    define($capsDirName . '_DIRNAME', $GLOBALS['xoopsModule']->dirname());
define($capsDirName . '_PATH', XOOPS_ROOT_PATH . '/modules/' . $moduleDirName);
//    define($capsDirName . '_URL', XOOPS_URL . '/modules/' . $moduleDirName);
define($capsDirName . '_ADMIN', constant($capsDirName . '_URL') . '/admin/index.php');
//    define($capsDirName . '_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . $moduleDirName);
define($capsDirName . '_AUTHOR_LOGOIMG', constant($capsDirName . '_URL') . '/assets/images/logoModule.png');
//}

// Define here the place where main upload path

//$img_dir = $GLOBALS['xoopsModuleConfig']['uploaddir'];

define($capsDirName . '_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . $moduleDirName); // WITHOUT Trailing slash
//define("XXXXXX_UPLOAD_PATH", $img_dir); // WITHOUT Trailing slash
define($capsDirName . '_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . $moduleDirName); // WITHOUT Trailing slash

//constant($cloned_lang . '_CATEGORY_NOTIFY')

$uploadFolders = [
    constant($capsDirName . '_UPLOAD_PATH'),
    constant($capsDirName . '_UPLOAD_PATH') . '/images',
    constant($capsDirName . '_UPLOAD_PATH') . '/images/thumbnails'
];

$copyFiles = [
    constant($capsDirName . '_UPLOAD_PATH'),
    constant($capsDirName . '_UPLOAD_PATH') . '/images',
    constant($capsDirName . '_UPLOAD_PATH') . '/images/thumbnails'
];

$oldFiles = [
    '/include/update_functions.php',
    '/include/install_functions.php'
];

/*
//Configurator
return array(
    'name'           => 'Module Configurator',
    'uploadFolders'  => array(
        constant($capsDirName . '_UPLOAD_PATH'),
        constant($capsDirName . '_UPLOAD_PATH') . '/batch',
        constant($capsDirName . '_UPLOAD_PATH') . '/images',
        constant($capsDirName . '_UPLOAD_PATH') . '/images/category',
        constant($capsDirName . '_UPLOAD_PATH') . '/images/category/thumbs',
        constant($capsDirName . '_UPLOAD_PATH') . '/images/screenshots',
        constant($capsDirName . '_UPLOAD_PATH') . '/images/screenshots/thumbs',
    ),
    'blankFiles' => array(
        constant($capsDirName . '_UPLOAD_PATH'),
        constant($capsDirName . '_UPLOAD_PATH') . '/batch',
        constant($capsDirName . '_UPLOAD_PATH') . '/images',
        constant($capsDirName . '_UPLOAD_PATH') . '/images/category',
        constant($capsDirName . '_UPLOAD_PATH') . '/images/category/thumbs',
        constant($capsDirName . '_UPLOAD_PATH') . '/images/screenshots',
        constant($capsDirName . '_UPLOAD_PATH') . '/images/screenshots/thumbs',
    ),

    'templateFolders' => array(
        '/templates/',
        '/templates/blocks/',
        '/templates/admin/'

    ),
    'oldFiles'        => array(
        '/include/update_functions.php',
        '/include/install_functions.php'
    ),
    'oldFolders'      => array(
        '/images',
        '/css',
        '/js',
        '/tcpdf',
        '/images',
    ),
);
*/

//Configurator Class

/**
 * Class WfdownloadsConfigurator
 */
class WfdownloadsConfigurator
{
    public $uploadFolders   = [];
    public $blankFiles      = [];
    public $templateFolders = [];
    public $oldFiles        = [];
    public $oldFolders      = [];
    public $name;

    public function __construct()
    {
        $moduleDirName       = basename(dirname(__DIR__));
        $capsDirName         = strtoupper($moduleDirName);
        $this->name          = 'Module Configurator';
        $this->uploadFolders = [
            constant($capsDirName . '_UPLOAD_PATH'),
            constant($capsDirName . '_UPLOAD_PATH') . '/batch',
            constant($capsDirName . '_UPLOAD_PATH') . '/images',
            constant($capsDirName . '_UPLOAD_PATH') . '/images/category',
            constant($capsDirName . '_UPLOAD_PATH') . '/images/category/thumbs',
            constant($capsDirName . '_UPLOAD_PATH') . '/images/screenshots',
            constant($capsDirName . '_UPLOAD_PATH') . '/images/screenshots/thumbs',
        ];
        $this->blankFiles    = [
            constant($capsDirName . '_UPLOAD_PATH'),
            constant($capsDirName . '_UPLOAD_PATH') . '/batch',
            constant($capsDirName . '_UPLOAD_PATH') . '/images',
            constant($capsDirName . '_UPLOAD_PATH') . '/images/category',
            constant($capsDirName . '_UPLOAD_PATH') . '/images/category/thumbs',
            constant($capsDirName . '_UPLOAD_PATH') . '/images/screenshots',
            constant($capsDirName . '_UPLOAD_PATH') . '/images/screenshots/thumbs',
        ];

        $this->templateFolders = [
            '/templates/',
            '/templates/blocks/',
            '/templates/admin/'

        ];
        $this->oldFiles        = [
            '/include/update_functions.php',
            '/include/install_functions.php'
        ];
        $this->oldFolders      = [
            '/images',
            '/css',
            '/js',
            '/tcpdf',
            '/images',
        ];
    }
}

// module information
$modCopyright = "<a href='https://xoops.org' title='XOOPS Project' target='_blank'>
                     <img src='" . constant($capsDirName . '_AUTHOR_LOGOIMG') . "' alt='XOOPS Project'></a>";
