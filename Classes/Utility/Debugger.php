<?php
namespace Tesseract\Displaycontroller\Utility;

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

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Debugging output for the 'displaycontroller' extension.
 *
 * @author Francois Suter (Cobweb) <typo3@cobweb.ch>
 * @package TYPO3
 * @subpackage tx_displaycontroller
 */
class Debugger implements SingletonInterface {
	/**
	 * @var PageRenderer Reference to the current page renderer object
	 */
	protected $pageRenderer;

	/**
	 * @var bool Flag to control output of unique content
	 */
	protected $firstCall = TRUE;

	/**
	 * @var string Inline CSS code
	 */
	protected $cssCode = '';

	/**
	 * @var string Path to debug script
	 */
	protected $jsFile;

	/**
	 * @var array Flash message class names
	 */
	protected $severityClasses;

	public function __construct(PageRenderer $pageRenderer) {
		$this->pageRenderer = $pageRenderer;
		// Prepare CSS code based on t3skin, if loaded
		if (ExtensionManagementUtility::isLoaded('t3skin')) {
			$this->cssCode = GeneralUtility::getUrl(ExtensionManagementUtility::extPath('displaycontroller') . 'Resources/Public/Styles/Debugger.css');
			$t3SkinPath = ExtensionManagementUtility::extPath('t3skin');
			$messageSkinningFile = $t3SkinPath  . 'Resources/Public/Css/visual/element_message.css';
			$pathToReplace = '../../../../icons';
			$this->cssCode .= GeneralUtility::getUrl($messageSkinningFile);
			// Adjust path to icons
			$replacement = GeneralUtility::locationHeaderUrl(TYPO3_mainDir . ExtensionManagementUtility::extRelPath('t3skin') . 'icons');
			$this->cssCode = str_replace($pathToReplace, $replacement, $this->cssCode);
			$this->jsFile = GeneralUtility::locationHeaderUrl(TYPO3_mainDir . ExtensionManagementUtility::extRelPath('displaycontroller')) . 'Resources/Public/JavaScript/Debugger.js';
		}
	}

	/**
	 * Renders all messages and dumps their related data
	 *
	 * @param array $messageQueue List of messages to display
	 * @return string Debug output
	 */
	public function render(array $messageQueue) {
		$debugOutput = '';
		if (count($messageQueue) > 0) {
			// If this is the first debug call, write the necessary CSS code
			// and load the related JS library
			if ($this->firstCall) {
				$debugOutput .= '<style>' . $this->cssCode . '</style>';
				$debugOutput .= '<script src="' . $this->jsFile . '" type="text/javascript"></script>';
				$this->firstCall = FALSE;
			}
			// Prepare the output and return it
			$icons = '';
			foreach ($messageQueue as $messageData) {
				/** @var \TYPO3\CMS\Core\Messaging\FlashMessage $messageObject */
				$messageObject = $messageData['message'];
				// Choose the log method based on severity
				switch ($messageObject->getSeverity()) {
					case 2:
						$logMethod = 'error';
						break;
					case 1:
						$logMethod = 'warn';
						break;
					default:
						$logMethod = 'log';
				}

				// Prepare the output, as a clickable icon and a message
				$label = '<p><strong>' . $messageObject->getTitle() . '</strong>: ' . $messageObject->getMessage() . '</p>';
				$debugLink = '
					<span class="debug-message ' . $messageObject->getClass() . '"' .
					' data-debug="' . urlencode(json_encode($messageData['data'])) . '"' .
					' data-debug-header="' . addslashes($messageObject->getTitle() . ': ' . $messageObject->getMessage()) . '"' .
					' data-method="' . $logMethod . '"' .
					' onclick="DisplaycontrollerDebugger.dumpDebugData()">&nbsp;</span>
				';
				$icons .= '<div class="icon-group">' . $debugLink . $label . '</div>';
			}
			// Wrap all the icons
			$debugOutput .= '<div class="tx_displaycontroller_debug">' . $icons . '</div>';
		}

		return $debugOutput;
	}
}
