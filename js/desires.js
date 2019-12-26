function setDesires(x){
  //  console.log('tut');
    if($('#d_chek-'+x).prop('checked')){
            $.ajax({
                url:'/desires/',
                type:'POST',
                dataType:'json',
                data: '&method=add&ids='+x,
                beforeSend:function(){
                    $('#zet-'+x).attr('title','Удалить c избранного');},
                success:function(res){
                   // console.log(res);
                },
        error: function(e){
            console.log(e);
            
        },
                complete:function(res){
                    $(".desires_ok_div").load("document.location #desires");
        }
    });
return true;
}else{
        $.ajax({
            url:'/desires/add/',
            type:'POST',
            dataType:'json',
            data:'&method=dell&ids='+x,
            beforeSend:function(){
                if(window.location.href.indexOf("desires")>-1){
                    $('.'+x).hide();}
                $('#zet-'+x).attr('title','Добавить в избранное');
            },
            success:function(res){
               // console.log(res);
            },
        error: function(e){
            console.log(e);
            
        },
            complete:function(res){
                $(".desires_ok_div").load("document.location #desires");
            }
        });
    
    return true;
}
}
