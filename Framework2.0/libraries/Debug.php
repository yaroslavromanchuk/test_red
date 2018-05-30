<?php
	class Debug/* extends Component*/
	{
		static $comment = '';
		static $table_started = 0;

		static function dump($var, $comment = '')
		{
			self::$table_started = 0;
			if($comment)
				self::$comment = ' - ' . $comment;
			self::_dump($var);
		}
		
		static function _dump(&$var, $parrents = array())
		{
			if ( count($parrents) == 0 )
			{
				self::drawStyle();
			}
			switch ( gettype($var) )
			{
				case 'array':
				case 'object':
					$method = "draw" . ucfirst(gettype($var));
					self::$table_started = 1;
					echo self::$method($var, $parrents);
					break;
				case 'boolean':
					echo self::drawBoolean($var);
					break;
				case 'NULL':
					echo self::drawNull();
					break;
				case 'resource':
					echo self::drawResource($var);
					break;					
				default:
					echo self::drawScalar($var);
					break;
			}
			self::$comment = '';
		}

		static function drawArray(&$var, $parrents = array())
		{
			$hidden = ( count($parrents) > 1 ? ' style="display:none;"' : '' );
			if ( count($parrents) > 0 )
			{
				$found = 0;
				$uKey = uniqid('array', true);
				$var[$uKey] = true;
				foreach ( $parrents as &$parrent )
				{
					if ( is_array($parrent) && isset($parrent[$uKey]) )
					{
						$found++;
					}
				}
				unset($var[$uKey]);
				if ($found > 2)
				{
					echo '<span style="color:red;"><b>*RECURSION*</b></span>';
					return;
				}
			}
			$parrents[] = &$var;
			echo '<table class="debugTable table" cellspacing="1"><tr><td colspan="2" class="arrayHeader" onclick="toggleDebugTable(this);">Array (' . count($var) . ')' . self::$comment . '</td></tr>';
			foreach ( array_keys($var) as $key )
			{
				echo '<tr class="row1"' . $hidden . '><td class="arrayKey">' . $key . '</td><td class="value">';
				self::_dump($var[$key], $parrents);
				echo '</td></tr>';
			}
			echo '</table>';
		}
			
		static function drawObject($var, $parrents = array())
		{
			static $objects = array();
			$hidden = ( count($parrents) > 0 ? ' style="display:none;"' : '' );

			if ( ($id = array_search($var, $objects, true)) !== false )
			{
				$id++;
				echo '<table class="debugTable table" cellspacing="1"><tr><td class="objectHeader" style="cursor: default;">Reference to Object #' . $id . ' (' . get_class($objects[$id-1]) . ')' . self::$comment . '</td></tr></table>';
				return;
			}
			else
			{
				$objects[] = $var;
				$id = count($objects);
			}
			
			$parrents[] = $var;
			
			
			$class = new ReflectionObject($var);
			$className = $class->getName();
			$classArray = array();
			while ( is_object($class) )
			{
				$classArray[] = $class->getName();
				$class = $class->getParentClass();
			}
			$className .= count($classArray) > 1 ? ' : ' . join (' > ', array_reverse($classArray)) : '';
			
			echo '<table class="debugTable table" cellspacing="1"><tr onclick="toggleDebugTable(this);"><td colspan="2" class="objectHeader">Object #' . $id . ' (' . $className . ')' . self::$comment . '</td></tr>';
			//$vars = get_object_vars($var);
			$vars = (array) $var;
			if(!count($vars))
				$vars = (array) $var;
			foreach ( $vars as $key => $value )
			{
				preg_match('/^(\0([^\0]+)\0)?(.*)$/', $key, $matches);
				echo '<tr class="row1"' . $hidden . '><td class="objectKey">';
				
				switch($matches[2])
				{
					case '*':
						echo 'protected: ' . $matches[3];
						break;
					case '':
						echo $matches[3];
						break;
					default:
						echo 'private(' . $matches[2] . '): ' . $matches[3];
						break;
				}
				
				echo '</td><td class="value">';
				self::_dump($value, $parrents);
				echo '</td></tr>';
			}
			echo '</table>';
		}

		static function drawResource(&$var)
		{
			if(!self::$table_started)
				echo '<table class="debugTable table" cellspacing="1"><tr onclick="toggleDebugTable(this);"><td colspan="2" class="resourceHeader">' . gettype($var) . self::$comment . '</td></tr>';
			echo '<span class="' . gettype($var) . '">Resource #</span>'; //!!get ID
			if(!self::$table_started)
			{
				self::$table_started = 1;
				echo '</table>';			
			}		
		}
				
		static function drawScalar(&$var)
		{		
			if(!self::$table_started)
				echo '<table class="debugTable table" cellspacing="1"><tr onclick="toggleDebugTable(this);"><td colspan="2" class="' . gettype($var) . 'Header">' . gettype($var) . self::$comment . '</td></tr><tr><td>';
			echo '<span class="' . gettype($var) . '">' . @htmlentities($var, ENT_QUOTES, 'UTF-8') . '</span>';
			if(!self::$table_started)
			{
				self::$table_started = 1;
				echo '</td></tr></table>';			
			}			
		}
		
		static function drawBoolean(&$var)
		{
			echo '<span class="boolean">' . ( $var ? 'true' : 'false' ) . '</span>';
		}
		
		static function drawNull()
		{
			echo '<span class="null">NULL</span>';
		}
		
		static function drawStyle()
		{
			static $return = true;
			
			if ($return)
			{ 
				echo str_replace('%PATH%','', <<<HMTL
<style type="text/css">
	.debugTable { font-family: Arial; font-size: 12px; padding: 0px; background: #000; margin: 4px 0; cursor: default;}
	.debugTable tr { background: #fff; }
	.debugTable td { padding: 0px 5px; }
	.debugTable td .debugTable { margin: 2px 3px; }
	.debugTable td { padding: 0px 5px; }
	.debugTable .arrayHeader { font-weight: bold; background: #050  color: #fff; cursor: pointer; }
	.debugTable .arrayKey { font-style: oblique; background: #cfc; }
	.debugTable .objectHeader { font-weight: bold; background: #006  color: #fff; cursor: pointer;}
	.debugTable .objectKey { font-style: oblique; background: #ccf; }
	.debugTable .resourceHeader { font-weight: bold; background: #520  color: #fff; cursor: pointer;}
	.debugTable .resourceKey { font-style: oblique; background: #fdc; }
	.debugTable .string { color: #a40; }
	.debugTable .integer, .debugTable .double { color: #f00; }
	.debugTable .null { color: #000; font-weight: bold; }
	.debugTable .boolean { color: #00f; }
</style>
<script type="text/javascript">
function toggleDebugTable(table)
{
	while ( table.tagName.toUpperCase() != 'TABLE' )
	{
		table = table.parentNode;
	}
	for (var r = 1; r < table.rows.length; r++)
	{
		row = table.rows[r];
		row.style.display = ( row.style.display != 'none' ? 'none' : '' );
	}
}

</script>
HMTL
				);
				$return = false;
			}
			
		}
	}
