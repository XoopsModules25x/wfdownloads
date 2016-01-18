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

$modversion['name']        = _MI_WFDOWNLOADS_NAME;
$modversion['version']     = 3.23;
$modversion['description'] = _MI_WFDOWNLOADS_DESC;
$modversion['author']      = 'XOOPS Development Team';
$modversion['credits']     = 'This module was originally based on Mydownloads, and refactored by Catzwolf and the WF-Projects team. Then it became a project of The SmartFactory who continued the excellent work started by the WF-Projects team.';
$modversion['help']        = 'page=help';
$modversion['license']     = 'GNU GPL 2.0 or later';
$modversion['license_url'] = "http://www.gnu.org/licenses/gpl-2.0.html";
$modversion['official']    = false;
$modversion['dirname']     = basename(__DIR__);

include_once XOOPS_ROOT_PATH . "/modules/" . $modversion['dirname'] . "/include/constants.php";

// Path and name of the module’s logo
$modversion['image'] = 'assets/images/module_logo.png';

// Install, update, unistall
$modversion['onInstall']   = 'include/oninstall.php';
$modversion['onUpdate']    = 'include/onupdate.php';
$modversion['onUninstall'] = 'include/onuninstall.php';

$modversion['date']         = '2014-11-22';
$modversion['release_date'] = '2014/11/22';
$modversion['status']       = 'RC4';
$modversion['teammembers']  = 'Bender, David, FrankBlack, Xpider, M0nty, Mithrandir, Marcan, felix[fx2024], Sudhaker, Jegelstaff';

// About
$modversion["module_status"]       = "RC4";
$modversion['releasedate']         = '2014-11-22';
$modversion["module_website_url"]  = "http://www.xoops.org/";
$modversion["module_website_name"] = "XOOPS";
$modversion['min_php']             = '5.5';
$modversion['min_xoops']           = '2.5.7.2';
$modversion['min_admin']           = '1.1';
$modversion['min_db']              = array(
    'mysql'  => '5.0.7',
    'mysqli' => '5.0.7'
);
$modversion['dirmoduleadmin']      = 'Frameworks/moduleclasses';
$modversion['icons16']             = 'Frameworks/moduleclasses/icons/16';
$modversion['icons32']             = 'Frameworks/moduleclasses/icons/32';

// Help files
$i                                     = 0;
$modversion['helpsection'][$i]['name'] = _MI_WFDOWNLOADS_HELP_OVERVIEW;
$modversion['helpsection'][$i]['link'] = "page=help";
++$i;
$modversion['helpsection'][$i]['name'] = _MI_WFDOWNLOADS_HELP_INSTALL;
$modversion['helpsection'][$i]['link'] = "page=install";
++$i;
$modversion['helpsection'][$i]['name'] = _MI_WFDOWNLOADS_HELP_TIPSTRICKS;
$modversion['helpsection'][$i]['link'] = "page=tips_tricks";
++$i;
$modversion['helpsection'][$i]['name'] = _MI_WFDOWNLOADS_HELP_IMPORT;
$modversion['helpsection'][$i]['link'] = "page=help3";
++$i;
$modversion['helpsection'][$i]['name'] = _MI_WFDOWNLOADS_HELP_UPDATE1;
$modversion['helpsection'][$i]['link'] = "page=help4";
++$i;
$modversion['helpsection'][$i]['name'] = _MI_WFDOWNLOADS_HELP_UPDATE2;
$modversion['helpsection'][$i]['link'] = "page=help5";
++$i;
$modversion['helpsection'][$i]['name'] = _MI_WFDOWNLOADS_HELP_UPDATE3;
$modversion['helpsection'][$i]['link'] = "page=help6";

/*
* added by Liquid. Based on code by Marcan
*/
$modversion['author_realname']     = "The SmartFactory";
$modversion['author_website_url']  = "http://www.smartfactory.ca";
$modversion['author_website_name'] = "The SmartFactory";
$modversion['author_email']        = "info@smartfactory.ca";
$modversion['demo_site_url']       = "http://smartfactory.ca/modules/wfdownloads/";
$modversion['demo_site_name']      = "SmartFactory.ca";
$modversion['support_site_url']    = "http://smartfactory.ca/modules/newbb/viewforum.php?forum=12";
$modversion['support_site_name']   = "SmartFactory.ca";
$modversion['submit_bug']          = "http://dev.xoops.org/modules/xfmod/tracker/?group_id=1289&atid=1325";
$modversion['submit_feature']      = "http://dev.xoops.org/modules/xfmod/tracker/?group_id=1289&atid=1328";

$modversion['warning']        = _MI_WFDOWNLOADS_WARNINGTEXT;
$modversion['author_credits'] = _MI_WFDOWNLOADS_AUTHOR_CREDITSTEXT;

// Admin things
$modversion['hasAdmin']   = true;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu']  = "admin/menu.php";
// If you want your module has a sub menu in system menu set it to 1
$modversion['system_menu'] = true;

// Sql file (must contain sql generated by phpMyAdmin or phpPgAdmin)
// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
// Tables created by sql file (without prefix!)

$modversion['tables'] = array(
    $modversion['dirname'] . '_broken',
    $modversion['dirname'] . '_cat',
    $modversion['dirname'] . '_downloads',
    $modversion['dirname'] . '_mod',
    $modversion['dirname'] . '_votedata',
    $modversion['dirname'] . '_indexpage',
    $modversion['dirname'] . '_reviews',
    $modversion['dirname'] . '_mimetypes',
    $modversion['dirname'] . '_meta',
    $modversion['dirname'] . '_mirrors',
    $modversion['dirname'] . '_ip_log'
);

// Search
$modversion['hasSearch']      = true;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = $modversion["dirname"] . '_search';

// Menu
$modversion['hasMain']     = true;
$modversion['system_menu'] = true;

