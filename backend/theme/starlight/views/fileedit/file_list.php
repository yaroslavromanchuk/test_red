<link rel="stylesheet" type="text/css" href="<?=$this->files?>scripts/codemirror/lib/codemirror.css">
<link rel="stylesheet" href="<?=$this->files?>scripts/codemirror/theme/abcdef.css">

<?php

//$this->log('gjfg');
echo '<pre>';
 //if($this->user->getCart()){
                //    $this->user->getCart()->clearCart();
               // }
echo '</pre>';




$dir = $_SERVER['DOCUMENT_ROOT'].$this->files.'models';
$file = scandir($dir);
foreach($file as $v){
    echo $v.'<br>';
    
}
?>
<textarea id="code"><?=htmlspecialchars(highlight_file($file[5]))?></textarea>

<script src="<?=$this->files?>scripts/codemirror/lib/codemirror.js"></script>

<script src="<?=$this->files?>scripts/codemirror/mode/javascript/javascript.js"></script>

<script src="<?=$this->files?>scripts/codemirror/mode/php/php.js"></script>

<script src="<?=$this->files?>scripts/codemirror/mode/clike/clike.js"></script>

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