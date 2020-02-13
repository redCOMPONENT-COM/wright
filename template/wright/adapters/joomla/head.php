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
class WrightAdapterJoomlaHead
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
		$doc = JFactory::getDocument();
		
		// Add viewport meta for tablets
		$doc->setMetaData('viewport', 'width=device-width, initial-scale=1, shrink-to-fit=no');

		$doc->setMetaData('X-UA-Compatible', 'IE=edge', true);

		// Add favicon links
		$faviconsurl = JURI::root() . 'templates/' . $doc->template . '/favicons/';

		$doc->addFavicon($faviconsurl . 'favicon.ico');

		$doc->addHeadLink($faviconsurl . 'apple-touch-icon.png', 'apple-touch-icon', 'rel', array('sizes' => '180x180'));

		$doc->addHeadLink($faviconsurl . 'favicon-32x32.png', 'icon', 'rel', array('sizes' => '32x32', 'type' => 'image/png'));

		$doc->addHeadLink($faviconsurl . 'favicon-16x16.png', 'icon', 'rel', array('sizes' => '16x16', 'type' => 'image/png'));

		$doc->addHeadLink($faviconsurl . 'manifest.json', 'manifest', 'rel');

		$doc->addHeadLink($faviconsurl . 'safari-pinned-tab.svg', 'mask-icon', 'rel', array('color' => '#5bbad5'));

		$doc->setMetaData('msapplication-config', $faviconsurl . 'browserconfig.xml');

		$doc->setMetaData('theme-color', $doc->params->get('theme_color', '#ffffff'));

		$head = '<jdoc:include type="head" />';
		$head .= "\n";

		$wr = Wright::getInstance();

		$head .= $wr->generateJS();
		$head .= $wr->generateCSS();

		return $head;
	}
}
