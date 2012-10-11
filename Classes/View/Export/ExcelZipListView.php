<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 punkt.de GmbH
 *  Authors:
 *    Christian Herberger <herberger@punkt.de>,
 *    Ursula Klinger <klinger@punkt.de>,
 *    Daniel Lienert <lienert@punkt.de>,
 *    Joachim Mathes <mathes@punkt.de>
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
 * ExcelZipListView
 *
 * @package View
 * @subpackage Export
 */
class Tx_PtExtlistSpecial_View_Export_ExcelZipListView extends Tx_PtExtlist_View_Export_ExcelListView {

	/**
	 * Clears output buffer and sends corresponding headers
	 * Needed in Zip-Export because of content type
	 *
	 * @return void
	 */
	protected function clearOutputBufferAndSendHeaders() {
		ob_clean();
		// redirect output to client browser
		header('Content-Type: application/zip');
		header('Content-Disposition: attachment;filename="' . $this->getFilenameFromTs() . '"');
		header('Cache-Control: max-age=0');
	}

	/**
	 * Builds a zip file from an excel export
	 *
	 * @param $objWriter PHPExcel_Writer_IWriter
	 */
	protected function saveOutputAndExit(PHPExcel_Writer_IWriter $objWriter) {
		$workpath = $this->prepareWorkingDirectory();

		$zipFile = $this->getFilenameFromTs();
		$excelFile = str_replace('.zip', '.xls', $zipFile);
		$objWriter->save($excelFile);

		// generate zip content
		$zipPassword = $this->exportConfiguration->getSettings('password');

		$zipContent = $this->generateZipFileContentFromFile($excelFile, $zipPassword);
		echo $zipContent;

		t3lib_div::rmdir($workpath, TRUE);

		exit();
	}

	/**
	 * prepares a working directory to build a zip
	 *
	 * @return string
	 * @throws Exception
	 */
	protected function prepareWorkingDirectory() {
		$now = time();

		$workpath = PATH_site . '/fileadmin/_temp_/'. $now.'/';
		t3lib_div::mkdir_deep($workpath);

		$isWorkpathAccessible = chdir($workpath);
		if (!$isWorkpathAccessible) {
			throw new Exception('Workpath for zip export is not accessible', 1337673757);
		}

		return $workpath;
	}

	/**
	 * generate the zip file content, echo this to deliver the zip
	 *
	 * @param $filename
	 * @param string $password
	 * @return string
	 * @throws Exception
	 */
	protected function generateZipFileContentFromFile($filename, $password = '') {
		$execResult = '';

		if ($password) {
			exec('zip -e -P ' . escapeshellarg($password) . ' ' . escapeshellarg('temp.zip') . ' ' . escapeshellarg($filename), $execOutput, $execResult);
		} else {
			exec('zip ' . escapeshellarg('temp.zip') . ' ' . escapeshellarg($filename), $execOutput, $execResult);
		}

		if ($execResult !== 0) {
			if (TYPO3_DLOG) t3lib_div::devLog('exec(zip) didn\'t work, error code '.$execResult, 'pt_extlist', 2);
			throw new Exception('Exec zip did not work, error code: '.$execResult, 1337674278);
		}

		$zipContent = file_get_contents('temp.zip');
		return $zipContent;
	}

}

?>