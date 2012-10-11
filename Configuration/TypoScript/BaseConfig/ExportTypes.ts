####################################################
# This template configures export handling
# with pt_extlist
#
# Using this template requires a configured list
# on the same page or a parent page. Only
# settings for export are given here!
#
# @author Daniel Lienert <lienert@punkt.de>
# @package pt_extlist
# @subpackage Export
####################################################

plugin.tx_ptextlist.settings.export.exportConfigs {

	htmlExcelExport < plugin.tx_ptextlist.prototype.export
	htmlExcelExport {
		viewClassName = Tx_PtExtlistSpecial_View_Export_ExcelHtmlListView

		fileExtension = xls
		stripTags = 1

		headerContentType = application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
		templatePath = typo3conf/ext/pt_extlist_special/Resources/Private/Templates/Export/ExcelHtmlExport.html
	}

}