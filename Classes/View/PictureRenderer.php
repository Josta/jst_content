<?php
namespace Josta\JstContent\View;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;

class PictureRenderer implements \TYPO3\CMS\Core\Resource\Rendering\FileRendererInterface {
	
	/**
	 * @var array
	 */
	protected $possibleMimeTypes = ['image/jpeg', 'image/png'];
	
	/**
	 * Returns the priority of the renderer
	 * Should be between 1 and 100, 100 is more important than 1
	 *
	 * @return int
	 */
	public function getPriority() {
		return 1;
	}
	
	/**
	 * Check if given File(Reference) can be rendered
	 *
	 * @param FileInterface $file File or FileReference to render
	 * @return bool
	 */
	public function canRender(FileInterface $file) {
		return in_array($file->getMimeType(), $this->possibleMimeTypes, TRUE);
	}
	
	/**
	 * Render for given File(Reference) HTML output
	 *
	 * @param FileInterface $image
	 * @param int|string $width TYPO3 known format; examples: 220, 200m or 200c
	 * @param int|string $height TYPO3 known format; examples: 220, 200m or 200c
	 * @param array $options controls = TRUE/FALSE (default TRUE), autoplay = TRUE/FALSE (default FALSE), loop = TRUE/FALSE (default FALSE)
	 * @param bool $usedPathsRelativeToCurrentScript See $file->getPublicUrl()
	 * @return string
	 */
	public function render(FileInterface $image, $width, $height, array $options = [], $usedPathsRelativeToCurrentScript = FALSE) {
		$svc = GeneralUtility::makeInstance(ObjectManager::class)->get(ImageService::class);
		
		
		// prepare crop
        $cropVariants = CropVariantCollection::create(
			(string) ($image instanceof FileReference ? $image->getProperty('crop') : ''));			
		$cropVariant = $options['cropVariant'] ?: 'default';
        $cropArea = $cropVariants->getCropArea($cropVariant);
		$crop = $cropArea->isEmpty() ? null : $cropArea->makeAbsoluteBasedOnFile($image);
		$focusArea = $cropVariants->getFocusArea($cropVariant);
		
		// create default image
		$defaultImage = $svc->getImageUri($svc->applyProcessingInstructions($image,
			['width' => $width, 'height' => $height, 'crop' => $crop]));
		
		// create srcset images
		$setwidths = [200, 400, 600, 800];
		$sources = [];
		foreach ($setwidths as $setwidth) {
			$img = $svc->applyProcessingInstructions($image,
				['width' => $setwidth, 'height' => '', 'crop' => $crop]);
			$sources[] = $svc->getImageUri($img) .' '. $img->getProperty('width') . 'w';
		}
       
		// attributes
		$attr = [];
		$attr[] = 'src="'	.$defaultImage.'"';
		$attr[] = 'srcset="'.implode(', ', $sources).'"';
		$attr[] = 'alt="'	.$image->getProperty('alternative').'"';
		$attr[] = 'title="'	.$image->getProperty('title').'"';
		$attr[] = 'width="100%"';
		/*if (!$focusArea->isEmpty()) {
			$attr[] = 'data-focus-area="'.$focusArea->makeAbsoluteBasedOnFile($image).'"';
		}*/
		
		
		// original_url = htmlspecialchars($file->getPublicUrl($usedPathsRelativeToCurrentScript)),
	
		return '<noscript><img '.implode(' ', $attr).'/></noscript>';
	}
}