<?php defined('EXEC') or die; ?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h3 class="text-center">Заполните анкету и присоединитесь к нашей команде</h3>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-5 vacancy-info">
		<div class="panel panel-default vacancy">
			<div class="panel-body">
				<div class="tab-content vacancy-info-tab-content">
					<div role="tabpanel" class="tab-pane fade in active" id="about-vacancy">
						<div class="vacancy-logo vacancy-cashier-logo"></div>
						<hr style="margin-top: 0;" />
						<div style="margin-top: 30px;">
<p>RED – сеть магазинов по продаже модной мужской и женской одежды, обуви и аксессуаров известных мировых брендов приглашает Вас в наш дружный коллектив.</p>
						</div>
					</div>
				    <div role="tabpanel" class="tab-pane fade" id="we-propose">
					    <h4>Мы предлагаем</h4>
					    <hr>
					    <p>Мы приглашаем всех желающих на работу в сеть магазинов RED.</p>
						<p><i>Мы предлагаем</i></p>
						
						<ul>
							<li>Гибкий график работы;</li>
							<li>Возможность работать с модными брендами;</li>
							<li>Дружелюбный коллектив;</li>
							<li>Своевременную выплату заработной платы;</li>
							<li>Программу лояльности для сотрудников.</li>
						</ul>
				    </div>
				    <div role="tabpanel" class="tab-pane fade" id="we-want">
					    <h4>Мы ищем</h4>
					    <hr>
					    <p>Мы хотим найти трудолюбивых, веселых, талантливых, жизнерадостных сотрудников.</p>
						<p><i>Требования к кандидатам:</i></p>
					    <ul>
						    	<li>Внутреннее желание делать людей счастливыми;</li>
						    	<li>Активность;</li>
						    	<li>Порядочность;</li>
						    	<li>Исполнительность;</li>
						    	<li>Ответственность.</li>
					    </ul>
					    <p>Будем рады Вас видеть в нашем коллективе.</p>
				    </div>
				</div>
				<div class="btn-group btn-group-justified vacancy-info-navigation" role="tablist">
					<div class="btn-group">
						<a href="#about-vacancy" class="btn btn-sm active" aria-controls="about-vacancy" role="tab" data-toggle="tab" id="btn-about-vacancy">
							О нас
						</a>
					</div>
					<div class="btn-group">
						<a href="#we-propose" class="btn btn-sm" aria-controls="we-propose" role="tab" data-toggle="tab" id="btn-we-propose">
							Мы предлагаем
						</a>
					</div>
					<div class="btn-group">
						<a href="#we-want" class="btn btn-sm" aria-controls="we-want" role="tab" data-toggle="tab" id="btn-we-want">
							Мы ищем
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-7">
		<form name="questionnaire" id="questionnaire" class="form-horizontal disabled-while-empty" method="post" action="pages/send-cv.php">
			<div class="form-group form-group-sm">
				<label for="input-name" class="col-sm-4 control-label">Фамилия, имя</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="input-name" name="name" placeholder="Фамилия, имя" required>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="input-age" class="col-sm-4 control-label">Возраст</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" id="input-age" name="age" placeholder="Возраст" min="1" required>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="input-citizenship" class="col-sm-4 control-label">Гражданство</label>
				<div class="col-sm-8">
					<select class="form-control" name="citizenship" id="input-citizenship">
					  <option>Украина</option>
					  <option>Россия</option>
					  <option>Белорусь</option>
					  <option>Другое</option>
					</select>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="input-tel" class="col-sm-4 control-label">Телефон</label>
				<div class="col-sm-8">
					<input type="tel" class="form-control" id="input-tel" name="tel" placeholder="+xx (xxx) xxx-xx-xx" required>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="input-email" class="col-sm-4 control-label">e-mail</label>
				<div class="col-sm-8">
					<input type="email" class="form-control" id="input-email" name="email" placeholder="example@mail.com" required>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="input-prefered-shop" class="col-sm-4 control-label">Желаемое место работы</label>
				<div class="col-sm-8">
					<select class="form-control" name="prefered-shop" id="input-prefered-shop">
					  <option>Не важно</option>
					  <option>г. Киев, проспект Героев Сталинграда, 46</option>
					  <option>г. Киев, ул. Александра Мишуги, 6</option>
					  <option>г. Киев, Проспект Правды, 66</option>
					  <option>г. Киев, ул. Елены Телиги, 13/14</option>
					  <option>г. Киев, ул. Фрунзе (Кирилловская), 127</option>
					  <option>г. Киев, проспект Победы, 98/2</option>
					  <option>г. Киев, ул. Драйзера, 8</option>
					  <option>г. Борисполь, ул. Киевский Шлях, 67</option>
					</select>
				</div>
			</div>
			<div class="form-group">
			    <div class="col-sm-offset-4 col-sm-8">
			    	<div class="checkbox">
			        	<label>
			        		<input type="checkbox" id="checkbox-agreement" required>Нажимая кнопку "Оправить анкету", я даю согласие на обработку своих персональных данных и на получение информационных сообщений от компании RED.
						</label>
			    	</div>
		    	</div>
			</div>
			<input type="hidden" name="position" value="я хочу работать в RED">
			<div class="form-group form-group-sm">
				<div class="col-sm-offset-4 col-sm-8">
					<button type="submit" class="btn btn-red-branded form-control" disabled="disabled">Отправить анкету</button>
	    		</div>
	  		</div>
		</form>
	</div>
</div>
