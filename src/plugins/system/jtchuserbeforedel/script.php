<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.Jtchuserbeforedel
 *
 * @author      Guido De Gobbis <support@joomtools.de>
 * @copyright   Copyright JoomTools.de - All rights reserved.
 * @license     GNU General Public License version 3 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\Installer;
use Joomla\CMS\Language\Text;

/**
 * Script file of Joomla CMS
 *
 * @since  __BUMP_VERSION__
 */
class PlgSystemjJtchuserbeforedelInstallerScript
{
    /**
     * Extension script constructor.
     *
     * @since  __BUMP_VERSION__
     */
    public function __construct()
    {
        // Define the minumum versions to be supported.
        $this->minimumJoomla = '3.10';
        $this->minimumPhp    = '7.4';
    }

    /**
     * Function to act prior to installation process begins
     *
     * @param   string     $action     Which action is happening (install|uninstall|discover_install|update)
     * @param   Installer  $installer  The class calling this method
     *
     * @return  boolean  True on success
     * @since   __BUMP_VERSION__
     */
    public function preflight($action, $installer)
    {
        $app = Factory::getApplication();
        Factory::getLanguage()->load('plg_system_jtchuserbeforedel', dirname(__FILE__));

        if (version_compare(PHP_VERSION, $this->minimumPhp, 'lt')) {
            $app->enqueueMessage(Text::sprintf('PLG_SYSTEM_JTCHUSERBEFOREDEL_MINPHPVERSION', $this->minimumPhp), 'error');

            return false;
        }

        if (version_compare(JVERSION, $this->minimumJoomla, 'lt')) {
            $app->enqueueMessage(Text::sprintf('PLG_SYSTEM_JTCHUSERBEFOREDEL_MINJVERSION', $this->minimumJoomla), 'error');

            return false;
        }

        return true;
    }
}
