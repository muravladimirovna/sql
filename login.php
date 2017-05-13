<!DOCTYPE html>
<html style="height: 100%;">
<head>
	<title>SQL Ex</title>
<?php
session_start();
$_SESSION["sql"] = $_POST["sql"];
error_reporting (0);
require_once('dbconnect.php');
require_once('menu.php');
require_once('loginform.php');
require_once('footer.php');
 $_SESSION['color'] = '#337ab7';
 printmenu();
 printloginform();	?>
	</div>
	<?printfooter();?>
</body>
</html>