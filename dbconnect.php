<?php
	$host = "localhost";
	
	$dbase_r = "srv72360_prov"; # БАЗА ТОЛЬКО НА ЧТЕНИЕ
	$user_r = "srv72360_user"; # ТАБЛИЦЫ laptop, pc, printer, product
	$password_r = "sqluser";
	
	$dbase_rw = "srv72360_sqlex"; # БАЗА С ПОЛНЫМ ДОСТУПОМ
	$user_rw = "srv72360_root"; # ТАБЛИЦЫ answers, qer, tasks, users
	$password_rw = "123456";
	
	$dbase_edt = "srv72360_editdb"; # БАЗА С ПОЛНЫМ ДОСТУПОМ
	$user_edt = "srv72360_root"; #
	$password_edt = "123456";
	
	$dbcon_r = new mysqli($host, $user_r, $password_r, $dbase_r);
	$dbcon_rw = new mysqli($host, $user_rw, $password_rw, $dbase_rw);
	$dbcon_edt = new mysqli($host, $user_edt, $password_edt, $dbase_edt);
	
	$dbcon_r->set_charset('utf8');
	$dbcon_rw->set_charset('utf8');
	$dbcon_edt->set_charset('utf8');
	
	if (!$dbcon_r OR !$dbcon_rw OR !$dbcon_edt){
		echo "Ошибка: Невозможно установить соединение с MySQL.".'<br>';
		echo "<p>Произошла ошибка при подсоединении к MySQL!</p>";
		exit;
		}
	?>