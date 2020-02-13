<?php
/**
 * @package     Wright
 * @subpackage  Adapters
 *
 * @copyright   Copyright (C) 2005 - 2020 redCOMPONENT.com.  All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Wright Adapters
 *
 * @package     Wright
 * @subpackage  Adapters
 * @since       3.0
 */
class WrightAdapterJoomlaContent
{
	/**
	 * Render function
	 *
	 * @param   array  $args  Array
	 *
	 * @return  string
	 */
	public function render($args)
	{
		$content = '<jdoc:include type="message" />';

		$content .= '<jdoc:include type="component" />';

		return $content;
	}
}
