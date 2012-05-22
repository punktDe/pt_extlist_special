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

class Tx_PtExtlistSpecial_Domain_Renderer_StructuredList_RowToColumnGroupRenderer extends Tx_PtExtlist_Domain_Renderer_AbstractRenderer {


	/**
	 * Renders list data, add group header to a specifi column
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_ListData $listData
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public function renderList(Tx_PtExtlist_Domain_Model_List_ListData $listData) {

		$yDimensionValue = '';			// yDimensionValue to determine a group
		$newRow = NULL;
		$firstRowInGroup = TRUE;		// only render the yDimension column when processing the first row
		$firstGroupCompleted = FALSE;	// needed to calculate header / subheader in first group

		$tempListData = new Tx_PtExtlist_Domain_Model_List_ListData();

		$yDimensionColumn = $this->rendererConfiguration->getSettings('yDimensionColumn');
		$xDimensionGroupColumn = $this->rendererConfiguration->getSettings('xDimensionGroupColumn');

		$headerRow = new Tx_PtExtlist_Domain_Model_List_Row();
		$subHeaderRow = new Tx_PtExtlist_Domain_Model_List_Row();


		foreach($listData as $rowID => $row) { /* @var $row Tx_PtExtlist_Domain_Model_List_Row */

			if($row->getCell($yDimensionColumn)->getValue() != $yDimensionValue) {

				if($newRow) {
					$tempListData->addRow($newRow);
					$firstGroupCompleted = TRUE;
				}

				$newRow = new Tx_PtExtlist_Domain_Model_List_Row();
				$yDimensionValue = $row->getCell($yDimensionColumn)->getValue();
				$firstRowInGroup = TRUE;
			}


			foreach($row as $columnId => $cell) {

				if($firstRowInGroup || $columnId != $yDimensionColumn) { // if this is the first row in the yDimension group, add the "labelColumn", but do not repeat it

					$firstRowInGroup = FALSE;

					if($columnId != $xDimensionGroupColumn) {	// never add the xdimensionGroupColumn, we add it to a separate header row
						$newColumnIdentifier = $rowID . '.' . $columnId;
						$newRow->addCell($cell, $newColumnIdentifier);

						if(!$firstGroupCompleted) {

							if($columnId != $yDimensionColumn) {
								$columnGroupElementCount = $row->count() - 2;
								$headerCell = new Tx_PtExtlist_Domain_Model_List_Cell($row->getCell($xDimensionGroupColumn)->getValue());
								$headerCell->addSpecialValue('colSpan', $columnGroupElementCount);
								$headerRow->addCell($headerCell, $newColumnIdentifier);
							} else {
								$headerRow->addCell(new Tx_PtExtlist_Domain_Model_List_Cell(''), $newColumnIdentifier);
							}

							$subHeaderRow->addCell(new Tx_PtExtlist_Domain_Model_List_Cell($columnId), $newColumnIdentifier);
						}
					}
				}
			}
		}

		$tempListData->addRow($newRow);

		/**
		 * Build the final list
		 */
		$outputListData = new Tx_PtExtlist_Domain_Model_List_ListData();

		$outputListData->addRow($headerRow);
		$outputListData->addRow($subHeaderRow);

		foreach($tempListData as $tempListRow) {
			$outputListData->addRow($tempListRow);
		}

		return $outputListData;
	}
}
?>