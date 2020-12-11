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
 * @author          marcan <marcan@smartfactory.ca>, Xoops Development Team
 */

/**
 * Contains the classes for updating database tables
 *
 * @license    GNU
 * @author     marcan <marcan@smartfactory.ca>
 * @link       http://www.smartfactory.ca The SmartFactory
 * @package    Wfdownloads
 * @subpackage dbUpdater
 */

use XoopsModules\Wfdownloads;

/**
 * Dbupdater class
 *
 * Class performing the database update for the module
 *
 * @package Wfdownloads
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://www.smartfactory.ca The SmartFactory
 */
class Dbupdater
{
    public function __construct()
    {
    }

    /**
     * Use to execute a general query
     *
     * @param string $query   query that will be executed
     * @param string $goodmsg message displayed on success
     * @param string $badmsg  message displayed on error
     *
     * @return bool true if success, false if an error occured
     */
    public function runQuery($query, $goodmsg, $badmsg)
    {
        $ret = $GLOBALS['xoopsDB']->query($query);
        if (!$ret) {
            echo "<li class='err'>$badmsg</li>";

            return false;
        }
        echo "<li class='ok'>$goodmsg</li>";

        return true;
    }

    /**
     * Use to rename a table
     *
     * @param string $from name of the table to rename
     * @param string $to   new name of the renamed table
     *
     * @return bool true if success, false if an error occured
     */
    public function renameTable($from, $to)
    {
        $from = $GLOBALS['xoopsDB']->prefix($from);
        $to   = $GLOBALS['xoopsDB']->prefix($to);

        $query = \sprintf('ALTER TABLE %s RENAME %s', $from, $to);
        $ret   = $GLOBALS['xoopsDB']->query($query);
        if (!$ret) {
            echo "<li class='err'>" . \sprintf(\_AM_WFDOWNLOADS_DB_MSG_RENAME_TABLE_ERR, $from) . '</li>';

            return false;
        }
        echo "<li class='ok'>" . \sprintf(\_AM_WFDOWNLOADS_DB_MSG_RENAME_TABLE, $from, $to) . '</li>';

        return true;
    }

    /**
     * Use to update a table
     *
     * @param DbupdaterTable $table {@link DbupdaterTable} that will be updated
     *
     * @return bool true if success, false if an error occured
     */
    public function updateTable(DbupdaterTable $table)
    {
        $ret = true;
        echo '<ul>';

        // If table has a structure, create the table
        if ($table->getStructure()) {
            $ret = $table->createTable() && $ret;
        }

        // If table is flag for drop, drop it
        if ($table->_flagForDrop) {
            $ret = $table->dropTable() && $ret;
        }

        // If table has data, insert it
        if ($table->getData()) {
            $ret = $table->addData() && $ret;
        }

        // If table has new fields to be added, add them
        if ($table->getNewFields()) {
            $ret = $table->addNewFields() && $ret;
        }

        // If table has altered field, alter the table
        if ($table->getAlteredFields()) {
            $ret = $table->alterTable() && $ret;
        }

        // If table has updated field values, update the table
        if ($table->getUpdatedFields()) {
            $ret = $table->updateFieldsValues($table) && $ret;
        }

        // If table has droped field, alter the table
        if ($table->getDropedFields()) {
            $ret = $table->dropFields($table) && $ret;
        }
        //felix
        // If table has updated field values, update the table
        if ($table->getUpdatedWhere()) {
            $ret = $table->updateWhereValues($table) && $ret;
        }

        echo '</ul>';

        return $ret;
    }
}