global $xoopsModule, $xoopsModuleConfig;
// check if submission is allowed
$isSubmissionAllowed = false;
if (is_object($xoopsModule) && $xoopsModule->dirname() == $modversion['dirname'] && $xoopsModule->isactive()) {
    if (is_object($GLOBALS['xoopsUser'])
        && ($xoopsModuleConfig['submissions'] == _WFDOWNLOADS_SUBMISSIONS_DOWNLOAD
            || $xoopsModuleConfig['submissions'] == _WFDOWNLOADS_SUBMISSIONS_BOTH)
    ) {
        // if user is a registered user
        $groups = $GLOBALS['xoopsUser']->getGroups();
        if (count(array_intersect($xoopsModuleConfig['submitarts'], $groups)) > 0) {
            $isSubmissionAllowed = true;
        }
    } else {
        // if user is anonymous
        if ($xoopsModuleConfig['anonpost'] == _WFDOWNLOADS_ANONPOST_DOWNLOAD || $xoopsModuleConfig['anonpost'] == _WFDOWNLOADS_ANONPOST_BOTH) {
            $isSubmissionAllowed = true;
        }
    }
}
$i = 0;
if ($isSubmissionAllowed) {
    ++$i;
    $modversion['sub'][$i]['name'] = _MI_WFDOWNLOADS_SMNAME1;
    $category_suffix               = (!empty($_GET['cid'])) ? "?cid=" . intval($_GET['cid']) : ""; //Added by Lankford on 2008/2/20
    $modversion['sub'][$i]['url']  = "submit.php{$category_suffix}";
}

// ------------------- Menu -------------------
$modversion['sub'][] = array(
    'name' => _MI_WFDOWNLOADS_SMNAME2,
    'url'  => "topten.php?list=hit"
);
$modversion['sub'][] = array(
    'name' => _MI_WFDOWNLOADS_SMNAME3,
    'url'  => "topten.php?list=rate"
);

// Blocks
$i                                       = 0;
$modversion['blocks'][$i]['file']        = "top.php";
$modversion['blocks'][$i]['name']        = _MI_WFDOWNLOADS_BNAME1;
$modversion['blocks'][$i]['description'] = "Shows recently added download files";
$modversion['blocks'][$i]['show_func']   = $modversion['dirname'] . "_top_show";
$modversion['blocks'][$i]['edit_func']   = $modversion['dirname'] . "_top_edit";
$modversion['blocks'][$i]['options']     = "published|10|19";
$modversion['blocks'][$i]['template']    = $modversion['dirname'] . "_mb_new.tpl";
++$i;
$modversion['blocks'][$i]['file']        = "top.php";
$modversion['blocks'][$i]['name']        = _MI_WFDOWNLOADS_BNAME2;
$modversion['blocks'][$i]['description'] = "Shows most downloaded files";
$modversion['blocks'][$i]['show_func']   = $modversion['dirname'] . "_top_show";
$modversion['blocks'][$i]['edit_func']   = $modversion['dirname'] . "_top_edit";
$modversion['blocks'][$i]['options']     = "hits|10|19";
$modversion['blocks'][$i]['template']    = $modversion['dirname'] . "_mb_top.tpl";
++$i;
$modversion['blocks'][$i]['file']        = "top_by_cat.php";
$modversion['blocks'][$i]['name']        = _MI_WFDOWNLOADS_BNAME3;
$modversion['blocks'][$i]['description'] = "Shows most downloaded files by top categories";
$modversion['blocks'][$i]['show_func']   = $modversion['dirname'] . "_top_by_cat_show";
$modversion['blocks'][$i]['edit_func']   = $modversion['dirname'] . "_top_by_cat_edit";
$modversion['blocks'][$i]['options']     = "hits|10|19";
$modversion['blocks'][$i]['template']    = $modversion['dirname'] . "_mb_top_by_cat.tpl";

// Comments
$modversion['hasComments']             = true;
$modversion['comments']['itemName']    = 'lid';
$modversion['comments']['pageName']    = 'singlefile.php';
$modversion['comments']['extraParams'] = array('cid');
// Comment callback functions
$modversion['comments']['callbackFile']        = 'include/comment_functions.php';
$modversion['comments']['callback']['approve'] = $modversion['dirname'] . '_com_approve';
$modversion['comments']['callback']['update']  = $modversion['dirname'] . '_com_update';

// Templates
$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_header.tpl',
    'description' => 'Header info'
);
$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_footer.tpl',
    'description' => 'Footer info'
);
$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_brokenfile.tpl',
    'description' => ''
);
$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_download.tpl',
    'description' => ''
);
$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_index.tpl',
    'description' => ''
);
$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_ratefile.tpl',
    'description' => ''
);
$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_singlefile.tpl',
    'description' => ''
);
$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_topten.tpl',
    'description' => ''
);
$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_viewcat.tpl',
    'description' => ''
);
$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_newlistindex.tpl',
    'description' => ''
);
$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_reviews.tpl',
    'description' => ''
);
$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_mirrors.tpl',
    'description' => ''
);
$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_disclaimer.tpl',
    'description' => ''
);

// Admin templates

$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_submit.tpl',
    'description' => ''
);
$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_am_categorieslist.tpl',
    'type'        => 'admin',
    'description' => ''
);

$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_am_downloadslist.tpl',
    'type'        => 'admin',
    'description' => ''
);

$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_am_ip_logslist.tpl',
    'type'        => 'admin',
    'description' => ''
);

$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_am_reportsmodificationslist.tpl',
    'type'        => 'admin',
    'description' => ''
);

$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_am_ratingslist.tpl',
    'type'        => 'admin',
    'description' => ''
);

$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_am_reviewslist.tpl',
    'type'        => 'admin',
    'description' => ''
);

$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_am_mirrorslist.tpl',
    'type'        => 'admin',
    'description' => ''
);

$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_am_mimetypeslist.tpl',
    'type'        => 'admin',
    'description' => ''
);

$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_am_permissions.tpl',
    'type'        => 'admin',
    'description' => ''
);

// Common templates
$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_co_breadcrumb.tpl',
    'description' => ''
);

$modversion['templates'][] = array(
    'file'        => $modversion['dirname'] . '_co_letterschoice.tpl',
    'description' => ''
);

