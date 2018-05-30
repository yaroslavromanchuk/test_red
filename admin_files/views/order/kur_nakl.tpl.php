<style type="text/css">
    body {
        font-size: 14px;
        width: 164px;
        margin: 0;
        padding: 0;
    }

    .tt_border_bottoms {
        border-bottom: solid 1px #000;
        padding: 5px;
    }

    .fnt_size_1 {
        font-size: 20px;
    }

    .fnt_size_2 {
        font-size: 16px;
    }

    .fnt_size_3 {
        font-size: 22px;
    }

    .border_alls {
        border-top: 2px solid #000;
        border-left: 2px solid #000;
        border-bottom: 2px solid #000;
        border-right: 2px solid #000;
    }

    .border_right {
        border-right: 2px solid #000;
    }

</style>
<table border="0" cellpadding="3" cellspacing="0" width="164" height="79" style="padding: 7px 0px 0px 20px;">
    <tr>
        <td class="border_alls">
            <table border="0" cellpadding="0" cellspacing="0" width="164" height="79">
                <tr>
                    <td style="text-align: center;"><span style="font-size: 40px;">
                <?php echo $this->order->getId()?>
                </span>
                    </td>
                </tr>
				<tr>
				<td style=" font-size: 14px;">
				<?php echo $this->order->getName()?>
				</td>
				</tr>
            </table>
        </td>
    </tr>
</table>
<div style='page-break-after: always;'></div>
