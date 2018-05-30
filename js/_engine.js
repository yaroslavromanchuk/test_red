var run = 0;
var reset_params = 0;

function prepareSearchToPage(p){
    $('#current_page').val(p - 2);
    goSearch();
    return false;
}

function goSearch(){
    reset_params = 0;
    var total_pages =  parseInt($('#total_pages').val());
    var page = parseInt($('#current_page').val()) + 1;
    if (page == total_pages){
        $('#show_next').attr('disabled', true);
    }else{
        gatheringSelected('', page);
        $('#current_page').val(page);
    }
}
function categoryCheckboxClick(){
    reset_params = 1;
    gatheringSelected('categories', 0)
}
function gatheringSelected(what, page){
    var request = 'page='+page+'&selected_root_category='+$('#selected_root_category').val()+'&';
    //clear all     run = 1;    run = 0;
    if (run == 0){
        //need convert to JSON and work with OBJECT/ARRAY
        /*
         if (what == 'categories'){
         resetOneFilter('c_category', false);
         }
         if (what == 'colors'){
         resetOneFilter('c_color', false);
         }
         if (what == 'sizes'){
         resetOneFilter('s_size', false);
         }
         if (what == 'lables'){
         resetOneFilter('c_label', false);
         }
         if (what == 'brands'){
         resetOneFilter('c_brand', false);
         }*/
        request+='search_word="'+$('#search_word').val()+'"&categories=';
        $('.c_category:checked').each(function(){
            request += $(this).val()+',';
        });

        request+='&colors=';
        $('.c_color:checked').each(function(){
            request += $(this).val()+',';
        });

        request+='&sizes=';
        $('.s_size:checked').each(function(){
            request += $(this).val()+',';
        });

        request+='&labels=';
        $('.c_label:checked').each(function(){
            request += $(this).val()+',';
        });

        request+='&brands=';
        $('.c_brand:checked').each(function(){
            request += $(this).val()+',';
        });
        request += '&price_min='+jQuery(".real_price_min").html();
        request += '&price_max='+jQuery(".real_price_max").html();
        run = 1;


        $('#show_next').val('Выполняется поиск').attr('disabled', true);
        $('.res_loader').show();
        $('#total_founded').hide();


        $.ajax({
            type: 'POST',
            url: '/finder/ajaxsearch/&',
            data: request,
            success: function(data) {
                run = 0;
                var data = JSON.parse(data);

                var enb = data.enabled_params;
                //slow. need optimize
                pasteResult(what, 'c_category', enb.categories, 'categories', 0);
                pasteResult(what, 'c_color', enb.colors, 'colors', 0);
                pasteResult(what, 's_size', enb.sizes, 'sizes', 0);
                pasteResult(what, 'c_label', enb.labels, 'labels', 0);
                pasteResult(what, 'c_brand', enb.brands, 'brands', 1);
                //
                /*
                if ( reset_params == 1){
                    $('#result').html(data.result);
                }else{
                    $('#result').append(data.result);
                }*/
                $('#result').html(data.result);
                $('#total_founded').html(data.total_count);
                reset_params = 0;
                if (page + 1 == data.total_pages){
                    $('#show_next').val('Показать еще').attr('disabled', true);
                }else{
                    $('#show_next').val('Показать еще').attr('disabled', false);
                }

                $('#total_pages').val(data.total_pages);

                $('.res_loader').hide();
                $('#total_founded').show();
                initiateArticlesJS();
			    $('html, body').animate({ scrollTop: 240 }, 'slow');

            }
        });

        return true;
    }else{
        return false;
    }
}

function pasteResult(what, clss, arr, curr, use_name){
    $('.'+clss).each(function(){
        var o = $(this);
        var disabled = true;
        var v = o.val();
        for (var k in arr){
            if (use_name){
                if (v == arr[k].name){
                    disabled = false;
                }
            }else{
                if (v == arr[k].id){
                    disabled = false;
                }
            }

        }
        if (what != curr)
        {
            o.attr('disabled', disabled);
            if (disabled && o.attr('checked')){
                //o.attr('checked', false);
            }
            if (disabled){
                //o.parent().hide();
                o.parent().addClass('i_disabled');
            }else{
                //o.parent().show();
                o.parent().removeClass('i_disabled');
            }
        }

    });
}

