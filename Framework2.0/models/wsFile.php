<?php
//TODO:
// count number of download of file

class wsFile extends wsActiveRecord
{
	protected $_table = 'ws_files';
	protected $_orderby = array('id' => 'ASC');
	protected $_size = '';
	
	protected $_fpath;
    
	protected function _defineRelations()
	{
		$this->_relations = array('file_type' => array('type'=>'hasOne', //belongs to
													'class'=> self::$_file_type_class,
													'field'=>'file_type_id',
													'autoload'=>true),
								'category' => array('type'=>'hasOne', //belongs to
													'class'=> 'Menu',
													'field'=>'category_id',
													'autoload'=>true),
								);
	}
	
	
	public function setSize($value)
	{
		$this->_size = $value;
	}
	
	public function getSize()
	{
		if($this->_size)
			return $this->_size . "/";
		else
			return '';
	}
	
	public function getUrl($size = '')
	{
		return '/storage/images/' . $this->getName();

		$size_url = $size ? 'size/' . $size . '/' : '';
		$url = $size ? wsConfig::findByCode('image_download_url')->getValue() : wsConfig::findByCode('file_download_url')->getValue();
		return $url . $size_url . 'name/' . $this->getName() . '/id/' . $this->getId() . '/';
		/*
		if(wsConfig::findByCode('download_via_script')->getValue() && $this->getId())
			return wsConfig::findByCode('download_script_name')->getValue() . $this->getId();
		else
			return $this->getFilepath();
		*/
	}
	
	public function getOpenUrl()
	{
		return SITE_URL . $this->getFileType()->getFolder() . $this->getFilename();
	}
	
	public function getFilepath()
	{
		//if(!$this->_fpath)
		{
			$name = $this->getLocation() ? $this->getLocation() : $this->getFilename();
			$this->_fpath = $_SERVER['DOCUMENT_ROOT'] . $this->getFileType()->getFolder() . ($this->_size ? $this->getSize() : "") . $name;
		}

		return $this->_fpath;
	}

	public static function getDefaultFile($typeid = 0)
	{
		$tmp = new self::$_file_class();
		$ft = new self::$_file_type_class($typeid);
		$tmp->setFileType($ft);
		$tmp->setLocation($ft->getDefaultFile());
		return $tmp;
	}
		
	public function isOK()
	{
		return 1;
		if(@fopen($this->getFilepath(), "r"))
			return 1;
		else
			return 0;
	}
	
	public function download($type='attachment')
	{
		$range=0;

		$name = $this->getLocation() ? $this->getLocation() : $this->getFilename();
		$ftype = $this->getHeadertype() ? $this->getHeadertype() : 'application/octet-stream';
		$filename = $this->getFilepath();

		if (!$this->isOk()) {
			header ("HTTP/1.0 404 Not Found");
			exit;
		} else {
			$fsize = filesize($filename);
			$ftime = date("D, d M Y H:i:s T", filemtime($filename));
			$fd = @fopen($filename, "rb");
			if (!$fd) {
				header ("HTTP/1.0 403 Forbidden");
				exit;
			}
			if (isset($HTTP_SERVER_VARS["HTTP_RANGE"])) {
				$range = $HTTP_SERVER_VARS["HTTP_RANGE"];
				$range = str_replace("bytes=", "", $range);
				$range = str_replace("-", "", $range);
				if ($range)
					fseek($fd, $range);
			}

			if ($range) {
				header("HTTP/1.1 206 Partial Content");
			} else {
				header("HTTP/1.1 200 OK");
			}
			header("Content-Disposition: $type; filename=\"".($name)."\"");
			header("Last-Modified: $ftime");
			header("Accept-Ranges: bytes");
			header("Content-Length: ".($fsize-$range));
			header("Content-Range: bytes $range-".($fsize -1)."/".$fsize);
			header("Content-type: ".$ftype);

			fpassthru($fd);
			fclose($fd);
			die();
		}
	}	
	
	public function resize($width, $height, $watermark = 0, $sizename = '')
	{
		require_once('upload/class.upload.php');
		$handle = new upload($this->getFilepath(), Registry::get('locale'));	

		//only if image - advert or profile
		if($this->getFileTypeId() == 1 || $this->getFileTypeId() == 6) {		
			//$handle->image_convert = 'jpg';
			$handle->image_resize = true;
			$handle->image_x = $width; //1024
			$handle->image_y = $height; //1024
			$handle->image_ratio_y = true;
			$handle->image_ratio_x = true;
			$handle->image_ratio_no_zoom_in = true;
			if($watermark) {
				$handle->image_watermark = Config::findByCode('watermark_image')->getValue();
				$handle->image_watermark_position = 'BR';
			}
		}

		if ($handle->uploaded) {
			//$handle->file_new_name_body=md5(time()); //no change
			//$width . 'x' . $height
			$handle->process($_SERVER['DOCUMENT_ROOT'].$this->getFileType()->getFolder() . $sizename . '/' . $this->_getFolder($this->getFilename()));
			if ($handle->processed) {
				//$handle->clean();
			} else {
				return $handle->error;
			}
		} else 
			return $handle->error;
		
		return true;	
	}	
	
	protected function _beforeDelete()
	{
		$this->setSize('small');
		@unlink($this->getFilepath());
		$this->setSize('big');
		@unlink($this->getFilepath());
		
		return true;
	}		
}

?>