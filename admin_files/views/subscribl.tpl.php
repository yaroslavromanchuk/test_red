<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32" />
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody();?>
 <form action="" method="post" enctype="multipart/form-data" id="form1">
     <p><?php echo $this->trans->get('Загрузка подписчиков. Файл .csv розделение'); ?> ";"</p>
      <input type="file" name="exel"/>
                <input type="submit" name = 'csv' value="<?php echo $this->trans->get('Загрузить'); ?>" />
 </form>
    <?php if(isset($this->text)){?>
        <textarea rows="20" cols="60"><?=$this->text?></textarea>
        <?php } ?>

  

  