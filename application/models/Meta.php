<?php

class Meta extends wsActiveRecord
{
   
    public function getMeta($param) {
        $meta = [];
        $title = [];
        $category = $param['category'];
       // 'search'=>$search,
         //  'param' => $param,
          // 'category' => $category,
         //  'order_by' => $page_onpage_order_by['order_by'],
         //  'page' => $page_onpage_order_by['page'],
          // 'Onpage' => $page_onpage_order_by['onPage']
        
        
        if(count($param['filter']) and (in_array($category->parent_id, [54,59,85,106,254]) or in_array($category->id, [54,59,85,106,254]))){
           $meta['nofollow'] = 1; 
        }
        
        
           if(count($param['filter']['brands'])){
               $bb = Brand::findByQueryArray("SELECT id, name FROM `red_brands` WHERE  `id` in(".implode(',', $param['filter']['brands']).")  ");
                  foreach ($bb as $value) {
                             $title[] = $value->name;
                    }
                 if(count($param['filter']) == 1 and count($param['filter']['brands']) == 1){
                  $text = FooterText::Text(['category_id' =>$category->id, 'brand_id' => $param['filter']['brands'][0]]);
                  if($text){
                     $meta['footer']['text'] = $text;
                 }else{
                      $meta['footer']['block'] = 'tut';
                 }
                }else{
                    $meta['footer']['block'] = 'tut';
                }
           }
           
           if(count($param['filter']['sezons'])){
               $sezon = wsActiveRecord::useStatic('Shoparticlessezon')->findByQuery("SELECT * FROM  `ws_articles_sezon` WHERE  `id` IN (".implode(',', $param['filter']['sezons']).")");
               foreach ($sezon as $value) {
                if(in_array($value->id, $param['filter']['sezons'])){
                             $title[] = $value->getName();
                        }
                }
                
                if(count($param['filter']) == 1 and count($param['filter']['sezons']) == 1){
                 $text = FooterText::Text(['category_id' =>$category->id, 'sezon_id' => $param['filter']['sezons'][0]]);
                 if($text){
                     $meta['footer']['text'] = $text;
                 }else{
                      $meta['footer']['block'] = 'tut';
                 }
                }else{
                    $meta['footer']['block'] = 'tut';
                }
           }
           
           if(count($param['filter']['colors'])){
               if(!$category->parent_id){
                   $meta['nofollow'] = 1;
               }
               $color = wsActiveRecord::useStatic('Shoparticlescolor')->findByQuery("SELECT * FROM  `ws_articles_colors` WHERE  `id` IN (".implode(',', $param['filter']['colors']).")");
               foreach ($color as $value) {
                if(in_array($value->id, $param['filter']['colors'])){
                             $title[] = mb_strtolower($value->getName());
                        }
                }
                
                if(count($param['filter']) == 1 and count($param['filter']['colors']) == 1){
                 $text = FooterText::Text(['category_id' =>$category->id, 'color_id' => $param['filter']['colors'][0]]);
                 if($text){
                     $meta['footer']['text'] = $text;
                 }else{
                      $meta['footer']['block'] = 'tut';
                 }
                }else{
                    $meta['footer']['block'] = 'tut';
                }
           }
           
             if(count($param['filter']['sizes'])){
                  $meta['nofollow'] = 1;
                 $size = wsActiveRecord::useStatic('Size')->findByQuery("SELECT * FROM  `ws_sizes` WHERE  `id` IN (".implode(',', $param['filter']['sizes']).")");
               foreach ($size as $value) {
                if(in_array($value->id, $param['filter']['sizes'])){
                             $title[] = mb_strtolower($value->size);
                        }
                }
                
                if(count($param['filter']) == 1 and count($param['filter']['sizes']) == 1){
                   
                  $text = FooterText::Text(['category_id' =>$category->id, 'size_id' => $param['filter']['sizes'][0]]);
                    if($text){
                     $meta['footer']['text'] = $text;
                 }else{
                      $meta['footer']['block'] = 'tut';
                 }
                }else{
                    $meta['footer']['block'] = 'tut';
                }
           }
           if(count($param['filter']['labels'])){
                $meta['nofollow'] = 1;
            $meta['footer']['block'] = 'tut';
           }
           if(count($param['filter']['skidka'])){
                $meta['nofollow'] = 1;
            $meta['footer']['block'] = 'tut';
           }
           
           
           if(count($param['filter']['price'])){
               $meta['nofollow'] = 1;
           }
            if ($param['search']){
                 $title[] = $param['search'];
                $meta['footer']['block'] = 'tut';
                $meta['nofollow'] = 1;
            }
            if($param['order_by']){
                $meta['nofollow'] = 1;
                $meta['footer']['block'] = 'tut';
            }
            if($param['page']){
                 $meta['nofollow'] = 1;
                $meta['footer']['block'] = 'tut';
            }
            
             $t = ' '.implode(', ', $title);
             
             $meta['h1'] = ($category->getH1()?$category->getH1():$category->getName()).($t?' '.$t.' ':'').Translator::get('в интернет магазине RED');
             
        $title_obr_1 = explode(' ', ($category->getH1()?$category->getH1():$category->getName()));
        krsort($title_obr_1);
        $title_obr = implode(' ', $title_obr_1);
        if($category->parent_id == 85){
             $meta['title'] = trim(Translator::get('Купить').' '.($category->getTitle()?$category->getTitle():$category->getName()));
        }elseif(count($param['filter'])){
            $meta['title'] = ($category->getH1()?$category->getH1():$category->getName()).($t?' '.$t.' ':'').trim(Translator::get('dop_title_filter'));
        }elseif($category->getTitle()){
             $meta['title'] = $category->getTitle();//Translator::get('dop_title_no_filter');
        }else{
            $meta['title'] = $meta['h1'].Translator::get('dop_title_no_filter');
        }
        
        if($category->id){
            if($category->id == 85){ 
                $meta['descriptions'] =$category->getDescription();
            }elseif($category->parent_id == 85){
                $meta['descriptions'] = strip_tags($category->getH1().' '.mb_strtolower($t).' '.Translator::get('в интернет магазине RED').$category->getDescription());
            }else{
                if(count($param['filter'])){
                $meta['descriptions'] = strip_tags(($category->getH1()?$category->getH1():$category->getName()).' ⭐'.mb_strtolower($t).' ⭐ '.Translator::get('description_exit_filter'));
                }elseif($category->getDescription() != NULL){
                    $meta['descriptions'] = $category->getDescription();
                }else{
                    $meta['descriptions'] = ($category->getH1()?$category->getH1():$category->getName()).' '.Translator::get('description_exit_category');//$category->getDescription();
                }
                }
             
            }else{
             $meta['descriptions'] = strip_tags(trim($t).' '.Translator::get('в интернет магазине RED').' '.Translator::get('покупайте').' '.mb_strtolower($title_obr).mb_strtolower($t).' '.Translator::get('description_exit'));
        }
            
        return $meta;
    }
    
    
}

