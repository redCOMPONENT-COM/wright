<?php
/**
 * @copyright      Copyright (C) 2013 redComponent
 * @author         redComponent
 * @package        Template
 *
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

$bodyclass = "";

if ($this->countModules('toolbar'))
{
	$bodyclass = "toolbarpadding";
}

?>
<!DOCTYPE html>
	<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
	<head>
		<!--
            ##########################################
            ## redComponent ApS                     ##
            ## Blangstedgaardsvej 1                 ##
            ## 5220 Odense SØ                       ##
            ## Danmark                              ##
            ## support@redcomponent.com             ##
            ## http://www.redcomponent.com          ##
            ## Dato: 2013-04-23                     ##
            ##########################################
        -->

		<w:head />
	</head>
	<body <?php if ($bodyclass != "") : ?> class="<?php echo $bodyclass ?>"<?php endif; ?>>
	<?php if ($this->countModules('toolbar')) : ?>
		<!-- menu -->
		<w:nav containerClass="container" rowClass="row" wrapClass="navbar-fixed-top navbar-inverse" type="toolbar" name="toolbar" />
	<?php endif; ?>

	<div class="container">
		<!-- header -->
		<header id="header">
			<div class="row clearfix">
                <w:logo name="top"   />
				<div class="clear"></div>
			</div>
		</header>
		<?php if ($this->countModules('menu')) : ?>
			<!-- menu -->
			<w:nav name="menu" />
		<?php endif; ?>

		<?php if ($this->countModules('featured')) : ?>
			<!-- featured -->
			<div id="featured">
				<w:module type="none" name="featured" chrome="xhtml" />
			</div>
		<?php endif; ?>

		<?php if ($this->countModules('grid-top')) : ?>
			<!-- grid-top -->
			<div id="grid-top">
				<w:module type="row" name="grid-top" chrome="popover" extra="bottom"  />
			</div>
		<?php endif; ?>
		<?php if ($this->countModules('grid-top2')) : ?>
			<!-- grid-top2 -->
			<div id="grid-top2">
				<w:module type="row" name="grid-top2" chrome="wrightflexgrid" />
			</div>
		<?php endif; ?>
		<div id="main-content" class="row">
			<!-- sidebar1 -->
			<aside id="sidebar1">
				<w:module name="sidebar1" chrome="accordion" />
			</aside>
			<!-- main -->
			<section id="main">
				<?php if ($this->countModules('above-content')) : ?>
					<!-- above-content -->
					<div id="above-content">
						<w:module type="none" name="above-content" chrome="xhtml" />
					</div>
				<?php endif; ?>
				<?php if ($this->countModules('breadcrumbs')) : ?>
					<!-- breadcrumbs -->
					<div id="breadcrumbs">
						<w:module type="single" name="breadcrumbs" chrome="none" />
					</div>
				<?php endif; ?>
				<!-- component -->
				<w:content />
				<?php if ($this->countModules('below-content')) : ?>
					<!-- below-content -->
					<div id="below-content">
						<w:module type="none" name="below-content" chrome="xhtml" />
					</div>
				<?php endif; ?>
			</section>
			<!-- sidebar2 -->
			<aside id="sidebar2">
				<w:module name="sidebar2" chrome="xhtml" />
			</aside>
		</div>
		<?php if ($this->countModules('grid-bottom')) : ?>
			<!-- grid-bottom -->
			<div id="grid-bottom">
				<w:module type="row" name="grid-bottom" chrome="wrightflexgrid" />
			</div>
		<?php endif; ?>
		<?php if ($this->countModules('grid-bottom2')) : ?>
			<!-- grid-bottom2 -->
			<div id="grid-bottom2">
				<w:module type="row" name="grid-bottom2" chrome="wrightflexgrid" />
			</div>
		<?php endif; ?>
		<?php if ($this->countModules('bottom-menu')) : ?>
			<!-- bottom-menu -->
			<w:nav containerClass="container" rowClass="row" name="bottom-menu" />
		<?php endif; ?>
	</div>

	<!-- footer -->
	<div class="wrapper-footer">
		<footer id="footer" <?php if ($this->params->get('stickyFooter', 1)) : ?> class="sticky"<?php endif;?>>
			<div class="container">
				<?php if ($this->countModules('footer')) : ?>
					<w:module type="row" name="footer" chrome="wrightflexgrid" />
				<?php endif; ?>

			</div>
		</footer>
	</div>

    <w:footer />

	</body>
	</html>
