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
 * Module: wfdownloads
 *
 * @category        Module
 * @package         Wfdownloads
 * @author          XOOPS Development Team <https://xoops.org>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         GPL 2.0 or later
 * @link            https://xoops.org/
 * @since           1.0.0
 */

use XoopsModules\Wfdownloads;
use XoopsModules\Wfdownloads\Common;

require_once  dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

include  dirname(__DIR__) . '/preloads/autoloader.php';

$op = \Xmf\Request::getCmd('op', '');

switch ($op) {
    case 'load':
        loadSampleData();
        break;
    case 'save':
        saveSampleData();
        break;
    case 'exportschema':
        exportSchema();
        break;
}

// XMF TableLoad for SAMPLE data

function loadSampleData()
{
    $moduleDirName = basename(dirname(__DIR__));
    /** @var \XoopsModules\Wfdownloads\Helper $helper */
    $helper        = \XoopsModules\Wfdownloads\Helper::getInstance();
    $utility       = new Wfdownloads\Utility();
    $configurator  = new Wfdownloads\Common\Configurator();
    // Load language files
    $helper->loadLanguage('admin');
    $helper->loadLanguage('modinfo');
    $helper->loadLanguage('common');

    $tables = \Xmf\Module\Helper::getHelper($moduleDirName)->getModule()->getInfo('tables');

    foreach ($tables as $table) {
        $tabledata = \Xmf\Yaml::readWrapped($table . '.yml');
        \Xmf\Database\TableLoad::truncateTable($moduleDirName . '_' . $table);
        \Xmf\Database\TableLoad::loadTableFromArray($moduleDirName . '_' . $table, $tabledata);
    }

    //  ---  COPY test folder files ---------------
    if (count($configurator->copyTestFolders) > 0) {
        //        $file =  dirname(__DIR__) . '/testdata/images/';
        foreach (array_keys($configurator->copyTestFolders) as $i) {
            $src  = $configurator->copyTestFolders[$i][0];
            $dest = $configurator->copyTestFolders[$i][1];
            $utility::rcopy($src, $dest);
        }
    }
    redirect_header('../admin/index.php', 1, constant('CO_' . $moduleDirNameUpper . '_' . 'SAMPLEDATA_SUCCESS'));
}

function saveSampleData()
{
    $moduleDirName      = basename(dirname(__DIR__));
    $moduleDirNameUpper = strtoupper($moduleDirName);

    $tables = \Xmf\Module\Helper::getHelper($moduleDirName)->getModule()->getInfo('tables');

    foreach ($tables as $table) {
        \Xmf\Database\TableLoad::saveTableToYamlFile($table, $table . '_' . date('Y-m-d H-i-s') . '.yml');
    }

//    $migrate = new  Wfdownloads\Migrate($moduleDirName);
//    foreach ($migrate->moduleTables as $table) {
//        \Xmf\Database\TableLoad::saveTableToYamlFile($table, $table . '_' . date("Y-m-d H-i-s") . '.yml');
//    }

    redirect_header('../admin/index.php', 1, constant('CO_' . $moduleDirNameUpper . '_' . 'SAMPLEDATA_SUCCESS'));
}

function exportSchema()
{
    try {
        $moduleDirName      = basename(dirname(__DIR__));
        $moduleDirNameUpper = strtoupper($moduleDirName);

        $migrate = new  Wfdownloads\Migrate($moduleDirName);
        $migrate->saveCurrentSchema();

        redirect_header('../admin/index.php', 1, constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA_SUCCESS'));
    } catch (\Exception $e) {
        exit(constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA_ERROR'));
    }
}
