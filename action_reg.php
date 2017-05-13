<?php			require_once("dbconnect.php");
				if(isset($_POST["send"])){
					if(isset($_POST["login"])){$r_login = $_POST["login"];} 
					if(isset($_POST["password"])){$r_password = $_POST["password"];} 
					if(isset($_POST["name"])){$r_name = $_POST["name"];} 	
					if(isset($_POST["family"])){$r_family = $_POST["family"];} 
					if(isset($_POST["send"])){$r_send = $_POST["send"];}
					
					if($r_login==""){
						echo "Введите логин";
						exit();
						}
					if($r_password==""){
						echo "Введите пароль";
						exit();
						}
					if($r_name==""){
						echo "Введите имя";
						exit();
						}
					if($r_family==""){
						echo "Введите фамилию";
						exit();
						}
					
					$result = $dbcon_rw->query("select id from users");
					$max = $result->num_rows;
					$id = $max+1;
					$_SESSION["id"] = $id;
					
					$mysqli1 = $dbcon_rw->query("INSERT INTO users (id,login, password, name, family, dostup) values ('".$id."','".$r_login."','".$r_password."','".$r_name."','".$r_family."','root');");
					$mysqli2 = $dbcon_rw->query("ALTER TABLE answers ADD ".$r_login." VARCHAR( 200 ) NOT NULL DEFAULT 'false'");
					$mysqli3 = $dbcon_rw->query("UPDATE answers SET  ".$r_login." =  'false'"); 
					$mysqli4 = $dbcon_rw->query("UPDATE users SET  score =  '0' WHERE  login = '".$r_login."'");
					
					
					if(!$mysqli1){
						echo "<font color='red'>Ошибка регистрации</font>";
					}
					elseif(!$mysqli2){
						echo "<font color='red'>Ошибка добавления колонки:<br>Этот логин уже используется</font>";
						$r_error = $dbcon_rw->query("DELETE FROM users WHERE id = '".$id."';");
					}
					elseif(!$mysqli3){
						echo "<font color='red'>Ошибка записи колонки</font>";
					}
					elseif(!$mysqli4){
						echo "<font color='red'>Ошибка записи ячейки</font>";
					}
					else{ 
						echo "<font color='green'>Регистрация прошла успешно!</font>";
						header("Location: login.php");						
						}	
					}
					?>