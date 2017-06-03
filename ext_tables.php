<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

// Add context sensitive help (csh) for the new fields
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
	'tt_content',
	'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh_ttcontent.xlf'
);

// Register icons for content element type
/** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
        'tx_displaycontroller-content-element',
        \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
        [
            'source' => 'EXT:displaycontroller/Resources/Public/Icons/TypeIcon.png'
        ]
);
$iconRegistry->registerIcon(
        'tx_displaycontroller-content-element-wizard',
        \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
        [
            'source' => 'EXT:displaycontroller/Resources/Public/Icons/WizardIcon.png'
        ]
);

// Register the name of the table linking the controller and its components
$GLOBALS['T3_VAR']['EXT']['tesseract']['controller_mm_tables'][] = 'tx_displaycontroller_components_mm';
