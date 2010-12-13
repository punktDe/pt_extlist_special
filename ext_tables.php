<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

/*
Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Pi1',
	'Extlist special'
);
*/

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Extlist special Base');
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Demolist', 'Extlist special Demos');

//$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY . '_pi1'] = 'pi_flexform';
//t3lib_extMgm::addPiFlexFormValue($_EXTKEY . '_pi1', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_list.xml');


?>