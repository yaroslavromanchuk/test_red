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
<div class="card mt-3">
    <div class="card-body">
      <fieldset>
          <form id="trans" name="trans" method="post" >
              <legend>Форма перевода</legend>
              
            <input type="text" hidden="true" value="trans" name="method" >
            <div class="form-group">
  <label class="col-md-2 control-label" for="from">С</label>
  <div class="col-md-2">
    <select id="from" name="from" class="form-control">
      <option value="uk">Укр.</option>
      <option value="ru">Рус.</option>
      <option value="en">Eng.</option>
    </select>
  </div>
</div>
 <div class="form-group">
  <label class="col-md-2 control-label" for="to">На</label>
  <div class="col-md-2">
    <select id="to" name="to" class="form-control">
        <option value="ru">Рус.</option>
      <option value="uk">Укр.</option>
      <option value="en">Eng.</option>
    </select>
  </div>
</div>
            <div class="form-group">
  <label class="col-md-4 control-label" for="text">Сообщение</label>  
  <div class="col-md-4">
  <input id="text" name="text" type="text" placeholder="введите сообщения для перевода" class="form-control input-md" required="">
  </div>
</div>
           <div class="form-group">
  <div class="col-md-4">
  <input name="save" type="submit" class="form-control input-md">
  </div>
</div>
        </form>
      </fieldset>
        <div id="res_trans"></div>
    </div>
</div>
<script src="<?=$this->files?>lib/codemirror/lib/codemirror.js"></script>

<script src="<?=$this->files?>lib/codemirror/mode/xml/xml.js" ></script>
<script>
    $("#trans").on("submit", function (e) {
  e.preventDefault();

  // собираем строку запроса
 // var query = $(this).serialize(); // user=&s1=12&s2=foo

  // удаляем пустые параметры
 // query = query.replace(/([\w\-_]*=(&|$))/g, ''); // s1=12&s2=foo

  // шлем на сервер
  $.ajax({
    type: 'POST',
    url: '/admin/form-designer/',
    data:  $(this).serialize() // site.ru?s1=12&s2=foo

  }).done(function (r) {
    //  alert(r);
      $('#res_trans').html(r);
    console.log(r); // ответ от сервера
  }); 
  return false;
});

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



