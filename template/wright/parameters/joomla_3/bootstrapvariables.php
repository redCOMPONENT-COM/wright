<?php
defined('_JEXEC') or die;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

JHtml::_('jquery.framework');
JHtml::_('script', 'system/html5fallback.js', false, true);
JHtml::_('behavior.colorpicker');

class JFormFieldBootstrapVariables extends JFormFieldList
{
	public $type = 'BootstrapVariables';

	private $variables = array();

	/**
	 * Creates color picker
	 *
	 * @return  JFormField  Formatted input
	 */
	protected function getInput()
	{
		$jinput = JFactory::getApplication()->input;
		$templateId = $jinput->getInt('id', 0);

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->quoteName('template'));
		$query->from($db->quoteName('#__template_styles'));
		$query->where($db->quoteName('id') . ' = ' . (int) $templateId);
		$db->setQuery($query);
		$templateName = $db->loadResult();

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
						preg_match_all("/\/\/==(" . $titles[$i] . ")([\w\W]*)\/\/==/", $variablesContent, $pat_array);
					}
					else
					{
						preg_match_all("/\/\/==(" . $titles[$i] . ")([\w\W]*)\/\/==" . $titles[$i + 1] . "/", $variablesContent, $pat_array);
					}

					if (count($pat_array[2]) > 0 && !empty($pat_array[1][0]))
					{
						$groupName = str_replace("=", "", $pat_array[1][0]);
						$groupName = trim($groupName);

						$variables = $pat_array[2][0];

						preg_match_all("/[\$](.*)\:(.*)!/", $variables, $pat_array);

						if (count($pat_array[1]) > 0)
						{
							foreach ($pat_array[1] as $key => $value)
							{
								$v = trim($pat_array[2][$key]);
								$v = htmlspecialchars($v);

								$this->variables[$groupName][$value] = trim($v);
							}
						}
					}
				}
			}
		}

		$html = '<div class="container-fluid bootstrapvariables">';

		foreach ($this->variables as $group => $set)
		{
			$html .= '<h2>' . $group . '</h2>';
			$html .= '<div class="row-fluid">';

			$i = 0;

			foreach ($set as $key => $value)
			{
				if ($i > 0 && $i % 4 == 0)
				{
					$html .= '</div><div class="row-fluid">';
				}

				$name = $this->name . '[' . $key . ']';

				$html .= '<div class="span3">
							<label for="input-' . $key . '">' . $key . '</label>
							<input type="text" name="' . $name . '" class="input" value="' . $value . '" id="wb_bootstrapvariables-input-' . $key . '" placeholder="' . $value . '">
						</div>';

				$i++;
			}

			$html .= '</div>';
		}

		$html .= '</div>';

		return $html;
	}
}
