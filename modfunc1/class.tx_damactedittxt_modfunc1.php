<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2006 René Fritz <r.fritz@colorcube.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Module extension (addition to function menu) 'Edit File' for the 'dam_act_edittxt' extension.
 *
 * @author	René Fritz <r.fritz@colorcube.de>
 */


require_once(PATH_t3lib.'class.t3lib_basicfilefunc.php');
require_once(PATH_txdam.'lib/class.tx_dam_guifunc.php');

require_once(PATH_t3lib.'class.t3lib_extobjbase.php');

class tx_damactedittxt_modfunc1 extends t3lib_extobjbase {


	/**
	 * Additional access check
	 *
	 * @return	boolean Return true if access is granted
	 */
	function accessCheck() {
		return tx_dam::access_checkAction('editFile');
	}


	/**
	 * Do some init things and set some things in HTML header
	 *
	 * @return	void
	 */
	function head() {
		$GLOBALS['SOBE']->pageTitle = $GLOBALS['LANG']->getLL('tx_dam_cmd_fileedit.title');
	}


	/**
	 * Returns a help icon for context help
	 *
	 * @return	string HTML
	 */
	function getContextHelp() {
// TODO csh
#		return t3lib_BEfunc::cshItem('xMOD_csh_corebe', 'file_rename', $GLOBALS['BACK_PATH'],'');
	}


	/**
	 * Main function, rendering the content of the rename form
	 *
	 * @return	void
	 */
	function main()	{
		global  $LANG;

		$content = '';

			// Cleaning and checking target
		$this->file = tx_dam::file_compileInfo($this->pObj->file[0]);


		if (is_array($this->pObj->data) AND isset($this->pObj->data['file_content'])) {

				// create the file and process DB update
			$error = tx_dam::process_editFile($this->file, $this->pObj->data['file_content']);

			if ($error) {
				$content .= $GLOBALS['SOBE']->getMessageBox ($LANG->getLL('error'), htmlspecialchars($error), $this->pObj->buttonBack(0), 2);

			} else {
				$this->pObj->redirect(true);
			}


		} elseif ($this->file['file_accessable']) {
			$content.= $this->renderForm(t3lib_div::getURL(tx_dam::file_absolutePath($this->file)));

		} else {
				// this should have happen in index.php already
			$content.= $this->pObj->accessDeniedMessage(tx_dam_guiFunc::getFolderInfoBar($this->folder));
		}

		return $content;
	}


	/**
	 * Making the form for create file
	 *
	 * @return	string		HTML content
	 */
	function renderForm($fileContent='')	{
		global $BE_USER, $LANG, $TYPO3_CONF_VARS;

		$content = '';
		$msg = array();

		$msg[] = tx_dam_guiFunc::getFolderInfoBar(tx_dam::path_compileInfo($this->file['file_path']));
		$msg[] = '&nbsp;';

		$msg[] = $GLOBALS['LANG']->sL('LLL:EXT:dam/locallang_db.xml:tx_dam_item.file_name',1).' <strong>'.htmlspecialchars($this->file['file_name']).'</strong>';
		$msg[] = '&nbsp;';
		$msg[] = $GLOBALS['LANG']->getLL('tx_dam_cmd_filenew.text_content',1);
		$msg[] = '<textarea rows="15" name="data[file_content]" wrap="off"'.$this->pObj->doc->formWidthText(48,'width:99%;height:65%','off').'>'.
					t3lib_div::formatForTextarea($fileContent).
					'</textarea>';


		$buttons = '
			<input type="submit" value="'.$GLOBALS['LANG']->getLL('labelCmdSave',1).'" />
			<input type="submit" value="'.$LANG->sL('LLL:EXT:lang/locallang_core.xml:labels.cancel',1).'" onclick="jumpBack(); return false;" />';


		$content .= $GLOBALS['SOBE']->getMessageBox ($GLOBALS['SOBE']->pageTitle, $msg, $buttons, 1);

		return $content;
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam_act_edittxt/modfunc1/class.tx_damactedittxt_modfunc1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam_act_edittxt/modfunc1/class.tx_damactedittxt_modfunc1.php']);
}

?>