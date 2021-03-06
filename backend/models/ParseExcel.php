<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ParseExcel
 *
 * @author PHP
 */
class ParseExcel extends wsActiveRecord{
    //put your code here
    /**
     * Чтение файла в массив
     * @param type $file - путь к файлу
     * @return type
     */
    public static function getExcelToArray($file, $ActiveSheet = 0)
            {
                require_once('PHPExel/PHPExcel/IOFactory.php');
                $objPHPExcel = PHPExcel_IOFactory::load($file);
                $objPHPExcel->setActiveSheetIndex($ActiveSheet);
		unlink($file);
                return  $objPHPExcel->getActiveSheet()->toArray(); 
            }
            
 /**
 * Чтение новых накладных с Excel файла для добавление товара
 * @param string $file - путь к excel файлу
 * @return array()
 */
public static function getExcelArticles($file)
    {
        require_once('PHPExel/PHPExcel/IOFactory.php');
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $objPHPExcel->setActiveSheetIndex(0);
        //$aSheet = $objPHPExcel->getActiveSheet();
		$aSheet = $objPHPExcel->getActiveSheet()->toArray();
		$mas = array();
		$errors = array();
		unset($aSheet[0]);
		unset($aSheet[1]);
		foreach($aSheet as $k => $m){
		if(!empty($m[0]) && !empty($m[16])){
                  
		//$mas[$m[16]]['nakladnaya'] = $nakladna[2];
		$mas[$m[16]]['model'] = trim($m[1]);
		
		$mas[$m[16]]['brand'] = str_replace('-',' ',trim($m[2]));
               // $br = $mas[$m[16]]['brand'];
               // $mas[$m[16]]['brand_id'] = wsActiveRecord::useStatic('Brand')->findFirst('name LIKE "'.$br.'"')->id;
		
		$mas[$m[16]]['stock'] = $mas[$m[16]]['stock'] + (int)$m[8];
                
                switch((int)$m[9]){
                    case 1604: $m[9] = 1216; break;
                    case 1602: $m[9] = 1154; break;
                     case 1603: $m[9] = 1268; break;
                      case 1601: $m[9] = 1398; break;
                      case 1595: $m[9] = 1398; break;
                    default: break;
                }
				
		$sex = wsActiveRecord::useStatic('Shoparticlessex')->findFirst(array('id_1c'=>(int)$m[9]));
                
		if (!$sex) {
                    $mas[$m[16]]['errors'][] = 'Ошибка Пола "' . $m[10] . '", строка '.$m[0];
                    $errors[] = 'Ошибка Пола "' . $m[10] . '", строка '.$m[0]; $sex = 0;
                    }else{ 
                        $sex = $sex->id;
                        
                    }
				
		$mas[$m[16]]['id_sex'] = $sex;
		$mas[$m[16]]['sex'] = $m[10];
                if(!isset($mas[$m[16]]['id_sex_excel'])){
                $mas[$m[16]]['id_sex_excel'] = (int)$m[9];
                }
		
		$season = wsActiveRecord::useStatic('Shoparticlessezon')->findFirst(array('id_1c'=>(int)$m[11]));
		if (!$season) {
                     $mas[$m[16]]['errors'][] = 'Ошибка сезона "' . $m[12] . '", строка '.$m[0];
                    $errors[] = 'Ошибка сезона "' . $m[12] . '", строка '.$m[0]; $season = 0; }else{ $season = $season->id;}
				
		$mas[$m[16]]['id_season'] = $season;
		$mas[$m[16]]['season'] = $m[12];
                if(!isset($mas[$m[16]]['id_season_excel'])){
                $mas[$m[16]]['id_season_excel'] = (int)$m[11];
                }
		
					
		
		$mas[$m[16]]['price'] = trim($m[13]);
		$mas[$m[16]]['cc'] = trim($m[14]);
		$mas[$m[16]]['skidka'] = trim($m[15]);
                if(!$mas[$m[16]]['sostav'] && trim($m[17])){
                    $excel_sostav =  explode("/", trim($m[17]));
                    if(count($excel_sostav)){
                        $pod_excel_sostav = [];
                        foreach ($excel_sostav as $key_mass => $pod_mass){
                            $temp_s = explode("=", $pod_mass);
                            if(count($temp_s)){
                                $str = $temp_s[0];
                                 list($str[0], $str[1]) = mb_strtoupper($str[0].$str[1], 'UTF8'); 
                                 if(isset($temp_s[1]) && !empty($temp_s[1])){
                            $pod_excel_sostav[$str] = $temp_s[1]?$temp_s[1]:'';
                                 }
                            }
                            unset($excel_sostav[$key_mass]);
                        }
                        $excel_sostav = $pod_excel_sostav;
                    }
                    
                    
                $mas[$m[16]]['sostav'] = $excel_sostav;
                
                }
		
		//$size = wsActiveRecord::useStatic('Size')->findFirst(array('id_1c'=>(int)$m[6]));
		//	if (!$size) { $errors[] = 'Ошибка размера "' . $m[7] . '", строка '.$m[0]; $size_id = 0; $size_name = $m[7]; }else{ $size_id = $size->id; $size_name = $size->size;}
			
                $size = wsActiveRecord::useStatic('Size')->findFirst(array('id_1c'=>(int)$m[6]));
                if ($size->id) {
                    $size_name = $size->size;
                    $size_id = $size->id;
                } else {
                    $size = wsActiveRecord::useStatic('Size')->findFirst(['size LIKE "'.trim($m[7]).'"']);
                    if (!$size) {
                            $mas[$m[16]]['errors'][] =  'Ошибка размера "' . $m[7] . '", строка '.$m[0];
                            $errors[] = 'Ошибка размера "' . $m[7] . '", строка '.$m[0];
                            $size_id = 0;
                            $size_name = $m[7];
                        }else{
                            $size_name = $size->size;
                            $size_id = $size->id;
                            if(!$size->id_1c){
                            $size->setId_1c(trim($m[6]));
                            $size->save();
                            }
                        }
                }
		//$size = wsActiveRecord::useStatic('Size')->findFirst(['size LIKE "'.trim($m[7]).'"']);
		/*	if (!$size) {
                            $mas[$m[16]]['errors'][] =  'Ошибка размера "' . $m[7] . '", строка '.$m[0];
                            $errors[] = 'Ошибка размера "' . $m[7] . '", строка '.$m[0];
                            $size_id = 0;
                            $size_name = $m[7];
                        }else{
                            $size_name = $size->size;
                            $size_id = $size->id;
                            if(!$size->id_1c){
                            $size->setId_1c(trim($m[6]));
                            $size->save();
                            }
                        }*/
			
		$color = wsActiveRecord::useStatic('Shoparticlescolor')->findFirst(array('id_1c' =>(int)$m[4]));
			if (!$color) {
                             $mas[$m[16]]['errors'][] = 'Ошибка с цветом "' . $m[5] . '", строка '.$m[0];
                            $errors[] = 'Ошибка с цветом "' . $m[5] . '", строка '.$m[0];
                            $color_id = 0;
                            $color_name = $m[5];
                            }else{
                                $color_id = $color->id;
                                $color_name = $color->name;
                            }
			
		$art = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(["code LIKE  '".trim($m[3])."' "]);
			if ($art) {
                             $mas[$m[16]]['errors'][] = 'Товар с штрихкодом '.trim($m[3]).' уже существует. id: '.$art->id_article.' Строка в накладной: '.$m[0];
                            $errors[] = 'Товар с штрихкодом '.trim($m[3]).' уже существует. id: '.$art->id.' Строка в накладной: '.$m[0];
                            }
                            
                      
		
		$mas[$m[16]]['color_id'] = $color_id;
                
		if (self::chekModel($mas[$m[16]], $m)) {
                        $mas[$m[16]]['errors'][] = 'Товар с штрихкодом '.trim($m[3]).' не в своей модели!. Строка в накладной: '.$m[0];
                        $errors[] = 'Товар с штрихкодом '.trim($m[3]).' не в своей модели!. Строка в накладной: '.$m[0];
                       }
                       
		$mas[$m[16]]['sizes'][] = [
		'code'=>trim($m[3]),
                    'id_color_excel' => (int)$m[4],
		'id_color'=>$color_id,
		'color'=>$color_name,
                    'id_size_excel' => (int)$m[6],
		'id_size'=>$size_id,
		'size'=>$size_name,
		'count'=>(int)$m[8]
		];
                
                    //   exit;
		}else{
                    $mas[$m[0]]['errors'][]  = 'Не указаны модели в файле!';
                    break; 
		}
		}
		
                unlink($file);
        return $mas;
    }
    
