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
 * Class to support the core extension 'com_content'.
 *
 * @since  1.0.1
 */
class Workflow implements JtChUserBeforeDelInterface
{
    /**
     * The extensions real name language string.
     *
     * @return  string
     *
     * @since   1.0.1
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
     * @since   1.0.1
     */
    public function getExtensionBaseContext()
    {
        return 'com_workflow';
    }

    /**
     * The database table and columns about the user information to change.
     *
     * @return  array
     *
     * @since   1.0.1
     * @see     JtChUserBeforeDelInterface
     */
    public function getColumsToChange()
    {
        return array(
            array(
                'tableName' => '#__workflows',
                'uniqueId'  => 'id',
                'author'    => 'created_by',
            ),
        );
    }
}
