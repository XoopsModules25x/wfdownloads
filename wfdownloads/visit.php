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
include 'header.php';

// Check permissions
if (is_object($xoopsUser)) {
    if ($xoopsUser->getVar('posts') < $wfdownloads->getConfig('download_minposts') && !$xoopsUser->isAdmin()) {
        redirect_header('index.php', 5, _MD_WFDOWNLOADS_DOWNLOADMINPOSTS);
    }
} elseif (!is_object($xoopsUser) && ($wfdownloads->getConfig('download_minposts') > 0)) {
    redirect_header(XOOPS_URL . '/user.php', 1, _MD_WFDOWNLOADS_MUSTREGFIRST);
}

$lid      = WfdownloadsRequest::getInt('lid', 0);
$download = $wfdownloads->getHandler('download')->get($lid);
// Check if download exists
if ($download->isNew()) {
    redirect_header('index.php', 1, _MD_WFDOWNLOADS_NODOWNLOAD);
}
$cid    = WfdownloadsRequest::getInt('cid', $download->getVar('cid'));
$agreed = WfdownloadsRequest::getBool('agreed', false, 'POST');

//Download not published, expired or taken offline - redirect
if ($download->getVar('published') == 0 || $download->getVar('published') > time() || $download->getVar('offline') == true
    || ($download->getVar(
            'expired'
        ) != 0
        && $download->getVar('expired') < time())
    || $download->getVar('status') == 0
) {
    redirect_header('index.php', 3, _MD_WFDOWNLOADS_NODOWNLOAD);
}

// Check permissions
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);
if (!$gperm_handler->checkRight('WFDownCatPerm', $cid, $groups, $wfdownloads->getModule()->mid())) {
    redirect_header('index.php', 3, _NOPERM);
}

function reportBroken($lid)
{
    echo "<h4>" . _MD_WFDOWNLOADS_BROKENFILE . "</h4>\n";
    echo "<div>" . _MD_WFDOWNLOADS_PLEASEREPORT . "\n";
    echo "<a href='" . WFDOWNLOADS_URL . "/brokenfile.php?lid={$lid}'>" . _MD_WFDOWNLOADS_CLICKHERE . "</a>\n";
    echo "</div>\n";
}

if ($agreed == false) {
    if ($wfdownloads->getConfig('check_host')) {
        $isAGoodHost  = false;
        $referer      = parse_url(xoops_getenv('HTTP_REFERER'));
        $referer_host = $referer['host'];
        foreach ($wfdownloads->getConfig('referers') as $ref) {
            if (!empty($ref) && preg_match("/{$ref}/i", $referer_host)) {
                $isAGoodHost = true;
                break;
            }
        }
        if (!$isAGoodHost) {
            redirect_header(WFDOWNLOADS_URL . "/singlefile.php?cid={$cid}&amp;lid={$lid}", 20, _MD_WFDOWNLOADS_NOPERMISETOLINK);
        }
    }
}

