<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (!defined ('TYPO3_MODE'))     die ('Access denied.');

// Manage Xclasses by ext manager
$tx_cabaglanglink_extconf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cabag_langlink']);

if (!defined('CabagLanglink_TYPO3_version')) {
	if ($tx_cabaglanglink_extconf['simulateTYPO3Version']) {
		define('CabagLanglink_TYPO3_version', $tx_cabaglanglink_extconf['simulateTYPO3Version']);
	} else {
		define('CabagLanglink_TYPO3_version', 'v620');
	}
}

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
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.browse_links.php']['browseLinksHook'][] = 'Cabag\CabagLanglink\\' . CabagLanglink_TYPO3_version . '\Hooks\LanglinkBrowseLinksHook';
	$TYPO3_CONF_VARS['SC_OPTIONS']['ext/rtehtmlarea/mod3/class.tx_rtehtmlarea_browse_links.php']['browseLinksHook'][] = 'Cabag\\CabagLanglink\\' . CabagLanglink_TYPO3_version . '\Hooks\LanglinkBrowseLinksHook';
}

/* hook for rte typolink parsing */
if (class_exists('Cabag\CabagLanglink\\' . CabagLanglink_TYPO3_version . '\Hooks\LanglinkTypolinkHook')) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['typolinkLinkHandler']['?id=L'] = 'Cabag\\CabagLanglink\\' . CabagLanglink_TYPO3_version . '\\Hooks\\LanglinkTypolinkHook';
}

/* hook for browse_links typolink parsing */
if (class_exists('Cabag\CabagLanglink\\' . CabagLanglink_TYPO3_version . '\Hooks\LanglinkTypolinkHook')) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['typolinkLinkHandler']['L'] = 'Cabag\\CabagLanglink\\' . CabagLanglink_TYPO3_version . '\\Hooks\\LanglinkTypolinkHook';
}

/* hook for rte transformation */
if (class_exists('Cabag\CabagLanglink\\' . CabagLanglink_TYPO3_version . '\Hooks\RteTransformationHook')) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_parsehtml_proc.php']['transformation']['ts_links'] = 'Cabag\\CabagLanglink\\' . CabagLanglink_TYPO3_version . '\\Hooks\\RteTransformationHook';
}
