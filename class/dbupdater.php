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

/**
 * WfdownloadsTable class
 *
 * Information about an individual table
 *
 * @package Wfdownloads
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://www.smartfactory.ca The SmartFactory
 */
defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

/**
 * Class WfdownloadsTable
 */
class WfdownloadsTable
{
    /**
     * @var string $_name name of the table
     */
    public $_name;

    /**
     * @var string $_structure structure of the table
     */
    public $_structure;

    /**
     * @var array $_data containing valued of each records to be added
     */
    public $_data;

    /**
     * @var array $_alteredFields containing fields to be altered
     */
    public $_alteredFields;

    /**
     * @var array $_newFields containing new fields to be added
     */
    public $_newFields;

    /**
     * @var array $_dropedFields containing fields to be droped
     */
    public $_dropedFields;

    /**
     * @var array $_flagForDrop flag table to drop it
     */
    public $_flagForDrop = false;

    /**
     * @var array $_updatedFields containing fields which values will be updated
     */
    public $_updatedFields;

    /**
     * @var array $_updatedFields containing fields which values will be updated
     */ //felix
    public $_updatedWhere;

    /**
     * Constructor
     *
     * @param string $name name of the table
     *
     */
    public function __construct($name)
    {
        $this->_name = $name;
        $this->_data = array();
    }

    /**
     * Return the table name, prefixed with site table prefix
     *
     * @return string table name
     *
     */
    public function name()
    {
        return $GLOBALS['xoopsDB']->prefix($this->_name);
    }

    /**
     * Set the table structure
     *
     * @param string $structure table structure
     *
     */
    public function setStructure($structure)
    {
        $this->_structure = $structure;
    }

    /**
     * Return the table structure
     *
     * @return string table structure
     *
     */
    public function getStructure()
    {
        return sprintf($this->_structure, $this->name());
    }

    /**
     * Add values of a record to be added
     *
     * @param string $data values of a record
     *
     */
    public function setData($data)
    {
        $this->_data[] = $data;
    }

    /**
     * Get the data array
     *
     * @return array containing the records values to be added
     *
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * Use to insert data in a table
     *
     * @return bool true if success, false if an error occured
     *
     */
    public function addData()
    {
        $ret = null;
        foreach ($this->getData() as $data) {
            $query = sprintf('INSERT INTO %s VALUES (%s)', $this->name(), $data);
            $ret   = $GLOBALS['xoopsDB']->query($query);
            if (!$ret) {
                echo "<li class='err'>" . sprintf(_AM_WFDOWNLOADS_DB_MSG_ADD_DATA_ERR, $this->name()) . '</li>';
            } else {
                echo "<li class='ok'>" . sprintf(_AM_WFDOWNLOADS_DB_MSG_ADD_DATA, $this->name()) . '</li>';
            }
        }

        return $ret;
    }

    /**
     * Add a field to be added
     *
     * @param string $name       name of the field
     * @param string $properties properties of the field
     *
     */
    public function addAlteredField($name, $properties)
    {
        $field                  = array();
        $field['name']          = $name;
        $field['properties']    = $properties;
        $this->_alteredFields[] = $field;
    }

    /**
     * Invert values 0 to 1 and 1 to 0
     *
     * @param string $name name of the field
     * @param        $newValue
     * @param        $oldValue
     *
     */ //felix
    public function addUpdatedWhere($name, $newValue, $oldValue)
    {
        $field                 = array();
        $field['name']         = $name;
        $field['value']        = $newValue;
        $field['where']        = $oldValue;
        $this->_updatedWhere[] = $field;
    }

    /**
     * Add new field of a record to be added
     *
     * @param string $name       name of the field
     * @param string $properties properties of the field
     *
     */
    public function addNewField($name, $properties)
    {
        $field               = array();
        $field['name']       = $name;
        $field['properties'] = $properties;
        $this->_newFields[]  = $field;
    }

    /**
     * Get fields that need to be altered
     *
     * @return array fields that need to be altered
     *
     */
    public function getAlteredFields()
    {
        return $this->_alteredFields;
    }

