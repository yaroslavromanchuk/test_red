
<div class="main_image">

<div class="amount_text"><?php echo $this->str_price_round ?></div>
<div class="amount_text2"><?php echo $this->str_price?></div>
<div class="name"><?php echo $this->order->getName(). ' ' .$this->order->getMiddleName() ?></div>
<div class="address"><?php echo $this->order->getAddress()?></div>
<div class="phone"><?php echo "тел. " . $this->order->getTelephone()?>
</div>
<div class="return">а/я №144, "Цыбуля И.В.", г. Киев, 04080</div>
<div class="galochka">X</div>
<div class="amount_num"><?php echo $this->num_price_round?></div>
<div class="amount_num2"><?php echo $this->num_price?></div>
<div class="name2"><?php echo $this->order->getName(). ' ' .$this->order->getMiddleName() ?></div>
<div class="address2"><?php echo $this->order->getAddress()?></div>
<div class="phone2"><?php echo "тел. " . $this->order->getTelephone()?>
</div>

<!--
<div class="doz"><?php echo $this->doz ?></div>
<div class="poslez"><?php echo $this->poslez ?></div>
<div class="amount_text3"><?php echo $this->str_price?></div>
<div class="return2">ФЛП "ПЛАХОТНЮК",<br>Р/Р 26002301356947</div>
<div class="rahunok">ПАТ "ОТП Банк" МФО 300528 ОКПО 2908614863<br>01999, Г.КИЕВ</div>
<div class="name3"><?php echo $this->order->getName(). ' ' .$this->order->getMiddleName() ?></div>
<div class="address3"><?php echo $this->order->getAddress()?></div>
-->
</div>

<?php if ($this->blank_count % 2) { ?><div style='page-break-after: always;'></div> <?php } ?>