<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.Jtchuserbeforedel
 *
 * @author      Guido De Gobbis <support@joomtools.de>
 * @copyright   Copyright JoomTools.de - All rights reserved.
 * @license     GNU General Public License version 3 or later
 */

namespace JtChUserBeforeDel\Extension;

defined('_JEXEC') or die;

use JtChUserBeforeDel\JtChUserBeforeDelInterface;

/**
 * Class to support the core extension 'com_fields'.
 *
 * @since  1.0.0
 */
class Fields implements JtChUserBeforeDelInterface
{
    /**
     * The extensions real name language string.
     *
     * @return  string
     *
     * @since   1.0.0
     */
    public function getExtensionRealNameLanguageString()
    {
        return $this->getExtensionBaseContext();
    }

    /**
     * The extensions first/base part of the context.
     *
     * @return  string
     *
     * @since   1.0.0
     */
    public function getExtensionBaseContext()
    {
        return 'com_fields';
    }

    /**
     * The database table and columns about the user information to change.
     *
     * @return  array
     *
     * @since   1.0.0
     * @see     JtChUserBeforeDelInterface
     */
    public function getColumsToChange()
    {
        return array(
            array(
                'tableName' => '#__fields',
                'uniqueId'  => 'id',
                'author'    => 'created_user_id',
            ),
            array(
                'tableName' => '#__fields_groups',
                'uniqueId'  => 'id',
                'author'    => 'created_by',
            ),
        );
    }
}
