<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2012 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
 *  All rights reserved
 *
 *  For further information: http://extlist.punkt.de <extlist@punkt.de>
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
 * Class implements data backend for reports with some special functionality
 * 
 * @author Michael Knoll 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage DataBackend
 */
class Tx_PtExtlistSpecial_Domain_DataBackend_ReportDataBackend extends Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend {

	/**
	 * Holds an array of filter addresses (filterbox.filteridentifier) which
	 * need to be active BEFORE a report is generated by this backend (before a query is send to the database)
	 *
	 * @var array
	 */
	protected $requiredActiveFilters = array();



	/**
	 * We implement template method for initializing backend
	 */
	protected function initBackend() {
		$this->initRequiredActiveFilters();
		parent::initBackend();
	}



	/**
	 * Build the list data
	 * @return array Array of raw list data
	 */
	public function buildListData() {
		if ($this->requiredActiveFiltersAreActive()) {
			return parent::buildListData();
		} else {
			$rawData = array();
			return $this->dataMapper->getMappedListData($rawData);
		}
	}



	/**
	 * Initializes required active filters from given configuration
	 */
	protected function initRequiredActiveFilters() {
		$requiredActiveFilters = $this->backendConfiguration->getSettings('requiredActiveFilters');
		if (!empty($requiredActiveFilters)) {
			$this->requiredActiveFilters = explode(',', $requiredActiveFilters);
		}
	}



	/**
	 * Returns TRUE, if all filters configured as required to be active are active
	 *
	 * @return bool TRUE, if all required filters are active.
	 */
	protected function requiredActiveFiltersAreActive() {
		foreach($this->requiredActiveFilters as $requiredActiveFilter) {
			$filter = $this->getFilterboxCollection()->getFilterByFullFiltername($requiredActiveFilter);
			if (!$filter->isActive()) {
				return FALSE;
			}
		}
		return TRUE;
	}

}
?>