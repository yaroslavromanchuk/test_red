<div id="forma_avt" class="forma_avt"  align="center">
		<form  name="authenticateByLoginPost"
                       action="/account/login/?redirect=<?=$_GET['redirect']?$_GET['redirect']:'/account/'?>"
                          method="post" >
				   <table cellpadding="0" cellspacing="0"  align="center" style="text-align:center;">
				   <tr>
				   								<td>
								 <a href="/account/resetpassword/">Забыли пароль?</a> 
								</td>
				   </tr>
				   <tr>
				   								<td>
								 <a href="/account/register/" >Зарегистрироваться</a>
								</td>
				   </tr>
                            <tr>
								<td>
                                    <input type="e-mail" 
									  class="form-control"
									
                                           onblur="if($(this).val()=='') $(this).val('e-mail');"
                                           onfocus="if($(this).val()=='e-mail') $(this).val('');" 
										   placeholder="e-mail"
										  
                                           name="login"><br><br>
										  
                                </td>
								</tr>
								<tr>
								<td>
									<input type="password"
											default=""
                                           class="form-control" 
										   placeholder="Пароль" 
										   name="password"><br><br>
										   
								</td>

								</tr>
								<tr>
								<td>
								<button type="submit" class="btn btn-danger" 
								title="Войти в личный аккаунт">Войти</button>
								</td> 
							</tr>
					 </table>
					 </form>
		</div>