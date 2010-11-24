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
 * Class implements a "render user function" wich fetches a database row from an external database 
 * 
 * @package Domain
 * @subpackage RenderUserFunction\ExternalData\DB\RenderUserFunction
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlistSpecial_Domain_RenderUserFunction_ExternalData_DB_RenderUserFunction {

	
	/**
	 * Fetch a row from an external database and render it by given render configuration
	 * 
	 * @param array $params
	 * @return string renderes result roe
	 */
	public static function fetchExternalRow(array $params) {
		
		$internalFieldData = $params['values'];
		$settings = $params['conf'];
		
		$externalDBRowConfiguration = new Tx_PtExtlistSpecial_Domain_Configuration_RenderUserFunction_ExternalData_ExternalDBRowConfiguration($settings, $internalFieldData);
		
		$dbObejct = Tx_PtExtlistSpecial_Domain_RenderUserFunction_ExternalData_DB_ExternalDBAccessorFactory::getInstance($externalDBRowConfiguration);
		$result = $dbObejct->fetchRowByConfiguration($externalDBRowConfiguration);

		return $this->renderExternalData($result);
	}
	
	
	
	/**
	 * Render the rowData as given by configuration
	 * 
	 * @param Tx_PtExtlistSpecial_Domain_Configuration_RenderUserFunction_ExternalData_ExternalDBRowConfiguration $externalDBRowConfiguration
	 * @param array $rowData
	 */
	protected function renderExternalData(Tx_PtExtlistSpecial_Domain_Configuration_RenderUserFunction_ExternalData_ExternalDBRowConfiguration $externalDBRowConfiguration, $rowData) {
		return Tx_PtExtlist_Utility_RenderValue::renderByConfigObject($rowData, $externalDBRowConfiguration);
	}
}
?>