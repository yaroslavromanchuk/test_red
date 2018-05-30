<?php
/**
 * Simple DB work
 *
 * @author Khavilo Dmitry
 * @copyright 2008-2013 Dkavilo Dmitry
 */

class SimpleDB
{
	/**
	 * Find id(row) or returns $pk = 0 ([$pk: 0])
	 *
	 * @param string $table
	 * @param array|string $condition
	 * @param string $orderby orderby field
	 * @param boolean $full switches return format ( false -> int, true -> array() )
	 * @param string $pk Primary Key name
	 * @return int|object
	 */
	public function findBy($table, $condition = null, $orderby = null, $full = true, $pk = 'id', $return_as_array = false)
	{
		$sql = 'SELECT ' . ($full ? '*' : $this->escapeName($pk)) . ' FROM ' . $this->escapeName($table);
		$sql .= $this->compileWhere($condition);
		$sql .= ' ORDER BY ' . ( is_null($orderby) ? $this->escapeName($pk) : $orderby );
		$sql .= ' LIMIT 0, 1';
		$res = $this->executeQuery($sql);

		if ( @mysql_num_rows($res) > 0 )
			if ($full)
			{
				if ($return_as_array)
					return @mysql_fetch_assoc($res);
				else
					return @mysql_fetch_object($res);
			}
			else
				return @mysql_result($res, 0, 0);

		@mysql_free_result($res);
		return ($full ? null : 0);
	}

	/**
	 * Find id(row) or returns $pk = 0 ([$pk: 0])
	 *
	 * @param string $table
	 * @param array|string $condition
	 * @param string $orderby orderby field
	 * @param string $limit limit field
	 * @param boolean|string $full switches return format ( false -> int, true -> array() )
	 * @param string $pk Primary Key name
	 * @return array[int,int,...]|array[object,object,...]
	 */
	public function findAll($table, $condition = null, $orderby = null, $limit = null, $full = true, $pk = 'id')
	{
		$sql = 'SELECT ' . ($full === false ? $this->escapeName($pk) : ($full === true ? '*' : $full)) . ' FROM ' . $this->escapeName($table);
		$sql .= $this->compileWhere($condition);
		$sql .= ( is_null($orderby) ? '' : ' ORDER BY ' . $orderby );
		if (!is_null($limit))
			$sql .= ' LIMIT ' . $limit;
		$res = $this->executeQuery($sql);

		$result = array();
		if ( @mysql_num_rows($res) > 0 )
			if ($full)
				while ( $row = @mysql_fetch_object($res))
					$result[] = $row;
			else
				while ( $row = @mysql_result($res, 0, 0))
					$result[] = $row;

		@mysql_free_result($res);
		return $result;
	}

	/**
	 * Interts new row into $table
	 *
	 * @param string $table
	 * @param array $data
	 * @return int insert id
	 */
	public function insert($table, $data = array())
	{
		if((is_array($data) && !count($data) || (!$data))) return 0;

		$sql = 'INSERT INTO ' . $this->escapeName($table);
		$sql .= $this->compileSet($data);
		$this->executeQuery($sql);
		return mysql_insert_id();
	}

	/**
	 * Deletes a row in $table
	 *
	 * @param string $table
	 * @param array $data
	 * @param array|string $condition
	 */
	public function delete($table, $condition = null)
	{
		if ( is_null($condition) )
			return;
		$sql = 'DELETE FROM ' . $this->escapeName($table);
		$sql .= $this->compileWhere($condition);
		$this->executeQuery($sql);
	}

	/**
	 * Updates a row in $table
	 *
	 * @param string $table
	 * @param array $data
	 * @param array|string $condition
	 */
	public function update($table, $data = array(), $condition = '')
	{
		if((is_array($data) && !count($data) || (!$data))) return 0;

		$sql = 'UPDATE ' . $this->escapeName($table);
		$sql .= $this->compileSet($data);
		$sql .= $this->compileWhere($condition);
		$this->executeQuery($sql);
	}

	/**
	 * Replaces a row in $table
	 *
	 * @param string $table
	 * @param array $data
	 * @return int insert id
	 */
	public function replace($table, $data = array())
	{
		if((is_array($data) && !count($data) || (!$data))) return 0;

		$sql = 'REPLACE INTO ' . $this->escapeName($table);
		$sql .= $this->compileSet($data);
		$this->executeQuery($sql);
		return mysql_insert_id();
	}

