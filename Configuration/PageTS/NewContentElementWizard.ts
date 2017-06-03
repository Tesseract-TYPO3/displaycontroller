mod.wizards.newContentElement.wizardItems.tesseract {
	show = displaycontroller_cached, displaycontroller_uncached
	header = LLL:EXT:displaycontroller/Resources/Private/Language/locallang.xlf:tesseract
	elements {
		displaycontroller_cached {
			iconIdentifier = tx_displaycontroller-content-element-wizard
			title = LLL:EXT:displaycontroller/Resources/Private/Language/locallang.xlf:pi1_title
			description = LLL:EXT:displaycontroller/Resources/Private/Language/locallang.xlf:pi1_plus_wiz_description
			tt_content_defValues {
				CType = displaycontroller_pi1
			}
		}
		displaycontroller_uncached {
			iconIdentifier = tx_displaycontroller-content-element-wizard
			title = LLL:EXT:displaycontroller/Resources/Private/Language/locallang.xlf:pi2_title
			description = LLL:EXT:displaycontroller/Resources/Private/Language/locallang.xlf:pi2_plus_wiz_description
			tt_content_defValues {
				CType = displaycontroller_pi2
			}
		}
	}
}