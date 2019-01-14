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

        );


    }
    public function getPath()
    	{
    		return "/brands/id/" . $this->getId() .'/'.mb_strtolower($this->_generateUrl($this->name)).'/';
    	    	}

    public function getPathFind(){
        return "/all/articles/brands-".mb_strtolower($this->name);
    }

    static public function findAllActive()
    {

        $array = array();
        $where = 'FROM ws_articles_sizes
JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id
WHERE ws_articles_sizes.count >0
AND ws_articles.active =  "y"
AND brand_id >0';

        $brands = 'SELECT brand_id, brand, COUNT( DISTINCT (ws_articles.id) ) AS cnt ' . $where;
        $brands = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($brands . ' GROUP BY brand_id ORDER BY  `cnt` DESC ');

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

    public  function findActiveArticles ($limit = 8){
        $query = 'SELECT distinct(ws_articles.id), ws_articles.*,DATE_FORMAT(ws_articles.data_new,"%Y-%m-%d") as orderctime
        FROM ws_articles_sizes
        JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id
        WHERE ws_articles_sizes.count > 0
        AND ws_articles.active = "y"
        AND ws_articles.stock > 0
        AND (DATE_FORMAT(ws_articles.ctime,"%Y-%m-%d") < DATE_ADD(NOW(), INTERVAL -1 DAY) OR ws_articles.get_now = 1)
        AND ws_articles.brand_id IN ("'.$this->getId().'")
        ORDER BY orderctime DESC, model ASC LIMIT 0,'.$limit;

        $articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($query);

        return $articles;
    }
}

