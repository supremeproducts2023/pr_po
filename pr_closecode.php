<?
  	@session_start();
	if(session_is_registered("valid_userprpo")) {	
		require_once("../include_RedThemes/odbc_connect.php");				
		$empno_user = $_SESSION["empno_user"];
	
		$pr_no=@$_GET["pr_no"];
		$flag_status = @$_GET["flag_status"];
			if($flag_status=="5"){
					$str_count = "select count(*) from pr_and_po where pr_no='$pr_no'";			
					$cur_count = @odbc_exec($conn,$str_count);
					$count = @odbc_result($cur_count, "count(*)");

					if($count==0){
							include("./pr_search.php");						
							echo '<script language="JavaScript" type="text/JavaScript">';
							echo 'alert ("�س��ͧ����Ţ��� PO �����觫��͡�͹ �֧������ö�������觫�������");';
							echo '</script>';
					}else{		
					
							$txt_up = "update pr_master set pr_status='5',ms_user='$empno_user' where pr_no='$pr_no'";
							$exe_up = odbc_exec($conn,$txt_up);
							$exe_commit = odbc_exec($conn,"commit");
										
							echo '<script language="JavaScript" type="text/JavaScript">';
							echo 'alert ("�ѹ�֡����͡ PO �ͧ PR �����Ţ : '.$pr_no.' ���Ǥ��");';
							echo '</script>';
							include("./pr_search.php");						
					}
			}else if($flag_status=="6"){
						$txt_up = "update pr_master set pr_status='6',ms_user='$empno_user' where pr_no='$pr_no'";
						$exe_up = odbc_exec($conn,$txt_up);
						$exe_commit = odbc_exec($conn,"commit");
									
						echo '<script language="JavaScript" type="text/JavaScript">';
						echo 'alert ("�ѹ�֡����Ѻ����Թ��Ҩҡ Supplier �ͧ PR �����Ţ : '.$pr_no.' ���Ǥ��");';
						echo '</script>';
						include("./pr_search.php");						
						
			}else if($flag_status=="7"){
						$strDelete = "delete from pr_and_po where pr_no='$pr_no'";
						$exeDelete = odbc_exec($conn,$strDelete);
			
						$txt_up = "update pr_master set pr_status='7',approve_date=getdate(),ms_user='$empno_user' where pr_no='$pr_no'";
						$exe_up = odbc_exec($conn,$txt_up);
						$exe_commit = odbc_exec($conn,"commit");

						echo '<script language="JavaScript" type="text/JavaScript">';
						echo 'alert ("�ա�Ѻ PR �����Ţ : '.$pr_no.' ��Ѻ��� ���᷹���º�������Ǥ��");';
						echo '</script>';									
						include("./pr_search.php");						
			}
	}else{
		include("../include_RedThemes/SessionTimeOut.php");
	}
?>
<html><head>
<title>PR REPORT</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
</head>
<body></body>
</html>	