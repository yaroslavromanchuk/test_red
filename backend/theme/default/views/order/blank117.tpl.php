
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


<div class="doz"><?php echo $this->doz ?></div>
<div class="poslez"><?php echo $this->poslez ?></div>
<div class="amount_text3"><?php echo $this->str_price?></div>
<div class="return2">ФЛП "Цыбуля И.В.",<br>Р/Р 26005455021620</div>
<div class="rahunok">ПАТ "ОТП Банк" МФО 300528 РНОКППОП 2978405548<br>01926, Г.КИЕВ</div>
<div class="name3"><?php echo $this->order->getName(). ' ' .$this->order->getMiddleName() ?></div>
<div class="address3"><?php echo $this->order->getAddress()?></div>
<div class="phone3"><?php echo "тел. " . $this->order->getTelephone()?>
</div>

</div>

<?php if ($this->blank_count % 2) { ?>
<div class="fon_ukr"></div>
<div class="fon_ukr"></div>
<div style='page-break-after: always;'></div>
<?php } ?>