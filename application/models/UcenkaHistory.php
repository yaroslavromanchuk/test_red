<?php
class UcenkaHistory extends wsActiveRecord
{
    protected $_table = 'ucenka_history';
    protected $_orderby = array('ctime' => 'DESC');

    protected function _defineRelations()
    {
        $this->_relations = array(
            'admin' => array(
                'type' => 'hasOne',
                'class' => 'Customer',
                'field' => 'admin_id'),
        );
    }


    static function newUcenka($admin, $article, $old, $new, $proc = false)
    {

        $ucenka = new UcenkaHistory();
        $ucenka->setAdminId($admin);
        $ucenka->setArticleId($article);
        $ucenka->setOldPrice($old);
        $ucenka->setNewPrice($new);
		if($proc){
		 $ucenka->setProc($proc);
		}else{
		 $ucenka->setProc(((1 - ($new / $old)) * 100));
		}
       

        $ucenka->save();
        /*$articleitem = new Shoparticles($article);
        $category = new Shopcategories($articleitem->getCategoryId());
        $articleitem->setDopCatId($category->getUsencaCategory());
        $articleitem->setDataUcenki(date('Y-m-d H:i:s'));
        $articleitem->save();*/
        return true;
    }
}

?>