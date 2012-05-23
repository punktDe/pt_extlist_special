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

class Tx_PtExtlistSpecial_Tests_Functional_ExcelZipExportTestCase extends Tx_PtExtlist_Tests_BaseTestcase {

	protected $proxyClass;

	protected $proxy;

	/**
	 * @test
	 */
	public function prepareWorkingDirectoryCreatesFolders() {
		$this->proxyClass = $this->buildAccessibleProxy('Tx_PtExtlistSpecial_View_Export_ExcelZipListView');
		$this->proxy = new $this->proxyClass();

		$workingPath = $this->proxy->_call('prepareWorkingDirectory');

		$this->assertTrue(is_dir($workingPath));

		t3lib_div::rmdir($workingPath, TRUE);

		$this->assertFalse(is_dir($workingPath));
	}

	/**
	 * @test
	 */
	public function generateZipFileContentFromFileReturnsZip() {
		$filename = 'test.txt';
		$this->proxyClass = $this->buildAccessibleProxy('Tx_PtExtlistSpecial_View_Export_ExcelZipListView');
		$this->proxy = new $this->proxyClass();

		$workingPath = $this->proxy->_call('prepareWorkingDirectory');

		chdir($workingPath);

		$fileHandle = fopen($filename, 'w') or die("can't open file");
		fclose($fileHandle);

		$zipContent = $this->proxy->_call('generateZipFileContentFromFile', $filename);

		$this->assertContains($filename, $zipContent);

		chdir('..');

		t3lib_div::rmdir($workingPath, TRUE);
		$this->assertFalse(is_dir($workingPath));

	}
}