// ------------------- Preferences -------------------

xoops_load('XoopsEditorHandler');
$editor_handler         = XoopsEditorHandler::getInstance();
$editorList             = array_flip($editor_handler->getList());
$modversion['config'][] = array(
    'name'        => 'editor_options',
    'title'       => '_MI_WFDOWNLOADS_EDITOR',
    'description' => '_MI_WFDOWNLOADS_EDITORCHOICE',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => $editorList,
    'default'     => 'dhtmltextarea'
);

$modversion['config'][] = array(
    'name'        => 'displayicons',
    'title'       => '_MI_WFDOWNLOADS_ICONDISPLAY',
    'description' => '_MI_WFDOWNLOADS_DISPLAYICONDSC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'options'     => array(
        '_MI_WFDOWNLOADS_DISPLAYICON1' => _WFDOWNLOADS_DISPLAYICONS_ICON,
        '_MI_WFDOWNLOADS_DISPLAYICON2' => _WFDOWNLOADS_DISPLAYICONS_TEXT,
        '_MI_WFDOWNLOADS_DISPLAYICON3' => _WFDOWNLOADS_DISPLAYICONS_NO
    ),
    'default'     => _WFDOWNLOADS_DISPLAYICONS_ICON
);

$modversion['config'][] = array(
    'name'        => 'popular',
    'title'       => '_MI_WFDOWNLOADS_POPULAR',
    'description' => '_MI_WFDOWNLOADS_POPULARDSC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'options'     => array('5' => 5, '10' => 10, '50' => 50, '100' => 100, '200' => 200, '500' => 500, '1000' => 1000),
    'default'     => 100
);

$modversion['config'][] = array(
    'name'        => 'daysnew',
    'title'       => '_MI_WFDOWNLOADS_DAYSNEW',
    'description' => '_MI_WFDOWNLOADS_DAYSNEWDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10
);

$modversion['config'][] = array(
    'name'        => 'daysupdated',
    'title'       => '_MI_WFDOWNLOADS_DAYSUPDATED',
    'description' => '_MI_WFDOWNLOADS_DAYSUPDATEDDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10
);

$modversion['config'][] = array(
    'name'        => 'perpage',
    'title'       => '_MI_WFDOWNLOADS_PERPAGE',
    'description' => '_MI_WFDOWNLOADS_PERPAGEDSC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'options'     => array('5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30, '50' => 50),
    'default'     => 10
);

$modversion['config'][] = array(
    'name'        => 'admin_perpage',
    'title'       => '_MI_WFDOWNLOADS_ADMINPAGE',
    'description' => '_MI_WFDOWNLOADS_ADMINPAGEDESC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'options'     => array('5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30, '50' => 50),
    'default'     => 10
);

$modversion['config'][] = array(
    'name'        => 'dateformat',
    'title'       => '_MI_WFDOWNLOADS_DATEFORMAT',
    'description' => '_MI_WFDOWNLOADS_DATEFORMATDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => _DATESTRING
); //'D, d-M-Y');

// Upload configs
$modversion['config'][] = array(
    'name'        => 'upload_configs',
    'title'       => '_MI_WFDOWNLOADS_UPLOAD_CONFIGS',
    'description' => '_MI_WFDOWNLOADS_UPLOAD_CONFIGS',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head'
);

$modversion['config'][] = array(
    'name'        => 'submissions',
    'title'       => '_MI_WFDOWNLOADS_ALLOWSUBMISS',
    'description' => '_MI_WFDOWNLOADS_ALLOWSUBMISSDSC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'options'     => array(
        '_MI_WFDOWNLOADS_ALLOWSUBMISS1' => _WFDOWNLOADS_SUBMISSIONS_NONE,
        '_MI_WFDOWNLOADS_ALLOWSUBMISS2' => _WFDOWNLOADS_SUBMISSIONS_DOWNLOAD,
        '_MI_WFDOWNLOADS_ALLOWSUBMISS3' => _WFDOWNLOADS_SUBMISSIONS_MIRROR,
        '_MI_WFDOWNLOADS_ALLOWSUBMISS4' => _WFDOWNLOADS_SUBMISSIONS_BOTH
    ),
    'default'     => _WFDOWNLOADS_SUBMISSIONS_NONE
);

$modversion['config'][] = array(
    'name'        => 'anonpost',
    'title'       => '_MI_WFDOWNLOADS_ANONPOST',
    'description' => '_MI_WFDOWNLOADS_ANONPOSTDSC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'options'     => array(
        '_MI_WFDOWNLOADS_ANONPOST1' => _WFDOWNLOADS_ANONPOST_NONE,
        '_MI_WFDOWNLOADS_ANONPOST2' => _WFDOWNLOADS_ANONPOST_DOWNLOAD,
        '_MI_WFDOWNLOADS_ANONPOST3' => _WFDOWNLOADS_ANONPOST_MIRROR,
        '_MI_WFDOWNLOADS_ANONPOST4' => _WFDOWNLOADS_ANONPOST_BOTH
    ),
    'default'     => _WFDOWNLOADS_ANONPOST_NONE
);

$modversion['config'][] = array(
    'name'        => 'autoapprove',
    'title'       => '_MI_WFDOWNLOADS_AUTOAPPROVE',
    'description' => '_MI_WFDOWNLOADS_AUTOAPPROVEDSC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'options'     => array(
        '_MI_WFDOWNLOADS_AUTOAPPROVE1' => _WFDOWNLOADS_AUTOAPPROVE_NONE,
        '_MI_WFDOWNLOADS_AUTOAPPROVE2' => _WFDOWNLOADS_AUTOAPPROVE_DOWNLOAD,
        '_MI_WFDOWNLOADS_AUTOAPPROVE3' => _WFDOWNLOADS_AUTOAPPROVE_MIRROR,
        '_MI_WFDOWNLOADS_AUTOAPPROVE4' => _WFDOWNLOADS_AUTOAPPROVE_BOTH
    ),
    'default'     => _WFDOWNLOADS_AUTOAPPROVE_NONE
);

