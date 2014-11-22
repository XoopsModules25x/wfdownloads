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
include_once dirname(__DIR__) . '/include/common.php';

/**
 * Class WfdownloadsDownload
 */
class WfdownloadsDownload extends XoopsObject
{
    /**
     * @var WfdownloadsWfdownloads
     * @access public
     */
    public $wfdownloads = null;

    /**
     * @var WfdownloadsCategory
     * @access public
     */
    public $category = null;

    /**
     * @param int|null $id
     */
    public function __construct($id = null)
    {
        $this->wfdownloads = WfdownloadsWfdownloads::getInstance();
        $this->db          = XoopsDatabaseFactory::getDatabaseConnection();
        $this->initVar('lid', XOBJ_DTYPE_INT);
        $this->initVar('cid', XOBJ_DTYPE_INT, 0);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('url', XOBJ_DTYPE_URL, 'http://');
        $this->initVar('filename', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('filetype', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('homepage', XOBJ_DTYPE_URL, 'http://');
        $this->initVar('version', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('size', XOBJ_DTYPE_INT, 0);
        $this->initVar('platform', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('screenshot', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('screenshot2', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('screenshot3', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('screenshot4', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('submitter', XOBJ_DTYPE_INT);
        $this->initVar('publisher', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('status', XOBJ_DTYPE_INT, _WFDOWNLOADS_STATUS_WAITING);
        $this->initVar('date', XOBJ_DTYPE_INT);
        $this->initVar('hits', XOBJ_DTYPE_INT, 0);
        $this->initVar('rating', XOBJ_DTYPE_OTHER, 0.0);
        $this->initVar('votes', XOBJ_DTYPE_INT, 0);
        $this->initVar('comments', XOBJ_DTYPE_INT, 0);
        $this->initVar('license', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('mirror', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('price', XOBJ_DTYPE_TXTBOX, 0);
        $this->initVar('paypalemail', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('features', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('requirements', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('homepagetitle', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('forumid', XOBJ_DTYPE_INT, 0);
        $this->initVar('limitations', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('versiontypes', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('dhistory', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('published', XOBJ_DTYPE_INT, 0); // published time or 0
        $this->initVar('expired', XOBJ_DTYPE_INT, 0);
        $this->initVar('updated', XOBJ_DTYPE_INT, 0); // uploaded time or 0
        $this->initVar('offline', XOBJ_DTYPE_INT, false); // boolean
        $this->initVar('summary', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('description', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('ipaddress', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('notifypub', XOBJ_DTYPE_INT, 0);
        // Formulize module support (2006/05/04) jpc
        $this->initVar('formulize_idreq', XOBJ_DTYPE_INT, 0);
        // added 3.23
        $this->initVar('screenshots', XOBJ_DTYPE_ARRAY, array()); // IN PROGRESS
        $this->initVar('dohtml', XOBJ_DTYPE_INT, false); // boolean
        $this->initVar('dosmiley', XOBJ_DTYPE_INT, true); // boolean
        $this->initVar('doxcode', XOBJ_DTYPE_INT, true); // boolean
        $this->initVar('doimage', XOBJ_DTYPE_INT, true); // boolean
        $this->initVar('dobr', XOBJ_DTYPE_INT, true); // boolean

        if (isset($id)) {
            $item = $this->wfdownloads->getHandler('item')->get($id);
            foreach ($item->vars as $k => $v) {
                $this->assignVar($k, $v['value']);
            }
        }
    }

    /**
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        $arg = isset($args[0]) ? $args[0] : null;

        return $this->getVar($method, $arg);
    }

    /**
     * @return null|PublisherCategory
     */
    public function category()
    {
        if (!isset($this->_category)) {
            $this->_category = $this->wfdownloads->getHandler('category')->get($this->getVar('cid'));
        }

        return $this->_category;
    }

    function getDownloadInfo()
    {
        xoops_load('XoopsUserUtility');

        $download['id']  = $this->getVar('lid');
        $download['cid'] = $this->getVar('cid');

        $use_mirrors = $this->wfdownloads->getConfig('enable_mirrors');
        $add_mirror  = false;
        if (!is_object($GLOBALS['xoopsUser'])
            && ($this->wfdownloads->getConfig('anonpost') == _WFDOWNLOADS_ANONPOST_MIRROR
                || $this->wfdownloads->getConfig('anonpost') == _WFDOWNLOADS_ANONPOST_BOTH)
            && ($this->wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_MIRROR
                || $this->wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_BOTH)
            && $use_mirrors == true
        ) {
            $add_mirror = true;
        } elseif (is_object($GLOBALS['xoopsUser'])
            && ($this->wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_MIRROR
                || $this->wfdownloads->getConfig('submissions') == _WFDOWNLOADS_SUBMISSIONS_BOTH
                || wfdownloads_userIsAdmin())
            && $use_mirrors == true
        ) {
            $add_mirror = true;
        }
        $download['add_mirror']  = $add_mirror;
        $download['use_mirrors'] = $use_mirrors;

        $download['use_reviews'] = $this->wfdownloads->getConfig('enable_reviews');

        $download['use_ratings']    = $this->wfdownloads->getConfig('enable_ratings');
        $download['rateimg']        = "rate" . round(number_format($this->getVar('rating'), 0) / 2) . ".gif"; // this definition is not removed for backward compatibility issues
        $download['average_rating'] = $this->getVar('rating'); // new
        $download['votes']          = ($this->getVar('votes') == 1) ? _MD_WFDOWNLOADS_ONEVOTE : sprintf(_MD_WFDOWNLOADS_NUMVOTES, $this->getVar('votes'));
        $download['hits']           = $this->getVar('hits');

        $download['path'] = $this->wfdownloads->getHandler('category')->getNicePath($download['cid']);

        $download['imageheader'] = wfdownloads_headerImage();

        $download['title']    = trim($this->getVar('title'));
        $download['url']      = $this->getVar('url');
        $download['filename'] = $this->getVar('filename');
        $download['filetype'] = $this->getVar('filetype');
        /*
                if ($this->getVar('screenshot')) { // IN PROGRESS
                    $download['screenshot_full'] = $this->getVar('screenshot'); // IN PROGRESS
                    $download['screenshot_full1'] = $this->getVar('screenshot'); // IN PROGRESS
                    if ($this->getVar('screenshot') // IN PROGRESS
                        && file_exists(XOOPS_ROOT_PATH . '/' . $this->wfdownloads->getConfig('screenshots') . '/' . xoops_trim($this->getVar('screenshot')))
                    ) {
                        if ($this->wfdownloads->getConfig('usethumbs') == true) {
                            $download['screenshot_thumb'] = wfdownloads_createThumb(
                                $download['screenshot_full'], $this->wfdownloads->getConfig('screenshots'), 'thumbs',
                                $this->wfdownloads->getConfig('shotwidth'), $this->wfdownloads->getConfig('shotheight'),
                                $this->wfdownloads->getConfig('imagequality'), $this->wfdownloads->getConfig('updatethumbs'), $this->wfdownloads->getConfig('keepaspect')
                            );
                        } else {
                            $download['screenshot_thumb'] = XOOPS_URL . '/' . $this->wfdownloads->getConfig('screenshots') . '/' . xoops_trim($this->getVar('screenshot'));
                        }
                        $download['screenshot_thumb1'] = $download['screenshot_thumb']; // IN PROGRESS
                    }
                }
                if ($this->getVar('screenshot2') && $this->wfdownloads->getConfig('max_screenshot') >= 2) { // IN PROGRESS
                    $download['screenshot_full2'] = $this->getVar('screenshot2');
                    if ($this->getVar('screenshot2')
                        && file_exists(XOOPS_ROOT_PATH . '/' . $this->wfdownloads->getConfig('screenshots') . '/' . xoops_trim($this->getVar('screenshot2')))
                    ) {
                        if ($this->wfdownloads->getConfig('usethumbs') == true) {
                            $download['screenshot_thumb2'] = wfdownloads_createThumb(
                                $download['screenshot_full2'], $this->wfdownloads->getConfig('screenshots'), 'thumbs',
                                $this->wfdownloads->getConfig('shotwidth'), $this->wfdownloads->getConfig('shotheight'),
                                $this->wfdownloads->getConfig('imagequality'), $this->wfdownloads->getConfig('updatethumbs'), $this->wfdownloads->getConfig('keepaspect')
                            );
                        } else {
                            $download['screenshot_thumb2'] = XOOPS_URL . '/' . $this->wfdownloads->getConfig('screenshots') . '/' . xoops_trim($this->getVar('screenshot2'));
                        }
                    }
                }
                if ($this->getVar('screenshot3') && $this->wfdownloads->getConfig('max_screenshot') >= 3) { // IN PROGRESS
                    $download['screenshot_full3'] = $this->getVar('screenshot3');
                    if ($this->getVar('screenshot3')
                        && file_exists(XOOPS_ROOT_PATH . '/' . $this->wfdownloads->getConfig('screenshots') . '/' . xoops_trim($this->getVar('screenshot3')))
                    ) {
                        if ($this->wfdownloads->getConfig('usethumbs') == true) {
                            $download['screenshot_thumb3'] = wfdownloads_createThumb(
                                $download['screenshot_full3'], $this->wfdownloads->getConfig('screenshots'), 'thumbs',
                                $this->wfdownloads->getConfig('shotwidth'), $this->wfdownloads->getConfig('shotheight'),
                                $this->wfdownloads->getConfig('imagequality'), $this->wfdownloads->getConfig('updatethumbs'), $this->wfdownloads->getConfig('keepaspect')
                            );
                        } else {
                            $download['screenshot_thumb3'] = XOOPS_URL . '/' . $this->wfdownloads->getConfig('screenshots') . '/' . xoops_trim($this->getVar('screenshot3'));
                        }
                    }
                }
                if ($this->getVar('screenshot4') && $this->wfdownloads->getConfig('max_screenshot') >= 4) { // IN PROGRESS
                    $download['screenshot_full4'] = $this->getVar('screenshot4');
                    if ($this->getVar('screenshot4')
                        && file_exists(XOOPS_ROOT_PATH . '/' . $this->wfdownloads->getConfig('screenshots') . '/' . xoops_trim($this->getVar('screenshot4')))
                    ) {
                        if ($this->wfdownloads->getConfig('usethumbs') == true) {
                            $download['screenshot_thumb4'] = wfdownloads_createThumb(
                                $download['screenshot_full4'], $this->wfdownloads->getConfig('screenshots'), 'thumbs',
                                $this->wfdownloads->getConfig('shotwidth'), $this->wfdownloads->getConfig('shotheight'),
                                $this->wfdownloads->getConfig('imagequality'), $this->wfdownloads->getConfig('updatethumbs'), $this->wfdownloads->getConfig('keepaspect')
                            );
                        } else {
                            $download['screenshot_thumb4'] = XOOPS_URL . '/' . $this->wfdownloads->getConfig('screenshots') . '/' . xoops_trim($this->getVar('screenshot4'));
                        }
                    }
                }
        */
        // IN PROGRESS
        $screenshots             = $this->getVar('screenshots');
        $download['screenshots'] = array();
        foreach ($screenshots as $key => $screenshot) {
            if (file_exists(XOOPS_ROOT_PATH . '/' . $this->wfdownloads->getConfig('screenshots') . '/' . xoops_trim($screenshot))) {
                if ($this->wfdownloads->getConfig('usethumbs') == true
                    && $screenshot != ''
                ) {
                    $screenshot_thumb = wfdownloads_createThumb(
                        $screenshot,
                        $this->wfdownloads->getConfig('screenshots'),
                        'thumbs',
                        $this->wfdownloads->getConfig('shotwidth'),
                        $this->wfdownloads->getConfig('shotheight'),
                        $this->wfdownloads->getConfig('imagequality'),
                        $this->wfdownloads->getConfig('updatethumbs'),
                        $this->wfdownloads->getConfig('keepaspect')
                    );
                } else {
                    $screenshot_thumb = XOOPS_URL . '/' . $this->wfdownloads->getConfig('screenshots') . '/' . xoops_trim($screenshot);
                }
                $download['screenshots'][$key]['filename']  = $screenshot;
                $download['screenshots'][$key]['thumb_url'] = $screenshot_thumb;
                unset($screenshot_thumb);
            }
        }
        //
        $download['homepage'] = (!$this->getVar('homepage') || $this->getVar('homepage') == 'http://') ? '' : $GLOBALS['myts']->htmlSpecialChars(trim($this->getVar('homepage')));
        //
        $homepagetitle = $this->getVar('homepagetitle');
        if ($download['homepage'] && !empty($download['homepage'])) {
            $download['homepagetitle'] = ($homepagetitle != '') ? trim($download['homepage']) : trim($homepagetitle);
            $download['homepage']      = "<a href='" . $download['homepage'] . "' target='_blank'>" . $homepagetitle . "</a>";
        } else {
            $download['homepage'] = '';
        }
        //
        if ($use_mirrors !== true) {
            $download['mirror'] = ($this->getVar('mirror') == 'http://') ? '' : trim($this->getVar('mirror'));
            if ($download['mirror'] && !empty($download['mirror'])) {
                $download['mirror'] = "<a href='" . $download['mirror'] . "' target='_blank'>" . _MD_WFDOWNLOADS_MIRRORSITE . "</a>";
            } else {
                $download['mirror'] = '';
            }
        }
        //
        $download['comments'] = $this->getVar('comments');
        //
        $download['version'] = ($this->getVar('version')) ? $this->getVar('version') : 0;
        //
        $download['downtime'] = str_replace('|', '<br />', wfdownloads_getDownloadTime($this->getVar('size'), 1, 1, 1, 1, 0));
        //
        $download['size'] = wfdownloads_bytesToSize1024($this->getVar('size'));
        //
        $time                     = ($this->getVar('updated') != 0) ? $this->getVar('updated') : $this->getVar('published');
        $download['updated']      = formatTimestamp($time, $this->wfdownloads->getConfig('dateformat'));
        $download['lang_subdate'] = ($this->getVar('updated') != 0) ? _MD_WFDOWNLOADS_UPDATEDON : _MD_WFDOWNLOADS_SUBMITDATE;
        //
        $summary = $this->getVar('summary');
        if (($this->wfdownloads->getConfig('autosummary') == _WFDOWNLOADS_AUTOSUMMARY_YES)
            || ($this->wfdownloads->getConfig('autosummary') == _WFDOWNLOADS_AUTOSUMMARY_IFBLANK && empty($summary))
        ) {
            // generate auto summary from description field
            $download['summary'] = $this->getVar('description');
            // patch for multilanguage summary if xlanguage module is installed
            if (wfdownloads_checkModule('xlanguage')) {
                global $xlanguage;
                include_once XOOPS_ROOT_PATH . '/modules/xlanguage/include/vars.php';
                include_once XOOPS_ROOT_PATH . '/modules/xlanguage/include/functions.php';
                $download['summary'] = xlanguage_ml($download['summary']);
            }
            // html or plain text auto summary
            if ($this->wfdownloads->getConfig('autosumplaintext')) {
                $download['summary'] = strip_tags($download['summary'], "<br><br />");
            }
            // truncate auto summary
            $autosumLength = (int)$this->wfdownloads->getConfig('autosumlength');
            if (strlen($download['summary']) > $autosumLength) {
                $download['summary'] = wfdownloads_truncateHtml($download['summary'], $autosumLength, '...', false, true);
            }
        } else {
            $download['summary'] = $summary;
        }
        //
        $download['description'] = $this->getVar('description'); //no html
        //
        $download['price'] = ($this->getVar('price') != 0) ? $this->getVar('price') : _MD_WFDOWNLOADS_PRICEFREE;
        //
        $limitationsArray        = $this->wfdownloads->getConfig('limitations');
        $download['limitations'] = ($this->getVar('limitations') == '') ? _MD_WFDOWNLOADS_NOTSPECIFIED : $GLOBALS['myts']->htmlSpecialChars(trim($limitationsArray[$this->getVar('limitations')]));
        //
        $versiontypesArray        = $this->wfdownloads->getConfig('versiontypes');
        $download['versiontypes'] = ($this->getVar('versionypes') == '') ? _MD_WFDOWNLOADS_NOTSPECIFIED : $GLOBALS['myts']->htmlSpecialChars(trim($versiontypesArray[$this->getVar('versiontypes')]));
        $licenseArray             = $this->wfdownloads->getConfig('license');
        //
        $download['license'] = ($this->getVar('license') == '') ? _MD_WFDOWNLOADS_NOTSPECIFIED : $GLOBALS['myts']->htmlSpecialChars(trim($licenseArray[$this->getVar('license')]));
        //
        $download['submitter'] = XoopsUserUtility::getUnameFromId($this->getVar('submitter'));
        //
        $publisher             = $this->getVar('publisher');
        $download['publisher'] = (!empty($publisher)) ? $publisher : '';
        //
        $platformArray        = $this->wfdownloads->getConfig('platform');
        $download['platform'] = $GLOBALS['myts']->htmlSpecialChars($platformArray[$this->getVar('platform')]);
        //
        $history             = $this->getVar('dhistory', 'n');
        $download['history'] = $GLOBALS['myts']->displayTarea($history, true);
        //
        $download['features'] = array();
        if ($this->getVar('features')) {
            $features = explode('|', trim($this->getVar('features')));
            foreach ($features as $feature) {
                $download['features'][] = $feature;
            }
        }
        //
        $download['requirements'] = array();
        if ($this->getVar('requirements')) {
            $requirements = explode('|', trim($this->getVar('requirements')));
            foreach ($requirements as $requirement) {
                $download['requirements'][] = $requirement;
            }
        }
        //
        $download['mail_subject'] = rawurlencode(sprintf(_MD_WFDOWNLOADS_INTFILEFOUND, $GLOBALS['xoopsConfig']['sitename']));
        //
        $download['mail_body'] = rawurlencode(
            sprintf(_MD_WFDOWNLOADS_INTFILEFOUND, $GLOBALS['xoopsConfig']['sitename']) . ':  ' . WFDOWNLOADS_URL . '/singlefile.php?cid=' . $download['cid'] . '&amp;lid=' . $download['id']
        );
        //
        $download['isadmin'] = (wfdownloads_userIsAdmin()) ? true : false;
        //
        $download['adminlink'] = '';
        if ($download['isadmin'] == true) {
            $download['adminlink'] = '[<a href="' . WFDOWNLOADS_URL . '/admin/downloads.php?op=download.edit&amp;lid=' . $download['id'] . '">' . _MD_WFDOWNLOADS_EDIT . '</a> | ';
            $download['adminlink'] .= '<a href="' . WFDOWNLOADS_URL . '/admin/downloads.php?op=download.delete&amp;lid=' . $download['id'] . '">' . _MD_WFDOWNLOADS_DELETE . '</a>]';
        }
        //
        $download['is_updated'] = ($this->getVar('updated') > 0) ? _MD_WFDOWNLOADS_UPDATEDON : _MD_WFDOWNLOADS_SUBMITDATE;
        //
        if (is_object($GLOBALS['xoopsUser']) && $download['isadmin'] != true) {
            $download['useradminlink'] = ((int)($GLOBALS['xoopsUser']->getvar('uid')) == $this->getVar('submitter')) ? true : false; // this definition is not removed for backward compatibility issues
            $download['issubmitter']   = ((int)($GLOBALS['xoopsUser']->getvar('uid')) == $this->getVar('submitter')) ? true : false;
        }
        //
        $sql2 = "SELECT rated";
        $sql2 .= " FROM " . $GLOBALS['xoopsDB']->prefix('wfdownloads_reviews');
        $sql2 .= " WHERE lid = '" . (int)$download['id'] . "' AND submit = '1'";
        $results                 = $GLOBALS['xoopsDB']->query($sql2);
        $numrows                 = $GLOBALS['xoopsDB']->getRowsNum($results);
        $download['reviews_num'] = ($numrows) ? $numrows : 0;
        //
        $totalReviewsRating = 0;
        while ($review_text = $GLOBALS['xoopsDB']->fetchArray($results)) {
            $totalReviewsRating += $review_text['rated'];
        }
        $averageReviewsRating              = ($download['reviews_num'] > 0) ? $totalReviewsRating / $download['reviews_num'] : 0;
        $download['review_average_rating'] = $averageReviewsRating; // new
        //
        $download['review_rateimg'] = 'rate' . round(number_format($averageReviewsRating, 0) / 2) . '.gif'; // this definition is not removed for backward compatibility issues
        //
        $download['icons'] = wfdownloads_displayIcons($this->getVar('published'), $this->getVar('status'), $this->getVar('hits'));
        //
        $sql3 = "SELECT downurl";
        $sql3 .= " FROM " . $GLOBALS['xoopsDB']->prefix('wfdownloads_mirrors');
        $sql3 .= " WHERE lid = '" . (int)$download['id'] . "' AND submit = '1'";
        $results3                = $GLOBALS['xoopsDB']->query($sql3);
        $numrows2                = $GLOBALS['xoopsDB']->getRowsNum($results3);
        $download['mirrors_num'] = ($numrows2) ? $numrows2 : 0;
        // file url
        $fullFilename = trim($download['filename']);
        if ((!$download['url'] == '' && !$download['url'] == 'http://') || $fullFilename == '') {
            $download['file_url'] = $GLOBALS['myts']->htmlSpecialChars(preg_replace('/javascript:/si', 'javascript:', $download['url']), ENT_QUOTES);
        } else {
            $download['file_url'] = XOOPS_URL . str_replace(XOOPS_ROOT_PATH, '', $this->wfdownloads->getConfig('uploaddir')) . '/' . stripslashes(trim($fullFilename));
        }
        // has_custom_fields
        $download['has_custom_fields'] = (wfdownloads_checkModule('formulize') && $this->getVar('formulize_idreq'));

        return $download;
    }

    /**
     * @param array $customArray
     *
     * @return XoopsThemeForm
     */
    function getForm($customArray = array()) // $custom array added April 22, 2006 by jwe)
    {
        include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        include_once XOOPS_ROOT_PATH . '/class/tree.php';

        $groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);

        $use_mirrors = $this->wfdownloads->getConfig('enable_mirrors');

        $sform = new XoopsThemeForm(_MD_WFDOWNLOADS_SUBMITCATHEAD, 'storyform', $_SERVER['REQUEST_URI']);
        $sform->setExtra('enctype="multipart/form-data"');
        // download: title
        $sform->addElement(new XoopsFormText(_MD_WFDOWNLOADS_FILETITLE, 'title', 50, 255, $this->getVar('title', 'e')), true);
        // download: url
        $sform->addElement(new XoopsFormText(_MD_WFDOWNLOADS_DLURL, 'url', 50, 255, $this->getVar('url', 'e')), false);
        if (!$this->isNew()) {
            // download: filename
            $sform->addElement(new XoopsFormHidden('filename', $this->getVar('filename', 'e')));
            // download: filetype
            $sform->addElement(new XoopsFormHidden('filetype', $this->getVar('filetype', 'e')));
        }
        // download: userfile
        if (($this->wfdownloads->getConfig('useruploads') && array_intersect($this->wfdownloads->getConfig('useruploadsgroup'), $groups))
            || (wfdownloads_userIsAdmin())
        ) {
            $userfile_file = new XoopsFormFile(_MD_WFDOWNLOADS_UPLOAD_FILEC, 'userfile', 0);
            // get max file size (setup and php.ini)
            $phpiniMaxFileSize = (min((int)(ini_get('upload_max_filesize')), (int)(ini_get('post_max_size')), (int)(ini_get('memory_limit')))) * 1024 * 1024; // bytes
            $maxFileSize       = wfdownloads_bytesToSize1024(min($this->wfdownloads->getConfig('maxfilesize'), $phpiniMaxFileSize));
            // get allowed mimetypes
            if (wfdownloads_userIsAdmin()) {
                $criteria = new Criteria('mime_admin', true);
            } else {
                $criteria = new Criteria('mime_user', true);
            }
            $mimetypes         = $this->wfdownloads->getHandler('mimetype')->getList($criteria);
            $allowedExtensions = implode(' | ', $mimetypes);
            $userfile_file->setDescription(
                sprintf(
                    _MD_WFDOWNLOADS_UPLOAD_FILEC_DESC,
                    $maxFileSize,
                    $this->wfdownloads->getConfig('maximgwidth'),
                    $this->wfdownloads->getConfig('maximgheight'),
                    $allowedExtensions,
                    substr($allowedExtensions, 0, 40) . '...'
                )
            );
            $sform->addElement($userfile_file, false);
        }
        // download: mirror
        if ($use_mirrors !== true) {
            $sform->addElement(new XoopsFormText(_MD_WFDOWNLOADS_MIRROR, 'mirror', 50, 255, $this->getVar('mirror', 'e')), false);
        }
        // download: cid
// Formulize module support (2006/05/04) jpc - start
        if (wfdownloads_checkModule('formulize')) {
            $sform->addElement(new XoopsFormHidden('cid', $this->getVar('cid', 'e')));
        } else {
            $categoryObjs     = $this->wfdownloads->getHandler('category')->getUserUpCategories();
            $categoryObjsTree = new XoopsObjectTree($categoryObjs, 'cid', 'pid');
            $sform->addElement(new XoopsFormLabel(_MD_WFDOWNLOADS_CATEGORYC, $categoryObjsTree->makeSelBox('cid', 'title', '-', $this->getVar('cid', 'e'))));
        }

        if (count($customArray) == 0) {
            // download: homepagetitle
            $sform->addElement(
                new XoopsFormText(_MD_WFDOWNLOADS_HOMEPAGETITLEC, 'homepagetitle', 50, 255, $this->getVar('homepagetitle', 'e')),
                false
            );
            // download: homepage
            $sform->addElement(new XoopsFormText(_MD_WFDOWNLOADS_HOMEPAGEC, 'homepage', 50, 255, $this->getVar('homepage', 'e')), false);
            // download: version
            $sform->addElement(new XoopsFormText(_MD_WFDOWNLOADS_VERSIONC, 'version', 10, 20, $this->getVar('version', 'e')), false);
            // download: publisher
            $sform->addElement(new XoopsFormText(_MD_WFDOWNLOADS_PUBLISHERC, 'publisher', 50, 255, $this->getVar('publisher', 'e')), false);
            // download: size
            $sform->addElement(new XoopsFormText(_MD_WFDOWNLOADS_FILESIZEC, 'size', 10, 20, $this->getVar('size', 'e')), false);
            // download: platform
            $platform_array  = $this->wfdownloads->getConfig('platform');
            $platform_select = new XoopsFormSelect(_MD_WFDOWNLOADS_PLATFORMC, 'platform', $this->getVar('platform', 'e'));
            $platform_select->addOptionArray($platform_array);
            $sform->addElement($platform_select);
            // download: license
            $license_array  = $this->wfdownloads->getConfig('license');
            $license_select = new XoopsFormSelect(_MD_WFDOWNLOADS_LICENCEC, 'license', $this->getVar('license', 'e'));
            $license_select->addOptionArray($license_array);
            $sform->addElement($license_select);
            // download: limitations
            $limitations_array  = $this->wfdownloads->getConfig('limitations');
            $limitations_select = new XoopsFormSelect(_MD_WFDOWNLOADS_LIMITATIONS, 'limitations', $this->getVar('limitations', 'e'));
            $limitations_select->addOptionArray($limitations_array);
            $sform->addElement($limitations_select);
            // download: versiontype
            $versiontypes_array  = $this->wfdownloads->getConfig('versiontypes');
            $versiontypes_select = new XoopsFormSelect(_MD_WFDOWNLOADS_VERSIONTYPES, 'versiontypes', $this->getVar('versiontypes', 'e'));
            $versiontypes_select->addOptionArray($versiontypes_array);
            $sform->addElement($versiontypes_select);
            // download: price
            $sform->addElement(new XoopsFormText(_MD_WFDOWNLOADS_PRICEC, 'price', 10, 20, $this->getVar('price', 'e')), false);
            // download: summary
            switch ($this->wfdownloads->getConfig('autosummary')) {
                case _WFDOWNLOADS_AUTOSUMMARY_YES :
                    $summary_dhtmltextarea = new XoopsFormDhtmlTextArea(_MD_WFDOWNLOADS_SUMMARY, 'summary', $this->getVar('summary', 'e'), 10, 60, 'smartHiddenSummary');
                    $summary_dhtmltextarea->setDescription(_MD_WFDOWNLOADS_SUMMARY_DESC_AUTOSUMMARY_YES);
                    $summary_dhtmltextarea->setExtra('disabled', 'disabled');
                    $sform->addElement($summary_dhtmltextarea, false);
                    break;
                case _WFDOWNLOADS_AUTOSUMMARY_IFBLANK :
                    $summary_dhtmltextarea = new XoopsFormDhtmlTextArea(_MD_WFDOWNLOADS_SUMMARY, 'summary', $this->getVar('summary', 'e'), 10, 60, 'smartHiddenSummary');
                    $summary_dhtmltextarea->setDescription(_MD_WFDOWNLOADS_SUMMARY_DESC_AUTOSUMMARY_IFBLANK);
                    $sform->addElement($summary_dhtmltextarea, false);
                    break;
                default :
                case _WFDOWNLOADS_AUTOSUMMARY_NO :
                    $summary_dhtmltextarea = new XoopsFormDhtmlTextArea(_MD_WFDOWNLOADS_SUMMARY, 'summary', $this->getVar('summary', 'e'), 10, 60, 'smartHiddenSummary');
                    $summary_dhtmltextarea->setDescription(_MD_WFDOWNLOADS_SUMMARY_DESC_AUTOSUMMARY_NO);
                    $sform->addElement($summary_dhtmltextarea, false);
                    break;
            }
            // download: description
            $description_dhtmltextarea = new XoopsFormDhtmlTextArea(_MD_WFDOWNLOADS_DESCRIPTION, 'description', $this->getVar('description', 'e'), 15, 60, 'smartHiddenDescription');
            $description_dhtmltextarea->setDescription(_MD_WFDOWNLOADS_DESCRIPTION_DESC);
            $sform->addElement($description_dhtmltextarea, true);
            // download: dohtml, dosmiley, doxcode, doimage, dobr
            $options_tray = new XoopsFormElementTray(_MD_WFDOWNLOADS_TEXTOPTIONS, '<br />');
            $options_tray->setDescription(_MD_WFDOWNLOADS_TEXTOPTIONS_DESC);
            $html_checkbox = new XoopsFormCheckBox('', 'dohtml', $this->getVar('dohtml'));
            $html_checkbox->addOption(1, _MD_WFDOWNLOADS_ALLOWHTML);
            $options_tray->addElement($html_checkbox);
            $smiley_checkbox = new XoopsFormCheckBox('', 'dosmiley', $this->getVar('dosmiley'));
            $smiley_checkbox->addOption(1, _MD_WFDOWNLOADS_ALLOWSMILEY);
            $options_tray->addElement($smiley_checkbox);
            $xcodes_checkbox = new XoopsFormCheckBox('', 'doxcode', $this->getVar('doxcode'));
            $xcodes_checkbox->addOption(1, _MD_WFDOWNLOADS_ALLOWXCODE);
            $options_tray->addElement($xcodes_checkbox);
            $noimages_checkbox = new XoopsFormCheckBox('', 'doimage', $this->getVar('doimage'));
            $noimages_checkbox->addOption(1, _MD_WFDOWNLOADS_ALLOWIMAGES);
            $options_tray->addElement($noimages_checkbox);
            $breaks_checkbox = new XoopsFormCheckBox('', 'dobr', $this->getVar('dobr'));
            $breaks_checkbox->addOption(1, _MD_WFDOWNLOADS_ALLOWBREAK);
            $options_tray->addElement($breaks_checkbox);
            $sform->addElement($options_tray);
            // download: features
            $features_textarea = new XoopsFormTextArea(_MD_WFDOWNLOADS_KEYFEATURESC, 'features', $this->getVar('features', 'e'), 7, 60);
            $features_textarea->setDescription(_MD_WFDOWNLOADS_KEYFEATURESC_DESC);
            $sform->addElement($features_textarea, false);
            // download: requirements
            $requirements_textarea = new XoopsFormTextArea(_MD_WFDOWNLOADS_REQUIREMENTSC, 'requirements', $this->getVar('requirements', 'e'), 7, 60);
            $requirements_textarea->setDescription(_MD_WFDOWNLOADS_REQUIREMENTSC_DESC);
            $sform->addElement($requirements_textarea, false);
        } else {
            // if we are using a custom form, then add in the form's elements here
            $sform->addElement(
                new XoopsFormDhtmlTextArea(
                    _MD_WFDOWNLOADS_DESCRIPTION, 'description', $this->getVar(
                        'description',
                        'e'
                    ), 15, 60, 'smartHiddenDescription'
                ),
                true
            );
            $sform->addElement(new XoopsFormHidden('size', $this->getVar('size', 'e')));
            if (wfdownloads_checkModule('formulize')) {
                include_once XOOPS_ROOT_PATH . '/modules/formulize/include/formdisplay.php';
                include_once XOOPS_ROOT_PATH . '/modules/formulize/include/functions.php';
                $sform = compileElements( // is a Formulize function
                    $customArray['fid'],
                    $sform,
                    $customArray['formulize_mgr'],
                    $customArray['prevEntry'],
                    $customArray['entry'],
                    $customArray['go_back'],
                    $customArray['parentLinks'],
                    $customArray['owner_groups'],
                    $customArray['groups'],
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null
                );
            } else {
                // IN PROGRESS... formulize module not installed!!!
            }
        }
// Formulize module support (2006/05/04) jpc - end
        // download: dhistory
        $sform->addElement(new XoopsFormTextArea(_MD_WFDOWNLOADS_HISTORYC, 'dhistory', $this->getVar('dhistory', 'e'), 7, 60), false);
        if (!$this->isNew() && $this->getVar('dhistory', 'n') != '') {
            $dhistoryaddedd_textarea = new XoopsFormTextArea(_MD_WFDOWNLOADS_HISTORYD, 'dhistoryaddedd', '', 7, 60);
            $dhistoryaddedd_textarea->setDescription(_MD_WFDOWNLOADS_HISTORYD_DESC);
            $sform->addElement($dhistoryaddedd_textarea, false);
        }
        // download: screenshot, screenshot2, screenshot3, screenshot4
        if (($this->wfdownloads->getConfig('useruploads') && array_intersect($this->wfdownloads->getConfig('useruploadsgroup'), $groups))
            || wfdownloads_userIsAdmin()
        ) {
            $sform->addElement(new XoopsFormFile(_MD_WFDOWNLOADS_DUPLOADSCRSHOT, 'screenshot', 0), false); // IN PROGRESS
            if ($this->wfdownloads->getConfig('max_screenshot') >= 2) {
                $sform->addElement(new XoopsFormFile(_MD_WFDOWNLOADS_DUPLOADSCRSHOT, 'screenshot2', 0), false); // IN PROGRESS
            }
            if ($this->wfdownloads->getConfig('max_screenshot') >= 3) {
                $sform->addElement(new XoopsFormFile(_MD_WFDOWNLOADS_DUPLOADSCRSHOT, 'screenshot3', 0), false); // IN PROGRESS
            }
            if ($this->wfdownloads->getConfig('max_screenshot') >= 4) {
                $sform->addElement(new XoopsFormFile(_MD_WFDOWNLOADS_DUPLOADSCRSHOT, 'screenshot4', 0), false); // IN PROGRESS
            }
        }

        // download: notifypub
        $option_tray     = new XoopsFormElementTray(_MD_WFDOWNLOADS_OPTIONS, '<br />');
        $notify_checkbox = new XoopsFormCheckBox('', 'notifypub');
        $notify_checkbox->addOption(1, _MD_WFDOWNLOADS_NOTIFYAPPROVE);
        $option_tray->addElement($notify_checkbox);
        $sform->addElement($option_tray);
        // form: button tray
        $button_tray = new XoopsFormElementTray('', '');
        if ($this->isNew()) {
            $button_tray->addElement(new XoopsFormHidden('op', 'download.save'));
            $button_submit = new XoopsFormButton('', '', _SUBMIT, 'submit');
            //$button_submit->setExtra('onclick="this.form.elements.op.value=\'download.save\'"');
            $button_tray->addElement($button_submit);
        } else {
            $button_tray->addElement(new XoopsFormHidden('lid', (int)$this->getVar('lid')));
            $button_tray->addElement(new XoopsFormHidden('op', 'download.save'));
            $button_submit = new XoopsFormButton('', '', _SUBMIT, 'submit');
            //$button_submit->setExtra('onclick="this.form.elements.op.value=\'download.save\'"');
            $button_tray->addElement($button_submit);
        }
        $button_reset = new XoopsFormButton('', '', _RESET, 'reset');
        $button_tray->addElement($button_reset);
        $button_cancel = new XoopsFormButton('', '', _CANCEL, 'button');
        $button_cancel->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement($button_cancel);
        $sform->addElement($button_tray);

        return $sform;
    }

    /**
     * @param       $title
     * @param array $customArray
     *
     * @return XoopsThemeForm
     */
    function getAdminForm($title, $customArray = array()) // $custom array added April 22, 2006 by jwe
    {
        include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        include_once WFDOWNLOADS_ROOT_PATH . '/class/wfdownloads_lists.php';

        $use_mirrors = $this->wfdownloads->getConfig('enable_mirrors');

        $sform = new XoopsThemeForm($title, 'storyform', $_SERVER['REQUEST_URI']);
        $sform->setExtra('enctype="multipart/form-data"');
        // download: lid
        if (!$this->isNew()) {
            $sform->addElement(new XoopsFormLabel(_AM_WFDOWNLOADS_FILE_ID, (int)$this->getVar('lid')));
        }
        // download: ipaddress
        if ($this->getVar('ipaddress') != '') {
            $sform->addElement(new XoopsFormLabel(_AM_WFDOWNLOADS_FILE_IP, $this->getVar('ipaddress')));
        }
        // download: title
        $titles_tray = new XoopsFormElementTray(_AM_WFDOWNLOADS_FILE_TITLE, '<br />');
        $titles      = new XoopsFormText('', 'title', 50, 255, $this->getVar('title', 'e'));
        $titles_tray->addElement($titles);
        $titles_checkbox = new XoopsFormCheckBox('', 'title_checkbox', 0);
        $titles_checkbox->addOption(1, _AM_WFDOWNLOADS_FILE_USE_UPLOAD_TITLE);
        $titles_tray->addElement($titles_checkbox);
        $sform->addElement($titles_tray);
        // download: submitter
        if (!$this->isNew()) {
            //$sform -> addElement(new XoopsFormText(_AM_WFDOWNLOADS_FILE_SUBMITTERID, 'submitter', 10, 10, $this->getVar('submitter', 'e')), true);
            $submitter_select = new XoopsFormSelectUser (
                _AM_WFDOWNLOADS_FILE_SUBMITTER, 'submitter', false, $this->getVar(
                    'submitter',
                    'e'
                ), 1, false
            );
            $submitter_select->setDescription(_AM_WFDOWNLOADS_FILE_SUBMITTER_DESC);
            $sform->addElement($submitter_select);
        } else {
            $sform->addElement(new XoopsFormHidden('submitter', $GLOBALS['xoopsUser']->getVar('uid', 'e')));
        }
        // download: url
        $sform->addElement(new XoopsFormText(_AM_WFDOWNLOADS_FILE_DLURL, 'url', 50, 255, $this->getVar('url', 'e')), false);
        // download: filename
        $sform->addElement(new XoopsFormText(_AM_WFDOWNLOADS_FILE_FILENAME, 'filename', 50, 255, $this->getVar('filename', 'e')), false);
        // download: filetype
        $sform->addElement(new XoopsFormText(_AM_WFDOWNLOADS_FILE_FILETYPE, 'filetype', 50, 100, $this->getVar('filetype', 'e')), false);
        // download: mirror
        if ($use_mirrors !== true) {
            $sform->addElement(new XoopsFormText(_AM_WFDOWNLOADS_FILE_MIRRORURL, 'mirror', 50, 255, $this->getVar('mirror', 'e')), false);
        }
        // download: userfile
        $userfile_file = new XoopsFormFile(_MD_WFDOWNLOADS_UPLOAD_FILEC, 'userfile', 0);
        // get max file size (setup and php.ini)
        $phpiniMaxFileSize = min((int)(ini_get('upload_max_filesize')), (int)(ini_get('post_max_size')), (int)(ini_get('memory_limit'))) * 1024 * 1024; // bytes
        $maxFileSize       = wfdownloads_bytesToSize1024(min($this->wfdownloads->getConfig('maxfilesize'), $phpiniMaxFileSize));
        // get allowed mimetypes
        $criteria          = new Criteria('mime_admin', true);
        $mimetypes         = $this->wfdownloads->getHandler('mimetype')->getList($criteria);
        $allowedExtensions = implode(' | ', $mimetypes);
        $userfile_file->setDescription(
            sprintf(
                _MD_WFDOWNLOADS_UPLOAD_FILEC_DESC,
                $maxFileSize,
                $this->wfdownloads->getConfig('maximgwidth'),
                $this->wfdownloads->getConfig('maximgheight'),
                $allowedExtensions,
                substr($allowedExtensions, 0, 40) . '...'
            )
        );
        $sform->addElement($userfile_file, false);
        // download: cid
        $categoryObjs     = $this->wfdownloads->getHandler('category')->getObjects();
        $categoryObjsTree = new XoopsObjectTree($categoryObjs, 'cid', 'pid');
        $sform->addElement(
            new XoopsFormLabel(_AM_WFDOWNLOADS_FILE_CATEGORY, $categoryObjsTree->makeSelBox('cid', 'title', '-', $this->getVar('cid', 'e')))
        );
// Formulize module support (2006/03/06, 2006/03/08) jpc - start
        if (count($customArray) == 0) {
            // download: homepagetitle
            $sform->addElement(
                new XoopsFormText(_AM_WFDOWNLOADS_FILE_HOMEPAGETITLE, 'homepagetitle', 50, 255, $this->getVar('homepagetitle', 'e')),
                false
            );
            // download: homepage
            $sform->addElement(new XoopsFormText(_AM_WFDOWNLOADS_FILE_HOMEPAGE, 'homepage', 50, 255, $this->getVar('homepage', 'e')), false);
            // download: version
            $sform->addElement(new XoopsFormText(_AM_WFDOWNLOADS_FILE_VERSION, 'version', 10, 20, $this->getVar('version', 'e')), false);
            // download: publisher
            $sform->addElement(new XoopsFormText(_AM_WFDOWNLOADS_FILE_PUBLISHER, 'publisher', 50, 255, $this->getVar('publisher', 'e')), false);
            // download: size
            $sform->addElement(new XoopsFormText(_AM_WFDOWNLOADS_FILE_SIZE, 'size', 10, 20, $this->getVar('size', 'e')), false);
            // download: platform
            $platform_array  = $this->wfdownloads->getConfig('platform');
            $platform_select = new XoopsFormSelect('', 'platform', $this->getVar('platform', 'e'), '', '', 0);
            $platform_select->addOptionArray($platform_array);
            $platform_tray = new XoopsFormElementTray(_AM_WFDOWNLOADS_FILE_PLATFORM, '&nbsp;');
            $platform_tray->addElement($platform_select);
            $sform->addElement($platform_tray);
            // download: license
            $license_array  = $this->wfdownloads->getConfig('license');
            $license_select = new XoopsFormSelect('', 'license', $this->getVar('license', 'e'), '', '', 0);
            $license_select->addOptionArray($license_array);
            $license_tray = new XoopsFormElementTray(_AM_WFDOWNLOADS_FILE_LICENCE, '&nbsp;');
            $license_tray->addElement($license_select);
            $sform->addElement($license_tray);
            // download: limitations
            $limitations_array  = $this->wfdownloads->getConfig('limitations');
            $limitations_select = new XoopsFormSelect('', 'limitations', $this->getVar('limitations', 'e'), '', '', 0);
            $limitations_select->addOptionArray($limitations_array);
            $limitations_tray = new XoopsFormElementTray(_AM_WFDOWNLOADS_FILE_LIMITATIONS, '&nbsp;');
            $limitations_tray->addElement($limitations_select);
            $sform->addElement($limitations_tray);
            // download: versiontypes
            $versiontypes_array  = $this->wfdownloads->getConfig('versiontypes');
            $versiontypes_select = new XoopsFormSelect('', 'versiontypes', $this->getVar('versiontypes', 'e'), '', '', 0);
            $versiontypes_select->addOptionArray($versiontypes_array);
            $versiontypes_tray = new XoopsFormElementTray(_AM_WFDOWNLOADS_FILE_VERSIONTYPES, '&nbsp;');
            $versiontypes_tray->addElement($versiontypes_select);
            $sform->addElement($versiontypes_tray);
            // download: versiontypes
            $sform->addElement(new XoopsFormText(_AM_WFDOWNLOADS_FILE_PRICE, 'price', 10, 20, $this->getVar('price', 'e')), false);
            // download: summary
            $mode              = 'html';
            $summary_tray      = new XoopsFormElementTray(_MD_WFDOWNLOADS_SUMMARY, '<br />');
            $options['name']   = 'summary';
            $options['value']  = $this->getVar('summary', 'e');
            $options['rows']   = 10;
            $options['cols']   = '100%';
            $options['width']  = '100%';
            $options['height'] = '200px';
            $options['mode']   = $mode; // for editors that support mode option
            $summary_editor    = new XoopsFormEditor(
                '', $this->wfdownloads->getConfig('editor_options'), $options, $nohtml = false, $onfailure = 'textarea'
            );
            $summary_tray->addElement($summary_editor);
            switch ($this->wfdownloads->getConfig('autosummary')) {
                case _WFDOWNLOADS_AUTOSUMMARY_YES :
                    $summary_tray->setDescription(_MD_WFDOWNLOADS_SUMMARY_DESC_AUTOSUMMARY_YES);
                    break;
                case _WFDOWNLOADS_AUTOSUMMARY_IFBLANK :
                    $summary_tray->setDescription(_MD_WFDOWNLOADS_SUMMARY_DESC_AUTOSUMMARY_IFBLANK);
                    break;
                default :
                case _WFDOWNLOADS_AUTOSUMMARY_NO :
                    $summary_tray->setDescription(_MD_WFDOWNLOADS_SUMMARY_DESC_AUTOSUMMARY_NO);
                    break;
            }
            $sform->addElement($summary_tray);
            // download: decription
            $description_tray   = new XoopsFormElementTray(_MD_WFDOWNLOADS_DESCRIPTION, '<br />');
            $options['name']    = 'description';
            $options['value']   = $this->getVar('description', 'e');
            $options['rows']    = 15;
            $options['cols']    = '100%';
            $options['width']   = '100%';
            $options['height']  = '200px';
            $description_editor = new XoopsFormEditor(
                '', $this->wfdownloads->getConfig('editor_options'), $options, $nohtml = false, $onfailure = 'textarea'
            );
            $description_tray->addElement($description_editor, true);
            $description_tray->setDescription(_MD_WFDOWNLOADS_DESCRIPTION_DESC);
            $sform->addElement($description_tray);
            // download: dohtml, dosmiley, doxcode, doimage, dobr
            $options_tray = new XoopsFormElementTray(_AM_WFDOWNLOADS_TEXTOPTIONS, ' ');
            $options_tray->setDescription(_AM_WFDOWNLOADS_TEXTOPTIONS_DESC);
            $html_checkbox = new XoopsFormCheckBox('', 'dohtml', $this->getVar('dohtml'));
            $html_checkbox->addOption(1, _AM_WFDOWNLOADS_ALLOWHTML);
            $options_tray->addElement($html_checkbox);
            $smiley_checkbox = new XoopsFormCheckBox('', 'dosmiley', $this->getVar('dosmiley'));
            $smiley_checkbox->addOption(1, _AM_WFDOWNLOADS_ALLOWSMILEY);
            $options_tray->addElement($smiley_checkbox);
            $xcodes_checkbox = new XoopsFormCheckBox('', 'doxcode', $this->getVar('doxcode'));
            $xcodes_checkbox->addOption(1, _AM_WFDOWNLOADS_ALLOWXCODE);
            $options_tray->addElement($xcodes_checkbox);
            $noimages_checkbox = new XoopsFormCheckBox('', 'doimage', $this->getVar('doimage'));
            $noimages_checkbox->addOption(1, _AM_WFDOWNLOADS_ALLOWIMAGES);
            $options_tray->addElement($noimages_checkbox);
            $breaks_checkbox = new XoopsFormCheckBox('', 'dobr', $this->getVar('dobr'));
            $breaks_checkbox->addOption(1, _AM_WFDOWNLOADS_ALLOWBREAK);
            $options_tray->addElement($breaks_checkbox);
            $sform->addElement($options_tray);
            // download: features
            $sform->addElement(new XoopsFormTextArea(_AM_WFDOWNLOADS_FILE_KEYFEATURES, 'features', $this->getVar('features', 'e'), 7, 60), false);
            // download: requirements
            $sform->addElement(
                new XoopsFormTextArea(_AM_WFDOWNLOADS_FILE_REQUIREMENTS, 'requirements', $this->getVar('requirements', 'e'), 7, 60),
                false
            );
        } else {
            // if we are using a custom form, then add in the form's elements here
            // download: description
            $description_tray   = new XoopsFormElementTray(_MD_WFDOWNLOADS_DESCRIPTION, '<br />');
            $options['name']    = 'description';
            $options['value']   = $this->getVar('description', 'e');
            $options['rows']    = 15;
            $options['cols']    = '100%';
            $options['width']   = '100%';
            $options['height']  = '200px';
            $description_editor = new XoopsFormEditor(
                '', $this->wfdownloads->getConfig('editor_options'), $options, $nohtml = false, $onfailure = 'textarea'
            );
            $description_tray->addElement($description_editor, true);
            $description_tray->setDescription(_MD_WFDOWNLOADS_DESCRIPTION_DESC);
            $sform->addElement($description_tray);
            // download: size
            $sform->addElement(new XoopsFormHidden('size', $this->getVar('size', 'e')));

            if (wfdownloads_checkModule('formulize')) {
                include_once XOOPS_ROOT_PATH . '/modules/formulize/include/formdisplay.php';
                include_once XOOPS_ROOT_PATH . '/modules/formulize/include/functions.php';
                $sform = compileElements( // is a Formulize function
                    $customArray['fid'],
                    $sform,
                    $customArray['formulize_mgr'],
                    $customArray['prevEntry'],
                    $customArray['entry'],
                    $customArray['go_back'],
                    $customArray['parentLinks'],
                    $customArray['owner_groups'],
                    $customArray['groups'],
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null
                );
            } else {
                // IN PROGRESS... Formulize module not installed!!!
            }
        }
// Formulize module support (2006/03/06, 2006/03/08) jpc - end
        // download: dhistory
        $sform->addElement(new XoopsFormTextArea(_AM_WFDOWNLOADS_FILE_HISTORY, 'dhistory', $this->getVar('dhistory', 'e'), 7, 60), false);
        if (!$this->isNew() && $this->getVar('dhistory') != '') {
            $sform->addElement(new XoopsFormTextArea(_AM_WFDOWNLOADS_FILE_HISTORYD, 'dhistoryaddedd', '', 7, 60), false);
        }

        // download: screenshot
        $graph_array1       = WfsLists::getListTypeAsArray(XOOPS_ROOT_PATH . '/' . $this->wfdownloads->getConfig('screenshots'), 'images');
        $indeximage_select1 = new XoopsFormSelect('', 'screenshot', $this->getVar('screenshot', 'e'));
        $indeximage_select1->addOptionArray($graph_array1);
        $indeximage_select1->setExtra("onchange='showImgSelected(\"image1\", \"screenshot\", \"" . $this->wfdownloads->getConfig('screenshots') . "\", \"\", \"" . XOOPS_URL . "\")'");
        $indeximage_tray1 = new XoopsFormElementTray(_AM_WFDOWNLOADS_FILE_SHOTIMAGE, '&nbsp;');
        $indeximage_tray1->addElement($indeximage_select1);
        if ($this->getVar('screenshot') != '') { // IN PROGRESS
            $indeximage_tray1->addElement(
                new XoopsFormLabel(
                    '',
                    "<br /><br /><img src='" . XOOPS_URL . '/' . $this->wfdownloads->getConfig('screenshots') . '/' . $this->getVar('screenshot', 'e') . "' id='image1' alt='' title='screenshot 1' />"
                )
            );
        } else {
            $indeximage_tray1->addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/uploads/blank.gif' id='image1' alt='' title='' />"));
        }
        $sform->addElement($indeximage_tray1);

        // download: screenshot2
        $graph_array2       = WfsLists::getListTypeAsArray(XOOPS_ROOT_PATH . '/' . $this->wfdownloads->getConfig('screenshots'), 'images');
        $indeximage_select2 = new XoopsFormSelect('', 'screenshot2', $this->getVar('screenshot2', 'e'));
        $indeximage_select2->addOptionArray($graph_array2);
        $indeximage_select2->setExtra("onchange='showImgSelected(\"image2\", \"screenshot2\", \"" . $this->wfdownloads->getConfig('screenshots') . "\", \"\", \"" . XOOPS_URL . "\")'");
        $indeximage_tray2 = new XoopsFormElementTray(_AM_WFDOWNLOADS_FILE_SHOTIMAGE, '&nbsp;');
        $indeximage_tray2->addElement($indeximage_select2);
        if ($this->getVar('screenshot2') != '') {
            $indeximage_tray2->addElement(
                new XoopsFormLabel(
                    '',
                    "<br /><br /><img src='" . XOOPS_URL . '/' . $this->wfdownloads->getConfig('screenshots') . '/' . $this->getVar('screenshot2', 'e') . "' id='image2' alt='' title='screenshot 2' />"
                )
            );
        } else {
            $indeximage_tray2->addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/uploads/blank.gif' id='image2' alt='' title='' />"));
        }
        $sform->addElement($indeximage_tray2);

        // download: screenshot3
        $graph_array3       = WfsLists::getListTypeAsArray(XOOPS_ROOT_PATH . '/' . $this->wfdownloads->getConfig('screenshots'), 'images');
        $indeximage_select3 = new XoopsFormSelect('', 'screenshot3', $this->getVar('screenshot3', 'e', true));
        $indeximage_select3->addOptionArray($graph_array3);
        $indeximage_select3->setExtra("onchange='showImgSelected(\"image3\", \"screenshot3\", \"" . $this->wfdownloads->getConfig('screenshots') . "\", \"\", \"" . XOOPS_URL . "\")'");
        $indeximage_tray3 = new XoopsFormElementTray(_AM_WFDOWNLOADS_FILE_SHOTIMAGE, '&nbsp;');
        $indeximage_tray3->addElement($indeximage_select3);
        if ($this->getVar('screenshot3') != '') {
            $indeximage_tray3->addElement(
                new XoopsFormLabel(
                    '',
                    "<br /><br /><img src='" . XOOPS_URL . '/' . $this->wfdownloads->getConfig('screenshots') . '/' . $this->getVar('screenshot3', 'e') . "' id='image3' alt='' title='screenshot 3' />"
                )
            );
        } else {
            $indeximage_tray3->addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/uploads/blank.gif' id='image3' alt='' title='' />"));
        }
        $sform->addElement($indeximage_tray3);

        // download: screenshot4
        $graph_array4       = WfsLists::getListTypeAsArray(
            XOOPS_ROOT_PATH . '/' . $this->wfdownloads->getConfig('screenshots'),
            'images'
        );
        $indeximage_select4 = new XoopsFormSelect('', 'screenshot4', $this->getVar('screenshot4', 'e'));
        $indeximage_select4->addOptionArray($graph_array4);
        $indeximage_select4->setExtra("onchange='showImgSelected(\"image4\", \"screenshot4\", \"" . $this->wfdownloads->getConfig('screenshots') . "\", \"\", \"" . XOOPS_URL . "\")'");
        $indeximage_tray4 = new XoopsFormElementTray(_AM_WFDOWNLOADS_FILE_SHOTIMAGE, '&nbsp;');
        $indeximage_tray4->addElement($indeximage_select4);
        if ($this->getVar('screenshot4') != "") {
            $indeximage_tray4->addElement(
                new XoopsFormLabel(
                    '',
                    "<br /><br /><img src='" . XOOPS_URL . '/' . $this->wfdownloads->getConfig('screenshots') . '/' . $this->getVar('screenshot4', 'e') . "' id='image4' alt='' title='screenshot 4' />"
                )
            );
        } else {
            $indeximage_tray4->addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/uploads/blank.gif' id='image4' alt='' title='' />"));
        }
        $sform->addElement($indeximage_tray4);

        $sform->insertBreak(sprintf(_AM_WFDOWNLOADS_FILE_MUSTBEVALID, "<b>" . $this->wfdownloads->getConfig('screenshots') . "</b>"), "even");

        // download: published
        $publishtext = ($this->isNew() || $this->getVar('published') == 0) ? _AM_WFDOWNLOADS_FILE_SETPUBLISHDATE : _AM_WFDOWNLOADS_FILE_SETNEWPUBLISHDATE;
        if ($this->getVar('published') > time()) {
            $publishtext = _AM_WFDOWNLOADS_FILE_SETPUBDATESETS;
        }
        $ispublished          = ($this->getVar('published') > time()) ? true : false;
        $publishdates         = ($this->getVar('published') > time()) ? _AM_WFDOWNLOADS_FILE_PUBLISHDATESET . formatTimestamp($this->getVar('published', 'e'), "Y-m-d H:s")
            : _AM_WFDOWNLOADS_FILE_SETDATETIMEPUBLISH;
        $publishdate_checkbox = new XoopsFormCheckBox('', 'publishdateactivate', $ispublished);
        $publishdate_checkbox->addOption(1, $publishdates . '<br />');
        if (!$this->isNew()) {
            $sform->addElement(new XoopsFormHidden('was_published', $this->getVar('published', 'e')));
            $sform->addElement(new XoopsFormHidden('was_expired', $this->getVar('expired', 'e')));
        }
        $publishdate_tray = new XoopsFormElementTray(_AM_WFDOWNLOADS_FILE_PUBLISHDATE, '');
        $publishdate_tray->addElement($publishdate_checkbox);
        $publishdate_tray->addElement(new XoopsFormDateTime($publishtext, 'published', 15, $this->getVar('published', 'e')));
        $publishdate_tray->addElement(new XoopsFormRadioYN(_AM_WFDOWNLOADS_FILE_CLEARPUBLISHDATE, 'clearpublish', 0));
        $sform->addElement($publishdate_tray);
        // download: expired
        $isexpired           = ($this->getVar('expired', 'e') > time()) ? true : false;
        $expiredates         = ($this->getVar('expired', 'e') > time()) ? _AM_WFDOWNLOADS_FILE_EXPIREDATESET . formatTimestamp($this->getVar('expired'), 'Y-m-d H:s')
            : _AM_WFDOWNLOADS_FILE_SETDATETIMEEXPIRE;
        $warning             = ($this->getVar('published') > $this->getVar('expired') && $this->getVar('expired') > time()) ? _AM_WFDOWNLOADS_FILE_EXPIREWARNING : '';
        $expiredate_checkbox = new XoopsFormCheckBox('', 'expiredateactivate', $isexpired);
        $expiredate_checkbox->addOption(1, $expiredates . '<br />');
        $expiredate_tray = new XoopsFormElementTray(_AM_WFDOWNLOADS_FILE_EXPIREDATE . $warning, '');
        $expiredate_tray->addElement($expiredate_checkbox);
        $expiredate_tray->addElement(new XoopsFormDateTime(_AM_WFDOWNLOADS_FILE_SETEXPIREDATE, 'expired', 15, $this->getVar('expired')));
        $expiredate_tray->addElement(new XoopsFormRadioYN(_AM_WFDOWNLOADS_FILE_CLEAREXPIREDATE, 'clearexpire', 0));
        $sform->addElement($expiredate_tray);
        // download: offline
        $filestatus_radio = new XoopsFormRadioYN(_AM_WFDOWNLOADS_FILE_FILESSTATUS, 'offline', $this->getVar('offline', 'e'));
        $sform->addElement($filestatus_radio);
        // download: up_dated
        $file_updated_radio = new XoopsFormRadioYN(_AM_WFDOWNLOADS_FILE_SETASUPDATED, 'up_dated', ($this->getVar('updated', 'e') == true));
        $sform->addElement($file_updated_radio);
        // download: approved
        if (!$this->isNew() && $this->getVar('published') == 0) {
            $approved         = ($this->getVar('published') == 0) ? false : true;
            $approve_checkbox = new XoopsFormCheckBox(_AM_WFDOWNLOADS_FILE_EDITAPPROVE, 'approved', true);
            $approve_checkbox->addOption(1, " ");
            $sform->addElement($approve_checkbox);
        }
        // form: button tray
        $button_tray = new XoopsFormElementTray('', '');
        $button_tray->addElement(new XoopsFormHidden('op', 'download.save'));
        if ($this->isNew()) {
            $button_tray->addElement(new XoopsFormHidden('status', _WFDOWNLOADS_STATUS_APPROVED));
            $button_tray->addElement(new XoopsFormHidden('notifypub', $this->getVar('notifypub', 'e')));

            $button_tray->addElement(new XoopsFormButton('', '', _SUBMIT, 'submit'));
        } else {
            $button_tray->addElement(new XoopsFormHidden('status', _WFDOWNLOADS_STATUS_UPDATED));
            $button_tray->addElement(new XoopsFormHidden('lid', (int)$this->getVar('lid')));
            $button_submit = new XoopsFormButton('', '', _SUBMIT, 'submit');
            $button_submit->setExtra('onclick="this.form.elements.op.value=\'download.save\'"');
            $button_tray->addElement($button_submit);
            $button_delete = new XoopsFormButton('', '', _DELETE, 'submit');
            $button_delete->setExtra('onclick="this.form.elements.op.value=\'download.delete\'"');
            $button_tray->addElement($button_delete);
        }
        $button_reset = new XoopsFormButton('', '', _RESET, 'reset');
        $button_tray->addElement($button_reset);
        $button_cancel = new XoopsFormButton('', '', _CANCEL, 'button');
        $button_cancel->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement($button_cancel);

        $sform->addElement($button_tray);

        return $sform;
    }

// Formulize module support (2006/03/06, 2006/03/08) jpc - start
    /**
     * @param $title
     *
     * @return XoopsThemeForm
     */
    function getCategoryForm($title)
    {
        include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        include_once XOOPS_ROOT_PATH . '/class/tree.php';
        $sform = new XoopsThemeForm($title, 'storyform', $_SERVER['REQUEST_URI']);
        $sform->setExtra('enctype="multipart/form-data"');
        // download: cid
        $categoryObjs     = $this->wfdownloads->getHandler('category')->getUserUpCategories();
        $categoryObjsTree = new XoopsObjectTree($categoryObjs, 'cid', 'pid');
        $sform->addElement(
            new XoopsFormLabel(_MD_WFDOWNLOADS_CATEGORYC, $categoryObjsTree->makeSelBox('cid', 'title', '-', $this->getVar('cid', 'e')))
        );
        // form: button tray
        $button_tray = new XoopsFormElementTray('', '');
        $button_tray->addElement(new XoopsFormButton('', 'submit_category', _SUBMIT, 'submit'));
        if (!$this->isNew()) {
            $button_tray->addElement(new XoopsFormHidden('lid', $this->getVar('lid', 'e')));
        }
        $sform->addElement($button_tray);

        return $sform;
    }
// Formulize module support (2006/03/06, 2006/03/08) jpc - end

    /**
     * Returns an array representation of the object
     *
     * @return array
     */
    function toArray()
    {
        $ret  = array();
        $vars = $this->getVars();
        foreach (array_keys($vars) as $i) {
            $ret[$i] = $this->getVar($i);
        }

        return $ret;
    }
}

/**
 * Class WfdownloadsDownloadHandler
 */
class WfdownloadsDownloadHandler extends XoopsPersistableObjectHandler
{
    /**
     * @var WfdownloadsWfdownloads
     * @access public
     */
    public $wfdownloads = null;

    /**
     * @param null|object $db
     */
    public function __construct(&$db)
    {
        parent::__construct($db, 'wfdownloads_downloads', 'WfdownloadsDownload', 'lid', 'title');
        $this->wfdownloads = WfdownloadsWfdownloads::getInstance();
    }

    /**
     * Get maximum published date from a criteria
     *
     * @param CriteriaElement $criteria
     *
     * @return mixed
     */
    public function getMaxPublishdate($criteria = null)
    {
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            if ($criteria->groupby != '') {
                $groupby = true;
                $field   = $criteria->groupby . ", "; //Not entirely secure unless you KNOW that no criteria's groupby clause is going to be mis-used
            }
        }
        $sql = "SELECT " . $field . "MAX(published) FROM " . $this->table;
        if (is_object($criteria)) {
            $sql .= " " . $criteria->renderWhere();
            if ($criteria->groupby != "") {
                $sql .= $criteria->getGroupby();
            }
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        if ($groupby == false) {
            list($count) = $this->db->fetchRow($result);

            return $count;
        } else {
            $ret = array();
            while (list($id, $count) = $this->db->fetchRow($result)) {
                $ret[$id] = $count;
            }

            return $ret;
        }
    }

    /**
     * Get criteria for active downloads
     *
     * @return CriteriaElement
     */
    public function getActiveCriteria()
    {
        $gperm_handler = xoops_gethandler('groupperm');

        $criteria = new CriteriaCompo(new Criteria('offline', false));
        $criteria->add(new Criteria('published', 0, '>'));
        $criteria->add(new Criteria('published', time(), '<='));
        $expiredCriteria = new CriteriaCompo(new Criteria('expired', 0));
        $expiredCriteria->add(new Criteria('expired', time(), '>='), 'OR');
        $criteria->add($expiredCriteria);
        // add criteria for categories that the user has permissions for
        $groups                   = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : array(0 => XOOPS_GROUP_ANONYMOUS);
        $allowedDownCategoriesIds = $gperm_handler->getItemIds('WFDownCatPerm', $groups, $this->wfdownloads->getModule()->mid());
        $criteria->add(new Criteria('cid', '(' . implode(',', $allowedDownCategoriesIds) . ')', 'IN'));

        return $criteria;
    }

    /**
     * Get array of active downloads with optional additional criteria
     *
     * @param CriteriaCompo $crit Additional criteria
     *
     * @return array
     */
    public function getActiveDownloads($crit = null)
    {
        if (is_object($crit)) {
            $criteria = $crit;
        } else {
            $criteria = new CriteriaCompo();
        }
        $active_crit = $this->getActiveCriteria();
        $criteria->add($active_crit);

        return $this->getObjects($criteria);
    }

    /**
     * Get count of active downloads
     *
     * @param CriteriaElement $crit Additional criteria
     *
     * @return array/int
     */
    public function getActiveCount($crit = null)
    {
        $criteria = $this->getActiveCriteria();
        if (is_object($crit)) {
            $criteria->add($crit);
        }

        return $this->getCount($criteria);
    }

    /**
     * Increment hit counter for a download
     *
     * @param int $lid
     *
     * @return bool
     */
    public function incrementHits($lid)
    {
        $sql = "UPDATE " . $this->table . " SET hits=hits+1 WHERE lid='" . (int)$lid . "'";

        return $this->db->queryF($sql);
    }

    /**
     * @param object $download
     * @param bool   $force
     *
     * @return bool
     */
    public function delete($download, $force = false)
    {
        if (parent::delete($download, $force)) {
            $criteria = new Criteria('lid', (int)$download->getVar('lid'));
            $this->wfdownloads->getHandler('rating')->deleteAll($criteria);
            $this->wfdownloads->getHandler('mirror')->deleteAll($criteria);
            $this->wfdownloads->getHandler('review')->deleteAll($criteria);
            $this->wfdownloads->getHandler('report')->deleteAll($criteria);
            // delete comments
            xoops_comment_delete((int)$this->wfdownloads->getModule()->mid(), (int)$download->getVar('lid'));

// Formulize module support (2006/05/04) jpc - start
            if (wfdownloads_checkModule('formulize')) {
                if (file_exists(XOOPS_ROOT_PATH . '/modules/formulize/include/functions.php') && $download->getVar('formulize_idreq') > 0) {
                    include_once XOOPS_ROOT_PATH . '/modules/formulize/include/functions.php';
                    //deleteFormEntries(array($download->getVar('formulize_idreq')));
                    $category = $this->wfdownloads->getHandler('category')->get($download->getVar('cid'));
                    deleteFormEntries(array($download->getVar('formulize_idreq')), $category->getVar('formulize_fid'));
                }
            }
// Formulize module support (2006/05/04) jpc - end
            return true;
        }

        return false;
    }
}
