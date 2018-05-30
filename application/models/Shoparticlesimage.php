<?php
    class Shoparticlesimage extends wsActiveRecord
    {
        protected $_table = 'ws_articles_images';
        protected $_orderby = array( 'id' => 'DESC');


        protected function _defineRelations()
        {
            $this->_relations = array(    'article' => array(
            'type'=>'hasOne',
            'class'=>'Shoparticles',
            'field'=>'article_id'), 

            );


        }
        public function getImagePath($type = 1) {
            switch ($type) {
                case 'small_basket':
                    return SITE_URL . Mimeg::getrealpath('/mimage/type/1/width/36/height/36/crop/false/fill/true/fill_color/255_255_255/filename/' . $this->getImage());
                case 'homepage':
                    return SITE_URL . Mimeg::getrealpath('/mimage/type/1/width/396/height/365/crop/false/fill/true/fill_color/255_255_255/filename/' . $this->getImage());
                case 'listing':
                    return SITE_URL . Mimeg::getrealpath('/mimage/type/1/width/155/height/155/crop/false/fill/true/fill_color/255_255_255/filename/' . $this->getImage());
                case 'detail':
                    return SITE_URL . Mimeg::getrealpath('/mimage/type/1/width/360/height/360/crop/false/fill/true/fill_color/255_255_255/filename/' . $this->getImage());
                case 'small_preview':
                    return SITE_URL . Mimeg::getrealpath('/mimage/type/1/width/800/height/600/crop/false/fill/true/fill_color/255_255_255/filename/' . $this->getImage());
					case 'card_product':
                return SITE_URL . Mimeg::getrealpath('/mimage/type/1/width/600/height/600/crop/false/fill/true/fill_color/255_255_255/filename/' . $this->getImage());
                default:
                    return SITE_URL . Mimeg::getrealpath('/mimage/type/1/original/1/filename/' . $this->getImage());
            }
//			echo '<pre>';
//			print_r(SITE_URL);
//			print_r(Mimeg::getrealpath('/mimage/type/1/width/800/height/600/crop/false/fill/true/fill_color/255_255_255/filename/' . $this->getImage()));
//			echo '</pre>';
            //return SITE_URL . '/files/i' . $type . '/' . $this->getImage();
        }
    }

?>