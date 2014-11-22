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
include_once __DIR__ . '/header.php';

if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}

$feed_type = 'rss';
$contents  = ob_get_clean();
header('Content-Type:text/xml; charset=utf-8');
$xoopsOption['template_main'] = 'system_' . $feed_type . '.tpl';
error_reporting(0);

include_once XOOPS_ROOT_PATH . '/class/template.php';
$xoopsTpl = new XoopsTpl();

// Find case
$case = 'all';
$categoryObj = $wfdownloads->getHandler('category')->get((int) $_REQUEST['cid']);

$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);

// Get download permissions
$allowedDownCategoriesIds = $gperm_handler->getItemIds('WFDownCatPerm', $groups, $wfdownloads->getModule()->mid());

if (!$categoryObj->isNew()) {
    if (!in_array($categoryObj->getVar('cid'), $allowedDownCategoriesIds)) {
        exit();
    }
    $case = 'category';
}

switch ($case) {
    // Set cache_prefix
    default:
    case 'all':
        $cache_prefix = 'wfd|feed|' . $feed_type;
        break;

    case 'category':
        $cache_prefix = 'wfd|catfeed|' . $feed_type . '|' . (int) $categoryObj->getVar('cid');
        break;
}

$xoopsTpl->caching        = true;
$xoopsTpl->cache_lifetime = $GLOBALS['xoopsConfig']['module_cache'][(int) $wfdownloads->getModule()->mid()];
if (!$xoopsTpl->is_cached('db:' . $xoopsOption['template_main'], $cache_prefix)) {
    // Get content
    $limit = 30;

    $criteria = new CriteriaCompo(new Criteria('offline', false));
    $criteria->setSort('published');
    $criteria->setOrder('DESC');
    $criteria->setLimit($limit);

    switch ($case) {
        default:
        case 'all':
            $shorthand = 'all';
            $title = $GLOBALS['xoopsConfig']['sitename'] . ' - ' . htmlspecialchars($wfdownloads->getModule()->getVar('name'), ENT_QUOTES);
            $desc = $GLOBALS['xoopsConfig']['slogan'];
            $channel_url = XOOPS_URL . '/modules/' . $wfdownloads->getModule()->getVat('dirname') . '/rss.php';

            $criteria->add(new Criteria('cid', '(' . implode(',', $allowedDownCategoriesIds) . ')', 'IN'));
            $downloadObjs = $wfdownloads->getHandler('download')->getObjects($criteria);
            $id = 0;
            break;

        case 'category':
            $shorthand = 'cat';
            $title = $GLOBALS['xoopsConfig']['sitename'] . ' - ' . htmlspecialchars($categoryObj->getVar('title'), ENT_QUOTES);
            $desc = $GLOBALS['xoopsConfig']['slogan'] . ' - ' . htmlspecialchars($categoryObj->getVar('title'), ENT_QUOTES);
            $channel_url = XOOPS_URL . '/modules/' . $wfdownloads->getModule()->getVat('dirname') . '/rss.php?cid=' . (int) $categoryObj->getVar('cid');

            $criteria->add(new Criteria('cid', (int) $categoryObj->getVar('cid')));
            $downloadObjs = $wfdownloads->getHandler('download')->getObjects($criteria);
            $id = $categoryObj->getVar('categoryid');
            break;
    }

    // Assign feed-specific vars
    $xoopsTpl->assign('channel_title', xoops_utf8_encode($title, 'n'));
    $xoopsTpl->assign('channel_desc', xoops_utf8_encode($desc, 'n'));
    $xoopsTpl->assign('channel_link', $channel_url);
    $xoopsTpl->assign('channel_lastbuild', formatTimestamp(time(), $feed_type));
    $xoopsTpl->assign('channel_webmaster', $GLOBALS['xoopsConfig']['adminmail']);
    $xoopsTpl->assign('channel_editor', $GLOBALS['xoopsConfig']['adminmail']);
    $xoopsTpl->assign('channel_editor_name', $GLOBALS['xoopsConfig']['sitename']);
    $xoopsTpl->assign('channel_category', $wfdownloads->getModule()->getVar('name', 'e'));
    $xoopsTpl->assign('channel_generator', 'PHP');
    $xoopsTpl->assign('channel_language', _LANGCODE);

    // Assign items to template style array
    $url = XOOPS_URL . '/modules/' . $wfdownloads->getModule()->getVat('dirname') . '/';
    if (count($downloadObjs) > 0) {
        // Get users for downloads
        $uids = array();
        foreach ($downloadObjs as $downloadObj) {
            $uids[] = $downloadObj->getVar('submitter');
        }
        if (count($uids) > 0) {
            $users = $member_handler->getUserList(new Criteria('uid', '(' . implode(',', array_unique($uids)) . ')', 'IN'));
        }

        // Assign items to template
        foreach ($downloadObjs as $downloadObj) {
            $item = $downloadObj;
            $link = $url . 'singlefile.php?lid=' . (int) $item->getVar('lid');
            $title = htmlspecialchars($item->getVar('title', 'n'));
            $teaser = htmlspecialchars($item->getVar('summary', 'n'));
            $author = isset($users[$item->getVar('submitter')]) ? isset($users[$item->getVar('submitter')]) : $GLOBALS['xoopsConfig']['anonymous'];

            $xoopsTpl->append(
                'items',
                array(
                     'title' => xoops_utf8_encode($title),
                     'author' => xoops_utf8_encode($author),
                     'link' => $link,
                     'guid' => $link,
                     'is_permalink' => false,
                     'pubdate' => formatTimestamp($item->getVar('published'), $feed_type),
                     'dc_date' => formatTimestamp($item->getVar('published'), 'd/m H:i'),
                     'description' => xoops_utf8_encode($teaser)
                )
            );
        }
    } else {
        $excuse_title = 'No items!';
        $excuse = 'There are no items for this feed!';
        $art_title = htmlspecialchars($excuse_title, ENT_QUOTES);
        $art_teaser = htmlspecialchars($excuse, ENT_QUOTES);
        $xoopsTpl->append(
            'items',
            array(
                 'title' => xoops_utf8_encode($art_title),
                 'link' => $url,
                 'guid' => $url,
                 'pubdate' => formatTimestamp(time(), $feed_type),
                 'dc_date' => formatTimestamp(time(), 'd/m H:i'),
                 'description' => xoops_utf8_encode($art_teaser)
            )
        );
    }
}

$xoopsTpl->display('db:' . $xoopsOption['template_main'], $cache_prefix);
