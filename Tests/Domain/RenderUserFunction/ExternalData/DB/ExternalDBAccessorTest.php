<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>,
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
 * TetsClass
 * 
 * @package Tests
 * @subpackage Domain\RenderUserFunction\ExternalData\DB
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlistSpecial_Tests_Domain_RenderUserFunction_ExternalData_DB_ExternalDBAccessorTestCase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	protected $settings;
	protected $rowData;
	
	/**
	 * @var Tx_PtExtlistSpecial_Domain_Configuration_RenderUserFunction_ExternalData_ExternalDBRowConfiguration
	 */
	protected $testConfigObject;

	public function setup() {
		$this->rowData = array('key1' => 'value1', 'key2' => 'value2');
		$this->settings = array('dsn' => 'mysql://test:test@testServer',
						  'user' => 'user', 
						  'password' => 'password',
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
	public function buildQuery() {
		$externalDBAccessorMock = $this->getAccessibleMock('Tx_PtExtlistSpecial_Domain_RenderUserFunction_ExternalData_DB_ExternalDBAccessor', array('dummy'), array(), '', FALSE);
		$query = $externalDBAccessorMock->_call('buildQuery', $this->testConfigObject);
		$this->assertEquals('SELECT field FROM table WHERE otherField=value1 GROUPBY value2 ORDERBY field2 LIMIT 1', $query);
	}
}
?>