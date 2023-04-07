<?php 
		@session_start();
	
		//session_unregister("strGloUsername");
		// session_unregister("strGloPassword");
		isset($_SESSION["strGloUsername"]);
		isset($_SESSION["strGloPassword"]);
		// session_register("strGloUsername");
		// session_register("strGloPassword");

		$sup_username=@$_POST["sup_username2"];
		$sup_password=@$_POST["sup_password2"];
		$sup_username = strtoupper($sup_username);
		$sup_password = strtoupper($sup_password);

		$_SESSION["strGloUsername"]= $sup_username;
		$_SESSION["strGloPassword"]= $sup_password;
		$boolLogin = "1";

		include("../include_RedThemes/MSSQLServer_connect_2.php");
		$boolLogin = "";

		if(!$conn){
				echo "login=0";
		}else
		{
			//$sup_username = 'rd_20002';
				$txt_empno = "select s.empno,ISNULL(r.program_role,'Sales') roles_user,e.deptno
											from sup_user s
											left join (select * from program_role r where program_name='pr_po') r on s.empno=r.empno,
											emp e 
											where s.empno = e.empno
											and s.sup_username='$sup_username'";
										
				$cur_empno=odbc_exec($conn,$txt_empno);
				$empno=odbc_result($cur_empno,"empno");		
			    $deptno=odbc_result($cur_empno,"deptno");	
				$roles_user=odbc_result($cur_empno,"roles_user");		

				if($empno  == ""){
						echo "login=0";
				}else{			
						isset($_SESSION["valid_userprpo"]);
						$_SESSION["valid_userprpo"] = $sup_username;		

						isset($_SESSION["empno_user"]);
						$_SESSION["empno_user"] = $empno;

						isset($_SESSION["ses_deptno"]);
						$_SESSION["ses_deptno"] = $deptno;

						isset($_SESSION["roles_user"]);
						isset($_SESSION["ses_Search"]);
						$_SESSION["ses_Search"] = "";

						isset($_SESSION["ses_poCheck"]);
						$_SESSION["ses_poCheck"] = "";

						isset($_SESSION["ses_poImport"]);
						$_SESSION["ses_poImport"] = "";

					 $_SESSION["roles_user"] = $roles_user;
						
						if ($empno == '14002'){
								$_SESSION["roles_user"] = "MNG_MS";
						}

						// session_register("menuchoice");
						isset($_SESSION["menuchoice"]);
						$_SESSION["menuchoice"] = '';						
					 	echo "login=1";
				}
		 }
		
?>
