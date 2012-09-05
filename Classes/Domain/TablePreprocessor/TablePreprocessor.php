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
 * Table Preprocessor
 *
 * @package pt_extlist_special
 * @subpackage Domain\TablePreprocessor
 */
class Tx_PtExtlistSpecial_Domain_TablePreprocessor_TablePreprocessor {

	/**
	 * @var t3lib_DB
	 */
	protected $connection;

	/**
	 * @var Tx_PtExtlistSpecial_Domain_TablePreprocessor_ColumnDefinitionBuilderInterface
	 */
	protected $columnDefinitionBuilder;

	/**
	 * @var string
	 */
	protected $columnDefinitions;

	/**
	 * @var string
	 */
	protected $tableName = '';

	/**
	 * @var string
	 */
	protected $temporaryTableName = '';

	/**
	 * @var string
	 */
	protected $backupTableName = '';

	/**
	 * @var string
	 */
	protected $tableCreationQueryTemplate = "
		CREATE TABLE IF NOT EXISTS `%s` (
			`uid` int(11) NOT NULL AUTO_INCREMENT,
			%s
			PRIMARY KEY (`uid`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

	/**
	 * @var string
	 */
	protected $switchTablesQueryTemplate = "RENAME TABLE %s TO %s, %s TO %s;";

	/**
	 * @var string
	 */
	protected $dropBackupTableQueryTemplate = "DROP TABLE IF EXISTS %s";

	/**
	 * @param Tx_PtExtlistSpecial_Domain_TablePreprocessor_ColumnDefinitionBuilderInterface $columnDefinitionBuilder
	 * @return void
	 */
	public function injectColumnDefinitionBuilder(Tx_PtExtlistSpecial_Domain_TablePreprocessor_ColumnDefinitionBuilderInterface $columnDefinitionBuilder) {
		$this->columnDefinitionBuilder = $columnDefinitionBuilder;
	}

	/**
	 * Initialize object
	 *
	 * This initialization of the database connection provides better testing prerequisites.
	 *
	 * @return void
	 */
	public function initializeObject() {
		$this->connection = $GLOBALS['TYPO3_DB'];
	}

	/**
	 * @param string $listIdentifier
	 * @param string $tableName
	 * @return void
	 */
	public function execute($listIdentifier, $tableName) {
		$this->tableName = $tableName;
		$this->columnDefinitions = $this->columnDefinitionBuilder->getColumnDefinitions($listIdentifier);
		$this->setTableNames();
		$this->createTable();
		$this->createTemporaryTable();
		$this->fillTemporaryTable();
		$this->switchTables();
		$this->dropBackupTable();
	}

	/**
	 * @return void
	 */
	protected function setTableNames() {
		$this->temporaryTableName = 'tmp_' . $this->tableName;
		$this->backupTableName = 'bck_' . $this->tableName;
	}

	/**
	 * @return void
	 * @throws Exception
	 */
	protected function createTable() {
		$tableCreationQuery = sprintf($this->tableCreationQueryTemplate, $this->tableName, $this->columnDefinitions);
		$this->sqlQuery($tableCreationQuery);
	}

	/**
	 * @return void
	 * @throws Exception
	 */
	protected function createTemporaryTable() {
		$tableCreationQuery = sprintf($this->tableCreationQueryTemplate, $this->temporaryTableName, $this->columnDefinitions);
		$this->sqlQuery($tableCreationQuery);
	}

	/**
	 * @return void
	 * @throws Exception
	 */
	protected function fillTemporaryTable() {

	}

	/**
	 * @return void
	 * @throws Exception
	 */
	protected function switchTables() {
		$this->sqlQuery(sprintf($this->switchTablesQueryTemplate, $this->tableName, $this->backupTableName, $this->temporaryTableName, $this->tableName));
	}

	/**
	 * @return void
	 * @throws Exception
	 */
	protected function dropBackupTable() {
		$dropTableQuery = sprintf($this->dropBackupTableQueryTemplate, $this->backupTableName);
		$this->sqlQuery($dropTableQuery);
	}

	/**
	 * @param $query
	 * @throws Exception
	 */
	protected function sqlQuery($query) {
		$result = $this->connection->sql_query($query);
		if ($result === FALSE) {
			throw new Exception('SQL query failed! 1346685973');
		}
	}

}