$modversion['config'][] = array(
    'name'        => 'submitarts',
    'title'       => '_MI_WFDOWNLOADS_SUBMITART',
    'description' => '_MI_WFDOWNLOADS_SUBMITARTDSC',
    'formtype'    => 'group_multi',
    'valuetype'   => 'array',
    'default'     => '1'
);

$modversion['config'][] = array(
    'name'        => 'useruploads',
    'title'       => '_MI_WFDOWNLOADS_ALLOWUPLOADS',
    'description' => '_MI_WFDOWNLOADS_ALLOWUPLOADSDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => false
);

$modversion['config'][] = array(
    'name'        => 'useruploadsgroup',
    'title'       => '_MI_WFDOWNLOADS_ALLOWUPLOADSGROUP',
    'description' => '_MI_WFDOWNLOADS_ALLOWUPLOADSGROUPDSC',
    'formtype'    => 'group_multi',
    'valuetype'   => 'array',
    'default'     => '1'
);

$modversion['config'][] = array(
    'name'        => 'upload_minposts',
    'title'       => '_MI_WFDOWNLOADS_UPLOADMINPOSTS',
    'description' => '_MI_WFDOWNLOADS_UPLOADMINPOSTSDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 0
);

$modversion['config'][] = array(
    'name'        => 'showdisclaimer',
    'title'       => '_MI_WFDOWNLOADS_SHOWDISCLAIMER',
    'description' => '_MI_WFDOWNLOADS_SHOWDISCLAIMERDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => false
);

$modversion['config'][] = array(
    'name'        => 'disclaimer',
    'title'       => '_MI_WFDOWNLOADS_DISCLAIMER',
    'description' => '_MI_WFDOWNLOADS_DISCLAIMERDSC',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    'default'     => _MI_WFDOWNLOADS_DISCLAIMER_DEFAULT
);

// Download configs
$modversion['config'][] = array(
    'name'        => 'download_configs',
    'title'       => '_MI_WFDOWNLOADS_DOWNLOAD_CONFIGS',
    'description' => '_MI_WFDOWNLOADS_UPLOAD_CONFIGS',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head'
);

$modversion['config'][] = array(
    'name'        => 'download_minposts',
    'title'       => '_MI_WFDOWNLOADS_DOWNLOADMINPOSTS',
    'description' => '_MI_WFDOWNLOADS_DOWNLOADMINPOSTSDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 0
);

$modversion['config'][] = array(
    'name'        => 'showDowndisclaimer',
    'title'       => '_MI_WFDOWNLOADS_SHOWDOWNDISCL',
    'description' => '_MI_WFDOWNLOADS_SHOWDOWNDISCLDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => false
);

$modversion['config'][] = array(
    'name'        => 'downdisclaimer',
    'title'       => '_MI_WFDOWNLOADS_DOWNDISCLAIMER',
    'description' => '_MI_WFDOWNLOADS_DOWNDISCLAIMERDSC',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    'default'     => _MI_WFDOWNLOADS_DOWNDISCLAIMER_DEFAULT
);

// Images/screenshots/thumbs configs
$modversion['config'][] = array(
    'name'        => 'images_configs',
    'title'       => '_MI_WFDOWNLOADS_IMAGES_CONFIGS',
    'description' => '_MI_WFDOWNLOADS_IMAGES_CONFIGSDSC',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head'
);

$modversion['config'][] = array(
    'name'        => 'screenshot',
    'title'       => '_MI_WFDOWNLOADS_USESHOTS',
    'description' => '_MI_WFDOWNLOADS_USESHOTSDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => true
);

$modversion['config'][] = array(
    'name'        => 'screenshots',
    'title'       => '_MI_WFDOWNLOADS_SCREENSHOTS',
    'description' => '_MI_WFDOWNLOADS_SCREENSHOTSDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'uploads/' . $modversion['dirname'] . '/images/screenshots'
);

$modversion['config'][] = array(
    'name'        => 'max_screenshot',
    'title'       => '_MI_WFDOWNLOADS_MAXSHOTS',
    'description' => '_MI_WFDOWNLOADS_MAXSHOTSDSC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'options'     => array('1' => 1, '2' => 2, '3' => 3, '4' => 4),
    'default'     => 4
);

$modversion['config'][] = array(
    'name'        => 'catimage',
    'title'       => '_MI_WFDOWNLOADS_CATEGORYIMG',
    'description' => '_MI_WFDOWNLOADS_CATEGORYIMGDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'uploads/' . $modversion['dirname'] . '/images/category'
);

$modversion['config'][] = array(
    'name'        => 'cat_imgwidth',
    'title'       => '_MI_WFDOWNLOADS_CAT_IMGWIDTH',
    'description' => '_MI_WFDOWNLOADS_CAT_IMGWIDTHDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 64
); // =1024/16

$modversion['config'][] = array(
    'name'        => 'cat_imgheight',
    'title'       => '_MI_WFDOWNLOADS_CAT_IMGHEIGHT',
    'description' => '_MI_WFDOWNLOADS_CAT_IMGHEIGHTDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 48
); // =768/16

$modversion['config'][] = array(
    'name'        => 'mainimagedir',
    'title'       => '_MI_WFDOWNLOADS_MAINIMGDIR',
    'description' => '_MI_WFDOWNLOADS_MAINIMGDIRDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'uploads/' . $modversion['dirname'] . '/images'
);

$modversion['config'][] = array(
    'name'        => 'usethumbs',
    'title'       => '_MI_WFDOWNLOADS_USETHUMBS',
    'description' => '_MI_WFDOWNLOADS_USETHUMBSDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => true
);

$modversion['config'][] = array(
    'name'        => 'shotwidth',
    'title'       => '_MI_WFDOWNLOADS_SHOTWIDTH',
    'description' => '_MI_WFDOWNLOADS_SHOTWIDTHDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 64
); // =1024/16

