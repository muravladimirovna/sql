<?
#require_once("myfunction.php");
#require_once("color.php");
function printmenu(){
	
	$red = '#a52337';
	$yellow = '#ff3';
	$green = '#093';
	$skyblue = '#2bcead';
	$primary = '#337ab7';
	$violet = '#609';

	if(isset($_POST['red'])){
		$_SESSION['color'] = $red;
	}elseif(isset($_POST['yellow'])){
		$_SESSION['color'] = $yellow;
	}elseif(isset($_POST['green'])){
		$_SESSION['color'] =$green;
	}elseif(isset($_POST['skyblue'])){
		$_SESSION['color'] = $skyblue;
	}elseif(isset($_POST['primary'])){
		$_SESSION['color'] = $primary;
	}elseif(isset($_POST['violet'])){
		$_SESSION['color'] = $violet;
	}?>	
	<link href='http://fonts.googleapis.com/css?family=Archivo+Narrow' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="style_color.css">
	<link rel="stylesheet" type="text/css" href="style_login.css">
	<link rel="stylesheet" type="text/css" href="style_new.css">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<meta charset="utf-8">
</head>
<body style="background: linear-gradient(to top, #fefcea, <?=$_SESSION['color'];?>);">
	<script src="js/jquery-3.1.1.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/bootstrap-tab.js"></script>
	
	<div class="container" style="padding: 65px 15px 20px 15px;">
	<nav class="navbar navbar-fixed-top navbar-default" role="navigation" style="border-radius: 0 0 5px 5px;">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<div class="navbar-brand" href="#">SQL</div>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<ul class="nav navbar-nav">
			<li><a href="index.php">На главную</a></li>
			<li><a href="sqlex.php">Практические задания</a></li>
			<li><a href="esqlex.php">Операторы модификации данных</a></li>
			<li><a href="#">О разработчике</a></li>
		<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		<input class="btn" id="colorbtn" name="color" style="background: linear-gradient(to top, #fefcea, <?=$_SESSION['color'];?>);margin-right: 5px;">
			Тема оформления
			<b class="caret"></b>
		</a>
        <ul class="dropdown-menu">
            <li>
				<form method="post">
					<input class="btn" id="colorbtn" type="submit" value="" name="red" style="background: linear-gradient(to top, #fefcea, <?=$red;?>);">
					<input class="btn" id="colorbtn" type="submit" value="" name="yellow" style="background: linear-gradient(to top, #fefcea, <?=$yellow;?>);">
					<input class="btn" id="colorbtn" type="submit" value="" name="green" style="background: linear-gradient(to top, #fefcea, <?=$green;?>);">
					<br clear="all">
					<input class="btn" id="colorbtn" type="submit" value="" name="skyblue" style="background: linear-gradient(to top, #fefcea, <?=$skyblue;?>);">
					<input class="btn" id="colorbtn" type="submit" value="" name="primary" style="background: linear-gradient(to top, #fefcea, <?=$primary;?>);">
					<input class="btn" id="colorbtn" type="submit" value="" name="violet" style="background: linear-gradient(to top, #fefcea, <?=$violet;?>);">
				</form>
			</li>
        </ul>
	</li>
		</ul>
		<ul class="nav navbar-nav navbar-right">
			<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<div id="icon">
						<span class="glyphicon glyphicon-user"></span>
					</div>
				<? if($_SESSION['dostup']==true){?>
					<?echo $_SESSION['name'];
				}else{?>
					<?
					echo "Гость";
					}?>
				<b class="caret"></b>
			</a>
			<ul class="dropdown-menu">
				<?if($_SESSION['dostup']==true){?>
					<li><a href="lk.php">Личный кабинет</a></li>
					<?if($_SESSION["admindostup"]==true){?>
						<li><a href="adminpanel.php">Панель управления</a></li>
					<?}?>
				<li class="divider"></li>
				<li><a href="viiti.php">Выйти</a></li>
				<?}else{?>
					<li><a href="login.php">Войти</a></li>
					<li class="divider"></li>
					<li><a href="registration.php">Зарегистрироваться</a></li>
				<?}?>
			</ul>
			</li>
		</ul>
		</div>
	</div>
	</nav>
	
	<!--div class="navbar-fixed-bottom" style="position: absolute;">
		<p>© Company 2017</p>
	</div-->
	<?
}
?>