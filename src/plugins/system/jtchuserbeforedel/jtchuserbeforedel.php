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

\JLoader::registerNamespace('JtChUserBeforeDel', JPATH_PLUGINS . '/system/jtchuserbeforedel/src', false, true, 'psr4');

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;
use JtChUserBeforeDel\JtChUserBeforeDelInterface;

/**
 * Class to replace the userid on component items on user deletion
 *
 * @since  __BUMP_VERSION__
 */
class PlgSystemJtchuserbeforedel extends CMSPlugin
{
    /**
     * Global application object
     *
     * @var    \JDatabaseDriver
     * @since  __BUMP_VERSION__
     */
    protected $db = null;

    /**
     * Global application object
     *
     * @var     CMSApplication
     * @since  __BUMP_VERSION__
     */
    protected $app = null;

    /**
     * Load the language file on instantiation.
     *
     * @var     boolean
     * @since  __BUMP_VERSION__
     */
    protected $autoloadLanguage = true;

    /**
     * Description
     *
     * @var    array
     * @since  __BUMP_VERSION__
     */
    private static $extensions = array();

    /**
     * Description
     *
     * @param   string  $context
     * @param   object  $item
     *
     * @return  void
     *
     * @since   __BUMP_VERSION__
     */
    public function onContentBeforeSave($context, $item)
    {
        // TODO: Check if needed, as 'onContentPrepareData' clears all on load!
        list($extensionName, $rest) = explode('.', $context, 2);

        $extension = $this->getExtension($extensionName);

        if ($extension instanceof JtChUserBeforeDelInterface) {
            $this->changeUserIdIfUserDoesNotExistAnymore($extension, $item);
        }
    }

    /**
     * Description
     *
     * @param   string  $context
     * @param   object  $item
     *
     * @return  void
     *
     * @since   __BUMP_VERSION__
     */
    public function onContentPrepareData($context, $item)
    {
        list($extensionName, $rest) = explode('.', $context, 2);

        $extension = $this->getExtension($extensionName);

        if ($extension instanceof JtChUserBeforeDelInterface) {
            $this->changeUserIdIfUserDoesNotExistAnymore($extension, $item);
        }
    }

    /**
     * Description
     *
     * @param   string  $context
     * @param   object  $item
     *
     * @return  void
     *
     * @since   __BUMP_VERSION__
     */
    public function onExtensionBeforeSave($context, $item)
    {
        list($extensionName, $rest) = explode('.', $context, 2);

        $extension = $this->getExtension($extensionName);

        if ($extension instanceof JtChUserBeforeDelInterface) {
            $this->changeUserIdIfUserDoesNotExistAnymore($extension, $item);
        }

        if ($context == 'com_plugins.plugin' && $item->name == 'plg_system_jtchuserbeforedel') {
            $newParams               = new Registry($item->params);
            $userIdToChangeManualy   = $newParams->get('userIdToChangeManualy', '');
            $userNameToChangeManualy = $newParams->get('userNameToChangeManualy', '');

            // Reset the fields
            $newParams->set('userIdToChangeManualy', '');
            $newParams->set('userNameToChangeManualy', '');

            $item->params = (string) $newParams;

            if (empty($userIdToChangeManualy)) {
                return;
            }

            if ($this->isUserExists($userIdToChangeManualy)) {
                $this->app->enqueueMessage(
                    Text::sprintf(
                        'PLG_SYSTEM_JTCHUSERBEFOREDEL_USER_ID_TO_CHANGE_MANUALY_EXISTS',
                        $userIdToChangeManualy
                    ),
                    'error'
                );

                return;
            }

            if (!empty($userIdToChangeManualy)) {
                $this->params = $newParams;
                $user         = array(
                    'id'   => $userIdToChangeManualy,
                    'name' => $userNameToChangeManualy,
                );

                $this->changeUser($user);
            }
        }
    }

