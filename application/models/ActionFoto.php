<?php
    class ActionFoto extends wsActiveRecord
    {
        protected $_table = 'red_action_fotos';
        protected $_orderby = array( 'status'=>'ASC' ,'id' => 'DESC');


      protected function _defineRelations()
        {
            	$this->_relations = array(	'score' => array(
										'type'=>'hasMany',
										'class'=>'ActionFotoscore',
										'field_foreign'    => 'image_id',
                                        'orderby'        => array('id' => 'ASC'),
                                        'onDelete'        => 'delete')
                );

        }
 public function isScore(){
     $score = wsActiveRecord::useStatic('ActionFotoscore')->findFirst(array('ip'=>$_SERVER['REMOTE_ADDR'],'image_id'=>$this->id));
     if($score) return false;
     else return true;
 }
         public function getSystemPath($type = NULL, $filename = NULL)
        {
            if ($type === NULL)
                $type = $this->type;
            if ($filename === NULL)
                $filename = $this->filename;

            $path = '';
            switch (strtolower($type))
            {
                case 1:
                case 2:
                case 3:
                    $path =  "/files/i" . ((int) $type ). "/{$filename}";
                    break;

                default:
                    $path =  "/files/org/{$filename}";
                    if (!is_file($path))
                        $path =  "/files/i3/{$filename}";
            }
                    return $path;
        }
  public function getImagePath($type = 1)
    {
        $folder ='/files/org/';
        return $folder.$this->getFilename();
        switch ($type) {
            case 'small_basket':
                return SITE_URL . '/mimage/type/1/width/36/height/36/crop/false/fill/true/fill_color/255_255_255/filename/' . $this->getFilename();
            case 'homepage':
                return SITE_URL . '/mimage/type/1/width/546/height/365/crop/false/fill/true/fill_color/255_255_255/filename/' . $this->getFilename();
            case 'listing':
                return SITE_URL . '/mimage/type/1/width/155/height/155/crop/false/fill/true/fill_color/255_255_255/filename/' . $this->getFilename();
            case 'detail':
                return SITE_URL . '/mimage/type/1/width/360/height/360/crop/false/fill/true/fill_color/255_255_255/filename/' . $this->getFilename();
            default:
                return SITE_URL . '/mimage/filename/' . $this->getFilename();
        }
    }
    public function _beforeDelete()
    {
        $folder = $_SERVER['DOCUMENT_ROOT'].'/files/org/';
        $name = explode('.',$this->getFilename());
        if (file_exists($folder.$name[0].'_w155_h132_cf_ft_fc255_255_255.'.$name[1]))
            @unlink($folder.$name[0].'_w155_h132_cf_ft_fc255_255_255.'.$name[1]);
         if (file_exists($folder.$name[0].'_w70_h70_cf_ft_fc255_255_255.'.$name[1]))
            @unlink($folder.$name[0].'_w70_h70_cf_ft_fc255_255_255.'.$name[1]);
         if (file_exists($folder.$name[0].'_w155_h155_cf_ft_fc255_255_255.'.$name[1]))
            @unlink($folder.$name[0].'_w155_h155_cf_ft_fc255_255_255.'.$name[1]);
         unlink($folder.$this->getFilename());
        return true;
    }

    }
?>