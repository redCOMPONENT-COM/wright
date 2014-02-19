<?php
/**
 * @package    Template.Function
 *
 * @copyright  Copyright (C) 2005 - 2014 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 * Use this file to add any PHP to the template before it is executed
 */

// Restrict Access to within Joomla
defined('_JEXEC') or die('Restricted access');

$bodyclass = "";

if ($this->countModules('toolbar'))
{
	$bodyclass = "toolbarpadding";
}