    static private function chekModel($model = [], $str = []){
      // l($model);
        // l($str);
       if($model['brand'] != str_replace('-',' ',trim($str[2]))){
         //  var_dump($model['brand']);
         //  var_dump(str_replace('-',' ',trim($str[2])));
         //  echo 'brand<br>';
           return true;
       }
        if($model['id_sex_excel'] != $str[9]){
           //  echo 'sex<br>';
           return true;
        } 
        if($model['id_season_excel'] != (int)$str[11]){
           //  echo 'season<br>';
           return true;
        } 
        if($model['price'] != $str[13]){
          //   echo 'price<br>';
           return true;
        }
       
       if(!empty($model['sizes'])){
           foreach ($model['sizes'] as $m){
               if($m['id_color_excel'] != (int)$str[4]){
                  //  echo 'color<br>';
                    return true;
               }
               if($m['id_size_excel'] == (int)$str[6]){
                  // echo 'size<br>';
                    return true;
               }
           }
           
       }
        return false;
    }
    public static function getExcelArticlesStaraVersiya($load_file)
        {//старые накладные
   
         require_once('PHPExel/PHPExcel/IOFactory.php');
        $objPHPExcel = PHPExcel_IOFactory::load($load_file);
        $objPHPExcel->setActiveSheetIndex(0);
		$aSheet = $objPHPExcel->getActiveSheet()->toArray();
		$mas = array();
		$errors = array();
		unset($aSheet[0]);
		unset($aSheet[1]);
		unset($aSheet[2]);
		unset($aSheet[3]);
		unset($aSheet[4]);
		unset($aSheet[5]);
		unset($aSheet[6]);
		unset($aSheet[7]);
		//return $aSheet;
		foreach($aSheet as $k => $m){
		if($m[1]){
		$mas[$m[41]]['model'] = trim($m[3]);
		$mas[$m[41]]['brand'] = trim($m[17]);
		$mas[$m[41]]['stock'] = $mas[$m[41]]['stock'] + (int)$m[29];
		$mas[$m[41]]['price'] = trim($m[32]);
		$mas[$m[41]]['cc'] = trim($m[35]);
		$mas[$m[41]]['skidka'] = trim($m[38]);
		
		$size = wsActiveRecord::useStatic('Size')->findFirst(array("size LIKE '".trim($m[26])."' "));
			if (!$size) { $errors[] = 'Ошибка размера "' . $m[26] . '", строка '.$m[1]; $size = 0; }else{ $size = $size->id;}
		$color = wsActiveRecord::useStatic('Shoparticlescolor')->findFirst(array('name' => mb_strtolower($m[23])));
			if (!$color) { $errors[] = 'Ошибка с цветом "' . $m[23] . '", строка '.$m[1]; $color = 0; }else{ $color = $color->id;}
		$art = wsActiveRecord::useStatic('Shoparticlessize')->count(array("code LIKE  '".trim($m[20])."' "));
			if ($art) { $errors[] = 'Товар с штрихкодом '.trim($m[20]).' уже существует. id: '.$art->id.' Строка в накладной: '.$m[1]; }
		
		
		$mas[$m[41]]['color_id'] = $color;
		$mas[$m[41]]['sizes'][] = array(
		'code'=>trim($m[20]),
		'id_color'=>$color,
		'color'=>trim($m[23]),
		'id_size'=>$size,
		'size'=>trim($m[26]),
		'count'=>(int)$m[29]
		);
		}else{
		break;
		}
		}
		$mas['error'] = $errors;
		 unlink($load_file);
		 return $mas;
    }
    
