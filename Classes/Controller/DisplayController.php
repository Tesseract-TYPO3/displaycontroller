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

use Cobweb\Expressions\ExpressionParser;
use Tesseract\Displaycontroller\Utility\Debugger;
use Tesseract\Tesseract\Component\DataProviderInterface;
use Tesseract\Tesseract\Exception\InvalidComponentException;
use Tesseract\Tesseract\Exception\MissingComponentException;
use Tesseract\Tesseract\Frontend\PluginControllerBase;
use Tesseract\Tesseract\Tesseract;
use TYPO3\CMS\Core\Localization\LocalizationFactory;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Plugin 'Display Controller (cached)' for the 'displaycontroller' extension.
 *
 * @author Francois Suter (Cobweb) <typo3@cobweb.ch>
 * @package TYPO3
 * @subpackage tx_displaycontroller
 */
class DisplayController extends PluginControllerBase
{
    /**
     * @var string Prefix for GET/POST variables
     */
    public $prefixId = 'tx_displaycontroller';

    /**
     * @var string Extension key
     */
    public $extKey = 'displaycontroller';

    /**
     * @var \Tesseract\Tesseract\Service\FrontendConsumerBase Reference to the frontend Data Consumer object
     */
    protected $consumer;

    /**
     * @var bool FALSE if Data Consumer should not receive the structure
     */
    protected $passStructure = true;

    /**
     * @var array General extension configuration
     */
    protected $extensionConfiguration = array();

    /**
     * @var bool Debug to output or not
     */
    protected $debugToOutput = false;

    /**
     * @var bool Debug to devlog or not
     */
    protected $debugToDevLog = false;

    /**
     * @var int Minimum level of message to be logged. Default is all.
     */
    protected $debugMinimumLevel = -1;

    public function __construct()
    {
        // Read the general configuration and initialize the debug flags
        $this->extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
        if (!empty($this->extensionConfiguration['debug'])) {
            $this->setDebugOptions($this->extensionConfiguration['debug']);
        }
        // Make sure the minimum debugging level is set and has a correct value
        if (isset($this->extensionConfiguration['minDebugLevel'])) {
            $level = (int)$this->extensionConfiguration['minDebugLevel'];
            if ($level >= -1 && $level <= 3) {
                $this->debugMinimumLevel = $level;
            }
        }
    }

    /**
     * Sets the proper debug options, given some flag.
     *
     * @param string $flag Debug flag. Expected values are "output", "devlog", "both" or "none"
     * @return void
     */
    protected function setDebugOptions($flag)
    {
        $this->debug = true;
        switch ($flag) {
            case 'output':
                $this->debugToOutput = true;
                break;
            case 'devlog':
                $this->debugToDevLog = true;
                break;
            case 'both':
                $this->debugToOutput = true;
                $this->debugToDevLog = true;
                break;

            // Turn off all debugging if no valid value was entered
            default:
                $this->debug = false;
                $this->debugToOutput = false;
                $this->debugToDevLog = false;
        }
    }

    /**
     * Overrides the default pi_loadLL method, as displaycontroller provides two plugins sharing the same locallang files.
     *
     * NOTE: TypoScript override of language labels is not implemented.
     *
     * @return void
     */
    public function pi_loadLL()
    {
        /** @var $languageFactory LocalizationFactory */
        $languageFactory = GeneralUtility::makeInstance(LocalizationFactory::class);
        $languageFile = 'EXT:' . $this->extKey . '/Resources/Private/Language/locallang.xlf';
        // Read the strings in the required charset
        $this->LOCAL_LANG = $languageFactory->getParsedData(
                $languageFile,
                $this->LLkey,
                $GLOBALS['TSFE']->renderCharset
        );
        if ($this->altLLkey) {
            $this->LOCAL_LANG = $languageFactory->getParsedData(
                    $languageFile,
                    $this->altLLkey,
                    $GLOBALS['TSFE']->renderCharset
            );
        }
        $this->LOCAL_LANG_loaded = 1;
    }

