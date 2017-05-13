<?function changehead(){
	echo 'border-color:'.$_SESSION['color'].'; background-color:'.$_SESSION['color'].';';
}
function changebody(){
	echo 'border-color:'.$_SESSION['color'].';';
}
function textareastyle(){
	echo 'style="margin-right:1%;width: 49%;float: left;resize:none;paddind:10px;"';
}?>