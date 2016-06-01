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

// Module Info
// The name of this module
define('_MI_WFDOWNLOADS_NAME', "Wfdownloads");
// A brief description of this module
define('_MI_WFDOWNLOADS_DESC', "Creates a downloads section where users can download/submit/rate various files.");
// Names of blocks for this module (Not all module has blocks)
define('_MI_WFDOWNLOADS_BNAME1', "Recent Downloads");
define('_MI_WFDOWNLOADS_BNAME2', "Top Downloads");
define('_MI_WFDOWNLOADS_BNAME3', "Top Downloads by top categories");
// Sub menu titles
define('_MI_WFDOWNLOADS_SMNAME1', "Submit");
define('_MI_WFDOWNLOADS_SMNAME2', "Popular");
define('_MI_WFDOWNLOADS_SMNAME3', "Top rated");
// Names of admin menu items
//define('_MI_WFDOWNLOADS_BINDEX', "Manager"); // NOT USED FROM 3.23
//define('_MI_WFDOWNLOADS_INDEXPAGE', "Index Page"); // NOT USED FROM 3.23
//define('_MI_WFDOWNLOADS_MCATEGORY', "Categories"); // NOT USED FROM 3.23
//define('_MI_WFDOWNLOADS_MDOWNLOADS', "Downloads"); // NOT USED FROM 3.23
//define('_MI_WFDOWNLOADS_REVIEWS', "Reviews"); // NOT USED FROM 3.23
//define('_MI_WFDOWNLOADS_MUPLOADS', "Images Upload"); // NOT USED FROM 3.23
//define('_MI_WFDOWNLOADS_MMIMETYPES', "MIME types"); // NOT USED FROM 3.23
//define('_MI_WFDOWNLOADS_PERMISSIONS', "Permissions"); // NOT USED FROM 3.23
//define('_MI_WFDOWNLOADS_MVOTEDATA', "Votes"); // NOT USED FROM 3.23
//define('_MI_WFDOWNLOADS_MMIRRORS', "Mirrors"); // NOT USED FROM 3.23
// Title of config items
define('_MI_WFDOWNLOADS_POPULAR', "[status] Downloads popular count");
define('_MI_WFDOWNLOADS_POPULARDSC', "The number of hits before a download status will be considered as popular.");
//Display Icons
define('_MI_WFDOWNLOADS_ICONDISPLAY', "[status] Display downloads popular and new icons");
define('_MI_WFDOWNLOADS_DISPLAYICONDSC', "Select how to display the popular and new icons in download listing.");
define('_MI_WFDOWNLOADS_DISPLAYICON1', "Display as icons");
define('_MI_WFDOWNLOADS_DISPLAYICON2', "Display as text");
define('_MI_WFDOWNLOADS_DISPLAYICON3', "Do not display");
define('_MI_WFDOWNLOADS_DAYSNEW', "[status] Downloads days 'new'");
define('_MI_WFDOWNLOADS_DAYSNEWDSC', "The number of days a download status will be considered 'new'.");
define('_MI_WFDOWNLOADS_DAYSUPDATED', "[status] Downloads days updated");
define('_MI_WFDOWNLOADS_DAYSUPDATEDDSC', "The number of days a download status will be considered 'update'.");
define('_MI_WFDOWNLOADS_PERPAGE', "Download listing count");
define('_MI_WFDOWNLOADS_PERPAGEDSC', "Number of Downloads to display in each category listing.");
define('_MI_WFDOWNLOADS_TEMPLATESET', "Select Template Set");
define('_MI_WFDOWNLOADS_TEMPLATESETDSC', "Select Templates to use for your module. <br />This will allow you to choose how your downloads are listed");
define('_MI_WFDOWNLOADS_TEMPLATESET1', "Default");
define('_MI_WFDOWNLOADS_TEMPLATESET2', "Professional");
define('_MI_WFDOWNLOADS_USESHOTS', "[screenshots] Display screenshot images");
define('_MI_WFDOWNLOADS_USESHOTSDSC', "Select '" . _YES . "' to display screenshot images for each download item.");
define('_MI_WFDOWNLOADS_SHOTWIDTH', "[thumbnails] Thumbnails width (pixels)");
define('_MI_WFDOWNLOADS_SHOTWIDTHDSC', "Display width for thumbnails image.");
define('_MI_WFDOWNLOADS_SHOTHEIGHT', "[thumbnails] Thumbnails height (pixels)");
define('_MI_WFDOWNLOADS_SHOTHEIGHTDSC', "Display height for thumbnails image.");
define('_MI_WFDOWNLOADS_CHECKHOST', "[leeching] Disallow direct download linking");
define('_MI_WFDOWNLOADS_REFERERS', "[leeching] These sites can directly link to your files");
define('_MI_WFDOWNLOADS_REFERERSDSC', "Separate with | ");
define('_MI_WFDOWNLOADS_CAT_IMGWIDTH', "[categories images] Categories images display width");
define('_MI_WFDOWNLOADS_CAT_IMGWIDTHDSC', "Display width for category image.");
define('_MI_WFDOWNLOADS_CAT_IMGHEIGHT', "[categories images] Categories images display height");
define('_MI_WFDOWNLOADS_CAT_IMGHEIGHTDSC', "Display height for category image.");
define('_MI_WFDOWNLOADS_ANONPOST', "[submissions] Anonymous user submission");
define('_MI_WFDOWNLOADS_ANONPOSTDSC', "Allow Anonymous users to submit new Downloads and/or Mirrors to your website.");
define('_MI_WFDOWNLOADS_ANONPOST1', "None");
define('_MI_WFDOWNLOADS_ANONPOST2', "Download only");
define('_MI_WFDOWNLOADS_ANONPOST3', "Mirror only");
define('_MI_WFDOWNLOADS_ANONPOST4', "Both");
define('_MI_WFDOWNLOADS_AUTOAPPROVE', "[submissions] Auto approve submitted Downloads and/or Mirrors");
define('_MI_WFDOWNLOADS_AUTOAPPROVEDSC', "Select to approve submitted Downloads and/or Mirrors without moderation.");
define('_MI_WFDOWNLOADS_AUTOAPPROVE1', "None");
define('_MI_WFDOWNLOADS_AUTOAPPROVE2', "Download only");
define('_MI_WFDOWNLOADS_AUTOAPPROVE3', "Mirror only");
define('_MI_WFDOWNLOADS_AUTOAPPROVE4', "Both");
define('_MI_WFDOWNLOADS_REVIEWAPPROVE', "[reviews] Auto approve submitted reviews");
define('_MI_WFDOWNLOADS_REVIEWAPPROVEDSC', "Select to approve submitted reviews without moderation.");
define('_MI_WFDOWNLOADS_REVIEWANONPOST', "[reviews] Anonymous user reviews");
define('_MI_WFDOWNLOADS_REVIEWANONPOSTDSC', "Allow Anonymous users to submit new reviews to your website.");
define('_MI_WFDOWNLOADS_MAXFILESIZE', "[upload files] Max file size (bytes)");
define('_MI_WFDOWNLOADS_MAXFILESIZEDSC', "Maximum file size permitted with file uploads.<br />WARNING: Upload File Size Limit is also influenced by 'upload_max_filesize', 'post_max_size' and 'memory_limit' php.ini directives");
define('_MI_WFDOWNLOADS_IMGWIDTH', "[upload files] Max upload image width (pixels)");
define('_MI_WFDOWNLOADS_IMGWIDTHDSC', "Maximum image width permitted when uploading image files.");
define('_MI_WFDOWNLOADS_IMGHEIGHT', "[upload files] Max upload image height (pixels)");
define('_MI_WFDOWNLOADS_IMGHEIGHTDSC', "Maximum image height permitted when uploading image files.");
define('_MI_WFDOWNLOADS_AUTOSUMMARY', "[auto summary] Enable download auto summary");
define('_MI_WFDOWNLOADS_AUTOSUMMARYDESC', "Automatically create download summary based on x amount of characters defined.");
define('_MI_WFDOWNLOADS_AUTOSUMMARYLENGTH', "[auto summary] Auto summary length");
define('_MI_WFDOWNLOADS_AUTOSUMMARYLENGTHDESC', "The maximum amount of characters displayed for the summary.");
define('_MI_WFDOWNLOADS_UPLOADDIR', "[upload files] Upload directory");
define('_MI_WFDOWNLOADS_UPLOADDIRDSC', "Upload directory *MUST* be an absolute path! <br />No trailing slash.");
define('_MI_WFDOWNLOADS_ENABLERSS', "[RSS feeds] Enable RSS Feeds");
define('_MI_WFDOWNLOADS_ENABLERSSDSC', "Select '" . _YES . "' to enable RSS feeds.");
define('_MI_WFDOWNLOADS_DOWNLOADMINPOSTS', "Minimum posts required to download");
define('_MI_WFDOWNLOADS_DOWNLOADMINPOSTSDSC', "Enter the minimum number of posts required to download a file.");
define('_MI_WFDOWNLOADS_UPLOADMINPOSTS', "Minimum posts required to upload");
define('_MI_WFDOWNLOADS_UPLOADMINPOSTSDSC', "Enter the minimum number of posts required to upload a file.");
define('_MI_WFDOWNLOADS_ALLOWSUBMISS', "[submissions] User submissions");
define('_MI_WFDOWNLOADS_ALLOWSUBMISSDSC', "Allow users to submit new Downloads and/or Mirrors.");
define('_MI_WFDOWNLOADS_ALLOWSUBMISS1', "None");
define('_MI_WFDOWNLOADS_ALLOWSUBMISS2', "Download only");
define('_MI_WFDOWNLOADS_ALLOWSUBMISS3', "Mirror only");
define('_MI_WFDOWNLOADS_ALLOWSUBMISS4', "Both");
define('_MI_WFDOWNLOADS_ALLOWUPLOADS', "[submissions] User can upload files");
define('_MI_WFDOWNLOADS_ALLOWUPLOADSDSC', "Allow Users to upload files directly to your website. <br />This includes both files & screenshots!");
define('_MI_WFDOWNLOADS_ALLOWUPLOADSGROUP', "[submissions] Groups uploading files");
define('_MI_WFDOWNLOADS_ALLOWUPLOADSGROUPDSC', "Select groups that can upload files directly to your website. <br />This includes both files & screenshots!");
define('_MI_WFDOWNLOADS_SCREENSHOTS', "[screenshots] Screenshots upload directory");
define('_MI_WFDOWNLOADS_CATEGORYIMG', "[categories images] Categories images upload directory");
define('_MI_WFDOWNLOADS_MAINIMGDIR', "[index page] Main images directory");
define('_MI_WFDOWNLOADS_USETHUMBS', "[thumbnails] Use thumbnails");
define('_MI_WFDOWNLOADS_USETHUMBSDSC', "Supported file types: JPG, GIF, PNG. <br />Module will use thumbnails for images (category & screenshots). <br />Set to '" . _NO
    . "' to use original image if the server does not support this option.");