    /**
     * Старая версия
     * Чтение данных товра с Excel файла для добавление товара
     * model=>[1][3],price=>[1][32],min_price=>[1][35],max_price=>[1][38],nakladna=>[1][0]
     * @param string $file - путь к excel файлу
     * @return array()
     */
    
     public static function importadvertinfo($file)
    {
        require_once('PHPExel/PHPExcel/IOFactory.php');
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $objPHPExcel->setActiveSheetIndex(0);
        //$aSheet = $objPHPExcel->getActiveSheet();
		$aSheet = $objPHPExcel->getActiveSheet()->toArray();
$mas=array('model'=>$aSheet[1][3], 'price' =>$aSheet[1][32], 'min_price'=>$aSheet[1][35], 'max_skidka'=>$aSheet[1][38], 'nakladna'=>$aSheet[1][0]);

        return $mas;
    }
    
     /**
     * Старая версия
     * Чтение данных размера с Excel файла для добавление товара
     * 'sr'=>$m[20], 'color'=>$m[23], 'size'=>$m[26], 'count'=>$m[29]
     * @param string $file - путь к excel файлу
     * @return array()
     */
    public static function importadvert($file)
    {
        require_once('PHPExel/PHPExcel/IOFactory.php');
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $objPHPExcel->setActiveSheetIndex(0);
       // $aSheet = $objPHPExcel->getActiveSheet();
		$aSheet = $objPHPExcel->getActiveSheet()->toArray();
		
		$mas = array();
		unset($aSheet[0]);
		foreach($aSheet as $k => $m){
		if($m[1] != NULL) { $mas[] = array('sr'=>$m[20], 'color'=>$m[23], 'size'=>$m[26], 'count'=>$m[29]);}
		}
		 unlink($file);
		 return $mas;
    }
    
