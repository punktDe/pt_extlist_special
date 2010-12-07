<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
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
 * Configuration for external DB Row RenderUserFunction
 * This Configuration is specific for every row of the result list!
 *
 * @package Domain
 * @subpackage Configuration\RenderUserFunction\ExternalData
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlistSpecial_Domain_Configuration_RenderUserFunction_ExternalData_ExternalDBRowConfiguration  extends Tx_PtExtlist_Domain_Configuration_AbstractExtlistConfiguration
																										   implements Tx_PtExtlist_Domain_Configuration_RenderConfigInterface {

	/**
	 * @var string
	 */
	protected $dsn;


	/**
	 * @var string
	 */
	protected $user;


	/**
	 * @var string
	 */
	protected $password;


	/**
	 * @var string
	 */
	protected $select;


	/**
	 * @var string
	 */
	protected $from;


	/**
	 * @var string
	 */
	protected $where;


	/**
	 * @var string
	 */
	protected $orderBy;


	/**
	 * @var string
	 */
	protected $groupBy;


	/**
	 * @var array
	 */
	protected $renderObj;


	/**
	 * @var string
	 */
	protected $renderTemplate;



	/**
	 * @var array
	 */
	protected $renderUserFunctions;


	/**
	 * @param array $settings
	 * @param array $internalFieldData
	 */
	public function __construct($settings, $internalFieldData) {
		$this->settings = $this->prepareSettingsForSpecificRow($settings, $internalFieldData);
		$this->init();
	}


	/**
	 * Fill placeholders in query part with data from the current row
	 *
	 * @param array $settings
	 * @param array $internalFieldData
	 */
	protected function prepareSettingsForSpecificRow($settings, $internalFieldData) {

		$replaceArray = array();
		foreach ($internalFieldData as $key => $value) {
			$replaceArray['###'.$key.'###'] = $value;
		}

		foreach ($settings['query'] as $queryPart => $queryValue) {
			// insert values
			$value = $this->replaceQueryValuesRecursive($queryValue, $replaceArray);

			// resolve stdWraps
			$value = Tx_PtExtlist_Utility_RenderValue::stdWrapIfPlainArray($value);
			$settings[$queryPart] = $value;
		}

		return $settings;
	}



	/**
	 * Replace string in array recursive
	 *
	 * @param mixed $queryValue
	 * @param array $replaceArray
	 * @return mixed
	 */
	protected function replaceQueryValuesRecursive($queryValue, &$replaceArray) {
		if (!is_array($queryValue)) {
			return str_replace(array_keys($replaceArray), array_values($replaceArray), $queryValue);
		}

		foreach ($queryValue as $key => $value) {
			$queryValue[$key] = $this->replaceQueryValuesRecursive($value, $replaceArray);
		}
		return $queryValue;
	}



	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Configuration/Tx_PtExtlist_Domain_Configuration_AbstractConfiguration::init()
	 */
	protected function init() {
		$this->setRequiredValue('dsn', 'No DSN Configuration set for ExternalDBRow Render User Function! 1290506596');
		$this->setValueIfExistsAndNotNothing('user');
		$this->setValueIfExistsAndNotNothing('password');

		$this->setRequiredValue('select', 'No select part given for ExternalDBRow Render User Function! 1290507801');
		$this->setValueIfExistsAndNotNothing('from');
		$this->setValueIfExistsAndNotNothing('where');
		$this->setValueIfExistsAndNotNothing('groupBy');
		$this->setValueIfExistsAndNotNothing('orderBy');

		$this->setValueIfExistsAndNotNothing('renderTemplate');
		if(array_key_exists('renderUserFunctions', $this->settings) && is_array($this->settings['renderUserFunctions'])) {
			asort($this->settings['renderUserFunctions']);
			$this->renderUserFunctions = $this->settings['renderUserFunctions'];
		}

		if(array_key_exists('renderObj', $this->settings) && $this->settings['renderObj']) {
			$this->renderObj = Tx_Extbase_Utility_TypoScript::convertPlainArrayToTypoScriptArray(array('renderObj' => $this->settings['renderObj']));
		}
	}

	public function getDSN() {
		return $this->dsn;
	}

	public function getUser() {
		return $this->user;
	}

	public function getPassword() {
		return $this->password;
	}

	public function getSelect() {
		return $this->select;
	}

	public function getFrom() {
		return $this->from;
	}

	public function getWhere() {
		return $this->where;
	}

	public function getOrderBy() {
		return $this->orderBy;
	}

	public function getGroupBy() {
		return $this->groupBy;
	}


	/**
	 * Limit is hardcoded to 1 for the externla DB row userfunction
	 */
	public function getLimit() {
		return 1;
	}



	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Configuration/Tx_PtExtlist_Domain_Configuration_RenderConfigInterface::getRenderObj()
	 */
	public function getRenderObj() {
		return $this->renderObj;
	}



	/**
	 * Returns a configuration array for user functions
	 *
	 * @return array userFunctions Configuration
	 */
	public function getRenderUserFunctions() {
		return $this->renderUserFunctions;
	}



	/**
	 * Returns a path to a fluid template file
	 *
	 * @returns string template
	 */
	public function getRenderTemplate() {
		return $this->renderTemplate;
	}


}
?>