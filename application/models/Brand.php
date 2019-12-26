<?php

class Brand extends wsActiveRecord
{
    protected $_table = 'red_brands';
    protected $_orderby = array('name' => 'ASC', 'id' => 'ASC');
    protected $_multilang = array('text' => 'text');

    protected function _defineRelations()
    {

        $this->_relations = array(
            'articles' => array(
                'type' => 'hasMany',
                'class' => 'Shoparticles',
                'field_foreign' => 'brand_id',
                'orderby' => array('ctime' => 'ASC')
            ),
            'balance' => array(
                'type' => 'hasMany',
                'class' => 'BalanceCategory',
                'field_foreign' => 'id_brand',
                'orderby' => array('id' => 'ASC')
            ),
            'subscribes' => array(
                'type' => 'hasMany',
                'class' => 'BrandSubscribeCustomer',
                'field_foreign' => 'brand_id',
                'orderby' => array('id' => 'ASC')
            ),

        );


    }
    public function getIsSubscribe($id){
       $s = wsActiveRecord::useStatic('BrandSubscribeCustomer')->findAll(['brand_id'=>$this->getId(), 'customer_id'=>$id]);
       if($s->count()){ return true;}
       
       return false;
    }
    
    public function getToUrl(){
                           return mb_strtolower(str_replace(' ', '_', str_replace('&', '&amp;', $this->name)));
                        
    }
    public function getToSitemapUrl(){
         return "/all/articles/brands-".$this->getToUrl()."/";
                         
                        
    }

    /**
     * 
     * @return type
     */
    public function getPath()
    	{
    		return "/brands/id/" . $this->getId() .'/'.$this->_generateUrl(str_replace(" ", "_", $this->name))."/";
    	    	}
    /**
    * 
    * @return type
    */
    public function getPathFind(){
        return "/all/articles/brands-".$this->_generateUrl(str_replace(" ", "_", $this->name))."/";
    }
    public function getPathNew(){
        return "/new/all/brands-".$this->_generateUrl(str_replace(" ", "_", $this->name))."/";
    }
    /**
     * 
     * @return type
     */
    static public function findAllActive()
    {

        $array = [];
        $where = 'FROM ws_articles_sizes
JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id
JOIN red_brands ON ws_articles.brand_id = red_brands.id
WHERE ws_articles_sizes.count >0
AND ws_articles.active =  "y"
AND ws_articles.brand_id >0
and red_brands.hide = 1';

        $brandd = 'SELECT brand_id, brand, COUNT( DISTINCT (ws_articles.id) ) AS cnt ' . $where;
        $brands = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($brandd . ' GROUP BY brand_id ORDER BY  `cnt` DESC ');

        $i = 0;
        foreach ($brands as $brand) {

            if (!in_array($brand->getBrand(), array('<>', '1', 'Italia', 'Made in Germany'))) {
                $brand_obj = new Brand($brand->getBrandId());
                $array[] = array(
                    'id' => $brand->getBrandId(),
                    'name' => mb_strtolower($brand->getBrand()),
                    'cnt' => $brand->getCnt(),
                    'image' => $brand_obj->getImage(),
                    'path' => $brand_obj->getPath()
                );
            }
        }

        return $array;

    }
    /**
     * 
     * @param type $limit
     * @return type
     */
    public  function findActiveArticles ($limit = 8){
        $query = 'SELECT  *
        FROM ws_articles
        WHERE ws_articles.active = "y"
        AND ws_articles.stock not like "0"
        AND ws_articles.status = 3
        AND ws_articles.brand_id = '.$this->id.'
        LIMIT 0,'.$limit;

        $articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($query);

        return $articles;
    }
    /**
     * Количество товара в остатке по бренду
     * @return type
     */
    public function getCountArticles(){
        $sql="SELECT sum(stock) as ctn FROM ws_articles WHERE `stock` NOT LIKE  '0' and status = 3 and brand_id=".$this->id;
        return wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql)->at(0)->ctn;	
    }
    public function getCount(){
        $sql="SELECT count(ws_articles.id) as ctn FROM ws_articles WHERE `stock` NOT LIKE  '0' and status = 3 and brand_id=".$this->id;
        return wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql)->at(0)->ctn;	
    }
    public function getCountsub(){
         $r = wsActiveRecord::findByQueryFirstArray("
                SELECT sum(`ws_order_articles`.`count`) as `ctn` 
                FROM `ws_orders` 
                INNER JOIN `ws_order_articles` on `ws_orders`.`id` =  `ws_order_articles`.`order_id` 
                WHERE `ws_orders`.`track` like '".$this->track."' ")['ctn'];
         if($r) {return $r;}
         return 0;
         
    }
}

