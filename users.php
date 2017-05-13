<?php
session_start();
$login = $_SESSION['login'];
$password = $_SESSION['password']; 

require_once('dbconnect.php');
require_once('myfunction.php');
require_once('menu.php');
require_once('score.php');
require_once('inline.php');
require_once('footer.php');
require_once('fetchtable.php');
error_reporting(0); 

if(isset($_SESSION['dostup']) AND $_SESSION['dostup']==true AND $_SESSION["admindostup"]==true){	
	# СОЗДАЕМ АССОЦИАТИВНЫЙ МАССИВ ПОЛЬЗОВАТЕЛЕЙ 'ИД' -> 'ЛОГИН'
	$q = "SELECT id,login FROM users;";
	$usersarr = my_array($dbcon_rw,$q, 'id', 'login');
	if(isset($_GET["delete_user"])){
		$gu = $_SESSION['user'];
		$user_login = $usersarr[$gu];
		$result = $dbcon_rw->query("DELETE FROM users WHERE id = '".$gu."';");
		$result = $dbcon_rw->query("ALTER TABLE answers DROP ".$user_login.";");
	}
	?>
<!DOCTYPE html>
<html style="height: 100%;">
<head>
	<title>SQL Ex</title>
	<?printmenu();?>
		<ul class="nav nav-pills">
			<li class=""><a href="adminpanel.php"style="color:black;">Задания</a></li>
			<li class="" style="<?changehead();?>border-radius: 5px;"><a href="#" style="color: white;">Пользователи</a></li>
		</ul>
		<br clear="all">
		<div class="col-md-6" style="padding-right: 0;">
			<div class="panel panel-primary" style="<?changebody();?> font-size: 11px;" >
				<div class="panel-heading"  style="<?changehead();?>" >
					Все пользователи 
				</div>
				<?php
				if(isset($_GET['sort_login'])){
					$sort = 'ORDER BY login ASC';
				}elseif(isset($_GET['sort_name'])){
					$sort = 'ORDER BY name ASC';
				}elseif(isset($_GET['sort_family'])){
					$sort = 'ORDER BY family ASC';
				}elseif(isset($_GET['sort_score'])){
					$sort = 'ORDER BY score DESC';
				}else{
					$sort = 'ORDER BY id ASC';
				}
				$qer = $dbcon_rw->query("SELECT id,login,name,family,avatar,score FROM users ".$sort."");
				$count = 6; # определяем количество столбцов
				$buf = $qer->num_rows; # определяем количество строк?>			
				<?for($i = 0; $i<$count; $i++){
				$fname[$i] = '<form name="sort" method="get"><button class="btn btn-default" type="submit" name="sort_'.mysqli_field_name($qer,$i).'" style="">'.mysqli_field_name($qer,$i).' <b class="caret"></b></button></form>';
				}
				fetchtable($qer,$count,$fname);
				?>
			</div>
		</div>	<!--col-md-6-->
		
		<div class="col-md-6">
			<form name="task_form" method="get" action="" style="width: 100% ;float: right;">
				<div  class="input-group" >
					<select name="user" class="form-control" style="width: 100px;" onchange="this.form.submit()">
						<option <? if(!isset($_GET['send_user'])){?>selected<?}?> disabled>id</option>
						<?php
						for($t=1;$t<=$buf;$t++){ ?>
							<option 
							<?if($_GET["user"]==$t){
								echo 'selected';
							} 
							echo " value=\"$t\"";?>>
							<?echo $t;?>
							</option>
						<?}?>
					</select>
					<div class="selectbtn">Выберите пользователя для просмотра</div>
				</div>
				<br clear="all">
			</form>
			<form name="del_form" method="get" action="" style="width: 100% ;float: right;">
				<input class="btn btn-warning" type="submit" name="delete_user" value="Удалить" style="width:100%">
			</form>
			
			<br clear="all"><br>
			
		<?if(isset($_GET['user'])){?>
			<div class="panel panel-primary" style="<?changebody();?>">
				<div class="panel-heading"  style="<?changehead();?>">
					Параметры пользователя
				</div>
				<?if (!$_GET["user"]){
					}else{
						$gu = $_GET["user"];
						$userflag = true;
					}
					$_SESSION["user"] = $gu;
					$result = $dbcon_rw->query("SELECT id,login,password,name,family,dostup,score FROM users WHERE id = '".$gu."';");
					$buf = $result->num_rows; # определяем количество строк
					$w = false;	?>					
				<table class="table" style="font-size: 11px;">
					<?while ($uline = $result->fetch_array(MYSQLI_ASSOC)){ # преобразование запроса в массив
						if ($w == false){
							for($a = 0; $a<7; $a++){
								echo '<td style="font-weight: bold;">'.mysqli_field_name($result,$a).'</td>';
								$w = true;
							}
						}
						echo "<tr>"; # генерация таблицы  
						foreach ($uline as $colvalue){										 
							echo "<td>$colvalue</td>";
						}
						echo "</tr>";
					}?>
				</table> 
			</div>
			
			<div class="panel panel-primary" style="<?changebody();?>">
				<div class="panel-heading"  style="<?changehead();?>" >
					Прогресс пользователя
				</div>
				<?
				$user_login = $usersarr[$gu];
				$result = $dbcon_rw->query("SELECT num,".$user_login." FROM answers;");
				$z = false;	?>					
				<table class="table"  style="font-size: 11px;">
					<?while ($userline = $result->fetch_array(MYSQLI_ASSOC)){ # преобразование запроса в массив
						if ($z == false){
							for($a = 0; $a<2; $a++){
								echo '<td style="font-weight: bold;">'.mysqli_field_name($result,$a).'</td>';
								$z = true;
							}
						}
						echo "<tr>"; # генерация таблицы  
						foreach ($userline as $usvalue){										 
							echo "<td>$usvalue</td>";
						}
						echo "</tr>";
					}?>
				</table>
			</div>	<!--panel panel-primary-->		

			<form name="read_form" method="post">
			</form>
<?	
		}
	}else{
		echo '<font color="red">Ошибка доступа</font>';
	}?>
			<br>
		</div>
	</div>
	<?printfooter();?>
	</body>
</html>
