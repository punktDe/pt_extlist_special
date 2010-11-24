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
 * Class implements a lightwight DB Connector for the external DB userfunction
 * 
 * @package Domain
 * @subpackage RenderUserFunction\ExternalData\DB
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlistSpecial_Domain_RenderUserFunction_ExternalData_DB_ExternalDBAccessor {
	
	
	/**
	 * @var PDO
	 */
	protected $dbObject;
	
	
	/**
	 * Set the DB Object
	 * @param PDO $dbObject
	 */
	public function injectDBObject(PDO $dbObject) {
		$this->dbObject = $dbObject;
	}
	
	
	
	/**
	 * Fetch a row from the external database by configuration
	 * 
	 * @param Tx_PtExtlistSpecial_Domain_Configuration_RenderUserFunction_ExternalData_ExternalDBRowConfiguration $externalDBRowConfiguration
	 * @return array result
	 */
	public function fetchRowByConfiguration(Tx_PtExtlistSpecial_Domain_Configuration_RenderUserFunction_ExternalData_ExternalDBRowConfiguration $externalDBRowConfiguration) {

		try {
			/* @var $statement PDOStatement */
			$query = $this->buildQuery($externalDBRowConfiguration);

		    $statement = $this->dbObject->prepare($query);
		    $statement->execute();
		    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
		} catch(Exception $e) {
			throw new Exception('Error while trying to execute query on database! SQL-Statement: ' . $query . 
			    ' 1290586188 - Error message from PDO: ' . $e->getMessage() . 
			    '. Further information from PDO_errorInfo: ' . $statement->errorInfo());
		}

		return $result;
	}
	
	
	
	/**
	 * Create the query
	 * 
	 * @param Tx_PtExtlistSpecial_Domain_Configuration_RenderUserFunction_ExternalData_ExternalDBRowConfiguration $externalDBRowConfiguration
	 * @return strings
	 */
	protected function buildQuery(Tx_PtExtlistSpecial_Domain_Configuration_RenderUserFunction_ExternalData_ExternalDBRowConfiguration $externalDBRowConfiguration) {
		$query = '';
		$query .= 'SELECT ' . $externalDBRowConfiguration->getSelect();
		$query .=  $externalDBRowConfiguration->getFrom() ? ' FROM ' . $externalDBRowConfiguration->getFrom() : '';
		$query .=  $externalDBRowConfiguration->getWhere() ? ' WHERE ' . $externalDBRowConfiguration->getWhere() : '';
		$query .=  $externalDBRowConfiguration->getGroupBy() ? ' GROUPBY ' . $externalDBRowConfiguration->getGroupBy() : '';
		$query .=  $externalDBRowConfiguration->getOrderBy() ? ' ORDERBY ' . $externalDBRowConfiguration->getOrderBy() : '';
		$query .=  $externalDBRowConfiguration->getLimit() ? ' LIMIT ' . $externalDBRowConfiguration->getLimit() : '';
		
		return $query;
	}
}
?>