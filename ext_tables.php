<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

// Add context sensitive help (csh) for the new fields
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
	'tt_content',
	'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh_ttcontent.xlf'
);

$extensionPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY);
$extensionRelativePath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY);

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

// Register plug-ins (pi1 is cached, pi2 is not cached)
$typeIcon = $extensionRelativePath . 'Resources/Public/Icons/TypeIcon.png';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
	array(
		'LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tt_content.CType_pi1',
		$_EXTKEY . '_pi1',
		$typeIcon
	),
	'CType'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
	array(
		'LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tt_content.CType_pi2',
		$_EXTKEY . '_pi2',
		$typeIcon
	),
	'CType'
);

// Register wizards for plug-ins
if (TYPO3_MODE === 'BE') {
	$GLOBALS['TBE_MODULES_EXT']['xMOD_db_new_content_el']['addElClasses']['Tesseract\\Displaycontroller\\Controller\\PluginCachedWizard'] = $extensionPath . 'Classes/Wizard/PluginCachedWizard.php';
	$GLOBALS['TBE_MODULES_EXT']['xMOD_db_new_content_el']['addElClasses']['Tesseract\\Displaycontroller\\Controller\\PluginNotCachedWizard'] = $extensionPath . 'Classes/Wizard/PluginNotCachedWizard.php';
}

// Declare static TypoScript
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
	$_EXTKEY,
	'Configuration/TypoScript/',
	'Generic display controller'
);

// Register the name of the table linking the controller and its components
$GLOBALS['T3_VAR']['EXT']['tesseract']['controller_mm_tables'][] = 'tx_displaycontroller_components_mm';
