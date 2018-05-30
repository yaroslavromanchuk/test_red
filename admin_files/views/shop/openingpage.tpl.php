<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32" />
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
	
	<p>&nbsp;</p>
	<?php if ($this->errors) { ?>
    <div id="errormessage"><img src="<?php echo SITE_URL;?>/img/icons/error.png" alt="" width="32" height="32" class="page-img" />
    <h1>Ошибки:</h1>
    <ul>
	<?php foreach ($this->errors as $error) echo "<li>{$error}</li>"; ?>
    </ul>
    </div>
	<?php } ?>
	<?php if ($this->msg) { ?>
    <div id="errormessage">
    <ul>
	<?php foreach ($this->msg as $error) echo "<li>{$error}</li>"; ?>
    </ul>
    </div>
	<?php } ?>
	
	<form action="" method="post" enctype="multipart/form-data" id="form1">
      <table cellspacing="0" cellpadding="6" id="product-edit">
      
		<?php
        $i=1;
        foreach ($this->articles as $article) {  ?>
        <tr>
          <td class="column-data">Позиция <?php echo $i; $i++;?></td>
          <td>
              <?php
             $art_img = new Shoparticles($article->getArticleId());?>
             

                <img class="prev" rel="#miesart<?=$art_img->getId();?>" src="<?php echo $art_img->getImagePath('small_basket'); ?>"
                                           alt="<?php echo htmlspecialchars($art_img->getTitle()); ?>"/>
                    <div class="simple_overlay" id="miesart<?=$art_img->getId();?>" style="min-height: 300px; padding:10px 80px">
   <img src="<?php echo $art_img->getImagePath('detail'); ?>"
                                           alt="<?php echo htmlspecialchars($art_img->getTitle()); ?>"/>

</div>
                    <div class="previ" style="position: absolute; display: none; margin-left: 600px; margin-top: -150px;"><img src="<?php echo $art_img->getImagePath('detail'); ?>"
                                           alt="<?php echo htmlspecialchars($art_img->getTitle()); ?>"/></div>

              <select name="article<?php echo $article->getId();?>" class="formfields" id="article<?php echo $article->getId();?>">
            <option value="" selected>Выберите товар...</option>
			<?php foreach($this->categories as $category) {
				?><optgroup label="<?php echo $category->getRoute(0);?>">
				<?php foreach($category->getArticles(array('active' => 'y')) as $item) if (strcasecmp($item->getActive(),'y') == 0) {
					?><option value="<?php echo $item->getId();?>" <?php if ($item->getId() == $article->getArticleId()) echo "selected";?>><?php echo $item->getBrand().' '.$item->getModel();?></option><?php
				}
				?></optgroup><?php
			}
			?>
          </select></td>
        </tr>
		<?php } ?>
         <tr>
          <td class="column-data">Позиция <?php echo $i; $i++;?></td>
          <td><select name="article<?php echo 0;?>" class="formfields" id="article<?php  echo 0;?>">
            <option value="" selected>Выберите товар...</option>
            <?php foreach($this->categories as $category) {
                ?><optgroup label="<?php echo $category->getRoute(0);?>">
                <?php foreach($category->getArticles(array('active' => 'y')) as $item) if (strcasecmp($item->getActive(),'y') == 0) {
                    ?><option value="<?php echo $item->getId();?>"><?php echo $item->getBrand().' '.$item->getModel();?></option><?php
                }
                ?></optgroup><?php
            }
            ?>
          </select></td>
        </tr>
        
      </table>
      <p>
        <input type="submit" name="button" id="button" value="Сохранить" />
      </p>
    </form>
    <div id ='aih_box'>
       <?php foreach($this->categories as $category) {
                ?>
                <?php foreach($category->getArticles(array('active' => 'y')) as $item) if (strcasecmp($item->getActive(),'y') == 0) {
                    ?>
                <img src="<?php echo $item->getImagePath('detail');?>" id="aih_<?php echo $item->getId();?>" style="display: none; position: fixed; top: 100px;" />
            <?php
                }
                ?>
        <?php
            }
            ?>
        </div>

    <script type="text/javascript">
$(document).ready(function () {
     $('.prev').hover(function(){
        $(this).parent().find('div.previ').show();
    },function(){
        $(this).parent().find('div.previ').hide();
    });
    $('.formfields').mousemove(function(){
         $('#aih_box img').hide();
          $('#aih_box #aih_'+$(this).attr('value')).show();
     }).mouseout(function(){
                  $('#aih_box img').hide();
             });
});
</script>
