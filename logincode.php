<?
		@session_start();
	
		session_unregister("strGloUsername");
		session_unregister("strGloPassword");
		session_register("strGloUsername");
		session_register("strGloPassword");

		$sup_username=@$_POST["sup_username2"];
		$sup_password=@$_POST["sup_password2"];
		$sup_username = strtoupper($sup_username);
		$sup_password = strtoupper($sup_password);

		$_SESSION["strGloUsername"]= $sup_username;
		$_SESSION["strGloPassword"]= $sup_password;
		$boolLogin = "1";
		include("../include_RedThemes/odbc_connect.php");
		$boolLogin = "";

		if(!$conn){
				echo "login=0";
		}else
		{
				$txt_empno = "select s.empno,ISNULL(r.program_role,'Sales') roles_user,e.deptno
											from sup_user s,(select * from program_role r where program_name='pr_po') r,emp e
											where s.empno=r.empno(+)
											and s.empno = e.empno
											and s.sup_username='$sup_username'";
				$cur_empno=odbc_exec($conn,$txt_empno);
				$empno=odbc_result($cur_empno,"empno");		
				$deptno=odbc_result($cur_empno,"deptno");	
				$roles_user=odbc_result($cur_empno,"roles_user");		

				if($empno  == ""){
						echo "login=0";
				}else{				
						session_register("valid_userprpo");
						$_SESSION["valid_userprpo"] = $sup_username;								
						session_register("empno_user");
						$_SESSION["empno_user"] = $empno;
						session_register("ses_deptno");
						$_SESSION["ses_deptno"] = $deptno;
						session_register("roles_user");
						session_register("ses_Search");
						session_register("ses_poCheck");
						session_register("ses_poImport");

						$_SESSION["roles_user"] = $roles_user;
						
						if ($empno == '14002'){
								$_SESSION["roles_user"] = "MNG_MS";
						}

						session_register("menuchoice");
						$_SESSION["menuchoice"] = '';						
						echo "login=1";
				}
		
			
		}
		
?>
