<?function printloginform(){?>
	<div id="loginform">
		<form class="navbar-form navbar-left" action="action_log.php" method="post">
			<div class="input-group" id="inputlog">
				<span class="input-group-addon">Логин:</span>
				<input class="form-control" name="login" type="text">
			</div>
			<div class="input-group" id="inputlog">
				<span class="input-group-addon">Пароль:</span>
				<input class="form-control" name="password" type="password">
			</div>	
			<input name="l_send" type="submit" class="btn btn-default navbar-btn" id="inputlog" value="Войти"><br>
		<a href="registration.php">Зарегистрироваться</a>
		</form>	
	</div>
<?}?>