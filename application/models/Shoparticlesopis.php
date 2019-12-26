<?php
class Shoparticlesopis extends wsActiveRecord
{
protected $_table = 'ws_articles_opis';
protected $_orderby = array('name'=>'ASC');



public static function getDistOpis()
	{
	return wsActiveRecord::useStatic('Shoparticlesopis')->findByQuery("SELECT * FROM  `ws_articles_opis`  GROUP BY  `name` ORDER BY  `ws_articles_opis`.`name` ASC ");
	}
        
public static function getOpisCatId($id)
	{
    $res = [];
	$r = wsActiveRecord::findByQueryArray("SELECT name FROM  `ws_articles_opis` WHERE cat = ".(int)$id);//wsActiveRecord::useStatic('Shoparticlesopis')->findAll(['cat'=>(int)$id]);
	foreach ($r as $value) {
            $res[] = $value->name;
        }
        return $res;
        }
        
        public static function getOpisArray($id = '')
	{
	return wsActiveRecord::useStatic('Shoparticlesopis')->findAll(['id in('.$id.')']);
	}
}
