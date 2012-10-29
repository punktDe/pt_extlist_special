<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
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
 * Renders the list as plain HTML table
 *
 * @author Daniel Lienert
 * @package View
 * @subpackage Export
 */
class Tx_PtExtlistSpecial_View_Export_ExcelHtmlListView extends Tx_PtExtlist_View_Export_AbstractExportView {


	/**
	 * Holds an instance of FLUID template variable container
	 *
	 * @var Tx_Fluid_Core_ViewHelper_TemplateVariableContainer
	 */
	protected $templateVariableContainer;


	/**
	 * @var Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection
	 */
	protected $columnConfigCollection;


	/**
	 * Path to fluid template
	 *
	 * @var string
	 */
	protected $templatePath;


	/**
	 * @var string
	 */
	protected $headerContentType = 'text/html';


	/**
	 * @var bool
	 */
	protected $stripTags = true;


	/**
	 * @var string
	 */
	protected $outputBuffer;


	/**
	 * Overwriting the render method to generate Excel output
	 */
	public function render() {

		$this->init();

		$this->clearOutputBufferAndSendHeaders();

		$this->renderHeader();
		$this->renderBody();
		$this->renderAggregates();

		$this->saveOutputAndExit();
	}


	/**
	 * Initialize additional class properties
	 */
	public function initConfiguration() {

		$settings = $this->exportConfiguration->getSettings();

		$this->templatePath = $this->exportConfiguration->getSettings('templatePath');
		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($this->templatePath, array('message' => 'No template path given for fluid export!', 1349949505));
		$this->setTemplatePathAndFilename(t3lib_div::getFileAbsFileName($this->templatePath));

		if(array_key_exists('headerContentType', $settings)) {
			$this->headerContentType = $settings['headerContentType'];
		}

		if(array_key_exists('stripTags', $settings)) {
			$this->stripTags = $settings['stripTags'] == '1' ? true : false;
		}

	}


	/**
	 * Initializes empty worksheet object
	 *
	 * @return void
	 */
	protected function init() {
		$this->initConfiguration();

		$this->templateVariableContainer = $this->baseRenderingContext->getTemplateVariableContainer();
		$this->columnConfigCollection = $this->configurationBuilder->buildColumnsConfiguration();
	}


	/**
	 * Render the header row
	 */
	protected function renderHeader() {

		// Headers
		if ($this->templateVariableContainer->exists('listCaptions')) {

			$values = array();

			foreach ($this->templateVariableContainer['listCaptions'] as $caption) {
				$values[] = $this->stripTags ? strip_tags($caption->getValue()) : $caption->getValue();
			}

			$this->templateVariableContainer->add('tableHeaderLine', '<tr><td class=headerCell>'. implode('</td><td class=headerCell>', $values) .'</td></tr>');

		}
	}



	/**
	 * render all body rows
	 */
	protected function renderBody() {

		$tableBody = '';

		// Rows
		foreach ($this->templateVariableContainer['listData'] as $listRow) { /* @var $row Tx_PtExtlist_Domain_Model_List_Row */

			$values = array();

			foreach ($listRow as &$listCell) { /* @var $listCell Tx_PtExtlist_Domain_Model_List_Cell */
				$values[] = $this->stripTags ? strip_tags($listCell->getValue()) : $listCell->getValue();
			}

			$tableBody .= '<tr><td>'. implode('</td><td>', $values) .'</td></tr>';

		}

		$this->templateVariableContainer->add('tableBodyLines', $tableBody);
	}



	/**
	 * Render the aggregate rows
	 */
	protected function renderAggregates() {
		$aggregateRows = '';

		// Rows
		foreach ($this->templateVariableContainer['aggregateRows'] as $aggregateRow) { /* @var $row Tx_PtExtlist_Domain_Model_List_Row */

			$values = array();

			foreach ($aggregateRow as &$cell) { /* @var $cell Tx_PtExtlist_Domain_Model_List_Cell */
				$values[] = $this->stripTags ? strip_tags($cell->getValue()) : $cell->getValue();
			}

			$aggregateRows .= '<tr><td class=aggregateCell>'. implode('</td><td class=aggregateCell>', $values) .'</td></tr>';

		}

		$this->templateVariableContainer->add('tableAggregateLines', $aggregateRows);
	}



	/**
	 * Clears output buffer and sends corresponding headers
	 *
	 * @return void
	 */
	protected function clearOutputBufferAndSendHeaders() {
		ob_clean();

		// redirect output to client browser
		header('Content-Type: ' . $this->headerContentType);
		header('Content-Disposition: attachment;filename="' . $this->getFilenameFromTs() . '"');
		header('Cache-Control: max-age=0');
	}



	/**
	 * Render the output and send it to the browser
	 */
	protected function saveOutputAndExit() {

		$outputData = parent::render();

		$out = fopen('php://output', 'w');

		fwrite($out, $outputData);

		fclose($out);

		exit();
	}
}

?>