$modversion['config'][] = array(
    'name'        => 'shotheight',
    'title'       => '_MI_WFDOWNLOADS_SHOTHEIGHT',
    'description' => '_MI_WFDOWNLOADS_SHOTHEIGHTDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 48
); // =768/16

$modversion['config'][] = array(
    'name'        => 'keepaspect',
    'title'       => '_MI_WFDOWNLOADS_KEEPASPECT',
    'description' => '_MI_WFDOWNLOADS_KEEPASPECTDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => true
);

$modversion['config'][] = array(
    'name'        => 'imagequality',
    'title'       => '_MI_WFDOWNLOADS_QUALITY',
    'description' => '_MI_WFDOWNLOADS_QUALITYDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 100
);

$modversion['config'][] = array(
    'name'        => 'updatethumbs',
    'title'       => '_MI_WFDOWNLOADS_IMGUPDATE',
    'description' => '_MI_WFDOWNLOADS_IMGUPDATEDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => true
);

// Files configs
$modversion['config'][] = array(
    'name'        => 'filesuploads_configs',
    'title'       => '_MI_WFDOWNLOADS_FILESUPLOADS_CONFIGS',
    'description' => '_MI_WFDOWNLOADS_FILESUPLOADS_CONFIGSDSC',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head'
);

$modversion['config'][] = array(
    'name'        => 'maxfilesize',
    'title'       => '_MI_WFDOWNLOADS_MAXFILESIZE',
    'description' => '_MI_WFDOWNLOADS_MAXFILESIZEDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 2097152
); // 2MB

$modversion['config'][] = array(
    'name'        => 'uploaddir',
    'title'       => '_MI_WFDOWNLOADS_UPLOADDIR',
    'description' => '_MI_WFDOWNLOADS_UPLOADDIRDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => XOOPS_ROOT_PATH . '/uploads/' . $modversion['dirname']
);

$modversion['config'][] = array(
    'name'        => 'maximgwidth',
    'title'       => '_MI_WFDOWNLOADS_IMGWIDTH',
    'description' => '_MI_WFDOWNLOADS_IMGWIDTHDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 1024
);

$modversion['config'][] = array(
    'name'        => 'maximgheight',
    'title'       => '_MI_WFDOWNLOADS_IMGHEIGHT',
    'description' => '_MI_WFDOWNLOADS_IMGHEIGHTDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 768
);

$modversion['config'][] = array(
    'name'        => 'batchdir',
    'title'       => '_MI_WFDOWNLOADS_BATCHDIR',
    'description' => '_MI_WFDOWNLOADS_BATCHDIRDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => XOOPS_ROOT_PATH . '/uploads/' . $modversion['dirname'] . '/batch'
);

// extra systems configs
$modversion['config'][] = array(
    'name'        => 'extrasystems_configs',
    'title'       => '_MI_WFDOWNLOADS_SCREENSHOTS_ESTRASYSTEMS',
    'description' => '_MI_WFDOWNLOADS_SCREENSHOTS_ESTRASYSTEMSDSC',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head'
);

$modversion['config'][] = array(
    'name'        => 'enable_reviews',
    'title'       => '_MI_WFDOWNLOADS_REVIEW_ENABLE',
    'description' => '_MI_WFDOWNLOADS_REVIEW_ENABLEDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => true
);

$modversion['config'][] = array(
    'name'        => 'rev_anonpost',
    'title'       => '_MI_WFDOWNLOADS_REVIEWANONPOST',
    'description' => '_MI_WFDOWNLOADS_REVIEWANONPOSTDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => false
);

$modversion['config'][] = array(
    'name'        => 'rev_approve',
    'title'       => '_MI_WFDOWNLOADS_REVIEWAPPROVE',
    'description' => '_MI_WFDOWNLOADS_REVIEWAPPROVEDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => false
);

$modversion['config'][] = array(
    'name'        => 'enable_ratings',
    'title'       => '_MI_WFDOWNLOADS_RATING_ENABLE',
    'description' => '_MI_WFDOWNLOADS_RATING_ENABLEDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => true
);

$modversion['config'][] = array(
    'name'        => 'enable_brokenreports',
    'title'       => '_MI_WFDOWNLOADS_BROKENREPORT_ENABLE',
    'description' => '_MI_WFDOWNLOADS_BROKENREPORT_ENABLEDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => true
);

$modversion['config'][] = array(
    'name'        => 'enablerss',
    'title'       => '_MI_WFDOWNLOADS_ENABLERSS',
    'description' => '_MI_WFDOWNLOADS_ENABLERSSDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => false
);

$modversion['config'][] = array(
    'name'        => 'enable_mirrors',
    'title'       => '_MI_WFDOWNLOADS_MIRROR_ENABLE',
    'description' => '_MI_WFDOWNLOADS_MIRROR_ENABLEDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => true
);

$modversion['config'][] = array(
    'name'        => 'enable_onlinechk',
    'title'       => '_MI_WFDOWNLOADS_MIRROR_ENABLEONCHK',
    'description' => '_MI_WFDOWNLOADS_MIRROR_ENABLEONCHKDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => true
);

$modversion['config'][] = array(
    'name'        => 'copyright',
    'title'       => '_MI_WFDOWNLOADS_COPYRIGHT',
    'description' => '_MI_WFDOWNLOADS_COPYRIGHTDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => true
);

// Various configs
$modversion['config'][] = array(
    'name'        => 'various_configs',
    'title'       => '_MI_WFDOWNLOADS_VARIOUS_CONFIGS',
    'description' => '_MI_WFDOWNLOADS_VARIOUS_CONFIGSDSC',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head'
);

$modversion['config'][] = array(
    'name'        => 'check_host',
    'title'       => '_MI_WFDOWNLOADS_CHECKHOST',
    'description' => '_MI_WFDOWNLOADS_CHECKHOSTDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => false
);

$modversion['config'][] = array(
    'name'        => 'referers',
    'title'       => '_MI_WFDOWNLOADS_REFERERS',
    'description' => '_MI_WFDOWNLOADS_REFERERSDSC',
    'formtype'    => 'textarea',
    'valuetype'   => 'array'
);

