<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

// Register plug-ins with standard template
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43(
	$_EXTKEY,
	'Classes/Controller/PluginCached.php',
	'_pi1',
	'CType',
	1
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43(
	$_EXTKEY,
	'Classes/Controller/PluginNotCached.php',
	'_pi2',
	'CType',
	0
);

// Add plugin controller
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
	'Tesseract.Displaycontroller',
	'setup',
	'
		# Setting "felogin" plugin TypoScript
		plugin.tx_displaycontroller_pi1 = USER
		plugin.tx_displaycontroller_pi1.userFunc = Tesseract\\Displaycontroller\\Controller\\PluginCached->main
	'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
	'Tesseract.Displaycontroller',
	'setup',
	'
		# Setting "felogin" plugin TypoScript
		plugin.tx_displaycontroller_pi2 = USER_INT
		plugin.tx_displaycontroller_pi2.userFunc = Tesseract\\Displaycontroller\\Controller\\PluginNotCached->main
	'
);

// Initialise known list of consumer and providers (if not yet done (might be if extensions were not loaded in proper order))
if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['providers'])) {
	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['providers'] = array();
}
if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['consumers'])) {
	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['consumers'] = array();
}

// Register Controller services for both plug-ins
// NOTE 1: the type of service is "datacontroller" and not "controller" to avoid conflict with a possible, future, core "controller" service
// NOTE 2: the subtype corresponds to the CType
// NOTE 3: the actual class used is the same for both plug-ins (since both plug-ins are the same, except for the cache)
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
	$_EXTKEY,
	// Service type
	'datacontroller',
	// Service key
	'tx_displaycontroller_pi1',
	array(
		'title' => 'Display Controller (cached)',
		'description' => 'Controller service for the (cached) display controller',

		'subtype' => 'displaycontroller_pi1',

		'available' => TRUE,
		'priority' => 50,
		'quality' => 50,

		'os' => '',
		'exec' => '',

		'className' => \Tesseract\Displaycontroller\Service\ControllerService::class,
	)
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
	$_EXTKEY,
	// Service type
	'datacontroller',
	// Service key
	'tx_displaycontroller_pi2',
	array(
		'title' => 'Display Controller (not cached)',
		'description' => 'Controller service for the (not cached) display controller',

		'subtype' => 'displaycontroller_pi2',

		'available' => TRUE,
		'priority' => 50,
		'quality' => 50,

		'os' => '',
		'exec' => '',

		'className' => \Tesseract\Displaycontroller\Service\ControllerService::class,
	)
);
