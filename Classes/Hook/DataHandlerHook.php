<?php
namespace Tesseract\Displaycontroller\Hook;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * DataHandler hook for the 'displaycontroller' extension.
 *
 * Provides a way to call up a FE page with the correct parameters when hitting the save and view
 * button in the BE.
 *
 * @todo: Check if this is still needed with TYPO3 CMS 7+, as it provides such a feature by default.
 *
 * @author Francois Suter (Cobweb) <typo3@cobweb.ch>
 * @package TYPO3
 * @subpackage tx_displaycontroller
 */
class DataHandlerHook {
	/**
	 * @var array Extension configuration
	 */
	protected $extensionConfiguration = array();

	public function __construct() {
		$this->extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['displaycontroller']);
	}

	/**
	 * Hooks into the DataHandler process to call up a page for preview when the "Save and view" button was clicked.
	 *
	 * @param string $status Status of the record
	 * @param string $table Name of the table
	 * @param mixed $id Id of the record (may be a string if the record is new)
	 * @param array $fieldArray Fields of the record
	 * @param DataHandler $parentObject Back-reference to the calling object
	 */
	public function processDatamap_afterDatabaseOperations($status, $table, $id, $fieldArray, DataHandler $parentObject) {
		// If the feature is activated, hook into the preview process to provide a valid preview link
		// (if the "save and view" button was clicked)
		if (isset($GLOBALS['_POST']['_savedokview_x']) && $this->extensionConfiguration['saveAndViewAction']) {
			// Get the actual id if the record is a new one
			if (!is_numeric($id)) {
				$id = $parentObject->substNEWwithIDs[$id];
			}
			// Get the Page TSconfig for the preview page
			$pageId = intval($GLOBALS['_POST']['popViewId']);
			$tsConfig = BackendUtility::getPagesTSconfig($pageId);
			// Act if some preview information is indeed defined
			if (isset($tsConfig['tx_displaycontroller.'][$table . '.'])) {
				// Change the preview page id to use the configured preview page, if defined
				// (otherwise it will stay on the current page)
				if (!empty($tsConfig['tx_displaycontroller.'][$table . '.']['previewPid'])) {
					$GLOBALS['_POST']['popViewId'] = intval($tsConfig['tx_displaycontroller.'][$table . '.']['previewPid']);
				}
				// Make sure the cache is not used
				$additionalParameters = '&no_cache=1';
				// If the parameters were not defined, use default
				if (empty($tsConfig['tx_displaycontroller.'][$table . '.']['parameters'])) {
					$moreAdditionalParameters = '&tx_displaycontroller[table]=###table###&tx_displaycontroller[showUid]=###id###&L=###lang###';
				} else {
					$moreAdditionalParameters = trim($tsConfig['tx_displaycontroller.'][$table . '.']['parameters']);
					// If the parameters don't start with "&", add it
					if (strpos($moreAdditionalParameters, '&') !== 0) {
						$moreAdditionalParameters = '&' . $moreAdditionalParameters;
					}
				}
				// Prepare replacements for the allowed markers
				$search = array('###id###', '###table###', '###lang###');
				$replacements = array($id, $table);
				// Add the language parameter, if needed
				if (isset($fieldArray['sys_language_uid'])) {
					$replacements[] = $fieldArray['sys_language_uid'];
				} else {
					$replacements[] = 0;
				}
				// Replace the markers and add the parameters
				$additionalParameters .= str_replace(
					$search,
					$replacements,
					$moreAdditionalParameters
				);
				// Assign the additional parameters to the pop-up data
				$GLOBALS['_POST']['popViewId_addParams'] = $additionalParameters . '&tx_displaycontroller_preview=1';
			}
		}
	}
}