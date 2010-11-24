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
 * Class implements a cell of a row of list data
 * 
 * @package Domain
 * @subpackage RenderUserFunction\ExternalData\DB
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlistSpecial_Domain_RenderUserFunction_ExternalData_DB_ExternalDBAccessorFactory {
	
	/**
	 * Array of db connections identified by dsn 
	 * @var array
	 */
	protected static $instances = array();
	
	
	/**
	 * Build database object
	 * 
	 * @param Tx_PtExtlistSpecial_Domain_Configuration_RenderUserFunction_ExternalData_ExternalDBRowConfiguration $externalDBRowConfiguration
	 * @return Tx_PtExtlistSpecial_Domain_RenderUserFunction_ExternalData_DB_ExternalDBAccessor
	 */
	public static function getInstance(Tx_PtExtlistSpecial_Domain_Configuration_RenderUserFunction_ExternalData_ExternalDBRowConfiguration $externalDBRowConfiguration) {
		
		$instanceIdentifier = md5($externalDBRowConfiguration->getDSN());
		
		if(!self::$instances[$instanceIdentifier]) {
			self::$instances[$instanceIdentifier] = new Tx_PtExtlistSpecial_Domain_RenderUserFunction_ExternalData_DB_ExternalDBAccessor();
			self::$instances[$instanceIdentifier]->injectDbObject(self::createDataObject($externalDBRowConfiguration));
		}
		
		return self::$instances[$instanceIdentifier];
	}
	
	
	
	
	/**
	 * Create Database Object
	 * 
	 * @param Tx_PtExtlistSpecial_Domain_Configuration_RenderUserFunction_ExternalData_ExternalDBRowConfiguration $externalDBRowConfiguration
	 * @return PDO
	 * @throws Exception
	 */
	protected static function createDataObject(Tx_PtExtlistSpecial_Domain_Configuration_RenderUserFunction_ExternalData_ExternalDBRowConfiguration $externalDBRowConfiguration) {
		
		try {
			$pdo = new PDO($externalDBRowConfiguration->getDSN());	
		} catch (Exception $e) {
			throw new Exception('Unable to establish databse connection: ' . $e->getMessage() . ' 1290585032');
		}
						
		return $pdo;
	}	
}
?>