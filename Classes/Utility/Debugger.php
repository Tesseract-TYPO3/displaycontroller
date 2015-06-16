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
		$extensionRelativePath = ExtensionManagementUtility::extRelPath('displaycontroller');
		// Load the relevant CSS and JS files (making sure they are not concatenated)
		$pageRenderer->addCssFile(
			$extensionRelativePath . 'Resources/Public/Styles/font-awesome/css/font-awesome.min.css',
			'stylesheet',
			'screen',
			'',
			FALSE,
			FALSE,
			'',
			TRUE
		);
		$pageRenderer->addCssFile(
			$extensionRelativePath . 'Resources/Public/Styles/Debugger.css',
			'stylesheet',
			'screen',
			'',
			FALSE,
			FALSE,
			'',
			TRUE
		);
		$pageRenderer->addJsFile(
			$extensionRelativePath . 'Resources/Public/JavaScript/Debugger.js',
			'text/javascript',
			FALSE,
			FALSE,
			'',
			TRUE
		);
	}

	/**
	 * Renders all messages and dumps their related data.
	 *
	 * @param array $messageQueue List of messages to display
	 * @return string Debug output
	 */
	public function render(array $messageQueue) {
		$debugOutput = '';
		if (count($messageQueue) > 0) {
			// Prepare the output and return it
			$icons = '';
			foreach ($messageQueue as $messageData) {
				/** @var \TYPO3\CMS\Core\Messaging\FlashMessage $messageObject */
				$messageObject = $messageData['message'];
				// Choose the display classes and the log method based on severity
				$logMethod = 'log';
				switch ($messageObject->getSeverity()) {
					case 2:
						$logMethod = 'error';
						$classes = 'debug-message debug-error fa fa-times-circle';
						break;
					case 1:
						$logMethod = 'warn';
						$classes = 'debug-message debug-warning fa fa-exclamation-triangle';
						break;
					case 0:
						$classes = 'debug-message debug-success fa fa-check-circle';
						break;
					case -1:
						$classes = 'debug-message debug-info fa fa-info-circle';
						break;
					default:
						$classes = 'debug-message debug-note fa fa-comment';
				}

				// Prepare the output, as a clickable icon and a message
				$label = '<p><strong>' . $messageObject->getTitle() . '</strong>: ' . $messageObject->getMessage() . '</p>';
				$debugLink = '
					<i class="' . $classes . '"' .
					' data-debug="' . urlencode(json_encode($messageData['data'])) . '"' .
					' data-debug-header="' . addslashes($messageObject->getTitle() . ': ' . $messageObject->getMessage()) . '"' .
					' data-method="' . $logMethod . '"' .
					' onclick="DisplaycontrollerDebugger.dumpDebugData()"></i>
				';
				$icons .= '<div class="icon-group">' . $debugLink . $label . '</div>';
			}
			// Wrap all the icons
			$debugOutput .= '<div class="tx_displaycontroller_debug">' . $icons . '</div>';
		}

		return $debugOutput;
	}
}
