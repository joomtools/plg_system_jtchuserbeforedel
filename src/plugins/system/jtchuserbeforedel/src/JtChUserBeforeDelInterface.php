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
     * Description.
     *
     * @return  string
     *
     * @since   __BUMP_VERSION__
     */
    public function getExtensionName();

    /**
     * Description.
     *
     * @return  array
     *
     * @since   __BUMP_VERSION__
     */
    public function getColumsToChange();
}
