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
 * Test case for class Tx_PtExtlistSpecial_Domain_TablePreprocessor_MySqlColumnDefinitionBuilder
 *
 * @package pt_dppp_auth
 * @subpackage Tests\Unit\Domain\TablePreprocessor
 */
class Tx_PtExtlistSpecial_Domain_TablePreprocessor_MySqlColumnDefinitionBuilderTest extends Tx_Extbase_Tests_Unit_BaseTestCase {

	protected $proxyClass;

	protected $proxy;

	public function setUp() {
		$this->proxyClass = $this->buildAccessibleProxy('Tx_PtExtlistSpecial_Domain_TablePreprocessor_MySqlColumnDefinitionBuilder');
		$this->proxy = new $this->proxyClass();
	}

	public function tearDown() {
		unset($this->proxy);
	}

	/**
	 * @test
	 */
	public function checkType() {
		$this->assertInstanceOf('Tx_PtExtlistSpecial_Domain_TablePreprocessor_ColumnDefinitionBuilderInterface', $this->proxy);
	}

	/**
	 * @test
	 */
	public function getColumnDefinitionsReturnsColumnsDefinitions() {
		$input = 'Terence';
		$expected = 'Hill';

		$proxyMock = $this->getMockBuilder($this->proxyClass)
				->setMethods(array('initialize', 'buildColumnDefinitions'))
				->getMock();
		$proxyMock->expects($this->once())
			->method('initialize');
		$proxyMock->expects($this->once())
			->method('buildColumnDefinitions')
			->will($this->returnValue($expected));

		$actual = $proxyMock->getColumnDefinitions($input);
		$this->assertSame($input, $proxyMock->_get('listIdentifier'));
		$this->assertSame($expected, $actual);
	}

	/**
	 * @test
	 */
	public function buildColumnDefinitionsReturnsValidColumnDefinition() {
		$sqlSettings = array(
			'foo' => array(
				"columnName" => "title",
				"type" => "varchar(255)",
				"constraints" => "DEFAULT '' NOT NULL"
			),
			'bar' => array(
				"columnName" => "date",
				"type" => "int(11)",
				"constraints" => "DEFAULT '' NOT NULL"
			),
		);

		$expected = "title varchar(255) DEFAULT '' NOT NULL, date int(11) DEFAULT '' NOT NULL,";

		$proxyMock = $this->getMockBuilder($this->proxyClass)
				->setMethods(array('collectSqlSettings'))
				->getMock();
		$proxyMock->expects($this->once())
			->method('collectSqlSettings')
			->will($this->returnValue($sqlSettings));

		$actual = $proxyMock->_call('buildColumnDefinitions');
		$this->assertEquals($expected, $actual);
	}

	/**
	 * @test
	 */
	public function collectSqlSettingsReturnsArrayOfSqlSettings() {
		$expected = $columnSqlSettings = array(
			array(
				"columnName" => "title",
				"type" => "varchar(255)",
				"constraints" => "DEFAULT '' NOT NULL"
			),
			array(
				"columnName" => "date",
				"type" => "int(11)",
				"constraints" => "DEFAULT '' NOT NULL"
			),
		);

		$columnConfiguration1 = $this->getMockBuilder('Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig')
				->setMethods(array('getColumnIdentifier'))
				->disableOriginalConstructor()
				->getMock();
		$columnConfiguration1->expects($this->exactly(2))
				->method('getColumnIdentifier')
				->will($this->returnValue(0));
		$columnConfiguration2 = $this->getMockBuilder('Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig')
				->setMethods(array('getColumnIdentifier'))
				->disableOriginalConstructor()
				->getMock();
		$columnConfiguration2->expects($this->exactly(2))
				->method('getColumnIdentifier')
				->will($this->returnValue(1));
		$columnConfigurationCollection = array(
			$columnConfiguration1,
			$columnConfiguration2
		);

		$proxyMock = $this->getMockBuilder($this->proxyClass)
				->setMethods(array('getSqlSettingsByColumnIdentifier'))
				->getMock();
		$proxyMock->expects($this->exactly(2))
			->method('getSqlSettingsByColumnIdentifier')
			->will($this->returnCallback(
			function ($columnIdentifier) use ($columnSqlSettings) {
				return $columnSqlSettings[$columnIdentifier];
			}));
		$proxyMock->_set('columnConfigurationCollection', $columnConfigurationCollection);

		$actual = $proxyMock->_call('collectSqlSettings');
		$this->assertEquals($expected, $actual);
	}

	/**
	 * @test
	 */
	public function getSqlSettingsByColumnIdentifierReturnsSqlSettingsIfIdentifiersExist() {
		$input = 'BudSpencer';
		$expected = array(
				"columnName" => "title",
				"type" => "varchar(255)",
				"constraints" => "DEFAULT '' NOT NULL"
		);

		$columnConfiguration = $this->getMockBuilder('Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig')
				->setMethods(array('getSettings'))
				->disableOriginalConstructor()
				->getMock();
		$columnConfiguration->expects($this->once())
				->method('getSettings')
				->with('sql')
				->will($this->returnValue($expected));

		$columnConfigurationCollection = $this->getMockBuilder('Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection')
				->setMethods(array('hasIdentifier', 'getColumnConfigByIdentifier'))
				->getMock();
		$columnConfigurationCollection->expects($this->once())
			->method('hasIdentifier')
			->with($input)
			->will($this->returnValue(TRUE));
		$columnConfigurationCollection->expects($this->once())
			->method('getColumnConfigByIdentifier')
			->with($input)
			->will($this->returnValue($columnConfiguration));

		$this->proxy->_set('columnConfigurationCollection', $columnConfigurationCollection);

		$actual = $this->proxy->_call('getSqlSettingsByColumnIdentifier', $input);
		$this->assertEquals($expected, $actual);

	}

}
?>