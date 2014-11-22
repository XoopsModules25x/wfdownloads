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

//Todo - Still to remove redundant defines from this area.
define('_MD_WFDOWNLOADS_NEEDLOGINVIEW', "You need to login first!");
define('_MD_WFDOWNLOADS_NODOWNLOAD', "This download does not exist!");
define('_MD_WFDOWNLOADS_DOWNLOADMINPOSTS', "You need to increase your post count, <br />before you can download files.");
define('_MD_WFDOWNLOADS_UPLOADMINPOSTS', "You need to increase your post count, <br />before you can upload files.");
define('_MD_WFDOWNLOADS_SUBCATLISTING', "Category listing");
define('_MD_WFDOWNLOADS_ISADMINNOTICE', "Webmaster: there is a problem with this image.");
define('_MD_WFDOWNLOADS_THANKSFORINFO', "Thank you for your submission. You will be notified once your request has been approved by the webmaster.");
define('_MD_WFDOWNLOADS_ISAPPROVED', "Thank you for your submission. Your request has been approved and will now appear in our listing.");
define('_MD_WFDOWNLOADS_THANKSFORHELP', "Thank you for helping to maintain this directory's integrity.");
define('_MD_WFDOWNLOADS_FORSECURITY', "For security reasons your user name and IP address will also be temporarily recorded.");
define('_MD_WFDOWNLOADS_NOPERMISETOLINK', "This file doesn't belong to the site you came from <br /><br />Please e-mail the webmaster of the site you came from and tell him: <br /><span style='font-weight: bold;'>NOT TO LEECH OTHER SITES LINKS!</span> <br /><br /><span style='font-weight: bold;'>Definition of a Leecher:</span> One who is too lazy to link from his own server or steals other people's hard work and makes it look like his own <br /><br />  Your IP address <span style='font-weight: bold;'>has been logged</span>.");
define('_MD_WFDOWNLOADS_SUMMARY', "Summary");
define('_MD_WFDOWNLOADS_DESCRIPTION', "Description");
define('_MD_WFDOWNLOADS_SUBMITCATHEAD', "Submit download form");
define('_MD_WFDOWNLOADS_MAIN', "HOME");
define('_MD_WFDOWNLOADS_POPULAR', "Popular");
define('_MD_WFDOWNLOADS_NEWTHISWEEK', "New this week");
define('_MD_WFDOWNLOADS_UPTHISWEEK', "Updated this week");
define('_MD_WFDOWNLOADS_POPULARITYLTOM', "Popularity (least to most hits)");
define('_MD_WFDOWNLOADS_POPULARITYMTOL', "Popularity (most to least hits)");
define('_MD_WFDOWNLOADS_TITLEATOZ', "Title (A to Z)");
define('_MD_WFDOWNLOADS_TITLEZTOA', "Title (Z to A)");
define('_MD_WFDOWNLOADS_DATEOLD', "Date (old files listed first)");
define('_MD_WFDOWNLOADS_DATENEW', "Date (new files listed first)");
define('_MD_WFDOWNLOADS_RATINGLTOH', "Rating (lowest to highest score)");
define('_MD_WFDOWNLOADS_RATINGHTOL', "Rating (highest to lowest score)");
define('_MD_WFDOWNLOADS_SIZELTOH', "Size (smallest to largest)");
define('_MD_WFDOWNLOADS_SIZEHTOL', "Size (largest to smallest)");
define('_MD_WFDOWNLOADS_DESCRIPTIONC', "Description");
define('_MD_WFDOWNLOADS_CATEGORYC', "Category");
define('_MD_WFDOWNLOADS_VERSION', "Version");
define('_MD_WFDOWNLOADS_SUBMITDATE', "Released");
define('_MD_WFDOWNLOADS_DLTIMES', "Downloaded %s times");
define('_MD_WFDOWNLOADS_FILESIZE', "File size");
define('_MD_WFDOWNLOADS_SUPPORTEDPLAT', "Platform");
define('_MD_WFDOWNLOADS_HOMEPAGE', "Home page");
define('_MD_WFDOWNLOADS_PUBLISHERC', "Publisher");
define('_MD_WFDOWNLOADS_RATINGC', "Rating");
define('_MD_WFDOWNLOADS_ONEVOTE', "1 Vote");
define('_MD_WFDOWNLOADS_NUMVOTES', "%s Votes");
define('_MD_WFDOWNLOADS_RATETHISFILE', "Rate resource");
define('_MD_WFDOWNLOADS_REVIEWTHISFILE', "Review resource");
define('_MD_WFDOWNLOADS_REVIEWS', "Reviews");
define('_MD_WFDOWNLOADS_DOWNLOADHITS', "Downloads");
define('_MD_WFDOWNLOADS_MODIFY', "Modify");
define('_MD_WFDOWNLOADS_REPORTBROKEN', "Report broken");
define('_MD_WFDOWNLOADS_BROKENREPORT', "Report broken resource");
define('_MD_WFDOWNLOADS_SUBMITBROKEN', "Submit");
define('_MD_WFDOWNLOADS_BEFORESUBMIT', "Before submitting a broken resource request, please check that the actual source of the file you intend reporting broken, is no longer there and that the website is not temporally down.");
define('_MD_WFDOWNLOADS_TELLAFRIEND', "Recommend");
define('_MD_WFDOWNLOADS_EDIT', "Edit");
define('_MD_WFDOWNLOADS_THEREARE', "There are <span style='font-weight: bold;'>%s</span> <span style='font-style: italic;'>Categories</span> and <span style='font-weight: bold;'>%s</span> <span style='font-style: italic;'>Downloads</span> listed");
define('_MD_WFDOWNLOADS_THEREIS', "There is <span style='font-weight: bold;'>%s</span> <span style='font-style: italic;'>Category</span> and <span style='font-weight: bold;'>%s</span> <span style='font-style: italic;'>Downloads</span> listed");
define('_MD_WFDOWNLOADS_LATESTLIST', "Latest listings");
define('_MD_WFDOWNLOADS_FILETITLE', "Download title");
define('_MD_WFDOWNLOADS_DLURL', "Remote URL");
define('_MD_WFDOWNLOADS_UPLOAD_FILENAME', "Local Filename");
define('_MD_WFDOWNLOADS_UPLOAD_FILETYPE', "File Type");
define('_MD_WFDOWNLOADS_HOMEPAGEC', "Home page");
define('_MD_WFDOWNLOADS_UPLOAD_FILEC', "Upload file");
define('_MD_WFDOWNLOADS_VERSIONC', "Version");
define('_MD_WFDOWNLOADS_FILESIZEC', "File size (in Bytes)");
define('_MD_WFDOWNLOADS_NUMBYTES', "%s bytes");
define('_MD_WFDOWNLOADS_PLATFORMC', "Platform");
define('_MD_WFDOWNLOADS_PRICE', "Price");
define('_MD_WFDOWNLOADS_LIMITS', "Limitations");
define('_MD_WFDOWNLOADS_VERSIONTYPES', "Release status");
define('_MD_WFDOWNLOADS_DOWNLICENSE', "License");
define('_MD_WFDOWNLOADS_NOTSPECIFIED', "Not specified");
define('_MD_WFDOWNLOADS_MIRRORSITE', "Download mirror");
define('_MD_WFDOWNLOADS_MIRROR', "Mirror website");
define('_MD_WFDOWNLOADS_PUBLISHER', "Publisher");
define('_MD_WFDOWNLOADS_UPDATEDON', "Updated on");
define('_MD_WFDOWNLOADS_PRICEFREE', "Free");
define('_MD_WFDOWNLOADS_VIEWDETAILS', "View full details");
define('_MD_WFDOWNLOADS_OPTIONS', "Options");
define('_MD_WFDOWNLOADS_NOTIFYAPPROVE', "Notify me when this file is approved");
define('_MD_WFDOWNLOADS_VOTEAPPRE', "Your vote is appreciated.");
define('_MD_WFDOWNLOADS_THANKYOU', "Thank you for taking the time to vote here at %s"); // %s is your site name
define('_MD_WFDOWNLOADS_VOTEONCE', "Please do not vote for the same resource more than once.");
define('_MD_WFDOWNLOADS_RATINGSCALE', "The scale is 1 - 10, with 1 being poor and 10 being excellent.");
define('_MD_WFDOWNLOADS_BEOBJECTIVE', "Please be objective, if everyone receives a 1 or a 10, the ratings aren't very useful.");
define('_MD_WFDOWNLOADS_DONOTVOTE', "Do not vote for your own resource.");
define('_MD_WFDOWNLOADS_RATEIT', "Rate it!");
define('_MD_WFDOWNLOADS_INTFILEFOUND', "Here is a good file to download at %s"); // %s is your site name
define('_MD_WFDOWNLOADS_RANK', "Rank");
define('_MD_WFDOWNLOADS_CATEGORY', "Category");
define('_MD_WFDOWNLOADS_HITS', "Hits");
define('_MD_WFDOWNLOADS_RATING', "Rating");
define('_MD_WFDOWNLOADS_VOTE', "Vote");
define('_MD_WFDOWNLOADS_SORTBY', "Sort by:");
define('_MD_WFDOWNLOADS_TITLE', "Title");
define('_MD_WFDOWNLOADS_DATE', "Date");
define('_MD_WFDOWNLOADS_POPULARITY', "Popularity");
define('_MD_WFDOWNLOADS_SIZE', "Size");
define('_MD_WFDOWNLOADS_TOPRATED', "Rating");
define('_MD_WFDOWNLOADS_CURSORTBY', "Downloads currently sorted by: %s");
define('_MD_WFDOWNLOADS_CANCEL', "Cancel");
define('_MD_WFDOWNLOADS_ALREADYREPORTED', "You have already submitted a broken report for this resource.");
define('_MD_WFDOWNLOADS_MUSTREGFIRST', "Sorry, you don't have the permission to perform this action. <br />Please register or login first!");
define('_MD_WFDOWNLOADS_NORATING', "No rating selected.");
define('_MD_WFDOWNLOADS_CANTVOTEOWN', "You cannot vote on the resource you submitted. <br />All votes are logged and reviewed.");
define('_MD_WFDOWNLOADS_SUBMITDOWNLOAD', "Submit download");
define('_MD_WFDOWNLOADS_SUB_SNEWMNAMEDESC', "<ul><li>All new downloads are subject to validation and may take up to 24 hours before they appear in our listing.</li><li>We reserve the rights to refuse any submitted download or change the content without approval.</li></ul>");
define('_MD_WFDOWNLOADS_MAINLISTING', "Main category listings");
define('_MD_WFDOWNLOADS_LASTWEEK', "Last week");
define('_MD_WFDOWNLOADS_LAST30DAYS', "Last 30 days");
define('_MD_WFDOWNLOADS_1WEEK', "1 week");
define('_MD_WFDOWNLOADS_2WEEKS', "2 weeks");
define('_MD_WFDOWNLOADS_30DAYS', "30 days");
define('_MD_WFDOWNLOADS_SHOW', "Show");
define('_MD_WFDOWNLOADS_DAYS', "days");
define('_MD_WFDOWNLOADS_NEWDOWNLOADS', "New downloads");
define('_MD_WFDOWNLOADS_TOTALNEWDOWNLOADS', "Total new downloads");
define('_MD_WFDOWNLOADS_DTOTALFORLAST', "Total new downloads for last");
define('_MD_WFDOWNLOADS_AGREE', "I Agree");
define('_MD_WFDOWNLOADS_DOYOUAGREE', "Do you agree to the above terms?");
define('_MD_WFDOWNLOADS_DISCLAIMERAGREEMENT', "Disclaimer");
define('_MD_WFDOWNLOADS_DUPLOADSCRSHOT', "Upload screenshot image");
define('_MD_WFDOWNLOADS_RESOURCEID', "Resource ID#");
define('_MD_WFDOWNLOADS_REPORTER', "Original reporter");
define('_MD_WFDOWNLOADS_DATEREPORTED', "Date reported");
define('_MD_WFDOWNLOADS_RESOURCEREPORTED', "Resource reported broken");
define('_MD_WFDOWNLOADS_BROWSETOTOPIC', "<span style='font-weight: bold;'>Browse downloads in alphabetical order</span>");
define('_MD_WFDOWNLOADS_WEBMASTERACKNOW', "Broken report acknowledged");
define('_MD_WFDOWNLOADS_WEBMASTERCONFIRM', "Broken report confirmed");
define('_MD_WFDOWNLOADS_DELETE', "Delete");
define('_MD_WFDOWNLOADS_DISPLAYING', "Displayed by: ");
define('_MD_WFDOWNLOADS_LEGENDTEXTNEW', "New today");
define('_MD_WFDOWNLOADS_LEGENDTEXTNEWTHREE', "New last 3 days");
define('_MD_WFDOWNLOADS_LEGENDTEXTTHISWEEK', "New this week");
define('_MD_WFDOWNLOADS_LEGENDTEXTNEWLAST', "Over 1 week");
define('_MD_WFDOWNLOADS_THISFILEDOESNOTEXIST', "ERROR: this file does not exist!");
define('_MD_WFDOWNLOADS_BROKENREPORTED', "Broken file reported");
define('_MD_WFDOWNLOADS_LEGENDTEXTRSS', "RSS feeds");
define('_MD_WFDOWNLOADS_LEGENDTEXTCATRSS', "RSS category feed");
define('_MD_WFDOWNLOADS_BYTES', " B");
define('_MD_WFDOWNLOADS_KILOBYTES', " kB");
define('_MD_WFDOWNLOADS_MEGABYTES', " MB");
define('_MD_WFDOWNLOADS_GIGABYTES', " GB");
define('_MD_WFDOWNLOADS_TERRABYTES', " TB");
define('_MD_WFDOWNLOADS_FILENOTEXIST', "ERROR: file does not exist or file not found!");
define('_MD_WFDOWNLOADS_FILENOTOPEN', "ERROR: unable to open file!"); // Orphan Define. Delete if not needed (Cesag).
// visit
define('_MD_WFDOWNLOADS_DOWNINPROGRESS', "Download in progress");
define('_MD_WFDOWNLOADS_DOWNSTARTINSEC', "Your download should start in 3 seconds...<span style='font-weight: bold;'>please wait</span>.");
define('_MD_WFDOWNLOADS_DOWNNOTSTART', "If your download does not start, ");
define('_MD_WFDOWNLOADS_CLICKHERE', "Click here!");
define('_MD_WFDOWNLOADS_BROKENFILE', "Broken file");
define('_MD_WFDOWNLOADS_PLEASEREPORT', "Please report this broken file to the webmaster, ");
// Reviews
define('_MD_WFDOWNLOADS_REV_TITLE', "Review title");
define('_MD_WFDOWNLOADS_REV_RATING', "Review rating");
define('_MD_WFDOWNLOADS_REV_DESCRIPTION', "Review");
define('_MD_WFDOWNLOADS_REV_SUBMITREV', "Submit review");
define('_MD_WFDOWNLOADS_ERROR_CREATEREVIEW', "ERROR: unable to create a review");
define('_MD_WFDOWNLOADS_REV_SNEWMNAMEDESC', "
Please completely fill out the form below, and we'll add your review as soon as possible. <br /><br />
Thank you for taking the time to submit your opinion. We want to give our users a possibility to find quality software faster. <br /><br />All reviews will be reviewed by one of our webmasters before they are put up on the web site.
");
define('_MD_WFDOWNLOADS_ISNOTAPPROVED', "Your submission has to be approved by a moderator first.");
define('_MD_WFDOWNLOADS_LICENCEC', "Software licence");
define('_MD_WFDOWNLOADS_LIMITATIONS', "Software limitations");
define('_MD_WFDOWNLOADS_KEYFEATURESC', "Key features");
define('_MD_WFDOWNLOADS_REQUIREMENTSC', "System requirements");
define('_MD_WFDOWNLOADS_HISTORYC', "Download history");
define('_MD_WFDOWNLOADS_HISTORYD', "Add new download history");
define('_MD_WFDOWNLOADS_HOMEPAGETITLEC', "Home page title");
define('_MD_WFDOWNLOADS_REQUIREMENTS', "System requirements");
define('_MD_WFDOWNLOADS_FEATURES', "Features");
define('_MD_WFDOWNLOADS_HISTORY', "Download history");
define('_MD_WFDOWNLOADS_PRICEC', "Price");
define('_MD_WFDOWNLOADS_SCREENSHOT', "Screenshot 1");
define('_MD_WFDOWNLOADS_SCREENSHOT2', "Screenshot 2");
define('_MD_WFDOWNLOADS_SCREENSHOT3', "Screenshot 3");
define('_MD_WFDOWNLOADS_SCREENSHOT4', "Screenshot 4");
define('_MD_WFDOWNLOADS_SCREENSHOTCLICK', "Display full image");
define('_MD_WFDOWNLOADS_OTHERBYUID', "Other files by");
define('_MD_WFDOWNLOADS_DOWNTIMES', "Download times");
define('_MD_WFDOWNLOADS_MAINTOTAL', "Total files");
define('_MD_WFDOWNLOADS_DOWNLOADNOW', "Download now");
define('_MD_WFDOWNLOADS_PAGES', "<span style='font-weight: bold;'>Pages</span>");
define('_MD_WFDOWNLOADS_REVIEWER', "Reviewer");
define('_MD_WFDOWNLOADS_RATEDRESOURCE', "Rated resource");
define('_MD_WFDOWNLOADS_SUBMITTER', "Submitter");
define('_MD_WFDOWNLOADS_REVIEWTITLE', "User reviews");
define('_MD_WFDOWNLOADS_REVIEWTOTAL', "<span style='font-weight: bold;'>Reviews total</span> %s");
define('_MD_WFDOWNLOADS_USERREVIEWSTITLE', "User reviews");
define('_MD_WFDOWNLOADS_USERREVIEWS', "Read User reviews on %s");
define('_MD_WFDOWNLOADS_NOUSERREVIEWS', "Be the first person to review %s");
define('_MD_WFDOWNLOADS_ERROR', "ERROR: error updating database, information not saved");
define('_MD_WFDOWNLOADS_COPYRIGHT', "copyright");
define('_MD_WFDOWNLOADS_INFORUM', "Discuss in forum");
// added frankblack
// submit.php
define('_MD_WFDOWNLOADS_NOTALLOWESTOSUBMIT', "You are not allowed to submit files");
define('_MD_WFDOWNLOADS_INFONOSAVEDB', "Information not saved to database: <br /><br />");
define('_MD_WFDOWNLOADS_NOTALLOWEDTOMOD', "You are not allowed to modify this download");
// reviews.php
define('_MD_WFDOWNLOADS_ERROR_CREATCHANNEL', "Create channel first");
define('_MD_WFDOWNLOADS_REVIEW_CATPATH', "Category path");
define('_MD_WFDOWNLOADS_ADDREVIEW', "Add review");
//
define('_MD_WFDOWNLOADS_NEWLAST', "New submitted before last week");
define('_MD_WFDOWNLOADS_NEWTHIS', "New submitted within this week");
define('_MD_WFDOWNLOADS_THREE', "New submitted within last three days");
define('_MD_WFDOWNLOADS_TODAY', "New submitted today");
define('_MD_WFDOWNLOADS_NO_FILES', "No files yet");
// mirrors.php
define('_MD_WFDOWNLOADS_MIRROR_AVAILABLE', "Mirrors available");
define('_MD_WFDOWNLOADS_MIRROR_CATPATH', "Category path");
define('_MD_WFDOWNLOADS_MIRROR_FILENAME', "Filename");
define('_MD_WFDOWNLOADS_DOWNLOADMIRRORS', "Download mirrors");
define('_MD_WFDOWNLOADS_MIRROR_NOTALLOWESTOSUBMIT', "You are not allowed to submit mirrors");
define('_MD_WFDOWNLOADS_MIRRORS', "Download mirrors");
define('_MD_WFDOWNLOADS_USERMIRRORSTITLE', "Available download mirrors");
define('_MD_WFDOWNLOADS_USERMIRRORS', "View available download mirrors on %s");
define('_MD_WFDOWNLOADS_NOUSERMIRRORS', "Add a new download mirror on %s.");
define('_MD_WFDOWNLOADS_TOTALMIRRORS', "Total mirrors");
define('_MD_WFDOWNLOADS_ADDMIRROR', "Add mirror");
define('_MD_WFDOWNLOADS_MIRROR_TOTAL', "<span style='font-weight: bold;'>Total mirrors</span> %s");
define('_MD_WFDOWNLOADS_MIRROR_HOMEURLTITLE', "Home page title");
define('_MD_WFDOWNLOADS_MIRROR_HOMEURL', "Home page URL");
define('_MD_WFDOWNLOADS_MIRROR_UPLOADMIRRORIMAGE', "Upload site logo");
define('_MD_WFDOWNLOADS_MIRROR_MIRRORIMAGE', "Site logo");
define('_MD_WFDOWNLOADS_MIRROR_CONTINENT', "Continent");
define('_MD_WFDOWNLOADS_MIRROR_LOCATION', "Location");
define('_MD_WFDOWNLOADS_MIRROR_DOWNURL', "Download URL");
define('_MD_WFDOWNLOADS_MIRROR_SUBMITMIRROR', "Submit mirror");
define('_MD_WFDOWNLOADS_ERROR_CREATEMIRROR', "ERROR: unable to create a mirror");
define('_MD_WFDOWNLOADS_MIRROR_SNEWMNAMEDESC', "
Please completely fill out the form below, and we'll add your mirror as soon as possible. <br /><br />
Thank you for your assistance in providing another location to download these files. We want to give our users a possibility to find quality software faster. <br /><br />All mirror submissions will be reviewed by one of our webmasters before they are put up on the web site.
");
define('_MD_WFDOWNLOADS_MIRROR_HHOST', "Host");
define('_MD_WFDOWNLOADS_MIRROR_HLOCATION', "Location");
define('_MD_WFDOWNLOADS_MIRROR_HCONTINENT', "Continent");
define('_MD_WFDOWNLOADS_MIRROR_HDOWNLOAD', "Download");
define('_MD_WFDOWNLOADS_MIRROR_OFFLINE', "Server host is offline.");
define('_MD_WFDOWNLOADS_MIRROR_ONLINE', "Server host is online.");
define('_MD_WFDOWNLOADS_MIRROR_DISABLED', "Server host check disabled.");
// continents (used in mirrors page)
define('_MD_WFDOWNLOADS_CONT1', "Africa");
define('_MD_WFDOWNLOADS_CONT2', "Antarctica");
define('_MD_WFDOWNLOADS_CONT3', "Asia");
define('_MD_WFDOWNLOADS_CONT4', "Europe");
define('_MD_WFDOWNLOADS_CONT5', "North America");
define('_MD_WFDOWNLOADS_CONT6', "South America");
define('_MD_WFDOWNLOADS_CONT7', "Oceania");
define('_MD_WFDOWNLOADS_ADMIN_PAGE', "Administrative section");
define('_MD_WFDOWNLOADS_DOWNLOADS_LIST', "Downloads list (%s)");
define('_MD_WFDOWNLOADS_NEWDOWNLOADS_ALL', "All");
define('_MD_WFDOWNLOADS_NEWDOWNLOADS_INTHELAST', "In the last %s days");
define('_MD_WFDOWNLOADS_DOWNLOAD_MOST_POPULAR', "Most popular downloads");
define('_MD_WFDOWNLOADS_DOWNLOAD_MOST_RATED', "Best rated downloads");
// Added Formulize module support (2006/05/04) jpc - start
define('_MD_WFDOWNLOADS_FFS_SUBMITCATEGORYHEAD', "Which Category of file do you want to submit?");
define('_MD_WFDOWNLOADS_FFS_DOWNLOADDETAILS', "Download details:");
define('_MD_WFDOWNLOADS_FFS_DOWNLOADCUSTOMDETAILS', "Custom details:");
define('_MD_WFDOWNLOADS_FFS_BACK', "Back");
define('_MD_WFDOWNLOADS_FFS_DOWNLOADTITLE', "Submitting a '{category}' file.");
// Added Formulize module support (2006/05/04) jpc - end


// 3.23
/**
 * @return array
 */
function wfdownloads_alphabet()
{
    $alphabet = array(
        '0',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',
        'G',
        'H',
        'I',
        'J',
        'K',
        'L',
        'M',
        'N',
        'O',
        'P',
        'Q',
        'R',
        'S',
        'T',
        'U',
        'V',
        'W',
        'X',
        'Y',
        'Z'
    );

    return $alphabet;
}

define('_MD_WFDOWNLOADS_MIRROR_HOMEURLTITLE_DESC', "");
define('_MD_WFDOWNLOADS_MIRROR_HOMEURL_DESC', "Enter your home page URL.");
define('_MD_WFDOWNLOADS_MIRROR_UPLOADMIRRORIMAGE_DESC', "A small logo representing your website.");
define('_MD_WFDOWNLOADS_MIRROR_LOCATION_DESC', "Example: London, UK");
define('_MD_WFDOWNLOADS_MIRROR_DOWNURL_DESC', "Enter the URL to the file.");
define('_WFDOWNLOADS_MD_UPDATED', "Updated!");
define('_WFDOWNLOADS_MD_NEW', "New!");
define('_WFDOWNLOADS_MD_POPULAR', "Popular");
define('_MD_WFDOWNLOADS_KEYFEATURESC_DESC', "Separate each key feature with a |");
define('_MD_WFDOWNLOADS_REQUIREMENTSC_DESC', "Separate each requirement with |");
define('_MD_WFDOWNLOADS_HISTORYD_DESC', "The submit date will automatically be added to this.");
define('_MD_WFDOWNLOADS_UPLOAD_FILEC_DESC', "Max file size: %s <br />Max image width: %dpx <br />Max image height: %dpx <br /><span title='%s'>Allowed extensions: <br />%s</span>");
define('_MD_WFDOWNLOADS_SUMMARY_DESC', "You can leave this blank <br />A summary will be auto created if empty.");
define('_MD_WFDOWNLOADS_SUMMARY_DESC_AUTOSUMMARY_YES', "Warning: summary field is disabled. <br />The 'Download auto summary' module preference is enabled. <br />A summary will be auto created using 'Description' field content.");
define('_MD_WFDOWNLOADS_SUMMARY_DESC_AUTOSUMMARY_IFBLANK', "You can leave this field blank. A summary will be auto created using 'Description' field content.");
define('_MD_WFDOWNLOADS_SUMMARY_DESC_AUTOSUMMARY_NO', "You can leave this field blank.");
define('_MD_WFDOWNLOADS_DESCRIPTION_DESC', "");
define('_MD_WFDOWNLOADS_SUBCATEGORIESLISTING', "Subcategories listing");
define('_MD_WFDOWNLOADS_DOWNLOADSLISTING', "Downloads listing");
define('_MD_WFDOWNLOADS_SORTDOWNLOADSBY', "Sort downloads by");
// Other Options
define('_MD_WFDOWNLOADS_TEXTOPTIONS', "Text options");
define('_MD_WFDOWNLOADS_TEXTOPTIONS_DESC', "Description and Summary text options");
define('_MD_WFDOWNLOADS_ALLOWHTML', " Allow HTML tags");
define('_MD_WFDOWNLOADS_ALLOWSMILEY', " Allow Smiley icons");
define('_MD_WFDOWNLOADS_ALLOWXCODE', " Allow XOOPS codes");
define('_MD_WFDOWNLOADS_ALLOWIMAGES', " Allow images");
define('_MD_WFDOWNLOADS_ALLOWBREAK', " Use XOOPS line break conversion");
define('_MD_WFDOWNLOADS_UPLOADFILE', "File uploaded successfully");
define('_MD_WFDOWNLOADS_NOMENUITEMS', "No menu items within the menu");
// singlefile.php
define('_MD_WFDOWNLOADS_PREVIEW', "Preview");

// index.php
define('_MD_WFDOWNLOADS_ERROR_UPLOADDIRNOTEXISTS', "Warning: contact the administrator, the upload directory does not exist");
define('_MD_WFDOWNLOADS_ERROR_MAINIMAGEDIRNOTEXISTS', "Warning: contact the administrator, the main images directory does not exist");
define('_MD_WFDOWNLOADS_ERROR_SCREENSHOTSDIRNOTEXISTS', "Warning: contact the administrator, the categories images upload directory does not exist");
define('_MD_WFDOWNLOADS_ERROR_CATIMAGEDIRNOTEXISTS', "Warning: contact the administrator, the upload directory does not exist");

define('_MD_WFDOWNLOADS_FFS_SUBMIT1ST_STEP', "1st step: choose category");
