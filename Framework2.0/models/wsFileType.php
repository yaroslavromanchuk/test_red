<?php
class wsFileType extends wsActiveRecord
{
    protected $_table = 'ws_file_types';
	protected $_orderby = array('default' => 'DESC', 
								'name' => 'ASC');
    
	
	protected function _defineRelations()
	{
		$this->_relations = array('files' => array('type' => 'hasMany',
													'class' => self::$_file_class,
													'field_foreign' => 'file_type_id',
													'onDelete' => 'null'),
								);
	}	
	
	public function getFolder()
	{
		$path = '';
		switch($this->getId())
		{
			//image
			case '1':
					$path = wsConfig::findByCode('image_folder')->getValue();
				break;					
				
			//pdf
			case '2':
					$path = wsConfig::findByCode('pdf_folder')->getValue();				
				break;					

			//Gallery
			case '3':
					$path = wsConfig::findByCode('gallery_folder')->getValue();				
				break;	
				
			default:
					$path = wsConfig::findByCode('image_folder')->getValue();
				break;					
		}
		
		if(!$path)
			$path = wsConfig::findByCode('image_folder')->getValue();
			
		return $path;
	}
	
	
	public function getDefaultFile()
	{
		$image = '';
		switch($this->getId())
		{
			//image & gallery
			case '1':
			case '3':
					$image = wsConfig::findByCode('default_image')->getValue();
				break;
				
			//pdf
			case '2':
					$image = wsConfig::findByCode('default_pdf')->getValue();				
				break;					

		
			default:
					$image = wsConfig::findByCode('default_image')->getValue();
				break;					
		}
		
		if(!$image)
			$image = wsConfig::findByCode('default_image')->getValue();
		
		return $image;
	}	
}
?>