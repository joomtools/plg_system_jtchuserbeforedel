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
class Tags implements JtChUserBeforeDelInterface
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
        return 'com_tags';
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
                'tableName' => '#__tags',
                'author'    => 'created_user_id',
                'alias'     => 'created_by_alias',
                'editor'    => 'modified_user_id',
                'date'      => 'modified_time',
            ),
        );
    }
}
