<?php
namespace Tesseract\Displaycontroller\Service;

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

use Tesseract\Tesseract\Service\ControllerBase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Tesseract\Tesseract\Exception\MissingComponentException;

/**
 * Service for controller-type Tesseract component.
 *
 * @author Francois Suter (Cobweb) <typo3@cobweb.ch>
 * @package TYPO3
 * @subpackage tx_displaycontroller
 */
class ControllerService extends ControllerBase {
	/**
	 * Returns the primary provider related to the given display controller instance.
	 *
	 * @return \Tesseract\Tesseract\Service\ProviderBase
	 * @throws MissingComponentException
	 */
	public function getRelatedProvider() {
		// Get table where the relation to the provider is stored
		$mmTable = $GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_provider']['config']['MM'];
		// Get the provider-relation record
		$row = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
			'*',
			$mmTable,
			'uid_local = ' . intval($this->uid) . ' AND component = \'provider\' AND rank = 1'
		);
		if (empty($row)) {
			throw new MissingComponentException(
				'No provider found',
				1432298782
			);
        } else {
			// Create an instance of the appropriate service
			/** @var $provider \Tesseract\Tesseract\Service\ProviderBase */
			$provider = GeneralUtility::makeInstanceService(
				'dataprovider',
				$row['tablenames']
			);
			// NOTE: loadData() may throw an exception, but we just let it pass at this point
			$provider->loadData(
				array(
					'table' => $row['tablenames'],
					'uid' => $row['uid_foreign']
				)
			);
			return $provider;
        }
    }
}
