<?php
namespace Android2Chrome\db;

//defined('WS_EXEC') or die('Restricted access');

abstract class WSDB {
	
	var $_query;
	var $_result;
	var $_connection;
	var $_nameQuote		= '`';
	var $_table_prefix	= '';
	var $_hasQuoted = null;
	
	
//	function setQuery($query){
//		$this->_query = $query;
//	}

/**
	 * Sets the SQL query string for later execution.
	 *
	 * This function replaces a string identifier <var>$prefix</var> with the
	 * string held is the <var>_table_prefix</var> class variable.
	 *
	 * @access public
	 * @param string The SQL query
	 * @param string The offset to start selection
	 * @param string The number of results to return
	 * @param string The common table prefix
	 */
	function setQuery( $sql, $offset = 0, $limit = 0, $prefix='#__' )
	{
		$this->_sql		= $this->replacePrefix( $sql, $prefix );
		$this->_limit	= (int) $limit;
		$this->_offset	= (int) $offset;
	}

	/**
	 * This function replaces a string identifier <var>$prefix</var> with the
	 * string held is the <var>_table_prefix</var> class variable.
	 *
	 * @access public
	 * @param string The SQL query
	 * @param string The common table prefix
	 */
	function replacePrefix( $sql, $prefix='#__' )
	{
		$sql = trim( $sql );

		$escaped = false;
		$quoteChar = '';

		$n = strlen( $sql );

		$startPos = 0;
		$literal = '';
		while ($startPos < $n) {
			$ip = strpos($sql, $prefix, $startPos);
			if ($ip === false) {
				break;
			}

			$j = strpos( $sql, "'", $startPos );
			$k = strpos( $sql, '"', $startPos );
			if (($k !== FALSE) && (($k < $j) || ($j === FALSE))) {
				$quoteChar	= '"';
				$j			= $k;
			} else {
				$quoteChar	= "'";
			}

			if ($j === false) {
				$j = $n;
			}

			$literal .= str_replace( $prefix, $this->_table_prefix,substr( $sql, $startPos, $j - $startPos ) );
			$startPos = $j;

			$j = $startPos + 1;

			if ($j >= $n) {
				break;
			}

			// quote comes first, find end of quote
			while (TRUE) {
				$k = strpos( $sql, $quoteChar, $j );
				$escaped = false;
				if ($k === false) {
					break;
				}
				$l = $k - 1;
				while ($l >= 0 && $sql{$l} == '\\') {
					$l--;
					$escaped = !$escaped;
				}
				if ($escaped) {
					$j	= $k+1;
					continue;
				}
				break;
			}
			if ($k === FALSE) {
				// error in the query - no end quote; ignore it
				break;
			}
			$literal .= substr( $sql, $startPos, $k - $startPos + 1 );
			$startPos = $k+1;
		}
		if ($startPos < $n) {
			$literal .= substr( $sql, $startPos, $n - $startPos );
		}
		return $literal;
	}
	
	function _loadResult(){
		//echo 'ok';
		if (!$result = mysql_query($this->_sql, $this->_connection)){
			//echo $this->_query;
			error ("Query not valid: {$this->getQuery()}");
			//error ("SQL error");
		}
		//echo $this->_query;
		$this->_result = $result;
	}
	
	function query(){
		return;
	}
	
	/**
	 * This method loads the first field of the first row returned by the query.
	 *
	 * @access	public
	 * @return The value returned in the query or null if the query failed.
	 */
	function loadResult(){
		return;
	}
	
	/**
	 * Load an array of single field results into an array
	 *
	 * @access	public
	 */
	function loadResultArray($numinarray = 0)
	{
		return;
	}
	
	/**
	* Fetch a result row as an associative array
	*
	* @access	public
	* @return array
	*/
	function loadAssoc()
	{
		return;
	}
	
	function loadAssocList( $key='' )
	{
		return;
	}
	
	/**
	* This global function loads the first row of a query into an object
	*
	* @access	public
	* @return 	stdClass
	*/
	function loadObject( )
	{
		return;
	}
	
	function loadObjectList( $key='' )
	{
		return;
	}
	
	/**
	 * Description
	 *
	 * @access public
	 */
	function insertid()
	{
		return;
	}
	
	/**
	* Get a quoted database escaped string
	*
	* @param	string	A string
	* @param	boolean	Default true to escape string, false to leave the string unchanged
	* @return	string
	* @access public
	*/
	function quote( $text, $escaped = true )
	{
		if($text === null){
			return 'null';
		}
		return '\''.($escaped ? $this->getEscaped( $text ) : $text).'\'';
	}
	
	/**
	 * Get a database escaped string
	 *
	 * @param	string	The string to be escaped
	 * @param	boolean	Optional parameter to provide extra escaping
	 * @return	string
	 * @access	public
	 * @abstract
	 */
	function getEscaped( $text, $extra = false )
	{
		return;
	}
	
	/**
	 * Get the active query
	 *
	 * @access public
	 * @return string The current value of the internal SQL vairable
	 */
	function getQuery()
	{
		return $this->_sql;
	}
	
	/**
	 * Quote an identifier name (field, table, etc)
	 *
	 * @access	public
	 * @param	string	The name
	 * @return	string	The quoted name
	 */
	function nameQuote( $s )
	{
		// Only quote if the name is not using dot-notation
		if (strpos( $s, '.' ) === false)
		{
			$q = $this->_nameQuote;
			if (strlen( $q ) == 1) {
				return $q . $s . $q;
			} else {
				return $q{0} . $s . $q{1};
			}
		}
		else {
			return $s;
		}
	}
	
	/**
	 * Inserts a row into a table based on an objects properties
	 *
	 * @access	public
	 * @param	string	The name of the table
	 * @param	object	An object whose properties match table fields
	 * @param	string	The name of the primary key. If provided the object property is updated.
	 */
	function insertObject( $table, &$object, $keyName = NULL )
	{
		return;
	}
	
	/**
	 * Description
	 *
	 * @access public
	 * @param [type] $updateNulls
	 */
	function updateObject( $table, &$object, $keyName, $updateNulls=true )
	{
		return;
	}
	
	/**
	 * Checks if field name needs to be quoted
	 *
	 * @access public
	 * @param string The field name
	 * @return bool
	 */
	function isQuoted( $fieldName )
	{
		if ($this->_hasQuoted) {
			return in_array( $fieldName, $this->_quoted );
		} else {
			return true;
		}
	}
}