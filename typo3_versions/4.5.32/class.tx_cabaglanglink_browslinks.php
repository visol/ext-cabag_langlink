<?php
/**
 * @author	Sonja Scholz <ss@cabag.ch>
 * @package TYPO3
 */
final class tx_cabaglanglink_browslinks {
	/**
	 * get the flag HTML for the xclasses
	 *
	 * @param	int		UID of the page
	 * @return	string	HTML output.
	 */
	function getFlagHTML($uid)	{
		global $BACK_PATH;
		$uid = intval($uid);
		
		if($uid > 0) {
			
			// check if there are alternative language records on this page
			$listOfLangRecords = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'pages_language_overlay.*,
					sys_language.flag as lang_flag, 
					sys_language.title as lang_title', 
				'pages_language_overlay,sys_language', 
				'pages_language_overlay.deleted = 0 
					AND pages_language_overlay.hidden = 0 
					AND pages_language_overlay.sys_language_uid != 0 
					AND pages_language_overlay.pid ='.$uid.' 
					AND pages_language_overlay.sys_language_uid = sys_language.uid',
				'',
				'pages_language_overlay.sys_language_uid'
				);
			
			$langLinks = '';
			
			// get default language flag for that page
			$defaultFlag = t3lib_BEfunc::getModTSconfig($uid, 'mod.SHARED.defaultLanguageFlag');
			$defaultFlag = $defaultFlag['value'];
			if (!preg_match('/\.[a-z]+$/', $defaultFlag)) {
				// version 4.5.0 only saves 'gb' for 'gb.gif'
				$defaultFlag .= '.gif';
			}
			
			if(empty($defaultFlag)) {
				$defaultFlag = 'unknown.gif';
			}
			
			// add flag for link to the default language
			$langLinks .= '
				<a href="#" onclick="return link_typo3Page(\'L:0/'.$uid.'\');">
					<img'.t3lib_iconWorks::skinImg($BACK_PATH,'gfx/flags/'.$defaultFlag,'width="20" height="12"').' alt="" />
				</a>';
			
			// add flag for link to other languages
			if(is_array($listOfLangRecords) && !empty($listOfLangRecords)) {
				foreach($listOfLangRecords as $langRecord) {
					if (!preg_match('/\.[a-z]+$/', $langRecord['lang_flag'])) {
						// version 4.5.0 only saves 'gb' for 'gb.gif'
						$langRecord['lang_flag'] .= '.gif';
					}
					$langLinks .= '
						<a href="#" onclick="return link_typo3Page(\'L:'.$langRecord['sys_language_uid'].'/'.$uid.'\');">
							<img'.t3lib_iconWorks::skinImg($BACK_PATH,'gfx/flags/'.$langRecord['lang_flag'],'width="20" height="12"').' alt="" />
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
