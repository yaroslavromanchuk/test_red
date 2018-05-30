<?=$this->render('email/email.header.tpl.php');?>
<table border="0" cellpadding="0" cellspacing="0" width="700" style="font-family: Verdana">
                            <tr>
                                <td width='700' style="vertical-align:top">
                                    <table  style="border:1px solid #e4e4e4;">
                                        <tr>
                                            <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#383838;" align="left">
                                                <p><b>Благодарим Вас за Ваш заказ на сайте интернет-магазина RED.</b></p> 
                                                <p>Чтобы получить доступ к Вашим заказам и контактной информации Вы можете сейчас войти в личный кабинет на <a href="https://www.red.ua/account/login/">https://www.red.ua/account/login/</a> используя следующие учетные данные:</p>
                                                <p>Ваш логин: <?=$this->login?><br /> 
                                                    Ваш пароль: <?=$this->pass?><br />
                                                    Если у Вас возникли вопросы напишите нам на <a href="mailto:market@red.ua">market@red.ua</a></p>
                                            </td>
                                        </tr>
                                </table>
								</td>
                            </tr>
                        </table>
<?=$this->render('email/email.footer.tpl.php');?>