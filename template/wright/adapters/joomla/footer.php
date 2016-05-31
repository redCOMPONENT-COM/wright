<?php
/**
 * @package     Wright
 * @subpackage  Adapters
 *
 * @copyright   Copyright (C) 2005 - 2016 redCOMPONENT.com.  All rights reserved.
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
class WrightAdapterJoomlaFooter
{
	/**
	 * Renders the footer
	 *
	 * @param   Array  $args  Args sent
	 *
	 * @return  string
	 */
	public function render($args)
	{
		$doc = Wright::getInstance();

		if ($doc->document->params->get('rebrand', 'no') !== 'yes')
		{
			return '<a target="_blank" class="joomlashack" href="http://www.joomlashack.com"><img src="./templates/' . JFactory::getApplication()->getTemplate() . '/wright/images/jscright.png" alt ="Joomlashack" /> </a>';
		}
	}
}
