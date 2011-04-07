<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>,
*  Christoph Ehscheidt <ehscheidt@punkt.de>
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
 * Type: Post-list Renderer
 * 
 * Custom Renderer for grouping the table by a specific column
 * 
 * @package Domain
 * @subpackage Renderer\StructuredList
 * @author Daniel Lienert <lienert@punkt.de>
 */

class Tx_PtExtlistSpecial_Domain_Renderer_StructuredList_ColumnGroupRenderer extends Tx_PtExtlist_Domain_Renderer_AbstractRenderer {
	
	/**
	 * Renders list data, add group header to a specifi column
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_ListData $listData
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public function renderList(Tx_PtExtlist_Domain_Model_List_ListData $listData) {
		
		if($listData->count() == 0) return $listData;
		
		$outputListData = new Tx_PtExtlist_Domain_Model_List_ListData();
		
		$groupColumn = $this->rendererConfiguration->getSettings('columnIdentifier');
		$showRowCount = $this->rendererConfiguration->getSettings('showRowCount');
		
		$headerRowMarker = NULL;
		$currentGroupCount = NULL;
		$currentGroup = NULL;
		
		$colCount = $listData->getItemByIndex(0)->count();
		$rowIndex = 1;
		
		foreach($listData as $rowID => $row) { /* @var $row Tx_PtExtlist_Domain_Model_List_Row */
			
			if($currentGroup != $row->getCell($groupColumn)->getValue()) {
				$currentGroup = $row->getCell($groupColumn)->getValue();
				
				$groupHeaderCell = new Tx_PtExtlist_Domain_Model_List_Cell();
				$groupHeaderCell->setValue($currentGroup);
				$groupHeaderCell->addSpecialValue('colSpan', $colCount);
				$groupHeaderCell->addSpecialValue('showRowCount', $showRowCount);
				$groupHeaderCell->setCSSClass('tx-ptextlist-groupHeaderRow');
				
				$groupHeaderRow = new Tx_PtExtlist_Domain_Model_List_Row();
				$groupHeaderRow->addCell($groupHeaderCell, $groupColumn);
				$outputListData->addRow($groupHeaderRow);
				
				$headerRowMarker = $outputListData->count() -1;
				$currentGroupCount = 1;
			}
			
			$oddEvenClass = $rowIndex++ % 2 == 0 ? 'odd' : 'even';
			$row->addSpecialValue('oddEvenClass', $oddEvenClass);
			
			$outputListData->addRow($row);
			
			if($showRowCount) {
				$outputListData->getItemById($headerRowMarker)->getCell($groupColumn)->addSpecialValue('rowCount', $currentGroupCount++);	
			}
		}
		
		return $outputListData;
	}
}
?>