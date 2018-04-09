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

use Xmf\Request;
use XoopsModules\Wfdownloads;
use XoopsModules\Wfdownloads\Common;

$currentFile = basename(__FILE__);
require_once __DIR__ . '/header.php';

// Check permissions
if (is_object($GLOBALS['xoopsUser'])) {
    if ($GLOBALS['xoopsUser']->getVar('posts') < $helper->getConfig('download_minposts') && !$GLOBALS['xoopsUser']->isAdmin()) {
        redirect_header('index.php', 5, _MD_WFDOWNLOADS_DOWNLOADMINPOSTS);
    }
} elseif (!is_object($GLOBALS['xoopsUser']) && ($helper->getConfig('download_minposts') > 0)) {
    redirect_header(XOOPS_URL . '/user.php', 1, _MD_WFDOWNLOADS_MUSTREGFIRST);
}

$lid         = Request::getInt('lid', 0);
$downloadObj = $helper->getHandler('Download')->get($lid);
// Check if download exists
if ($downloadObj->isNew()) {
    redirect_header('index.php', 1, _MD_WFDOWNLOADS_NODOWNLOAD);
}
$cid    = Request::getInt('cid', $downloadObj->getVar('cid'));
$agreed = Request::getBool('agreed', false, 'POST');

// Download not published, expired or taken offline - redirect
if (0 == $downloadObj->getVar('published') || $downloadObj->getVar('published') > time() || true === $downloadObj->getVar('offline')
    || (0 != $downloadObj->getVar('expired')
        && $downloadObj->getVar('expired') < time())
    || _WFDOWNLOADS_STATUS_WAITING == $downloadObj->getVar('status')) {
    redirect_header('index.php', 3, _MD_WFDOWNLOADS_NODOWNLOAD);
}

// Check permissions
$grouppermHandler = xoops_getHandler('groupperm');
$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : [0 => XOOPS_GROUP_ANONYMOUS];
if (!$grouppermHandler->checkRight('WFDownCatPerm', $cid, $groups, $helper->getModule()->mid())) {
    redirect_header('index.php', 3, _NOPERM);
}

