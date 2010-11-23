<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
*  All rights reserved
*
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
 * @package Tests
 * @subpackage Configuration\RenderUserFunction\ExternalData
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlistSpecial_Domain_Configuration_RenderUserFunction_ExternalData_ExternalDBRowConfigurationTestCase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	protected $settings;
	protected $rowData;
	
	/**
	 * @var Tx_PtExtlistSpecial_Domain_Configuration_RenderUserFunction_ExternalData_ExternalDBRowConfiguration
	 */
	protected $testConfigObject;
	
	public function setUp() {
		
		$this->rowData = array('key1' => 'value1', 'key2' => 'value2');
		$this->settings = array('dsn' => 'mysql://test:test@testServer',
						  'query' => array (
								'select' => 'field',
								'from' => 'table',
								'where' => 'otherField=###key1###',
								'groupBy' => '###key2###',
								'orderBy' => 'field2',
							)
						);
		$this->testConfigObject = new Tx_PtExtlistSpecial_Domain_Configuration_RenderUserFunction_ExternalData_ExternalDBRowConfiguration($this->settings, $this->rowData);
	}	

	/** @test */
	public function prepareSettingsForSpecificRow() {
		$configMock = $this->getAccessibleMock('Tx_PtExtlistSpecial_Domain_Configuration_RenderUserFunction_ExternalData_ExternalDBRowConfiguration', array('dummy'), array(), '', FALSE);
		$settings = $configMock->_call('prepareSettingsForSpecificRow', $this->settings, $this->rowData);
		
		$this->assertEquals('otherField=value1', $settings['where']);
		$this->assertEquals('value2', $settings['groupBy']);
	}
	
	/** @test */
	public function getDSN() {
		$this->assertEquals($this->testConfigObject->getDSN(), 'mysql://test:test@testServer');
	}
	
	/** @test */
	public function getSelect() {
		$this->assertEquals($this->testConfigObject->getSelect(), 'field');
	}
	
	/** @test */
	public function getFrom() {
		$this->assertEquals($this->testConfigObject->getFrom(), 'table');
	}
	
	/** @test */
	public function getWhere() {
		$this->assertEquals($this->testConfigObject->getWhere(), 'otherField=value1');
	}
	
	/** @test */
	public function getOrderBy() {
		$this->assertEquals($this->testConfigObject->getOrderBy(), 'field2');
	}
	
	/** @test */
	public function getGroupBy() {
		$this->assertEquals($this->testConfigObject->getGroupBy(), 'value2');
	}
}
?>