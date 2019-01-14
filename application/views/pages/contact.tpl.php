        <?php 
          $text = explode(',', $this->trans->get('obratnaya_svaz'));  
            ?>
<?php if(Config::findByCode('new_grafik')->getValue()){ ?>
<p style="text-align: center;color: red;font-size: 18px;"><?=Config::findByCode('new_grafik')->getValue()?></p>
<?php } ?>

<div class="row m-0">
    <div class="col-xl-6">
<?=$this->getCurMenu()->getPageBody()?>
        </div>
    <div class="col-xl-6">
        <div class="row m-0">
<div class="card text-center  m-auto  col-12">
  <div class="card-body ">
    <h5 class="card-title text-uppercase "><?=$text[0]?></h5>
    <p class="card-text text-muted"><?=$text[1]?>. </p>
 <div class="comment-type"><span class="red">*</span> - <?php echo $this->trans->get('Поля, обязательные для заполнения');?></div>
    <?php 
    if($this->message_contact){
                if($this->message_contact['status'] == 'ok'){
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Ok!</strong> '.$this->message_contact["message"].'.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
}else{
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Ошибка!</strong> '.$this->message_contact["message"].'.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
                }
            }
    ?>
    <form action="<?=$this->getCurMenu()->getPath()?>" method="post" class="needs-validation text-left" novalidate>

        <div class="form-group">
    <label for="exampleFormControlInput1"><?=$text[2]?><span class="red">*</span></label>
    <input type="text" class="form-control" name="name" id="exampleFormControlInput1" placeholder="name@example.com" value="<?=$this->user->getFullname()?>" required>
     <div class="invalid-feedback">
        <?=$text[3]?>.
      </div>
  </div>
  <div class="form-group">
    <label for="exampleFormControlInput1">Email<span class="red">*</span></label>
    <input type="email" name="email" class="form-control" id="exampleFormControlInput2" placeholder="email" value="<?=$this->user->getEmail()?>" required>
    <div class="invalid-feedback">
        <?=$text[4]?>.
      </div>
  </div>
        <div class="form-group">
    <label for="exampleFormControlInput1">ТЕЛЕФОН<span class="red">*</span></label>
    <input type="tel" name="phone" class="form-control" id="exampleFormControlInput3" placeholder="" value="<?=$this->user->getPhone1()?>" required>
    <div class="invalid-feedback">
        <?=$text[5]?>.
      </div>
  </div>
  <div class="form-group">
    <label for="exampleFormControlSelect1"><?=$text[6]?><span class="red">*</span></label>
    <select class="form-control" id="exampleFormControlSelect1" name="sender" required>
      <option value=""><?=$text[7]?></option>
      <option value="Товар">Товар</option>
      <option value="<?=$text[8]?>"><?=$text[8]?></option>
      <option value="<?=$text[9]?>"><?=$text[9]?></option>
      <option value="<?=$text[10]?>"><?=$text[10]?></option>
      <option value="<?=$text[11]?>"><?=$text[11]?></option>
    </select>
     <div class="invalid-feedback">
        <?=$text[12]?>.
      </div>
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1"><?=$text[13]?><span class="red">*</span></label>
    <textarea class="form-control" name="message" id="exampleFormControlTextarea1" rows="3" required></textarea>
    <div class="invalid-feedback">
        <?=$text[14]?>.
      </div>
  </div>
        <div class="form-group text-center">
        <button type="submit"   class="btn btn-primary"><?=$text[15]?></button>
        </div>
       
</form>
  </div>
</div>
</div>
    </div>
    </div>



<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>