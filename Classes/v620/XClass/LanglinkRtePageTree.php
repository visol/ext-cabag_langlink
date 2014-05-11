<?php
/**
 * Class which generates the page tree
 *
 * @author	Sonja Scholz
 * @package TYPO3
 * @subpackage core
 */

namespace Cabag\CabagLanglink\v620\XClass;
use Cabag\CabagLanglink\v620\Utility\LanglinkBrowseLinksUtility;
use TYPO3\CMS\Backend\Utility\IconUtility;

class LanglinkRtePageTree extends \TYPO3\CMS\Rtehtmlarea\PageTree {

	/**
	 * Create the page navigation tree in HTML
	 *
	 * @param	array		Tree array
	 * @return	string		HTML output.
	 */
	function printTree($treeArr='')	{
		global $BACK_PATH;
		$titleLen=intval($GLOBALS['BE_USER']->uc['titleLen']);
		if (!is_array($treeArr))	$treeArr=$this->tree;

		$out='';
		$c=0;

		foreach($treeArr as $k => $v)	{
			$c++;
			$bgColorClass = ($c+1)%2 ? 'bgColor' : 'bgColor-10';
			if ($GLOBALS['SOBE']->browser->curUrlInfo['act']=='page' && $GLOBALS['SOBE']->browser->curUrlInfo['pageid']==$v['row']['uid'] && $GLOBALS['SOBE']->browser->curUrlInfo['pageid'])	{
				$arrCol='<td><img' . IconUtility::skinImg($BACK_PATH,'gfx/blinkarrow_right.gif','width="5" height="9"').' class="c-blinkArrowR" alt="" /></td>';
				$bgColorClass='bgColor4';
			} else {
				$arrCol='<td></td>';
			}

			$aOnClick = 'return jumpToUrl(\''.$this->thisScript.'&act='.$GLOBALS['SOBE']->browser->act.'&editorNo='.$GLOBALS['SOBE']->browser->editorNo.'&contentTypo3Language='.$GLOBALS['SOBE']->browser->contentTypo3Language.'&contentTypo3Charset='.$GLOBALS['SOBE']->browser->contentTypo3Charset.'&mode='.$GLOBALS['SOBE']->browser->mode.'&expandPage='.$v['row']['uid'].'\');';
			$cEbullet = $this->ext_isLinkable($v['row']['doktype'],$v['row']['uid']) ?
						'<a href="#" onclick="'.htmlspecialchars($aOnClick).'"><img'.IconUtility::skinImg($BACK_PATH,'gfx/ol/arrowbullet.gif','width="18" height="16"').' alt="" /></a>' :
						'';
			
			// Get the flags HTML code depending on the available pages_language_overlay records for the current page
			$langLinks = LanglinkBrowseLinksUtility::getFlagHTML($v['row']['uid']);
			
			$out.='
				<tr class="'.$bgColorClass.'">
					<td nowrap="nowrap"'.($v['row']['_CSSCLASS'] ? ' class="'.$v['row']['_CSSCLASS'].'"' : '').'>'.
					$v['HTML'].
					$this->wrapTitle($this->getTitleStr($v['row'],$titleLen),$v['row'],$this->ext_pArrPages).
					'</td>'.
					$arrCol.
					'<td>'.$langLinks.' '.$cEbullet.'</td>
				</tr>';
		}
		$out='


			<!--
				Navigation Page Tree:
			-->
			<table border="0" cellpadding="0" cellspacing="0" id="typo3-tree">
				'.$out.'
			</table>';
		return $out;
	}
}

?>
