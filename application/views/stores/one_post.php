<?php if($this->post->id){ ?>
<div class="row m-auto">
    <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8 text-center m-auto">
        
        <img class="w-100" src="<?=$this->post->src?>"><br>
        <p class="p-3 m-2"><?=$this->post->text?></p>
        
</div>
</div>
<?php  }else{
           echo  header("HTTP/1.0 404 Not Found");
         ?>  
<div class="row m-auto">
    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4 text-center m-auto">
        <img class="w-100" src="<?=$this->error['src']?>"><br>
        <p class="p-3 m-2"><a  class="btn btn-outline-dark" href='/stores/'>Активні акції</a></p>
</div>
</div>
       <?php  } ?> 

