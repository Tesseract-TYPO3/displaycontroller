<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}
// Add new columns to tt_content
//
// A note about MM_match_fields:
// This structure makes use of a lot of additional fields in the MM table
// "component" defines whether the related component is a consumer, a provider and a filter
// "rank" defines the position of the component in the relation chain (1, 2, 3, ...)
// "local_table" and "local_field" are set so that the relation can be reversed-engineered
// when looking from the other side of the relation (i.e. the component). They help
// the component know to which record from which table it is related and in which
// field to find the type of controller (which is matched to a specific datacontroller service)
$tempColumns = array(
	'tx_displaycontroller_consumer' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tt_content.tx_displaycontroller_consumer',
		'config' => array(
			'type' => 'group',
			'internal_type' => 'db',
			'allowed' => (isset($GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_consumer']['config']['allowed'])) ? $GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_consumer']['config']['allowed'] : '',
			'size' => 1,
			'minitems' => 1,
			'maxitems' => 1,
			'prepend_tname' => 1,
			'MM' => 'tx_displaycontroller_components_mm',
			'MM_match_fields' => array(
				'component' => 'consumer',
				'rank' => 1,
				'local_table' => 'tt_content',
				'local_field' => 'CType'
			),
			'wizards' => array(
				'edit' => array(
					'type' => 'popup',
					'title' => 'LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:wizards.edit_dataconsumer',
					'script' => 'wizard_edit.php',
					'module' => array(
						'name' => 'wizard_edit'
					),
					'icon' => 'edit2.gif',
					'popup_onlyOpenIfSelected' => 1,
					'notNewRecords' => 1,
					'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1,resizable=yes'
				),
			)
		)
	),
	'tx_displaycontroller_provider' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tt_content.tx_displaycontroller_provider',
		'config' => array (
			'type' => 'group',
			'internal_type' => 'db',
			'allowed' => (isset($GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_provider']['config']['allowed'])) ? $GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_provider']['config']['allowed'] : '',
			'size' => 1,
			'minitems' => 1,
			'maxitems' => 1,
			'prepend_tname' => 1,
			'MM' => 'tx_displaycontroller_components_mm',
			'MM_match_fields' => array(
				'component' => 'provider',
				'rank' => 1,
				'local_table' => 'tt_content',
				'local_field' => 'CType'
			),
			'wizards' => array(
				'edit' => array(
					'type' => 'popup',
					'title' => 'LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:wizards.edit_dataprovider',
					'script' => 'wizard_edit.php',
					'module' => array(
						'name' => 'wizard_edit'
					),
					'icon' => 'edit2.gif',
					'popup_onlyOpenIfSelected' => 1,
					'notNewRecords' => 1,
					'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1,resizable=yes'
				),
			)
		)
	),
	'tx_displaycontroller_filtertype' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tt_content.tx_displaycontroller_filtertype',
		'config' => array (
			'type' => 'radio',
			'items' => array (
				array('LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tt_content.tx_displaycontroller_filtertype.I.0', ''),
				array('LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tt_content.tx_displaycontroller_filtertype.I.1', 'single'),
				array('LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tt_content.tx_displaycontroller_filtertype.I.2', 'list'),
				array('LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tt_content.tx_displaycontroller_filtertype.I.3', 'filter'),
			),
		)
	),
	'tx_displaycontroller_datafilter' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tt_content.tx_displaycontroller_datafilter',
		'config' => array (
			'type' => 'group',
			'internal_type' => 'db',
			'allowed' => (isset($GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_datafilter']['config']['allowed'])) ? $GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_datafilter']['config']['allowed'] : '',
			'size' => 1,
			'minitems' => 0,
			'maxitems' => 1,
			'prepend_tname' => 1,
			'MM' => 'tx_displaycontroller_components_mm',
			'MM_match_fields' => array(
				'component' => 'filter',
				'rank' => 1,
				'local_table' => 'tt_content',
				'local_field' => 'CType'
			),
			'wizards' => array(
				'edit' => array(
					'type' => 'popup',
					'title' => 'LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:wizards.edit_datafilter',
					'script' => 'wizard_edit.php',
					'module' => array(
						'name' => 'wizard_edit'
					),
					'icon' => 'edit2.gif',
					'popup_onlyOpenIfSelected' => 1,
					'notNewRecords' => 1,
					'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1,resizable=yes'
				),
			)
		)
	),
	'tx_displaycontroller_emptyfilter' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tt_content.tx_displaycontroller_emptyfilter',
		'config' => array (
			'type' => 'radio',
			'items' => array (
				array('LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tt_content.tx_displaycontroller_emptyfilter.I.0', ''),
				array('LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tt_content.tx_displaycontroller_emptyfilter.I.1', 'all'),
			),
		)
	),
	'tx_displaycontroller_provider2' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tt_content.tx_displaycontroller_provider2',
		'config' => array (
			'type' => 'group',
			'internal_type' => 'db',
			'allowed' => (isset($GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_provider2']['config']['allowed'])) ? $GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_provider2']['config']['allowed'] : '',
			'size' => 1,
			'minitems' => 0,
			'maxitems' => 1,
			'prepend_tname' => 1,
			'MM' => 'tx_displaycontroller_components_mm',
			'MM_match_fields' => array(
				'component' => 'provider',
				'rank' => 2,
				'local_table' => 'tt_content',
				'local_field' => 'CType'
			),
			'wizards' => array(
				'edit' => array(
					'type' => 'popup',
					'title' => 'LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:wizards.edit_dataprovider',
					'script' => 'wizard_edit.php',
					'module' => array(
						'name' => 'wizard_edit'
					),
					'icon' => 'edit2.gif',
					'popup_onlyOpenIfSelected' => 1,
					'notNewRecords' => 1,
					'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1,resizable=yes'
				),
			)
		)
	),
	'tx_displaycontroller_emptyprovider2' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tt_content.tx_displaycontroller_emptyprovider2',
		'config' => array (
			'type' => 'radio',
			'items' => array (
				array('LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tt_content.tx_displaycontroller_emptyfilter.I.0', ''),
				array('LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tt_content.tx_displaycontroller_emptyfilter.I.1', 'all'),
			),
		)
	),
	'tx_displaycontroller_datafilter2' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tt_content.tx_displaycontroller_datafilter2',
		'config' => array (
			'type' => 'group',
			'internal_type' => 'db',
			'allowed' => (isset($GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_datafilter2']['config']['allowed'])) ? $GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_datafilter2']['config']['allowed'] : '',
			'size' => 1,
			'minitems' => 0,
			'maxitems' => 1,
			'prepend_tname' => 1,
			'MM' => 'tx_displaycontroller_components_mm',
			'MM_match_fields' => array(
				'component' => 'filter',
				'rank' => 2,
				'local_table' => 'tt_content',
				'local_field' => 'CType'
			),
			'wizards' => array(
				'edit' => array(
					'type' => 'popup',
					'title' => 'LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:wizards.edit_datafilter',
					'script' => 'wizard_edit.php',
					'module' => array(
						'name' => 'wizard_edit'
					),
					'icon' => 'edit2.gif',
					'popup_onlyOpenIfSelected' => 1,
					'notNewRecords' => 1,
					'JSopenParams' => 'height=800,width=1000,status=0,menubar=0,scrollbars=1,resizable=yes'
				),
			)
		)
	),
	'tx_displaycontroller_emptyfilter2' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tt_content.tx_displaycontroller_emptyfilter',
		'config' => array (
			'type' => 'radio',
			'items' => array (
				array('LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tt_content.tx_displaycontroller_emptyfilter.I.0', ''),
				array('LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tt_content.tx_displaycontroller_emptyfilter.I.1', 'all'),
			),
		)
	),
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns, 1);

