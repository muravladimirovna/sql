<?php
	session_start();
	$login = $_SESSION['login'];
	$password = $_SESSION['password'];
	$id = $_SESSION['id'];			 
	require_once('dbconnect.php'); // Подключаемся к базе данных.
	require_once('myfunction.php');
	require_once('menu.php');
	require_once('inline.php');
	require_once('footer.php');
	error_reporting(0);
	if(isset($_SESSION['dostup']) AND $_SESSION['dostup']==true ){	?>
<!DOCTYPE html>
<html>
<head>
	<title>SQL Ex</title>
		<?printmenu();?>
						<h1>Личный кабинет</h1><hr>
						<div class="col-md-12" style="text-align: center">
						<?	$q = "UPDATE users SET avatar = '".$_FILES['uploadfile']['name']."' WHERE login='".$login."';";
							download_img($dbcon_rw,'img/','uploadfile', $q);
							$q = "SELECT login,avatar FROM users;";
							$avatars = my_array($dbcon_rw,$q, 'login', 'avatar');?> 
						</div>
		
		<div class="col-md-3">	
			<div class="row">
				<div class="col-sm-12 col-md-12">
					<a href="#" class="thumbnail">
						<img data-src="holder.js/300x400" src="<?='img/'.$avatars[$login];?>" alt="<?='img/'.$avatars[$login];?>">
					</a>
				</div>
			</div>
		</div> 
		<div class="col-md-4">
			<?$q="SELECT id, name FROM users;";
			$names = my_array($dbcon_rw,$q, 'id', 'name');
			$q="SELECT id, family FROM users;";
			$fams = my_array($dbcon_rw,$q, 'id', 'family');
			$q="SELECT id, score FROM users;";
			$scores = my_array($dbcon_rw,$q, 'id', 'score');
			$result = $dbcon_rw->query("select count(*) from users where score>".$scores[$id].";");
			$pos = $result->fetch_array();?>
			<form action="" method="POST" enctype=multipart/form-data>
				<input class="btn btn-default" type="file" name="uploadfile" value="Обзор" style="float:left;" id="btn1">
				<input class="btn btn-warning" type="submit" value="Загрузить" name="download" id="btn2">
			</form>
			<br><br><br>
			<div class="panel" style="padding: 20px;">
				<h4>Имя: <font color="coral"><?=$names[$id];?></font></h4><br>
				<h4>Фамилия: <font color="coral"><?=$fams[$id];?></font></h4><br>
				<h4>Результат: <font color="coral"><?=$scores[$id]?></font></h4><br>
				<h4>Рейтинг: <font color="coral"><?=$pos[0]+1?></font></h4><br>
			</div>
		</div>
		<div class="col-md-5">
		<h3>Изменение пароля</h3><hr>
		<div id="inpas">
			<form class="navbar-form navbar-left" name="lk" action=""  method="post">
				<div class="input-group" id="inputpas">
					<span class="input-group-addon">Старый пароль:</span> 
					<input class="form-control" name="lk_password" type="password" size="15" maxlength="15">
				</div>
				<div class="input-group" id="inputpas">
					<span class="input-group-addon">Новый пароль:</span> 
					<input class="form-control" name="new_password_1" type="password" size="15" maxlength="15">
				</div>
				<div class="input-group" id="inputpas">
					<span class="input-group-addon">Повторите пароль:</span> 
					<input class="form-control" name="new_password_2" type="password" size="15" maxlength="15">
				</div>
				<input class="btn btn-default" id="change" type="submit" name="submit" value="Изменить">
				<br>
			</form>
		</div>
		</div>
		<div class="alert" style="float:right;margin-right:15px;">
			<?php		if(isset($_POST["lk_password"])){$lk_password = $_POST["lk_password"];}
						if(isset($_POST["new_password_1"])){$new_password_1 = $_POST["new_password_1"];}
						if(isset($_POST["new_password_2"])){$new_password_2 = $_POST["new_password_2"];}
						if(isset($_POST["lk_password"])){
							if($password != $lk_password){
								echo "<font color='red'>Неправильно указан старый пароль</font>";
								}
							elseif($new_password_1 != $new_password_2){
								echo "<font color='red'>Введенные пароли не совпадают</font>";
								}
							else{
								$o = $dbcon_rw->query("update users set password = '".$new_password_1."' where login = '".$login."';");
								$password = $_SESSION['password'] = $new_password_1;
								echo "<font color='green'>Пароль успешно изменён!</font>";
								}
							}			?>
		</div>
		<br clear="all">
		<hr>
		<div  class="alert">
			<h4><a href="delete.php">Удалить</a> свой аккаунт</h4>
		</div>
	</div>
	<?printfooter();
	}
	else{
		echo "Несанкционированный доступ";
	}			?>
	</body>
</html>