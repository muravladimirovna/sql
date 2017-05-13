<?php
require_once('dbconnect.php');

function mysqli_field_name($res, $field_offset){
	$properties = mysqli_fetch_field_direct($res, $field_offset);
	return is_object($properties) ? $properties->name : null;
}

function my_array($con, $q, $key, $value){
	$array = array();
	$querys = $con->query($q);
	$r = $querys->num_rows;
	if ($r > 0){
		while ($l = $querys->fetch_array()){
			$ans = $l["$value"]; # указываются в опострофах
			$i = $l["$key"];
			$array[$i] = $ans;
		}
	}
	return $array;
}

function download_img($con, $uploaddir, $buttonname, $q){
	$uploadfile=$uploaddir.basename($_FILES[$buttonname]['name']); 
	$result = copy($_FILES[$buttonname]["tmp_name"], $uploadfile);
	if($result){
		$res = $con->query($q);
		if($res){
			echo "<font color=\"green\">Файл упешно загружен</font>";
			unlink($_SESSION["filename"]);
			$_SESSION["filename"]= $uploadfile;
		}elseif(isset($_POST["download"])){
				echo "<font color=\"yellow\">Путь не добавлен в базу данных, но файл загружен</font>";
		}
	}elseif(isset($_POST["$buttonname"])) {echo "<font color=\"red\">Файл не загружен,максимальный размер файла 2 мб</font>";}
	echo "<br>";
}

function usericon($con){
	$result = $con->query("SELECT avatar FROM users WHERE login = ".$_SESSION['login'].";");
	$mass = $result->fetch_array();?>
	<div style="width:22px; height: 22px; border-radius: 11px; border: 1px solid gray; padding: 2px 2px 2px 3.5px; float: left; margin-right: 5px; 
		background:url("<?= 'img/'.$mass["avatar"];?>"); background-size: cover;">
	</div>
<?}

function copytable($con,$new,$old){
	$NEW_TName = $new; //им¤ новой таблицы
	$OLD_TName = $old; //им¤ копируемой таблицы
	$q = "CREATE TABLE ".$NEW_TName." SELECT * FROM ".$OLD_TName."";
	$result = $con -> query($q);
	if(!$result){
		echo "Error: Ошибка создания временной таблицы";
	}
}

function droptable($con,$new){
$TName = $new;
$q = "DROP TABLE IF EXISTS ".$TName."";
$result = $con -> query($q);
}
	
?>