<?php

class UtilsController extends controllerAbstract {

    public function init() {
        parent::init();
        if ($_SERVER['REMOTE_ADDR']!= '127.0.0.1' && $_SERVER['REMOTE_ADDR']!= '93.74.7.135'){
            //die('404');
        }
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
    }

    public function testemailAction()
    {
            $email =  'notforall@bk.ru';
            $msg = $this->render('email/test.tpl.php');

            require_once('nomadmail/nomad_mimemail.inc.php');
            $mimemail = new nomad_mimemail();
            $mimemail->debug_status = 'no';
            $mimemail->set_from('test@red.ua', 'RED');
            $mimemail->set_to($email, 'Ирина');
            $mimemail->set_charset('UTF-8');
            $mimemail->set_subject('тест 1');
            $mimemail->set_text(make_plain($msg));
            $mimemail->set_html($msg);
            @$mimemail->send(); 

 
            MailerNew::getInstance()->sendToEmail($email, 'Ирина', 'тест 2', $msg);
            die('ok');   
    }

    public function exportAction()
    {
        die();
        $q = "select * from ws_articles_history where customer_id > 30 order by customer_id ASC, ctime ASC";
        $res = mysql_query($q);
        $old = 0;
        $old_c = 0;
        $text = '';
        while($row = mysql_fetch_array ( $res ))
        {
            if(strtotime($row['ctime']) - $old > 60*60)
            {
                $text .= "\n";
                $old = strtotime($row['ctime']);
                $old_c = $row['customer_id'];
            }
            if($row['customer_id'] != $old_c)
            {
                $text .= "\n";
                $old_c = $row['customer_id'];
                $old = strtotime($row['ctime']);
            }
            $r = mysql_query("SELECT id, brand, model from ws_articles where stock>0 and id = " .$row['article_id']);
            $one = mysql_fetch_array ( $r );
            if($one['id'])
                $text .= $one['brand'] . ' (' . $one['model'] . ')-' . $one['id'] . ',';
        }
        file_put_contents ( './tmp/item_views.txt', $text );
        die('nothing here');
        $orders = wsActiveRecord::useStatic('Shoporders')->findAll();

        $text = '';
        foreach($orders as $order)
        {
            $arts = $order->getArticles();
            if($arts->count()>1)
            {
                foreach( $arts as $item)
                {
                    if($item->getArticleDb())
                        $text .= str_replace(',', ' ', $item->getArticleDb()->getTitle()) . '-' . $item->getArticleDb()->getId() . ', ';
                }
                $text.="\n";
            }
        }
        file_put_contents ( './tmp/item_views.txt', $text );
        die('OK');

    }

    public function recacheAction() {
        echo 'recache<br/>';
        $folder = $_SERVER['DOCUMENT_ROOT'].'/files/org/';
        $bc_f = $_SERVER['DOCUMENT_ROOT'].'/files/backup/';
        $i = 0;
        foreach (glob($folder."*255_255_255*.*") as $filename) {
            if (is_file($filename)){
                //

                @copy($filename, $bc_f.pathinfo($filename,PATHINFO_BASENAME));
                @unlink($filename);
                echo $bc_f.pathinfo($filename,PATHINFO_BASENAME).'<br/>';
                //echo "$filename size " . filesize($filename) . "<br/>";
                $i++;
            }

        }

        die('Count: '.$i);
    }


    public function resizebycronAction(){
        // 36_36 155_155 360_360 800_600 396_365
        //$filename_dest = $folder.pathinfo($filename_dest, PATHINFO_BASENAME);
        $folder = $_SERVER['DOCUMENT_ROOT'].'/files/org/';
        foreach (glob($folder."*.*") as $filename) {
            if (is_file($filename)){
                //
                $this->resize(36, 36, $filename);
                $this->resize(155, 155, $filename);
                $this->resize(360, 360, $filename);
                $this->resize(800, 600, $filename);
                $this->resize(396, 365, $filename);
            }

        }


        die('recache complete');
    }

    public function resize($w, $h, $filename_original){
        //2b56eead95251c41f60aab4ba54c6013_w800_h600_cf_ft_fc255_255_255.jpg
        $folder = $_SERVER['DOCUMENT_ROOT'].'/files/'. $w.'_'.$h.'/';
        if (!file_exists($folder)){
            mkdir($folder);
        }

        $filename_dest = pathinfo($filename_original);
        $filename_dest = $folder.$filename_dest['filename'].'_w'.$w.'_h'.$h.'_ct_ft_fc255_255_255.'.$filename_dest['extension'];
        $rvk_image = new RvkImage();

        return $rvk_image->copy($filename_original, $filename_dest, $w, $h, false, true, array(255, 255, 255));
    }
    public function testAction(){
       $orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('status in (0,1,8,9,10,11,14)','box_number is not null or box_number_c is not null'));
      $mas =array();
        foreach($orders as $ord){
            $mas[$ord->getBoxNumber()][]=$ord->getId();
        }
        $text = '';
        foreach($mas as $k=>$v){

            $text .= $k.'('.implode(',',$v).'); ';
        }

            d($text);


    }


    public function regenerationskidkaAction(){
        //$orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('customer_id'=>150,'status<>0'),array('date_create'=>'asc'));
        /* ЕВЖЕНЯ!
         * вот это надо делать для каждого оржера при его подтверждении!!!
         */
        $orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('status<>0 AND skida IS NULL'),array('date_create'=>'asc'));
        foreach ($orders as $order){
            $all_orders_amount = $order->getAllAmount();

            $skidka = 0;
            if ($all_orders_amount <= 700) {
                $skidka = 0;
            } elseif ($all_orders_amount > 700 && $all_orders_amount <= 5000) { //5%
                $skidka = 5;
            } elseif ($all_orders_amount > 5000 && $all_orders_amount <= 12000) { //10%
                $skidka = 10;
            } elseif ($all_orders_amount > 12000) { //15%
                $skidka = 15;
            }
            $query = 'UPDATE ws_orders SET skidka = '.$skidka.' WHERE id = '.$order->getId();
            wsActiveRecord::query($query);
            echo $order->getAllAmount().'#'.$skidka.'<br/>';

        }
            d($orders->count());
            die('here');

    }

}
