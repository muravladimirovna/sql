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
	# МАССИВ ОТВЕТОВ
	$q = "SELECT id,qer FROM eqer";
	$answers = my_array($dbcon_rw, $q, 'id', 'qer');	
	# СОЗДАЕМ АССОЦИАТИВНЫЙ МАССИВ 'ИД' -> 'ЗАДАНИЕ'
	$q = "SELECT id,task FROM etasks;";
	$tasks = my_array($dbcon_rw,$q, 'id', 'task');
	# СОЗДАЕМ АССОЦИАТИВНЫЙ МАССИВ ЗАДАНИЙ 'ИД' -> 'ИСХОДНАЯ ТАБЛИЦА'
	$q = "SELECT id,tname FROM etasks;";
	$dbases = my_array($dbcon_rw,$q, 'id', 'tname');
	# СОЗДАЕМ АССОЦИАТИВНЫЙ МАССИВ ЗАДАНИЙ 'НОМЕР' -> 'ОПИСАНИЕ БД'
	#$q = "SELECT id,info FROM db;";
	#$info = my_array($dbcon_rw,$q, 'id', 'info');?>	
			
	<div class="col-md-6" style="padding-left: 0;">
		<div class="panel panel-primary" style="<?changebody();?>">
			<div class="panel-heading" style="<?changehead();?>">
				Исходная таблица
			</div>
			<div class="panel-body">
				<p>
					<?if(isset($_GET['option'])){
						$ge = $_GET['option'];
						$q = "SELECT * FROM ".$dbases[$ge]."";
						$qer = $dbcon_rw->query($q); 
						$count = $qer->field_count;
						for($i = 0; $i<$count; $i++){
							$fname[$i] = mysqli_field_name($qer,$i);
						}
						fetchtable($qer,$count,$fname);
					}?>
				</p>
			</div>
		</div>
	</div>
	<div class="col-md-6" style="padding-right: 0;">
		<div  class="panel panel-default">			
			<!-- создание combobox для выбора заданий -->
			<form class="input-group" name="sql_form" action="" method="get">
				<select name="option" class="form-control" style="width: 100px;" onchange="this.form.submit()">
					<option <? if(!isset($_GET['option'])){?>selected<?}?> disabled>№</option>
					<?php
					$result = $dbcon_rw->query("SELECT id FROM etasks");
					$task_col = $result->num_rows; 
					for($t=1;$t<=$task_col;$t++){ ?>
						<option 
				<?php 	if($_GET["option"]==$t){
							echo 'selected';
							} 
						echo " value=\"$t\"";?>>
				<?php 	echo $t;	?>
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
			#printscoretable($dbcon_rw);?>
		</div>	
		
		<?# ПОКАЗАТЬ ПРАВИЛЬНЫЙ ОТВЕТ
		if(isset($_POST["view"]) AND isset($_GET["option"])){ 	?>
			<div class="col-md-5">	
				<div class="panel panel-default" style="<?changebody();?>">
				<?	
					$q = "SELECT * FROM ".$dbases[$ge]."$ge";
					$qer = $dbcon_rw->query($q); 
					$count = $qer->field_count;
					for($i = 0; $i<$count; $i++){
						$fname[$i] = mysqli_field_name($qer,$i);
					}
					fetchtable($qer,$count,$fname);?>
				</div>
			</div>
		<?}?>
		
		<?#забор содержимого формы для ввода запроса и преобразование в понятный для SQL интерпретатора формат
	if(isset($_POST["submit"]) AND isset($_GET["option"])){
		$str0 = mb_strtolower(stripslashes($_POST["sql"]), 'UTF-8'); #добавил перевод в регистр
		if($str0==""){exit();}
		#имя таблицы пользователя
		$new = substr($login,0,5)."$ge";
		#создание временной таблицы пользователя
		copytable($dbcon_rw,$new,$dbases[$ge]);
		#поиск названия таблицы в запросе пользователя
		$pos = strpos($str0, "$dbases[$ge]");
		if($pos === false)/*если не найдено*/{
			echo "Некорректное имя таблицы";
			exit();
		}else{/*если найдено*/
		//echo "Имя таблицы указано верно.";
		#замена в запросе пользователя названия таблицы уникальным именем
		$str  = str_replace($dbases[$ge], $new, $str0);
		#выполнение запроса пользователя
		$result = $dbcon_rw->query($str);
		if(!$result){
			echo "Ошибка обработки запроса";
			exit();}
		$qer = $dbcon_rw->query("SELECT * FROM ".$new.";"); #чтение таблицы пользователя для вывода на экран
		$qer2 = $dbcon_rw->query("SELECT * FROM ".$dbases[$ge].$ge.";"); #чтение проверочной таблицы на основе выбранного option
		if(!$qer2){
			echo "Ошибка чтения проверочной таблицы";
			exit();}
		$qer3= $dbcon_rw->query("SELECT * FROM ".$new.";"); #чтение таблицы пользователя для проверки на верность			
		
		if (!$qer){
			echo 'Некорректный запрос: '.$dbcon_rw->error;
			exit;
		}else{				
			$count = $qer->field_count;	# определяем количество столбцов запроса пользователя	
			$pline = $qer2->fetch_array(); 	#проверочный
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
				echo "Неверное количество строк";
				$e = false;
			} 
		}
		#ПОДРОБНАЯ ПРОВЕРКА
		$e1 = 0;
		if($e == true){
			$q1 = "SELECT * FROM ".$new.";";
			$qe1 = $dbcon_rw->query($q); 
			
			$q = "SELECT * FROM ".$dbases[$ge]."$ge".";";
			$qe = $dbcon_rw->query($q); 
			$count = $qe->field_count;
			for($i = 0; $i<$count; $i++){
				$fname[$i] = mysqli_field_name($qe,$i);
			}
			for($j = 1; $j<=$buf1; $j++){ #строки
				$res = $qe->fetch_row();
				$res1 = $qe1->fetch_row();
				for($i = 0; $i<$count; $i++){ #столбцы
					if($res[$i] == $res1[$i]){
						echo $j.":".($i+1)."+ ; ";
					}else{
						echo $j.":".($i+1)." ОШИБКА; ";
						$e1++;
					}
				}
			}
		}	
		}?>
					
		<div class="alert" style="text-align:center;">
			<?if ($e == true and $e1 === 0){	# ПРОВЕРКА НА ВЕРНОСТЬ							
				//$answ = $dbcon_rw->query("update answers set ".$login." = 'true' where num = ".$ge.";");
				echo 'Запрос верный!';	
			}elseif($e1 > 0){
				echo 'Запрос не точный!';
			}else{
				echo 'Запрос не верный!';
			}?>
		</div>
	
		<div class="col-md-5">
			<div class="panel panel-default" style="<?changebody();?>">
				<? 
				if ($qer){ # ЕСЛИ ЗАПРОС ПОЛЬЗОВАТЕЛЯ КОРРЕКТЕН
					for($i = 0; $i<$count; $i++){
						$fname[$i] = mysqli_field_name($qer,$i);
					}
					fetchtable($qer,$count,$fname);
				}?>
			</div>
		</div>
	
		<div class="col-md-5">
			<div class="panel panel-default" style="<?changebody();?>">
				<?# ГЕНЕРАЦИЯ ПРАВИЛЬНОЙ ТАБЛИЦЫ 
				$q = "SELECT * FROM ".$dbases[$ge]."$ge";
				$qer = $dbcon_rw->query($q); 
				$count = $qer->field_count;
				for($i = 0; $i<$count; $i++){
					$fname[$i] = mysqli_field_name($qer,$i);
				}
				fetchtable($qer,$count,$fname);?>
			</div>
		</div>	
		
		<?#УДАЛЕНИЕ ВРЕМЕННОЙ ТАБЛИЦЫ
		$result = $dbcon_rw -> query("DROP TABLE ".$new.";");
		if(!$result){
			echo "Ошибка удаления таблицы";
		}
	} ?>

	</div>	<!--panel panel-default-->
	</div>	<!--container-->
	<?printfooter();?>
	
</body>
</html>
<?}?>