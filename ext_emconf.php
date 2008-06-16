<?php

########################################################################
# Extension Manager/Repository config file for ext: "displaycontroller"
#
# Auto generated 10-06-2008 13:44
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Display Controller',
	'description' => 'This extension is used to get data from different data models and dispatch it to various views',
	'category' => 'plugin',
	'author' => 'Francois Suter (Cobweb)',
	'author_email' => 'support@cobweb.ch',
	'shy' => '',
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.0.0',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
			'dataquery' => '',
			'datadisplay' => '',
		),
	),
);

?>