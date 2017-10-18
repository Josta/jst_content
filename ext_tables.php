<?php
defined('TYPO3_MODE') || die ();

// Bug fix, see https://forge.typo3.org/issues/80541
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ElementBrowsers']['file_reference'] = \TYPO3\CMS\Recordlist\Browser\FileBrowser::class;

if (TYPO3_MODE === 'BE') {

	// Include Page TSConfig (new content wizard tab reordering and better TCA defaults for columns/area elements)
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
		'<INCLUDE_TYPOSCRIPT: source="FILE:EXT:jst_content/Configuration/TSconfig/PageTS.txt">');
	
	$conf = unserialize($_EXTCONF);	
	
	// Enable content preview mode "Tabs" for BE
	if ($conf['enable_be_tabs']) {
		$extpath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('jst_content');
		$page = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);
		$page->addJsFile($extpath . 'Resources/Public/be-tabs.js', 'text/javascript', false, true, '', true);
		$page->addCssFile($extpath. 'Resources/Public/be-tabs.css');
	}
	
}