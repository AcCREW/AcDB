<?php
/**
 * Parser Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Parser
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/parser.html
 */
class CParser {

	public static $L_DELIM = '{';
	public static $R_DELIM = '}';

	/**
	 *  Parse a template
	 *
	 * Parses pseudo-variables contained in the specified template view,
	 * replacing them with the data in the second param
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	array
	 * @return	string
	 */
	public static function Parse($sTemplateName, $sModuleName, $arData = array()) {
		$sTemplate = Loader::LoadTemplate($sTemplateName, $sModuleName);

		return self::_Parse($sTemplate, $arData);
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse a String
	 *
	 * Parses pseudo-variables contained in the specified string,
	 * replacing them with the data in the second param
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	public static function ParseString($sTemplateName, $arData) {
		return self::_Parse($sTemplateName, $arData);
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse a template
	 *
	 * Parses pseudo-variables contained in the specified template,
	 * replacing them with the data in the second param
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @return	string
	 */
	public static function _Parse($sTemplate, $arData) {
		if ($sTemplate == '') {
			return false;
		}
        
        $arData['BaseURL'] = ACPATH;

		foreach ($arData as $sKey => $vVal) {
			if (is_array($vVal)) {
				$sTemplate = self::_ParsePair($sKey, $vVal, $sTemplate);
			} else {
				$sTemplate = self::_ParseSingle($sKey, (string)$vVal, $sTemplate);
			}
		}

		return $sTemplate;
	}

	// --------------------------------------------------------------------

	/**
	 *  Set the left/right variable delimiters
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	void
	 */
	public static function SetDelimiters($sL = '{', $sR = '}') {
		self::$L_DELIM = $sL;
		self::$R_DELIM = $sR;
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse a single key/value
	 *
	 * @access	private
	 * @param	string
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	public static function _ParseSingle($sKey, $vVal, $sString) {
		return str_replace(self::$L_DELIM.$sKey.self::$R_DELIM, $vVal, $sString);
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse a tag pair
	 *
	 * Parses tag pairs:  {some_tag} string... {/some_tag}
	 *
	 * @access	private
	 * @param	string
	 * @param	array
	 * @param	string
	 * @return	string
	 */
	public static function _ParsePair($vVariable, $arData, $sString) {
		if (false === ($arMatch = self::_MatchPair($sString, $vVariable))) {
			return $sString;
		}

		$sStr = '';
		foreach ($arData as $sRow) {
			$sTemp = $arMatch['1'];
			foreach ($sRow as $sKey => $vVal) {
				if (!is_array($vVal)) {
					$sTemp = self::_ParseSingle($sKey, $vVal, $sTemp);
				} else {
					$sTemp = self::_ParsePair($sKey, $vVal, $sTemp);
				}
			}

			$sStr .= $sTemp;
		}

		return str_replace($arMatch['0'], $sStr, $sString);
	}

	// --------------------------------------------------------------------

	/**
	 *  Matches a variable pair
	 *
	 * @access	private
	 * @param	string
	 * @param	string
	 * @return	mixed
	 */
	public static function _MatchPair($sString, $vVariable) 	{
		if (!preg_match("|" . preg_quote(self::$L_DELIM) . $vVariable . preg_quote(self::$R_DELIM) . "(.+?)". preg_quote(self::$L_DELIM) . '/' . $vVariable . preg_quote(self::$R_DELIM) . "|s", $sString, $arMatch)) {
			return false;
		}

		return $arMatch;
	}

}
// END Parser Class

/* End of file Parser.php */
/* Location: ./system/libraries/Parser.php */
