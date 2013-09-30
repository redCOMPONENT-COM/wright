<?php

class WrightAdapterJoomlaHead
{
	public function render($args)
	{
		jimport('joomla.environment.browser');
		$browser = JBrowser::getInstance();

		$head = '';
		$dochtml = JFactory::getDocument();
		if ($dochtml->params->get('responsive',1)) {
		    // add viewport meta for tablets
		    $head = '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
		    $head .= "\n";
		}

		$head .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
		$head .= "\n";
		$head .= '<jdoc:include type="head" />';
	    $head .= "\n";

	    $wr = Wright::getInstance();
	    $head .= $wr->generateCSS();

	    if ($browser->getBrowser() == 'msie')
		{
			// Switch to allow specific versions of IE to have additional sheets
			$major = $browser->getMajor();

			if ((int) $major <= 9)
			{
				$head .= '<script src="' . JURI::root() . 'templates/' . $wr->document->template . '/wright/js/html5shiv.js" type="text/javascript"></script>';
				$head .= "\n";
				$head .= '<script src="' . JURI::root() . 'templates/' . $wr->document->template . '/wright/js/respond.min.js" type="text/javascript"></script>';
			}
		}

		return $head;
	}
}
