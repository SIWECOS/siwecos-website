<?php
/**
 * Siwecos Template
 *
 * @version     1.0.0
 * @package     Siwecosjoomla
 * @subpackage  Sealoftrustplugin
 * @copyright   Copyright (C) 2018 eco.de
 * @license     GPLv2 or later
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$params = $app->getParams();
$pageclass = $params->get('pageclass_sfx');

if ($this->countModules('sidebar'))
{
	$pageclass .= " sidebar";
}

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;

// Generator tag
$this->setGenerator(null);

// Load critical CSS
$criticalCss = file_get_contents(dirname(__FILE__) . "/css/critical.css");

// Generate CSP
$cspRules = array(
	"default-src" => array("'self'"),
	'connect-src' => array("'self'", "https://api.siwecos.de", "https://bla.staging2.siwecos.de", "https://ca.staging2.siwecos.de"),
	'style-src' => array("'self'", "'unsafe-inline'", "'sha256-" . base64_encode(hash("sha256", $criticalCss, true)) . "'"),
	'frame-src' => array("'self'", "https://www.youtube.com/", "https://www.youtube-nocookie.com/"),
	'img-src' => array(
			"'self'", "data:", "https://img.youtube.com", "https://i1.ytimg.com",
			"https://i.ytimg.com", "https://i9.ytimg.com", "https://s.ytimg.com"
	)
);

$cspString = "";

foreach ($cspRules as $cspType => $assets)
{
	$cspString .= $cspType . " " . implode(" ", $assets) . "; ";
}

$app->setHeader('Content-Security-Policy', $cspString);

// Add Stylesheets
unset($this->_scripts[$this->baseurl . '/media/system/js/caption.js']);
unset($this->_scripts[$this->baseurl . '/media/jui/js/chosen.jquery.min.js']);

if (isset($this->_script['text/javascript']))
{
	$this->_script['text/javascript'] = preg_replace(
		'%jQuery\(window\).on\(\'load\',\s*function\(\)\s*\{\s*new\s*JCaption\(\'img.caption\'\)\;\s*\}\)\;\s*%',
		'',
		$this->_script['text/javascript']
	);

	if (empty($this->_script['text/javascript']))
	{
		unset($this->_script['text/javascript']);
	}
};

?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=5">
	<jdoc:include type="head" />
	<style><?php echo $criticalCss; ?></style>
</head>
<body class="<?php echo $pageclass ? htmlspecialchars($pageclass) : 'default'; ?>">
	<header>
		<div class="headerbar">
			<a href="<?php echo $this->baseurl ?>" class="headerbar__logo">
				<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/img/logo.svg" alt="<?php echo JText::_('TPL_SIWECOS_BACKTOHOME'); ?>">
			</a>
			<div class="headerbar__supportbox">
				<a href="http://www.it-sicherheit-in-der-wirtschaft.de/" target="_blank" rel="noopener noreferrer">
					<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/img/IT-Sicherheit.png" alt="<?php echo JText::_('TPL_SIWECOS_TOITSICHERHEITHOMEPAGE'); ?>">
				</a>
				<a href="http://www.bmwi.de/" target="_blank" rel="noopener noreferrer">
					<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/img/BMWi_4C_Gef_de.png" alt="<?php echo JText::_('TPL_SIWECOS_TOBMWIHOMEPAGE'); ?>">
				</a>
			</div>
			<div class="headerbar__navbar">
				<button class="mainnav__navtoggler" title="Menü öffnen/schließen">
					<span></span>
					<span></span>
					<span></span>
					<span></span>
				</button>
				<nav class="headerbar__mainnav">
					<jdoc:include type="modules" name="mainnav" />
				</nav>
				<section class="headerbar__search">
					<jdoc:include type="modules" name="search" />
				</section>
				<nav class="languageselect">
					<jdoc:include type="modules" name="language" />
				</nav>
			</div>
		</div>
	</header>

	<div class="contentcontainer">
		<main>
			<?php if ($this->countModules('main-top')): ?>
				<section class="main-top">
					<jdoc:include type="modules" name="main-top" style="html5" />
				</section>
			<?php endif; ?>
			<section class="main-content">
				<jdoc:include type="message" />
				<jdoc:include type="component" />
			</section>
			<?php if ($this->countModules('main-bottom')): ?>
				<section class="main-bottom">
					<jdoc:include type="modules" name="main-bottom" style="html5" />
				</section>
			<?php endif; ?>
		</main>
		<?php if ($this->countModules('sidebar')): ?>
			<aside class="sidebar">
				<jdoc:include type="modules" name="sidebar" style="html5" />
			</aside>
		<?php endif; ?>
	</div>

	<footer>
		<jdoc:include type="modules" name="footer" style="html5" />
	</footer>
	<jdoc:include type="modules" name="debug" />

	<link href="<?php echo 'templates/' . $this->template . '/css/template.css?v=' . md5(file_get_contents(dirname(__FILE__) . "/css/template.css")); ?>" rel="stylesheet" type="text/css" />
	<script async src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/template.js"></script>
</body>
</html>
