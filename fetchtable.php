<?
function fetchtable($q,$count,$name){?>
	<table class="table">
		<?while($line = $q->fetch_array(MYSQLI_ASSOC)){ # преобразование запроса пользователя в массив
			if ($i != true){
				for($a = 0; $a<$count; $a++){
					echo '<td style="font-weight: bold;">'.$name[$a].'</td>';
				}
				$i=true;
			}								
			echo "<tr>";
			foreach ($line as $col_value){										 
				echo "<td>".substr($col_value,0,15)."</td>";
			}
			echo "</tr>";
		}?>
	</table> 
<?}?>