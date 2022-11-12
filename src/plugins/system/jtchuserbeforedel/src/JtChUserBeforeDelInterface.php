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
 * @since  1.0.0
 */
interface JtChUserBeforeDelInterface
{
    /**
     * The extensions real name language string.
     *
     * @return  string
     *
     * @since   1.0.0
     */
    public function getExtensionRealNameLanguageString();

    /**
     * The extensions first/base part of the context.
     *
     * @return  string
     *
     * @since   1.0.0
     */
    public function getExtensionBaseContext();

    /**
     * The database table and columns about the user information to change.
     *
     * Expected array keys, if there is a table and column to change:
     * Example:
     * array(
     *      'tableName' => '#__content',
     *      'uniqueId'  => 'id',
     *      'author'    => 'created_by',
     *      'alias'     => 'created_by_alias',
     * )
     *
     * @return  array
     *
     * @since   1.0.0
     */
    public function getColumsToChange();
}
