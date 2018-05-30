<img src="<?php echo SITE_URL; ?><?php echo $this->getCurMenu()->getImage(); ?>" alt="" width="32" class="page-img"
     height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>

<div >
		<select name="id" id="select">
			<option value="">Выберите категорию</option>
			<?php
			$mas = array();

			foreach ($this->categories as $cat) {
				$mas[$cat->getRoutez()]['id'] = $cat->getId();
			}

			ksort($mas);
			foreach ($mas as $kay => $value) {
			?>
			<option value="<?php echo $value['id']; ?>" <?php if ($this->cur_category and $value['id'] == @$this->cur_category->getId()) echo "selected";?>><?php echo $kay ;?></option>
			<?php } ?>
		</select></br>
			<label>Начало акции:<input type="date" name="start" id="start" class="datapiker"></label></br>
			<label>Окончание акции:<input type="date" name="finish" id="finish" class="datapiker"></label></br>
			<label>Процент скидки:<input type="text" name="procent" id="procent" class="datapiker"></label></br>
			<label>Активна:<input type="checkbox" name="publish" id="publish" class="datapiker" value="1" ></label></br>
</div>			
				
				<input  type="button" id='add_cat' value="Добавить скидку"/>
				<div id="mess_span"></div>
<script type="text/javascript">
    var clik_ok = 0;
    function chekAll() {
        if (!clik_ok) {
            $('.cheker').attr('checked', true);
            clik_ok = 1;
        } else {
            $('.cheker').attr('checked', false);
            clik_ok = 0;
        }

        return false;
    }
</script>
				<div id="list_ar" style="margin-left: 50px;width: 600px; ">
				
				</div>

<script type="text/javascript">
	function loadArticles(category_id, i) {
	$('#all_chek').hide();
	$('#list_ar').html('');
		var data_to_post = new Object();
		data_to_post.id = category_id;
		data_to_post.getarticles = '1';
		$.post('/admin/skidki/', data_to_post, function (data) {
			createSelectList(data, this, i); //console.log(data);
		}, 'json');
		//$('#article_id_'+i).html('');
		//$('#option_id').html('');
	}
	
	function createSelectList(data, a, item) {
		if ('done' == data.result) {
			out = '<table id="productss1" cellpadding="4" cellspacing="0" >';
			out += '<tr><th>Chek</th><th>ID</th><th>Товар</th><th>Фото</th></tr>';
			himg = '';
			
			
			for (var i = 0; i < data.data.length; i++) {
			if (data.data[i].img) {
					himg = '<img  style="width: 50px;" id ="aih_' + data.data[i].id + '" src="' + data.data[i].img + '"  />';
				}
				out += '<tr><td><input type="checkbox" class="order-item cheker" id="'+ data.data[i].id +'" name="'+ data.data[i].id +'"/></td><td>' + data.data[i].id + '</td><td>' + data.data[i].title + '</td><td>'+ himg +'</td></tr>';
			}
			/*if ('articles' == data.type) {
				out = '<option value="0" selected>Выберите товар...</option>' + out;
				$('.article[rel="'+item+'"]').html(out);
				$('.aih_box[rel="'+item+'"]').html(himg);
			} else {
				out = '<option value="0" selected>Selecteer een optie...</option>' + out;
				$('#option_id').html(out);
			}*/
			out +='</table>';
			$('#list_ar').html(out);
			$('#all_chek').show();
			
		}
	}
	
	 $('#add_cat').click(function () {
			var error = 0;
              var  id = $('#select').val(); if(id == '') error=1;
			  var start = $('#start').val(); if(start == '') error=1;
			  var finish = $('#finish').val(); if(finish == '') error=1;
			  var procent = $('#procent').val(); if(procent == '') error=1;
			   var publish = $('#publish:checked').val(); if(publish == '') error=1;

				if(error == 0){
				//alert(id);
				$.ajax({
			beforeSend: function( data ) {
				$('#add_cat').attr('value', 'Добавление...');
			},
			type: "POST",
			url: '/admin/skidki/',
			data: '&method=add_category&id='+ id+'&start='+start+'&finish='+finish+'&procent='+procent+'&publish='+publish,
			success: function( data ) {
			
			$('#mess_span').html('Скидка на категорию добавлена!');
				console.log(data); 
			},
			dataType: 'json',
			complete: function( data ) {
			//console.log(data); 
			//$('#massprintttn').hide();
			$('#mess_span').html('Скидка на категорию добавлена123!');
				$('#add_cat').attr('value', 'Скидка добавлена!!!');
				
			},
			error: function( e ) {
			alert('Что-то пошло не так!');
			}
		});
		}else{
		alert("Найдены ошибки!!!");
		}
        });
	</script>


	