if ($wfdownloads->getConfig('showDowndisclaimer') && $agreed == false) {
    $xoopsOption['template_main'] = 'wfdownloads_disclaimer.html';
    include XOOPS_ROOT_PATH . '/header.php';

    $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/module.css');
    $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/thickbox.css');
    $xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

    $catarray['imageheader'] = wfdownloads_headerImage();
    $xoopsTpl->assign('catarray', $catarray);

    // Breadcrumb
    $breadcrumb = new WfdownloadsBreadcrumb();
    $breadcrumb->addLink($wfdownloads->getModule()->getVar('name'), WFDOWNLOADS_URL);
    $breadcrumb->addLink(_MD_WFDOWNLOADS_DOWNLOADNOW, '');
    $xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

    $xoopsTpl->assign('lid', $lid);
    $xoopsTpl->assign('cid', $cid);

    $xoopsTpl->assign('image_header', wfdownloads_headerImage());

    $xoopsTpl->assign('submission_disclaimer', false);
    $xoopsTpl->assign('download_disclaimer', truee);
    $xoopsTpl->assign('download_disclaimer_content', $myts->displayTarea($wfdownloads->getConfig('downdisclaimer'), true, true, true, true, true));

    $xoopsTpl->assign('down_disclaimer', true); // this definition is not removed for backward compatibility issues
    $xoopsTpl->assign(
        'downdisclaimer',
        $myts->displayTarea($wfdownloads->getConfig('downdisclaimer'), true, true, true, true, true)
    ); // this definition is not removed for backward compatibility issues
    $xoopsTpl->assign('cancel_location', WFDOWNLOADS_URL . '/index.php'); // this definition is not removed for backward compatibility issues
    $xoopsTpl->assign('agree_location', WFDOWNLOADS_URL . "/{$currentFile}?agree=1&amp;lid={$lid}&amp;cid={$cid}");
    include 'footer.php';
} else {
    if (!wfdownloads_userIsAdmin()) {
        $wfdownloads->getHandler('download')->incrementHits($lid);
    }
    // Create ip log
    $ip_log = $wfdownloads->getHandler('ip_log')->create();
    $ip_log->setVar('lid', $lid);
    $ip_log->setVar('date', time());
    $ip_log->setVar('ip_address', getenv('REMOTE_ADDR'));
    $ip_log->setVar('uid', is_object($xoopsUser) ? $xoopsUser->getVar('uid') : 0);
    $wfdownloads->getHandler('ip_log')->insert($ip_log, true);

    $fullFilename = trim($download->getVar('filename'));
    if ((!$download->getVar('url') == '' && !$download->getVar('url') == 'http://') || $fullFilename == '') {
        include XOOPS_ROOT_PATH . '/header.php';

        $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/module.css');
        $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/thickbox.css');
        $xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

        echo "<div align='center'>" . wfdownloads_headerImage() . "</div>";
        $url = $myts->htmlSpecialChars(preg_replace('/javascript:/si', 'javascript:', $download->getVar('url')), ENT_QUOTES);
        echo "<h4><img src='" . WFDOWNLOADS_URL . "/images/icon/downloads.gif' align='middle' alt='' title='" . _MD_WFDOWNLOADS_DOWNINPROGRESS
            . "' /> " . _MD_WFDOWNLOADS_DOWNINPROGRESS . "</h4>\n";
        echo "<div>" . _MD_WFDOWNLOADS_DOWNSTARTINSEC . "</div><br />\n";
        echo "<div>" . _MD_WFDOWNLOADS_DOWNNOTSTART . "\n";
        echo "<a href='{$url}' target='_blank'>" . _MD_WFDOWNLOADS_CLICKHERE . "</a>.\n";
        echo "</div>\n";

        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache"); // HTTP/1.0
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Always modified
        header("Refresh: 3; url={$url}");
    } elseif (!empty($fullFilename)) {
        $mimeType     = $download->getVar('filetype');
        $file         = strrev($fullFilename);
        $tempFilename = strtolower(strrev(substr($file, 0, strpos($file, '--'))));
        $filename     = ($tempFilename == '') ? $fullFilename : $tempFilename;
        $filePath     = $wfdownloads->getConfig('uploaddir') . '/' . stripslashes(trim($fullFilename));
        if (ini_get('zlib.output_compression')) {
            ini_set('zlib.output_compression', 'Off');
        }
        // MSIE Bug fix.
        $headerFilename = (strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) ? preg_replace('/\./', '%2e', $filename, substr_count($filename, '.') - 1)
            : $filename;
        header("Pragma: public");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-Length: " . (string)(filesize($filePath)));
        header("Content-Transfer-Encoding: binary");
        if (isset($mimeType)) {
            header("Content-Type: {$mimeType}");
        }
        header("Content-Disposition: attachment; filename={$headerFilename}");
        if (isset($mimeType) && strstr($mimeType, 'text/')) {
            wfdownloads_download($filePath, false, true);
        } else {
            wfdownloads_download($filePath, true, true);
        }
        exit();
    } else {
        include XOOPS_ROOT_PATH . '/header.php';
        echo "<br />";
        echo "<div align='center'>" . wfdownloads_headerImage() . "</div>";
        reportBroken($lid);
    }
    include 'footer.php';
}
