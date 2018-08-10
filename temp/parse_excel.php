<?php 
require_once('../cron_init.php');


if(isset($_FILES['excel_file']) and isset($_POST['save'])){
if (is_uploaded_file($_FILES['excel_file']['tmp_name'])) {
//echo $_FILES['excel_file']['name'];
$res = parse_excel_file($_FILES['excel_file']['tmp_name']);
//save_excel_file($res);
$i = 0;
//echo '<pre>';
//unset($res[0]);

foreach($res as $k=>$b){
$b = trim($b[0]);
if($i== 0) { 
//$res[$k][9] = 'Категория'; $res[$k][10] = 'Дата добавления'; $res[$k][11] = 'Дата уценки';
$res[$k][2] = 'Сезон';
 }else{
 $q = "SELECT ws_articles.*
					FROM ws_articles 
					JOIN ws_articles_sizes ON  ws_articles.id = ws_articles_sizes.id_article
					WHERE ws_articles_sizes.code LIKE  '$b'";
					
  $article =  wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array(" code LIKE  '$b'"));
  $art =  wsActiveRecord::useStatic('Shoparticles')->findFirst(array("id"=>$article->id_article));
  
	//if($art){
	 $res[$k][2] = $art->name_sezon->name;
	//}
 
  
	//$res[$k][9] = $art->getCategory()->getRoutez();
	//$res[$k][10] = date("d-m-Y", strtotime($art->getCtime()));
	//$res[$k][11] =  date("d-m-Y", strtotime($art->getDataUcenki()));
 
}
$i++;
//if($i == 10) break;
}
save_excel_file($res);

}
}


function parse_excel_file($file){
	//$limit = 100;
	//$i=0;
	//$mass = array();
	
        require_once('PHPExcel/IOFactory.php');
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $objPHPExcel->setActiveSheetIndex(0);
      $aSheet = $objPHPExcel->getActiveSheet()->toArray();
	 return $aSheet;
    }
	
	function save_excel_file($mass, $title_list='test'){

	 require_once('PHPExcel.php');
	 require_once("PHPExcel/Writer/Excel5.php");
	 $filename = $title_list . '.xls';
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
//$m = array();

$h = array(0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F',6 => 'G',7 => 'H',8 => 'I',9 => 'J',10 => 'K',11 => 'L',12 => 'M',13 => 'N',14 => 'O', 15=>'P', 16=>'Q', 17=>'R', 18=>'S', 19=>'T', 20=>'U', 21=>'V', 22=>'W', 23=>'X', 24=>'Y', 25=>'Z', 26=>'AA', 27=>'AB', 28=>'AC', 29=>'AD', 30=>'AE', 31=>'AF');
	$i = 1;

	foreach ($mass as $k=>$l) {
	$j = 0;
	foreach($l as $z => $s){
	//echo $s;
	$sheet->setCellValue($h[$j].$i, $s);
	$j++;
	}
	$i++;
	}
	

header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
header ( "Cache-Control: no-cache, must-revalidate" );
 header ( "Pragma: no-cache" );
header ( "Content-type: application/vnd.ms-excel" );
header ( "Content-Disposition: attachment; filename=matrix.xls" );
// Выводим содержимое файла
$objWriter = new PHPExcel_Writer_Excel5($xls);
 $objWriter->save('php://output');
 exit;
	}

?>