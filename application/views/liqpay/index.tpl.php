<form method="POST" action="<?=$this->data['action']?>" id="liqpay_checkout" name="liqpay_checkout" accept-charset="utf-8">
    <input type="hidden" name="data"  value="<?=$this->data['data']?>" />
    <input type="hidden" name="signature" value="<?=$this->data['signature']?>" />
    <div class="buttons">
        <div class="text-center">
            <input type="submit" value="<?=$this->data['button_confirm']?>" id="button-confirm" class="btn btn-lg btn-success button" />
        </div>
    </div>
</form>
<script>
            //if(document.referrer == 'https://www.red.ua/shop-checkout-step2/') {
            document.forms.liqpay_checkout.submit();
            // }
</script>