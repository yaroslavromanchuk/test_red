<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32" />
<h1>Shop artikel</h1>

	<p>&nbsp;</p>
	<?php if ($this->errors) { ?>
    <div id="errormessage"><img src="<?php echo SITE_URL;?>/img/icons/error.png" alt="" width="32" height="32" class="page-img" />
     <h1>Er is iets misgegaan:</h1>
    <ul>
	<?php foreach ($this->errors as $error) echo "<li>{$error}</li>"; ?>
    </ul>
    </div>
	<?php } ?>
    <form action="" method="post" enctype="multipart/form-data">
      <table cellspacing="0" cellpadding="6" id="product-edit">
        <tr>
          <td class="column-data">Article *</td>
          <td><select name="article_id" class="formfields" id="article_id" onchange="loadPrice(this.value)">
            <option value="0" selected>Maak een selectie...</option>
			<?php foreach($this->categories as $category) {
				?><optgroup label="<?php echo $category->getName();?>">
				<?php foreach($category->getArticles() as $item) {
					?><option value="<?php echo $item->getId();?>" <?php if ($this->getOffer() && $item->getId() == $this->getOffer()->getArticleId()) echo "selected";?>><?php echo $item->getBrand().' '.$item->getModel();?></option><?php
				}
				?></optgroup><?php
			}
			?>
          </select></td>
        </tr>
        <tr>
          <td class="column-data">Old price *</td>
          <td><label>
            <input name="price_old" type="text" class="formfields" id="price_old" value="<?php if ($this->offer && ($article = $this->offer->getArticle()) && $article->getId()) echo Shoparticles::showPrice($article->getPrice()); ?>" />
          </label></td>
        </tr>
        <tr>
          <td class="column-data">Offer price *</td>
          <td><input type="text" class="formfields" name="price" value="<?php echo $this->offer ? Shoparticles::showPrice($this->offer->price) : ""; ?>" /></td>
        </tr>
        <tr>
          <td class="column-data">Short text *</td>
          <td><label>
            <textarea name="short_text" id="short_text" class="formfields" cols="45" rows="10"><?php echo $this->offer ? $this->offer->short_text : ""; ?></textarea>
          </label></td>
        </tr>
        <tr>
          <td class="column-data">Image (80 x 80)</td>
          <td><input name="image_file" type="file" class="formfields" /><?php if ($this->offer && $this->offer->getImage()) { ?><a href="<?php echo SITE_URL;?>/files/i4/<?php echo $this->offer->getImage(); ?>" target="_blank">Image</a><input type="hidden" name="image" value="<?php echo $this->offer->getImage(); ?>" /><?php } ?></td>
        </tr>
      </table>
      <p>
        <input type="submit" name="button" id="button" value="Opslaan" />
      </p>
    </form>
	
	
	
	
<script type="text/javascript">
<!--
	function loadPrice(i) {
		var data_to_post = new Object();
		data_to_post.id = i;
		data_to_post.getprice = '1';
		$.post('<?php echo $this->path."shop-offer/"; ?>', data_to_post, function(data){ refreshPrice(data); }, 'json');
	}
	
	function refreshPrice(data) {
		if ('done' == data.result) {
			$('#price_old').val(data.price);
			$('#short_text').val(data.short_text);
		} else {
			$('#price_old').val('0,00');
			$('#short_text').val('');
		}
	}
-->
</script>