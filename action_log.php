<?php 	
					require_once("dbconnect.php");
					if(isset($_POST["l_send"])){
						session_start();
						if(isset($_POST["login"])){
							$login = $_POST["login"]; 
							if($login==""){
								unset($login);
								}
							} 
						if(isset($_POST["password"])){
							$password = $_POST["password"]; 
							if($password==""){
								unset($password);
								}
							}
						
							if(empty($login) or empty($password)){
								echo "<font color=\"red\">Не все поля заполнены</font>"; 
								exit();
								}
						//если логин и пароль введены,то обрабатываем их, чтобы теги и скрипты не работали, мало ли что люди могут ввести
						$login = stripslashes($login);
						$login = htmlspecialchars($login);
						$password = stripslashes($password);
						$password = htmlspecialchars($password);
						//удаляем лишние пробелы
						$login = trim($login);
						$password = trim($password);
						//извлекаем из базы все данные о пользователе с введенным логином
						$result = $dbcon_rw->query("SELECT * FROM users WHERE login='$login'");
						$myrow = $result->fetch_array();
						if (empty($myrow["password"])){	//если пользователя с введенным логином не существует
							echo "<font color=\"red\">Неверный логин или пароль</font>";
							exit();
							$_SESSION['dostup'] = false; 
							}
						else{
							if($myrow["password"]==$password){
								$_SESSION['name']=ucfirst($myrow["name"]);
								$_SESSION['login']=$myrow["login"]; 
								$_SESSION['password']=$myrow["password"];
								$_SESSION['id']=$myrow["id"];
								$id = $_SESSION['id'];
								$_SESSION['dostup'] = true; 
								$result = $dbcon_rw->query("SELECT id FROM users WHERE login='".$_SESSION['login']."' AND dostup='admin';");
								$admin = $result->num_rows;
								if($admin<>0 AND $id = $result){
									$_SESSION['admindostup'] = true;
								}else{
									$_SESSION['admindostup'] = false;
								}
								header("Location:sqlex.php"); 
							}else{	//если пароли не сошлись
								$_SESSION['dostup'] = false; 
								$_SESSION['admindostup'] = false;
								echo "<font color=\"red\">Вы ввели неверный пароль</font>";
								exit();
								}
							}	
						}?>