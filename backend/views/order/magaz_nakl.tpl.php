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
<table border="0" cellpadding="3" cellspacing="0" width="164" height="79" style="margin-left:20px;margin-top:7px;">
    <tr>
        <td class="border_alls">
            <table border="0" cellpadding="0" cellspacing="0" width="164" height="79">
                <tr>
                    <td style="text-align: center;"><b style="font-size: 36px;">
                        <?php 
	switch ($this->order->getDeliveryTypeId()) {
    case 3:
        echo "П";
        break;
    case 5:
        echo "С";
        break;
	case 12:
        echo "М";
        break;
}
?>
                    </b> <br/>
                        <b style="font-size: 12px;">
                            <?php if ($this->order->getDateVProcese() != '0000-00-00') {
                            echo date('d.m', mktime(0, 0, 0, date("m", strtotime($this->order->getDateVProcese())), date("d", strtotime($this->order->getDateVProcese())) + 4, date("Y", strtotime($this->order->getDateVProcese()))));
                        } else {
							echo date('d.m', mktime(0, 0, 0, date("m"), date("d", time()) + 4, date("Y")));
                            //echo date('d.m', mktime(0, 0, 0, date("m", strtotime($this->order->getDateCreate())), date("d", strtotime($this->order->getDateCreate())) + 5, date("Y", strtotime($this->order->getDateCreate()))));
                        }
                            ?>
                        </b>
                    </td>
                    <td style="text-align: center;"><span style="font-size: 48px;">
                <?=$this->order->getId()?>
                </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<div style='page-break-after: always;'></div>
