<?function printregform(){?>
	<div id="loginform">
		<form class="navbar-form navbar-left" name="reg" action="action_reg.php" method="post">
			<div class="input-group" id="inputlog">
				<span class="input-group-addon">Имя:</span>
				<input class="form-control"  name="name" type="text" size="15" maxlength="15">
			</div>
			<div class="input-group" id="inputlog">
				<span class="input-group-addon">Фамилия:</span>
				<input class="form-control"  name="family" type="text" size="15" maxlength="15">
			</div>
			<div class="input-group" id="inputlog">
				<span class="input-group-addon" name="login" type="text" maxlength="15">Логин:</span>
				<input class="form-control" name="login" type="text" maxlength="15">
			</div>
			<div class="input-group" id="inputlog">
				<span class="input-group-addon" name="password" type="password" maxlength="15">Пароль:</span>
				<input class="form-control" name="password" type="password" maxlength="15">
			</div>	
			<input name="send" type="submit" class="btn btn-default navbar-btn" id="inputlog" value="Зарегистрироваться"><br>
		</form>	
	</div>
<?}?>