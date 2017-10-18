<?php
namespace Josta\JstContent\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * A small wrapper to use the JST Assets Icon API without making it a dependency.
 */
class IconViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
	
    protected $escapeOutput = false;
	
	/**
	 * @param string name
	 * @param string classes
	 * @return string
	 */
    public function render($name, $classes='') {
		if (class_exists($vh = 'Josta\\JstAssets\\ViewHelpers\\IconViewHelper', true))
			return $this->objectManager->get($vh)->forwardRender($name, $classes);
		$file = GeneralUtility::getFileAbsFileName('EXT:jst_content/Resources/Public/Icons/Frontend/'.$name.'.svg');
		return preg_replace('/^.*<svg/s', '<svg class="icon icon-'.$name.' '.$classes.'"', file_get_contents($file));
    }
}