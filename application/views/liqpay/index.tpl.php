<form method="POST" action="<?=$this->data['action']?>" id="liqpay_checkout" accept-charset="utf-8">
    <input type="hidden" name="data"  value="<?=$this->data['data']?>" />
    <input type="hidden" name="signature" value="<?=$this->data['signature']?>" />
    <div class="buttons">
        <div class="right">
            <input type="submit" value="<?=$this->data['button_confirm']?>" id="button-confirm" class="btn btn-primary button" />
        </div>
    </div>
</form>