// Define showitem property for both plug-ins, depending on TYPO3 version
$showItem = '--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general, --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.header;header,';
$showItem .= '--div--;LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tabs.dataobjects, tx_displaycontroller_consumer;;;;1-1-1, tx_displaycontroller_provider;;displaycontroller_1;;2-2-2,  tx_displaycontroller_provider2;;displaycontroller_2;;2-2-2, tx_displaycontroller_emptyprovider2,';
$showItem .= '--div--;LLL:EXT:displaycontroller/Resources/Private/Language/locallang_db.xlf:tabs_options, pi_flexform,';
$showItem .= '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance, --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames, --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.textlayout;textlayout,';
$showItem .= '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.visibility;visibility, --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,';
$showItem .= '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.extended';
$GLOBALS['TCA']['tt_content']['types']['displaycontroller_pi1']['showitem'] = $showItem;
$GLOBALS['TCA']['tt_content']['types']['displaycontroller_pi2']['showitem'] = $showItem;

$GLOBALS['TCA']['tt_content']['palettes']['displaycontroller_1'] = array('showitem' => 'tx_displaycontroller_filtertype, tx_displaycontroller_datafilter, tx_displaycontroller_emptyfilter');
$GLOBALS['TCA']['tt_content']['palettes']['displaycontroller_2'] = array('showitem' => 'tx_displaycontroller_datafilter2, tx_displaycontroller_emptyfilter2');

// Register icons for content type
// Define classes and register icon files with Sprite Manager
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['displaycontroller_pi1'] =  'extensions-displaycontroller-type-controller';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['displaycontroller_pi2'] =  'extensions-displaycontroller-type-controller';
