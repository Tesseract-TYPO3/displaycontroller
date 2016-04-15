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
 * Class that adds the wizard icon for the cached plugin.
 *
 * @author Francois Suter <typo3@cobweb.ch>
 * @package TYPO3
 * @subpackage tx_displaycontroller
 */
class PluginCachedWizard
{

    /**
     * Processing the wizard items array.
     *
     * @param array $wizardItems The wizard items
     * @return array Modified array with wizard items
     */
    public function proc($wizardItems)
    {
        $LL = $this->includeLocalLang();

        $wizardItems['plugins_tx_displaycontroller_pi1'] = array(
                'icon' => ExtensionManagementUtility::extRelPath('displaycontroller') . 'Resources/Public/Icons/WizardIcon.png',
                'title' => $GLOBALS['LANG']->getLLL('pi1_title', $LL),
                'description' => $GLOBALS['LANG']->getLLL('pi1_plus_wiz_description', $LL),
                'params' => '&defVals[tt_content][CType]=displaycontroller_pi1'
        );

        return $wizardItems;
    }

    /**
     * Reads the [extDir]/locallang.xml and returns the $LOCAL_LANG array found in that file.
     *
     * @return array The array with language labels
     */
    protected function includeLocalLang()
    {
        $llFile = ExtensionManagementUtility::extPath('displaycontroller') . 'locallang.xml';
        /** @var LocallangXmlParser $l10nParser */
        $l10nParser = GeneralUtility::makeInstance(LocallangXmlParser::class);
        $LOCAL_LANG = $l10nParser->getParsedData($llFile, $GLOBALS['LANG']->lang);

        return $LOCAL_LANG;
    }
}
