<?function printscoretable($con){	
	# ВЫБОРКА ИЗ БД И ЗАПИСЬ В АССОЦИАТИВНЫЙ МАССИВ 'ЛОГИН'->'КОЛ-ВО РЕШЕННЫХ ЗАДАНИЙ'
	$q = "SELECT login,score FROM users WHERE dostup<>'admin' ORDER BY score DESC;";
	$sarr = my_array($con,$q,'login','score');?>
	<div class="panel panel-primary" id="score"  style="<?changebody();?>" >
		<div class="panel-heading"  style="<?changehead();?>">
			Текущий рейтинг
		</div>
		<table class="table">
			<tr class="textblack">
				<th>№</th>
				<th>login</th>
				<th>sc</th>
			</tr>
			<?php $i = 1;
			foreach ($sarr as $key => $value){
				echo '<tr>';
				echo '<th width="20">'.$i.'</th>';
				echo '<th style="width:50px; overflow: hidden;">'.substr($key,0,7).'</th>';
				echo '<th width="20">'.$value.'</th>';
				echo '</tr>';
				$i++;
			} ?>
		</table>
	</div>
<?}?>