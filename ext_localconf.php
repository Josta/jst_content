<?php
defined('TYPO3_MODE') || die ();

$conf = unserialize($_EXTCONF);	

// Tell Flux we have Content Elements
\FluidTYPO3\Flux\Core::registerProviderExtensionKey('jst_content', 'Content');

// Inject main template
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript('jst_content', 'setup',
	'<INCLUDE_TYPOSCRIPT: source="FILE:EXT:jst_content/Configuration/TypoScript/Setup.txt">',
	'defaultContentRendering' 
);

// Inject HTML fluid syntax extension
if ($conf['fluid_in_html']) {
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript('jst_content', 'setup',
		'<INCLUDE_TYPOSCRIPT: source="FILE:EXT:jst_content/Configuration/TypoScript/FluidInHTML.txt">',
		'defaultContentRendering');
}

// Make image grid flexbox ready
if ($conf['responsive_image_columns']) {
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript('jst_content', 'setup',
		'<INCLUDE_TYPOSCRIPT: source="FILE:EXT:jst_content/Configuration/TypoScript/ResponsiveImageColumns.txt">',
		'defaultContentRendering');
}

// Register responsive image renderer
if ($conf['responsive_image_sizes']) {
	\TYPO3\CMS\Core\Resource\Rendering\RendererRegistry::getInstance()->registerRendererClass(
		\Josta\JstContent\View\PictureRenderer::class);
}

// Register Frontend icons
if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('jst_assets')) {
	\Josta\JstAssets\Utility\IconUtility::addIconPath('EXT:jst_content/Resources/Public/Icons/Frontend');
}

