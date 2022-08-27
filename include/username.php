<?
  	session_start();		
		$valid_userprpo=@$_SESSION["strGloUsername"];
		$empno_user=@$_SESSION["empno_user"];
		$roles_user=@$_SESSION["roles_user"];
		
		echo "valid_user=$valid_userprpo&roles_user=$roles_user&empno_user=$empno_user";
?>