function resetOneFilter(cls, reset_checked){
    $('.'+cls).each(function(){
        var o = $(this);
        if (reset_checked){
            o.attr('checked', false);
        }
        o.attr('disabled', false);
        o.parent().removeClass('i_disabled');
    });


}

function clearsearchfilters(){
    reset_params = 1;
    run = 1;
    resetOneFilter('c_category', true);
    resetOneFilter('c_color', true);
    resetOneFilter('s_size', true);
    resetOneFilter('c_label', true);
    resetOneFilter('c_brand', true);
    run = 0;
    gatheringSelected('', 1);
    $('#show_next').attr('disabled', false);
    $('#curent_page').val(0);
    return false;
}



jQuery(document).ready(function(){


    /* слайдер цен */

    jQuery("#slider").slider({
        min: 0,
        max: 100,
        values: [0,100],
        range: true,
        stop: function(event, ui) {
            var value01 = jQuery("#slider").slider("values",0);
            var value02 = jQuery("#slider").slider("values",1);
            var min = $('#minCost').val();
            var max = $('#maxCost').val();
            var onePercent = (max - min) / 100;
            var resultMin = min;
            var resultMax = max;
            if (value01 > 1){
                resultMin = Math.round(parseInt(min) + value01 * onePercent);
            }

            if (value02 < 99){
                resultMax = Math.round(parseInt(min) + value02 * onePercent);
            }
            jQuery(".real_price_min").html(resultMin);
            jQuery(".real_price_max").html(resultMax);
            run = 0;
            reset_params = 1;
            $('#current_page').val(-1);
            gatheringSelected('', 0);

        },
        slide: function(event, ui){
            var value01 = jQuery("#slider").slider("values",0);
            var value02 = jQuery("#slider").slider("values",1);
            var min = $('#minCost').val();
            var max = $('#maxCost').val();
            var onePercent = (max - min) / 100;
            var resultMin = min;
            var resultMax = max;
            if (value01 > 1){
                resultMin = Math.round(parseInt(min) + value01 * onePercent);
            }

            if (value02 < 99){
                resultMax = Math.round(parseInt(min) + value02 * onePercent);
            }
            jQuery(".real_price_min").html(resultMin);
            jQuery(".real_price_max").html(resultMax);
        }
    });
});



function initiateArticlesJS(){
    ob = $('.articles-row .article-item');
    if(ob.size() > 0){
        ob.bind("mouseenter",function(){
            var img_obj = $(this).children("a").children("img");
            if(thmbs[tmbGetPrId(img_obj)]){
                if(tmb_timer != null){
                    clearInterval(tmb_timer);
                }
                tmb_i = 0;
                tmb_obj = img_obj;
                tmbStep();
                tmb_timer = setInterval(tmbStep, 700);
            }
        }).bind("mouseleave",function(){
                var img_obj = $(this).children("a").children("img");
                var prod_id = tmbGetPrId(img_obj);
                if(thmbs[prod_id] && tmb_timer != null){
                    clearInterval(tmb_timer);
                    tmb_timer = null;
                    img_obj.attr("src",thmbs[prod_id][0]);
                }
            });
    }

    $("a[rel]").overlay({mask:{
        color: '#ebecff',
        loadSpeed: 200,
        opacity: 0.7
    }});

}

function getQuikArticle(id){
    $('#quik_frame').html('');
    $.post('/product/id/'+id+'/metod/frame/',function(data){
        $('#quik_frame').html(data);

        $('a.cloud-zoom').lightBox({fixedNavigation:true});
        $('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();


    });
}

function tmbGetPrId(img){
    var prod_id = img.parent("a").attr("href");
    //prod_id = prod_id.substr(prod_id.lastIndexOf("/")-1);
    //prod_id = prod_id.substr(12,prod_id.lastIndexOf("/"));
    prod_id = prod_id.split('/product/id/');
    prod_id = prod_id[1].split('/');
    //console.log(prod_id);
    return prod_id[0];
}
function tmbStep(){
    tmb_i++;
    var prod_id = tmbGetPrId(tmb_obj);

    if((tmb_i+1) > thmbs[prod_id].length){
        tmb_i = 0;
    }
    tmb_obj.attr("src", thmbs[prod_id][tmb_i]);
}