$modversion['config'][] = array(
    'name'        => 'subcats',
    'title'       => '_MI_WFDOWNLOADS_SUBCATS',
    'description' => '_MI_WFDOWNLOADS_SUBCATSDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => false
);

$modversion['config'][] = array(
    'name'        => 'subcatssortby',
    'title'       => '_MI_WFDOWNLOADS_SUBCATSSORTBY',
    'description' => '_MI_WFDOWNLOADS_SUBCATSSORTBYDSC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => array(
        '_MI_WFDOWNLOADS_SUBCATSSORTBYCID'    => 'cid',
        '_MI_WFDOWNLOADS_SUBCATSSORTBYTITLE'  => 'title',
        '_MI_WFDOWNLOADS_SUBCATSSORTBYWEIGHT' => 'weight'
    ),
    'default'     => 'weight'
);

$qa                     = ' (A)';
$qd                     = ' (D)';
$modversion['config'][] = array(
    'name'        => 'filexorder',
    'title'       => '_MI_WFDOWNLOADS_ARTICLESSORT',
    'description' => '_MI_WFDOWNLOADS_ARTICLESSORTDSC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => array(
        _MI_WFDOWNLOADS_TITLE . $qa      => 'title ASC',
        _MI_WFDOWNLOADS_TITLE . $qd      => 'title DESC',
        _MI_WFDOWNLOADS_SUBMITTED2 . $qa => 'published ASC',
        _MI_WFDOWNLOADS_SUBMITTED2 . $qd => 'published DESC',
        _MI_WFDOWNLOADS_RATING . $qa     => 'rating ASC',
        _MI_WFDOWNLOADS_RATING . $qd     => 'rating DESC',
        _MI_WFDOWNLOADS_POPULARITY . $qa => 'hits ASC',
        _MI_WFDOWNLOADS_POPULARITY . $qd => 'hits DESC'
    ),
    'default'     => 'title ASC'
);

$modversion['config'][] = array(
    'name'        => 'autosummary',
    'title'       => '_MI_WFDOWNLOADS_AUTOSUMMARY',
    'description' => '_MI_WFDOWNLOADS_AUTOSUMMARYDESC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'options'     => array(
        '_MI_WFDOWNLOADS_AUTOSUMMARY1' => _WFDOWNLOADS_AUTOSUMMARY_NO,
        '_MI_WFDOWNLOADS_AUTOSUMMARY2' => _WFDOWNLOADS_AUTOSUMMARY_IFBLANK,
        '_MI_WFDOWNLOADS_AUTOSUMMARY3' => _WFDOWNLOADS_AUTOSUMMARY_YES
    ),
    'default'     => _WFDOWNLOADS_AUTOSUMMARY_NO
);

$modversion['config'][] = array(
    'name'        => 'autosumlength',
    'title'       => '_MI_WFDOWNLOADS_AUTOSUMMARYLENGTH',
    'description' => '_MI_WFDOWNLOADS_AUTOSUMMARYLENGTHDESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 200
);

$modversion['config'][] = array(
    'name'        => 'autosumplaintext',
    'title'       => '_MI_WFDOWNLOADS_AUTOSUMMARYPLAINTEXT',
    'description' => '_MI_WFDOWNLOADS_AUTOSUMMARYPLAINTEXTDESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => true
);

$modversion['config'][] = array(
    'name'        => 'platform',
    'title'       => '_MI_WFDOWNLOADS_PLATFORM',
    'description' => '_MI_WFDOWNLOADS_PLATFORMDSC',
    'formtype'    => 'textarea',
    'valuetype'   => 'array',
    'default'     => 'None|Windows|Unix|Mac|Xoops 2.5.5|Xoops 2.5.6|Xoops 2.5.7|Xoops 2.6.0|Other'
);

$license                = array();
$license[]              = 'None';
$license[]              = 'Apache License (v. 1.1) ';
$license[]              = 'Apple Public Source License (v. 2.0)';
$license[]              = 'Berkeley Database License ';
$license[]              = 'BSD License (Original)';
$license[]              = 'Common Public License';
$license[]              = 'FreeBSD Copyright (Modifizierte BSD-Lizenz) ';
$license[]              = 'GNU Emacs General Public License';
$license[]              = 'GNU Free Documentation License (FDL) (v. 1.2)';
$license[]              = 'GNU General Public License (GPL) (v. 1.0)';
$license[]              = 'GNU General Public License (GPL) (v. 2.0)';
$license[]              = 'GNU General Public License (GPL) (v. 3.0)';
$license[]              = 'GNU Lesser General Public License (LGPL) (v. 2.1)';
$license[]              = 'GNU Library General Public License (LGPL) (v. 2.0)';
$license[]              = 'Microsoft Shared Source License';
$license[]              = 'MIT License';
$license[]              = 'Mozilla Public License (v. 1.1)';
$license[]              = 'Open Software License (OSL) (v. 1.0)';
$license[]              = 'Open Software License (OSL) (v. 1.1)';
$license[]              = 'Open Software License (OSL) (v. 2.0)';
$license[]              = 'Open Public License';
$license[]              = 'Open RTLinux Patent License (v. 1.0)';
$license[]              = 'PHP License (v. 3.0)';
$license[]              = 'W3C Software Notice and License';
$license[]              = 'Wide Open License (WOL)';
$license[]              = 'X.Net License';
$license[]              = 'X Window System License';

$modversion['config'][] = array(
    'name'        => 'license',
    'title'       => '_MI_WFDOWNLOADS_LICENSE',
    'description' => '_MI_WFDOWNLOADS_LICENSEDSC',
    'formtype'    => 'textarea',
    'valuetype'   => 'array',
    'default'     => $license
);

$modversion['config'][] = array(
    'name'        => 'limitations',
    'title'       => '_MI_WFDOWNLOADS_LIMITS',
    'description' => '_MI_WFDOWNLOADS_LIMITSDSC',
    'formtype'    => 'textarea',
    'valuetype'   => 'array',
    'default'     => 'None|Trial|14 day limitation|None Save'
);

