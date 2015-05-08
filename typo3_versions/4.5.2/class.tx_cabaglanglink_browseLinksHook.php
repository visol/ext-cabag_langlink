<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2007-2011 Ingo Renner <ingo@typo3.org>
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
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


/**
 * browse_links hook that changes the behaviour of cabag_langlink's
 *
 * @author Nils Blattner <nb@cabag.ch
 * @package TYPO3
 * @subpackage cabag_langlink
 */
class tx_cabaglanglink_browseLinksHook implements t3lib_browseLinksHook {
	
	protected $parentObject = null;
	
	protected $additionalParameters = array();
	
	/**
	 * initializes the hook object
	 *
	 * @param	browse_links	parent browse_links object
	 * @param	array			additional parameters
	 * @return	void
	 */
	public function init($parentObject, $additionalParameters) {
		$this->parentObject = $parentObject;
		$this->additionalParameters = &$additionalParameters;
	}

	/**
	 * adds new items to the currently allowed ones and returns them
	 *
	 * @param	array	currently allowed items
	 * @return	array	currently allowed items plus added items
	 */
	public function addAllowedItems($currentlyAllowedItems) {
		return $currentlyAllowedItems;
	}

	/**
	 * modifies the menu definition and returns it
	 *
	 * @param	array	menu definition
	 * @return	array	modified menu definition
	 */
	public function modifyMenuDefinition($menuDefinition) {
		return $menuDefinition;
	}

	/**
	 * returns a new tab for the browse links wizard
	 *
	 * @param	string		current link selector action
	 * @return	string		a tab for the selected link action
	 */
	public function getTab($linkSelectorAction) {
		return '';
	}

	/**
	 * Checks the current url and "redirects" cabag_langlink as pages
	 *
	 * @param	unknown_type		$href
	 * @param	unknown_type		$siteUrl
	 * @param	unknown_type		$info
	 * @return	unknown_type
	 */
	public function parseCurrentUrl($href, $siteUrl, $info) {
		// langlink is parsed as url by default
		if ($info['act'] === 'url') {
			$matches = array();
			// check if the parameter is of the type http://www.example.com/?=L:1/23 and extract the page id
			if (t3lib_div::isFirstPartOfStr($href, $siteUrl) && preg_match('#^\?id=L:(\d+)/(\d+)$#i', substr($href, strlen($siteUrl)), $matches)) {
				// change to the page tab, add the page id and make sure rte doesn't have an external parameter
				$info['act'] = 'page';
				$info['pageid'] = $matches[2];
				unset($this->parentObject->curUrlArray['external']);
			}
		}
		
		return $info;
	}

}

?>