    /**
     * 
     * @param string $name - имя для файла excel без .xls
     * @param type $parametr ('header'=>[0=>['neme','koll','summ','price']], 'data'=>[0=>[], 1=>[]])
     * @param type $style - Стиль ечеек
     */
    public static function saveToExcel($name = '', $parametr = ['header' => [], 'data'=> [], 'title' => 'Превый лист'], $style = false){
        
      //  l($parametr);
       // exit();
        
            require_once('PHPExel/PHPExcel.php');
            require_once('PHPExel/excelarray.php');//массив буквенных обозначений в excel

                $filename = $name . '.xls'; 
                $pExcel = new PHPExcel();
                foreach ($parametr as $key => $val){
                $pExcel->createSheet();
                $pExcel->setActiveSheetIndex($key);
                $aSheet = $pExcel->getActiveSheet();
               if(count($val['title'])){ 
                   $aSheet->setTitle($val['title']);
               }else{
                    $aSheet->setTitle('Лист_'.$key);
               }
                
                if($style){
                   if($style['width']){
                       foreach ($style['width'] as $k => $w) {
                           $aSheet->getColumnDimension($k)->setWidth($w);
                           
                       }
                   }
                   if($style['merge']){
                        foreach ($style['merge'] as $k => $w) {
                            $aSheet->mergeCells($k);
                        }
                   }
                   if($style['font']){
                        foreach ($style['font'] as $k => $w) {
                            $aSheet->getStyle($k)->applyFromArray($w);
                        }
                   }
               }
               
                $j = 1;
                $i = 0;
                if(count($val['header'])){
              // $parametr['header'][0] = $parametr[0];
               foreach ($val['header'] as $v) {
                   foreach ($v as $st) {
                       $aSheet->setCellValue($h[$i].$j, $st);
                    $i++;
                   }
                   $i = 0;
                   $j++;
               }
    }
              
               
                //$j++;
                
              //  unset($parametr[0]);
                //$parametr['data'] = $parametr;
                if(count($val['data'])){
                    $i = 0;
                foreach ($val['data'] as $val) {
                    foreach ($val as $z) {
                         $aSheet->setCellValue($h[$i].$j, $z);
                        $i++;
                    }
                    $i=0;
                     $j++; 
                }
    }
    }
              
                require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);

                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header('Content-type: application/ms-excel');
                header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

              
                 $objWriter->save('php://output');
               // exit();
    }
}