    /**
     * Description
     *
     * @param   object  $extension
     * @param   object  $item
     *
     * @return  void
     *
     * @since   __BUMP_VERSION__
     */
    private function changeUserIdIfUserDoesNotExistAnymore($extension, $item)
    {
        $fallbackUserId    = $this->params->get('fallbackUser');
        $fallbackAliasName = $this->params->get('fallbackAliasName', '');

        if (empty($fallbackUserId) || !is_numeric($fallbackUserId)) {
            $fallbackUserId = Factory::getUser()->id;
        }

        foreach ($extension->getColumsToChange() as $table) {
            if (is_array($table) && count($table) > 1) {
                $authorExists = true;
                $authorTable  = $table['author'] ?? false;
                $aliasTable   = $table['alias'] ?? false;

                if ($authorTable && isset($item->$authorTable)) {
                    $authorExists = $this->isUserExists($item->$authorTable);
                }

                if (!$authorExists) {
                    $this->app->enqueueMessage(
                        Text::sprintf(
                            'PLG_SYSTEM_JTCHUSERBEFOREDEL_AUTHOR_CHANGED_MSG',
                            $item->$authorTable,
                            $fallbackUserId
                        ),
                        'info'
                    );

                    $item->$authorTable = $fallbackUserId;

                    if ($aliasTable && isset($item->$aliasTable) && $this->params->get('setAlias')) {
                        if ((!$this->params->get('overrideAlias') && !empty($item->$aliasTable))
                            || (empty($item->$aliasTable) && empty($fallbackAliasName))
                        ) {
                            continue;
                        }

                        $item->$aliasTable = $fallbackAliasName;

                        $this->app->enqueueMessage(
                            Text::sprintf(
                                'PLG_SYSTEM_JTCHUSERBEFOREDEL_USER_CHANGED_FALLBACK_ALIAS_MSG',
                                $fallbackAliasName
                            ),
                            'info'
                        );
                    }
                }
            }
        }
    }

    /**
     * Description
     *
     * @param   int  $userId
     *
     * @return  bool
     *
     * @since   __BUMP_VERSION__
     */
    private function isUserExists($userId)
    {
        $userTable = Table::getInstance('user');

        return $userTable->load((int) $userId) === true;
    }

    /**
     * Description
     *
     * @param   array  $user
     *
     * @return  void
     *
     * @since   __BUMP_VERSION__
     */
    public function onUserBeforeDelete($user)
    {
        $fallbackUser = $this->params->get('fallbackUser');

        if ($user['id'] == $fallbackUser) {
            $this->app->enqueueMessage(
                'Der Benutzer wurde als Fallback eingestellt und kann deshalb nicht gelöscht werden,<br /> bitte vorher ändern!',
                'error'
            );

            $url = Uri::getInstance()->toString(array('path', 'query', 'fragment'));
            $this->app->redirect($url, 500);
        }

        if (!$this->changeUser($user)) {
            $this->app->enqueueMessage(
                'Der Benutzer wurde nicht gelöscht!',
                'error'
            );

            $url = Uri::getInstance()->toString(array('path', 'query', 'fragment'));
            $this->app->redirect($url, 500);
        }
    }

