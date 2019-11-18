<?php
/**
 * @package    Template.Function
 *
 * @copyright  Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Include the framework
require dirname(__FILE__) . '/wright/wright.php';

// Initialize the framework and
$tpl = Wright::getInstance();

// Bootstrap JS
$tpl->addJSScript(JURI::root() . 'templates/' . $this->template . '/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js');

$tpl->addJSScript(JURI::root() . 'templates/' . $this->template . '/src/js/app.js');
$tpl->display();
