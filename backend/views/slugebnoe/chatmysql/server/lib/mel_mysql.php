<?php

/**
 * Mel_Mysql
 * 
 * @version		1.4.0
 * @author		Dmitry "Melnaron" Gureev (http://melnaron.net)
 * 
 * 2009-02-16
 * 	+ All functions has been renamed in camelCase;
 */

class Mel_Mysql
{
	private static $link;
	private static $connected;
	
	/**
	 * ������������� ���������� � ����� ������
	 *
	 * @param string $hostname
	 * @param string $username
	 * @param string $password
	 * @param string $database
	 * @param boolean $errorexit[default=false]
	 * @param string $charset[default=utf8]
	 */
	public static function connect($hostname, $username, $password, $database, $errorexit = false, $charset = 'utf8')
	{
		for ($i = 0; $i < 5 && ! self::$link; $i++) {
			if (self::$link = @mysql_connect($hostname, $username, $password)) {
				break;
			}
			sleep(1);
		}
		
		if (self::$link) {
			if (! empty($charset) && version_compare(mysql_get_server_info(self::$link), '4.1.0', '>=')) {
 				mysql_query("SET NAMES '$charset'");
			}
			
			if (mysql_select_db($database)) {
				self::$connected = true;
			}
		}
		
		if (self::$connected) {
			return true;
		} else {
			if ($errorexit) exit(self::error());
		}
	}
	
	/**
	 * ��������� ���������� � ����� ������ ���� ��� ���� �����������
	 */
	public static function disconnect()
	{
		if (self::$link) {
			return mysql_close(self::$link);
		}
		
		return false;
	}
	
	/**
	 * ���������� true ���� ���������� �����������
	 *
	 * @return true or false
	 */
	public static function connected()
	{
		return self::$connected;
	}
	
	/**
	 * ���������� ������ ������ ��������� ��������
	 *
	 * @return string
	 */
	public static function error()
	{
		return 'MySQL error: ' . ((self::$link) ? mysql_error(self::$link) : mysql_error());
	}
	
	/**
	 * ����������� � ���� ������ ���� �� �����. ������� ����������� ���.
	 *
	 * @param string $filename
	 * @return array of errors or true
	 */
	public static function import($filename)
	{
		if (file_exists($filename)) {
			$sql = str_replace("\r", '', file_get_contents($filename));
			$qrs = explode("\n", $sql);
			foreach ($qrs as $i => $qs) {
				if ((strpos($qs, '--') !== false) || empty($qs)) {
					unset($qrs[$i]);
				}
			}
			$sql = str_replace("\n", '', implode("\n", $qrs));
			$qrs = explode(';', trim($sql, '; '));
			foreach ($qrs as $qs) {
				if (! self::query($qs)) {
					$e[] = self::error();
				}
			}
		} else {
			$e[] = 'File ('.$filename.') not found!';
		}
		
		return (isset($e)) ? $e : true;
	}
	
	/**
	 * ���������� �����������
	 * 
	 * @return escaped string
	 * @param $string string
	 */
	public static function escape($string)
	{
		if (! self::$connected) {
			return false;
		}
		
		return mysql_real_escape_string($string, self::$link);
	}
	
	/**
	 * ��������� ������������ ������
	 *
	 * @param string $query
	 * @return resource
	 */
	public static function query($query)
	{
		if (! self::$connected) {
			return false;
		}
		
		return mysql_query($query, self::$link);
	}
	