    /**
     * Add field for which the value will be updated
     *
     * @param string $name  name of the field
     * @param string $value value to be set
     *
     */
    public function addUpdatedField($name, $value)
    {
        $field                  = array();
        $field['name']          = $name;
        $field['value']         = $value;
        $this->_updatedFields[] = $field;
    }

    /**
     * Get new fields to be added
     *
     * @return array fields to be added
     *
     */
    public function getNewFields()
    {
        return $this->_newFields;
    }

    /**
     * Get fields which values need to be updated
     *
     * @return array fields which values need to be updated
     *
     */
    public function getUpdatedFields()
    {
        return $this->_updatedFields;
    }

    /**
     * Get fields which values need to be updated
     *
     * @return array fields which values need to be updated
     *
     */ //felix
    public function getUpdatedWhere()
    {
        return $this->_updatedWhere;
    }

    /**
     * Add values of a record to be added
     *
     * @param string $name name of the field
     *
     */
    public function addDropedField($name)
    {
        $this->_dropedFields[] = $name;
    }

    /**
     * Get fields that need to be droped
     *
     * @return array fields that need to be droped
     *
     */
    public function getDropedFields()
    {
        return $this->_dropedFields;
    }

    /**
     * Set the flag to drop the table
     *
     */
    public function setFlagForDrop()
    {
        $this->_flagForDrop = true;
    }

    /**
     * Use to create a table
     *
     * @return bool true if success, false if an error occured
     *
     */
    public function createTable()
    {
        $query = $this->getStructure();

        $ret = $GLOBALS['xoopsDB']->query($query);
        if (!$ret) {
            echo "<li class='err'>" . sprintf(_AM_WFDOWNLOADS_DB_MSG_CREATE_TABLE_ERR, $this->name()) . '</li>';
        } else {
            echo "<li class='ok'>" . sprintf(_AM_WFDOWNLOADS_DB_MSG_CREATE_TABLE, $this->name()) . '</li>';
        }

        return $ret;
    }

    /**
     * Use to drop a table
     *
     * @return bool true if success, false if an error occured
     *
     */
    public function dropTable()
    {
        $query = sprintf('DROP TABLE %s', $this->name());
        $ret   = $GLOBALS['xoopsDB']->query($query);
        if (!$ret) {
            echo "<li class='err'>" . sprintf(_AM_WFDOWNLOADS_DB_MSG_DROP_TABLE_ERR, $this->name()) . '</li>';

            return false;
        } else {
            echo "<li class='ok'>" . sprintf(_AM_WFDOWNLOADS_DB_MSG_DROP_TABLE, $this->name()) . '</li>';

            return true;
        }
    }

    /**
     * Use to alter a table
     *
     * @return bool true if success, false if an error occured
     *
     */
    public function alterTable()
    {
        $ret = true;

        foreach ($this->getAlteredFields() as $alteredField) {
            $query = sprintf('ALTER TABLE `%s` CHANGE `%s` %s', $this->name(), $alteredField['name'], $alteredField['properties']);
            //echo $query;
            $ret = $ret && $GLOBALS['xoopsDB']->query($query);
            if (!$ret) {
                echo "<li class='err'>" . sprintf(_AM_WFDOWNLOADS_DB_MSG_CHGFIELD_ERR, $alteredField['name'], $this->name()) . '</li>';
            } else {
                echo "<li class='ok'>" . sprintf(_AM_WFDOWNLOADS_DB_MSG_CHGFIELD, $alteredField['name'], $this->name()) . '</li>';
            }
        }

        return $ret;
    }

    /**
     * Use to add new fileds in the table
     *
     * @return bool true if success, false if an error occured
     *
     */
    public function addNewFields()
    {
        $ret = true;
        foreach ($this->getNewFields() as $newField) {
            $query = sprintf('ALTER TABLE `%s` ADD `%s` %s', $this->name(), $newField['name'], $newField['properties']);
            //echo $query;
            $ret = $ret && $GLOBALS['xoopsDB']->query($query);
            if (!$ret) {
                echo "<li class='err'>" . sprintf(_AM_WFDOWNLOADS_DB_MSG_NEWFIELD_ERR, $newField['name'], $this->name()) . '</li>';
            } else {
                echo "<li class='ok'>" . sprintf(_AM_WFDOWNLOADS_DB_MSG_NEWFIELD, $newField['name'], $this->name()) . '</li>';
            }
        }

        return $ret;
    }

