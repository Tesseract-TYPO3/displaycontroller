<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

// Add FlexForm options for both controllers
// @todo: check if this can be moved safely to Configuration/TCA/Overrides
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
	'*',
	'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForm/Options.xml',
	$_EXTKEY . '_pi1'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
	'*',
	'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForm/Options.xml',
	$_EXTKEY . '_pi2'
);

// Add context sensitive help (csh) for the FlexForm
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
	'tt_content.pi_flexform.displaycontroller_pi1.CType',
	'EXT:' . $_EXTKEY . '/locallang_csh_options.xml'
);
// Add context sensitive help (csh) for the new fields
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
	'tt_content',
	'EXT:' . $_EXTKEY . '/locallang_csh_ttcontent.xml'
);

$extensionPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY);
$extensionRelativePath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY);
$typeIcon = $extensionRelativePath . 'Resources/Public/Icons/TypeIcon.png';
// Register icon with sprite manager
$icons = array(
	'type-controller' => $typeIcon
);
\TYPO3\CMS\Backend\Sprite\SpriteManager::addSingleIcons($icons, $_EXTKEY);

// Register plug-ins (pi1 is cached, pi2 is not cached)
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
	array(
		'LLL:EXT:displaycontroller/locallang_db.xml:tt_content.CType_pi1',
		$_EXTKEY . '_pi1',
		$typeIcon
	),
	'CType'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
	array(
		'LLL:EXT:displaycontroller/locallang_db.xml:tt_content.CType_pi2',
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