$modversion['config'][] = array(
    'name'        => 'versiontypes',
    'title'       => '_MI_WFDOWNLOADS_VERSIONTYPES',
    'description' => '_MI_WFDOWNLOADS_VERSIONTYPESDSC',
    'formtype'    => 'textarea',
    'valuetype'   => 'array',
    'default'     => 'None|Alpha|Beta|RC|Final'
);

/*
// Swish-e support EXPERIMENTAL
// Swish-e configs
$modversion['config'][] = array(
    'name'        => 'swishe_configs',
    'title'       => '_MI_WFDOWNLOADS_SWISHE_CONFIGS',
    'description' => '_MI_WFDOWNLOADS_SWISHE_CONFIGSDSC',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'head'
);

$modversion['config'][] = array(
    'name'        => 'enable_swishe',
    'title'       => '_MI_WFDOWNLOADS_SWISHE_ENABLE',
    'description' => '_MI_WFDOWNLOADS_SWISHE_ENABLEDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => false
);

$modversion['config'][] = array(
    'name'        => 'swishe_exe_path',
    'title'       => '_MI_WFDOWNLOADS_SWISHE_EXEPATH',
    'description' => '_MI_WFDOWNLOADS_SWISHE_EXEPATHDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => XOOPS_ROOT_PATH . '/uploads/' . $modversion['dirname'] . '/swishe'
);

$modversion['config'][] = array(
    'name'        => 'swishe_search_limit',
    'title'       => '_MI_WFDOWNLOADS_SWISHE_SEARCHLIMIT',
    'description' => '_MI_WFDOWNLOADS_SWISHE_SEARCHLIMITDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 0
);
// Swish-e support EXPERIMENTAL
*/

// ------------------- Notifications -------------------
$modversion['hasNotification']             = true;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = $modversion['dirname'] . '_notify_iteminfo';

$i                                                            = 0;
$modversion['notification']['category'][$i]['name']           = 'global';
$modversion['notification']['category'][$i]['title']          = _MI_WFDOWNLOADS_GLOBAL_NOTIFY;
$modversion['notification']['category'][$i]['description']    = _MI_WFDOWNLOADS_GLOBAL_NOTIFYDSC;
$modversion['notification']['category'][$i]['item_name']      = '';
$modversion['notification']['category'][$i]['subscribe_from'] = array('index.php', 'viewcat.php', 'singlefile.php');
++$i;
$modversion['notification']['category'][$i]['name']           = 'category';
$modversion['notification']['category'][$i]['title']          = _MI_WFDOWNLOADS_CATEGORY_NOTIFY;
$modversion['notification']['category'][$i]['description']    = _MI_WFDOWNLOADS_CATEGORY_NOTIFYDSC;
$modversion['notification']['category'][$i]['subscribe_from'] = array('viewcat.php', 'singlefile.php');
$modversion['notification']['category'][$i]['item_name']      = 'cid';
$modversion['notification']['category'][$i]['allow_bookmark'] = true;
++$i;
$modversion['notification']['category'][$i]['name']           = 'file';
$modversion['notification']['category'][$i]['title']          = _MI_WFDOWNLOADS_FILE_NOTIFY;
$modversion['notification']['category'][$i]['description']    = _MI_WFDOWNLOADS_FILE_NOTIFYDSC;
$modversion['notification']['category'][$i]['subscribe_from'] = 'singlefile.php';
$modversion['notification']['category'][$i]['item_name']      = 'lid';
$modversion['notification']['category'][$i]['allow_bookmark'] = true;

