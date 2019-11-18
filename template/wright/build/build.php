<?php

defined('_JEXEC') or die('You are not allowed to directly access this file');

use ScssPhp\ScssPhp\Compiler;

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

		if ($buildScss == false && is_file(JPATH_THEMES . '/' . $document->template . '/dist/css/style.css'))
		{
			$scss_path = JPATH_THEMES . '/' . $document->template . '/src/scss';

			$cachetime = filemtime(JPATH_THEMES . '/' . $document->template . '/dist/css/style.css');

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

			$scss->setFormatter("ScssPhp\ScssPhp\Formatter\Crunched");

			$scss->setImportPaths(
				array(
					JPATH_THEMES . '/' . $document->template . '/src/scss/',
					JPATH_THEMES . '/' . $document->template . '/vendor/twbs/bootstrap/scss',
				)
			);

			$columnsNumber = $document->params->get('columnsNumber', 12);

			if (is_file(JPATH_THEMES . '/' . $document->template . '/dist/css/style.css'))
			{
				unlink(JPATH_THEMES . '/' . $document->template . '/dist/css/style.css');
			}

			$ds = '@import "template"; ';

			file_put_contents(JPATH_THEMES . '/' . $document->template . '/dist/css/style.css', $scss->compile($ds));

			$ds = '@import "editor"; ';

			file_put_contents(JPATH_THEMES . '/' . $document->template . '/dist/css/editor.css', $scss->compile($ds));

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

		$js_path = JPATH_THEMES . '/' . $document->template . '/src/js';

		$jsFiles = JFolder::files($js_path, '.js', true, true, array('.svn', 'CVS', '.DS_Store', '__MACOSX', 'template.js'));

		if ($buildJs == false && is_file(JPATH_THEMES . '/' . $document->template . '/dist/js/js.js'))
		{
			$cachetime = filemtime(JPATH_THEMES . '/' . $document->template . '/dist/js/js.js');

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

				file_put_contents(JPATH_THEMES . '/' . $document->template . '/dist/js/js.js', $this->compress($buffer));
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

	private function compress($js)
	{
		return \JShrink\Minifier::minify($js, array('flaggedComments' => false));
	}

	private function getJsContent($file)
	{
		$read = false;
		$buffer = '';

		if (\JUri::isInternal($file))
		{
			$path = parse_url($file, PHP_URL_PATH);

			$readfile = JPATH_SITE . $path;

			if (file_exists($readfile) && $handle = fopen($readfile,"r")) 
			{
				$read = true;

				while (!feof($handle)) 
				{
					$buffer .= fread($handle, 8192);
				}
			}
		}
		
		if ($read == false)
		{
			$arrContextOptions=array(
				"ssl"=>array(
					"verify_peer" => false,
					"verify_peer_name" => false,
				),
			);  

			$buffer = file_get_contents($file, false, stream_context_create($arrContextOptions));
		}

		if (!empty($buffer))
		{
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
