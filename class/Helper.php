<?php namespace Xoopsmodules\wfdownloads;

/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

use Xoopsmodules\wfdownloads;

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * Class Helper
 */
class Helper extends \Xmf\Module\Helper
{
    public $debug;

    /**
     * @internal param $debug
     * @param bool $debug
     */
    protected function __construct($debug = false)
    {
        $this->debug   = $debug;
        $this->dirname = basename(dirname(__DIR__));
    }

    /**
     * @param bool $debug
     *
     * @return \Xoopsmodules\wfdownloads\Helper
     */
    public static function getInstance($debug = false)
    {
        static $instance;
        if (null === $instance) {
            $instance = new static($debug);
        }

        return $instance;
    }

    /**
     * @return string
     */
    public function getDirname()
    {
        return $this->dirname;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function getHandler($name)
    {
        if (!isset($this->handler[$name . 'Handler'])) {
            $this->_initHandler($name);
        }
        $this->addLog("Getting handler '{$name}'");
        if (isset($this->handler[$name . 'Handler'])) {
            return $this->handler[$name . 'Handler'];
        }
    }

    /**
     * @param $name
     */
    public function _initHandler($name)
    {
        $this->addLog('INIT ' . $name . ' HANDLER');
        $myClass                 = 'Xoopsmodules\\wfdownloads' . '\\' . ucfirst($name) . 'Handler';
        $db                      = \XoopsDatabaseFactory::getDatabase();
        $this->handler[$name . 'Handler'] = new $myClass($db);
    }
}
