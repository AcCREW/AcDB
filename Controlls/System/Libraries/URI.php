<?php
class URI {

	/**
     * Current uri string
     *
     * @var string
     * @access public
     */
	var $uri_string;
	/**
     * List of uri segments
     *
     * @var array
     * @access public
     */
	var $segments		= array();

	function __construct() {
        $this->_fetch_uri_string();
        $this->_explode_segments();
	}


	// --------------------------------------------------------------------

	/**
     * Get the URI String
     *
     * @access	private
     * @return	string
     */
	function _fetch_uri_string() {
		
        // Is the request coming from the command line?
        if (php_sapi_name() == 'cli' || defined('STDIN')) {
            $this->_set_uri_string($this->_parse_cli_args());
            return;
        }

        // Let's try the REQUEST_URI first, this will work in most situations
        if ($uri = $this->_detect_uri()) {
            $this->_set_uri_string($uri);
            return;
        }

        // Is there a PATH_INFO variable?
        // Note: some servers seem to have trouble with getenv() so we'll test it two ways
        $path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
        if (trim($path, '/') != '' && $path != "/".SELF) {
            $this->_set_uri_string($path);
            return;
        }

        // No PATH_INFO?... What about QUERY_STRING?
        $path =  (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');
        if (trim($path, '/') != '')  {
            $this->_set_uri_string($path);
            return;
        }

        // As a last ditch effort lets try using the $_GET array
        if (is_array($_GET) && count($_GET) == 1 && trim(key($_GET), '/') != '')  {
            $this->_set_uri_string(key($_GET));
            return;
        }
	}

	// --------------------------------------------------------------------

	/**
     * Set the URI String
     *
     * @access	public
     * @param 	string
     * @return	string
     */
	function _set_uri_string($str) {
		// Filter out control characters
		$str = remove_invisible_characters($str, false);
        
		// If the URI contains only a slash we'll kill it
		$this->uri_string = ($str == '/') ? '' : $str;
	}

	// --------------------------------------------------------------------

	/**
     * Detects the URI
     *
     * This function will detect the URI automatically and fix the query string
     * if necessary.
     *
     * @access	private
     * @return	string
     */
	private function _detect_uri() {
		if (!isset($_SERVER['REQUEST_URI']) || !isset($_SERVER['SCRIPT_NAME'])) {
			return '';
		}

		$uri = $_SERVER['REQUEST_URI'];
		if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
			$uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
		} elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0) {
			$uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
		}

		// This section ensures that even on servers that require the URI to be in the query string (Nginx) a correct
		// URI is found, and also fixes the QUERY_STRING server var and $_GET array.
		if (strncmp($uri, '?/', 2) === 0) {
			$uri = substr($uri, 2);
		}
		$parts = preg_split('#\?#i', $uri, 2);
		$uri = $parts[0];
		if (isset($parts[1])) {
			$_SERVER['QUERY_STRING'] = $parts[1];
			parse_str($_SERVER['QUERY_STRING'], $_GET);
		} else {
			$_SERVER['QUERY_STRING'] = '';
			$_GET = array();
		}

		if ($uri == '/' || empty($uri)) {
			return '/';
		}

		$uri = parse_url($uri, PHP_URL_PATH);

		// Do some final cleaning of the URI and return it
		return str_replace(array('//', '../'), '/', trim($uri, '/'));
	}

	// --------------------------------------------------------------------

	/**
     * Parse cli arguments
     *
     * Take each command line argument and assume it is a URI segment.
     *
     * @access	private
     * @return	string
     */
	private function _parse_cli_args() {
		$args = array_slice($_SERVER['argv'], 1);

		return $args ? '/' . implode('/', $args) : '';
	}

	// --------------------------------------------------------------------

	/**
     * Filter segments for malicious characters
     *
     * @access	private
     * @param	string
     * @return	string
     */
	function _filter_uri($str) {
		// Convert programatic characters to entities
		$bad	= array('$',		'(',		')',		'%28',		'%29');
		$good	= array('&#36;',	'&#40;',	'&#41;',	'&#40;',	'&#41;');

		return str_replace($bad, $good, $str);
	}

	// --------------------------------------------------------------------

	/**
     * Explode the URI Segments. The individual segments will
     * be stored in the $this->segments array.
     *
     * @access	private
     * @return	void
     */
	function _explode_segments() {
		foreach (explode("/", preg_replace("|/*(.+?)/*$|", "\\1", $this->uri_string)) as $val) {
			// Filter segments for security
			$val = trim($this->_filter_uri($val));

			if ($val != '') {
				$this->segments[] = $val;
			}
		}
	}


	// --------------------------------------------------------------------

	/**
     * Fetch a URI Segment
     *
     * This function returns the URI segment based on the number provided.
     *
     * @access	public
     * @param	integer
     * @param	bool
     * @return	string
     */
	function segment($n, $no_result = false) {
		return (!isset($this->segments[$n])) ? $no_result : $this->segments[$n];
	}
    
	// --------------------------------------------------------------------

	/**
     * Segment Array
     *
     * @access	public
     * @return	array
     */
	function segment_array() {
		return $this->segments;
	}

	// --------------------------------------------------------------------

	/**
     * Total number of segments
     *
     * @access	public
     * @return	integer
     */
	function total_segments() {
		return count($this->segments);
	}

}