<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.Jtchuserbeforedel
 *
 * @author      Guido De Gobbis <support@joomtools.de>
 * @copyright   Copyright JoomTools.de - All rights reserved.
 * @license     GNU General Public License version 3 or later
 */

namespace JtChUserBeforeDel;

defined('_JEXEC') or die;

/**
 * Description.
 *
 * @since  __BUMP_VERSION__
 */
interface JtChUserBeforeDelInterface
{
    /**
     * Get the extension name like the first part of the context.
     *
     * @return  string
     *
     * @since   __BUMP_VERSION__
     */
    public function getExtensionName();

    /**
     * Get the database table and columns about the user information to change.
     *
     * Expected array keys, if there is a table and column to change:
     * Example:
     * array(
     *      'tableName' => '#__content',
     *      'uniqueId'  => 'id',
     *      'author'    => 'created_by',
     *      'alias'     => 'created_by_alias',
     *      'editor'    => 'modified_by',
     * )
     *
     * @return  array
     *
     * @since   __BUMP_VERSION__
     */
    public function getColumsToChange();
}
