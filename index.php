<?php
	session_start();		 
	require_once('dbconnect.php'); // Подключаемся к базе данных.
	require_once('myfunction.php');
	require_once('menu.php');
	require_once('footer.php');
	error_reporting(0);?>
<!DOCTYPE html>
<html style=" height: 100%;">  		
<head>
	<title>Главная</title>
	<?printmenu();?>
		<p align = "center"><font size ="5">Образовательный ресурс по дисциплине «Разработка и эксплуатация удаленных баз данных»</font></p>
		<font size ="4"><p>Если вы хотите узнать, как получить информацию из базы данных, но не знаете, с чего начать, то этот ресурс для вас.
		Если же вы знакомы с языком SQL или даже являетесь специалистом по базам данных, то вам будет интересно оценить свои знания. </p>
		<p>	Цель этого ресурса — быстрое изучение языка SQL. При этом «быстрое» вовсе не означает «поверхностное». Напротив, оставляя в стороне многие аспекты языка, 
		автор старается дать глубокое понимание логической структуры данных и, как следствие, правильного построения запросов с учетом этой структуры. </p>
		<p> С помощью данного ресурса вы сможете не только улучшить свои знания языка SQL, но и закрепить их практически.Мы надеемся, что сайт окажется полезным как новичкам,
		так и профессионалам в SQL.
		Запросы в образовательном ресурсе выполняются реальной СУБД. Пока это MySQL, но мы планируем возможность использования также и других серверов баз данных, начиная со свободно распространяемых и заканчивая коммерческими продуктами:
		</br>MS SQL Server
		</br>PostgreSQL
		</br>Oracle
		</br>...
		</br>чтобы можно было изучать особенности диалектов языка SQL у разных СУБД.</p></font>
	</div>
	<?printfooter();?>
</body>
</html>