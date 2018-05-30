<?php
class wsFileSize extends wsActiveRecord
{
	protected $_table = 'ws_file_sizes';
	protected $_orderby = array('default' => 'DESC',
								'id' => 'ASC');

	public static function getDefault()
	{
		return wsActiveRecord::useStatic(self::$_file_size_class)->findFirst(array('default' => 1));
	}
}
?>
