<?php
require_once t3lib_extMgm::extPath('pt_extbase') . '/Classes/Utility/NameSpace.php';

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
 * Access
 *
 * @package Domain
 * @subpackage UserFunction\Data
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlistSpecial_Domain_RenderUserFunction_Tree {

	/**
	 * @param array $params
	 * @return array
	 */
	public function getPath(array $params) {

		$nodeIdField = $params['conf']['nodeIdField'];
		$repository = $params['conf']['repository'];
		$nameSpace = $params['conf']['nameSpace'];
		$pathGlue = $params['conf']['pathGlue'];

		$startIndex = 0;
		$length = 1000;

		if($params['conf']['startIndex']) {
			$startIndex = (int) $params['conf']['startIndex'];
		}

		if($params['conf']['length']) {
			$length = (int) $params['conf']['length'];
		}

		if(!$repository) throw new Exception('Please specify a node repository class name');

		if(!$nodeIdField) throw new Exception('Please specify the field to get the nodeId from by setting the parameter nodeIdField.', 1332185912);
		$nodeId = (int) $params['values'][$nodeIdField];

		if($nodeId) {
			$nodePathBuilder = Tx_PtExtbase_Tree_NodePathBuilder::getInstanceByRepositoryAndNamespace($repository, $nameSpace);
			$nodeArray = $nodePathBuilder->getPathFromRootToNode($nodeId, $startIndex, $length);
			$nodeLabels = array();

			foreach($nodeArray as $node) {
				$nodeLabels[] = $node->getLabel();
			}

			return implode($pathGlue, $nodeLabels);
		}
	}
}
?>