define('_MI_WFDOWNLOADS_DATEFORMAT', "Date format");
define('_MI_WFDOWNLOADS_DATEFORMATDSC', "Default date format for module front end. <br />More info here: <a href='http://www.php.net/manual/en/function.date.php'>http://www.php.net/manual/en/function.date.php</a>");
define('_MI_WFDOWNLOADS_SHOWDISCLAIMER', "[disclaimer] Show disclaimer before user submission");
define('_MI_WFDOWNLOADS_SHOWDOWNDISCL', "[disclaimer] Show disclaimer before user download");
define('_MI_WFDOWNLOADS_DISCLAIMER', "[disclaimer] Submission disclaimer text");
define('_MI_WFDOWNLOADS_DOWNDISCLAIMER', "[disclaimer] Download disclaimer text");
define('_MI_WFDOWNLOADS_PLATFORM', "Enter platforms");
define('_MI_WFDOWNLOADS_SUBCATS', "Subcategories");
define('_MI_WFDOWNLOADS_VERSIONTYPES', "Version status");
define('_MI_WFDOWNLOADS_LICENSE', "Enter licenses");
define('_MI_WFDOWNLOADS_LIMITS', "Enter file limitations");
define('_MI_WFDOWNLOADS_MAXSHOTS', "[screenshots] Select max number of screenshots");
define('_MI_WFDOWNLOADS_MAXSHOTSDSC', "Sets the maximum number of allowed screenshot uploads.");
define('_MI_WFDOWNLOADS_SUBMITART', "[submissions] Download submission");
define('_MI_WFDOWNLOADS_SUBMITARTDSC', "Select groups that can submit new downloads. <br />Webmasters are automatically selected!");
define('_MI_WFDOWNLOADS_IMGUPDATE', "[thumbnails] Update thumbnails");
define('_MI_WFDOWNLOADS_IMGUPDATEDSC', "If selected '" . _YES . "' thumbnail images will be updated at each page render, otherwise the first thumbnail image will be used regardless.");
define('_MI_WFDOWNLOADS_QUALITY', "[thumbnails] Thumbnail quality");
define('_MI_WFDOWNLOADS_QUALITYDSC', "Quality lowest: 0 highest: 100.");
define('_MI_WFDOWNLOADS_KEEPASPECT', "[thumbnails] Keep image aspect ratio");
define('_MI_WFDOWNLOADS_KEEPASPECTDSC', "");
define('_MI_WFDOWNLOADS_ADMINPAGE', "Admin index files count");
define('_MI_WFDOWNLOADS_AMDMINPAGEDSC', "Number of new files to display in module admin area.");
define('_MI_WFDOWNLOADS_ARTICLESSORT', "Default download order");
define('_MI_WFDOWNLOADS_ARTICLESSORTDSC', "Select the default order for the download listings.");
define('_MI_WFDOWNLOADS_TITLE', "Title");
define('_MI_WFDOWNLOADS_RATING', "Rating");
define('_MI_WFDOWNLOADS_WEIGHT', "Weight");
define('_MI_WFDOWNLOADS_POPULARITY', "Popularity");
define('_MI_WFDOWNLOADS_SUBMITTED2', "Submission date");
define('_MI_WFDOWNLOADS_COPYRIGHT', "Copyright notice");
define('_MI_WFDOWNLOADS_COPYRIGHTDSC', "Select to display a copyright notice on download page.");
// Description of each config items
define('_MI_WFDOWNLOADS_PLATFORMDSC', "List of platforms to enter. <br />Separate with | <br />IMPORTANT: Do not change this once the site is live, add new to the end of the list!");
define('_MI_WFDOWNLOADS_SUBCATSDSC', "Select '" . _YES . "' to display subcategories. Selecting '" . _NO . "' will hide sub-categories from the listings.");
define('_MI_WFDOWNLOADS_LICENSEDSC', "List of licenses to enter. <br />Separate with | <br />IMPORTANT: Do not change this once the site is live, add new to the end of the list!");
// Text for notifications
define('_MI_WFDOWNLOADS_GLOBAL_NOTIFY', "Global");
define('_MI_WFDOWNLOADS_GLOBAL_NOTIFYDSC', "Global downloads notification options.");
define('_MI_WFDOWNLOADS_CATEGORY_NOTIFY', "Category");
define('_MI_WFDOWNLOADS_CATEGORY_NOTIFYDSC', "Notification options that apply to the current file category.");
define('_MI_WFDOWNLOADS_FILE_NOTIFY', "File");
define('_MI_WFDOWNLOADS_FILE_NOTIFYDSC', "Notification options that apply to the current file.");
define('_MI_WFDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFY', "New Category");
define('_MI_WFDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFYCAP', "Notify me when a new file category is created.");
define('_MI_WFDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFYDSC', "Receive notification when a new file category is created.");
define('_MI_WFDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFYSBJ', "[{X_SITENAME}] {X_MODULE} auto-notify: New file category.");
define('_MI_WFDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFY', "Modify File Requested");
define('_MI_WFDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFYCAP', "Notify me of any file modification request.");
define('_MI_WFDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFYDSC', "Receive notification when any file modification request is submitted.");
define('_MI_WFDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFYSBJ', "[{X_SITENAME}] {X_MODULE} auto-notify: File Modification Requested");
define('_MI_WFDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFY', "Broken File Submitted");
define('_MI_WFDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFYCAP', "Notify me of any broken file report.");
define('_MI_WFDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFYDSC', "Receive notification when any broken file report is submitted.");
define('_MI_WFDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFYSBJ', "[{X_SITENAME}] {X_MODULE} auto-notify: Broken File Reported");
define('_MI_WFDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFY', "File Submitted");
define('_MI_WFDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFYCAP', "Notify me when any new file is submitted (awaiting approval).");
define('_MI_WFDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFYDSC', "Receive notification when any new file is submitted (awaiting approval).");
define('_MI_WFDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFYSBJ', "[{X_SITENAME}] {X_MODULE} auto-notify: New file submitted");
define('_MI_WFDOWNLOADS_GLOBAL_NEWFILE_NOTIFY', "New File");
define('_MI_WFDOWNLOADS_GLOBAL_NEWFILE_NOTIFYCAP', "Notify me when any new file is posted.");
define('_MI_WFDOWNLOADS_GLOBAL_NEWFILE_NOTIFYDSC', "Receive notification when any new file is posted.");
define('_MI_WFDOWNLOADS_GLOBAL_NEWFILE_NOTIFYSBJ', "[{X_SITENAME}] {X_MODULE} auto-notify: New file");
define('_MI_WFDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFY', "File Submitted");
define('_MI_WFDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFYCAP', "Notify me when a new file is submitted (awaiting approval) to the current category.");
define('_MI_WFDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFYDSC', "Receive notification when a new file is submitted (awaiting approval) to the current category.");
define('_MI_WFDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFYSBJ', "[{X_SITENAME}] {X_MODULE} auto-notify: New file submitted in category");
define('_MI_WFDOWNLOADS_CATEGORY_NEWFILE_NOTIFY', "New File");
define('_MI_WFDOWNLOADS_CATEGORY_NEWFILE_NOTIFYCAP', "Notify me when a new file is posted to the current category.");
define('_MI_WFDOWNLOADS_CATEGORY_NEWFILE_NOTIFYDSC', "Receive notification when a new file is posted to the current category.");
define('_MI_WFDOWNLOADS_CATEGORY_NEWFILE_NOTIFYSBJ', "[{X_SITENAME}] {X_MODULE} auto-notify: New file in category");
define('_MI_WFDOWNLOADS_FILE_APPROVE_NOTIFY', "File Approved");
define('_MI_WFDOWNLOADS_FILE_APPROVE_NOTIFYCAP', "Notify me when this file is approved.");
define('_MI_WFDOWNLOADS_FILE_APPROVE_NOTIFYDSC', "Receive notification when this file is approved.");
define('_MI_WFDOWNLOADS_FILE_APPROVE_NOTIFYSBJ', "[{X_SITENAME}] {X_MODULE} auto-notify: File Approved");
/* Added by Lankford on 2007/3/21 */
define('_MI_WFDOWNLOADS_FILE_FILEMODIFIED_NOTIFY', "File Modified");
define('_MI_WFDOWNLOADS_FILE_FILEMODIFIED_NOTIFYCAP', "Notify me when this file is modified.");
define('_MI_WFDOWNLOADS_FILE_FILEMODIFIED_NOTIFYDSC', "Receive notification when this file is modified.");
define('_MI_WFDOWNLOADS_FILE_FILEMODIFIED_NOTIFYSBJ', "[{X_SITENAME}] {X_MODULE} auto-notify: File Modified");
define('_MI_WFDOWNLOADS_CATEGORY_FILEMODIFIED_NOTIFY', "File Modified");
define('_MI_WFDOWNLOADS_CATEGORY_FILEMODIFIED_NOTIFYCAP', "Notify me when a file in this category is modified.");
define('_MI_WFDOWNLOADS_CATEGORY_FILEMODIFIED_NOTIFYDSC', "Receive notification when a file in this category is modified.");
define('_MI_WFDOWNLOADS_CATEGORY_FILEMODIFIED_NOTIFYSBJ', "[{X_SITENAME}] {X_MODULE} auto-notify: File Modified");
define('_MI_WFDOWNLOADS_GLOBAL_FILEMODIFIED_NOTIFY', "File Modified");
define('_MI_WFDOWNLOADS_GLOBAL_FILEMODIFIED_NOTIFYCAP', "Notify me when any file is modified.");
define('_MI_WFDOWNLOADS_GLOBAL_FILEMODIFIED_NOTIFYDSC', "Receive notification when any file is modified.");
define('_MI_WFDOWNLOADS_GLOBAL_FILEMODIFIED_NOTIFYSBJ', "[{X_SITENAME}] {X_MODULE} auto-notify: File Modified");
/* End add block */
define('_MI_WFDOWNLOADS_AUTHOR_INFO', "Developer Information");
define('_MI_WFDOWNLOADS_AUTHOR_NAME', "Developer");
define('_MI_WFDOWNLOADS_AUTHOR_DEVTEAM', "Development Team");
define('_MI_WFDOWNLOADS_AUTHOR_WEBSITE', "Developer website");
define('_MI_WFDOWNLOADS_AUTHOR_EMAIL', "Developer email");
define('_MI_WFDOWNLOADS_AUTHOR_CREDITS', "Credits");
define('_MI_WFDOWNLOADS_MODULE_INFO', "Module Development Information");
define('_MI_WFDOWNLOADS_MODULE_STATUS', "Development Status");
define('_MI_WFDOWNLOADS_MODULE_DEMO', "Demo Site");
define('_MI_WFDOWNLOADS_MODULE_SUPPORT', "Official support site");
define('_MI_WFDOWNLOADS_MODULE_BUG', "Report a bug for this module");
define('_MI_WFDOWNLOADS_MODULE_FEATURE', "Suggest a new feature for this module");
define('_MI_WFDOWNLOADS_MODULE_DISCLAIMER', "Disclaimer");
define('_MI_WFDOWNLOADS_RELEASE', "Release Date: ");
define('_MI_WFDOWNLOADS_MODULE_MAILLIST', "XOOPS Mailing Lists");
define('_MI_WFDOWNLOADS_MODULE_MAILANNOUNCEMENTS', "Announcements Mailing List");
define('_MI_WFDOWNLOADS_MODULE_MAILBUGS', "Bug Mailing List");
define('_MI_WFDOWNLOADS_MODULE_MAILFEATURES', "Features Mailing List");
define('_MI_WFDOWNLOADS_MODULE_MAILANNOUNCEMENTSDSC', "Get the latest announcements from XOOPS.");
define('_MI_WFDOWNLOADS_MODULE_MAILBUGSDSC', "Bug Tracking and submission mailing list");
define('_MI_WFDOWNLOADS_MODULE_MAILFEATURESDSC', "Request New Features mailing list.");
define('_MI_WFDOWNLOADS_WARNINGTEXT', "THE SOFTWARE IS PROVIDED BY XOOPS \"AS IS\" AND \"WITH ALL FAULTS.\"
XOOPS MAKES NO REPRESENTATIONS OR WARRANTIES OF ANY KIND CONCERNING
THE QUALITY, SAFETY OR SUITABILITY OF THE SOFTWARE, EITHER EXPRESS OR
IMPLIED, INCLUDING WITHOUT LIMITATION ANY IMPLIED WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, OR NON-INFRINGEMENT.
FURTHER, XOOPS MAKES NO REPRESENTATIONS OR WARRANTIES AS TO THE TRUTH,
ACCURACY OR COMPLETENESS OF ANY STATEMENTS, INFORMATION OR MATERIALS
CONCERNING THE SOFTWARE THAT IS CONTAINED IN XOOPS WEBSITE. IN NO
EVENT WILL XOOPS BE LIABLE FOR ANY INDIRECT, PUNITIVE, SPECIAL,
INCIDENTAL OR CONSEQUENTIAL DAMAGES, HOWEVER THEY MAY ARISE AND EVEN IF
XOOPS HAS BEEN PREVIOUSLY ADVISED OF THE POSSIBILITY OF SUCH DAMAGES..");
define('_MI_WFDOWNLOADS_AUTHOR_CREDITSTEXT', "The XOOPS Team would like to thank the following people for their help and support during the development phase of this module: <br /><br />tom, mking, paco1969, mharoun, Talis, m0nty, steenlnielsen, Clubby, Geronimo, bd_csmc, herko, LANG, Stewdio, tedsmith, veggieryan, carnuke, MadFish, Kiang, SmartFactory team<br />and anyone else who has contributed to either directly or indirectly.");
define('_MI_WFDOWNLOADS_AUTHOR_BUGFIXES', "Bug Fix History");
define('_MI_WFDOWNLOADS_COPYRIGHTIMAGE', "Images copyright XOOPS, WF-Project/SmartFactory and may only be used with permission");
// mirror defines
define('_MI_WFDOWNLOADS_MIRROR_USEIMAGES', "Display mirror logos"); // not implemented yet
define('_MI_WFDOWNLOADS_MIRROR_USEIMAGESDSC', "Select '" . _YES . "' to display logo for each mirror."); // not implemented yet
define('_MI_WFDOWNLOADS_MIRROR_IMGWIDTH', "Logo display width"); // not implemented yet
define('_MI_WFDOWNLOADS_MIRROR_IMGWIDTHDSC', "Display width for mirror logo."); // not implemented yet
define('_MI_WFDOWNLOADS_MIRROR_IMGHEIGHT', "Logo display height"); // not implemented yet
define('_MI_WFDOWNLOADS_MIRROR_IMGHEIGHTDSC', "Display height for mirror logo."); // not implemented yet
define('_MI_WFDOWNLOADS_MIRROR_AUTOAPPROVE', "[mirrors] Auto approve submitted mirrors");
define('_MI_WFDOWNLOADS_MIRROR_AUTOAPPROVEDSC', "Select to approve submitted mirrors without moderation.");
define('_MI_WFDOWNLOADS_MIRROR_MAXIMGWIDTH', "Upload logo width"); // not implemented yet
define('_MI_WFDOWNLOADS_MIRROR_MAXIMGWIDTHDSC', "Maximum logo width permitted when uploading logo files."); // not implemented yet
define('_MI_WFDOWNLOADS_MIRROR_MAXIMGHEIGHT', "Upload logo height"); // not implemented yet
define('_MI_WFDOWNLOADS_MIRROR_MAXIMGHEIGHTDSC', "Maximum logo height permitted when uploading logo files."); // not implemented yet
define('_MI_WFDOWNLOADS_MIRROR_ENABLE', "[mirrors] Enable mirrors system");
define('_MI_WFDOWNLOADS_MIRROR_ENABLEDSC', "If enabled, mirrors system (submit and use mirrors) is enabled in back end.");
define('_MI_WFDOWNLOADS_MIRROR_ENABLEONCHK', "[mirrors] Enable server online check");
define('_MI_WFDOWNLOADS_MIRROR_ENABLEONCHKDSC', "Enables the host server check for the mirrors. <br />This can slow your page load down if you have many mirrors.");
define('_MI_WFDOWNLOADS_MIRROR_ALLOWSUBMISS', "[mirrors] User mirror submissions");
define('_MI_WFDOWNLOADS_MIRROR_ALLOWSUBMISSDSC', "Allow users to submit new mirrors.");
define('_MI_WFDOWNLOADS_MIRROR_MIRRORIMAGES', "[mirrors] Mirror logo upload directory"); // not implemented yet
define('_MI_WFDOWNLOADS_MIRROR_MIRRORIMAGESDSC', "Mirror logo upload directory."); // not implemented yet


// 3.21
define('_MI_WFDOWNLOADS_DB_IMPORT', "Import");
define('_MI_WFDOWNLOADS_HELP', "Help");

// 3.22
define('_MI_WFDOWNLOADS_EDITOR', "[editor] Text editor");
define('_MI_WFDOWNLOADS_EDITORCHOICE', "Editor for File Summary and Download fields");

// 3.23
define('_MI_WFDOWNLOADS_SUBCATSSORTBY', "Sort subcategories by");
define('_MI_WFDOWNLOADS_SUBCATSSORTBYDSC', "");
define('_MI_WFDOWNLOADS_SUBCATSSORTBYCID', "Category ID");
define('_MI_WFDOWNLOADS_SUBCATSSORTBYTITLE', "Category title");
define('_MI_WFDOWNLOADS_SUBCATSSORTBYWEIGHT', "Category weight");
// Names of admin menu items
define('_MI_WFDOWNLOADS_MENU_HOME', "Home");
define('_MI_WFDOWNLOADS_MENU_HOME_DESC', "Home");
define('_MI_WFDOWNLOADS_MENU_CATEGORIES', "Categories");
define('_MI_WFDOWNLOADS_MENU_CATEGORIES_DESC', "Categories");
define('_MI_WFDOWNLOADS_MENU_DOWNLOADS', "Downloads");
define('_MI_WFDOWNLOADS_MENU_DOWNLOADS_DESC', "Downloads");
define('_MI_WFDOWNLOADS_MENU_REVIEWS', "Reviews");
define('_MI_WFDOWNLOADS_MENU_REVIEWS_DESC', "Reviews");
define('_MI_WFDOWNLOADS_MENU_MIRRORS', "Mirrors");
define('_MI_WFDOWNLOADS_MENU_MIRRORS_DESC', "Mirrors");
define('_MI_WFDOWNLOADS_MENU_BROKENS', "Brokens");
define('_MI_WFDOWNLOADS_MENU_BROKENS_DESC', "Brokens");
define('_MI_WFDOWNLOADS_MENU_REPORTSMODIFICATIONS', "Broken & Modified");
define('_MI_WFDOWNLOADS_MENU_REPORTSMODIFICATIONS_DESC', "Broken & Modified");
define('_MI_WFDOWNLOADS_MENU_INDEXPAGE', "Index page");
define('_MI_WFDOWNLOADS_MENU_INDEXPAGE_DESC', "Index page");
define('_MI_WFDOWNLOADS_MENU_SWISHE', "Swish-e config");
define('_MI_WFDOWNLOADS_MENU_SWISHE_DESC', "Swish-e config");
define('_MI_WFDOWNLOADS_MENU_IMAGES', "Images");
define('_MI_WFDOWNLOADS_MENU_IMAGES_DESC', "Images");
define('_MI_WFDOWNLOADS_MENU_MIMETYPES', "MIME types");
define('_MI_WFDOWNLOADS_MENU_MIMETYPES_DESC', "MIME types");
define('_MI_WFDOWNLOADS_MENU_RATINGS', "Ratings & Votes");
define('_MI_WFDOWNLOADS_MENU_RATINGS_DESC', "Ratings & Votes");
define('_MI_WFDOWNLOADS_MENU_PERMISSIONS', "Permissions");
define('_MI_WFDOWNLOADS_MENU_PERMISSIONS_DESC', "Permissions");
define('_MI_WFDOWNLOADS_MENU_IMPORT', "Import");
define('_MI_WFDOWNLOADS_MENU_IMPORT_DESC', "Import");
define('_MI_WFDOWNLOADS_MENU_CLONE', "Clone module");
define('_MI_WFDOWNLOADS_MENU_CLONE_DESC', "Clone module");
define('_MI_WFDOWNLOADS_MENU_ABOUT', "About");
define('_MI_WFDOWNLOADS_MENU_ABOUT_DESC', "About");
define('_MI_WFDOWNLOADS_SCREENSHOTSDSC', "Path relative to Xoops root path: \"" . XOOPS_ROOT_PATH . "/\". <br />No trailing slash.");
define('_MI_WFDOWNLOADS_MAINIMGDIRDSC', "Path relative to Xoops root path: \"" . XOOPS_ROOT_PATH . "/\". <br />No trailing slash.");
define('_MI_WFDOWNLOADS_CATEGORYIMGDSC', "Path relative to Xoops root path: \"" . XOOPS_ROOT_PATH . "/\". <br />No trailing slash.");
define('_MI_WFDOWNLOADS_SHOWDISCLAIMERDSC', "");
define('_MI_WFDOWNLOADS_DISCLAIMERDSC', "HTML tags, smiley icons, XOOPS codes, images are enabled.");
define('_MI_WFDOWNLOADS_SHOWDOWNDISCLDSC', "");
define('_MI_WFDOWNLOADS_DOWNDISCLAIMERDSC', "HTML tags, smiley icons, XOOPS codes, images are enabled.");
define('_MI_WFDOWNLOADS_LIMITSDSC', "List of limits to enter. <br />Separate with | <br />IMPORTANT: Do not change this once the site is live, add new to the end of the list!");
define('_MI_WFDOWNLOADS_VERSIONTYPESDSC', "List of version types to enter. <br />Separate with | <br />IMPORTANT: Do not change this once the site is live, add new to the end of the list!");
define('_MI_WFDOWNLOADS_REVIEW_ENABLE', "[reviews] Enable Reviews system");
define('_MI_WFDOWNLOADS_REVIEW_ENABLEDSC', "If enabled, Reviews system (submit and show reviews) is enabled in back end.");
define('_MI_WFDOWNLOADS_RATING_ENABLE', "[ratings] Enable ratings system");
define('_MI_WFDOWNLOADS_RATING_ENABLEDSC', "If enabled, Ratings system (vote and show ratings) is enabled in back end.");
define('_MI_WFDOWNLOADS_BROKENREPORT_ENABLE', "[broken reports] Enable Broken reports system");
define('_MI_WFDOWNLOADS_BROKENREPORT_ENABLEDSC', "If enabled, Broken reports system is enabled in back end.");
define('_MI_WFDOWNLOADS_SUBMISSIONS_CONFIGS', "Downloads submission generals permissions");
define('_MI_WFDOWNLOADS_SUBMISSIONS_CONFIGSDSC', "");
define('_MI_WFDOWNLOADS_DOWNLOAD_CONFIGS', "Download preferences");
define('_MI_WFDOWNLOADS_DOWNLOAD_CONFIGSDSC', "");
define('_MI_WFDOWNLOADS_UPLOAD_CONFIGS', "Upload/submit preferences");
define('_MI_WFDOWNLOADS_UPLOAD_CONFIGSDSC', "");
define('_MI_WFDOWNLOADS_IMAGES_CONFIGS', "Images preferences");
define('_MI_WFDOWNLOADS_IMAGES_CONFIGSDSC', "");
define('_MI_WFDOWNLOADS_SCREENSHOTS_CONFIGS', "Screenshots preferences");
define('_MI_WFDOWNLOADS_SCREENSHOTS_CONFIGSDSC', "");
define('_MI_WFDOWNLOADS_FILESUPLOADS_CONFIGS', "Files preferences");
define('_MI_WFDOWNLOADS_FILESUPLOADS_CONFIGSDSC', "");
define('_MI_WFDOWNLOADS_SCREENSHOTS_ESTRASYSTEMS', "Extra systems preferences");
define('_MI_WFDOWNLOADS_SCREENSHOTS_ESTRASYSTEMSDSC', "");
define('_MI_WFDOWNLOADS_VARIOUS_CONFIGS', "Various preferences");
define('_MI_WFDOWNLOADS_VARIOUS_CONFIGSDSC', "");
define('_MI_WFDOWNLOADS_AUTOSUMMARY1', _NO);
define('_MI_WFDOWNLOADS_AUTOSUMMARY2', "If blank");
define('_MI_WFDOWNLOADS_AUTOSUMMARY3', _YES);

define('_MI_WFDOWNLOADS_AUTOSUMMARYPLAINTEXT', "[auto summary] Auto summary plain text");
define('_MI_WFDOWNLOADS_AUTOSUMMARYPLAINTEXTDESC', "If '" . _YES . "' all html tags, except &lt;br&gt;, will be removed.");

define('_MI_WFDOWNLOADS_DISCLAIMER_DEFAULT', 'We have the right, but not the obligation to monitor and review submissions submitted by users, in the forums. We shall not be responsible for any of the content of these messages. We further reserve the right, to delete, move or edit submissions that the we, in its exclusive discretion, deems abusive, defamatory, obscene or in violation of any Copyright or Trademark laws or otherwise objectionable.');
define('_MI_WFDOWNLOADS_DOWNDISCLAIMER_DEFAULT', 'The file downloads on this site are provided as is without warranty either expressed or implied. Downloaded files should be checked for possible virus infection using the most up-to-date detection and security packages. If you have a question concerning a particular piece of software, feel free to contact the developer. We refuse liability for any damage or loss resulting from the use or misuse of any software offered from this site for downloading. If you have any doubt at all about the safety and operation of software made available to you on this site, do not download it.<br /><br />Contact us if you have questions concerning this disclaimer.');

define('_MI_WFDOWNLOADS_HELP_OVERVIEW', "Overview");
define('_MI_WFDOWNLOADS_HELP_INSTALL', "Install");
define('_MI_WFDOWNLOADS_HELP_TIPSTRICKS', "Tips & Tricks");
define('_MI_WFDOWNLOADS_HELP_IMPORT', "Import<br />(IN PROGRESS)");
define('_MI_WFDOWNLOADS_HELP_UPDATE1', "Updates Notes<br />(IN PROGRESS)");
define('_MI_WFDOWNLOADS_HELP_UPDATE2', "Updates from 2.0 - 3.10<br />(IN PROGRESS)");
define('_MI_WFDOWNLOADS_HELP_UPDATE3', "Updates from 3.10+<br />(IN PROGRESS)");

define('_MI_WFDOWNLOADS_BATCHDIR', "[upload files] Batch directory");
define('_MI_WFDOWNLOADS_BATCHDIRDSC', "Batch directory *MUST* be an absolute path! <br />No trailing slash.");

define('_MI_WFDOWNLOADS_SWISHE_CONFIGS', "Swish-e preferences");
define('_MI_WFDOWNLOADS_SWISHE_CONFIGSDSC', "");
define('_MI_WFDOWNLOADS_SWISHE_ENABLE', "[Swish-e] Enable Swish-e searching system");
define('_MI_WFDOWNLOADS_SWISHE_ENABLEDSC', "IN PROGRESS");
define('_MI_WFDOWNLOADS_SWISHE_EXEPATH', "[Swish-e] Swish-e executable path");
define('_MI_WFDOWNLOADS_SWISHE_EXEPATHDSC', "IN PROGRESS");
define('_MI_WFDOWNLOADS_SWISHE_SEARCHLIMIT', "[Swish-e] Swish-e max results");
define('_MI_WFDOWNLOADS_SWISHE_SEARCHLIMITDSC', "While searching, this specifies the maximum number of results to return. '0' is to return all results.");
