<!-- AstraFit.Loader -->
<script>
(function(d, s, id, host, ver, shopID, locale){ 
    var js,fjs=d.getElementsByTagName(s)[0];if(d.getElementById(id))return;
    d.astrafitSettings={host:host,ver:ver,shopID:shopID,locale:locale};
    js=d.createElement(s);js.id=id;js.async=true;js.src=host+"/js/loader."+ver+".min.js";
    fjs.parentNode.insertBefore(js,fjs);
}(document, "script", "astrafit-loader", "https://widget.astrafit.com", "latest", 464, "ru"));
</script>
<!-- /AstraFit.Loader -->
<?php 
$s = [];
 foreach ($this->art->sizes as $k => $size) {
     $s[] = $size->size->size;
 }
?>
<div class="astrafit-wdgt" 
 data-id="<?=$this->art->id?>" 
 data-brand="<?=$this->art->brand?>" 
 data-brand-code="<?=$this->art->brand_id?>"  
 data-sizes-list="<?= implode($s, ',')?>" 
 data-canonical="https://www.red.ua<?=$this->art->getPath()?>"
 data-img="https://www.red.ua<?=$this->art->getImagePath('card_product')?>"
></div>