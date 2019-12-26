<link rel="stylesheet" type="text/css" href="<?=$this->files?>lib/codemirror/lib/codemirror.css">
<link rel="stylesheet" href="<?=$this->files?>lib/codemirror/theme/neo.css">

<?php

//$this->log('gjfg');
echo '<pre>';
 //if($this->user->getCart()){
                //    $this->user->getCart()->clearCart();
               // }
echo '</pre>';




$dir = $_SERVER['DOCUMENT_ROOT'].'/backend/'.'models';
$file = scandir($dir);
foreach($file as $v){
    echo $v.'<br>';
    
}
?>
<iframe id=f></iframe>
<textarea oninput=f.srcdoc=value rows="10" size="5"></textarea>
<!--<textarea id="code"><?=htmlspecialchars(highlight_file($file[5]))?></textarea>-->

<script src="<?=$this->files?>lib/codemirror/lib/codemirror.js"></script>
<!--
<script src="<?=$this->files?>lib/codemirror/mode/javascript/javascript.js"></script>-->
<script src="<?=$this->files?>lib/codemirror/mode/xml/xml.js"></script>

<!--
<script src="<?=$this->files?>lib/codemirror/mode/php/php.js"></script>
<script src="<?=$this->files?>lib/codemirror/mode/htmlmixed/htmlmixed.js"></script>-->

<!--
<script src="<?=$this->files?>lib/codemirror/mode/clike/clike.js"></script>-->

<script>
 
var myCodeMirror = CodeMirror.fromTextArea(
        document.getElementById('code'), {
 lineNumbers: true,               // показывать номера строк
  matchBrackets: true,             // подсвечивать парные скобки
 // mode: 'application/x-httpd-php', // стиль подсветки
  indentUnit: 4                    // размер табуляции
}
        );

</script>