    /**
     * Description
     *
     * @param   array  $user
     *
     * @return  void
     *
     * @since   __BUMP_VERSION__
     */
    private function changeUser($user)
    {
        $return         = true;
        $userId         = $user['id'];
        $aliasName      = $user['name'];
        $fallbackUserId = $this->params->get('fallbackUser');
        $setAuthorAlias = $this->params->get('setAlias');

        if (empty($fallbackUserId) || !is_numeric($fallbackUserId)) {
            $fallbackUserId = Factory::getUser()->id;
        }

        if (empty($extensions = $this->getExtension())) {
            // TODO: Add error handling and/or message and return false
            return true;
        }

        foreach ($extensions as $extensionName => $extensionClass) {
            /** @var JtChUserBeforeDelInterface $extensionClass */
            $columsToChangeUserId = $extensionClass->getColumsToChange();

            foreach ($columsToChangeUserId as $table) {
                $tableName    = $table['tableName'] ?? false;
                $uniqueId     = $table['uniqueId'] ?? false;
                $authorColumn = $table['author'] ?? false;
                $aliasColumn  = $table['alias'] ?? false;

                if ($tableName && $authorColumn) {
                    $selectQuery = $this->db->getQuery(true);

                    $selectQuery->select($this->db->quoteName($uniqueId))
                        ->from($tableName)
                        ->where($this->db->quoteName($authorColumn) . ' = ' . $this->db->quote((int) $userId))
                        ->set('FOR UPDATE');

                    $updateQuery = $this->db->getQuery(true);

                    $updateQuery->update($this->db->quoteName($tableName))
                        ->set($this->db->quoteName($authorColumn) . ' = ' . $this->db->quote((int) $fallbackUserId));

                    if ($setAuthorAlias && $aliasColumn) {
                        if ($this->params->get('overrideAlias')) {
                            $updateQuery->set($this->db->quoteName($aliasColumn) . ' = ' . $this->db->quote($aliasName));
                        } else {
                            $updateQuery->set(
                                $this->db->quoteName($aliasColumn)
                                . ' = COALESCE(NULLIF('
                                . $this->db->quote($aliasName)
                                . ', ""), '
                                . $this->db->quoteName($aliasColumn) . ')'
                            );
                        }
                    }

                    $updateQuery->where($this->db->quoteName($authorColumn) . ' = ' . $this->db->quote((int) $userId));
                }

                $test = (string) $updateQuery;

                try {
                    $infoAuthorAlias = '';
                    $selectResult    = $this->db->setQuery($selectQuery)->loadColumn();

                    if (!empty($selectResult)) {
                        $elementList = implode(', ', $selectResult);

                        if ($setAuthorAlias && $aliasColumn) {
                            $infoAuthorAlias = sprintf('Es wurde jeweils der Autoralias "%s" eingetragen.', $aliasName);
                        }

                        Factory::getLanguage()->load($extensionName);

                        $this->db->setQuery($updateQuery)->execute();
                        $this->app->enqueueMessage(
                            Text::sprintf(
                                '%s: bei den Elementen mit der ID "%s" wurde die alte Benutzer ID "%d" mit der ID "%d" ausgetauscht. %s',
                                Text::_($extensionName),
                                $elementList,
                                (int) $userId,
                                (int) $fallbackUserId,
                                $infoAuthorAlias
                            ),
                            'info'
                        );
                    }
                } catch (RuntimeException $e) {
                    $this->app->enqueueMessage(
                        $e->getMessage(),
                        'error'
                    );

                    $return = false;
                }
            }
        }

        return $return;
    }

    /**
     * Description
     *
     * @return  void
     *
     * @since   __BUMP_VERSION__
     */
    private function initExtensions()
    {
        $ns      = \JLoader::getNamespaces('psr4');
        $nsPaths = (array) $ns['JtChUserBeforeDel'];

        foreach ($nsPaths as $nsPath) {
            $extensions = Folder::files($nsPath . '/Extension');

            foreach ($extensions as $extension) {
                /** @var JtChUserBeforeDelInterface $extension */
                $extension = $this->getExtensionClass($extension);

                if ($extension instanceof JtChUserBeforeDelInterface) {
                    self::$extensions[$extension->getExtensionName()] = $extension;
                }
            }
        }
    }

    /**
     * Description
     *
     * @param   string  $extensionPath
     *
     * @return  JtChUserBeforeDelInterface|null
     *
     * @since   __BUMP_VERSION__
     */
    private function getExtensionClass($extensionPath)
    {
        $error         = false;
        $extensionName = File::stripExt($extensionPath);

        $extensionNs = 'JtChUserBeforeDel\\Extension\\' . ucfirst($extensionName);

        try {
            $extensionClass = new $extensionNs;
        } catch (\Throwable $e) {
            $error = true;
        } catch (\Exception $e) {
            $error = true;
        }

        if ($error) {
            // TODO: Change Message to Languagefile.
            $this->app->enqueueMessage(
                sprintf("The class '%s' to call for handle the download could not be found.", $extensionNs),
                'error'
            );

            return;
        }

        return $extensionClass;
    }

    /**
     * Description
     *
     * @param   string  $extName
     *
     * @return  JtChUserBeforeDelInterface|array
     *
     * @since   __BUMP_VERSION__
     */
    private function getExtension($extName = null)
    {
        if (empty(self::$extensions)) {
            $this->initExtensions();
        }

        if (is_null($extName)) {
            return self::$extensions;
        }

        return self::$extensions[$extName] ?? array();
    }
}
