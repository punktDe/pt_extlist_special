<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll, Christoph Ehscheidt
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
 * Implements a view for rendering a google visualisation data table
 *
 * @author Daniel Lienert
 * @package View
 * @subpackage Export
 */
class Tx_PtExtlistSpecial_View_Json_GoogleVisDataTable extends Tx_PtExtlist_View_BaseView {


	/**
	 * Returns true, if view has a template
	 *
	 * TODO We return true here, because otherwise, another view class is
	 * instantiated in controller.
	 *
	 * @return bool
	 */
	public function hasTemplate() {
		return TRUE;
	}


	/**
	 * @return void
	 */
	protected function initConfiguration() {
		
	}


	/**
	 * (non-PHPdoc)
	 * @see Classes/View/Tx_Fluid_View_TemplateView::canRender()
	 */
	public function canRender(Tx_Extbase_MVC_Controller_ControllerContext $controllerContext) {
		return true;
	}

	

	/**
	 * Overwriting the render method to generate a CSV output
	 *
	 * @return  void (never returns)
	 */
	public function render() {

		$templateVariableContainer = $this->baseRenderingContext->getTemplateVariableContainer();

		ob_clean();

		//$out = fopen('php://output', 'w');

		$data = array();

		// Cols
		if ($templateVariableContainer->exists('listCaptions')) {

			foreach ($templateVariableContainer['listCaptions'] as $columnIdentifier =>  $headerCell) { /* @var $headerCell Tx_PtExtlist_Domain_Model_List_Cell */
				$data['cols'][] = array (
					'id' => $columnIdentifier,
					'label' => $headerCell->getValue(),
					'type' => $this->getDataTypeByColumnConfig($columnIdentifier)
				);
			}
		}

		// Rows
		foreach ($templateVariableContainer['listData'] as $listRow) { /* @var $row Tx_PtExtlist_Domain_Model_List_Row */

			$row = array();

			foreach ($listRow as $listCell) { /* @var $listCell Tx_PtExtlist_Domain_Model_List_Cell */
				$cell = array(
					'v' => $this->formatValueByType($listCell->getValue()),
				);

				$row[] = $cell;
			}

			// $row = Tx_PtExtbase_Div::iconvArray($row, 'UTF-8', 'ISO-8859-1');     // TODO: make encoding configurable via TS

			$data['rows'][]['c'] = $row;

		}

		echo json_encode($data, 1);

		exit();
	}



	/**
	 * @param $columnIdentifier
	 * @return array|string
	 */
	public function getDataTypeByColumnConfig($columnIdentifier) {
		$jsonType = $this->configurationBuilder
			->buildColumnsConfiguration()
			->getColumnConfigByIdentifier($columnIdentifier)
			->getSettings('jsonType');

		return $jsonType ? $jsonType : 'string';
	}


	/**
	 * @param $value
	 * @return string type
	 */
	protected function formatValueByType($value) {

		switch(true) {
			case is_numeric($value):
				return (int) $value;
				break;

			default:
				return $value;
		}
	}
}