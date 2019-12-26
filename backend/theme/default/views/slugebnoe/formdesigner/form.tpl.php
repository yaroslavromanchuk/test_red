<?php 
$f = wsActiveRecord::useStatic('FormsItem')->findFirst(['forms_id'=>1]);
?>

<link rel="stylesheet" type="text/css" href="<?=$this->files?>lib/codemirror/lib/codemirror.css">
<link rel="stylesheet" href="<?=$this->files?>lib/codemirror/theme/neo.css">
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <?=Forms::getForms('form-designer')?>
            </div>
            <div class="col-sm-12 col-lg-8">
                <textarea onkeyup="$('#res').html(this.value)" id="edit" rows="20" cols="150"><?php foreach (explode(',', $f->item) as $i){ echo $i;}?></textarea>
            </div>
            <div class="col-sm-12 col-lg-4">
                <div id="res"></div>
                <div id="res2"></div>
            </div>
            <div class="col-sm-12 col-lg-2">
                <button onclick="toMass(); return false;">собрать</button>
            </div>
        </div>


    </div>
</div>
<script src="<?=$this->files?>lib/codemirror/lib/codemirror.js"></script>

<script src="<?=$this->files?>lib/codemirror/mode/xml/xml.js" ></script>
<script>
 $(function(){
     

                 'use strict';
    /*      CodeMirror.fromTextArea(
        document.getElementById('edit'), {
 lineNumbers: true,               // показывать номера строк
  matchBrackets: true,             // подсвечивать парные скобки
  mode: 'xml', // стиль подсветки
  indentUnit: 4                    // размер табуляции
}
        );
       */
          $('#res').html($('#edit').val());
      });
 function toMass(){
     var mass = [];
$("#res").children().each(function(e){
    console.log(e);
    console.log(this);
  mass.push(this.outerHTML);
  
  
});
;
console.log(JSON.stringify(mass));
$.ajax({ url: "/admin/form-designer/", 
    type: "POST",
    data : 'method=send&param='+mass ,
           context: $('#res2'), 
           success: function(data, textStatus){
               console.log(data);
               console.log(textStatus);
               // this.html(data);
          },
            error: function(data, textStatus, errorThrown){
                console.log(data);
                 console.log(textStatus);
                  console.log(errorThrown);
          
      }
  });

          }
</script>



