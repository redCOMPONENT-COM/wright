<?php
/**
 * @package    Template.Function
 *
 * @copyright  Copyright (C) 2005 - 2014 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Include the framework
require dirname(__FILE__) . '/wright/wright.php';

// Initialize the framework and
$tpl = Wright::getInstance();
$tpl->addJSScript(JURI::root() . 'templates/' . $this->template . '/js/js.js');
$tpl->display();
