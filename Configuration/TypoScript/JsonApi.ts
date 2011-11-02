#
# JSON API for Json Views
#
EXTLISTJSON = PAGE
EXTLISTJSON {
	typeNum = 85561

	10 = COA_INT
    10 {
        10 = USER_INT
        10 {
            userFunc = tx_extbase_core_bootstrap->run
            extensionName = PtExtlist
            pluginName = Pi1

			# LIMIT CONTROLLER / ACTION
			switchableControllerActions {
				List {
					1 = list
				}
			}
        }
    }

	config {
		disableAllHeaderCode = 1
		xhtml_cleaning = 0
		admPanel = 0
	    debug = 0
	    no_cache = 1
		additionalHeaders = Content-type:application/json

		listIdentifier = memberStatsHistoryJSON
	}
}