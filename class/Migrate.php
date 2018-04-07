<?php namespace XoopsModules\Wfdownloads;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */



/**
 * Class Migrate synchronize existing tables with target schema
 *
 * @category  Migrate
 * @package   Newbb
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2016 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
class Migrate extends \Xmf\Database\Migrate
{
//    private $renameTables = [
//        'bb_archive'     => 'newbb_archive',
//        'bb_categories'  => 'newbb_categories',
//        'bb_votedata'    => 'newbb_votedata',
//        'bb_forums'      => 'newbb_forums',
//        'bb_posts'       => 'newbb_posts',
//        'bb_posts_text'  => 'newbb_posts_text',
//        'bb_topics'      => 'newbb_topics',
//        'bb_online'      => 'newbb_online',
//        'bb_digest'      => 'newbb_digest',
//        'bb_report'      => 'newbb_report',
//        'bb_attachments' => 'newbb_attachments',
//        'bb_moderates'   => 'newbb_moderates',
//        'bb_reads_forum' => 'newbb_reads_forum',
//        'bb_reads_topic' => 'newbb_reads_topic',
//        'bb_type'        => 'newbb_type',
//        'bb_type_forum'  => 'newbb_type_forum',
//        'bb_stats'       => 'newbb_stats',
//        'bb_user_stats'  => 'newbb_user_stats',
//    ];

    /**
     * Migrate constructor.
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        $moduleDirName = basename(dirname(__DIR__));
        parent::__construct($moduleDirName);
    }

    /**
     * change table prefix if needed
     */
    private function changePrefix()
    {
        foreach ($this->renameTables as $oldName => $newName) {
            if ($this->tableHandler->useTable($oldName)) {
                $this->tableHandler->renameTable($oldName, $newName);
            }
        }
    }

    /**
     * Change integer IPv4 column to varchar IPv6 capable
     *
     * @param string $tableName  table to convert
     * @param string $columnName column with IP address
     *
     * @return void
     */
    private function convertIPAddresses($tableName, $columnName)
    {
        if ($this->tableHandler->useTable($tableName)) {
            $attributes = $this->tableHandler->getColumnAttributes($tableName, $columnName);
            if (false !== strpos($attributes, ' int(')) {
                if (false === strpos($attributes, 'unsigned')) {
                    $this->tableHandler->alterColumn($tableName, $columnName, " bigint(16) NOT NULL  DEFAULT '0' ");
                    $this->tableHandler->update($tableName, [$columnName => "4294967296 + $columnName"], "WHERE $columnName < 0", false);
                }
                $this->tableHandler->alterColumn($tableName, $columnName, " varchar(45)  NOT NULL  DEFAULT '' ");
                $this->tableHandler->update($tableName, [$columnName => "INET_NTOA($columnName)"], '', false);
            }
        }
    }

    /**
     * Move do* columns from newbb_posts to newbb_posts_text table
     *
     * @return void
     */
    private function moveDoColumns()
    {
        $tableName    = 'newbb_posts_text';
        $srcTableName = 'newbb_posts';
        if (false !== $this->tableHandler->useTable($tableName)
            && false !== $this->tableHandler->useTable($srcTableName)) {
            $attributes = $this->tableHandler->getColumnAttributes($tableName, 'dohtml');
            if (false === $attributes) {
                $this->synchronizeTable($tableName);
                $updateTable = $GLOBALS['xoopsDB']->prefix($tableName);
                $joinTable   = $GLOBALS['xoopsDB']->prefix($srcTableName);
                $sql         = "UPDATE `$updateTable` t1 INNER JOIN `$joinTable` t2 ON t1.post_id = t2.post_id \n" . "SET t1.dohtml = t2.dohtml,  t1.dosmiley = t2.dosmiley, t1.doxcode = t2.doxcode\n" . '  , t1.doimage = t2.doimage, t1.dobr = t2.dobr';
                $this->tableHandler->addToQueue($sql);
            }
        }
    }

    /**
     * Perform any upfront actions before synchronizing the schema
     *
     * Some typical uses include
     *   table and column renames
     *   data conversions
     *
     * @return void
     */
    protected function preSyncActions()
    {
        //add the table if it's missing:
        $this->addMissingTable('wfdownloads_ip_log');

        // or Convert IP address columns from varchar(20) to readable varchar(45) for IPv6
        $this->convertIPAddresses('wfdownloads_ip_log', 'ip_address');


        // change 'bb' table prefix to 'newbb'
        $this->changePrefix();
        // columns dohtml, dosmiley, doxcode, doimage and dobr moved between tables as some point
        $this->moveDoColumns();
    }
}
