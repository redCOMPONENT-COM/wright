<?php

defined('_JEXEC') or die('You are not allowed to directly access this file');

require_once dirname(__FILE__) . '/scss.inc.php';

use Leafo\ScssPhp\Compiler;

class BuildBootstrap
{
	static function getInstance()
	{
		static $instance = null;

		if ($instance === null)
		{
			$instance = new Wright;
		}

		return $instance;
	}

	public function start()
	{
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');

		$document = JFactory::getDocument();

		$less_path = JPATH_THEMES . '/' . $document->template . '/scss';

		// Check rebuild less
		$rebuild = $document->params->get('build', false);

		if (is_file(JPATH_THEMES . '/' . $document->template . '/css/style.css'))
		{
			$cachetime = filemtime(JPATH_THEMES . '/' . $document->template . '/css/style.css');

			$files = JFolder::files($less_path, '.scss', true, true);

			if (count($files) > 0)
			{
				foreach ($files as $file)
				{
					if (filemtime($file) > $cachetime)
					{
						$rebuild = true;
						break;
					}
				}
			}
		}
		else
		{
			$rebuild = true;
		}

		// Build LESS
		if ($rebuild)
		{
			$scss = new Compiler();

			$scss->setFormatter("Leafo\ScssPhp\Formatter\Crunched");

			$scss->setImportPaths(
				array(
					JPATH_THEMES . '/' . $document->template . '/scss/',
					JPATH_THEMES . '/' . $document->template . '/wright/build/',
					JPATH_THEMES . '/' . $document->template . '/wright/build/libraries/bootstrap/stylesheets/',
					JPATH_THEMES . '/' . $document->template . '/wright/build/libraries/redcomponent/'
				)
			);


			$columnsNumber = $document->params->get('columnsNumber', 12);

			if (is_file(JPATH_THEMES . '/' . $document->template . '/css/style.css'))
			{
				unlink(JPATH_THEMES . '/' . $document->template . '/css/style.css');
			}

			$ds = '@import "variables";';
			$ds .= '$grid-columns: ' . $columnsNumber . ';';
			$ds .= '@import "scss/bootstrap";';
			$ds .= '@import "template-responsive"; ';

			$ds .= '@import "scss/joomla";';

			$ds .= '@import "scss/typography";';
			$ds .= '@import "redcomponent"; ';

			if ($document->params->get('responsive', 1))
			{
				$ds .= '@import "template-responsive"; ';
				$ds .= '@import "redcomponent-responsive"; ';
			}
			else
			{
				$ds .= '.container{width:$container-desktop !important} .navbar-nav > li {float: left;} ';
			}

			file_put_contents(JPATH_THEMES . '/' . $document->template . '/css/style.css', $scss->compile($ds));

			$document->params->set('build', false);

			$newParams = new JRegistry(json_decode($document->params));

			$templateId = JFactory::getApplication()->getTemplate(true)->id;

			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->update($db->quoteName('#__template_styles'))->set($db->quoteName('params') . ' = ' . $db->q($newParams))->where($db->quoteName('id') . ' = ' . $db->q($templateId));

			$db->setQuery($query);
			$db->execute();
		}
	}
}
