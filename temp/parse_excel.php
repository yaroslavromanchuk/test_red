<?php 
function parse_excel_file($file){
	//$limit = 100;
	//$i=0;
	//$mass = array();
	
        require_once('PHPExcel/IOFactory.php');
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $objPHPExcel->setActiveSheetIndex(0);
      $aSheet = $objPHPExcel->getActiveSheet()->toArray();
	  
	 // foreach ($aSheet as $s) {
	 // $mass[$i] = array('sr'=>$s[0], 'ct'=>$s[1], 'ch'=>$s[2], 'ser'=>$s[3]);
	 // $i++;
	 // if($i >= $limit) break;
	 // }
	 // return $mass;
	 return $aSheet;
    }
	
	function save_excel_file($mass, $title_list='test'){
	 require_once('PHPExcel/IOFactory.php');
	 require_once("PHPExcel/Writer/Excel5.php");
	// $filename = $filename.'.xls';
	 // Создаем объект класса PHPExcel
$xls = new PHPExcel();
// Устанавливаем индекс активного листа
$xls->setActiveSheetIndex(0);
// Получаем активный лист
$sheet = $xls->getActiveSheet();
// Подписываем лист
$sheet->setTitle($title_list);


// Вставляем текст в ячейку A1
//$sheet->setCellValue("A1", 'Таблица умножения');
//$sheet->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
//$sheet->getStyle('A1')->getFill()->getStartColor()->setRGB('EEEEEE');

// Объединяем ячейки
//$sheet->mergeCells('A1:H1');

// Выравнивание текста
//$sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$h = array(0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F',6 => 'G',7 => 'H',8 => 'I',9 => 'J',10 => 'K',11 => 'L',12 => 'M',13 => 'N',14 => 'O', 15=>'P', 16=>'Q', 17=>'R', 18=>'S', 19=>'T', 20=>'U', 21=>'V', 22=>'W', 23=>'X', 24=>'Y', 25=>'Z', 26=>'AA', 27=>'AB', 28=>'AC', 29=>'AD', 30=>'AE', 31=>'AF');
	$i = 1;
	foreach ($mass as $k => $l) {
	$j = 0;
	foreach($l as $z => $s){
	$sheet->setCellValue($h[$j].$i, $s);
	$j++;
	}
	$i++;
	}

	//вывод файла
	// Выводим HTTP-заголовки
		// Выводим HTTP-заголовки
		header("Content-Type: text/html; charset=utf-8");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
		header ( "Content-Disposition: attachment; filename=matrix.xls" );
       // header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

// Выводим содержимое файла
 $objWriter = new PHPExcel_Writer_Excel5($xls);
 $objWriter->save('php://output');
	
	 return 'ok';
	}

?>