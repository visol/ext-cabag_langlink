<?php
namespace Cabag\CabagLanglink\v620\Hooks;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Implementation of RteTransformationHook to correctly recognize a language link as internal link to a page
 *
 * @author	Lorenz Ulrich <lorenz.ulrich@visol.ch>
 * @package TYPO3
 */
class RteTransformationHook extends \TYPO3\CMS\Core\Html\RteHtmlParser {

	public $transformationKey;

	/**
	 * Transformation handler: 'ts_links' / direction: "db"
	 * Converting <A>-tags to <link tags>
	 *
	 * @param string $value Content input
	 * @return string Content output
	 */
	public function transform_db($value) {
		return $this->TS_links_db($value);
	}

	/**
	 * Transformation handler: 'ts_links' / direction: "rte"
	 * Converting <link tags> to <A>-tags
	 *
	 * @param string $value Content input
	 * @return string Content output
	 */
	public function transform_rte($value) {
		return $this->TS_links_rte($value);
	}

	/**
	 * Parse <A>-tag href and return status of email,external,file or page
	 *
	 * @param string $url URL to analyse.
	 * @return array Information in an array about the URL
	 * @todo Define visibility
	 */
	public function urlInfoForLinkTags($url) {
		$info = array();
		$url = trim($url);
		if (substr(strtolower($url), 0, 7) == 'mailto:') {
			$info['url'] = trim(substr($url, 7));
			$info['type'] = 'email';
		} elseif (strpos($url, '?file:') !== FALSE) {
			$info['type'] = 'file';
			$info['url'] = rawurldecode(substr($url, strpos($url, '?file:') + 1));
		} else {
			$curURL = $this->siteUrl();
			$urlLength = strlen($url);
			for ($a = 0; $a < $urlLength; $a++) {
				if ($url[$a] != $curURL[$a]) {
					break;
				}
			}
			$info['relScriptPath'] = substr($curURL, $a);
			$info['relUrl'] = substr($url, $a);
			$info['url'] = $url;
			$info['type'] = 'ext';
			$siteUrl_parts = parse_url($url);
			$curUrl_parts = parse_url($curURL);
			// Hosts should match
			if ($siteUrl_parts['host'] == $curUrl_parts['host'] && (!$info['relScriptPath'] || defined('TYPO3_mainDir') && substr($info['relScriptPath'], 0, strlen(TYPO3_mainDir)) == TYPO3_mainDir)) {
				// If the script path seems to match or is empty (FE-EDIT)
				// New processing order 100502
				if (GeneralUtility::isFirstPartOfStr($info['relUrl'], '?id=L:')) {
					// langlink condition: it is a link to another language
					$info['type'] = 'page';
					$info['url'] = $info['relUrl'];
				} else {
					$uP = parse_url($info['relUrl']);
					if ($info['relUrl'] === '#' . $siteUrl_parts['fragment']) {
						$info['url'] = $info['relUrl'];
						$info['type'] = 'anchor';
					} elseif (!trim($uP['path']) || $uP['path'] === 'index.php') {
						// URL is a page (id parameter)
						$pp = preg_split('/^id=/', $uP['query']);
						$pp[1] = preg_replace('/&id=[^&]*/', '', $pp[1]);
						$parameters = explode('&', $pp[1]);
						$id = array_shift($parameters);
						if ($id) {
							$info['pageid'] = $id;
							$info['cElement'] = $uP['fragment'];
							$info['url'] = $id . ($info['cElement'] ? '#' . $info['cElement'] : '');
							$info['type'] = 'page';
							$info['query'] = $parameters[0] ? '&' . implode('&', $parameters) : '';
						}
					} else {
						$info['url'] = $info['relUrl'];
						$info['type'] = 'file';
					}
				}
			} else {
				unset($info['relScriptPath']);
				unset($info['relUrl']);
			}
		}
		return $info;
	}

}