    /**
     * Use to update fields values
     *
     * @return bool true if success, false if an error occured
     *
     */
    public function updateFieldsValues()
    {
        $ret = true;

        foreach ($this->getUpdatedFields() as $updatedField) {
            $query = sprintf('UPDATE %s SET %s = %s', $this->name(), $updatedField['name'], $updatedField['value']);
            $ret   = $ret && $GLOBALS['xoopsDB']->query($query);
            if (!$ret) {
                echo "<li class='err'>" . sprintf(_AM_WFDOWNLOADS_DB_MSG_UPDATE_TABLE_ERR, $this->name()) . '</li>';
            } else {
                echo "<li class='ok'>" . sprintf(_AM_WFDOWNLOADS_DB_MSG_UPDATE_TABLE, $this->name()) . '</li>';
            }
        }

        return $ret;
    }

    /**
     * Use to update fields values
     *
     * @return bool true if success, false if an error occured
     *
     */ //felix
    public function updateWhereValues()
    {
        $ret = true;

        foreach ($this->getUpdatedWhere() as $updatedWhere) {
            $query = sprintf('UPDATE %s SET %s = %s WHERE %s  %s', $this->name(), $updatedWhere['name'], $updatedWhere['value'], $updatedWhere['name'], $updatedWhere['where']);
            //echo $query."<br>";
            $ret = $ret && $GLOBALS['xoopsDB']->query($query);
            if (!$ret) {
                echo "<li class='err'>" . sprintf(_AM_WFDOWNLOADS_DB_MSG_UPDATE_TABLE_ERR, $this->name()) . '</li>';
            } else {
                echo "<li class='ok'>" . sprintf(_AM_WFDOWNLOADS_DB_MSG_UPDATE_TABLE, $this->name()) . '</li>';
            }
        }

        return $ret;
    }

    /**
     * Use to drop fields
     *
     * @return bool true if success, false if an error occured
     *
     */
    public function dropFields()
    {
        $ret = true;

        foreach ($this->getDropedFields() as $dropedField) {
            $query = sprintf('ALTER TABLE %s DROP %s', $this->name(), $dropedField);

            $ret = $ret && $GLOBALS['xoopsDB']->query($query);
            if (!$ret) {
                echo "<li class='err'>" . sprintf(_AM_WFDOWNLOADS_DB_MSG_DROPFIELD_ERR, $dropedField, $this->name()) . '</li>';
            } else {
                echo "<li class='ok'>" . sprintf(_AM_WFDOWNLOADS_DB_MSG_DROPFIELD, $dropedField, $this->name()) . '</li>';
            }
        }

        return $ret;
    }
}

/**
 * WfdownloadsDbupdater class
 *
 * Class performing the database update for the module
 *
 * @package Wfdownloads
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://www.smartfactory.ca The SmartFactory
 */
class WfdownloadsDbupdater
{
    /**
     *
     */
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
     *
     */
    public function runQuery($query, $goodmsg, $badmsg)
    {
        $ret = $GLOBALS['xoopsDB']->query($query);
        if (!$ret) {
            echo "<li class='err'>$badmsg</li>";

            return false;
        } else {
            echo "<li class='ok'>$goodmsg</li>";

            return true;
        }
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

        $query = sprintf('ALTER TABLE %s RENAME %s', $from, $to);
        $ret   = $GLOBALS['xoopsDB']->query($query);
        if (!$ret) {
            echo "<li class='err'>" . sprintf(_AM_WFDOWNLOADS_DB_MSG_RENAME_TABLE_ERR, $from) . '</li>';

            return false;
        } else {
            echo "<li class='ok'>" . sprintf(_AM_WFDOWNLOADS_DB_MSG_RENAME_TABLE, $from, $to) . '</li>';

            return true;
        }
    }

    /**
     * Use to update a table
     *
     * @param XoopsObject $table {@link WfdownloadsTable} that will be updated
     *
     * @see WfdownloadsTable
     *
     * @return bool true if success, false if an error occured
     */
    public function updateTable($table)
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
            $ret = $table->UpdateWhereValues($table) && $ret;
        }

        echo '</ul>';

        return $ret;
    }
}
