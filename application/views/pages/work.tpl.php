<div class="row mx-auto">
<div class="col-xl-12" style="    background: white; padding: 30px;">
<?=$this->getCurMenu()->getPageBody()?>
</div>
 </div>
 <script src="https://rabota.ua/export/context/company.js"></script>
<!--
<h1><strong>Анкета на работу:</strong></h1>

<form action="" method="post" enctype="multipart/form-data" name="work" id="work">
  <fieldset id="f1">
  <legend>
  <label for="label"><strong>Желаемая должность:</strong></label>
</legend>
  <p>
    <select name="works" id="works" class="input_style">
      <option value="0" selected="selected">Выберите должность...</option>
      <option value="Продавец-консультант">Продавец-консультант</option>
      <option value="Сотрудник внутреннего контроля">Сотрудник внутреннего контроля</option>
	  <option value="10">Предложить свою должность</option>
    </select>
	<input type="text" class="input_style hide_w" placeholder="Ваша должность" name="namework" id="namework" />
  </p>
 <button id="b1" onclick="uroven1(); return false;">Далее...</button>
  </fieldset>
  
  <fieldset id="f2" class="hide_w">
  <legend><strong>Личные данные:</strong></legend>
  <p>
    <label for="name">ФИО:</label>
  
    <input type="text" class="input_style" placeholder="Ваше имя" name="name" id="name" />
  </p>
  <p>
    <label for="date"> Дата рождения:</label>
 
    <input type="date" class="input_style" name="date" id="date" />
  </p>
  <p>
    <label for="phone">Телефон:</label>
  
    <input name="phone" class="input_style" type="text" id="phone" placeholder="(ххх) ххх-хх-хх"  maxlength="15" />
  </p>
  <p style="font-weight: bold;">Адрес  проживания(фактический)</p>
  <p>
    <label for="city">Город:</label>
  
    <input type="text" class="input_style" name="city" id="city" />
  </p>
  <p>
    <label for="streat">Улица:
    </label>
    <input type="text" class="input_style" name="streat" id="streat" />
  </p>
   <button id="b2" onclick="uroven2(); return false;">Далее...</button>
  </fieldset>
  <fieldset id="f3" class="hide_w">
  <legend><strong>Образование:</strong></legend>
  <p>
    <select name="education" size="1" id="education" class="input_style">
      <option value="0" selected="selected">Выберите образование...</option>
      <option value="Высшее">Высшее</option>
      <option value="Неоконченное высшее">Неоконченное высшее</option>
      <option value="Среднее-специальное(профессиональное)">Среднее-специальное(профессиональное)</option>
      <option value="Среднее общее">Среднее общее</option>
        </select>
  </p>
  <p><strong>Что заканчивали, когда, специальность:</strong></p>
  <p><strong>
    <textarea style="max-width: 300px;" name="educationall" cols="45" rows="3" wrap="virtual" id="educationall"></textarea>
  </strong></p>
     <button id="b3" onclick="uroven3(); return false;">Далее...</button>
  </fieldset>
  <fieldset id="f4" class="hide_w">
  <legend><strong>Трудовая деятельность</strong>:</legend>
  <p>
    <label for="xwork">Укажите последнее место работы:</label>
  </p>
  <p>
    <input name="xwork" class="input_style" type="text" id="xwork" size="50" />
  </p>
       <button id="b4" onclick="uroven4(); return false;">Далее...</button>
  </fieldset>
  <fieldset id="f5" class="hide_w">
  <legend><strong>Вам нужна работа:</strong></legend>
  <p>
    <label>
    <input name="typework" type="radio" id="typework" value="Полная занятость" />
Полная занятость</label>
    <label>
    <input type="radio" name="typework" value="Дополнительная" id="typework1" />
Дополнительная</label>
  </p>
       <button id="b5" onclick="uroven5(); return false;">Далее...</button>
  </fieldset>
   <fieldset id="f6" class="hide_w">
   <legend><strong>Когда Вам будет удобно приступить  к работе?</strong></legend>
    <p>
      <label>
      <input type="radio" name="datevuxod" value="Сегодня" id="r1" class="rrr"  onclick="$('#r5').val('');"/>
Сегодня</label>
      <br />
      <label>
      <input type="radio" name="datevuxod" value="Завтра" id="r2" class="rrr" onclick="$('#r5').val('');" />
Завтра</label>
      <br />
      <label>
      <input type="radio" name="datevuxod" value="Через 2-3 дня" id="r3" class="rrr" onclick="$('#r5').val('');"/>
Через 2-3 дня</label>
      <br />
      <label>
      <input type="radio" name="datevuxod" value="На следующей неделе" id="r4" class="rrr" onclick="$('#r5').val('');"/>
На следующей неделе</label>
      <br />
            <label>Ваш вариант:
			 <input type="radio" name="datevuxod" hidden value="" id="r6" class="rrrr" />
            <input type="text" class="input_style"  name="datevuxod1" value="" id="r5" onclick=" $('.rrr').prop('checked', false);$('.rrrr').prop('checked', true);" />
