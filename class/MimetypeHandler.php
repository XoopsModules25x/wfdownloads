<?php

namespace XoopsModules\Wfdownloads;

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
 * @license         GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package         wfdownload
 * @since           3.23
 * @author          Xoops Development Team
 */

use XoopsModules\Wfdownloads;

/*
CREATE TABLE wfdownloads_mimetypes (
  mime_id int(11) NOT NULL auto_increment,
  mime_ext varchar(60) NOT NULL default '',
  mime_types text NOT NULL,
  mime_name varchar(255) NOT NULL default '',
  mime_admin int(1) NOT NULL default '1',
  mime_user int(1) NOT NULL default '0',
  KEY mime_id (mime_id)
) ENGINE=MyISAM;
*/

require_once \dirname(__DIR__) . '/include/common.php';

/**
 * Class MimetypeHandler
 */
class MimetypeHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @access public
     */
    public $helper;

    /**
     * @param \XoopsDatabase|null $db
     */
    public function __construct(\XoopsDatabase $db = null)
    {
        parent::__construct($db, 'wfdownloads_mimetypes', Mimetype::class, 'mime_id', 'mime_ext');
        /** @var \XoopsModules\Wfdownloads\Helper $helper */
        $this->helper = Helper::getInstance();
    }
}
