<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "displaycontroller".
 *
 * Auto generated 06-06-2017 16:40
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
  'title' => 'Standard Controller - Tesseract project',
  'description' => 'This FE plugin manages relations between Tesseract components and produces output in the FE. More info on http://www.typo3-tesseract.com/',
  'category' => 'plugin',
  'author' => 'Francois Suter (Cobweb)',
  'author_email' => 'typo3@cobweb.ch',
  'state' => 'stable',
  'uploadfolder' => 0,
  'createDirs' => '',
  'clearCacheOnLoad' => 0,
  'author_company' => '',
  'version' => '2.1.0',
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '7.6.0-8.99.99',
      'tesseract' => '2.0.0-0.0.0',
    ),
    'conflicts' => 
    array (
    ),
    'suggests' => 
    array (
    ),
  ),
  '_md5_values_when_last_written' => 'a:80:{s:9:"ChangeLog";s:4:"60b4";s:9:"README.md";s:4:"9df6";s:13:"composer.json";s:4:"d8bf";s:21:"ext_conf_template.txt";s:4:"d730";s:12:"ext_icon.png";s:4:"375d";s:17:"ext_localconf.php";s:4:"b6e8";s:14:"ext_tables.php";s:4:"0f90";s:14:"ext_tables.sql";s:4:"62a7";s:40:"Classes/Controller/DisplayController.php";s:4:"75fe";s:35:"Classes/Controller/PluginCached.php";s:4:"4bde";s:38:"Classes/Controller/PluginNotCached.php";s:4:"5335";s:37:"Classes/Service/ControllerService.php";s:4:"2191";s:28:"Classes/Utility/Debugger.php";s:4:"a651";s:37:"Classes/Utility/RealUrlTranslator.php";s:4:"55a7";s:34:"Configuration/FlexForm/Options.xml";s:4:"e727";s:47:"Configuration/PageTS/NewContentElementWizard.ts";s:4:"7fdb";s:44:"Configuration/TCA/Overrides/sys_template.php";s:4:"0bdb";s:42:"Configuration/TCA/Overrides/tt_content.php";s:4:"aa28";s:34:"Configuration/TypoScript/setup.txt";s:4:"b445";s:26:"Documentation/Includes.txt";s:4:"c83c";s:23:"Documentation/Index.rst";s:4:"6f8c";s:26:"Documentation/Settings.yml";s:4:"ccd4";s:33:"Documentation/AppendixA/Index.rst";s:4:"e6d5";s:37:"Documentation/Configuration/Index.rst";s:4:"ffdf";s:43:"Documentation/Configuration/Hooks/Index.rst";s:4:"237b";s:45:"Documentation/Configuration/Realurl/Index.rst";s:4:"39d4";s:50:"Documentation/Configuration/Redirections/Index.rst";s:4:"e112";s:46:"Documentation/Configuration/Tsconfig/Index.rst";s:4:"2914";s:48:"Documentation/Configuration/Typoscript/Index.rst";s:4:"fd0b";s:27:"Documentation/FAQ/Index.rst";s:4:"387f";s:39:"Documentation/Images/AllowedRecords.png";s:4:"03df";s:45:"Documentation/Images/ControllerComponents.png";s:4:"ccf8";s:40:"Documentation/Images/FlexformOptions.png";s:4:"553a";s:44:"Documentation/Images/FrontendDebugOutput.png";s:4:"4a69";s:45:"Documentation/Images/NewDisplayController.png";s:4:"802a";s:39:"Documentation/Images/StaticTemplate.png";s:4:"949c";s:43:"Documentation/Images/Tutorial/DataQuery.png";s:4:"7c36";s:56:"Documentation/Images/Tutorial/DisplayControllerSetup.png";s:4:"5e02";s:53:"Documentation/Images/Tutorial/FinalTutorialResult.png";s:4:"ec5e";s:51:"Documentation/Images/Tutorial/ImprovedDataQuery.png";s:4:"d8f3";s:56:"Documentation/Images/Tutorial/ImprovedTutorialResult.png";s:4:"6fd0";s:51:"Documentation/Images/Tutorial/MappingEmailField.png";s:4:"163d";s:54:"Documentation/Images/Tutorial/MappingRealNameField.png";s:4:"0e93";s:62:"Documentation/Images/Tutorial/MappingRealNameFieldComplete.png";s:4:"6f4f";s:63:"Documentation/Images/Tutorial/MappingRealNameWithTypoScript.png";s:4:"5283";s:53:"Documentation/Images/Tutorial/TemplateDisplayHtml.png";s:4:"ace4";s:56:"Documentation/Images/Tutorial/TemplateDisplayMapping.png";s:4:"8b92";s:57:"Documentation/Images/Tutorial/TemplateDisplayMetadata.png";s:4:"f75b";s:48:"Documentation/Images/Tutorial/TutorialResult.png";s:4:"7bfe";s:57:"Documentation/Images/Tutorial/WizardDisplayController.png";s:4:"1368";s:36:"Documentation/Installation/Index.rst";s:4:"a4e2";s:36:"Documentation/Introduction/Index.rst";s:4:"1fbe";s:32:"Documentation/Tutorial/Index.rst";s:4:"245c";s:41:"Documentation/Tutorial/Database/Index.rst";s:4:"c19e";s:48:"Documentation/Tutorial/FittingTogether/Index.rst";s:4:"7a3c";s:49:"Documentation/Tutorial/ImprovingDisplay/Index.rst";s:4:"9d99";s:42:"Documentation/Tutorial/NextSteps/Index.rst";s:4:"dfc0";s:44:"Documentation/Tutorial/Preparation/Index.rst";s:4:"0add";s:41:"Documentation/Tutorial/Scenario/Index.rst";s:4:"3458";s:41:"Documentation/Tutorial/Template/Index.rst";s:4:"1515";s:28:"Documentation/User/Index.rst";s:4:"cd2e";s:34:"Documentation/User/Cache/Index.rst";s:4:"7ad0";s:46:"Documentation/User/DisplayController/Index.rst";s:4:"6f0b";s:36:"Documentation/User/Options/Index.rst";s:4:"97b5";s:44:"Documentation/User/QueryParameters/Index.rst";s:4:"2b12";s:40:"Resources/Private/Language/locallang.xlf";s:4:"15e7";s:54:"Resources/Private/Language/locallang_csh_ttcontent.xlf";s:4:"76aa";s:43:"Resources/Private/Language/locallang_db.xlf";s:4:"ae8d";s:35:"Resources/Public/Icons/TypeIcon.png";s:4:"103f";s:37:"Resources/Public/Icons/WizardIcon.png";s:4:"5c8e";s:39:"Resources/Public/JavaScript/Debugger.js";s:4:"9bc0";s:36:"Resources/Public/Styles/Debugger.css";s:4:"396a";s:57:"Resources/Public/Styles/font-awesome/css/font-awesome.css";s:4:"3f05";s:61:"Resources/Public/Styles/font-awesome/css/font-awesome.min.css";s:4:"0442";s:58:"Resources/Public/Styles/font-awesome/fonts/FontAwesome.otf";s:4:"0b46";s:66:"Resources/Public/Styles/font-awesome/fonts/fontawesome-webfont.eot";s:4:"f7c2";s:66:"Resources/Public/Styles/font-awesome/fonts/fontawesome-webfont.svg";s:4:"2980";s:66:"Resources/Public/Styles/font-awesome/fonts/fontawesome-webfont.ttf";s:4:"7064";s:67:"Resources/Public/Styles/font-awesome/fonts/fontawesome-webfont.woff";s:4:"d9ee";s:68:"Resources/Public/Styles/font-awesome/fonts/fontawesome-webfont.woff2";s:4:"9749";}',
);

