<?php
/***************************************************************
 *  Copyright notice
 *
 *  Copyright (c) 2009, Sonja Scholz <ss@cabag.ch>
 *  All rights reserved
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

namespace Cabag\CabagLanglink\v620\Hooks;
use TYPO3\CMS\Core\Utility\GeneralUtility;


if (!defined ('TYPO3_MODE'))
	die ('Access denied.');

/**
 * Linkhandler to process custom linking to any kind of configured record.
 *
 * @author	Sonja Scholz <ss@cabag.ch>
 * @package TYPO3
 * @subpackage cabag_patch
 */
class LanglinkTypolinkHook {

	/**
	 * Process the link generation
	 *
	 * @param string $linktxt
	 * @param array $conf
	 * @param string $linkHandlerKeyword Define the identifier that an record is given
	 * @param string $linkHandlerValue Table and uid of the requested record like "tt_news:2"
	 * @param string $linkParams Full link params like "record:tt_news:2"
	 * @param tslib_cObj $pObj
	 * @return string
	 */
	function main($linktxt, $conf, $linkHandlerKeyword, $linkHandlerValue, $linkParams, &$pObj) {
		$this->pObj = &$pObj;
		$addQueryParams = '';
		$tx_cabaglanglink_extconf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cabag_langlink']);
		
		// extract link params like "target", "css-class" or "title"
		$furtherLinkParams = str_replace('?id=L:' . $linkHandlerValue, '', $linkParams); 
		if($furtherLinkParams == $linkParams) {
			$furtherLinkParams = '';
		}

		// check if the parameter includes a language setting
		$splittedValue = GeneralUtility::trimExplode('/',$linkHandlerValue);
		$language = $splittedValue[0];
		$linkParam = $splittedValue[1];
		
		// SS: Check if there is already a L parameter in the params otherwise add the fixed language setting
		if(!empty($conf['additionalParams']) || !empty($conf['additionalParams.'])) {
			$addQueryParams = $conf['additionalParams.'];
		} 
		
		if((strstr($addQueryParams,'&'.$tx_cabaglanglink_extconf['languageParameter'].'=') === false)) {
			$addQueryParams.= '&'.$tx_cabaglanglink_extconf['languageParameter'].'='.$language;
			$conf['additionalParams'] = $addQueryParams;
			unset($conf['parameter.']);
			$conf['parameter'] = $linkParam.$furtherLinkParams;
			
			$localcObj = GeneralUtility::makeInstance('tslib_cObj');
			// build the full link to the record
			$generatedLink = $localcObj->typoLink($linktxt, $conf);
		} else {
			$generatedLink = $linktxt;
		}

		return $generatedLink;
	}
}

?>