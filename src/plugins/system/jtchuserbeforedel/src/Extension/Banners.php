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
 * Description.
 *
 * @since  __BUMP_VERSION__
 */
class Banners implements JtChUserBeforeDelInterface
{
    /**
     * Description.
     *
     * @return  string
     *
     * @since   __BUMP_VERSION__
     */
    public function getExtensionName()
    {
        return 'com_banners';
    }

    /**
     * Description.
     *
     * @return  array
     *
     * @since   __BUMP_VERSION__
     */
    public function getColumsToChange()
    {
        return array(
            array(
                'tableName' => '#__banners',
                'author'    => 'created_by',
                'alias'     => 'created_by_alias',
                'editor'    => 'modified_by',
            ),
        );
    }
}
