<?php
/**
 * @author	Sonja Scholz <ss@cabag.ch>
 * @package TYPO3
 */
namespace Cabag\CabagLanglink\v620\Utility;

final class LanglinkBrowseLinksUtility {
	/**
	 * get the flag HTML for the xclasses
	 *
	 * @param	int		UID of the page
	 * @return	string	HTML output.
	 */
	public static function getFlagHTML($uid)	{
		global $BACK_PATH;
		if($uid > 0) {
			
			// check if there are alternative language records on this page
			$listOfLangRecords = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'pages_language_overlay.*,
					sys_language.flag as lang_flag, 
					sys_language.title as lang_title', 
				"pages_language_overlay,sys_language", 
				"pages_language_overlay.deleted = 0 
					AND pages_language_overlay.hidden = 0 
					AND pages_language_overlay.sys_language_uid != 0 
					AND pages_language_overlay.pid =".$uid." 
					AND pages_language_overlay.sys_language_uid = sys_language.uid",
				'',
				'pages_language_overlay.sys_language_uid'
				);
			
			$langLinks = '';
			
			// get default language flag for that page
			$defaultFlag = \TYPO3\CMS\Backend\Utility\BackendUtility::getModTSconfig($uid, 'mod.SHARED.defaultLanguageFlag');
			$defaultFlag = $defaultFlag['value'];
			if (!preg_match('/\.[a-z]+$/', $defaultFlag)) {
				// version 4.5.0 only saves 'gb' for 'gb.gif'
				$defaultFlag .= '.png';
			}
			
			if(empty($defaultFlag)) {
				$defaultFlag = 'unknown.png';
			}
			
			// add flag for link to the default language
			$langLinks .= '
				<a href="#" onclick="return link_typo3Page(\'L:0/'.$uid.'\');">
					<img'.\TYPO3\CMS\Backend\Utility\IconUtility::skinImg($BACK_PATH,'/typo3/sysext/core/Resources/Public/Icons/Flags/'.$defaultFlag,'width="20" height="12"').' alt="" />
				</a>';
			
			// add flag for link to other languages
			if(is_array($listOfLangRecords) && !empty($listOfLangRecords)) {
				foreach($listOfLangRecords as $langRecord) {
					if (!preg_match('/\.[a-z]+$/', $langRecord['lang_flag'])) {
						// version 4.5.0 only saves 'gb' for 'gb.gif'
						$langRecord['lang_flag'] .= '.png';
					}
					$langLinks .= '
						<a href="#" onclick="return link_typo3Page(\'L:'.$langRecord['sys_language_uid'].'/'.$uid.'\');">
							<img'.\TYPO3\CMS\Backend\Utility\IconUtility::skinImg($BACK_PATH,'/typo3/sysext/core/Resources/Public/Icons/Flags/'.$langRecord['lang_flag'],'width="20" height="12"').' alt="" />
						</a>';
				}
			}
		} else {
			$langLinks = '';
		}

		return $langLinks;
	}
}
?>
