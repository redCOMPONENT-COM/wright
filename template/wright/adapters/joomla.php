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
class WrightAdapterJoomla
{
	/**
	 * Version
	 *
	 * @var  array
	 */
	protected $version;

	/**
	 * Construction
	 *
	 */
	public function __construct($version)
	{
		$this->version = $version;
	}

	/**
	 * Handles the tag processing
	 *
	 * @param   array  $config  Array
	 *
	 * @return class
	 */
	public function get($config)
	{
		$tag = key($config);
		$file = dirname(__FILE__) . '/joomla/' . $tag . '.php';
		$class = 'WrightAdapterJoomla' . ucfirst($tag);

		require_once $file;

		$item = new $class;

		return $item->render($config[$tag]);
	}

	/**
	 * Get version
	 *
	 * @return string
	 */
	public function getVersion()
	{
		return $this->version;
	}
}
