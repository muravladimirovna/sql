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
error_reporting(0);

if(isset($_SESSION['dostup']) AND $_SESSION['dostup']==true AND $_SESSION["admindostup"]==true){	
	# СОЗДАЕМ АССОЦИАТИВНЫЙ МАССИВ ЗАДАНИЙ 'НОМЕР' -> 'ТЕКСТ'
	$q = "SELECT id,task FROM tasks;";
	$tasks = my_array($dbcon_rw,$q, 'id', 'task');
	# СОЗДАЕМ АССОЦИАТИВНЫЙ МАССИВ ОТВЕТОВ 'НОМЕР' -> 'ТЕКСТ'
	$q = "SELECT id,qer FROM qer;";
	$answers = my_array($dbcon_rw,$q, 'id', 'qer');
	# ЗАБИРАЕМ КОЛИЧЕСТВО ЗАДАНИЙ И ВЫЧИСЛЯЕМ СЛЕДУЮЩИЙ ИД
	$maxid = count($tasks)+1;
	$_SESSION["maxid"] = $maxid;
	
	?>
	<?				if(isset($_POST["save"])){
						$ge = $_SESSION["ge"];
						$str = $_POST["task_text"];
						$answ = mb_strtolower(stripslashes($_POST["answ_text"]), 'UTF-8'); 
						$_SESSION["task_text"] = $str;
						$_SESSION["answ_text"] = $answ;
						$result1 = $dbcon_rw->query("update tasks set task = '".$str."' where id = '".$ge."';");
						$result2 = $dbcon_rw->query("update qer set qer = '".$answ."' where id = '".$ge."';");
						echo "<font color=\"green\">Текст задания успешно изменён</font><br>";
						}				?> 
	<?php			if(isset($_POST["delete"])){
						$ge = $_SESSION["ge"];
						$result = $dbcon_rw->query("DELETE FROM qer WHERE id = '".$ge."';");
						$result = $dbcon_rw->query("DELETE FROM tasks WHERE id = '".$ge."';");
						$result = $dbcon_rw->query("DELETE FROM answers WHERE num = '".$ge."';");
						$_SESSION["task_text"] = '';
						$_SESSION["answ_text"] = '';
						# ЕСЛИ УДАЛЕНО НЕ ПОСЛЕДНЕЕ ЗАДАНИЕ, "ПОДОДВИГАЕМ" ВСЕ СЛЕДУЮЩИЕ ЗАДАНИЯ ВВЕРХ
						if($ge != $maxid-1){ 
							for($i=$ge+1;$i<$maxid;$i++){ 	# ЧТОБЫ НУМЕРАЦИЯ ЗАДАНИЙ НЕ НАРУШАЛАСЬ
								$l=$i-1;					# ЗНАЮ, ЧТО КРИВО, НО ЧТО ПОДЕЛАТЬ
								$result = $dbcon_rw->query("update qer set id = '".$l."' where id = '".$i."';");
								$result = $dbcon_rw->query("update tasks set id = '".$l."' where id = '".$i."';");
								$result = $dbcon_rw->query("update answers set num = '".$l."' where num = '".$i."';");
							}
						}$maxid = $maxid -1;
					}	?>
<?					if(isset($_POST["save2"])){
					$ge = $_SESSION["ge"];
					$str = $_POST["task_text2"];
					if(isset($_POST["db"])){
						$db = $_POST["db"];
					}else{
						$db = 1;
					}
					$answ2 = mb_strtolower(stripslashes($_POST["answ_text2"]), 'UTF-8'); 
					$_SESSION["task_text2"] = $str;
					$_SESSION["answ_text2"] = $answ2;
					$_SESSION["db_text2"] = $_POST["db"];
					$result1 = $dbcon_rw->query("INSERT INTO tasks(id,task,db) VALUES ('".$maxid."','".$str."','".$db."');");
					$result2 = $dbcon_rw->query("INSERT INTO qer(id,qer) VALUES ('".$maxid."','".$answ2."');");
					$result1 = $dbcon_rw->query("INSERT INTO answers(num) VALUES ('".$maxid."');");
					$maxid = $maxid+1; 
					$_SESSION["maxid"] = $maxid;
					}
					
	# СОЗДАЕМ АССОЦИАТИВНЫЙ МАССИВ ЗАДАНИЙ 'НОМЕР' -> 'ТЕКСТ'
	$q = "SELECT id,task FROM tasks;";
	$tasks = my_array($dbcon_rw,$q, 'id', 'task');
	# СОЗДАЕМ АССОЦИАТИВНЫЙ МАССИВ ОТВЕТОВ 'НОМЕР' -> 'ТЕКСТ'
	$q = "SELECT id,qer FROM qer;";
	$answers = my_array($dbcon_rw,$q, 'id', 'qer');
	
	?>
<!DOCTYPE html>
<html>
<head>
	<title>SQL Ex</title>
		<?printmenu();?>
					<ul class="nav nav-pills">
						<li class="" style="<?changehead();?>border-radius: 5px;"><a href="#" style="color: white;">Задания</a></li>
						<li class=""><a href="users.php"style="color:black;">Пользователи</a></li>
					</ul>
					<div class="tab-content" style="padding-top: 15px;">
		<div class="panel-default" id="tasks">
			<h1>Изменение заданий</h1><hr>
			<ul class="nav nav-pills nav-stacked">
				<!-- создание combobox для выбора заданий -->
				<form class="input-group" name="sql_form" action="" method="get">
				<select name="option" class="form-control" style="width: 100px;" onchange="this.form.submit()">
					<option <? if(!isset($_GET['option'])){?>selected<?}?> disabled>№</option>
					<?php
					$result = $dbcon_rw->query("SELECT id FROM tasks");
					$task_col = $result->num_rows; 
					for($t=1;$t<=$task_col;$t++){ ?>
						<option 
				<?php 	if($_GET["option"]==$t){
							echo 'selected';
							} 
						echo " value=\"$t\"";?>>
				<?php 	echo $t;
						for($i=0;$i<count($arr);$i++){
							if($arr[$i]==$t){
								echo ' -> Ok';
								}
							}?>
						</option>
					<?	}?>
				</select>
				<div class="selectbtn">Выбeрите номер задания</div>
			</form>
			
				<br clear="all">	  
				<br> 
<?php			if (!$_GET["option"]){
					$_SESSION["task_text"] = '';	
					$_SESSION["answ_text"] = '';
				}else{
					$ge = $_GET["option"];
					$_SESSION["ge"] = $ge;
					# ВЫВОД ТЕКСТА ЗАДАНИЯ
					$_SESSION["task_text"] = $tasks[$ge];	
					$_SESSION["answ_text"] = $answers[$ge];
				}
				?> 
				<form name="read_form" method="post">

					<textarea <?if(!isset($_POST["modify"])){?> readonly <?} textareastyle()?> name="task_text" class="well well-lg"><?php echo $_SESSION["task_text"];?></textarea>
					<textarea <?if(!isset($_POST["modify"])){?> readonly <?} textareastyle()?> name="answ_text" class="well well-lg"><?php echo $_SESSION["answ_text"];?></textarea>
					<br> 
					<div class="btn-group">
						<input class="btn btn-default" type="submit" name="modify" value="Изменить"> 
						<input class="btn btn-default" type="submit" name="save" value="Сохранить">
						<input class="btn btn-warning" type="submit" name="delete" value="Удалить">
					</div>
				</form>		 				
			</ul>
			<h1>Добавление заданий</h1><hr>
			<ul class="nav nav-pills nav-stacked">
<?php			# ВЫВОД ТЕКСТА ЗАДАНИЯ
				$_SESSION["task_text2"] = $task_text2;	
				$_SESSION["answ_text2"] = $answ_text2;			?>
				<form name="read_form" method="post">
<?					if(isset($_POST["save2"])){
					
					echo "Номер задания: ".$maxid; ?>
<?					echo "<font color=\"green\"><br>Задание успешно добавлено</font><br>";
					}else{
						echo"Номер задания: ".$maxid;
					}?>		<br>
					<textarea name="task_text2" class="well well-lg" <?textareastyle()?>></textarea>
					<textarea name="answ_text2" class="well well-lg" <?textareastyle()?>></textarea>
					<select class="form-control" name="db" multiple>
						<option disabled>Выберите номер бд</option>
						<option value="1">1</option></option>
						<option value="2">2</option></option>
						<option value="2">3</option></option>
					</select>
					<br>
					<input type="submit" class="btn btn-default" name="save2" value="Добавить">
				</form>
				<br>
			</ul>
		</div>
	</div>
</div>
<?printfooter();
	}
	else{
		echo "Несанкционированный доступ";
		}			?>
</body>
</html>