<?php

defined('_JEXEC') or die('You are not allowed to directly access this file');

require_once dirname(__FILE__) . '/scss.inc.php';

use Leafo\ScssPhp\Compiler;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class BuildBootstrap
{
	static function getInstance()
	{
		static $instance = null;

		if ($instance === null)
		{
			$instance = new BuildBootstrap;
		}

		return $instance;
	}

	public function start()
	{
		$document = JFactory::getDocument();

		// Check rebuild SCSS
		$buildScss = $document->params->get('build', false);

		if ($buildScss == false && is_file(JPATH_THEMES . '/' . $document->template . '/css/style.css'))
		{
			$scss_path = JPATH_THEMES . '/' . $document->template . '/scss';

			$cachetime = filemtime(JPATH_THEMES . '/' . $document->template . '/css/style.css');

			$files = JFolder::files($scss_path, '.scss', true, true);

			if (count($files) > 0)
			{
				foreach ($files as $file)
				{
					if (filemtime($file) > $cachetime)
					{
						$buildScss = true;
						break;
					}
				}
			}
		}
		else
		{
			$buildScss = true;
		}

		// Build SCSS
		if ($buildScss)
		{
			$scss = new Compiler;

			$scss->setFormatter("Leafo\ScssPhp\Formatter\Crunched");

			$scss->setImportPaths(
				array(
					JPATH_THEMES . '/' . $document->template . '/scss/',
					JPATH_THEMES . '/' . $document->template . '/wright/build/',
					JPATH_THEMES . '/' . $document->template . '/wright/build/libraries/bootstrap',
					JPATH_THEMES . '/' . $document->template . '/wright/build/libraries/redcomponent/'
				)
			);

			$columnsNumber = $document->params->get('columnsNumber', 12);

			if (is_file(JPATH_THEMES . '/' . $document->template . '/css/style.css'))
			{
				unlink(JPATH_THEMES . '/' . $document->template . '/css/style.css');
			}

			$ds = '@import "scss/functions";';
			$ds .= '@import "variables";';
			$ds .= '$grid-columns: ' . $columnsNumber . ';';
			$ds .= '@import "scss/bootstrap";';
			$ds .= '@import "template"; ';

			$ds .= '@import "scss/joomla";';
			$ds .= '@import "scss/typography";';


			file_put_contents(JPATH_THEMES . '/' . $document->template . '/css/style.css', $scss->compile($ds));

			$document->params->set('build', false);

			// Version
			$version = $document->params->get('version', 1);
			$version++;
			$document->params->set('version', $version);

			$newParams = new JRegistry(json_decode($document->params));

			$templateId = JFactory::getApplication()->getTemplate(true)->id;

			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->update($db->quoteName('#__template_styles'))->set($db->quoteName('params') . ' = ' . $db->q($newParams))->where($db->quoteName('id') . ' = ' . $db->q($templateId));

			$db->setQuery($query);
			$db->execute();
		}

		// Check rebuild JS
		$buildJs = $document->params->get('buildjs', false);

		$js_path = JPATH_THEMES . '/' . $document->template . '/js';

		$jsFiles = JFolder::files($js_path, '.js', true, true, array('.svn', 'CVS', '.DS_Store', '__MACOSX', 'template.js'));

		if ($buildJs == false && is_file(JPATH_THEMES . '/' . $document->template . '/js/template.js'))
		{
			$cachetime = filemtime(JPATH_THEMES . '/' . $document->template . '/js/template.js');

			if (count($jsFiles) > 0)
			{
				foreach ($jsFiles as $file)
				{
					if (filemtime($file) > $cachetime)
					{
						$buildJs = true;
						break;
					}
				}
			}
		}
		else
		{
			$buildJs = true;
		}

		if ($buildJs)
		{
			$wr = Wright::getInstance();
			$jsFiles = $wr->_jsScripts;

			if (count($jsFiles) > 0)
			{
				$buffer = "";

				foreach ($jsFiles as $file)
				{
					$buffer .= $this->getJsContent($file);
				}

				file_put_contents($js_path . '/template.js', $this->compress($buffer));
			}

			$document->params->set('buildjs', false);

			// Version
			$versionjs = $document->params->get('versionjs', 1);
			$versionjs++;
			$document->params->set('versionjs', $versionjs);

			$newParams = new JRegistry(json_decode($document->params));

			$templateId = JFactory::getApplication()->getTemplate(true)->id;

			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->update($db->quoteName('#__template_styles'))->set($db->quoteName('params') . ' = ' . $db->q($newParams))->where($db->quoteName('id') . ' = ' . $db->q($templateId));

			$db->setQuery($query);
			$db->execute();
		}
	}

	private function compress($string)
	{
		$string = str_replace('/// ', '///', $string);
		$string = str_replace(',//', ', //', $string);
		$string = str_replace('{//', '{ //', $string);
		$string = str_replace('}//', '} //', $string);
		$string = str_replace('/**/', '/*  */', $string);
		$string = preg_replace("/\/\/.*\n\/\/.*\n\/\/.*\n\/\/.*\n\/\/.*\n\/\/.*\n\/\/.*\n\/\/.*\n/", "", $string);
		$string = preg_replace("/\/\/.*\n\/\/.*\n\/\/.*\n\/\/.*\n\/\/.*\n\/\/.*\n\/\/.*\n/", "", $string);
		$string = preg_replace("/\/\/.*\n\/\/.*\n\/\/.*\n\/\/.*\n\/\/.*\n\/\/.*\n/", "", $string);
		$string = preg_replace("/\/\/.*\n\/\/.*\n\/\/.*\n\/\/.*\n\/\/.*\n/", "", $string);
		$string = preg_replace("/\/\/.*\n\/\/.*\n\/\/.*\n\/\/.*\n/", "", $string);
		$string = preg_replace("/\/\/.*\n\/\/.*\n\/\/.*\n/", "", $string);
		$string = preg_replace('/\/\/.*\/\/\n/', '', $string);
		$string = preg_replace("/\s\/\/\".*/", "", $string);
		$string = preg_replace("/\/\/\n/", "\n", $string);
		$string = preg_replace("/\/\/\s.*.\n/", "\n  \n", $string);
		$string = preg_replace('/\/\/w[^w].*/', '', $string);
		$string = preg_replace('/\/\/s[^s].*/', '', $string);
		$string = preg_replace('/\/\/\*\*\*.*/', '', $string);
		$string = preg_replace('/\/\/\*\s\*\s\*.*/', '', $string);
		$string = preg_replace('!/\*[^\'."].*?\*/!s', '', $string);
		$string = preg_replace('/\n\s*\n/', "\n", $string);
		$string = preg_replace("/<!--.*-->/Us", "", $string);

		return $string;
	}

	private function getJsContent($file)
	{
		if (\JUri::isInternal($file))
		{
			$p = parse_url($file);
			$file = JPATH_SITE . $p['path'];
		}

		$buffer = '';

		if ($handle = fopen($file,"r")) 
		{
	        while (!feof($handle)) 
	        {
			    $buffer .= fread($handle, 8192);
			}

			$buffer = trim($buffer);

			if (preg_match('/;$/', $buffer) == false)
			{
				$buffer .= ';' . "\n";
			}

			$buffer .= "\n";
		}

		return $buffer;
	}
}
