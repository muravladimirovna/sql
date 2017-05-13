<?php
	require_once('dbconnect.php');
	session_start();
	require_once('myfunction.php');
	require_once('menu.php');
	require_once('score.php');
	require_once('footer.php');
	error_reporting(0);
	$login = $_SESSION["login"];
	$id = $_SESSION["id"];
	?>
	<!DOCTYPE html>
<html style="height: 100%;">
<head>
	<title>SQL Ex</title>
		<?printmenu();?>	
		<div  style="width: 400px; margin: 10px auto;">
		<div class="alert">
			Удалить все данные без возможности восстановления?
		</div>
			<form class="navbar-form navbar-left" action="" method="post" style="width: 400px;">
						<input name="del" type="submit" value="Удалить" class="btn btn-warning" style="float: left; width:49%;">
						<input name="back" type="submit" value="Назад" class="btn btn-default" style="float: right; width:49%;">
						<?php
							if(isset($_POST["del"])){
								$delete = $dbcon_rw->query("DELETE FROM users WHERE id = '".$id."';");
								$delete = $dbcon_rw->query("ALTER TABLE answers DROP ".$login.";");
								session_destroy();
								header("Location:sqlex.php"); 
							}
							elseif(isset($_POST["back"])){
								header("Location:lk.php");
							}	?>
					</form>
		</div>
	</div>
	<?printfooter();?>
</body>
