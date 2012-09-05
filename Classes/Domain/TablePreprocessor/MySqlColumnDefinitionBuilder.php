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
 * MySql Column Definition Builder
 *
 * @package pt_extlist_special
 * @subpackage Domain\TablePreprocessor
 */
class Tx_PtExtlistSpecial_Domain_TablePreprocessor_MySqlColumnDefinitionBuilder implements Tx_PtExtlistSpecial_Domain_TablePreprocessor_ColumnDefinitionBuilderInterface {

	/**
	 * @var string
	 */
	protected $listIdentifier;

	/**
	 * @var Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection
	 */
	protected $columnConfigurationCollection;

	/**
	 * @param string $listIdentifier
	 * @return string
	 */
	public function getColumnDefinitions($listIdentifier) {
		$this->listIdentifier = $listIdentifier;
		$this->initialize();
		return $this->buildColumnDefinitions();
	}

	/**
	 * @return void
	 */
	protected function initialize() {
		$this->columnConfigurationCollection = Tx_PtExtlist_ExtlistContext_ExtlistContextFactory::getContextByListIdentifier($this->listIdentifier)
				->getConfigurationBuilder()
				->buildColumnsConfiguration();
	}

	/**
	 * @return string
	 */
	protected function buildColumnDefinitions() {
		$columnDefinitions = '';
		$sqlSettings = $this->collectSqlSettings();
		foreach ($sqlSettings as $columnSqlSettings) {
			$columnDefinitions[] = implode(' ', $columnSqlSettings);
		}
		$columnDefinitions = implode(', ', $columnDefinitions) . ',';
		return $columnDefinitions;
	}

	/**
	 * @return array
	 */
	protected function collectSqlSettings() {
		$sqlSettings = array();
		foreach ($this->columnConfigurationCollection as $columnConfiguration) { /** @var Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig $columnConfiguration */
			$sqlSettings[$columnConfiguration->getColumnIdentifier()] = $this->getSqlSettingsByColumnIdentifier($columnConfiguration->getColumnIdentifier());
		}
		return $sqlSettings;
	}

	/**
	 * @param string $columnIdentifier
	 * @return array
	 */
	protected function getSqlSettingsByColumnIdentifier($columnIdentifier) {
		$sqlSettings = array();
		if ($this->columnConfigurationCollection->hasIdentifier($columnIdentifier)) {
			$sqlSettings = $this->columnConfigurationCollection->getColumnConfigByIdentifier($columnIdentifier)->getSettings('sql');
		}
		return $sqlSettings;
	}

}