	/**
	 * ����������� ��������� ������� � ������ ������.
	 * ���� �������� $all = true, �� ����������� ��� ������, ����� ������ ����.
	 * ���� �������� $array = true, �� ��������������� ������ ������������ � ���� �������� (�� ��������������) �������.
	 *
	 * @param resource $result
	 * @param boolean[optional] $all
	 * @param boolean[optional] $array
	 * @return array or array of arrays
	 */
	public static function fetch($result, $all = false, $array = false)
	{
		if (! self::$connected || ! $result) {
			return false;
		}
		
		if ($all) {
			if ($array) {
				while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
					$rows[] = $row;
				}
			} else {
				while ($row = mysql_fetch_assoc($result)) {
					$rows[] = $row;
				}
			}
			return (isset($rows)) ? $rows : false;
		} else {
			if ($array) {
				return mysql_fetch_array($result, MYSQL_NUM);
			} else {
				return mysql_fetch_assoc($result);
			}
		}
	}
	
	/**
	 * ����� ������ ��������� ��� �������� ������
	 * @return array
	 * @param object $result
	 * @param object $all[optional]
	 */
	public static function fetchArray($result, $all = false)
	{
		return self::fetch($result, $all, true);
	}
	
	/**
	 * ����� ������ ��������� ��� ������������� ������
	 * @return array
	 * @param object $result
	 * @param object $all[optional]
	 */
	public static function fetchAssoc($result, $all = false)
	{
		return self::fetch($result, $all);
	}
	
	/**
	 * ���������� ���������� ��������� �����
	 *
	 * @param resource $result
	 * @return integer
	 */
	public static function numRows($result)
	{
		if (! self::$connected) {
			return false;
		}
		
		return mysql_num_rows($result);
	}
	
	/**
	 * ���������� ���������� ���������� � ��������� �������� �����
	 * 
	 * @return integer
	 */
	public static function affectedRows() {
		if (! self::$connected) {
			return false;
		}
		
		return mysql_affected_rows(self::$link);
	}
	
	/**
	 * ���������� ����������������� ����� ��������� ���������� ������
	 *
	 * @return integer
	 */
	public static function insertId()
	{
		if (! self::$connected) {
			return false;
		}
		
		return mysql_insert_id(self::$link);
	}
	
	/**
	 * ��������� � ������� $table ��������� �������� $values
	 *
	 * @param string $table
	 * @param array $values
	 * @return integer (last insert id) or false
	 */
	public static function insert($table, $values)
	{
		if (! self::$connected) {
			return false;
		}
		
		$fields = array_keys($values);
		$result = self::query("INSERT INTO `$table` (`".implode('`, `', $fields)."`) VALUES ('".implode("', '", $values)."')");
		
		return ($result) ? ((self::insertId()) ? self::insertId() : true) : false;
	}
	
	/**
	 * �������� �� ������� $table ���� $what
	 *
	 * @param string $table
	 * @param string $what
	 * @param string[optional] $where
	 * @param string[optional] $order
	 * @return array of arrays or false
	 */
	public static function select($table, $what = '*', $where = null, $order = null, $limit = null)
	{
		if (! self::$connected) {
			return false;
		}
		
		$q = "SELECT $what FROM `$table`";
		
		if ($where) {
			$q .= " WHERE $where";
		}
		
		if ($order) {
			$q .= " ORDER BY $order";
		}
		
		if ($limit) {
			$q .= " LIMIT $limit";
		}
		
		$result = self::query($q);
		
		return ($result && self::numRows($result)) ? self::fetchAssoc($result, true) : false;
	}
	
	/**
	 * �������� �� ������� $table ���� $what
	 *
	 * @param string $table
	 * @param string $what
	 * @param string[optional] $where
	 * @return array or false
	 */
	public static function selectRow($table, $what = '*', $where = null, $order = null)
	{
		if (! self::$connected) {
			return false;
		}
		
		$q = "SELECT $what FROM `$table`";
		
		if ($where) {
			$q .= " WHERE $where";
		}
		
		if ($order) {
			$q .= " ORDER BY $order";
		}
		
		$q .= " LIMIT 1";
		
		$result = self::query($q);
		
		return ($result && self::numRows($result)) ? self::fetchAssoc($result) : false;
	}
	
	/**
	 * ���������� �������� ���������� ���� � ������ ������� ������������� ������� $where
	 * @return string
	 * @param string $table
	 * @param string $field
	 * @param string $where
	 * @param string $order[optional]
	 */
	public static function selectField($table, $field, $where = null, $order = null)
	{
		if (! self::$connected) {
			return false;
		}
		
		$q = "SELECT $field FROM `$table`";
		
		if ($where) {
			$q .= " WHERE $where";
		}
		
		if ($order) {
			$q .= " ORDER BY $order";
		}
		
		$q .= " LIMIT 1";
		
		$result = self::query($q);
		
		return ($result && self::numRows($result) && ($field = self::fetchArray($result))) ? $field[0] : false;
	}
	
	/**
	 * ���������� ���������� ����� � ������� $table �������������� ������� $where
	 * @return int
	 * @param object $table
	 * @param object $where[optional]
	 */
	public static function selectCount($table, $where = null)
	{
		if (! self::$connected) {
			return false;
		}
		
		$q = "SELECT COUNT(*) FROM `$table`";
		
		if ($where) {
			$q .= " WHERE $where";
		}
		
		$result = self::query($q);
		
		return ($result && self::numRows($result) && ($count = self::fetchArray($result))) ? $count[0] : false;
	}
	
	/**
	 * �������� �� ������� $table ���� $what � ���������� �� � ���� ������� $class
	 *
	 * @param string $table
	 * @param string $what
	 * @param string[optional] $where
	 * @param string[optional] $class
	 * @return object or false
	 */
	public static function selectAsObject($table, $what = '*', $where = null, $order = null, $class = 'stdClass')
	{
		if (! self::$connected) {
			return false;
		}
		
		$q = "SELECT $what FROM `$table`";
		
		if ($where) {
			$q .= " WHERE $where";
		}
		
		if ($order) {
			$q .= " ORDER BY $order";
		}
		
		$q .= " LIMIT 1";
		
		$result = self::query($q);
		
		if ($result && self::numRows($result)) {
			if (class_exists($class)) {
				$o = new $class();
			} else {
				$o = new stdClass();
			}
			
			foreach (self::fetchAssoc($result) as $field => $value) {
				$o->$field = $value;
			}
			
			return $o;
		} else {
			return false;
		}
	}
	
	/**
	 * ��������� ���� � ������� $table
	 *
	 * @param string $table
	 * @param array $values
	 * @param string[optional] $where
	 * @return true or false
	 */
	public static function update($table, $values, $where = null)
	{
		if (! self::$connected) {
			return false;
		}
		
		$q = "UPDATE `$table`";
		
		$st = "";
		
		foreach ($values as $field => $value) {
			if (! is_numeric($value)) {
				$value = "'$value'";
			}
			$st .= "`$field` = $value, ";
		}
		
		$q .= " SET ".trim($st, ', ');
		
		if ($where) {
			$q .= " WHERE $where";
		}
		
		return (self::query($q)) ? true : false;
	}
	
	/**
	 * ������� ������ �� ������� $table �������������� ������� $where
	 *
	 * @param string $table
	 * @param string[optional] $where
	 * @return true or false
	 */
	public static function delete($table, $where = null)
	{
		if (! self::$connected) {
			return false;
		}
		
		$q = "DELETE FROM `$table`";
		
		if ($where) {
			$q .= " WHERE $where";
		}
		
		return (self::query($q)) ? true : false;
	}
}

?>