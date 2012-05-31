<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

/*
Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Pi1',
	'Extlist special'
);
*/

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', '[pt_special] Extlist special Base');
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Export', '[pt_special] Extlist special Exports');
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Demolist', '[pt_special] Extlist special Demos');

//$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY . '_pi1'] = 'pi_flexform';
//t3lib_extMgm::addPiFlexFormValue($_EXTKEY . '_pi1', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_list.xml');


?>