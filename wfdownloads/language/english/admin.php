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

// %%%%%%	Module NMDe 'MyDownloads' (Admin)	  %%%%%
// Buttons
define('_AM_WFDOWNLOADS_BMODIFY', "Modify");
define('_AM_WFDOWNLOADS_BDELETE', "Delete");
define('_AM_WFDOWNLOADS_BADD', "Add");
define('_AM_WFDOWNLOADS_BAPPROVE', "Approve");
define('_AM_WFDOWNLOADS_BIGNORE', "Ignore");
define('_AM_WFDOWNLOADS_BCANCEL', "Cancel");
define('_AM_WFDOWNLOADS_BSAVE', "Save");
define('_AM_WFDOWNLOADS_BRESET', "Reset");
define('_AM_WFDOWNLOADS_BMOVE', "Move files");
define('_AM_WFDOWNLOADS_BUPLOAD', "Upload");
define('_AM_WFDOWNLOADS_BDELETEIMAGE', "Delete selected image");
define('_AM_WFDOWNLOADS_BRETURN', "Return to where you were!");
define('_AM_WFDOWNLOADS_DBERROR', "Database access error: please report this error to the XOOPS website");
//Banned Users
define('_AM_WFDOWNLOADS_NONBANNED', "Not Banned");
define('_AM_WFDOWNLOADS_BANNED', "Banned");
define('_AM_WFDOWNLOADS_EDITBANNED', "Edit Banned Users");
// Other Options
define('_AM_WFDOWNLOADS_TEXTOPTIONS', "Text options");
define('_AM_WFDOWNLOADS_ALLOWHTML', " Allow HTML tags");
define('_AM_WFDOWNLOADS_ALLOWSMILEY', " Allow Smiley icons");
define('_AM_WFDOWNLOADS_ALLOWXCODE', " Allow XOOPS codes");
define('_AM_WFDOWNLOADS_ALLOWIMAGES', " Allow images");
define('_AM_WFDOWNLOADS_ALLOWBREAK', " Use XOOPS line break conversion");
define('_AM_WFDOWNLOADS_UPLOADFILE', "File uploaded successfully");
define('_AM_WFDOWNLOADS_NOMENUITEMS', "No menu items within the menu");
// Admin Bread crumb
define('_AM_WFDOWNLOADS_PREFS', "Preferences");
define('_AM_WFDOWNLOADS_PERMISSIONS', "Permissions");
define('_AM_WFDOWNLOADS_BINDEX', "Main index");
define('_AM_WFDOWNLOADS_BLOCKADMIN', "Blocks");
define('_AM_WFDOWNLOADS_GOMODULE', "Go to module");
define('_AM_WFDOWNLOADS_BHELP', "Help");
define('_AM_WFDOWNLOADS_ABOUT', "About");
// Admin Summary
define('_AM_WFDOWNLOADS_SCATEGORY', "Categories: %s");
define('_AM_WFDOWNLOADS_SFILES', "Downloads: %s");
define('_AM_WFDOWNLOADS_SNEWFILESVAL', "New/waiting downloads: %s");
define('_AM_WFDOWNLOADS_SMODREQUEST', "Modifications: %s");
define('_AM_WFDOWNLOADS_SREVIEWS', "Reviews: %s");
define('_AM_WFDOWNLOADS_SMIRRORS', "Mirrors: %s");
// Admin Main Menu
define('_AM_WFDOWNLOADS_MCATEGORY', "Categories management");
define('_AM_WFDOWNLOADS_INDEXPAGE', "Index page management");
define('_AM_WFDOWNLOADS_MUPLOADS', "Images upload");
define('_AM_WFDOWNLOADS_MMIMETYPES', "MIME types management");
define('_AM_WFDOWNLOADS_MCOMMENTS', "Comments");
define('_AM_WFDOWNLOADS_MVOTEDATA', "Vote data");
// waiting reviews
define('_AM_WFDOWNLOADS_AREVIEWS', "Reviews management");
define('_AM_WFDOWNLOADS_AREVIEWS_WAITING', "Reviews waiting for validation");
define('_AM_WFDOWNLOADS_AREVIEWS_INFO', "Reviews information");
define('_AM_WFDOWNLOADS_AREVIEWS_APPROVE', "Approve new review without validation");
define('_AM_WFDOWNLOADS_AREVIEWS_APPROVED', "Review has been approved.");
define('_AM_WFDOWNLOADS_AREVIEWS_EDIT', "Edit new review and then approve");
define('_AM_WFDOWNLOADS_AREVIEWS_DELETE', "Delete the new review information");
// Catgeory defines
define('_AM_WFDOWNLOADS_CCATEGORY_CREATENEW', "Create new category");
define('_AM_WFDOWNLOADS_CCATEGORY_MODIFY', "Modify category");
define('_AM_WFDOWNLOADS_CCATEGORY_MOVE', "Move category files");
define('_AM_WFDOWNLOADS_CCATEGORY_MODIFY_TITLE', "Category title");
define('_AM_WFDOWNLOADS_CCATEGORY_MODIFY_FAILED', "Failed moving files: cannot move to this category");
define('_AM_WFDOWNLOADS_CCATEGORY_MODIFY_FAILEDT', "Failed moving files: cannot find this category");
define('_AM_WFDOWNLOADS_CCATEGORY_MODIFY_MOVED', "Files moved successfully");
define('_AM_WFDOWNLOADS_CCATEGORY_CREATED', "New Category created and database updated successfully");
define('_AM_WFDOWNLOADS_CCATEGORY_MODIFIED', "Selected category modified and database updated successfully");
define('_AM_WFDOWNLOADS_CCATEGORY_DELETED', "Selected category deleted and database updated successfully");
define('_AM_WFDOWNLOADS_CCATEGORY_AREUSURE', "WARNING: Are you sure to delete this category and ALL its files and comments?");
define('_AM_WFDOWNLOADS_CCATEGORY_NOEXISTS', "You must create a category before you can add a new file");
define('_AM_WFDOWNLOADS_FCATEGORY_GROUPPROMPT', "Category access permissions");
define('_AM_WFDOWNLOADS_FCATEGORY_TITLE', "Category title");
define('_AM_WFDOWNLOADS_FCATEGORY_WEIGHT', "Category weight");
define('_AM_WFDOWNLOADS_FCATEGORY_SUBCATEGORY', "As subcategory of");
define('_AM_WFDOWNLOADS_FCATEGORY_CIMAGE', "Category image");
define('_AM_WFDOWNLOADS_FCATEGORY_DESCRIPTION', "Category description");
define('_AM_WFDOWNLOADS_FCATEGORY_SUMMARY', "Category summary");
define('_AM_WFDOWNLOADS_CCATEGORY_CHILDASPARENT', "You cannot set a child's category as the category parent");
// Index page Defines
define('_AM_WFDOWNLOADS_IPAGE_UPDATED', "Index page modified and database updated successfully!");
define('_AM_WFDOWNLOADS_IPAGE_INFORMATION', "Index page information");
define('_AM_WFDOWNLOADS_IPAGE_MODIFY', "Modify index page");
define('_AM_WFDOWNLOADS_IPAGE_CIMAGE', "Select index image");
define('_AM_WFDOWNLOADS_IPAGE_CTITLE', "Index title");
define('_AM_WFDOWNLOADS_IPAGE_CHEADING', "Index heading");
define('_AM_WFDOWNLOADS_IPAGE_CHEADINGA', "Index heading alignment");
define('_AM_WFDOWNLOADS_IPAGE_CFOOTER', "Index footer");
define('_AM_WFDOWNLOADS_IPAGE_CFOOTERA', "Index footer alignment");
define('_AM_WFDOWNLOADS_IPAGE_CLEFT', "Align left");
define('_AM_WFDOWNLOADS_IPAGE_CCENTER', "Align center");
define('_AM_WFDOWNLOADS_IPAGE_CRIGHT', "Align right");
//  Permissions defines
define('_AM_WFDOWNLOADS_PERM_MANAGEMENT', "Permissions management");
define('_AM_WFDOWNLOADS_PERM_PERMSNOTE', "Please be aware that even if you've set correct viewing permissions here, a group might not see the articles or blocks if you don't also grant that group permissions to access the module. To do that, go to <span style='font-weight: bold;'>System admin > Groups</span>, choose the appropriate group and click the checkboxes to grant its members the access.");
define('_AM_WFDOWNLOADS_PERM_CPERMISSIONS', "Category permissions");
define('_AM_WFDOWNLOADS_PERM_CSELECTPERMISSIONS', "Select categories that each group is allowed to view");
define('_AM_WFDOWNLOADS_PERM_CNOCATEGORY', "Cannot set permissions: No categories have been created yet!");
define('_AM_WFDOWNLOADS_PERM_FPERMISSIONS', "File permissions");
define('_AM_WFDOWNLOADS_PERM_FNOFILES', "Cannot set permissions: no files have been created yet!");
define('_AM_WFDOWNLOADS_PERM_FSELECTPERMISSIONS', "Select the files that each group is allowed to view");
// Upload defines
define('_AM_WFDOWNLOADS_DOWN_IMAGEUPLOAD', "Image successfully uploaded to the server");
define('_AM_WFDOWNLOADS_DOWN_NOIMAGEEXIST', "Error: No file was selected for uploading. Please try again!");
define('_AM_WFDOWNLOADS_DOWN_IMAGEEXIST', "Image already exists in upload area!");
define('_AM_WFDOWNLOADS_DOWN_FILEDELETED', "File has been deleted.");
define('_AM_WFDOWNLOADS_DOWN_FILEERRORDELETE', "Error deleting file: file not found on the server.");
define('_AM_WFDOWNLOADS_DOWN_NOFILEERROR', "Error deleting file: No file selected for deleting.");
define('_AM_WFDOWNLOADS_DOWN_DELETEFILE', "WARNING: Are you sure you want to delete this image file?");
define('_AM_WFDOWNLOADS_DOWN_IMAGEINFO', "Server status");
define('_AM_WFDOWNLOADS_DOWN_NOTSET', "Upload path not set");
define('_AM_WFDOWNLOADS_DOWN_SERVERPATH', "Server path to XOOPS root: ");
define('_AM_WFDOWNLOADS_DOWN_UPLOADPATH', "Current upload path: ");
define('_AM_WFDOWNLOADS_DOWN_UPLOADPATHDSC', "Note. Upload path *MUST* contain the full server path of your upload folder.");
define('_AM_WFDOWNLOADS_DOWN_SPHPINI', "<span style='font-weight: bold;'>Information taken from PHP ini file:</span>");
define('_AM_WFDOWNLOADS_DOWN_METAVERSION', "<span style='font-weight: bold;'>Downloads meta version:</span> ");
define('_AM_WFDOWNLOADS_DOWN_SAFEMODESTATUS', "Safe mode status: ");
define('_AM_WFDOWNLOADS_DOWN_REGISTERGLOBALS', "Register globals: ");
define('_AM_WFDOWNLOADS_DOWN_SERVERUPLOADSTATUS', "Server uploads status: ");
define('_AM_WFDOWNLOADS_DOWN_MAXUPLOADSIZE', "Max upload size permitted (upload_max_filesize directive in php.ini): ");
define('_AM_WFDOWNLOADS_DOWN_MAXPOSTSIZE', "Max post size permitted (post_max_size directive in php.ini): ");
define('_AM_WFDOWNLOADS_DOWN_SAFEMODEPROBLEMS', " (This may cause problems)");
define('_AM_WFDOWNLOADS_DOWN_GDLIBSTATUS', "GD library support: ");
define('_AM_WFDOWNLOADS_DOWN_GDLIBVERSION', "GD Library version: ");
define('_AM_WFDOWNLOADS_DOWN_GDON', "<span style='font-weight: bold;'>Enabled</span> (Thumbsnails available)");
define('_AM_WFDOWNLOADS_DOWN_GDOFF', "<span style='font-weight: bold;'>Disabled</span> (No thumbnails available)");
define('_AM_WFDOWNLOADS_DOWN_OFF', "<span style='font-weight: bold;'>OFF</span>");
define('_AM_WFDOWNLOADS_DOWN_ON', "<span style='font-weight: bold;'>ON</span>");
define('_AM_WFDOWNLOADS_DOWN_CATIMAGE', "Category images");
define('_AM_WFDOWNLOADS_DOWN_SCREENSHOTS', "Screenshot images");
define('_AM_WFDOWNLOADS_DOWN_MAINIMAGEDIR', "Main images");
define('_AM_WFDOWNLOADS_DOWN_FCATIMAGE', "Category image path");
define('_AM_WFDOWNLOADS_DOWN_FSCREENSHOTS', "Screenshot image path");
define('_AM_WFDOWNLOADS_DOWN_FMAINIMAGEDIR', "Main image path");
define('_AM_WFDOWNLOADS_DOWN_FUPLOADIMAGETO', "Upload image: ");
define('_AM_WFDOWNLOADS_DOWN_FUPLOADPATH', "Upload path:");
define('_AM_WFDOWNLOADS_DOWN_FUPLOADURL', "Upload URL:");
define('_AM_WFDOWNLOADS_DOWN_FOLDERSELECTION', "Select upload destination");
define('_AM_WFDOWNLOADS_DOWN_FSHOWSELECTEDIMAGE', "Display selected image");
define('_AM_WFDOWNLOADS_DOWN_FUPLOADIMAGE', "Upload new image to selected destination");
// Main Index defines
define('_AM_WFDOWNLOADS_MINDEX_DOWNSUMMARY', "Module admin summary");
define('_AM_WFDOWNLOADS_MINDEX_PUBLISHEDDOWN', "Published downloads");
define('_AM_WFDOWNLOADS_MINDEX_AUTOPUBLISHEDDOWN', "Auto published downloads");
define('_AM_WFDOWNLOADS_MINDEX_AUTOEXPIRE', "Auto expire downloads");
define('_AM_WFDOWNLOADS_MINDEX_OFFLINEDOWN', "Offline downloads");
define('_AM_WFDOWNLOADS_MINDEX_ID', "ID");
define('_AM_WFDOWNLOADS_MINDEX_TITLE', "Download title");
define('_AM_WFDOWNLOADS_MINDEX_POSTER', "Poster");
define('_AM_WFDOWNLOADS_MINDEX_SUBMITTED', "Submission date");
define('_AM_WFDOWNLOADS_MINDEX_ONLINESTATUS', "Online status");
define('_AM_WFDOWNLOADS_MINDEX_PUBLISHED', "Published");
define('_AM_WFDOWNLOADS_MINDEX_ACTION', "Action");
define('_AM_WFDOWNLOADS_MINDEX_NODOWNLOADSFOUND', "NOTICE: there are no downloads that match these criteria");
define('_AM_WFDOWNLOADS_MINDEX_PAGE', "<span style='font-weight: bold;'>Page:<span style='font-weight: bold;'> ");
define('_AM_WFDOWNLOADS_MINDEX_PAGEINFOTXT', "<ul><li>Downloads main page details.</li><li>You can easily change the image logo, heading, main index header and footer text to suit your own look.</li></ul><br /><br />Note: The Logo image chosen will be used throughout this module.");
// Submitted Files
define('_AM_WFDOWNLOADS_SUB_SUBMITTEDFILES', "Submitted files");
define('_AM_WFDOWNLOADS_SUB_FILESWAITINGINFO', "Waiting files information");
define('_AM_WFDOWNLOADS_SUB_FILESWAITINGVALIDATION', "Files waiting for validation: ");
define('_AM_WFDOWNLOADS_SUB_APPROVEWAITINGFILE', "<span style='font-weight: bold;'>Approve</span> new file information without validation.");
define('_AM_WFDOWNLOADS_SUB_EDITWAITINGFILE', "<span style='font-weight: bold;'>Edit</span> new file information and then approve.");
define('_AM_WFDOWNLOADS_SUB_DELETEWAITINGFILE', "<span style='font-weight: bold;'>Delete</span> the new file information.");
define('_AM_WFDOWNLOADS_SUB_NOFILESWAITING', "There are no files that match these criteria");
define('_AM_WFDOWNLOADS_SUB_NEWFILECREATED', "New file data created and database updated successfully");
// Mime types
define('_AM_WFDOWNLOADS_MIME_ID', "ID");
define('_AM_WFDOWNLOADS_MIME_EXT', "EXT");
define('_AM_WFDOWNLOADS_MIME_NAME', "Application type");
define('_AM_WFDOWNLOADS_MIME_ADMIN', "Admin");
define('_AM_WFDOWNLOADS_MIME_USER', "User");
// Mime type Form
define('_AM_WFDOWNLOADS_MIME_CREATEF', "Create MIME type");
define('_AM_WFDOWNLOADS_MIME_MODIFYF', "Modify MIME type");
define('_AM_WFDOWNLOADS_MIME_EXTF', "File extension");
define('_AM_WFDOWNLOADS_MIME_NAMEF', "Application type/name");
define('_AM_WFDOWNLOADS_MIME_TYPEF', "MIME types");
define('_AM_WFDOWNLOADS_MIME_ADMINF', "Allowed admin MIME types/extension");
define('_AM_WFDOWNLOADS_MIME_ADMINFINFO', "<span style='font-weight: bold;'>MIME types/extensions that are available for admin uploads</span>");
define('_AM_WFDOWNLOADS_MIME_USERF', "Allowed user MIME types/extensions");
define('_AM_WFDOWNLOADS_MIME_USERFINFO', "<span style='font-weight: bold;'>MIME types/extensions that are available for user uploads</span>");
define('_AM_WFDOWNLOADS_MIME_NOMIMEINFO', "No MIME types selected");
define('_AM_WFDOWNLOADS_MIME_FINDMIMETYPE', "Find MIME type information");
define('_AM_WFDOWNLOADS_MIME_EXTFIND', "Search file extension");
define('_AM_WFDOWNLOADS_MIME_INFOTEXT', "<ul><li>New MIME types can be created, edited or deleted easily via this form</li>
    <li>Looking for a new MIME type via an external website.</li>
    <li>View displayed MIME types for Admin and user uploads.</li>
    <li>Change MIME type upload status.</li></ul>");
// Mime type Buttons
define('_AM_WFDOWNLOADS_MIME_CREATE', "Create");
define('_AM_WFDOWNLOADS_MIME_CLEAR', "Reset");
define('_AM_WFDOWNLOADS_MIME_CANCEL', "Cancel");
define('_AM_WFDOWNLOADS_MIME_MODIFY', "Modify");
define('_AM_WFDOWNLOADS_MIME_DELETE', "Delete");
define('_AM_WFDOWNLOADS_MIME_FINDIT', "Get extension!");
// Mime type Database
define('_AM_WFDOWNLOADS_MIME_DELETETHIS', "Delete selected MIME type?");
define('_AM_WFDOWNLOADS_MIME_MIMEDELETED', "MIME type %s has been deleted");
define('_AM_WFDOWNLOADS_MIME_CREATED', "MIME type information created");
define('_AM_WFDOWNLOADS_MIME_MODIFIED', "MIME type information modified");
// Vote Information
define('_AM_WFDOWNLOADS_VOTE_RATINGINFOMATION', "Voting information");
define('_AM_WFDOWNLOADS_VOTE_TOTALVOTES', "Total votes: ");
define('_AM_WFDOWNLOADS_VOTE_REGUSERVOTES', "Registered user votes: %s");
define('_AM_WFDOWNLOADS_VOTE_ANONUSERVOTES', "Anonymous user votes: %s");
define('_AM_WFDOWNLOADS_VOTE_USER', "User");
define('_AM_WFDOWNLOADS_VOTE_IP', "IP address");
define('_AM_WFDOWNLOADS_VOTE_USERAVG', "Average user rating");
define('_AM_WFDOWNLOADS_VOTE_TOTALRATE', "Total ratings");
define('_AM_WFDOWNLOADS_VOTE_DATE', "Submitted");
define('_AM_WFDOWNLOADS_VOTE_RATING', "Rating");
define('_AM_WFDOWNLOADS_VOTE_NOREGVOTES', "No registered user votes");
define('_AM_WFDOWNLOADS_VOTE_NOUNREGVOTES', "No unregistered user votes");
define('_AM_WFDOWNLOADS_VOTE_VOTEDELETED', "Vote data deleted.");
define('_AM_WFDOWNLOADS_VOTE_ID', "ID");
define('_AM_WFDOWNLOADS_VOTE_FILETITLE', "File title");
define('_AM_WFDOWNLOADS_VOTE_DISPLAYVOTES', "Voting data information");
define('_AM_WFDOWNLOADS_VOTE_NOVOTES', "No User Votes to display");
define('_AM_WFDOWNLOADS_VOTE_DELETE', "No User Votes to display");
define('_AM_WFDOWNLOADS_VOTE_DELETEDSC', "<span style='font-weight: bold;'>Delete</span> the chosen vote information from the database.");
// Modifications
/*
define('_AM_WFDOWNLOADS_MOD_TOTMODREQUESTS', "Total modification requests: ");
define('_AM_WFDOWNLOADS_MOD_MODREQUESTS', "Modified files");
define('_AM_WFDOWNLOADS_MOD_MODREQUESTSINFO', "Modified files information");
define('_AM_WFDOWNLOADS_MOD_MODID', "ID");
define('_AM_WFDOWNLOADS_MOD_MODTITLE', "Title");
define('_AM_WFDOWNLOADS_MOD_MODPOSTER', "Original poster: ");
define('_AM_WFDOWNLOADS_MOD_DATE', "Submitted");
define('_AM_WFDOWNLOADS_MOD_NOMODREQUEST', "There are no requests that match these criteria");
define('_AM_WFDOWNLOADS_MOD_MODIFYSUBMIT', "Submitter");
define('_AM_WFDOWNLOADS_MOD_ORIGINAL', "Orginal download details");
define('_AM_WFDOWNLOADS_MOD_REQDELETED', "Modification request removed from the database");
define('_AM_WFDOWNLOADS_MOD_REQUPDATED', "Selected download modified and database updated successfully");
*/
define('_AM_WFDOWNLOADS_MOD_TOTMODREQUESTS', "Modifications waiting");
define('_AM_WFDOWNLOADS_MOD_MODREQUESTS', "Modified files");
define('_AM_WFDOWNLOADS_MOD_MODREQUESTSINFO', "Modifications information");
define('_AM_WFDOWNLOADS_MOD_MODID', "ID");
define('_AM_WFDOWNLOADS_MOD_MODTITLE', "Title");
define('_AM_WFDOWNLOADS_MOD_MODPOSTER', "Original poster: ");
define('_AM_WFDOWNLOADS_MOD_DATE', "Submitted");
define('_AM_WFDOWNLOADS_MOD_NOMODREQUEST', "There are no requests that match these criteria");
define('_AM_WFDOWNLOADS_MOD_TITLE', "Download title");
define('_AM_WFDOWNLOADS_MOD_LID', "Download ID");
define('_AM_WFDOWNLOADS_MOD_CID', "Category");
define('_AM_WFDOWNLOADS_MOD_URL', "Remote URL");
define('_AM_WFDOWNLOADS_MOD_MIRROR', "Download mirror");
define('_AM_WFDOWNLOADS_MOD_SIZE', "Download size");
define('_AM_WFDOWNLOADS_MOD_PUBLISHER', "Publisher");
define('_AM_WFDOWNLOADS_MOD_LICENSE', "Software licence");
define('_AM_WFDOWNLOADS_MOD_FEATURES', "Key features");
define('_AM_WFDOWNLOADS_MOD_FORUMID', "Forum");
define('_AM_WFDOWNLOADS_MOD_LIMITATIONS', "Software limitations");
define('_AM_WFDOWNLOADS_MOD_VERSIONTYPES', "Release status");
define('_AM_WFDOWNLOADS_MOD_DHISTORY', "Download history");
define('_AM_WFDOWNLOADS_MOD_SCREENSHOTS', "Screenshot");
define('_AM_WFDOWNLOADS_MOD_SCREENSHOT', "Screenshot 1");
define('_AM_WFDOWNLOADS_MOD_SCREENSHOT2', "Screenshot 2");
define('_AM_WFDOWNLOADS_MOD_SCREENSHOT3', "Screenshot 3");
define('_AM_WFDOWNLOADS_MOD_SCREENSHOT4', "Screenshot 4");
define('_AM_WFDOWNLOADS_MOD_HOMEPAGE', "Home Page");
define('_AM_WFDOWNLOADS_MOD_HOMEPAGETITLE', "Home page title");
define('_AM_WFDOWNLOADS_MOD_VERSION', "Version");
define('_AM_WFDOWNLOADS_MOD_SHOTIMAGE', "Screenshot image");
define('_AM_WFDOWNLOADS_MOD_FILESIZE', "File size");
define('_AM_WFDOWNLOADS_MOD_PLATFORM', "Software platform");
define('_AM_WFDOWNLOADS_MOD_PRICE', "Price");
define('_AM_WFDOWNLOADS_MOD_LICENCE', "Software licence");
define('_AM_WFDOWNLOADS_MOD_DESCRIPTION', "Description");
define('_AM_WFDOWNLOADS_MOD_REQUIREMENTS', "Requirements");
define('_AM_WFDOWNLOADS_MOD_MODIFYSUBMITTER', "Submitter");
define('_AM_WFDOWNLOADS_MOD_MODIFYSUBMIT', "Submitter");
define('_AM_WFDOWNLOADS_MOD_PROPOSED', "Proposed download details");
define('_AM_WFDOWNLOADS_MOD_ORIGINAL', "Original download details");
define('_AM_WFDOWNLOADS_MOD_REQDELETED', "Modification request removed from the database");
define('_AM_WFDOWNLOADS_MOD_REQUPDATED', "Selected download Modified and database updated successfully");
define('_AM_WFDOWNLOADS_MOD_VIEW', "View and edit modification request");
define('_AM_WFDOWNLOADS_MOD_FILENAME', "Local file name");
define('_AM_WFDOWNLOADS_MOD_FILETYPE', "Local file type");
define('_AM_WFDOWNLOADS_MOD_STATUS', "Status");
define('_AM_WFDOWNLOADS_MOD_RATING', "Rating");
define('_AM_WFDOWNLOADS_MOD_HITS', "Hits");
define('_AM_WFDOWNLOADS_MOD_VOTES', "Votes");
define('_AM_WFDOWNLOADS_MOD_COMMENTS', "Comments");
define('_AM_WFDOWNLOADS_MOD_PUBLISHED', "Published");
define('_AM_WFDOWNLOADS_MOD_EXPIRED', "Expired");
define('_AM_WFDOWNLOADS_MOD_UPDATED', "Updated");
define('_AM_WFDOWNLOADS_MOD_OFFLINE', "Offline");
define('_AM_WFDOWNLOADS_MOD_REQUESTDATE', "Request date");
define('_AM_WFDOWNLOADS_MOD_IPADDRESS', "IP address");
define('_AM_WFDOWNLOADS_MOD_NOTIFYPUB', "Notify");
define('_AM_WFDOWNLOADS_MOD_PAYPALEMAIL', "PayPal email");
define('_AM_WFDOWNLOADS_MOD_SUMMARY', "Summary");
// Reviews defines
define('_AM_WFDOWNLOADS_REV_SNEWMNAMEDESC', "Approve review");
define('_AM_WFDOWNLOADS_REV_ID', "ID");
define('_AM_WFDOWNLOADS_REV_TITLE', "Download title");
define('_AM_WFDOWNLOADS_REV_REVIEWTITLE', "Review title");
define('_AM_WFDOWNLOADS_REV_POSTER', "Reviewer");
define('_AM_WFDOWNLOADS_REV_SUBMITDATE', "Date");
define('_AM_WFDOWNLOADS_REV_FTITLE', "Review title");
define('_AM_WFDOWNLOADS_REV_FRATING', "Review rating");
define('_AM_WFDOWNLOADS_REV_FDESCRIPTION', "Review description");
define('_AM_WFDOWNLOADS_REV_FAPPROVE', "Review approve");
define('_AM_WFDOWNLOADS_REV_ACTION', "Action");
define('_AM_WFDOWNLOADS_REV_NOWAITINGREVIEWS', "No waiting reviews found");
define('_AM_WFDOWNLOADS_REVIEW_APPROVETHIS', "Approve review");
define('_AM_WFDOWNLOADS_REV_NOPUBLISHEDREVIEWS', "No published reviews found");
define('_AM_WFDOWNLOADS_REV_REVIEW_UPDATED', "Selected review modified and database updated successfully");
define('_AM_WFDOWNLOADS_REV_REVIEW_TOTAL', "Total reviews");
define('_AM_WFDOWNLOADS_REV_REVIEW_WAITING', "Waiting reviews");
define('_AM_WFDOWNLOADS_REV_REVIEW_PUBLISHED', "Published reviews");
// File management
define('_AM_WFDOWNLOADS_FILE_SUBMITTERID', "Submitter user ID: <br /><br />Leave this as it is, unless you want to change who submitted the download");
define('_AM_WFDOWNLOADS_FILE_ID', "File ID");
define('_AM_WFDOWNLOADS_FILE_IP', "Uploader's IP address");
define('_AM_WFDOWNLOADS_FILE_ALLOWEDAMIME', "<div style='padding-top: 4px; padding-bottom: 4px;'><span style='font-weight: bold;'>Allowed admin file extensions</span></div>");
define('_AM_WFDOWNLOADS_FILE_MODIFYFILE', "Modify file information");
define('_AM_WFDOWNLOADS_FILE_CREATENEWFILE', "Create new file");
define('_AM_WFDOWNLOADS_FILE_TITLE', "File title");
define('_AM_WFDOWNLOADS_FILE_DLURL', "Remote URL");
define('_AM_WFDOWNLOADS_FILE_FILENAME', "Local file name <br /><br /><span style='font-weight: normal;'>Note: if using local file as download, then you must also enter the correct file type below!</span>");
define('_AM_WFDOWNLOADS_FILE_FILETYPE', "File type");
define('_AM_WFDOWNLOADS_FILE_MIRRORURL', "File mirror");
define('_AM_WFDOWNLOADS_FILE_SUMMARY', "File summary");
define('_AM_WFDOWNLOADS_FILE_DESCRIPTION', "File description");
define('_AM_WFDOWNLOADS_FILE_DUPLOAD', " Upload file");
define('_AM_WFDOWNLOADS_FILE_CATEGORY', "Select category");
define('_AM_WFDOWNLOADS_FILE_HOMEPAGETITLE', "Home page title");
define('_AM_WFDOWNLOADS_FILE_HOMEPAGE', "Home page");
define('_AM_WFDOWNLOADS_FILE_SIZE', "File size (in Bytes)");
define('_AM_WFDOWNLOADS_FILE_VERSION', "File version");
define('_AM_WFDOWNLOADS_FILE_VERSIONTYPES', "Release status");
define('_AM_WFDOWNLOADS_FILE_PUBLISHER', "File publisher");
define('_AM_WFDOWNLOADS_FILE_PLATFORM', "Software platform");
define('_AM_WFDOWNLOADS_FILE_LICENCE', "Software licence");
define('_AM_WFDOWNLOADS_FILE_LIMITATIONS', "Software limitations");
define('_AM_WFDOWNLOADS_FILE_PRICE', "Price");
define('_AM_WFDOWNLOADS_FILE_KEYFEATURES', "Key features <br /><br /><span style='font-weight: normal;'>Separate each key feature with a |</span>");
define('_AM_WFDOWNLOADS_FILE_REQUIREMENTS', "System requirements <br /><br /><span style='font-weight: normal;'>Separate each requirement with |</span>");
define('_AM_WFDOWNLOADS_FILE_HISTORY', "Download history edit <br /><br /><span style='font-weight: normal;'>Add new download history and only use this field to if you need to edit the previous history.</span>");
define('_AM_WFDOWNLOADS_FILE_HISTORYD', "Add new download history <br /><br /><span style='font-weight: normal;'>The version number and date will be added automatically</span>");
define('_AM_WFDOWNLOADS_FILE_HISTORYVERS', "<span style='font-weight: bold;'>Version</span>");
define('_AM_WFDOWNLOADS_FILE_HISTORDATE', " <span style='font-weight: bold;'>Updated</span> ");
define('_AM_WFDOWNLOADS_FILE_FILESSTATUS', " Set download offline <br /><br /><span style='font-weight: normal;'>Download will not be viewable to all users.</span>");
define('_AM_WFDOWNLOADS_FILE_SETASUPDATED', " Set download status as updated <br /><br /><span style='font-weight: normal;'>Download will display updated icon.</span>");
define('_AM_WFDOWNLOADS_FILE_SHOTIMAGE', "Select screenshot image <br /><br /><span style='font-weight: normal;'>Note that screenshots will only be displayed if activated in module preferences.</span>");
define('_AM_WFDOWNLOADS_FILE_DISCUSSINFORUM', "Add discuss in this forum?");
define('_AM_WFDOWNLOADS_FILE_PUBLISHDATE', "Download publish date");
define('_AM_WFDOWNLOADS_FILE_EXPIREDATE', "Download expire date");
define('_AM_WFDOWNLOADS_FILE_CLEARPUBLISHDATE', "<br /><br />Remove publish date");
define('_AM_WFDOWNLOADS_FILE_CLEAREXPIREDATE', "<br /><br />Remove expire date");
define('_AM_WFDOWNLOADS_FILE_PUBLISHDATESET', " Publish date set: ");
define('_AM_WFDOWNLOADS_FILE_SETDATETIMEPUBLISH', " Set the date/time of publish");
define('_AM_WFDOWNLOADS_FILE_SETDATETIMEEXPIRE', " Set the date/time of expire");
define('_AM_WFDOWNLOADS_FILE_SETPUBLISHDATE', "<span style='font-weight: bold;'>Set publish date</span>");
define('_AM_WFDOWNLOADS_FILE_SETNEWPUBLISHDATE', "<span style='font-weight: bold;'>Set new publish date:</span> <br />published");
define('_AM_WFDOWNLOADS_FILE_SETPUBDATESETS', "<span style='font-weight: bold;'>Publish date set:</span> <br />publishes on date");
define('_AM_WFDOWNLOADS_FILE_EXPIREDATESET', " Expire date set: ");
define('_AM_WFDOWNLOADS_FILE_SETEXPIREDATE', "<span style='font-weight: bold;'>Set expire date</span>");
define('_AM_WFDOWNLOADS_FILE_MUSTBEVALID', "Screenshot image must be a valid image file under %s directory (ex. shot.gif). Leave it blank if there is no image file.");
define('_AM_WFDOWNLOADS_FILE_EDITAPPROVE', "Approve download:");
define('_AM_WFDOWNLOADS_FILE_NEWFILEUPLOAD', "New file created and database updated successfully");
define('_AM_WFDOWNLOADS_FILE_FILEMODIFIEDUPDATE', "Selected file modified and database updated successfully");
define('_AM_WFDOWNLOADS_FILE_REALLYDELETEDTHIS', "Are you sure to delete the selected file?");
define('_AM_WFDOWNLOADS_FILE_FILEWASDELETED', "File %s successfully deleted from the database!");
define('_AM_WFDOWNLOADS_FILE_USE_UPLOAD_TITLE', " Use upload file name for file title.");
define('_AM_WFDOWNLOADS_FILE_FILEAPPROVED', "File approved and database updated successfully");
define('_AM_WFDOWNLOADS_FILE_CREATENEWSSTORY', "<span style='font-weight: bold;'>Create news story from download</span>");
define('_AM_WFDOWNLOADS_FILE_SUBMITNEWS', "Submit new file as news item?");
define('_AM_WFDOWNLOADS_FILE_NEWSCATEGORY', "Select news category to submit news:");
define('_AM_WFDOWNLOADS_FILE_NEWSTITLE', "News title:<div style='padding-top: 4px; padding-bottom: 4px;'><span style='font-weight: normal;'>Leave blank to use file title</span></div>");
// Broken downloads defines
define('_AM_WFDOWNLOADS_SBROKENSUBMIT', "Brokens reports: %s");
define('_AM_WFDOWNLOADS_BROKEN_FILE', "Broken reports");
define('_AM_WFDOWNLOADS_BROKEN_FILEIGNORED', "Broken report ignored and successfully removed from the database!");
define('_AM_WFDOWNLOADS_BROKEN_NOWACK', "Acknowledged status changed and database updated!");
define('_AM_WFDOWNLOADS_BROKEN_NOWCON', "Confirmed status changed and database updated!");
define('_AM_WFDOWNLOADS_BROKEN_REPORTINFO', "Broken reports information");
define('_AM_WFDOWNLOADS_BROKEN_REPORTSNO', "Broken reports waiting");
define('_AM_WFDOWNLOADS_BROKEN_IGNOREDESC', "<span style='font-weight: bold;'>Ignore</span> and <span style='font-weight: bold;'>Delete</span> the broken file report.");
define('_AM_WFDOWNLOADS_BROKEN_IGNORE_ALT', "Ignore and delete the broken file report");
define('_AM_WFDOWNLOADS_BROKEN_DELETEDESC', "<span style='font-weight: bold; color: red;'>WARNING: <span style='font-weight: bold;'>Delete</span> the download</span> and <span style='font-weight: bold;'>Delete</span> the broken file report.");
define('_AM_WFDOWNLOADS_BROKEN_DELETE_ALT', "Delete download and broken file report");
define('_AM_WFDOWNLOADS_BROKEN_EDITDESC', "<span style='font-weight: bold;'>Edit</span> download to fix the problem.");
define('_AM_WFDOWNLOADS_BROKEN_EDIT_ALT', "Edit download to correct the problem");
define('_AM_WFDOWNLOADS_BROKEN_ACKDESC', "<span style='font-weight: bold;'>Acknowledged</span> Set acknowledged state of broken file report.");
define('_AM_WFDOWNLOADS_BROKEN_ACK_ALT', "Acknowledge state of broken file report");
define('_AM_WFDOWNLOADS_BROKEN_CONFIRMDESC', "<span style='font-weight: bold;'>Confirmed</span> Set confirmed state of broken file report.");
define('_AM_WFDOWNLOADS_BROKEN_CONFIRM_ALT', "Confirm state of broken file report");
define('_AM_WFDOWNLOADS_BROKEN_ID', "ID");
define('_AM_WFDOWNLOADS_BROKEN_TITLE', "Download title");
define('_AM_WFDOWNLOADS_BROKEN_REPORTER', "Reporter");
define('_AM_WFDOWNLOADS_BROKEN_FILESUBMITTER', "Download submitter");
define('_AM_WFDOWNLOADS_BROKEN_DATESUBMITTED', "Report date");
define('_AM_WFDOWNLOADS_BROKEN_ACTION', "Action");
define('_AM_WFDOWNLOADS_BROKEN_NOFILEMATCH', "There are no broken reports that match these criteria");
define('_AM_WFDOWNLOADS_BROKENFILEDELETED', "Download description removed from database and broken report removed");
define('_AM_WFDOWNLOADS_BROKEN_DOWNLOAD_DONT_EXISTS', "The file no longer exists");
// About defines
define('_AM_WFDOWNLOADS_BY', "by");
// block defines
define('_AM_WFDOWNLOADS_BADMIN', "Block administration");
define('_AM_WFDOWNLOADS_BLKDESC', "Description");
define('_AM_WFDOWNLOADS_TITLE', "Title");
define('_AM_WFDOWNLOADS_SIDE', "Alignment");
define('_AM_WFDOWNLOADS_WEIGHT', "Weight");
define('_AM_WFDOWNLOADS_VISIBLE', "Visible");
define('_AM_WFDOWNLOADS_ACTION', "Action");
define('_AM_WFDOWNLOADS_SBLEFT', "Left");
define('_AM_WFDOWNLOADS_SBRIGHT', "Right");
define('_AM_WFDOWNLOADS_CBLEFT', "Center left");
define('_AM_WFDOWNLOADS_CBRIGHT', "Center right");
define('_AM_WFDOWNLOADS_CBCENTER', "Center middle");
define('_AM_WFDOWNLOADS_ACTIVERIGHTS', "Active rights");
define('_AM_WFDOWNLOADS_ACCESSRIGHTS', "Access rights");
// image admin icon
define('_AM_WFDOWNLOADS_ICO_EDIT', "Edit this item");
define('_AM_WFDOWNLOADS_ICO_DELETE', "Delete this item");
define('_AM_WFDOWNLOADS_ICO_ONLINE', "Online");
define('_AM_WFDOWNLOADS_ICO_OFFLINE', "Offline");
define('_AM_WFDOWNLOADS_ICO_APPROVED', "Approved");
define('_AM_WFDOWNLOADS_ICO_NOTAPPROVED', "Not approved");
define('_AM_WFDOWNLOADS_ICO_LINK', "Related link");
define('_AM_WFDOWNLOADS_ICO_URL', "Add related URL");
define('_AM_WFDOWNLOADS_ICO_ADD', "Add");
define('_AM_WFDOWNLOADS_ICO_APPROVE', "Approve");
define('_AM_WFDOWNLOADS_ICO_STATS', "Stats");
define('_AM_WFDOWNLOADS_ICO_IGNORE', "Ignore");
define('_AM_WFDOWNLOADS_ICO_ACK', "Broken report acknowledged");
define('_AM_WFDOWNLOADS_ICO_REPORT', "Acknowledge broken report?");
define('_AM_WFDOWNLOADS_ICO_CONFIRM', "Broken report confirmed");
define('_AM_WFDOWNLOADS_ICO_CONBROKEN', "Confirm broken report?");
define('_AM_WFDOWNLOADS_DB_IMPORT', "Import");
define('_AM_WFDOWNLOADS_DB_CURRENTVER', "Current version: <span class='currentVer'>%s</span>");
define('_AM_WFDOWNLOADS_DB_DBVER', "Database Version %s");
define('_AM_WFDOWNLOADS_DB_MSG_ADD_DATA', "Data added in table %s");
define('_AM_WFDOWNLOADS_DB_MSG_ADD_DATA_ERR', "Error adding data in table %s");
define('_AM_WFDOWNLOADS_DB_MSG_CHGFIELD', "Changing field %s in table %s");
define('_AM_WFDOWNLOADS_DB_MSG_CHGFIELD_ERR', "Error changing field %s in table %s");
define('_AM_WFDOWNLOADS_DB_MSG_CREATE_TABLE', "Table %s created");
define('_AM_WFDOWNLOADS_DB_MSG_CREATE_TABLE_ERR', "Error creating table %s");
define('_AM_WFDOWNLOADS_DB_MSG_NEWFIELD', "Successfully added field %s");
define('_AM_WFDOWNLOADS_DB_MSG_NEWFIELD_ERR', "Error adding field %s");
define('_AM_WFDOWNLOADS_DB_NEEDUPDATE', "Your database is out-of-date. Please upgrade your database tables!<br><span style='font-weight: bold;'>Note: The XOOPS Team strongly recommends you to backup all the module tables before running this upgrade script.</span><br>");
define('_AM_WFDOWNLOADS_DB_NOUPDATE', "Your database is up-to-date. No updates are necessary.");
define('_AM_WFDOWNLOADS_DB_UPDATE_DB', "Updating database");
define('_AM_WFDOWNLOADS_DB_UPDATE_ERR', "Errors updating to version %s");
define('_AM_WFDOWNLOADS_DB_UPDATE_NOW', "Update now!");
define('_AM_WFDOWNLOADS_DB_UPDATE_OK', "Successfully updated to version %s");
define('_AM_WFDOWNLOADS_DB_UPDATE_TO', "Updating to version %s");
define('_AM_WFDOWNLOADS_GOMOD', "Go to module");
define('_AM_WFDOWNLOADS_UPDATE_MODULE', "Update module");
define('_AM_WFDOWNLOADS_MDOWNLOADS', "File Management");
define('_AM_WFDOWNLOADS_DB_MSG_UPDATE_TABLE', "Updating field values in %s");
define('_AM_WFDOWNLOADS_DB_MSG_UPDATE_TABLE_ERR', "Errors updating field values in %s");
// Mirrors
// waiting mirrors
define('_AM_WFDOWNLOADS_AMIRRORS', "Mirrors management");
define('_AM_WFDOWNLOADS_AMIRRORS_WAITING', "Mirrors waiting validation");
define('_AM_WFDOWNLOADS_AMIRRORS_INFO', "Mirrors information");
define('_AM_WFDOWNLOADS_AMIRRORS_APPROVE', "<span style='font-weight: bold;'>Approve</span> new mirror without validation.");
define('_AM_WFDOWNLOADS_AMIRRORS_EDIT', "<span style='font-weight: bold;'>Edit</span> new mirror and then approve.");
define('_AM_WFDOWNLOADS_AMIRRORS_DELETE', "<span style='font-weight: bold;'>Delete</span> the new mirror information.");
// mirrors defines
define('_AM_WFDOWNLOADS_MIRROR_MIRRORTITLE', "Mirror host");
define('_AM_WFDOWNLOADS_MIRROR_NOPUBLISHEDMIRRORS', "No published mirrors found");
define('_AM_WFDOWNLOADS_MIRROR_MIRROR_TOTAL', "Total mirrors");
define('_AM_WFDOWNLOADS_MIRROR_MIRROR_WAITING', "Waiting mirrors");
define('_AM_WFDOWNLOADS_MIRROR_MIRROR_PUBLISHED', "Published mirrors");
define('_AM_WFDOWNLOADS_MIRROR_SNEWMNAMEDESC', "Approve mirror: ");
define('_AM_WFDOWNLOADS_MIRROR_ID', "ID");
define('_AM_WFDOWNLOADS_MIRROR_TITLE', "Title");
define('_AM_WFDOWNLOADS_MIRROR_MUSTBEVALID', "Home page logo  must be a valid image file under %s directory (ex. shot.gif). Leave it blank if there is no image file.");
define('_AM_WFDOWNLOADS_MIRROR_POSTER', "Submitter");
define('_AM_WFDOWNLOADS_MIRROR_SUBMITDATE', "Submitted");
define('_AM_WFDOWNLOADS_MIRROR_FHOMEURLTITLE', "Home page title");
define('_AM_WFDOWNLOADS_MIRROR_FHOMEURL', "Home Page URL: ");
define('_AM_WFDOWNLOADS_MIRROR_UPLOADIMAGE', "Upload site logo <br /><br />A small logo representing your website.");
define('_AM_WFDOWNLOADS_MIRROR_MIRRORIMAGE', "Site logo");
define('_AM_WFDOWNLOADS_MIRROR_CONTINENT', "Continent");
define('_AM_WFDOWNLOADS_MIRROR_LOCATION', "Location <br /><br />Example: London, UK");
define('_AM_WFDOWNLOADS_MIRROR_DOWNURL', "Download URL <br /><br />Enter the URL to the file.");
define('_AM_WFDOWNLOADS_MIRROR_FAPPROVE', "Mirror approve");
define('_AM_WFDOWNLOADS_MIRROR_ACTION', "Action");
define('_AM_WFDOWNLOADS_MIRROR_NOWAITINGMIRRORS', "No waiting mirrors found");
define('_AM_WFDOWNLOADS_MIRROR_MIRROR_UPDATED', "Selected mirror modified and database updated successfully");
define('_AM_WFDOWNLOADS_MIRROR_APPROVETHIS', "Approve mirror");
// continents (used in mirrors page)
define('_AM_WFDOWNLOADS_CONT1', "Africa");
define('_AM_WFDOWNLOADS_CONT2', "Antarctica");
define('_AM_WFDOWNLOADS_CONT3', "Asia");
define('_AM_WFDOWNLOADS_CONT4', "Europe");
define('_AM_WFDOWNLOADS_CONT5', "North America");
define('_AM_WFDOWNLOADS_CONT6', "South America");
define('_AM_WFDOWNLOADS_CONT7', "Oceania");
define('_AM_WFDOWNLOADS_HELP', "Help");
// Added Formulize module support (2006/05/04) jpc - start
define('_AM_WFDOWNLOADS_FFS_SUBMITBROKEN', "Submit");
define('_AM_WFDOWNLOADS_FFS_STANDARD_FORM', "No, use the standard form");
define('_AM_WFDOWNLOADS_FFS_CUSTOM_FORM', "Use a custom form for this category?");
define('_AM_WFDOWNLOADS_FFS_DOWNLOADTITLE', "2nd step: create new download");
define('_AM_WFDOWNLOADS_FFS_EDITDOWNLOADTITLE', "2nd step: edit download");
define('_AM_WFDOWNLOADS_FFS_BACK', "Back");
define('_AM_WFDOWNLOADS_FFS_RELOAD', "Reload");
define('_AM_WFDOWNLOADS_CATEGORYC', "Category: "); // _MD to reuse the category form
define('_AM_WFDOWNLOADS_FFS_SUBMITCATEGORYHEAD', "Which category of file do you want to submit?");
define('_AM_WFDOWNLOADS_FFS_DOWNLOADDETAILS', "Download details:");
define('_AM_WFDOWNLOADS_FFS_DOWNLOADCUSTOMDETAILS', "Custom details:");
define('_AM_WFDOWNLOADS_FILETITLE', "Download title: ");
define('_AM_WFDOWNLOADS_DLURL', "Download URL: ");
define('_AM_WFDOWNLOADS_UPLOAD_FILEC', "Upload file: ");
define('_AM_WFDOWNLOADS_DESCRIPTION', "Description");
// Added Formulize module support (2006/05/04) jpc - end
define('_AM_WFDOWNLOADS_MINDEX_LOG', "Logs");
define('_AM_WFDOWNLOADS_IP_LOGS', "View logs");
define('_AM_WFDOWNLOADS_EMPTY_LOG', "No logs recorded.");
define('_AM_WFDOWNLOADS_LOG_FOR_LID', "Here is the list of the downloader's IP address for %s");
define('_AM_WFDOWNLOADS_IP_ADDRESS', "IP address");
define('_AM_WFDOWNLOADS_DATE', "Download date");
define('_AM_WFDOWNLOADS_BACK', "<< Back");
define('_AM_WFDOWNLOADS_USER', "User");
define('_AM_WFDOWNLOADS_ANONYMOUS', "Anonymous user");
// 3.23
define('_AM_WFDOWNLOADS_MINDEX_EXPIREDDOWN', "Expired downloads");
define('_AM_WFDOWNLOADS_BUTTON_CATEGORIES_REORDER', "Reorder");
define('_AM_WFDOWNLOADS_CATEGORIES_REORDERED', "Categories reordered");
define('_AM_WFDOWNLOADS_FILE_SUBMITTER', "Submitter User");
define('_AM_WFDOWNLOADS_FILE_SUBMITTER_DESC', "Leave this as it is, unless you want to change who submitted the download");
define('_AM_WFDOWNLOADS_FCATEGORY_CATEGORIES_LIST', "Categories list");
define('_AM_WFDOWNLOADS_DOWN_ERROR_FILENOTFOUND', "Error: file not found on server.");
define('_AM_WFDOWNLOADS_DOWN_ERROR_CATEGORYNOTFOUND', "Error: category not found on server.");
define('_AM_WFDOWNLOADS_MIME_MIMETYPES_LIST', "MIME types list");
define('_AM_WFDOWNLOADS_MIME_NOMIMETYPES', "No MIME types");
define('_AM_WFDOWNLOADS_MINDEX_NEWDOWN', "New/waiting downloads");
define('_AM_WFDOWNLOADS_BROKEN_REPORTS', "Broken downloads reports");
define('_AM_WFDOWNLOADS_MODIFICATIONS', "Modifications");
define('_AM_WFDOWNLOADS_FORMULIZE_AVAILABLE', "Formulize Module active. Custom forms are supported.");
define('_AM_WFDOWNLOADS_FORMULIZE_NOT_AVILABLE', "Formulize Module not present or not installed or not active. Custom Forms are not supported.");
define('_AM_WFDOWNLOADS_PERM_NOTE', "Note");
// admin/import.php
define('_AM_WFDOWNLOADS_IMPORT_INFORMATION', "Import page information");
define('_AM_WFDOWNLOADS_IMPORT_INFORMATION_TEXT', "To import categories and downloads from other modules click on buttons bellow.<br /><br /><span style='font-weight: bold;'>WARNINGS</span><br /><ul><li>Import procedure will not overwrite existing categories/downloads.</li><li>Import procedure will not copy files, <span style='font-weight: bold;'>DO NOT remove orginal module/categories/downloads!</span></li><li>Import procedure will not copy permissions, <span style='font-weight: bold;'>remember to set permissions</span>.</li></ul>");

define('_AM_WFDOWNLOADS_IMPORT_BUTTON_IMPORT', "Import");
define('_AM_WFDOWNLOADS_IMPORT_IMPORTINGDATA', "<span style='font-weight: bold;'>Importing Data</span>");
define('_AM_WFDOWNLOADS_IMPORT_IMPORT_OK', "<span style='font-weight: bold;'>Downloads successfully imported<br/>Remember to update permissions</span>");
define('_AM_WFDOWNLOADS_IMPORT_RUSURE', "WARNING: Are you sure you want to import downloads?");
define('_AM_WFDOWNLOADS_IMPORT_WFD', "Import data from WF-Downloads");
define('_AM_WFDOWNLOADS_IMPORT_WFD_NOTFOUND', "Module WF-Downloads not found on this site");
define('_AM_WFDOWNLOADS_IMPORT_MYDOWNLOADS', "Import data from MyDownloads");
define('_AM_WFDOWNLOADS_IMPORT_MYDOWNLOADS_NOTFOUND', "Module MyDownloads not found on this site");
define('_AM_WFDOWNLOADS_IMPORT_PDDOWNLOADS', "Import data from PD-Downloads");
define('_AM_WFDOWNLOADS_IMPORT_PDDOWNLOADS_NOTFOUND', "Module PD-Downloads not found on this site");
define('_AM_WFDOWNLOADS_IMPORT_WMPOWNLOADS', "Import data from Wmpdownloads");
define('_AM_WFDOWNLOADS_IMPORT_WMPOWNLOADS_NOTFOUND', "Module Wmpdownloads not found on this site");
define('_AM_WFDOWNLOADS_IMPORT_TDMDOWNLOADS', "Import data from TDMDownloads");
define('_AM_WFDOWNLOADS_IMPORT_TDMDOWNLOADS_NOTFOUND', "Module TDMDownloads not found on this site");
define('_AM_WFDOWNLOADS_FCATEGORY_GROUPPROMPT_UP', "Category upload permissions");
define('_AM_WFDOWNLOADS_PERM_CSELECTPERMISSIONS_UP', "Select categories that each group is allowed to upload");
define('_AM_WFDOWNLOADS_FCATEGORY_GROUPPROMPT_DESC', "Select groups allowed to download from this category");
define('_AM_WFDOWNLOADS_FCATEGORY_GROUPPROMPT_UP_DESC', "Select groups allowed to upload to this category");
// admin/ratings.php
define('_AM_WFDOWNLOADS_VOTE_VOTES', "Votes");
// admin/mimetypes.php
define('_AM_WFDOWNLOADS_MIME_EXTFIND_DESC', "Enter file extension you wish to search.");
define('_AM_WFDOWNLOADS_MIME_EXTF_DESC', "");
define('_AM_WFDOWNLOADS_MIME_NAMEF_DESC', "Enter application associated with this extension.");
define('_AM_WFDOWNLOADS_MIME_TYPEF_DESC', "Enter each MIME type associated with the file extension. Each MIME type must be separated with a space.");
// directories
define('_AM_WFDOWNLOADS_AVAILABLE', "<span style='color:green;'>Available. </span>");
define('_AM_WFDOWNLOADS_NOTAVAILABLE', "<span style='color:red;'>is not available. </span>");
define('_AM_WFDOWNLOADS_NOTWRITABLE', '<span style="color:red;"> should have permission ( %1$d ), but it has ( %2$d )</span>');
define('_AM_WFDOWNLOADS_CREATETHEDIR', "Create it");
define('_AM_WFDOWNLOADS_SETMPERM', "Set the permission");
define('_AM_WFDOWNLOADS_DIRCREATED', "The directory has been created");
define('_AM_WFDOWNLOADS_DIRNOTCREATED', "The directory cannot be created");
define('_AM_WFDOWNLOADS_PERMSET', "The permission has been set");
define('_AM_WFDOWNLOADS_PERMNOTSET', "The permission cannot be set");
define('_AM_WFDOWNLOADS_ERROR_UPLOADDIRNOTEXISTS', "Warning: the upload directory does not exist");
define('_AM_WFDOWNLOADS_ERROR_MAINIMAGEDIRNOTEXISTS', "Warning: the main images directory does not exist");
define('_AM_WFDOWNLOADS_ERROR_SCREENSHOTSDIRNOTEXISTS', "Warning: the categories images upload directory does not exist");
define('_AM_WFDOWNLOADS_ERROR_CATIMAGEDIRNOTEXISTS', "Warning: the upload directory does not exist");
// admin/downloads.php
define('_AM_WFDOWNLOADS_SEARCH', "Search");
define('_AM_WFDOWNLOADS_FILTER', "Filter");
define('_AM_WFDOWNLOADS_SEARCH_EQUAL', "=");
define('_AM_WFDOWNLOADS_SEARCH_GREATERTHAN', "&gt;");
define('_AM_WFDOWNLOADS_SEARCH_LESSTHAN', "&lt;");
define('_AM_WFDOWNLOADS_SEARCH_CONTAINS', "contains");
define('_AM_WFDOWNLOADS_MIRROR_DISABLED', "Warning: Mirrors system is disabled in module preferences.");
define('_AM_WFDOWNLOADS_REVIEW_DISABLED', "Warning: Reviews system is disabled in module preferences.");
define('_AM_WFDOWNLOADS_RATING_DISABLED', "Warning: Ratings system is disabled in module preferences.");
define('_AM_WFDOWNLOADS_BROKENREPORT_DISABLED', "Warning: Broken reports system is disabled in module preferences.");
// admin/clone.php
define('_AM_WFDOWNLOADS_CLONE', "Clone module");
define('_AM_WFDOWNLOADS_CLONE_DSC', "Cloning a module has never been this easy! Just type in the name you want for it and hit submit button!");
define('_AM_WFDOWNLOADS_CLONE_TITLE', "Clone %s");
define('_AM_WFDOWNLOADS_CLONE_NAME', "Choose a name for the new module");
define('_AM_WFDOWNLOADS_CLONE_NAME_DSC', "Do not use special characters! <br />Do not choose an existing module dirname or database table name!<br />It must be at most 18 characters long.");
define('_AM_WFDOWNLOADS_CLONE_INVALIDNAME', "ERROR: Invalid module name, please try another one!");
define('_AM_WFDOWNLOADS_CLONE_EXISTS', "ERROR: Module name already taken, please try another one!");
define('_AM_WFDOWNLOADS_CLONE_CONGRAT', "Congratulations! %s was sucessfully created! <br />You may want to make changes in language files.");
define('_AM_WFDOWNLOADS_CLONE_IMAGEFAIL', "Attention, we failed creating the new module logo. Please consider modifying images/module_logo.png manually!");
define('_AM_WFDOWNLOADS_CLONE_FAIL', "Sorry, we failed in creating the new clone. Maybe you need to temporally set write permissions (CHMOD 777) to 'modules' folder and try again.");
// admin/categories.php
define('_AM_WFDOWNLOADS_FCATEGORY_ID', "ID");
// admin/reportsmodifications.php
define('_AM_WFDOWNLOADS_MOD_IGNORE', "Ignore and delete modification request");
define('_AM_WFDOWNLOADS_MOD_VIEWDESC', "<span style='font-weight: bold;'>View & Compare</span> download and modification.");
define('_AM_WFDOWNLOADS_MOD_IGNOREDESC', "<span style='font-weight: bold;'>Ignore</span> modification & <span style='font-weight: bold;'>Delete</span> modification request.");
define('_AM_WFDOWNLOADS_MOD_REALLYIGNOREDTHIS', "Are you sure to ignore and delete this modification?");
// Editor:
define('_AM_WFDOWNLOADS_MOD_DOHTML', "Allow HTML tags");
define('_AM_WFDOWNLOADS_MOD_DOSMILEY', "Allow XOOPS Smilies");
define('_AM_WFDOWNLOADS_MOD_DOXCODE', "Allow XOOPS BBcode");
define('_AM_WFDOWNLOADS_MOD_DOIMAGE', "Allow XOOPS Images");
define('_AM_WFDOWNLOADS_MOD_DOBR', "Convert line breaks");
define('_AM_WFDOWNLOADS_MOD_FORMULIZE_IDREQ', "Formulize Form ID");

// 3.23
define('_AM_WFDOWNLOADS_MOD_APPROVE', "Approve and delete modification request");
define('_AM_WFDOWNLOADS_MOD_SAVE', "Save");

define('_AM_WFDOWNLOADS_TEXTOPTIONS_DESC', "Description and Summary text options");
define('_AM_WFDOWNLOADS_FCATEGORY_DESCRIPTION_DESC', "");
define('_AM_WFDOWNLOADS_FCATEGORY_SUMMARY_DESC', "");
define('_AM_WFDOWNLOADS_DOWN_MEMORYLIMIT', "Memory limit (memory_limit directive in php.ini): ");
define('_AM_WFDOWNLOADS_DOWN_MODULE_MAXFILESIZE', "Module max file size: %s (module config value)");
define('_AM_WFDOWNLOADS_UPLOAD_MAXFILESIZE', "Upload file size limit: %s");

define('_AM_WFDOWNLOADS_MINDEX_BATCHFILES', "Batch files");
define('_AM_WFDOWNLOADS_MINDEX_NOBATCHFILESFOUND', "NOTICE: there are no files in batch path");
define('_AM_WFDOWNLOADS_MINDEX_BATCHPATH', "Batch path");
define('_AM_WFDOWNLOADS_BATCHFILE_FILENAME', "Filename");
define('_AM_WFDOWNLOADS_BATCHFILE_FILESIZE', "Size");
define('_AM_WFDOWNLOADS_BATCHFILE_EXTENSION', "File extension");
define('_AM_WFDOWNLOADS_BATCHFILE_MIMETYPE', "MIME type");
define('_AM_WFDOWNLOADS_ERROR_BATCHFILENOTFOUND', "ERROR: Batch file non found");
define('_AM_WFDOWNLOADS_ERROR_BATCHFILENOTCOPIED', "ERROR: Batch file not copied");
define('_AM_WFDOWNLOADS_ERROR_BATCHFILENOTADDED', "ERROR: Batch file not added");
define('_AM_WFDOWNLOADS_BATCHFILE_MOVEDEDITNOW', "Batch file moved, now edit!");

define('_AM_WFDOWNLOADS_FILE_CREATE', "Create new download");
define('_AM_WFDOWNLOADS_FILE_EDIT', "Edit download");
define('_AM_WFDOWNLOADS_FFS_1STEP', "1st step: choose category");

define('_AM_WFDOWNLOADS_CLONE_TOOLONG', "ERROR: Module name is too long! It must be at most 18 characters long");

define('_AM_WFDOWNLOADS_AREVIEWS_APPROVE_ALT', "Approve");
define('_AM_WFDOWNLOADS_AREVIEWS_APPROVE_DESC', "<span style='font-weight: bold;'>Approve</span> the review.");
define('_AM_WFDOWNLOADS_AREVIEWS_EDIT_ALT', "Edit & Approve");
define('_AM_WFDOWNLOADS_AREVIEWS_EDIT_DESC', "<span style='font-weight: bold;'>Edit</span> and then <span style='font-weight: bold;'>Approve</span> the review.");
define('_AM_WFDOWNLOADS_AREVIEWS_DELETE_ALT', "Delete");
define('_AM_WFDOWNLOADS_AREVIEWS_DELETE_DESC', "<span style='font-weight: bold;'>Delete</span> the review.");

define('_AM_WFDOWNLOADS_SVOTES', "Votes: %s");

define('_AM_WFDOWNLOADS_MOD_VIEW_ALT', "View & Compare");
define('_AM_WFDOWNLOADS_MOD_IGNORE_ALT', "Ignore & Delete");
define('_AM_WFDOWNLOADS_MOD_APPROVE_ALT', "Approve & Delete");
define('_AM_WFDOWNLOADS_MOD_APPROVEDESC', "<span style='font-weight: bold;'>Approve</span> modification & <span style='font-weight: bold;'>Delete</span> modification request.");

define('_AM_WFDOWNLOADS_AMIRRORS_APPROVE_ALT', "Approve");
define('_AM_WFDOWNLOADS_AMIRRORS_APPROVE_DESC', "<span style='font-weight: bold;'>Approve</span> the mirror.");
define('_AM_WFDOWNLOADS_AMIRRORS_EDIT_ALT', "Edit & Approve");
define('_AM_WFDOWNLOADS_AMIRRORS_EDIT_DESC', "<span style='font-weight: bold;'>Edit</span> and then <span style='font-weight: bold;'>Approve</span> the mirror.");
define('_AM_WFDOWNLOADS_AMIRRORS_DELETE_ALT', "Delete");
define('_AM_WFDOWNLOADS_AMIRRORS_DELETE_DESC', "<span style='font-weight: bold;'>Delete</span> the mirror.");