</label>
      <br />
    </p>
	     <button id="b6" onclick="uroven6(); return false;">Далее...</button>
  </fieldset>
  <fieldset id="f7" class="hide_w">
 <legend><strong> В каком из наших магазинов Вам удобнее всего будет работать?</strong></legend>
 <p>
   <label for="shop">Выберите магазин...</label>
 </p> 
 <p>
   <select name="shop" size="1" id="shop" class="input_style">
     <option value="0" selected="selected">Магазин</option>
	 <option value="oba@red.ua,info@red.ua">Не имеет значения</option>
     <option value="oba@red.ua,info@red.ua">Интернет магазин</option>
     <option value="teliga@red.ua,idr@red.ua">г.Киев, ул. Е.Телиги, 13/14 , возле ст.м. Дорогожичи</option>
     <option value="frunze@red.ua,vpe@red.ua">г.Киев, ул. Фрунзе (Кириловская), 127 </option>
     <option value="pobeda@red.ua,ssa@red.ua">г. Киев, пр-кт Победы 98/2 ,возле ст.м. Святошин и м.Нивки </option>
     <option value="draizera@red.ua,eus@red.ua">г. Киев, ул. Драйзера, 8</option>
     <option value="pravda@red.ua,vfi@red.ua">г. Киев, пр-кт Правды,66 </option>
     <option value="mishugi@red.ua,jpi@red.ua">г.Киев, ул. А.Мишуги,6 , возле ст.м. Позняки</option>
     <option value="obolon@red.ua,vma@red.ua">г. Киев, пр-кт Героев Сталинграда, 46, возле м.Оболонь</option>
     <option value="stroiteley@red.ua,yde@red.ua">г. Киев, ул. Строителей, 40, возле ст.м. Дарница</option>
     <option value="globus@red.ua,mno@red.ua">г. Киев, площа Независимости, 1, возле ст. м. Майдан Независимости</option>
     <option value="borispol@red.ua,aov@red.ua">г. Борисполь, ул. Киевский Шлях, 67</option>
   </select>
   <p  id="pEr" style="display:none; color: #a94442;background-color: #f2dede;border-color: #ebccd1; padding: 15px;
    border: 1px solid transparent;
    border-radius: 4px;">Обратите внимание. В случае, если в выбраном Вами магазине штат укомплектован, Ваша анкета передается в магазин, который находится ближе всего по локации.</p>
 </p>
 <button id="b7" onclick="uroven7(); return false;">Далее...</button>
  </fieldset>
  <fieldset id="f8" class="hide_w">
  <legend><strong>Прикрепить резюме:</strong></legend>
  <p>
    <label for="file">Выберите файл:</label>
    <input type="file" name="file" id="file" />
  </p>
  </br>
 <input name="save" type="submit" id="b8" style="display:none;" >Отправить</button>
  </fieldset>

</form>-->


<script >
    /*
function uroven1(){
$('.red').removeClass('red');
if($("select#works").val()== 0){
alert("Выберите должность");
$('#works').addClass('red');
$('#works').focus();
return false;
}else if($("select#works").val()== 10 && $('#namework').val() == ''){
$('#namework').show();
$('#namework').focus();
return false;
}else{
console.log($("select#works").val());
console.log($('#namework').val());
$('#b1').hide();
$('#f2').show();
}
}
function uroven2(){
$('.red').removeClass('red');
if($("#name").val() == ''){
alert("Введите Ваше имя.");
$('#name').addClass('red');
$('#name').focus();
return false;
}else if($("#date").val() == ''){
alert("Введите дату рождения.");
$('#date').addClass('red');
$('#date').focus();
return false;
}else if($("#phone").val() == ''){
alert("Введите контактный телефон.");
$('#phone').addClass('red');
$('#phone').focus();
return false;
}else if($("#city").val() == ''){
alert("Введите город проживания.");
$('#city').addClass('red');
$('#city').focus();
return false;
}else if($("#streat").val() == ''){
alert("Введите улицу на которой Вы проживаете.");
$('#streat').addClass('red');
$('#streat').focus();
return false;
}else{
console.log($("#name").val());
console.log($("#date").val());
console.log($("#phone").val());
console.log($("#city").val());
console.log($("#streat").val());
$('#b2').hide();
$('#f3').show();
}
}
function uroven3(){
$('.red').removeClass('red');
if($("select#education").val()== 0){
alert("Укажите Ваше образование.");
$('#education').addClass('red');
$('#education').focus();
return false;
}else if($("#educationall").val() == ''){
alert("Укажите что Вы заканчивали.");
$('#educationall').addClass('red');
$('#educationall').focus();
return false;
}else{
console.log($("select#education").val());
console.log($("#educationall").val());
$('#b3').hide();
$('#f4').show();
}
}

function uroven4(){
if($("#xwork").val() == ''){
alert("Укажите последнее место работы.");
$('#xwork').addClass('red');
$('#xwork').focus();
return false;
}else{
console.log($("#xwork").val());
$('#b4').hide();
$('#f5').show();
}
}
function uroven5(){
if($('input[name=typework]:checked').val() == 'Полная занятость' || $('input[name=typework]:checked').val() == 'Дополнительная'){
console.log($('input[name=typework]:checked').val());
$('#b5').hide();
$('#f6').show();
}else{
alert("Выберите тип зайнятости.");
return false;
}
}
function uroven6(){
if($('#r5').val() != ''){ $('input[name=datevuxod]:checked').val($('#r5').val())}
if($('input[name=datevuxod]:checked').val()){
console.log($('input[name=datevuxod]:checked').val());
$('#b6').hide();
$('#f7').show();
}else{
alert("Укажите когда Вам удобно приступить к работе.");
return false;
}
}
function uroven7(){
$('.red').removeClass('red');
if($("select#shop").val()== 0){
alert("Выберите магазин в котором Вы хотите работать");
$('#shop').addClass('red');
$('#shop').focus();
return false;
}else{
console.log($("select#shop").val());
$('#pEr').show();

$('#b7').hide();
$('#b8').show();
$('#f8').show();
}
}
*/
</script>
