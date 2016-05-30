<?php
namespace Cabag\CabagLanglink\v620\XClass;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Cabag\CabagLanglink\v620\Utility\LanglinkBrowseLinksUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\Utility\IconUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Script class for the Element Browser window.
 *
 * @author 	Kasper Skårhøj <kasperYYYY@typo3.com>
 */
class RtehtmlareaBrowseLinks extends \TYPO3\CMS\Rtehtmlarea\BrowseLinks {

	/**
	 * For RTE: This displays all content elements on a page and lets you create a link to the element.
	 *
	 * @return string HTML output. Returns content only if the ->expandPage value is set (pointing to a page uid to show tt_content records from ...)
	 * @todo Define visibility
	 */
	public function expandPage() {
		// Set page id (if any) to expand
		$expPageId = $this->expandPage;
		// If there is an anchor value (content element reference) in the element reference, then force an ID to expand:
		if (!$this->expandPage && $this->curUrlInfo['cElement']) {
			// Set to the current link page id.
			$expPageId = $this->curUrlInfo['pageid'];
		}
		// Draw the record list IF there is a page id to expand:
		if ($expPageId
			&& \TYPO3\CMS\Core\Utility\MathUtility::canBeInterpretedAsInteger($expPageId)
			&& $GLOBALS['BE_USER']->isInWebMount($expPageId)
		) {
			// Set header:
			$out .= $this->barheader($GLOBALS['LANG']->getLL('contentElements') . ':');
			// Create header for listing, showing the page title/icon:
			$mainPageRec = BackendUtility::getRecordWSOL('pages', $expPageId);
			$picon = IconUtility::getSpriteIconForRecord('pages', $mainPageRec);
			$picon .= BackendUtility::getRecordTitle('pages', $mainPageRec, TRUE);
			$out .= $picon . '<br />';
			// Look up tt_content elements from the expanded page:
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'uid,pid,header,hidden,starttime,endtime,fe_group,CType,colPos,bodytext,sys_language_uid',
				'tt_content',
				'pid=' . (int)$expPageId . BackendUtility::deleteClause('tt_content')
				. BackendUtility::versioningPlaceholderClause('tt_content'),
				'',
				'sys_language_uid,colPos,sorting'
			);
			$cc = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
			// Traverse list of records:
			$c = 0;
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$c++;
				$icon = IconUtility::getSpriteIconForRecord('tt_content', $row);
				if ($this->curUrlInfo['act'] == 'page' && $this->curUrlInfo['cElement'] == $row['uid']) {
					$arrCol = '<img' . IconUtility::skinImg($GLOBALS['BACK_PATH'], 'gfx/blinkarrow_left.gif', 'width="5" height="9"')
						. ' class="c-blinkArrowL" alt="" />';
				} else {
					$arrCol = '';
				}
				// Putting list element HTML together:
				$out .= '<img' . IconUtility::skinImg($GLOBALS['BACK_PATH'], ('gfx/ol/join' . ($c == $cc ? 'bottom' : '')
						. '.gif'), 'width="18" height="16"') . ' alt="" />' . $arrCol
					. '<a href="#" onclick="return link_typo3Page(' . GeneralUtility::quoteJSvalue('L:' . $row['sys_language_uid'] . '/' . $expPageId) . ',\'#' . $row['uid'] . '\');">'
					. LanglinkBrowseLinksUtility::getFlagIconForLanguage($row['pid'], $row['sys_language_uid'])
						. $icon
					. BackendUtility::getRecordTitle('tt_content', $row, TRUE) . '</a><br />';
				// Finding internal anchor points:
				if (GeneralUtility::inList('text,textpic', $row['CType'])) {
					$split = preg_split('/(<a[^>]+name=[\'"]?([^"\'>[:space:]]+)[\'"]?[^>]*>)/i', $row['bodytext'], -1, PREG_SPLIT_DELIM_CAPTURE);
					foreach ($split as $skey => $sval) {
						if ($skey % 3 == 2) {
							// Putting list element HTML together:
							$sval = substr($sval, 0, 100);
							$out .= '<img' . IconUtility::skinImg($GLOBALS['BACK_PATH'], 'gfx/ol/line.gif',
									'width="18" height="16"') . ' alt="" />'
								. '<img' . IconUtility::skinImg($GLOBALS['BACK_PATH'], ('gfx/ol/join'
									. ($skey + 3 > count($split) ? 'bottom' : '') . '.gif'), 'width="18" height="16"')
								. ' alt="" />' . '<a href="#" onclick="return link_typo3Page(' . GeneralUtility::quoteJSvalue('L:' . $row['sys_language_uid'] . '/' . $expPageId)
								. ',' . GeneralUtility::quoteJSvalue('#' . $sval) . ');">' . htmlspecialchars((' <A> ' . $sval))
								. '</a><br />';
						}
					}
				}
			}
		}
		return $out;
	}

}
