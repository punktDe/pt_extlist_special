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
 * Table Preprocessor Task Additional Fields
 *
 * @package pt_extlist_special
 * @subpackage Domain\Scheduler
 */
class Tx_PtExtlistSpecial_Domain_Scheduler_TablePreprocessorTaskAdditionalFields implements tx_scheduler_AdditionalFieldProvider {

	/**
	 * @var array
	 */
	protected $configuration = array(
		array(
			'id' => 'tx_ptextlistspecial_listidentifier',
			'label' => 'List Identifier'
		),
		array(
			'id' => 'tx_ptextlistspecial_tablename',
			'label' => 'Table name'
		),
	);

	/**
	 * Gets additional fields to render in the form to add/edit a task
	 *
	 * @param array $taskInfo Values of the fields from the add/edit task form
	 * @param tx_scheduler_Task $task The task object being eddited. Null when adding a task!
	 * @param tx_scheduler_Module $schedulerModule Reference to the scheduler backend module
	 * @return array A two dimensional array, array('Identifier' => array('fieldId' => array('code' => '', 'label' => '', 'cshKey' => '', 'cshLabel' => ''))
	 */
	public function getAdditionalFields(array &$taskInfo, $task, tx_scheduler_Module $schedulerModule) {
		$additionalFields = array();
		foreach ($this->configuration as $configuration) {
			if (empty($taskInfo[$configuration['id']])) {
				if($schedulerModule->CMD == 'edit') {
					$taskInfo[$configuration['id']] = $task->$configuration['id'];
				} else {
					$taskInfo[$configuration['id']] = '';
				}
			}

			$fieldCode = '<input type="text" name="tx_scheduler[' . $configuration['id'] . ']" id="' . $configuration['id'] . '" value="' . $taskInfo[$configuration['id']] . '" size="50" maxlength="100" />';
			$additionalFields[$configuration['id']] = array(
				'code'     => $fieldCode,
				'label'    => $configuration['label']
			);
		}

        return $additionalFields;
	}

	/**
	 * Validates the additional fields' values
	 *
	 * @param array $submittedData An array containing the data submitted by the add/edit task form
	 * @param tx_scheduler_Module $schedulerModule Reference to the scheduler backend module
	 * @return boolean TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
	 */
	public function validateAdditionalFields(array &$submittedData, tx_scheduler_Module $schedulerModule) {
		foreach ($this->configuration as $configuration) {
			$submittedData[$configuration['id']] = trim($submittedData[$configuration['id']]);
		}
        return TRUE;
	}

	/**
	 * Takes care of saving the additional fields' values in the task's object
	 *
	 * @param array $submittedData An array containing the data submitted by the add/edit task form
	 * @param tx_scheduler_Task $task Reference to the scheduler backend module
	 * @return void
	 */
	public function saveAdditionalFields(array $submittedData, tx_scheduler_Task $task) {
		foreach ($this->configuration as $configuration) {
			$task->$configuration['id'] = $submittedData[$configuration['id']];
		}
	}

}
?>