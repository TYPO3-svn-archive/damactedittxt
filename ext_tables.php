<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

if (TYPO3_MODE=='BE')	{
	t3lib_extMgm::insertModuleFunction(
		'txdamM1_cmd',
		'tx_damactedittxt_modfunc1',
		t3lib_extMgm::extPath($_EXTKEY).'modfunc1/class.tx_damactedittxt_modfunc1.php',
		'LLL:EXT:dam/mod_cmd/locallang.xml:tx_dam_cmd_filenew.title'
	);

	tx_dam::register_action ('tx_damactedittxt_editFile', 'EXT:dam_act_edittxt/class.tx_damactedittxt_editFile.php:&tx_damactedittxt_editFile', 'after:tx_dam_action_viewFile');
}
?>