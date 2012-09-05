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
 * Test case for class Tx_PtExtlistSpecial_Domain_TablePreprocessor_TablePreprocessor
 *
 * @package pt_dppp_auth
 * @subpackage Tests\Unit\Domain\TablePreprocessor
 */
class Tx_PtExtlistSpecial_Domain_TablePreprocessor_TablePreprocessorTest extends Tx_Extbase_Tests_Unit_BaseTestCase {

	protected $proxyClass;

	protected $proxy;

	public function setUp() {
		$this->proxyClass = $this->buildAccessibleProxy('Tx_PtExtlistSpecial_Domain_TablePreprocessor_TablePreprocessor');
		$this->proxy = new $this->proxyClass();
	}

	public function tearDown() {
		unset($this->proxy);
	}

	/**
	 * @test
	 */
	public function createTablePreparesAndExecutesSqlQuery() {
		$tableCreationQueryTemplate = "Foo %s Bar %s Baz";
		$tableName = 'ooF';
		$columnDefinitions = 'raB';
		$expected = "Foo ooF Bar raB Baz";

		$proxyMock = $this->getMockBuilder($this->proxyClass)
				->setMethods(array('sqlQuery'))
				->getMock();
		$proxyMock->expects($this->once())
				->method('sqlQuery')
				->with($expected);

		$proxyMock->_set('tableCreationQueryTemplate', $tableCreationQueryTemplate);
		$proxyMock->_set('tableName', $tableName);
		$proxyMock->_set('columnDefinitions', $columnDefinitions);

		$actual = $proxyMock->_call('createTable');
	}

	/**
	 * @test
	 */
	public function createTemporaryTablePreparesAndExecutesSqlQuery() {
		$tableCreationQueryTemplate = "Foo %s Bar %s Baz";
		$tableName = 'tmp_ooF';
		$columnDefinitions = 'raB';
		$expected = "Foo tmp_ooF Bar raB Baz";

		$proxyMock = $this->getMockBuilder($this->proxyClass)
				->setMethods(array('sqlQuery'))
				->getMock();
		$proxyMock->expects($this->once())
				->method('sqlQuery')
				->with($expected);

		$proxyMock->_set('tableCreationQueryTemplate', $tableCreationQueryTemplate);
		$proxyMock->_set('temporaryTableName', $tableName);
		$proxyMock->_set('columnDefinitions', $columnDefinitions);

		$actual = $proxyMock->_call('createTemporaryTable');
	}

	/**
	 * @test
	 */
	public function dropBackupTablePreparesAndExecutesSqlQuery() {
		$dropTableQueryTemplate = "Foo %s Bar";
		$tableName = 'bck_ooF';
		$columnDefinitions = 'raB';
		$expected = "Foo bck_ooF Bar";

		$proxyMock = $this->getMockBuilder($this->proxyClass)
				->setMethods(array('sqlQuery'))
				->getMock();
		$proxyMock->expects($this->once())
				->method('sqlQuery')
				->with($expected);

		$proxyMock->_set('dropBackupTableQueryTemplate', $dropTableQueryTemplate);
		$proxyMock->_set('backupTableName', $tableName);

		$actual = $proxyMock->_call('dropBackupTable');
	}

	/**
	 * @test
	 */
	public function switchTablesPreparesAndExecutesSqlQuery() {
		$switchTablesQueryTemplate = "Foo %s Bar %s Baz %s Foo %s Bar";
		$tableName = 'ooF';
		$temporaryTableName = 'tmp_ooF';
		$backupTableName = 'bck_ooF';
		$expected = "Foo ooF Bar bck_ooF Baz tmp_ooF Foo ooF Bar";

		$proxyMock = $this->getMockBuilder($this->proxyClass)
				->setMethods(array('sqlQuery'))
				->getMock();
		$proxyMock->expects($this->once())
				->method('sqlQuery')
				->with($expected);

		$proxyMock->_set('switchTablesQueryTemplate', $switchTablesQueryTemplate);
		$proxyMock->_set('tableName', $tableName);
		$proxyMock->_set('temporaryTableName', $temporaryTableName);
		$proxyMock->_set('backupTableName', $backupTableName);

		$actual = $proxyMock->_call('switchTables');
	}

}
?>