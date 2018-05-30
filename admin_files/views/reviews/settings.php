<?
include('../config.php');
?>
<form action="adminaction.php" method="POST">
<table cellspacing="0" cellpadding="0">
<tr>
	<td>Режим ссылок на личные сайты, блоги пользователей:</td>
	<td>
		<select name="url_type">
			<option value="1">Dofollow</option>
			<option value="2">Nofollow</option>
			<option value="3">JavaScript</option>
		</select>
	</td>
</tr>
<tr>
	<td>Максимальное количество комментариев на странице:</td>
	<td>
		<input type="text" name="comment_max" maxlength="3" size="3">
	</td>
</tr>
<tr>
	<td>Частота отправки статистики на ваш email:</td>
	<td>
		<select name="send">
			<option value="1">Каждый день</option>
			<option value="2">Один раз в день</option>
			<option value="3">Один раз в неделю</option>
			<option value="4">Один раз в месяц</option>
		</select>
	</td>
</tr>
<tr>
	<td>Тема оформления:</td>
	<td>
		<select name="theme">
			<option>Default</option>
		</select>
	</td>
</tr>
<tr>
	<td>Ваш email:</td>
	<td>
		<input type="text" name="mail" maxlength="60" size="40">
	</td>
</tr>
<tr>
	<td colspan="2" align="center">
		<br><h2><input style="font-size:18px; padding:5px;" type="submit" value="Сохранить"/></h2><br>
		<font color="grey">Не забудьте сохранить ваши настройки!</font>
	</td>
</tr>
</table>
</form>