    /**
     * This method performs various initialisations
     *
     * @param array $conf TypoScript configuration array
     * @return void
     */
    protected function init($conf)
    {
        // Merge the configuration of the pi* plugin with the general configuration
        // defined with plugin.tx_displaycontroller (if defined)
        if (isset($GLOBALS['TSFE']->tmpl->setup['plugin.'][$this->prefixId . '.'])) {
            $this->conf = $GLOBALS['TSFE']->tmpl->setup['plugin.'][$this->prefixId . '.'];
            ArrayUtility::mergeRecursiveWithOverrule($this->conf, $conf);
        } else {
            $this->conf = $conf;
        }
        // Load flexform options
        $this->pi_initPIflexForm();
        if (is_array($this->cObj->data['pi_flexform']) && array_key_exists('data', $this->cObj->data['pi_flexform']) && count($this->cObj->data['pi_flexform']['data']) > 0) {
            foreach ($this->cObj->data['pi_flexform']['data'] as $sheet => $langData) {
                foreach ($langData as $fields) {
                    foreach ($fields as $field => $value) {
                        $value = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], $field, $sheet);
                        $this->conf[$field] = $value;
                    }
                }
            }
        }
        // Check local debug flag (overrides main one)
        if (!empty($this->conf['debug']) && $this->conf['debug'] !== 'none') {
            $this->setDebugOptions($this->conf['debug']);
        }
        // Override standard piVars definition
        $this->piVars = GeneralUtility::_GPmerged($this->prefixId);
        // Load the language labels
        $this->pi_loadLL();
        // Show hidden records, if logged in BE and  previewing a record
        if (isset($GLOBALS['BE_USER']) && GeneralUtility::_GP('tx_displaycontroller_preview')) {
            // We show all hidden records
            $GLOBALS['TSFE']->showHiddenRecords = 1;
        }
        // Finally load some additional data into the parser
        $this->loadParserData();
    }

    /**
     * Loads additional data into the parser, so that it is available for Data Filters
     * and other places where expressions are used.
     *
     * @return void
     */
    protected function loadParserData()
    {
        // Load plug-in's variables into the parser
        ExpressionParser::setVars($this->piVars);
        // Load specific configuration into the extra data
        $extraData = array();
        if (is_array($this->conf['context.'])) {
            $extraData = GeneralUtility::removeDotsFromTS($this->conf['context.']);
        }
        // Allow loading of additional extra data from hooks
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['setExtraDataForParser'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['setExtraDataForParser'] as $className) {
                $hookObject = GeneralUtility::getUserObj($className);
                $extraData = $hookObject->setExtraDataForParser($extraData, $this);
            }
        }
        // Add the extra data to the parser and to the TSFE
        if (count($extraData) > 0) {
            ExpressionParser::setExtraData($extraData);
            // TODO: this should not stay
            // This was added so that context can be available in the local TS of the templatedisplay
            // We must find another solution so that the templatedisplay's TS can use the tx_expressions_parser
            $GLOBALS['TSFE']->tesseract = $extraData;
        }
    }

    /**
     * Drives the rendering by managing interaction of all components.
     *
     * This method uses a controller object to find the appropriate Data Provider.
     * The data structure from the Data Provider is then passed to the appropriate Data Consumer for rendering.
     *
     * @param string $content Current content (unused)
     * @param array $conf The plugin's TS configuration
     * @return string The content to display on the website
     */
    public function main($content, $conf)
    {
        $this->init($conf);
        $content = '';
        $filter = array();

        // Handle the secondary provider first
        $secondaryProvider = $this->initializeSecondaryProvider();

        // Handle the primary provider
        // Define the filter (if any)
        try {
            $filter = $this->definePrimaryFilter();
            $this->addMessage(
                    $this->extKey,
                    $this->pi_getLL('info.calculated_filter'),
                    $this->pi_getLL('info.primary_filter'),
                    FlashMessage::INFO,
                    $filter
            );
        } catch (\Exception $e) {
            // Issue error if a problem occurred with the filter
            $this->addMessage(
                    $this->extKey,
                    $e->getMessage() . ' (' . $e->getCode() . ')',
                    $this->pi_getLL('error.primary_filter'),
                    FlashMessage::ERROR
            );
        }

        // Get the primary data provider
        try {
            $primaryProviderData = $this->getComponentData('provider', 1);
            // Get the primary data provider, if necessary
            if ($this->passStructure) {
                try {
                    $primaryProvider = $this->getDataProvider(
                            $primaryProviderData,
                            isset($secondaryProvider) ? $secondaryProvider : null
                    );
                    $primaryProvider->setDataFilter($filter);
                    // If the secondary provider exists and the option was chosen
                    // to display everything in the primary provider, no matter what
                    // the result from the secondary provider, make sure to set
                    // the empty data structure flag to false, otherwise nothing will display
                    if (isset($secondaryProvider) && !empty($this->cObj->data['tx_displaycontroller_emptyprovider2'])) {
                        $primaryProvider->setEmptyDataStructureFlag(false);
                    }
                } // Something happened, skip passing the structure to the Data Consumer
                catch (\Exception $e) {
                    $this->passStructure = false;
                    $this->addMessage(
                            $this->extKey,
                            $e->getMessage() . ' (' . $e->getCode() . ')',
                            $this->pi_getLL('error.primary_provider_interrupt'),
                            FlashMessage::WARNING
                    );
                }
            }

            // Get the data consumer
            try {
                // Get the consumer's information
                $consumerData = $this->getComponentData('consumer');
                try {
                    // Get the corresponding Data Consumer component
                    $this->consumer = Tesseract::getComponent(
                            'dataconsumer',
                            $consumerData['tablenames'],
                            array('table' => $consumerData['tablenames'], 'uid' => $consumerData['uid_foreign']),
                            $this
                    );
                    // Pass appropriate TypoScript to consumer
                    $typoscriptKey = $this->consumer->getTypoScriptKey();
                    $typoscriptConfiguration = isset($GLOBALS['TSFE']->tmpl->setup['plugin.'][$typoscriptKey]) ? $GLOBALS['TSFE']->tmpl->setup['plugin.'][$typoscriptKey] : array();
                    $this->consumer->setTypoScript($typoscriptConfiguration);
                    $this->consumer->setDataFilter($filter);
                    // If the structure should be passed to the consumer, do it now and get the rendered content
                    if ($this->passStructure) {
                        // Check if Data Provider can provide the right structure for the Data Consumer
                        if ($primaryProvider->providesDataStructure($this->consumer->getAcceptedDataStructure())) {
                            // Get the data structure and pass it to the consumer
                            $structure = $primaryProvider->getDataStructure();
                            // Check if there's a redirection configuration
                            $this->handleRedirection($structure);
                            // Pass the data structure to the consumer
                            $this->consumer->setDataStructure($structure);
                            // Start the processing and get the rendered data
                            $this->consumer->startProcess();
                            $content = $this->consumer->getResult();
                        } else {
                            $this->addMessage(
                                    $this->extKey,
                                    $this->pi_getLL('error.incompatible_provider_consumer'),
                                    '',
                                    FlashMessage::ERROR
                            );
                        }
                    } else {
                        // If no structure should be passed (see defineFilter()),
                        // don't pass structure :-), but still do the rendering
                        // (this gives the opportunity to the consumer to render its own error content, for example)
                        // This is achieved by not calling startProcess(), but just getResult()
                        $content = $this->consumer->getResult();
                    }
                } catch (\Exception $e) {
                    $this->addMessage(
                            $this->extKey,
                            $e->getMessage() . ' (' . $e->getCode() . ')',
                            $this->pi_getLL('error.no_consumer'),
                            FlashMessage::ERROR
                    );
                }
            } catch (\Exception $e) {
                $this->addMessage(
                        $this->extKey,
                        $e->getMessage() . ' (' . $e->getCode() . ')',
                        $this->pi_getLL('error.no_consumer'),
                        FlashMessage::ERROR
                );
            }
        } catch (\Exception $e) {
            $this->addMessage(
                    $this->extKey,
                    $e->getMessage() . ' (' . $e->getCode() . ')',
                    $this->pi_getLL('error.no_primary_provider'),
                    FlashMessage::ERROR
            );
        }

        // If debugging to output is active, prepend content with debugging messages
        $content = $this->writeDebugOutput() . $content;
        return $content;
    }

    /**
     * Initializes the secondary provider, possibly with its secondary filter.
     *
     * @return null|\Tesseract\Tesseract\Component\DataProviderInterface
     */
    protected function initializeSecondaryProvider()
    {
        $secondaryProvider = null;
        if (!empty($this->cObj->data['tx_displaycontroller_provider2'])) {
            // Get the secondary data filter, if any
            $secondaryFilter = $this->getEmptyFilter();
            if (!empty($this->cObj->data['tx_displaycontroller_datafilter2'])) {
                $secondaryFilter = $this->defineAdvancedFilter('secondary');
                $this->addMessage(
                        $this->extKey,
                        $this->pi_getLL('info.calculated_filter'),
                        $this->pi_getLL('info.secondary_filter'),
                        FlashMessage::INFO,
                        $secondaryFilter
                );
            }
            // Get the secondary provider if necessary,
            // i.e. if the process was not blocked by the advanced filter (by setting the passStructure flag to false)
            if ($this->passStructure) {
                try {
                    // Get the secondary provider's information
                    $secondaryProviderData = $this->getComponentData('provider', 2);
                    try {
                        // Get the corresponding component
                        $secondaryProviderObject = $this->getDataProvider($secondaryProviderData);
                        $secondaryProvider = clone $secondaryProviderObject;
                        $secondaryProvider->setDataFilter($secondaryFilter);
                    } // Something happened, skip passing the structure to the Data Consumer
                    catch (\Exception $e) {
                        $this->passStructure = false;
                        $this->addMessage(
                                $this->extKey,
                                $e->getMessage() . ' (' . $e->getCode() . ')',
                                $this->pi_getLL('error.secondary_provider_interrupt'),
                                FlashMessage::WARNING
                        );
                    }
                } catch (\Exception $e) {
                    $this->addMessage(
                            $this->extKey,
                            $e->getMessage() . ' (' . $e->getCode() . ')',
                            $this->pi_getLL('error.no_secondary_provider'),
                            FlashMessage::ERROR
                    );
                }
            }
        }
        return $secondaryProvider;
    }

    /**
     * Defines the Data Filter to use depending on the values stored in the database record.
     *
     * It returns the Data Filter structure.
     *
     * @return array Data Filter structure
     */
    protected function definePrimaryFilter()
    {
        $filter = $this->getEmptyFilter();
        if (!empty($this->cObj->data['tx_displaycontroller_filtertype'])) {
            switch ($this->cObj->data['tx_displaycontroller_filtertype']) {

                // Simple filter for single view
                // We expect the "table" and "showUid" parameters and assemble a filter based on those values
                case 'single':
                    $filter = array();
                    $filter['filters'] = array(
                            0 => array(
                                    'table' => $this->piVars['table'],
                                    'field' => 'uid',
                                    'conditions' => array(
                                            0 => array(
                                                    'operator' => '=',
                                                    'value' => $this->piVars['showUid'],
                                            )
                                    )
                            )
                    );
                    break;

                // Simple filter for list view
                case 'list':
                    $filter = $this->defineListFilter();
                    break;

                // Handle advanced data filters
                case 'filter':
                    $filter = $this->defineAdvancedFilter();
                    break;
            }
        }
        return $filter;
    }

    /**
     * This method is used to return a clean, empty filter
     *
     * @return    array    Empty filter structure
     */
    protected function getEmptyFilter()
    {
        return array('filters' => array());
    }

    /**
     * Initialises the filter.
     *
     * This can be either an empty array or some structure already stored in cache.
     *
     * @param mixed $key A string or a number that identifies a given filter (for example, the uid of a DataFilter record)
     * @return array A filter structure or an empty array
     */
    protected function initFilter($key = '')
    {
        $filter = array();
        $clearCache = isset($this->piVars['clear_cache']) ? $this->piVars['clear_cache'] : GeneralUtility::_GP('clear_cache');
        // If cache is not cleared, retrieve cached filter
        if (empty($clearCache)) {
            if (empty($key)) {
                $key = 'default';
            }
            $cacheKey = $this->prefixId . '_filterCache_' . $key . '_' . $this->cObj->data['uid'] . '_' . $GLOBALS['TSFE']->id;
            $cache = $GLOBALS['TSFE']->fe_user->getKey('ses', $cacheKey);
            if (isset($cache)) {
                $filter = $cache;
            }
        }
        // Declare hook for extending the initialisation of the filter
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['extendInitFilter'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['extendInitFilter'] as $className) {
                $hookObject = GeneralUtility::getUserObj($className);
                $filter = $hookObject->extendInitFilter($filter, $this);
            }
        }
        return $filter;
    }

    /**
     * Defines the filter for the default, simple list view.
     *
     * It expects two parameters, "limit" and "page" ,for browsing the list's pages.
     * It will also consider a default sorting scheme represented by the "sort" and "order" parameters.
     *
     * @return array A filter structure
     */
    protected function defineListFilter()
    {
        // Initialise the filter
        $filter = $this->initFilter();
        if (!isset($filter['limit'])) {
            $filter['limit'] = array();
        }

        // Handle the page browsing variables
        if (isset($this->piVars['max'])) {
            $filter['limit']['max'] = $this->piVars['max'];
        }
        $filter['limit']['offset'] = isset($this->piVars['page']) ? $this->piVars['page'] : 0;

        // If the limit is still empty after that, consider the default value from TypoScript
        if (empty($filter['limit']['max'])) {
            $filter['limit']['max'] = $this->conf['listView.']['limit'];
        }

        // Handle sorting variables
        if (isset($this->piVars['sort'])) {
            $sortParts = GeneralUtility::trimExplode('.', $this->piVars['sort'], 1);
            $table = '';
            $field = $sortParts[0];
            if (count($sortParts) == 2) {
                $table = $sortParts[0];
                $field = $sortParts[1];
            }
            $order = isset($this->piVars['order']) ? $this->piVars['order'] : 'asc';
            $orderby = array(0 => array('table' => $table, 'field' => $field, 'order' => $order));
            $filter['orderby'] = $orderby;

            // If there were no variables, check a default sorting configuration
        } elseif (!empty($this->conf['listView.']['sort'])) {
            $sortParts = GeneralUtility::trimExplode('.', $this->conf['listView.']['sort'], 1);
            $table = '';
            $field = $sortParts[0];
            if (count($sortParts) == 2) {
                $table = $sortParts[0];
                $field = $sortParts[1];
            }
            $order = isset($this->conf['listView.']['order']) ? $this->conf['listView.']['order'] : 'asc';
            $orderby = array(0 => array('table' => $table, 'field' => $field, 'order' => $order));
            $filter['orderby'] = $orderby;
        }

        // Save the filter's hash in session
        $cacheKey = $this->prefixId . '_filterCache_default_' . $this->cObj->data['uid'] . '_' . $GLOBALS['TSFE']->id;
        $GLOBALS['TSFE']->fe_user->setKey('ses', $cacheKey, $filter);

        return $filter;
    }

    /**
     * Gets a filter structure from a referenced Data Filter.
     *
     * @param string $type Type of filter, either primary (default) or secondary
     * @return array
     * @throws MissingComponentException
     */
    protected function defineAdvancedFilter($type = 'primary')
    {
        // Define rank based on call parameter
        $rank = 1;
        $checkField = 'tx_displaycontroller_emptyfilter';
        if ($type === 'secondary') {
            $rank = 2;
            $checkField = 'tx_displaycontroller_emptyfilter2';
        }
        // Get the data filter
        try {
            // Get the filter's information
            $filterData = $this->getComponentData('filter', $rank);
            // Get the corresponding Data Filter component
            /** @var $datafilter \Tesseract\Tesseract\Component\DataFilterInterface */
            $datafilter = Tesseract::getComponent(
                    'datafilter',
                    $filterData['tablenames'],
                    array('table' => $filterData['tablenames'], 'uid' => $filterData['uid_foreign']),
                    $this
            );
            // Initialise the filter
            $filter = $this->initFilter($filterData['uid_foreign']);
            // Pass the cached filter to the DataFilter
            $datafilter->setFilter($filter);
            try {
                $filter = $datafilter->getFilterStructure();
                // Store the filter in session
                $cacheKey = $this->prefixId . '_filterCache_' . $filterData['uid_foreign'] . '_' . $this->cObj->data['uid'] . '_' . $GLOBALS['TSFE']->id;
                $GLOBALS['TSFE']->fe_user->setKey('ses', $cacheKey, $filter);
                // Here handle case where the "filters" part of the filter is empty
                // If the display nothing flag has been set, we must somehow stop the process
                // The Data Provider should not even be called at all
                // and the Data Consumer should receive an empty (special?) structure
                if ($datafilter->isFilterEmpty() && empty($this->cObj->data[$checkField])) {
                    $this->passStructure = false;
                }
            } catch (\Exception $e) {
                $this->addMessage(
                        $this->extKey,
                        $e->getMessage() . ' (' . $e->getCode() . ')',
                        $this->pi_getLL('error.get_filter'),
                        FlashMessage::WARNING
                );
            }
        } catch (\Exception $e) {
            throw new MissingComponentException($this->pi_getLL('exception.no_filter'), 1326454151);
        }
        return $filter;
    }

    /**
     * Checks whether a redirection is defined.
     *
     * If yes and if the conditions match, it performs the redirection.
     *
     * @param array $structure A Standardised Data Structure
     * @return void
     */
    protected function handleRedirection($structure)
    {
        if (isset($this->conf['redirect.']) && !empty($this->conf['redirect.']['enable'])) {
            // Initialisations
            $redirectConfiguration = $this->conf['redirect.'];
            // Load general SDS information into registers
            $GLOBALS['TSFE']->register['sds.totalCount'] = $structure['totalCount'];
            $GLOBALS['TSFE']->register['sds.count'] = $structure['count'];
            // Create a local cObject for handling the redirect configuration
            /** @var $localCObj ContentObjectRenderer */
            $localCObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            // If there's at least one record, load it into the cObject
            if ($structure['count'] > 0) {
                $localCObj->start($structure['records'][0]);
            }

            // First interpret the enable property
            $enable = false;
            if (!empty($redirectConfiguration['enable'])) {
                if (isset($this->conf['redirect.']['enable.'])) {
                    $enable = $this->cObj->stdWrap($this->conf['redirect.']['enable'],
                            $this->conf['redirect.']['enable.']);
                } else {
                    $enable = $this->conf['redirect.']['enable'];
                }
            }

            // If the redirection is indeed enabled, continue
            if ($enable) {
                // Get the result of the condition
                $condition = false;
                if (isset($redirectConfiguration['condition.'])) {
                    $condition = $localCObj->checkIf($redirectConfiguration['condition.']);
                }
                // If the condition was true, calculate the URL
                if ($condition) {
                    $url = '';
                    if (isset($redirectConfiguration['url.'])) {
                        $redirectConfiguration['url.']['returnLast'] = 'url';
                        $url = $localCObj->typoLink('', $redirectConfiguration['url.']);
                    }
                    header('Location: ' . GeneralUtility::locationHeaderUrl($url));
                }
            }
        }
    }

    /**
     * Retrieves information about a component related to the controller
     * An exception is thrown if none is found
     *
     * @param string $component Type of component (provider, consumer, filter)
     * @param integer $rank Level of the component (1 = primary, 2 = secondary)
     * @return array
     * @throws MissingComponentException
     */
    protected function getComponentData($component, $rank = 1)
    {
        // Assemble base WHERE clause
        $whereClause = 'component = ' . $this->getDatabaseConnection()->fullQuoteStr($component,
                        'tx_displaycontroller_components_mm') . ' AND rank = ' . (int)$rank;
        // Select the right uid for building the relation
        // If a _ORIG_uid is defined (i.e. we're in a workspace), use it preferentially
        // Otherwise, take the localized uid (i.e. we're using a translation), if it exists
        $referenceUid = $this->cObj->data['uid'];
        if (!empty($this->cObj->data['_ORIG_uid'])) {
            $referenceUid = $this->cObj->data['_ORIG_uid'];
        } elseif (!empty($this->cObj->data['_LOCALIZED_UID'])) {
            $referenceUid = $this->cObj->data['_LOCALIZED_UID'];
        }
        $where = $whereClause . ' AND uid_local = ' . (int)$referenceUid;
        // Query the database and return the fetched data
        // If the query fails or turns up no results, throw an exception
        $row = $this->getDatabaseConnection()->exec_SELECTgetSingleRow(
                '*',
                'tx_displaycontroller_components_mm',
                $where
        );
        if (empty($row)) {
            $message = sprintf(
                    $this->pi_getLL('exception.no_component'),
                    $component,
                    $rank
            );
            throw new MissingComponentException(
                    $message,
                    1265577739
            );
        } else {
            $componentData = $row;
        }
        return $componentData;
    }

    /**
     * Gets a data provider.
     *
     * If a secondary provider is defined, it is fed into the first one.
     *
     * @param array $providerInfo Information about a provider related to the controller
     * @param DataProviderInterface $secondaryProvider An instance of an object with a DataProvider interface
     * @throws \Exception
     * @return DataProviderInterface Object with a DataProvider interface
     */
    public function getDataProvider($providerInfo, DataProviderInterface $secondaryProvider = null)
    {
        // Get the related data providers
        $numProviders = count($providerInfo);
        if ($numProviders == 0) {
            // No provider, throw exception
            throw new MissingComponentException($this->pi_getLL('exception.no_provider'), 1269414211);
        } else {
            // Get the Data Provider Component
            /** @var $provider DataProviderInterface */
            $provider = Tesseract::getComponent(
                    'dataprovider',
                    $providerInfo['tablenames'],
                    array('table' => $providerInfo['tablenames'], 'uid' => $providerInfo['uid_foreign']),
                    $this
            );
            // If a secondary provider is defined and the types are compatible,
            // load it into the newly defined provider
            if (isset($secondaryProvider)) {
                if ($secondaryProvider->providesDataStructure($provider->getAcceptedDataStructure())) {
                    $inputDataStructure = $secondaryProvider->getDataStructure();
                    // If the secondary provider returned no list of items,
                    // force provider to return an empty structure
                    if ($inputDataStructure['count'] == 0) {
                        $provider->setEmptyDataStructureFlag(true);

                        // Otherwise pass structure to the provider
                    } else {
                        $provider->setDataStructure($inputDataStructure);

                    }
                    // Providers are not compatible, throw exception
                } else {
                    throw new InvalidComponentException($this->pi_getLL('exception.incompatible_providers'),
                            1269414231);
                }
            }
            return $provider;
        }
    }

    // Override tx_tesseract_pidatacontroller_output interface methods

    /**
     * Adds a debugging message to the controller's internal message queue
     *
     * @param string $key A key identifying the calling component (typically an extension's key)
     * @param string $message Text of the message
     * @param string $title An optional title for the message
     * @param int $status A status/severity level for the message, based on the class constants from FlashMessage
     * @param mixed $debugData An optional variable containing additional debugging information
     * @return void
     */
    public function addMessage($key, $message, $title = '', $status = FlashMessage::INFO, $debugData = null)
    {
        // Store the message only if debugging is active
        if ($this->debug) {
            // Validate status
            // Fall back to default if invalid
            $status = (int)$status;
            if ($status < FlashMessage::NOTICE || $status > FlashMessage::ERROR) {
                $status = FlashMessage::INFO;
            }
            // Match status to devLog levels
            // (which follow a more logical progression than Flash Message levels)
            switch ($status) {
                case FlashMessage::OK:
                    $level = -1;
                    break;
                case FlashMessage::NOTICE:
                    $level = 1;
                    break;
                default:
                    $level = $status + 1;
            }
            // Actually store the message only if it meets the minimum severity level
            if ($level >= $this->debugMinimumLevel) {
                // Prepend title, if any, with key
                $fullTitle = '[' . $key . ']' . ((empty($title)) ? '' : ' ' . $title);
                // The message data that corresponds to the Flash Message is stored directly as a Flash Message object,
                // as this performs input validation on the data
                /** @var $flashMessage FlashMessage */
                $flashMessage = GeneralUtility::makeInstance(
                        FlashMessage::class,
                        $message,
                        $fullTitle,
                        $status
                );
                $this->messageQueue[] = array(
                        'message' => $flashMessage,
                        'data' => $debugData
                );
                // Additionally write the message to the devLog if needed
                if ($this->debugToDevLog) {
                    // Make sure debug data is either NULL or array
                    $extraData = null;
                    if ($debugData !== null) {
                        if (is_array($debugData)) {
                            $extraData = $debugData;
                        } else {
                            $extraData = array($debugData);
                        }
                    }
                    GeneralUtility::devLog($flashMessage->getTitle() . ': ' . $flashMessage->getMessage(), $key, $level,
                            $extraData);
                }
            }
        }
    }

    /**
     * Prepares the debugging output, if so configured, and returns it.
     *
     * @return string HTML to output
     */
    protected function writeDebugOutput()
    {
        $output = '';
        // Output only if activated and if a BE user is logged in or the current IP address
        // matches the devIPMask
        if ($this->debugToOutput && (isset($GLOBALS['BE_USER']) || GeneralUtility::cmpIP(GeneralUtility::getIndpEnv('REMOTE_ADDR'),
                                $GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask']))
        ) {
            /** @var $debugger Debugger */
            $debugger = null;
            // If a custom debugging class is declared, get an instance of it
            if (!empty($this->extensionConfiguration['debugger'])) {
                try {
                    $debugger = GeneralUtility::makeInstance(
                            $this->extensionConfiguration['debugger'],
                            $GLOBALS['TSFE']->getPageRenderer()
                    );
                } catch (\Exception $e) {
                    $this->addMessage(
                            $this->extKey,
                            $this->pi_getLL('error.no_custom_debugger_info'),
                            $this->pi_getLL('error.no_custom_debugger'),
                            FlashMessage::WARNING
                    );
                }
            }
            // If no custom debugger class is defined or if it was not of the right type,
            // instantiate the default class
            if ($debugger === null || !($debugger instanceof Debugger)) {
                $debugger = GeneralUtility::makeInstance(
                        Debugger::class,
                        $GLOBALS['TSFE']->getPageRenderer()
                );
            }
            $output = $debugger->render($this->messageQueue);
        }
        return $output;
    }

    /**
     * Returns the global database object.
     *
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}