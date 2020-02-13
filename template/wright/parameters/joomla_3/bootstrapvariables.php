<?php
/**
 * @package     Wright
 * @subpackage  Parameters
 *
 * @copyright   Copyright (C) 2005 - 2020 redCOMPONENT.com.  All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

JHtml::_('jquery.framework');
JHtml::_('script', 'system/html5fallback.js', false, true);
JHtml::_('behavior.colorpicker');

/**
 * Bootstrap variables
 *
 * @package     Wright
 * @subpackage  Parameters
 * @since       3.0
 */
class JFormFieldBootstrapVariables extends JFormField
{
	/**
	 * Field type
	 *
	 * @var  string
	 */
	public $type = 'BootstrapVariables';

	/**
	 * Variables
	 *
	 * @var  array
	 */
	private $variables = array();

	/**
	 * Creates color picker
	 *
	 * @return  JFormField  Formatted input
	 */
	protected function getInput()
	{
		$input = JFactory::getApplication()->input;
		$templateId = $input->getInt('id', 0);

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('template'))
			->from($db->qn('#__template_styles'))
			->where($db->qn('id') . ' = ' . (int) $templateId);

		$templateName = $db->setQuery($query)->loadResult();

		$variablesPath = JPATH_SITE . '/templates/' . $templateName . '/scss/variables.scss';

		if (file_exists($variablesPath))
		{
			$variablesContent = JFile::read($variablesPath);

			preg_match_all("/\/\/==(.*)/", $variablesContent, $match);

			if (count($match[1]) > 0)
			{
				$titles = $match[1];

				for ($i = 0; $i < count($titles); $i++)
				{
					if (!isset($titles[$i + 1]))
					{
						preg_match_all("/\/\/==(" . $titles[$i] . ")([\w\W]*)\/\/==/", $variablesContent, $pats);
					}
					else
					{
						preg_match_all("/\/\/==(" . $titles[$i] . ")([\w\W]*)\/\/==" . $titles[$i + 1] . "/", $variablesContent, $pats);
					}

					if (count($pats[2]) > 0 && !empty($pats[1][0]))
					{
						$groupName = str_replace("=", "", $pats[1][0]);
						$groupName = trim($groupName);

						$variables = $pats[2][0];

						preg_match_all("/[\$](.*)\:(.*)!/", $variables, $pats);

						if (count($pats[1]) > 0)
						{
							foreach ($pats[1] as $key => $value)
							{
								$v = trim($pats[2][$key]);
								$v = htmlspecialchars($v);

								$this->variables[$groupName][$value] = trim($v);
							}
						}
					}
				}
			}
		}

		$tabs = array();
		$contents = array();
		$class = 'active';

		ksort($this->variables);

		foreach ($this->variables as $group => $set)
		{
			// Tab
			$tabs[] = '<li class="' . $class . '">
				<a href="#tab' . JFilterOutput::stringURLSafe($group) . '" data-toggle="tab">' . $group . '</a>
			</li>';

			// Content
			$content = '<div class="tab-pane ' . $class . '" id="tab' . JFilterOutput::stringURLSafe($group) . '"><div class="row-fluid">';

			$i = 0;

			foreach ($set as $key => $value)
			{
				if ($i > 0 && $i % 4 == 0)
				{
					$content .= '</div><div class="row-fluid">';
				}

				$name = $this->name . '[' . $key . ']';

				$content .= '<div class="span3">
					<div class="control-label">
						<label for="input-' . $key . '">' . $key . '</label>
					</div>
					<div class="controls">
						<input type="text" name="' . $name . '" class="input" value="' . $value . '" id="wb_bootstrapvariables-input-' . $key . '" placeholder="' . $value . '">
					</div>
				</div>';

				$i++;
			}

			$content .= '</div></div>';
			$class = '';
			$contents[] = $content;
		}

		$tabs = '<ul class="nav nav-pills" id="bootstrapvariablesTab">' . implode('', $tabs) . '</ul>';
		$contents = '<div class="tab-content">' . implode('', $contents) . '</div>';

		$html = '<div class="container-fluid bootstrapvariables form-vertical">' . $tabs . $contents . '</div>';

		return $html;
	}
}
