<?
function copytable($con,$new,$old){
$NEW_TName = $new; //им¤ новой таблицы
$OLD_TName = $old; //им¤ копируемой таблицы
$q = "CREATE TABLE ".$NEW_TName." SELECT * FROM ".$OLD_TName."";
$result = $con -> query($q);
}

function droptable($con,$new){
$TName = $new;
$q = "DROP TABLE IF EXISTS ".$TName."";
$result = $con -> query($q);
}

function prov($con,$post_sql,$dbcon_rw,$login,$ge,$answers){	#забор содержимого формы дл¤ ввода запроса и преобразование в пон¤тный дл¤ SQL интерпретатора формат
	$str = mb_strtolower(stripslashes($post_sql), 'UTF-8'); #добавил перевод в регистр
	$qer = $con->query($str); # построение запроса пользовател¤ дл¤ вывода на экран
	$qer2 = $con->query($answers["$ge"]); # построение оригинального запроса на основе выбранного option
	$qer3= $con->query($str); # построение запроса пользовател¤ дл¤	проверки на верность			
	if (!$qer){
		echo 'Некорректный запрос: '.$con->error;
		exit;
	}else{				
		$count = $qer->field_count;	# определ¤ем количество столбцов запроса пользовател¤	
		$pline = $qer2->fetch_array(); 	
		$sline = $qer3->fetch_array(); 
		$buf1 = $qer2->num_rows; # определ¤ем количество строк в выборке
		$buf2 = $qer3->num_rows; # определ¤ем количество строк в выборке пользовател¤
		if($buf1 == $buf2){
			foreach($sline as $col_value){
				if ($sline == $pline){
					$_SESSION['e'] = true;
				}else{
					$_SESSION['e'] = false;
				}
			}
		}else{
			$_SESSION['e'] = false;
		} 
	}
}
?>