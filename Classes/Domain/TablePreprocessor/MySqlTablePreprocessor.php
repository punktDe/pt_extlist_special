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
class Tx_PtExtlistSpecial_Domain_TablePreprocessor_MySqlTablePreprocessor implements Tx_PtExtlistSpecial_Domain_TablePreprocessor_TablePreprocessorInterface {

	/**
	 * @var t3lib_DB
	 */
	protected $connection;

	/**
	 * @var Tx_PtExtlistSpecial_Domain_TablePreprocessor_ColumnDefinitionBuilderInterface
	 */
	protected $columnDefinitionBuilder;

	/**
	 * @var Tx_PtExtlist_ExtlistContext_ExtlistContext
	 */
	protected $extlistContext;

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
	protected $insertRowQueryTemplate = "
		INSERT INTO %s VALUES (
			NULL,
			%s
		);";

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
		$this->extlistContext = Tx_PtExtlist_ExtlistContext_ExtlistContextFactory::getContextByListIdentifier($listIdentifier);
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
	 */
	protected function createTable() {
		$tableCreationQuery = sprintf($this->tableCreationQueryTemplate, $this->tableName, $this->columnDefinitions);
		$this->sqlQuery($tableCreationQuery);
	}

	/**
	 * @return void
	 */
	protected function createTemporaryTable() {
		$tableCreationQuery = sprintf($this->tableCreationQueryTemplate, $this->temporaryTableName, $this->columnDefinitions);
		$this->sqlQuery($tableCreationQuery);
	}

	/**
	 * @return void
	 */
	protected function fillTemporaryTable() {
		foreach ($this->extlistContext->getRenderedListData() as $row) { /** @var Tx_PtExtlist_Domain_Model_List_Row $row */
			$insertRow = array();
			foreach ($row as $cell) { /** @var Tx_PtExtlist_Domain_Model_List_Cell $cell */
				$insertRow[] = $cell->getValue();
			}
			$insertRowQuery = sprintf($this->insertRowQueryTemplate, $this->temporaryTableName, implode(',', $this->connection->fullQuoteArray($insertRow, $this->temporaryTableName)));
			$this->sqlQuery($insertRowQuery);
		}
	}

	/**
	 * @return void
	 */
	protected function switchTables() {
		$this->sqlQuery(sprintf($this->switchTablesQueryTemplate, $this->tableName, $this->backupTableName, $this->temporaryTableName, $this->tableName));
	}

	/**
	 * @return void
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
			throw new Exception('SQL query failed! '. $query . ' 1346685973');
		}
	}

}