if (false === $agreed) {
    if ($helper->getConfig('check_host')) {
        $isAGoodHost  = false;
        $referer      = parse_url(xoops_getenv('HTTP_REFERER'));
        $referer_host = $referer['host'];
        foreach ($helper->getConfig('referers') as $ref) {
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

if ($helper->getConfig('showDowndisclaimer') && false === $agreed) {
    $GLOBALS['xoopsOption']['template_main'] = "{$helper->getModule()->dirname()}_disclaimer.tpl";
    require_once XOOPS_ROOT_PATH . '/header.php';

    $xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
    $xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
    $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
    $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

    $xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

    $catarray['imageheader'] = Wfdownloads\Utility::headerImage();
    $xoopsTpl->assign('catarray', $catarray);

    // Breadcrumb
    $breadcrumb = new common\Breadcrumb();
    $breadcrumb->addLink($helper->getModule()->getVar('name'), WFDOWNLOADS_URL);
    $breadcrumb->addLink(_MD_WFDOWNLOADS_DOWNLOADNOW, '');
    $xoopsTpl->assign('wfdownloads_breadcrumb', $breadcrumb->render());

    $xoopsTpl->assign('lid', $lid);
    $xoopsTpl->assign('cid', $cid);

    $xoopsTpl->assign('image_header', Wfdownloads\Utility::headerImage());

    $xoopsTpl->assign('submission_disclaimer', false);
    $xoopsTpl->assign('download_disclaimer', true);
    $xoopsTpl->assign('download_disclaimer_content', $myts->displayTarea($helper->getConfig('downdisclaimer'), true, true, true, true, true));

    $xoopsTpl->assign('down_disclaimer', true); // this definition is not removed for backward compatibility issues
    $xoopsTpl->assign('downdisclaimer', $myts->displayTarea($helper->getConfig('downdisclaimer'), true, true, true, true, true)); // this definition is not removed for backward compatibility issues
    $xoopsTpl->assign('cancel_location', WFDOWNLOADS_URL . '/index.php'); // this definition is not removed for backward compatibility issues
    $xoopsTpl->assign('agree_location', WFDOWNLOADS_URL . "/{$currentFile}?agree=1&amp;lid={$lid}&amp;cid={$cid}");
    require_once __DIR__ . '/footer.php';
} else {
    if (!Wfdownloads\Utility::userIsAdmin()) {
        $helper->getHandler('Download')->incrementHits($lid);
    }
    // Create ip log
    $ip_logObj = $helper->getHandler('iplog')->create();
    $ip_logObj->setVar('lid', $lid);
    $ip_logObj->setVar('date', time());
    $ip_logObj->setVar('ip_address', getenv('REMOTE_ADDR'));
    $ip_logObj->setVar('uid', is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getVar('uid') : 0);
    $helper->getHandler('iplog')->insert($ip_logObj, true);

    // Download file
    $fileFilename = trim($downloadObj->getVar('filename')); // IN PROGRESS: why 'trim'?
    if (('' == !$downloadObj->getVar('url') && 'http://' === !$downloadObj->getVar('url')) || '' == $fileFilename) {
        // download is a remote file: download from remote url
        require_once XOOPS_ROOT_PATH . '/header.php';

        $xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
        $xoTheme->addScript(WFDOWNLOADS_URL . '/assets/js/magnific/jquery.magnific-popup.min.js');
        $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/js/magnific/magnific-popup.css');
        $xoTheme->addStylesheet(WFDOWNLOADS_URL . '/assets/css/module.css');

        $xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL . '/');

        echo "<div align='center'>" . Wfdownloads\Utility::headerImage() . '</div>';
        $url = $myts->htmlSpecialChars(preg_replace('/javascript:/si', 'javascript:', $downloadObj->getVar('url')), ENT_QUOTES);
        echo "<h4>\n";
        echo "<img src='" . WFDOWNLOADS_URL . "/assets/images/icon/downloads.gif' align='middle' alt='' title='" . _MD_WFDOWNLOADS_DOWNINPROGRESS . "'> " . _MD_WFDOWNLOADS_DOWNINPROGRESS . "\n";
        echo "</h4>\n";
        echo '<div>' . _MD_WFDOWNLOADS_DOWNSTARTINSEC . "</div><br>\n";
        echo '<div>' . _MD_WFDOWNLOADS_DOWNNOTSTART . "\n";
        echo "<a href='{$url}' target='_blank'>" . _MD_WFDOWNLOADS_CLICKHERE . "</a>.\n";
        echo "</div>\n";

        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache'); // HTTP/1.0
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // Always modified
        header("Refresh: 3; url={$url}");
    } elseif (!empty($fileFilename)) {
        // download is a local file: download from filesystem
        if (ini_get('zlib.output_compression')) {
            ini_set('zlib.output_compression', 'Off');
        }
        // get file informations from filesystem
        $fileFilename  = trim($downloadObj->getVar('filename')); // IN PROGRESS: why 'trim'?
        $fileMimetype  = ('' != $downloadObj->getVar('filetype')) ? $downloadObj->getVar('filetype') : 'application/octet-stream';
        $filePath      = $helper->getConfig('uploaddir') . '/' . stripslashes(trim($fileFilename));
        $fileFilesize  = filesize($filePath);
        $fileInfo      = pathinfo($filePath);
        $fileName      = $fileInfo['basename'];
        $fileExtension = $fileInfo['extension'];

        $headerFilename = strtolower(strrev(substr(strrev($fileFilename), 0, strpos(strrev($fileFilename), '--'))));
        $headerFilename = ('' == $headerFilename) ? $fileFilename : $headerFilename;
        // MSIE Bug fix
        if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
            $headerFilename = preg_replace('/\./', '%2e', $headerFilename, substr_count($headerFilename, '.') - 1);
        }
        //
        header('Pragma: public');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Length: ' . (string)$fileFilesize);
        header('Content-Transfer-Encoding: binary');
        header("Content-Type: {$fileMimetype}");
        header("Content-Disposition: attachment; filename={$headerFilename}");
        if (false !== strpos($fileMimetype, 'text/')) {
            // downladed file is not binary
            Wfdownloads\Utility::download($filePath, false, true);
        } else {
            // downladed file is binary
            Wfdownloads\Utility::download($filePath, true, true);
        }
        exit();
    } else {
        // download is a broken file: report broken
        require_once XOOPS_ROOT_PATH . '/header.php';
        echo '<br>';
        echo "<div align='center'>" . Wfdownloads\Utility::headerImage() . '</div>';
        echo '<h4>' . _MD_WFDOWNLOADS_BROKENFILE . "</h4>\n";
        echo '<div>' . _MD_WFDOWNLOADS_PLEASEREPORT . "\n";
        echo "<a href='" . WFDOWNLOADS_URL . "/brokenfile.php?lid={$lid}'>" . _MD_WFDOWNLOADS_CLICKHERE . "</a>\n";
        echo "</div>\n";
    }
    require_once __DIR__ . '/footer.php';
}
