<?php

/*********************************************************************
* Extension configuration file for ext "displaycontroller".
*
* Generated by ext 13-04-2015 12:06 UTC
*
* https://github.com/t3elmar/Ext
*********************************************************************/

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
  'version' => '1.6.1',
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '4.5.0-6.2.99',
      'tesseract' => '1.4.0-0.0.0',
    ),
    'conflicts' => 
    array (
    ),
    'suggests' => 
    array (
    ),
  ),
  '_md5_values_when_last_written' => 'a:29:{s:9:"ChangeLog";s:4:"ee8e";s:10:"README.txt";s:4:"b948";s:30:"class.tx_displaycontroller.php";s:4:"c297";s:39:"class.tx_displaycontroller_debugger.php";s:4:"2e76";s:38:"class.tx_displaycontroller_realurl.php";s:4:"ee95";s:38:"class.tx_displaycontroller_service.php";s:4:"e3ae";s:30:"displaycontroller_typeicon.png";s:4:"103f";s:16:"ext_autoload.php";s:4:"da27";s:21:"ext_conf_template.txt";s:4:"5e2a";s:12:"ext_icon.gif";s:4:"f02f";s:17:"ext_localconf.php";s:4:"d4f2";s:14:"ext_tables.php";s:4:"0b6f";s:14:"ext_tables.sql";s:4:"62a7";s:13:"locallang.xml";s:4:"eef3";s:27:"locallang_csh_ttcontent.xml";s:4:"7c1d";s:16:"locallang_db.xml";s:4:"95ed";s:15:"wizard_icon.gif";s:4:"b025";s:34:"Configuration/FlexForm/Options.xml";s:4:"93c7";s:39:"Resources/Public/JavaScript/Debugger.js";s:4:"9bc0";s:36:"Resources/Public/Styles/Debugger.css";s:4:"e4c9";s:14:"doc/manual.pdf";s:4:"b44e";s:14:"doc/manual.sxw";s:4:"439c";s:14:"doc/manual.txt";s:4:"0bc8";s:50:"hooks/class.tx_displaycontroller_hooks_tcemain.php";s:4:"7cfc";s:38:"pi1/class.tx_displaycontroller_pi1.php";s:4:"7800";s:46:"pi1/class.tx_displaycontroller_pi1_wizicon.php";s:4:"6f59";s:38:"pi2/class.tx_displaycontroller_pi2.php";s:4:"25ba";s:46:"pi2/class.tx_displaycontroller_pi2_wizicon.php";s:4:"32a5";s:16:"static/setup.txt";s:4:"b445";}',
  'user' => 'francois',
  'comment' => 'Fixed debugging output; caught empty FlexForm warning.',
);

?>