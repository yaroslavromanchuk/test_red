<?php
    class Shoparticlessize extends wsActiveRecord
    {
        protected $_table = 'ws_articles_sizes';
        protected $_orderby = array( 'count' => 'DESC');


        protected function _defineRelations()
        {
            $this->_relations = [
                        'size' => [
                            'type'=>'hasOne',
                            'class'=>'Size',
                            'field'=>'id_size'
                            ], 
                        'color' => [
                            'type'=>'hasOne',
                            'class'=>'Shoparticlescolor',
                            'field'=>'id_color'
                            ],
                        'article_rod' => [
                            'type'=>'hasOne',
                            'class'=>self::$_shop_articles_class,
                            'field'=>'id_article'
                            ]
                                ];

        }
        /**
         * Заказы конкретного размера
         * @return type
         */
        public function getOrders(){
            
            $sql="SELECT *"
                    . " FROM ws_order_articles"
                    . " WHERE article_id = ".$this->id_article
                    . " and size = ".$this->id_size
                    . " and color = ".$this->id_color
                    . " GROUP BY  `order_id` "
                    . " ORDER BY  `id` ASC ";
             return  wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($sql);
        }
        
        public function getDateOrderOst(){
             $co =  Shoporders::findByQueryFirstArray('
                    SELECT `ws_orders`.date_create 
                    FROM `ws_orders`
                    JOIN ws_order_articles ON `ws_orders`.`id` = ws_order_articles.order_id
                    WHERE  ws_order_articles.`article_id` ='.$this->id_article.' 
                    ORDER BY  `ws_orders`.`id` DESC 
                    LIMIT 1');
		 //$co = wsActiveRecord::useStatic('Shoporders')->findFirst(['customer_id'=>$this->getId()],['id' => 'DESC'])->date_create;
                 
		return $co['date_create'];
        }
    }
