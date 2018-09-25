<img src="<?php echo SITE_URL; ?><?php echo $this->getCurMenu()->getImage(); ?>" alt="" width="32" class="page-img"
     height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>

<div >
		<select name="id" id="select" onChange="loadArticles(this.value, 1); return false;">
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
		</select>
				</div>
				</br><a id="all_chek" style="margin-left: 50px;display: none;" onclick="chekAll(); return false;" href="#">Отметить/Снять все</a><input  type="button" id='add_article' value="Добавить скидку"/>
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
	
	 $('#add_article').click(function () {
            if ($('.order-item:checked').val()) {
				ord = '';
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
						//ord+= ',' + $(this).attr('id');
                        id += ',' + $(this).attr('id');
                    } else {
						//ord += $(this).attr('id');
                        id += $(this).attr('id');
                    }
                    i++;
                });
				//console.log(id);
				
				$.ajax({
			beforeSend: function( data ) {
				$('#add_article').attr('value', 'Создается...');
			},
			type: "POST",
			url: '/admin/skidki/',
			data: '&method=add_articles&id='+ id,
			success: function( data ) {
			
			//$('#mess_span').html('Реест  успешно создан! № Реестра: '+data);
				console.log(data); 
			},
			dataType: 'json',
			complete: function( data ) {
			//console.log(data); 
			//$('#massprintttn').hide();
			//$('#mess_span').html('TTН Создана!');
				$('#add_article').attr('value', 'Скидка добавлена!!!');
				
			},
			error: function( e ) {
			alert('Что-то пошло не так!');
			}
		});
            }
        });
	</script>


	