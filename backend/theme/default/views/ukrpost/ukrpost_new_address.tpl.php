<link rel="stylesheet" href="/js/meest/jquery-ui.css?v=20131212" type="text/css" media="screen">
<?php $up = $this->up;
?>
<div class="card pd-20 pd-sm-40">
      <form  action="" method="post" enctype="multipart/form-data">
          <div class="form-layout">
            <div class="row mg-b-25">
                <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Іd-Клієнта: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" required name="customer_id" value="<?=$this->get->id?>"  pattern="[0-9]" placeholder="Введіть id клієнта">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Индекс: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" required name="postcode" id="postcode" value="" maxlength="5"  pattern="[0-9]{5}" placeholder="Введите почтовый индекс">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Регион: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" id="region" name="region" value="" placeholder="введите область">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Райно: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="district" id="district" value="" placeholder="Введите район">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-3">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Город: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="city" id="city" value="" placeholder="Введите город">
                </div>
              </div><!-- col-8 -->
              <div class="col-lg-3">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Улица:  <span class="tx-danger">*</span></label>
                  <select class="form-control" data-placeholder="Выберите улицу" tabindex="-1" required aria-hidden="true" name="street" id="street">
                    <option label="Выберите улицу"></option>
                  </select>
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-3">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Дом: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="houseNumber" id="houseNumber" value="" placeholder="Введите № дома">
                </div>
              </div><!-- col-8 -->
              
            </div><!-- row -->

            <div class="form-layout-footer">
              <button class="btn btn-info mg-r-5" name="save_address" type="submit">Сохранить</button>
                          <button class="btn btn-secondary" type="reset">Очистить</button>
            </div><!-- form-layout-footer -->
          </div><!-- form-layout -->
          </form>
        </div>
<script>
    $( function() {
$('#postcode').autocomplete({
	source: function( request, response ) {
        $.ajax( {
          url: "/admin/ukrpost/new-address/?query=postcode",
          dataType: "json",
          data: {
            term: request.term
          },
          success: function( data ) {
            response( data.suggestions);
            console.log(data.suggestions);
          }
        } );
      },
			minLength: 4,
			maxHeight: 5,
                        width: 200, // Ширина списка
                        zIndex: 9999, // z-index списка
			deferRequestBy: 100,
			search: function( event, ui ) {
                           // console.log(event);
                           // console.log(ui);
			//$('#k_s_g').fadeIn(500);
			},
			select: function (event, ui) {
                            console.log(ui);
                            $('#region').val(ui.item.REGION);
                            $('#district').val(ui.item.DISTRICT);
                            $('#city').val(ui.item.CITY);
                            street(ui.item.CITYID);
			//$('.k').show();
			}
				});
 

});
function street(city){
console.log(city);
$.ajax({
         url: "https://ukrposhta.ua/address-classifier/get_street_by_name?city_id="+city+"&lang=UA&fuzzy=1",
         method : "GET",
         dataType: "json",
          success: function( data ) {
          //  console.log(data.Entries.Entry);
            var mas = data.Entries.Entry;
            var option = '<option label="Выберите улицу"></option>';
            for(var key in mas){
                option+='<option value="'+mas[key].STREET_NAME+'">'+mas[key].STREET_NAME+'</option>';
            }
            $('#street').html(option);
            
          }
     });
                                
                                
$('#street').select2({ minimumResultsForSearch: '' });
 return false;
    }
    
function house(st){
console.log(st);
$.ajax({
         url: "https://ukrposhta.ua/address-classifier/get_addr_house_by_street_id?street_id="+st,
         method : "GET",
         dataType: "json",
          success: function( data ) {
            console.log(data);
            var mass = data.Entries.Entry;
            var option = '<option label="Выберите дом"></option>';
            for(var k in mass){
                option+='<option value="'+mass[k].STREET_ID+'">'+mass[k].HOUSENUMBER_UA+'</option>';
            }
            $('#houseNumber').html(option);
            
          }
     });
                                
                                
$('#houseNumber').select2({});
 return false;
    }
</script>