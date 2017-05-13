<?
function truetable($con,$num){?>
	
		<?$qer4 = $con->query($num);?>
		<div class="panel panel-default" style="<?changebody();?>">
			<table class="table">
				<?$count2 = $qer4->field_count;
				while ($prov = $qer4->fetch_array(MYSQLI_ASSOC)){	
					if ($bi != true){
						for($b = 0; $b<$count2; $b++){
							echo '<td style="font-weight: bold;">'.mysqli_field_name($qer4,$b).'</td>'; //генерация правильной таблицы задания
						}
						$bi=true;
					}										
					echo "<tr>";
					foreach ($prov as $col_value){
						echo "<td>$col_value</td>";
					}
					echo "</tr>";
				}?>
			</table>
		</div>
<?}?>