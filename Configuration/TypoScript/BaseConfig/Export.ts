# Export Settings
# @version  $Id:$
# @author   Daniel Lienert <lienert@punkt.de>
################################################################################

plugin.tx_ptextlist.settings.export {
	exportConfigs {
		
		badgeExport < plugin.tx_ptextlist.prototype.export
		badgeExport {
			viewClassName = Tx_PtExtlist_Special_View_BadgeExportView
			fileExtension = inc			
			templatePath = EXT:pt_extlist_special/Resources/Private/Templates/Export/Latex.tex

			dateFormat = Y-m_d-H-i
		}
	}
}