<?php 
 session_start();
 $_SESSION["sql"] = $_POST["sql"];	
 #ПОДКЛЮЧАЕМСЯ К БАЗЕ ДАННЫХ
 require_once('dbconnect.php');
 require_once('myfunction.php');
 require_once('menu.php');
 require_once('score.php');
 require_once('inline.php');
 require_once('footer.php');
 require_once('truetable.php');
 require_once('fetchtable.php');
 error_reporting(0);
 #Проверяем, пусты ли переменные логина и id пользователя
if (empty($_SESSION['login']) or empty($_SESSION['id'])){
	header('Location: login.php'); // Если пусты, то выводим форму входа
	}
else{	?>
<!DOCTYPE html>
<html>
<head>
	<title>SQL Ex</title>
	<script type="text/javascript">
	function ctrlEnter(event, formElem)
    {
    if((event.ctrlKey) && ((event.keyCode == 0xA)||(event.keyCode == 0xD)))
        {
        formElem.submit.click();
        }
    }
	</script>

<?php
	printmenu();
	$login = $_SESSION["login"];
	$uname = ucwords($_SESSION["name"]);
	$id = $_SESSION["id"];		
	$q = "SELECT id,qer FROM qer";
	$answers = my_array($dbcon_rw, $q, 'id', 'qer');
	
			# СОЗДАЕМ АССОЦИАТИВНЫЙ МАССИВ ЗАДАНИЙ 'НОМЕР' -> 'ТЕКСТ'
			$q = "SELECT id,task FROM tasks;";
			$tasks = my_array($dbcon_rw,$q, 'id', 'task');
			# СОЗДАЕМ АССОЦИАТИВНЫЙ МАССИВ ЗАДАНИЙ 'НОМЕР' -> 'БД'
			$q = "SELECT id,db FROM tasks;";
			$dbases = my_array($dbcon_rw,$q, 'id', 'db');
			# СОЗДАЕМ АССОЦИАТИВНЫЙ МАССИВ ЗАДАНИЙ 'НОМЕР' -> 'ОПИСАНИЕ БД'
			$q = "SELECT id,info FROM db;";
			$info = my_array($dbcon_rw,$q, 'id', 'info');
			?>	
			
	<div class="col-md-6" style="padding-left: 0;">
		<div class="panel panel-primary" style="<?changebody();?>">
			<div class="panel-heading" style="<?changehead();?>">
				Описание базы данных
			</div>
			<div class="panel-body">
				<p>
					<?	if(isset($_GET['option'])){
						$ge = $_GET['option'];
						$buf = $dbases[$ge];
						echo $info[$buf];
					}?>
				</p>
			</div>
		</div>
	</div>
	<div class="col-md-6" style="padding-right: 0;">
		<div  class="panel panel-default">			
			<?php
			# ВЫБОРКА ИЗ БД НОМЕРОВ РЕШЕННЫХ ЗАДАНИЙ
			$arr = array();
			$result = $dbcon_rw->query("SELECT num FROM answers WHERE ".$login."<>'false';");
			$r = $result->num_rows;
			if ($r > 0) {
				while ($l = $result->fetch_array()){
					$old = $l['num'];
					$arr[] = $old;
					}
				}
			# ПОДСЧЕТ И ЗАПИСЬ В БД КОЛИЧЕСТВА РЕШЕННЫХ ЗАДАНИЙ 
			$userscore = count($arr);
			$answ = $dbcon_rw->query("update users set score = ".$userscore." where login = '".$login."';");
			 ?>
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
			
			<?# ПРОЦЕДУРА ВЫБОРА ЗАДАНИЙ
			if (!$_GET["option"]){
				}
			else{
				for($t=1;$t<$task_col;$t++){
					$ge = $_GET["option"];
					$_SESSION['ge'] = $ge;
					if ($ge == $t){
						$optionnum = $t;
						$qe = 'qer'."$t";
						}
					}
				}?>
			
			<div class="alert" style="margin-bottom: 0px;">
				<?# ВЫВОД ТЕКСТА ЗАДАНИЯ						
				echo $tasks["$ge"];	?>
			</div>
			
			<!--создание формы ввода запроса -->
			<form class="textarea" name="sql_form" action="" method="post" onkeypress="ctrlEnter(event, this);">
				<textarea  name="sql" class="well well-lg"><?php echo $_SESSION["sql"];?></textarea>
				<input class="btn btn-default navbar-btn" id="btntext1" type="submit" name="submit" value="Выполнить (Ctrl+Enter)">
			</form>
			
			<!-- создание кнопки показа правильного ответа -->
			<form name="form_btn_true" action="" method="post">
				<input class="btn btn-warning navbar-btn" id="btntext2" type="submit" name="view" value="Показать правильный результат"> 
			</form> 
			
		</div>	<!--panel panel-default-->
	</div>	<!--col-md-6-->
	
	<br clear="all">
	<div class="panel panel-default">
	
		<div class="col-md-2">
			<?# ПОСТРОЕНИЕ ТАБЛИЦЫ РЕЗУЛЬТАТОВ
			printscoretable($dbcon_rw);?>
		</div>	
		
		
		<?if(isset($_POST["view"]) AND isset($_GET["option"])){ # ПОКАЗАТЬ ПРАВИЛЬНЫЙ ОТВЕТ	?>
			<div class="col-md-5">	
			<?	$result = $dbcon_r->query($answers["$ge"]);
				truetable($dbcon_r,$answers["$ge"]);?>
			</div>
		<?}?>
		
		<?#забор содержимого формы для ввода запроса и преобразование в понятный для SQL интерпретатора формат
	if(isset($_POST["submit"]) AND isset($_GET["option"])){
		$str = mb_strtolower(stripslashes($_POST["sql"]), 'UTF-8'); #добавил перевод в регистр
		$title = ($_POST["sql"]); //**		
		$qer = $dbcon_r->query($str); # построение запроса пользователя для вывода на экран
		$qer2 = $dbcon_r->query($answers["$ge"]); # построение оригинального запроса на основе выбранного option
		$qer3= $dbcon_r->query($str); # построение запроса пользователя для	проверки на верность			
		if (!$qer){
			echo 'Некорректный запрос: '.$dbcon_r->error;
			exit;
		}else{				
			$count = $qer->field_count;	# определяем количество столбцов запроса пользователя	
			$pline = $qer2->fetch_array(); 				//*
			$sline = $qer3->fetch_array(); 
			$buf1 = $qer2->num_rows; # определяем количество строк в выборке
			$buf2 = $qer3->num_rows; # определяем количество строк в выборке пользователя
			if($buf1 == $buf2){
				foreach($sline as $col_value){
					if ($sline == $pline){
						
						$e = true;
					}else{
						$e=false;
					}
				}
			}else{
				$e = false;
			} 
		}
						# ПОВТОРНАЯ ПРОВЕРКА
		if($e==true){
			
			$qer = $dbcon_rw->query($str); # построение запроса пользователя для вывода на экран
			$qer2 = $dbcon_rw->query($answers["$ge"]); # построение оригинального запроса на основе выбранного option
			$qer3= $dbcon_rw->query($str); # построение запроса пользователя для	проверки на верность	
		
			$count = $qer->field_count;	# определяем количество столбцов запроса пользователя	
			$pline = $qer2->fetch_array(); 				//*
			$sline = $qer3->fetch_array(); 
			$buf1 = $qer2->num_rows; # определяем количество строк в выборке
			$buf2 = $qer3->num_rows; # определяем количество строк в выборке пользователя
			if($buf1 == $buf2){
				foreach($sline as $col_value){
					if ($sline == $pline){
						$e2 = true;
					}else{
						$e2=false;
					}
				}
			}else{
				$e2 = false;
			} 
		}?>
		
		<div class="alert" style="text-align:center;">
			<?if ($e2 == true){	# ПРОВЕРКА НА ВЕРНОСТЬ					 		
				$answ = $dbcon_rw->query("update answers set ".$login." = '".$str."' where num = ".$ge.";");
				echo 'Запрос верный!';	
			}elseif($e==true){
				echo 'Запрос не точный!';
			}
			else{
				echo 'Запрос не верный!';
			}?>
		</div>
	
		<div class="col-md-5">
			<div class="panel panel-default" style="<?changebody();?>">
				<?
				$qer = $dbcon_r->query($str); 
				if ($qer){ # ЕСЛИ ЗАПРОС ПОЛЬЗОВАТЕЛЯ КОРРЕКТЕН
					for($i = 0; $i<$count; $i++){
						$fname[$i] = mysqli_field_name($qer,$i);
					}
					fetchtable($qer,$count,$fname);
				}?>
			</div>
		</div>
	
		<div class="col-md-5">
			<?truetable($dbcon_r,$answers["$ge"]); # ГЕНЕРАЦИЯ ПРАВИЛЬНОЙ ТАБЛИЦЫ ?> 
		</div>	
<?  } ?>

	</div>	<!--panel panel-default-->
	</div>	<!--container-->
	<?printfooter();?>
	
</body>
</html>
<?}?>