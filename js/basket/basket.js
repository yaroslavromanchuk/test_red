   function loadDelivery(e, amount){
            $('.dop_delivery_pay_'+shop).html('');
            $('.dop_delivery_info_'+shop).html('');
                var shop =e.id.slice(-1);
         //   console.log(shop);
          //  console.log(e.value);
            $.ajax({
    url:"/cart/ajax/",
    method:"POST",
    dataType: 'json', 
    data:{ delivery_type_id : e.value, shop: shop, amount: amount },
    success:function(data)
    {
       // console.log(data);
     $('.dop_delivery_pay_'+shop).html(data.pay);
     $('.dop_delivery_info_'+shop).html(data.info);
      $('.dop_delivery_pay_'+shop+' .select2').select2({
            minimumResultsForSearch: Infinity
       });
    },
    error: function(e){
        console.log(e);
    }
   }).done(function() {
       switch(e.value){
           case '3':   break;
           case '8':  loadCityNp(shop); break;
           case '4':   break;
           case '18':   loadCityJustin(shop); break;
           case '9':  loadStreetTrekko(shop); break;  
           default: break;
       }
   });
            return false;
        }
        function loadStreetTrekko(shop){
            $(".street-select-"+shop).select2({
                 minimumResultsForSearch: "",
            minimumInputLength: 2,
             multiple: false,
             allowClear: true,
            placeholder:{
        text:"Начните ввод улицы",
        id:""
                },
                
                   ajax:{
                    url:"/cart/ajax/",
                    dataType: "json",
                    delay: 250,
                    method:"POST",
                    data: function(params){
                        return {
                            term:params.term,
                            method: 'street_trekko_load'
                        };
                    },
                    processResults: function (respons){
                        return {  results: respons };
                    },
                    cache: true
                }
            })
            
        }
        function loadCityJustin(shop){ 
            $(".city-select2-"+shop).select2({
               placeholder:{
        text:"Виберите со списка",
        id:""
                },
            });
            $(".city-select2-"+shop).on("select2:select", function(e){
              $.ajax({
         type: 'POST',
         url: '/shop/justin/',
         dataType: 'json',
         data: {id:this.value, metod: 'search_depart'},
         success: function(data) {
             $('.warehouses-select2-'+shop).empty().select2({
                 placeholder:{
        text:"Виберите со списка",
        id:""
                },
                data : data
            });
         }   
    });
    });
           

            return false;
        }
                function loadCityNp(shop){ 
            $(".city-select2-"+shop).select2({
                 minimumResultsForSearch: "",
            minimumInputLength: 2,
             multiple: false,
             allowClear: true,
            placeholder:{
        text:"Начните ввод города",
        id:""
                },
                
                   ajax:{
                    url:"/cart/ajax/",
                    dataType: "json",
                    delay: 250,
                    method:"POST",
                    data: function(params){
                        return {
                            term:params.term,
                            method: 'city_np_load'
                        };
                    },
                    processResults: function (respons){
                        return {  results: respons };
                    },
                    cache: true
                }
            }).on('change', function (e) {
               var l = $('.warehouses-select2-'+shop);
             //  l.find('option').remove();
           //  console.log( this.value);
               $.ajax({
                    url:"/cart/ajax/",
                    dataType: "json",
                    delay: 250,
                    method:"POST",
                    data: {method: 'warehouses_np_load', term: this.value },
                    success: function(data){
                        // console.log(data);
                        if(data){
                            l.empty().select2({
                                minimumResultsForSearch: Infinity,
                                allowClear: true,
                                placeholder: 'Виберите отделение',
                                data: data
                });
                        }else{
                            console.log(data);
                        }
                    }
                });  
            });
        }
        
      $(function(){
           $('.select2').select2({
                 minimumResultsForSearch: Infinity
        });
        
           $("#telephone").mask("+38(099) 999-99-99", {
  completed: function(){ $('#maske_phone1').html('Вы ввели телефон: '+this.val());  }
});

         Parsley.addMessages('ru', {
    defaultMessage: "Некорректное значение.",
    type: {
      email: "Введите адрес электронной почты.",
      url: "Введите URL адрес.",
      number: "Введите число.",
      integer: "Введите целое число.",
      digits: "Введите только цифры.",
      alphanum: "Введите буквенно-цифровое значение."
    },
    notblank: "Это поле должно быть заполнено.",
    required: "Обязательное поле.",
    pattern: "Это значение некорректно.",
    min: "Это значение должно быть не менее чем %s.",
    max: "Это значение должно быть не более чем %s.",
    range: "Это значение должно быть от %s до %s.",
    minlength: "Это значение должно содержать не менее %s символов.",
    maxlength: "Это значение должно содержать не более %s символов.",
    length: "Это значение должно содержать от %s до %s символов.",
    mincheck: "Выберите не менее %s значений.",
    maxcheck: "Выберите не более %s значений.",
    check: "Выберите от %s до %s значений.",
    equalto: "Это значение должно совпадать."
  });
 
  Parsley.setLocale('ru');
      });

