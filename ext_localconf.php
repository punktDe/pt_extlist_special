<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

// Scheduler Tasks
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Tx_PtExtlistSpecial_Domain_Scheduler_TablePreprocessorTask'] = array(
    'extension' => $_EXTKEY,
    'title' => 'Table preprocessor',
    'description' => 'Persist a pt_extlist result in a table.',
	'additionalFields' => 'Tx_PtExtlistSpecial_Domain_Scheduler_TablePreprocessorTaskAdditionalFields'
);

?>