$i                                                        = 0;
$modversion['notification']['event'][$i]['name']          = 'new_category';
$modversion['notification']['event'][$i]['category']      = 'global';
$modversion['notification']['event'][$i]['title']         = _MI_WFDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFY;
$modversion['notification']['event'][$i]['caption']       = _MI_WFDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFYCAP;
$modversion['notification']['event'][$i]['description']   = _MI_WFDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFYDSC;
$modversion['notification']['event'][$i]['mail_template'] = 'global_newcategory_notify';
$modversion['notification']['event'][$i]['mail_subject']  = _MI_WFDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFYSBJ;
++$i;
$modversion['notification']['event'][$i]['name']          = 'file_modify';
$modversion['notification']['event'][$i]['category']      = 'global';
$modversion['notification']['event'][$i]['admin_only']    = true;
$modversion['notification']['event'][$i]['title']         = _MI_WFDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFY;
$modversion['notification']['event'][$i]['caption']       = _MI_WFDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFYCAP;
$modversion['notification']['event'][$i]['description']   = _MI_WFDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFYDSC;
$modversion['notification']['event'][$i]['mail_template'] = 'global_filemodify_notify';
$modversion['notification']['event'][$i]['mail_subject']  = _MI_WFDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFYSBJ;
++$i;
$modversion['notification']['event'][$i]['name']          = 'file_broken';
$modversion['notification']['event'][$i]['category']      = 'global';
$modversion['notification']['event'][$i]['admin_only']    = true;
$modversion['notification']['event'][$i]['title']         = _MI_WFDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFY;
$modversion['notification']['event'][$i]['caption']       = _MI_WFDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFYCAP;
$modversion['notification']['event'][$i]['description']   = _MI_WFDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFYDSC;
$modversion['notification']['event'][$i]['mail_template'] = 'global_filebroken_notify';
$modversion['notification']['event'][$i]['mail_subject']  = _MI_WFDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFYSBJ;
++$i;
$modversion['notification']['event'][$i]['name']          = 'file_submit';
$modversion['notification']['event'][$i]['category']      = 'global';
$modversion['notification']['event'][$i]['admin_only']    = true;
$modversion['notification']['event'][$i]['title']         = _MI_WFDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFY;
$modversion['notification']['event'][$i]['caption']       = _MI_WFDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFYCAP;
$modversion['notification']['event'][$i]['description']   = _MI_WFDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFYDSC;
$modversion['notification']['event'][$i]['mail_template'] = 'global_filesubmit_notify';
$modversion['notification']['event'][$i]['mail_subject']  = _MI_WFDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFYSBJ;
++$i;
$modversion['notification']['event'][$i]['name']          = 'new_file';
$modversion['notification']['event'][$i]['category']      = 'global';
$modversion['notification']['event'][$i]['title']         = _MI_WFDOWNLOADS_GLOBAL_NEWFILE_NOTIFY;
$modversion['notification']['event'][$i]['caption']       = _MI_WFDOWNLOADS_GLOBAL_NEWFILE_NOTIFYCAP;
$modversion['notification']['event'][$i]['description']   = _MI_WFDOWNLOADS_GLOBAL_NEWFILE_NOTIFYDSC;
$modversion['notification']['event'][$i]['mail_template'] = 'global_newfile_notify';
$modversion['notification']['event'][$i]['mail_subject']  = _MI_WFDOWNLOADS_GLOBAL_NEWFILE_NOTIFYSBJ;
++$i;
$modversion['notification']['event'][$i]['name']          = 'file_submit';
$modversion['notification']['event'][$i]['category']      = 'category';
$modversion['notification']['event'][$i]['admin_only']    = true;
$modversion['notification']['event'][$i]['title']         = _MI_WFDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFY;
$modversion['notification']['event'][$i]['caption']       = _MI_WFDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFYCAP;
$modversion['notification']['event'][$i]['description']   = _MI_WFDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFYDSC;
$modversion['notification']['event'][$i]['mail_template'] = 'category_filesubmit_notify';
$modversion['notification']['event'][$i]['mail_subject']  = _MI_WFDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFYSBJ;
++$i;
$modversion['notification']['event'][$i]['name']          = 'new_file';
$modversion['notification']['event'][$i]['category']      = 'category';
$modversion['notification']['event'][$i]['title']         = _MI_WFDOWNLOADS_CATEGORY_NEWFILE_NOTIFY;
$modversion['notification']['event'][$i]['caption']       = _MI_WFDOWNLOADS_CATEGORY_NEWFILE_NOTIFYCAP;
$modversion['notification']['event'][$i]['description']   = _MI_WFDOWNLOADS_CATEGORY_NEWFILE_NOTIFYDSC;
$modversion['notification']['event'][$i]['mail_template'] = 'category_newfile_notify';
$modversion['notification']['event'][$i]['mail_subject']  = _MI_WFDOWNLOADS_CATEGORY_NEWFILE_NOTIFYSBJ;
++$i;
$modversion['notification']['event'][$i]['name']          = 'approve';
$modversion['notification']['event'][$i]['category']      = 'file';
$modversion['notification']['event'][$i]['invisible']     = true;
$modversion['notification']['event'][$i]['title']         = _MI_WFDOWNLOADS_FILE_APPROVE_NOTIFY;
$modversion['notification']['event'][$i]['caption']       = _MI_WFDOWNLOADS_FILE_APPROVE_NOTIFYCAP;
$modversion['notification']['event'][$i]['description']   = _MI_WFDOWNLOADS_FILE_APPROVE_NOTIFYDSC;
$modversion['notification']['event'][$i]['mail_template'] = 'file_approve_notify';
$modversion['notification']['event'][$i]['mail_subject']  = _MI_WFDOWNLOADS_FILE_APPROVE_NOTIFYSBJ;
++$i;
$modversion['notification']['event'][$i]['name']          = 'filemodified';
$modversion['notification']['event'][$i]['category']      = 'file';
$modversion['notification']['event'][$i]['title']         = _MI_WFDOWNLOADS_FILE_FILEMODIFIED_NOTIFY;
$modversion['notification']['event'][$i]['caption']       = _MI_WFDOWNLOADS_FILE_FILEMODIFIED_NOTIFYCAP;
$modversion['notification']['event'][$i]['description']   = _MI_WFDOWNLOADS_FILE_FILEMODIFIED_NOTIFYDSC;
$modversion['notification']['event'][$i]['mail_template'] = 'file_filemodified_notify';
$modversion['notification']['event'][$i]['mail_subject']  = _MI_WFDOWNLOADS_FILE_FILEMODIFIED_NOTIFYSBJ;
++$i;
$modversion['notification']['event'][$i]['name']          = 'filemodified';
$modversion['notification']['event'][$i]['category']      = 'category';
$modversion['notification']['event'][$i]['title']         = _MI_WFDOWNLOADS_CATEGORY_FILEMODIFIED_NOTIFY;
$modversion['notification']['event'][$i]['caption']       = _MI_WFDOWNLOADS_CATEGORY_FILEMODIFIED_NOTIFYCAP;
$modversion['notification']['event'][$i]['description']   = _MI_WFDOWNLOADS_CATEGORY_FILEMODIFIED_NOTIFYDSC;
$modversion['notification']['event'][$i]['mail_template'] = 'category_filemodified_notify';
$modversion['notification']['event'][$i]['mail_subject']  = _MI_WFDOWNLOADS_CATEGORY_FILEMODIFIED_NOTIFYSBJ;
++$i;
$modversion['notification']['event'][$i]['name']          = 'filemodified';
$modversion['notification']['event'][$i]['category']      = 'global';
$modversion['notification']['event'][$i]['title']         = _MI_WFDOWNLOADS_GLOBAL_FILEMODIFIED_NOTIFY;
$modversion['notification']['event'][$i]['caption']       = _MI_WFDOWNLOADS_GLOBAL_FILEMODIFIED_NOTIFYCAP;
$modversion['notification']['event'][$i]['description']   = _MI_WFDOWNLOADS_GLOBAL_FILEMODIFIED_NOTIFYDSC;
$modversion['notification']['event'][$i]['mail_template'] = 'global_filemodified_notify';
$modversion['notification']['event'][$i]['mail_subject']  = _MI_WFDOWNLOADS_GLOBAL_FILEMODIFIED_NOTIFYSBJ;
