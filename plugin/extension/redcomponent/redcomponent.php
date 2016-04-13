<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.Redcore
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('JPATH_BASE') or die;

/**
 * System plugin for redCORE
 *
 * @package     Joomla.Plugin
 * @subpackage  System
 * @since       1.0
 */
class PlgExtensionRedcomponent extends JPlugin
{
	/**
	 * Example after save content method
	 * Article is passed by reference, but after the save, so no changes will be saved.
	 * Method is called right after the content is saved
	 *
	 * @param   string   $context  The context of the content passed to the plugin (added in 1.6)
	 * @param   object   $table    A JTableContent object
	 * @param   boolean  $isNew    If the content is just about to be created
	 *
	 * @return  boolean   true if function not enabled, is in front-end or is new. Else true or
	 *                    false depending on success of save function.
	 *
	 * @since   1.6
	 */
	public function onExtensionBeforeSave($context, $table, $isNew)
	{
		if ($context != 'com_templates.style')
		{
			return true;
		}

		$params = new JRegistry($table->params);
		$params = $params->toArray();

		if (count($params['bootstrapvariables']) > 0)
		{
			$bootstrapvariables = $params['bootstrapvariables'];

			$variablesPath = JPATH_SITE . '/templates/' . $table->template . '/scss/variables.scss';

			if (file_exists($variablesPath))
			{
				$variablesContent = JFile::read($variablesPath);

				foreach ($bootstrapvariables as $key => $value)
				{
					$pattern = "/[\$]" . $key . "\:(.*)!/";
					$replacement = '$' . $key . ': ' . $value . ' !';
					$variablesContent = preg_replace($pattern, $replacement, $variablesContent);
				}

				JFile::write($variablesPath, $variablesContent);
			}
		}

		return true;
	}
}
