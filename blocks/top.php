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

/**
 * Function: b_mydownloads_top_show
 * Input   : $options[0] = date for the most recent downloads
 *                     hits for the most popular downloads
 *            $block['content'] = The optional above content
 *            $options[1]   = How many downloads are displayes
 * Output  : Returns the most recent or most popular downloads
 */

use XoopsModules\Wfdownloads;

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

require_once  dirname(__DIR__) . '/include/common.php';
/**
 * @param $options
 *
 * @return array
 */
function wfdownloads_top_show($options)
{
    $helper = Wfdownloads\Helper::getInstance();

    $groups                   = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : [0 => XOOPS_GROUP_ANONYMOUS];
    $grouppermHandler             = xoops_getHandler('groupperm');
    $allowedDownCategoriesIds = $grouppermHandler->getItemIds('WFDownCatPerm', $groups, $helper->getModule()->mid());

    $block = [];

    // get downloads
    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('cid', '(' . implode(',', $allowedDownCategoriesIds) . ')', 'IN'));
    $criteria->add(new \Criteria('offline', false));
    $criteria->setSort($options[0]);
    $criteria->setOrder('DESC');
    $criteria->setLimit($options[1]);
    $downloadObjs = $helper->getHandler('Download')->getObjects($criteria);

    foreach ($downloadObjs as $downloadObj) {
        $download = $downloadObj->toArray();
        if (!in_array((int)$download['cid'], $allowedDownCategoriesIds)) {
            continue;
        }
        $download['title'] = xoops_substr($download['title'], 0, $options[2] - 1);
        $download['id']    = (int)$download['lid'];
        if ('published' === $options[0]) {
            $download['date'] = formatTimestamp($download['published'], $helper->getConfig('dateformat'));
        } else {
            $download['date'] = formatTimestamp($download['date'], $helper->getConfig('dateformat'));
        }
        $download['dirname']  = $helper->getModule()->dirname();
        $block['downloads'][] = $download;
    }

    return $block;
}

/**
 * @param $options
 *
 * @return string
 */
function wfdownloads_top_edit($options)
{
    $form = '' . _MB_WFDOWNLOADS_DISP . '&nbsp;';
    $form .= "<input type='hidden' name='options[]' value='" . (('published' === $options[0]) ? 'published' : 'hits') . "'>";
    $form .= "<input type='text' name='options[]' value='" . $options[1] . "'>&nbsp;" . _MB_WFDOWNLOADS_FILES . '';
    $form .= '<br>';
    $form .= '' . _MB_WFDOWNLOADS_CHARS . "&nbsp;<input type='text' name='options[]' value='" . $options[2] . "'>&nbsp;" . _MB_WFDOWNLOADS_LENGTH . '';

    return $form;
}
