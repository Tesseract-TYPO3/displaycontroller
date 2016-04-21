<?php
namespace Tesseract\Displaycontroller\Controller;

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

use TYPO3\CMS\Core\Localization\Parser\LocallangXmlParser;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class that adds the wizard icon for the uncached plugin.
 *
 * @author Francois Suter <typo3@cobweb.ch>
 * @package TYPO3
 * @subpackage tx_displaycontroller
 */
class PluginNotCachedWizard
{

    /**
     * Processing the wizard items array.
     *
     * @param array $wizardItems The wizard items
     * @return array Modified array with wizard items
     */
    public function proc($wizardItems)
    {
        $wizardItems['plugins_tx_displaycontroller_pi2'] = array(
                'icon' => ExtensionManagementUtility::extRelPath('displaycontroller') . 'Resources/Public/Icons/WizardIcon.png',
                'title' => $GLOBALS['LANG']->sL('LLL:EXT:displaycontroller/Resources/Private/Language/locallang.xlf:pi2_title'),
                'description' => $GLOBALS['LANG']->sL('LLL:EXT:displaycontroller/Resources/Private/Language/locallang.xlf:pi2_plus_wiz_description'),
                'params' => '&defVals[tt_content][CType]=displaycontroller_pi2'
        );

        return $wizardItems;
    }
}