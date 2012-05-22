<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>
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
 * Implements a view for rendering a latex export with fluid
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @package View
 * @subpackage Export
 */
class Tx_PtExtlist_Special_View_BadgeExportView Extends Tx_PtExtlist_View_Export_AbstractExportView {


	/**
	 * Path to fluid template
	 *
	 * @var string
	 */
	protected $templatePath;

	

	/**
	 * Initialize additional class properties
	 *
	 */
	protected function initConfiguration() {
		parent::initConfiguration();
		
		$this->templatePath = $this->exportConfiguration->getSettings('templatePath');
		tx_pttools_assert::isNotEmptyString($this->templatePath, array('message' => 'No template path given for fluid export! 1284621481'));
		$this->setTemplatePathAndFilename(t3lib_div::getFileAbsFileName($this->templatePath));
	}


	/**
	 * Overwriting the render method to generate a downloadable output
	 *
	 * @return  void (never returns)
	 */
	public function render() {
		ob_clean();

		$outputData = parent::render();

		$this->sendHeader($this->getFilenameFromTs());

		$out = fopen('php://output', 'w');

		$outputData = $this->replaceBrackets($outputData);
		fwrite($out, $outputData);

		fclose($out);

		exit();
	}


	/**
	 * replaces
	 *
	 * @param string $string
	 */
	protected function replaceBrackets($string) {
		$searchArray = array('[', ']', '&', '%', '_', '#', '€', '$', 'ž', 'ø', 'ł', 'å', 'ß', 'æ', 'œ', 'Ä', 'Ø', 'Ł', 'Å', 'Æ', 'Œ', 'Ö', '!----');
		$replaceArray = array('{', '}', '{\&}', '{\%}', '{\_}', '{\#}', '{\EUR}', '{\$}', '\v{z}', '{\o}', '{\l}', '{\aa}', '{\ss}', '{\ae}', '{\oe}', '{\"A}', '{\O}', '{\L}', '{\AA}', '{\AE}', '{\OE}', '{\"O', '%');


		return str_replace($searchArray, $replaceArray, $string);
	}

}