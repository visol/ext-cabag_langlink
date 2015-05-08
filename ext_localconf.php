<?php
if (!defined ('TYPO3_MODE'))     die ('Access denied.');

// Manage Xclasses by ext manager
$tx_cabaglanglink_extconf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cabag_langlink']);

if (!defined('CabagLanglink_TYPO3_version')) {
	if ($tx_cabaglanglink_extconf['simulateTYPO3Version'] && file_exists(t3lib_extMgm::extPath($_EXTKEY).'typo3_versions/' . $tx_cabaglanglink_extconf['simulateTYPO3Version'])) {
		if(version_compare('6.2.0', TYPO3_version, '>=')) {
			define('CabagLanglink_TYPO3_version', $tx_cabaglanglink_extconf['simulateTYPO3Version']);
		} else {
			define('CabagLanglink_TYPO3_version', 'v620');
		}
	} else {
		if(version_compare('6.2.0', TYPO3_version, '>=')) {
			define('CabagLanglink_TYPO3_version', TYPO3_version);
		} else {
			define('CabagLanglink_TYPO3_version', 'v620');
		}
	}
}
if(CabagLanglink_TYPO3_version != 'v620') { // everything for TYPO3 version < 6.2
	/* xclass for rte typolink page browser*/
	if(file_exists(t3lib_extMgm::extPath($_EXTKEY).'typo3_versions/'.CabagLanglink_TYPO3_version.'/class.ux_tx_rtehtmlarea_browse_links.php')){
		$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/rtehtmlarea/mod3/class.tx_rtehtmlarea_browse_links.php'] = t3lib_extMgm::extPath($_EXTKEY).'typo3_versions/'.CabagLanglink_TYPO3_version.'/class.ux_tx_rtehtmlarea_browse_links.php';
	}
	
	/* xclass for typolink page browser */
	if(file_exists(t3lib_extMgm::extPath($_EXTKEY).'typo3_versions/'.CabagLanglink_TYPO3_version.'/class.ux_browse_links.php')){
		$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['typo3/class.browse_links.php'] = t3lib_extMgm::extPath($_EXTKEY).'typo3_versions/'.CabagLanglink_TYPO3_version.'/class.ux_browse_links.php';
	}
	
	/* hook to display the langlink as a page link */
	if(file_exists(t3lib_extMgm::extPath($_EXTKEY).'typo3_versions/'.CabagLanglink_TYPO3_version.'/class.tx_cabaglanglink_browseLinksHook.php')){
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.browse_links.php']['browseLinksHook'][] = t3lib_extMgm::extPath($_EXTKEY).'typo3_versions/'.CabagLanglink_TYPO3_version.'/class.tx_cabaglanglink_browseLinksHook.php:&tx_cabaglanglink_browseLinksHook';
		$TYPO3_CONF_VARS['SC_OPTIONS']['ext/rtehtmlarea/mod3/class.tx_rtehtmlarea_browse_links.php']['browseLinksHook'][] = t3lib_extMgm::extPath($_EXTKEY).'typo3_versions/'.CabagLanglink_TYPO3_version.'/class.tx_cabaglanglink_browseLinksHook.php:&tx_cabaglanglink_browseLinksHook';
	}
	
	/* hook for rte typolink parsing */
	 $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['typolinkLinkHandler']['?id=L'] = 'EXT:cabag_langlink/typo3_versions/'.CabagLanglink_TYPO3_version.'/class.user_tslib_content_typolinkHook.php:&user_tslib_content_typolinkHook';
	
	/* hook for browse_links typolink parsing */
	 $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['typolinkLinkHandler']['L'] = 'EXT:cabag_langlink/typo3_versions/'.CabagLanglink_TYPO3_version.'/class.user_tslib_content_typolinkHook.php:&user_tslib_content_typolinkHook';
} else { // everything for typo3 >= 6.2
	
	/* xclass for rte typolink page browser*/
	if (class_exists('Cabag\CabagLanglink\\' . CabagLanglink_TYPO3_version . '\XClass\LanglinkRtePageTree')) {
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Rtehtmlarea\\PageTree'] = array(
			'className' => 'Cabag\\CabagLanglink\\' . CabagLanglink_TYPO3_version . '\\XClass\\LanglinkRtePageTree',
		);
	}
	
	/* xclass for typolink page browser */
	if (class_exists('Cabag\CabagLanglink\\' . CabagLanglink_TYPO3_version . '\XClass\LanglinkRtePageTree')) {
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['localPageTree'] = array(
			'className' => 'Cabag\\CabagLanglink\\' . CabagLanglink_TYPO3_version . '\\XClass\\LanglinkPageTree',
		);
	}
	
	/* hook to display the langlink as a page link */
	if (class_exists('Cabag\CabagLanglink\\' . CabagLanglink_TYPO3_version . '\Hooks\LanglinkBrowseLinksHook')) {
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.browse_links.php']['browseLinksHook'][] = '\Cabag\CabagLanglink\\' . CabagLanglink_TYPO3_version . '\Hooks\LanglinkBrowseLinksHook';
		$TYPO3_CONF_VARS['SC_OPTIONS']['ext/rtehtmlarea/mod3/class.tx_rtehtmlarea_browse_links.php']['browseLinksHook'][] = '\Cabag\CabagLanglink\\' . CabagLanglink_TYPO3_version . '\Hooks\LanglinkBrowseLinksHook';
	}
	
	/* hook for rte typolink parsing */
	if (class_exists('Cabag\CabagLanglink\\' . CabagLanglink_TYPO3_version . '\Hooks\LanglinkTypolinkHook')) {
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['typolinkLinkHandler']['?id=L'] = '\Cabag\CabagLanglink\\' . CabagLanglink_TYPO3_version . '\Hooks\LanglinkTypolinkHook';
	}
	
	/* hook for browse_links typolink parsing */
	if (class_exists('Cabag\CabagLanglink\\' . CabagLanglink_TYPO3_version . '\Hooks\LanglinkTypolinkHook')) {
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['typolinkLinkHandler']['L'] = '\Cabag\CabagLanglink\\' . CabagLanglink_TYPO3_version . '\Hooks\LanglinkTypolinkHook';
	}
	
	/* hook for rte transformation */
	if (class_exists('Cabag\CabagLanglink\\' . CabagLanglink_TYPO3_version . '\Hooks\RteTransformationHook')) {
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_parsehtml_proc.php']['transformation']['ts_links'] = '\Cabag\CabagLanglink\\' . CabagLanglink_TYPO3_version . '\Hooks\RteTransformationHook';
	}
}

?>
