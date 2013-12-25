<?php
namespace Android2Chrome\db;

//defined('WS_EXEC') or die('Restricted access');

use Android2Chrome\db\WSDB;
include_once '/android2chrome/functions/configurazione.php';

class WSDBMysql extends WSDB {
	
	function __construct($configName='read'){
//		$config = WSFactory::getConfig();
		var_dump(Configuration::host);die();
		$db_host = $_CONF->host;//$config->getValue("db_{$configName}_host");
		$db_user = $_CONF->user;//$config->getValue("db_{$configName}_user");
		$db_pass = $_CONF->password;//$config->getValue("db_{$configName}_pass");
		$db_name = $_CONF->db;//$config->getValue("db_{$configName}_name");
		
//		$this->_table_prefix	= $config->getValue("db_{$configName}_prefix", 'cs_');
		
		if (!$this->_connection = mysql_connect($db_host, $db_user, $db_pass)){
			error('Could not connect to database');
		} 
		if (!mysql_select_db($db_name, $this->_connection)){
			error('Could not select database');
		}
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
		$this->_loadResult();
		return $this->_result;
	}
	
	/**
	 * This method loads the first field of the first row returned by the query.
	 *
	 * @access	public
	 * @return The value returned in the query or null if the query failed.
	 */
	function loadResult(){
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($row = mysql_fetch_row( $cur )) {
			$ret = $row[0];
		}
		mysql_free_result( $cur );
		return $ret;
	}
	
	/**
	 * Load an array of single field results into an array
	 *
	 * @access	public
	 */
	function loadResultArray($numinarray = 0)
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = mysql_fetch_row( $cur )) {
			$array[] = $row[$numinarray];
		}
		mysql_free_result( $cur );
		return $array;
	}
	
	/**
	* Fetch a result row as an associative array
	*
	* @access	public
	* @return array
	*/
	function loadAssoc()
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($array = mysql_fetch_assoc( $cur )) {
			$ret = $array;
		}
		mysql_free_result( $cur );
		return $ret;
	}
	
	function loadAssocList( $key='' )
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = mysql_fetch_assoc( $cur )) {
			if ($key) {
				$array[$row[$key]] = $row;
			} else {
				$array[] = $row;
			}
		}
		mysql_free_result( $cur );
		return $array;
	}
	
	/**
	* This global function loads the first row of a query into an object
	*
	* @access	public
	* @return 	stdClass
	*/
	function loadObject( )
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($object = mysql_fetch_object( $cur )) {
			$ret = $object;
		}
		mysql_free_result( $cur );
		return $ret;
	}
	
	function loadObjectList( $key='' )
	{
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = mysql_fetch_object( $cur )) {
			if ($key) {
				$array[$row->$key] = $row;
			} else {
				$array[] = $row;
			}
		}
		mysql_free_result( $cur );
		return $array;
	}
	
	/**
	 * Description
	 *
	 * @access public
	 */
	function insertid()
	{
		return mysql_insert_id( $this->_connection );
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
		$result = mysql_real_escape_string( $text, $this->_connection );
		if ($extra) {
			$result = addcslashes( $result, '%_' );
		}
		return $result;
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
		$fmtsql = 'INSERT INTO '.$this->nameQuote($table).' ( %s ) VALUES ( %s ) ';
		$fields = array();
		foreach (get_object_vars( $object ) as $k => $v) {
			if (is_array($v) or is_object($v) or $v === NULL) {
				continue;
			}
			if ($k[0] == '_') { // internal field
				continue;
			}
			$fields[] = $this->nameQuote( $k );
			$values[] = $this->isQuoted( $k ) ? $this->Quote( $v ) : (int) $v;
		}
		$this->setQuery( sprintf( $fmtsql, implode( ",", $fields ) ,  implode( ",", $values ) ) );
		if (!$this->query()) {
			return false;
		}
		$id = $this->insertid();
		if ($keyName && $id) {
			$object->$keyName = $id;
		}
		return true;
	}
	
	/**
	 * Description
	 *
	 * @access public
	 * @param [type] $updateNulls
	 */
	function updateObject( $table, &$object, $keyName, $updateNulls=true )
	{
		$fmtsql = 'UPDATE '.$this->nameQuote($table).' SET %s WHERE %s';
		$tmp = array();
		foreach (get_object_vars( $object ) as $k => $v)
		{
			if( is_array($v) or is_object($v) or $k[0] == '_' ) { // internal or NA field
				continue;
			}
			if( $k == $keyName ) { // PK not to be updated
				$where = $keyName . '=' . $this->Quote( $v );
				continue;
			}
			if ($v === null)
			{
				if ($updateNulls) {
					$val = 'NULL';
				} else {
					continue;
				}
			} else {
				$val = $this->isQuoted( $k ) ? $this->Quote( $v ) : (int) $v;
			}
			$tmp[] = $this->nameQuote( $k ) . '=' . $val;
		}
		$this->setQuery( sprintf( $fmtsql, implode( ",", $tmp ) , $where ) );
		return $this->query();
	}
	
}