<?php

/**
 * queryDB
 *
 * queryDB extends connectDB and queries the DB
 * insert, update, delete
 * data is sanitized and escaped 
 * 
 * @author Maail
 */
 
 //error_reporting(E_ALL | E_WARNING | E_NOTICE);
 //ini_set('display_errors', TRUE);

require_once('connect.php');

class queryDB extends Connect
{
	
	var $result;
	var $last_error;
	var $auto_slashes	=	true;
	var $last_query;
	
	function query($sql)
	{
		
		if ($sql !='')
		{
			$this->last_query = $sql;
			$this->result = mysql_query($sql);
			if($this->result)
			{
				return 1;
				
			}
			else
				$this->last_error = mysql_error();
				return 0;
			
		}
		else
		{
			$this->last_error = "You must pass a sql to the query() function.";
			return 1;
		}
	}
	
	
	function insert_array($table, $data)
	{
		
			if (empty($data))
			{
				$this->last_error = "You must pass an array to the insert_array() function.";
				return false;
			}
		
		$cols = '(';
		$values = '(';
		
		foreach ($data as $key=>$value)
		{     // iterate values to input
			
			$cols .= "$key,";
			
			$col_type = $this->get_column_type($table, $key);  // get column type
			if (!$col_type) return false;  // error!
			
			// determine if we need to encase the value in single quotes
	
			if (is_null($value)) {
				$values .= "NULL,";
			}
			else
			{
			    $value   = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES); 
			    $value   = addslashes($value);
				$values .= "'$value',";
			}
			/*
			elseif (substr_count(MYSQL_TYPES_NUMERIC, "$col_type ")) {
				$values .= "$value,";
			}
			elseif (substr_count(MYSQL_TYPES_DATE, "$col_type ")) {
				$value = $this->sql_date_format($value, $col_type); // format date
				$values .= "'$value',";
			}
			elseif (substr_count(MYSQL_TYPES_STRING, "$col_type ")) {
				if ($this->auto_slashes) $value = addslashes($value);
				$values .= "'$value',";
			}
			*/
	
		}
		$cols = rtrim($cols, ',').')';
		$values = rtrim($values, ',').')';
		
		// insert values
		$sql = "INSERT INTO $table $cols VALUES $values";
		return $this->query($sql);
		//return $sql;
		
	}

	function update_array($table, $data, $condition)
	{
	
		// Updates a row into the database from key->value pairs in an array. The
		// array passed in $data must have keys for the table's columns. You can
		// not use any MySQL functions with string and date types with this
		// function.  You must use insert_sql for that purpose.
		// $condition is basically a WHERE claus (without the WHERE). For example,
		// "column=value AND column2='another value'" would be a condition.
		// Returns the number or row affected or true if no rows needed the update.
		// Returns false if there is an error.
		
		if (empty($data)) {
			$this->last_error = "You must pass an array to the update_array() function.";
			return false;
		}
		
		$sql = "UPDATE $table SET";
		foreach ($data as $key=>$value) {     // iterate values to input
			
			$sql .= " $key=";
			$value = filter_var($value, FILTER_SANITIZE_STRING,  FILTER_FLAG_NO_ENCODE_QUOTES); 
			$value = addslashes($value);
			$sql .= "'$value',";
			
				/*$col_type = $this->get_column_type($table, $key);  // get column type
				if (!$col_type) return false;  // error!
				
				// determine if we need to encase the value in single quotes
				if (is_null($value)) {
					$sql .= "NULL,";
				}
				elseif (substr_count(MYSQL_TYPES_NUMERIC, "$col_type ")) {
					$sql .= "$value,";
				}
				elseif (substr_count(MYSQL_TYPES_DATE, "$col_type ")) {
					$value = $this->sql_date_format($value, $col_type); // format date
					$sql .= "'$value',";
				}
				elseif (substr_count(MYSQL_TYPES_STRING, "$col_type ")) {
					if ($this->auto_slashes) $value = addslashes($value);
					$sql .= "'$value',";
				
			}
			*/
	
		}
		$sql = rtrim($sql, ','); // strip off last "extra" comma
		if (!empty($condition)) $sql .= " WHERE $condition";
		
		// insert values
		return $this->query($sql);
	}
		
		
		function get_column_type($table, $column) {
		
		// Gets information about a particular column using the mysql_fetch_field
		// function.  Returns an array with the field info or false if there is
		// an error.
	
		$r = mysql_query("SELECT $column FROM $table");
		if (!$r) {
			$this->last_error = mysql_error();
			return false;
		}
		$ret = mysql_field_type($r, 0);
		if (!$ret) {
			$this->last_error = "Unable to get column information on $table.$column.";
			mysql_free_result($r);
			return false;
		}
		mysql_free_result($r);
		return $ret;
		
	}
	
	function get_array()
	{
		
		if($this->result)
		{
			$data = mysql_fetch_assoc($this->result);
			if(is_array($data))
			{
				foreach ($data as $key => $row) {
					$data[$key] = htmlspecialchars(stripslashes($row));
				}
			}
			return $data;
		}
		else
		{
			return 0;
		}
	}
	
	function get_numrows()
	{
		if($this->result)
			return mysql_num_rows($this->result);
		else
			return 0;
			
	}
	
	function get_id()
	{
		//if (isset($this->con))
		//{
			return mysql_insert_id();
		//}
	}
	
	function last_error()
	{
		echo $this->last_error;
	}
		
	function print_last_query()
	{
		echo $this->last_query;
	}
	
	
}
?>