	/**
	 * Replaces multiple rows in $table
	 *
	 * @param string $table
	 * @param array $data
	 * @return boolean run
	 */
	public function replace_multiple($table, $data = array())
	{
		if ( !is_array($data) || count($data) == 0 || count($data[0]) == 0 )
			return false;

		$sql = 'REPLACE INTO ' . $this->escapeName($table) . ' (`' . join('`, `', array_keys($data[0])) .'`) VALUES ';

		$order = array_keys($data[0]);
		foreach ( $data as $row )
		{
			$sql .= '(';

			foreach ( $order as $field )
				$sql .=  $this->escapeData($row[$field]) . ', ';

			$sql = substr($sql, 0, -2) . '), ';
		}
		$sql = substr($sql, 0, -2);

		$this->executeQuery($sql);
		return true;
	}

	/**
	 * Create set expression
	 *
	 * @param array|string $condition WARNING: if sting is provided it MUST be ESCAPED
	 * @return unknown
	 */
	protected function compileWhere($condition = '')
	{
		/* Skipping WHERE to create valid query if no data provided */
		if ( !((is_string($condition) && strlen(trim($condition)) > 0) || (is_array($condition) && count($condition) > 0))  )
			return '';

		$sql = ' WHERE ';
		if ( is_string($condition) )
			return $sql . $condition;

		foreach ( $condition as $field => $value )
		{
			if ( is_numeric($field) )
				$sql .= $value . ' AND ';
			else
				$sql .= $this->escapeName($field) . ( is_null($value) ? ' IS NULL' : ' = ' . $this->escapeData($value)) . ' AND ';
		}

		$sql = substr($sql, 0, -5);
		return $sql;
	}

	/**
	 * Creates SET expression
	 *
	 * @param array query data
	 * @return string
	 **/
	protected function compileSet($data = array())
	{
		/* Skipping SET to create valid query if no data provided */
		if ( !is_array($data) || count($data) == 0 )
			return '';

		$sql = ' SET ';
		foreach ( $data as $field => $value )
			$sql .= $this->escapeName($field) . ' = ' . $this->escapeData($value) . ', ';

		$sql = substr($sql, 0, -2);
		return $sql;
	}

	/**
	 * Returns query-safe field of table name.
	 *
	 * @param sting $string
	 * @return string
	 */
	protected function escapeName($string)
	{
		return '`' . $string . '`';
	}

	/**
	 * Returns query-safe field value
	 *
	 * @param unknown_type $string
	 * @return unknown
	 */
	protected function escapeData($string)
	{
		return ( is_null($string) ? 'NULL' : '"' . mysql_real_escape_string($string) . '"' );
	}

	/**
	 * Executes query and returns result pointer.
	 * Puts query and its execution time into (global)$log when (global)$e = 0. $log must be a valid file pointer.
	 *
	 * @param string $sql
	 * @return result
	 */
	public function executeQuery($sql)
	{
		global $log, $e;
		$time = microtime(true);

		($result = @mysql_query($sql)) || print('<!--Query: <i>' . $sql . '</i><br>Error: ' . mysql_error() . '<br>-->');

		/* Logging */
		if ( isset($log) && isset($e) && ($e == 0) )
			fwrite($log, ' -- ' . number_format(microtime(true)-$time,5) . ' -- ' . $sql . "\r\n");

		return $result;
	}

	/**
	 * Return an instance of this class
	 *
	 * @return SimpleDB
	 */
	static function getInstance()
	{
		static $instance;

		if ( !isset($instance) )
		{
			$instance = new SimpleDB();
		}

		return $instance;
	}
	
	public function filterArray($table, $data)
	{
		static $tables = array();
		if ( !isset($tables[$table]) )
		{
			$res = mysql_query('DESCRIBE ' . $table);
			$tables[$table] = array();
			while ( $row = mysql_fetch_row($res) )
				$tables[$table][] = $row[0];
		}
		
		$filteredData = array();
		foreach ( $tables[$table] as $field )
			if ( isset($data[$field]) && $data[$field] != '' )
				$filteredData[$field] = $data[$field];
		return $filteredData;
	}
	
}