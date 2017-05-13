<?php
 header('Content-Type: text/html; charset=utf-8');
 require_once('dbconnect.php');	
 require_once('menu.php');
 require_once('regform.php');
 require_once('footer.php');
?>
<html style="height: 100%;">
<head>
	<title>Регистрация</title>
<?		printmenu();	
		printregform();?> 
</div>
<?printfooter();?>
</body>
</html>