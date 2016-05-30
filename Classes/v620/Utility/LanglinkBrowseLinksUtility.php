<?php
/**
 * @author    Sonja Scholz <ss@cabag.ch>
 * @package TYPO3
 */
namespace Cabag\CabagLanglink\v620\Utility;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\Utility\IconUtility;

final class LanglinkBrowseLinksUtility {

	/**
	 * Cached language records to save DB calls
	 *
	 * @var array
	 */
	static $languageRecords = array();

	/**
	 * Returns a sprite icon for a given language
	 * Returns the configured defaultLanguage icon if set
	 *
	 * @param integer $pageUid
	 * @param integer $sysLanguageUid
	 * @return string
	 */
	public static function getFlagIconForLanguage($pageUid, $sysLanguageUid) {
		if ($sysLanguageUid > 0) {
			if (!isset(self::$languageRecords[$sysLanguageUid])) {
				self::$languageRecords[$sysLanguageUid] = BackendUtility::getRecord('sys_language', $sysLanguageUid, 'flag,title');
			}
			$languageRecord = self::$languageRecords[$sysLanguageUid];
			$flagIsoCode = $languageRecord['flag'];
		} else {
			$defaultFlag = BackendUtility::getModTSconfig($pageUid, 'mod.SHARED.defaultLanguageFlag');
			$defaultLanguageLabel = BackendUtility::getModTSconfig($pageUid, 'mod.SHARED.defaultLanguageLabel');
			$flagIsoCode = $defaultFlag['value'];
			if (stripos($flagIsoCode, '.gif') !== FALSE) {
				// A complete file name is used, strip away extension
				$flagIsoCode = str_ireplace('.gif', '', $defaultFlag['value']);
			}
			if (empty($defaultFlag)) {
				$flagIsoCode = FALSE;
			}
		}

		$iconTitle = is_array($languageRecord) ? $languageRecord['title'] : '';
		if (empty($iconTitle) && is_array($defaultLanguageLabel)) {
			$iconTitle = $defaultLanguageLabel['value'];
		}
		$options = array('title' => $iconTitle);

		if ($flagIsoCode !== FALSE) {
			return IconUtility::getSpriteIcon('flags-' . $flagIsoCode, $options);
		} else {
			return IconUtility::getSpriteIcon('mimetypes-x-sys_language', $options);
		}

	}

	/**
	 * Get the flag HTML for the different language versions of a page
	 *
	 * @param integer $pageUid UID of the affected page
	 * @return string
	 */
	public static function getFlagHTML($pageUid) {
		if ($pageUid > 0) {

			// check if there are alternative language records on this page
			$listOfLangRecords = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'pages_language_overlay.*',
				"pages_language_overlay",
				"pages_language_overlay.deleted = 0
			AND pages_language_overlay.hidden = 0
			AND pages_language_overlay.sys_language_uid != 0
			AND pages_language_overlay.pid =" . $pageUid,
				'',
				'pages_language_overlay.sys_language_uid'
			);

			$langLinks = '';

			// add flag for link to the default language
			$langLinks .= '
		<a href="#" onclick="return link_typo3Page(\'L:0/' . $pageUid . '\');">'
				. self::getFlagIconForLanguage($pageUid, 0)
				. '</a>';

			// add flag for link to other languages
			if (is_array($listOfLangRecords) && !empty($listOfLangRecords)) {
				foreach ($listOfLangRecords as $langRecord) {
					$langLinks .= '
				<a href="#" onclick="return link_typo3Page(\'L:' . $langRecord['sys_language_uid'] . '/' . $pageUid . '\');">'
						. self::getFlagIconForLanguage($pageUid, $langRecord['sys_language_uid'])
						. '</a>';
				}
			}
		} else {
			$langLinks = '';
		}

		return $langLinks;
	}
}

